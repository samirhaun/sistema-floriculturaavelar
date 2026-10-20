<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Coordena uma NFC-e a partir de um pedido ja pago. */
class Nfce_service {

    private $CI;
    private $fiscal;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->config->load('fiscal');
        $this->fiscal = $this->CI->config->item('fiscal');
        $this->CI->load->model('loja/notas_fiscais_model');
        $this->CI->load->library('focus_nfe');
    }

    public function emitir_pedido($pedido_id)
    {
        $pedido_id = (int) $pedido_id;
        if (!$pedido_id) throw new RuntimeException('Pedido nao informado.');
        if (empty($this->fiscal['enabled'])) throw new RuntimeException('Emissao fiscal esta desabilitada.');
        if (($this->fiscal['documento_padrao'] ?? null) !== 'nfce') throw new RuntimeException('Documento fiscal configurado nao e NFC-e.');
        if (!$this->CI->notas_fiscais_model->pedido_pronto_para_emitir($pedido_id)) {
            throw new RuntimeException('A NFC-e so pode ser emitida apos a confirmacao de todas as parcelas.');
        }

        $nota = $this->CI->notas_fiscais_model->buscar_por_pedido($pedido_id);
        if ($nota && $nota->status === 'autorizada') return $nota;
        $nota_id = $nota ? (int) $nota->id : $this->CI->notas_fiscais_model->criar_pendente($pedido_id, $this->fiscal['ambiente']);
        if (!$nota_id) throw new RuntimeException('Nao foi possivel criar a pendencia fiscal do pedido.');
        $nota = $this->CI->notas_fiscais_model->buscar_por_pedido($pedido_id);

        try {
            list($payload, $itens, $pagamentos, $totais) = $this->montar_payload($pedido_id);
            $this->CI->notas_fiscais_model->salvar_detalhes($nota_id, $pagamentos, $itens);
            $this->CI->notas_fiscais_model->atualizar($nota_id, array(
                'status' => 'enviando',
                'tentativa' => (int) $nota->tentativa + 1,
                'payload_json' => json_encode($payload, JSON_UNESCAPED_UNICODE),
                'valor_produtos' => $totais['produtos'],
                'valor_frete' => $totais['frete'],
                'valor_desconto' => $totais['desconto'],
                'valor_total' => $totais['total'],
                'erro' => null,
            ));
            $resposta = $this->CI->focus_nfe->emitir_nfce($nota->referencia, $payload);
            $corpo = $resposta['resposta'];
            $autorizada = $resposta['status_http'] >= 200 && $resposta['status_http'] < 300
                && ($corpo['status'] ?? null) === 'autorizado'
                && (string) ($corpo['status_sefaz'] ?? '') === '100';

            $dados = array(
                'status' => $autorizada ? 'autorizada' : 'rejeitada',
                'numero' => $corpo['numero'] ?? null,
                'serie' => $corpo['serie'] ?? null,
                'chave_acesso' => $corpo['chave_nfe'] ?? null,
                'protocolo' => $corpo['protocolo'] ?? null,
                'xml_url' => $corpo['caminho_xml_nota_fiscal'] ?? null,
                'danfe_url' => $corpo['caminho_danfe'] ?? null,
                'resposta_json' => json_encode($corpo, JSON_UNESCAPED_UNICODE),
                'erro' => $autorizada ? null : ($corpo['mensagem_sefaz'] ?? 'NFC-e rejeitada pela Focus/SEFAZ.'),
                'autorizada_em' => $autorizada ? date('Y-m-d H:i:s') : null,
            );
            $this->CI->notas_fiscais_model->atualizar($nota_id, $dados);
        } catch (Exception $e) {
            $this->CI->notas_fiscais_model->atualizar($nota_id, array('status' => 'erro', 'erro' => $e->getMessage()));
            throw $e;
        }

        return $this->CI->notas_fiscais_model->buscar_por_pedido($pedido_id);
    }

    private function montar_payload($pedido_id)
    {
        $pedido = $this->CI->db->select('pedidos.*, clientes.nome AS cliente_nome, clientes.cpf AS cliente_cpf')
            ->from('pedidos')->join('clientes', 'clientes.id = pedidos.clientes_id', 'left')
            ->where('pedidos.id', $pedido_id)->get()->row();
        if (!$pedido) throw new RuntimeException('Pedido nao encontrado.');

        $produtos = $this->CI->db->select('pedido_produto.id AS pedido_produto_id, pedido_produto.produtos_id, pedido_produto.quantidade, pedido_produto.valor_total AS valor_item, produtos.titulo, produtos.nfce_ncm, produtos.nfce_csosn, produtos.nfce_pis_cst, produtos.nfce_cofins_cst, produtos.nfce_cfop, grupos_fiscais_nfce.ncm AS grupo_ncm, grupos_fiscais_nfce.csosn AS grupo_csosn, grupos_fiscais_nfce.pis_cst AS grupo_pis, grupos_fiscais_nfce.cofins_cst AS grupo_cofins')
            ->from('pedido_produto')->join('produtos', 'produtos.id = pedido_produto.produtos_id')
            ->join('grupos_fiscais_nfce', 'grupos_fiscais_nfce.id = produtos.grupos_fiscais_nfce_id', 'left')
            ->where('pedido_produto.pedidos_id', $pedido_id)->get()->result();
        if (empty($produtos)) throw new RuntimeException('Pedido sem produtos para emissao fiscal.');

        // A NFC-e (modelo 65) so vale para operacao interna. Entrega em outra UF
        // exige NF-e modelo 55, entao a emissao para antes de tomar rejeicao.
        $uf_emitente = strtoupper((string) $this->estabelecimento('uf'));
        $uf_entrega = strtoupper(trim((string) $pedido->estado_entrega));
        $tem_entrega = trim((string) $pedido->rua_entrega) !== '';
        if ($uf_entrega !== '' && $uf_entrega !== $uf_emitente) {
            throw new RuntimeException('A NFC-e so vale para operacao dentro de '.$uf_emitente
                .'. Este pedido tem entrega em '.$uf_entrega.' e precisa de NF-e modelo 55.');
        }
        // Primeira passagem: valida a regra fiscal de cada produto e apura a
        // base do rateio antes de montar o payload.
        $linhas = array();
        $bases = array();
        $total_itens = 0.0;
        foreach ($produtos as $produto) {
            $regra = $this->regra_produto($produto);
            if (!$regra) throw new RuntimeException('Produto sem classificacao fiscal: '.$produto->titulo.'. Cadastre sua regra antes de emitir.');
            $quantidade = max((float) $produto->quantidade, 0.0001);
            $total = round((float) $produto->valor_item, 2);
            if ($total <= 0) throw new RuntimeException('Produto com valor invalido: '.$produto->titulo.'.');
            $linhas[] = array('produto' => $produto, 'regra' => $regra, 'quantidade' => $quantidade, 'total' => $total);
            $bases[] = $total;
            $total_itens += $total;
        }
        $total_itens = round($total_itens, 2);

        $valor_frete = round((float) $pedido->valor_frete, 2);
        $valor_desconto = $this->desconto_em_reais($pedido, $total_itens);
        if ($valor_desconto > $total_itens) {
            throw new RuntimeException('Desconto maior que o valor dos produtos do pedido.');
        }
        // Frete e desconto sao do pedido, mas a SEFAZ os quer por item. O
        // rateio proporcional evita desconto maior que o valor do proprio item.
        $rateio_frete = $this->ratear($valor_frete, $bases);
        $rateio_desconto = $this->ratear($valor_desconto, $bases);

        $itens_payload = array();
        $itens_registro = array();
        foreach ($linhas as $indice => $linha) {
            $produto = $linha['produto'];
            $regra = $linha['regra'];
            $total = $linha['total'];
            $unitario = round($total / $linha['quantidade'], 10);
            $item_payload = array(
                'numero_item' => (string) ($indice + 1),
                'codigo_produto' => (string) $produto->produtos_id,
                'descricao' => $produto->titulo,
                'codigo_ncm' => $regra['ncm'], 'cfop' => $regra['cfop'],
                'unidade_comercial' => 'UN', 'quantidade_comercial' => number_format($linha['quantidade'], 4, '.', ''),
                'valor_unitario_comercial' => number_format($unitario, 10, '.', ''),
                'valor_bruto' => number_format($total, 2, '.', ''),
                'icms_origem' => '0', 'icms_situacao_tributaria' => $regra['csosn'],
                'pis_situacao_tributaria' => $regra['pis'], 'cofins_situacao_tributaria' => $regra['cofins'],
            );
            if ($rateio_frete[$indice] > 0) $item_payload['valor_frete'] = number_format($rateio_frete[$indice], 2, '.', '');
            if ($rateio_desconto[$indice] > 0) $item_payload['valor_desconto'] = number_format($rateio_desconto[$indice], 2, '.', '');
            $itens_payload[] = $item_payload;
            $itens_registro[] = array('pedido_produto_id' => $produto->pedido_produto_id, 'produtos_id' => (int) $produto->produtos_id,
                'descricao' => $produto->titulo, 'quantidade' => $linha['quantidade'], 'valor_unitario' => $unitario,
                'valor_total' => $total, 'valor_frete' => $rateio_frete[$indice], 'valor_desconto' => $rateio_desconto[$indice],
                'ncm' => $regra['ncm'], 'cfop' => $regra['cfop'], 'unidade' => 'UN', 'tributacao_json' => json_encode($regra));
        }

        $contas = $this->CI->db->select('forma_pgto, valor')->from('contas_receber')
            ->where('pedidos_id', $pedido_id)->where('status', 1)->get()->result();
        $pagamentos_payload = array(); $pagamentos_registro = array(); $total_pagamentos = 0.0;
        foreach ($contas as $conta) {
            $forma = $this->forma_pagamento_focus((int) $conta->forma_pgto);
            $valor = round((float) $conta->valor, 2);
            if ($valor <= 0) continue;
            $pagamento_payload = array('indicador_pagamento' => '0', 'forma_pagamento' => $forma,
                'valor_pagamento' => number_format($valor, 2, '.', ''));
            if (in_array($forma, array('03', '04'), true)) $pagamento_payload['tipo_integracao'] = '2';
            $pagamentos_payload[] = $pagamento_payload;
            $pagamentos_registro[] = array('forma_pgto' => (int) $conta->forma_pgto, 'valor' => $valor);
            $total_pagamentos += $valor;
        }
        if (empty($pagamentos_payload)) throw new RuntimeException('Pedido sem pagamento confirmado.');
        $total_documento = round($total_itens + $valor_frete - $valor_desconto, 2);
        if (abs($total_pagamentos - $total_documento) > 0.01) {
            throw new RuntimeException('Total pago diverge do total fiscal do pedido. Revise produtos, frete, desconto e parcelas.');
        }

        $payload = array(
            'cnpj_emitente' => $this->cnpj_emitente(),
            'data_emissao' => (new DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo')))->format(DateTime::ATOM),
            'natureza_operacao' => 'VENDA AO CONSUMIDOR', 'tipo_documento' => '1', 'local_destino' => '1',
            'finalidade_emissao' => '1', 'consumidor_final' => '1', 'presenca_comprador' => $tem_entrega ? '4' : '1',
            // Quem entrega e' a loja, entao o frete e' por conta do emitente.
            'modalidade_frete' => $valor_frete > 0 ? '0' : '9',
            'items' => $itens_payload, 'formas_pagamento' => $pagamentos_payload,
            'informacoes_adicionais_contribuinte' => $this->fiscal['informacoes_adicionais_padrao'],
        );

        $documento = preg_replace('/\D/', '', (string) $pedido->cliente_cpf);
        if (strlen($documento) === 11) {
            $payload['cpf_destinatario'] = $documento;
        } elseif (strlen($documento) === 14) {
            $payload['cnpj_destinatario'] = $documento;
        }
        if (isset($payload['cpf_destinatario']) || isset($payload['cnpj_destinatario'])) {
            $payload['nome_destinatario'] = $pedido->cliente_nome;
            $payload['indicador_inscricao_estadual_destinatario'] = '9';
        }

        // Entrega em domicilio (presenca 4) exige destinatario identificado e o
        // endereco dele no documento -- a SEFAZ rejeita com "NFC-e de entrega a
        // domicilio sem o endereco do destinatario" quando so vai o grupo de
        // entrega. O endereco de entrega do pedido e' o endereco do destinatario.
        // Alem disso a SEFAZ rejeita (codigo 786) sem o grupo de transportador;
        // como a loja faz entrega propria, o emitente e' tambem o transportador.
        if ($tem_entrega) {
            if (!isset($payload['cpf_destinatario']) && !isset($payload['cnpj_destinatario'])) {
                // Excecao exclusiva do utilitario descartavel de homologacao:
                // emite como consumidor nao identificado, sem declarar o grupo
                // de entrega. A configuracao nunca existe no fluxo web normal.
                if (($this->fiscal['ambiente'] ?? null) === 'homologacao'
                    && !empty($this->fiscal['homologacao_consumidor_nao_identificado'])) {
                    $payload['presenca_comprador'] = '1';
                    return array($payload, $itens_registro, $pagamentos_registro,
                        array('produtos' => $total_itens, 'frete' => $valor_frete,
                            'desconto' => $valor_desconto, 'total' => $total_documento));
                }
                throw new RuntimeException('Entrega em domicilio exige CPF ou CNPJ do cliente na NFC-e. Cadastre o documento do cliente antes de emitir.');
            }
            $payload = array_merge($payload, $this->endereco_entrega($pedido, $uf_emitente));
            $payload = array_merge($payload, $this->transportador_proprio());
        }

        $totais = array('produtos' => $total_itens, 'frete' => $valor_frete,
            'desconto' => $valor_desconto, 'total' => $total_documento);
        return array($payload, $itens_registro, $pagamentos_registro, $totais);
    }

    /**
     * O pedido guarda o desconto em reais ou em percentual, conforme
     * tipo_desconto. O percentual incide somente sobre os produtos: o frete
     * entra depois do desconto (ver Site_pedidos::salvar_pedido).
     */
    private function desconto_em_reais($pedido, $total_itens)
    {
        $valor = (float) $pedido->valor_desconto;
        if ($valor <= 0) return 0.0;
        if (strtolower(trim((string) $pedido->tipo_desconto)) === 'porcentagem') {
            return round($total_itens * $valor / 100, 2);
        }
        return round($valor, 2);
    }

    /** Distribui um valor do pedido entre os itens, proporcional ao valor de cada um. */
    private function ratear($valor, array $bases)
    {
        $rateio = array_fill(0, count($bases), 0.0);
        $total_base = array_sum($bases);
        if ($valor <= 0 || $total_base <= 0) return $rateio;

        $acumulado = 0.0;
        foreach ($bases as $indice => $base) {
            $rateio[$indice] = round($valor * $base / $total_base, 2);
            $acumulado += $rateio[$indice];
        }
        // O residuo dos arredondamentos vai para o item de maior valor, para que
        // a soma dos itens continue fechando com o total do pedido.
        $residuo = round($valor - $acumulado, 2);
        if (abs($residuo) >= 0.01) {
            $maior = array_search(max($bases), $bases, true);
            $rateio[$maior] = round($rateio[$maior] + $residuo, 2);
        }
        return $rateio;
    }

    private function endereco_entrega($pedido, $uf_emitente)
    {
        $municipio = trim((string) $pedido->cidade_entrega);
        $logradouro = trim((string) $pedido->rua_entrega);
        $bairro = trim((string) $pedido->bairro_entrega);
        if ($logradouro === '' || $bairro === '' || $municipio === '') {
            throw new RuntimeException('Endereco de entrega incompleto para a NFC-e: informe rua, bairro e cidade.');
        }

        $endereco = array(
            'logradouro_destinatario' => $logradouro,
            'numero_destinatario' => trim((string) $pedido->numero_entrega) ?: 'S/N',
            'bairro_destinatario' => $bairro,
            'municipio_destinatario' => $municipio,
            'codigo_municipio_destinatario' => $this->codigo_municipio($municipio),
            'uf_destinatario' => $uf_emitente,
        );
        $complemento = trim((string) $pedido->complemento_entrega);
        if ($complemento !== '') $endereco['complemento_destinatario'] = $complemento;
        $cep = preg_replace('/\D/', '', (string) $pedido->cep_entrega);
        if (strlen($cep) === 8) $endereco['cep_destinatario'] = $cep;

        return $endereco;
    }

    /**
     * Retorna os campos do grupo de transportador preenchidos com os dados do
     * proprio estabelecimento. Necessario em NFC-e de entrega a domicilio
     * (presenca 4): a SEFAZ rejeita (codigo 786) sem o grupo de transportador,
     * mesmo quando a modalidade de frete e' 9 (sem frete no documento).
     */
    private function transportador_proprio()
    {
        $est = isset($this->fiscal['estabelecimento']) ? $this->fiscal['estabelecimento'] : array();
        $cnpj = preg_replace('/\D/', '', (string) (isset($est['cnpj']) ? $est['cnpj'] : ''));
        $logradouro = trim((string) (isset($est['logradouro']) ? $est['logradouro'] : ''));
        $numero = trim((string) (isset($est['numero']) ? $est['numero'] : ''));
        $bairro = trim((string) (isset($est['bairro']) ? $est['bairro'] : ''));
        $partes = array_filter(array($logradouro, $numero, $bairro));
        $endereco = substr(implode(', ', $partes), 0, 60);
        return array(
            'cnpj_transportador'               => $cnpj,
            'nome_transportador'               => trim((string) (isset($est['razao_social']) ? $est['razao_social'] : '')),
            'inscricao_estadual_transportador'  => trim((string) (isset($est['inscricao_estadual']) ? $est['inscricao_estadual'] : '')),
            'endereco_transportador'           => $endereco,
            'municipio_transportador'          => trim((string) (isset($est['municipio']) ? $est['municipio'] : '')),
            'uf_transportador'                 => trim((string) (isset($est['uf']) ? $est['uf'] : '')),
        );
    }

    /** O codigo IBGE do municipio e' obrigatorio no grupo de entrega. */
    private function codigo_municipio($municipio)
    {
        $mapa = $this->fiscal['municipios_ibge'];
        $chave = $this->normalizar($municipio);
        if (!isset($mapa[$chave])) {
            throw new RuntimeException('Municipio de entrega sem codigo IBGE cadastrado: '.$municipio
                .'. Adicione em application/config/fiscal.php (municipios_ibge).');
        }
        return (string) $mapa[$chave];
    }

    private function normalizar($texto)
    {
        $texto = iconv('UTF-8', 'ASCII//TRANSLIT', (string) $texto);
        return strtoupper(trim(preg_replace('/\s+/', ' ', $texto)));
    }

    private function estabelecimento($campo)
    {
        $dados = isset($this->fiscal['estabelecimento']) ? $this->fiscal['estabelecimento'] : array();
        if (empty($dados[$campo])) {
            throw new RuntimeException('Dado fiscal do estabelecimento nao configurado: '.$campo.'.');
        }
        return $dados[$campo];
    }

    private function cnpj_emitente()
    {
        $cadastrado = preg_replace('/\D/', '', (string) $this->estabelecimento('cnpj'));
        if (strlen($cadastrado) !== 14) {
            throw new RuntimeException('CNPJ do estabelecimento fiscal nao foi configurado corretamente.');
        }

        // O cadastro fiscal e' a fonte da NFC-e e do SINTEGRA. A variavel de
        // ambiente e' aceita apenas como dupla conferencia opcional: exigir
        // os dois campos fazia a homologacao ficar bloqueada mesmo com o
        // emitente ja configurado no sistema.
        $cnpj_ambiente = preg_replace('/\D/', '', (string) getenv('FOCUS_NFE_CNPJ_EMITENTE'));
        if ($cnpj_ambiente !== '' && $cnpj_ambiente !== $cadastrado) {
            throw new RuntimeException('FOCUS_NFE_CNPJ_EMITENTE diverge do CNPJ do estabelecimento em config/fiscal.php.');
        }
        return $cadastrado;
    }

    private function regra_produto($produto)
    {
        if (!empty($produto->nfce_ncm) && !empty($produto->nfce_csosn)
            && !empty($produto->nfce_pis_cst) && !empty($produto->nfce_cofins_cst)) {
            return array('ncm' => $produto->nfce_ncm, 'csosn' => $produto->nfce_csosn,
                'pis' => $produto->nfce_pis_cst, 'cofins' => $produto->nfce_cofins_cst,
                'cfop' => $produto->nfce_cfop ?: '5102');
        }
        if (!empty($produto->grupo_ncm)) {
            return array('ncm' => $produto->grupo_ncm, 'csosn' => $produto->grupo_csosn,
                'pis' => $produto->grupo_pis, 'cofins' => $produto->grupo_cofins,
                'cfop' => '5102');
        }
        return null;
    }

    private function forma_pagamento_focus($forma)
    {
        $mapa = array(1 => '01', 2 => '04', 3 => '03', 4 => '03', 5 => '03', 6 => '03', 7 => '14', 8 => '02', 9 => '17');
        if (!isset($mapa[$forma])) throw new RuntimeException('Forma de pagamento sem mapeamento fiscal.');
        return $mapa[$forma];
    }
}
