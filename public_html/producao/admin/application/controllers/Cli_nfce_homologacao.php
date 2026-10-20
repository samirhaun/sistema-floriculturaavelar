<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Utilitario descartavel para homologar NFC-e de vendas de um mes anterior.
 *
 * Uso (a partir de public_html/producao/admin):
 *   php cli_nfce_homologacao.php conferir 2026-07
 *   php cli_nfce_homologacao.php emitir 2026-07 EMITIR-HOMOLOGACAO
 *   php cli_nfce_homologacao.php sintegra 2026-08
 */
class Cli_nfce_homologacao extends CI_Controller
{
    private $fiscal;

    public function __construct()
    {
        parent::__construct();
        fwrite(STDERR, "Iniciando utilitario fiscal em homologacao...\n");
        if (!$this->input->is_cli_request()) show_error('Comando disponivel somente via terminal.', 403);

        $this->config->load('fiscal');
        $this->fiscal = $this->config->item('fiscal');
        $this->validar_homologacao();

        // Habilita apenas nesta execucao PHP. O arquivo fiscal.php continua
        // desligado e nenhuma venda nova passa a emitir automaticamente.
        $this->fiscal['enabled'] = true;
        $this->fiscal['ambiente'] = 'homologacao';
        $this->fiscal['emitir_automaticamente'] = false;
        $this->fiscal['homologacao_consumidor_nao_identificado'] = true;
        $this->config->set_item('fiscal', $this->fiscal);
        $this->load->model('loja/notas_fiscais_model');
        $this->load->library('focus_nfe');
        $this->load->library('nfce_service');
    }

    public function index()
    {
        $this->saida('Informe preparar_banco, conferir, emitir, produtos_pendentes ou sintegra.');
    }

    public function preparar_banco($confirmacao = null)
    {
        if ($confirmacao !== 'APLICAR-MIGRATION-012') {
            throw new RuntimeException('Confirme com APLICAR-MIGRATION-012.');
        }
        $alteracoes = array();
        foreach (array(
            'valor_produtos' => 'ADD COLUMN valor_produtos DECIMAL(10,2) NULL AFTER protocolo',
            'valor_frete' => 'ADD COLUMN valor_frete DECIMAL(10,2) NULL AFTER valor_produtos',
            'valor_desconto' => 'ADD COLUMN valor_desconto DECIMAL(10,2) NULL AFTER valor_frete',
            'valor_total' => 'ADD COLUMN valor_total DECIMAL(10,2) NULL AFTER valor_desconto',
        ) as $coluna => $sql) {
            if (!$this->db->field_exists($coluna, 'notas_fiscais')) $alteracoes[] = 'ALTER TABLE notas_fiscais '.$sql;
        }
        foreach (array(
            'produtos_id' => 'ADD COLUMN produtos_id INT NULL AFTER pedido_produto_id',
            'valor_frete' => 'ADD COLUMN valor_frete DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER valor_total',
            'valor_desconto' => 'ADD COLUMN valor_desconto DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER valor_frete',
        ) as $coluna => $sql) {
            if (!$this->db->field_exists($coluna, 'notas_fiscais_itens')) $alteracoes[] = 'ALTER TABLE notas_fiscais_itens '.$sql;
        }
        foreach ($alteracoes as $sql) {
            if (!$this->db->query($sql)) throw new RuntimeException('Falha ao aplicar: '.$sql);
            $this->saida('Aplicado: '.$sql);
        }
        if (!$this->indice_existe('notas_fiscais', 'idx_notas_fiscais_autorizada_em')) {
            $this->db->query('ALTER TABLE notas_fiscais ADD KEY idx_notas_fiscais_autorizada_em (autorizada_em)');
        }
        if (!$this->indice_existe('notas_fiscais_itens', 'idx_notas_fiscais_itens_produto')) {
            $this->db->query('ALTER TABLE notas_fiscais_itens ADD KEY idx_notas_fiscais_itens_produto (produtos_id)');
        }
        $this->saida('Banco fiscal pronto para a emissao e o SINTEGRA.');
    }

    public function conferir($mes = '2026-07')
    {
        list($inicio, $fim) = $this->periodo($mes);
        $pedidos = $this->pedidos($inicio, $fim);
        $aptos = 0; $bloqueados = 0; $ja_autorizados = 0; $total = 0.0;
        foreach ($pedidos as $pedido) {
            $resultado = $this->validar_pedido($pedido);
            if ($resultado['status'] === 'apto') {
                $aptos++; $total += $resultado['total'];
            } elseif ($resultado['status'] === 'autorizada') {
                $ja_autorizados++;
            } else {
                $bloqueados++;
            }
            $this->saida(sprintf('#%d | %s | R$ %.2f | %s', $pedido->id,
                $pedido->data_pedido, (float) $pedido->valor_total, $resultado['mensagem']));
        }
        $this->saida(sprintf('RESUMO: %d pedidos; %d aptos (R$ %.2f); %d bloqueados; %d ja autorizados.',
            count($pedidos), $aptos, $total, $bloqueados, $ja_autorizados));
    }

    public function emitir($mes = null, $confirmacao = null, $limite = null)
    {
        if (empty($this->fiscal['focus_nfe_token'])) {
            throw new RuntimeException('FOCUS_NFE_TOKEN nao configurado nesta execucao.');
        }
        if ($confirmacao !== 'EMITIR-HOMOLOGACAO') {
            throw new RuntimeException('Confirmacao ausente. Use EMITIR-HOMOLOGACAO como terceiro argumento.');
        }
        list($inicio, $fim) = $this->periodo($mes);
        $ok = 0; $falhas = 0; $ignorados = 0; $enviados = 0;
        foreach ($this->pedidos($inicio, $fim) as $pedido) {
            $validacao = $this->validar_pedido($pedido);
            if ($validacao['status'] === 'autorizada') {
                $ignorados++; $this->saida('#'.$pedido->id.' | ja autorizada'); continue;
            }
            if ($validacao['status'] !== 'apto') {
                $falhas++; $this->saida('#'.$pedido->id.' | BLOQUEADO | '.$validacao['mensagem']); continue;
            }
            if ($limite !== null && $enviados >= max(1, (int) $limite)) break;
            $enviados++;
            try {
                $nota = $this->emitir_um($pedido);
                if ($nota && $nota->status === 'autorizada') {
                    $ok++; $this->saida('#'.$pedido->id.' | AUTORIZADA | serie '.$nota->serie.' numero '.$nota->numero);
                } else {
                    $falhas++; $this->saida('#'.$pedido->id.' | REJEITADA | '.($nota->erro ?: 'sem mensagem'));
                }
            } catch (Exception $e) {
                $falhas++; $this->saida('#'.$pedido->id.' | ERRO | '.$e->getMessage());
            }
        }
        $this->saida(sprintf('FIM: %d autorizadas; %d falhas/bloqueios; %d ja existentes.', $ok, $falhas, $ignorados));
    }

    public function sintegra($mes = null, $destino = null)
    {
        list($inicio, $fim) = $this->periodo($mes);
        if (!$destino) $destino = realpath(FCPATH.'../..').'/SINTEGRA_HOMOLOGACAO_'.date('m_Y', strtotime($inicio)).'.txt';
        if (strpos($destino, '/') !== 0) throw new RuntimeException('Informe um caminho absoluto para o arquivo de saida.');
        $this->load->library('sintegra_service');
        $conteudo = $this->sintegra_service->gerar($inicio, $fim);
        if (file_put_contents($destino, $conteudo) === false) {
            throw new RuntimeException('Nao foi possivel gravar '.$destino.'.');
        }
        $this->saida('SINTEGRA de homologacao gravado em '.$destino.' ('.strlen($conteudo).' bytes).');
    }

    /**
     * Exporta os produtos que impedem a emissao mensal por ainda nao terem
     * classificacao fiscal efetiva. E' um relatorio de revisao, sem escrita
     * em pedidos, produtos ou documentos fiscais.
     */
    public function produtos_pendentes($mes = null, $destino = null)
    {
        list($inicio, $fim) = $this->periodo($mes);
        if (!$destino) {
            $destino = realpath(FCPATH.'../..').'/RELATORIO_PRODUTOS_FISCAIS_PENDENTES_'.date('m_Y', strtotime($inicio)).'.csv';
        }
        if (strpos($destino, '/') !== 0) throw new RuntimeException('Informe um caminho absoluto para o arquivo de saida.');

        $linhas = $this->db->select("produtos.id, produtos.titulo, categorias.nome AS categoria,
                sugestao.nome AS grupo_sugerido, produtos.grupo_fiscal_sugestao_confianca,
                COUNT(DISTINCT pedidos.id) AS pedidos_afetados,
                COUNT(*) AS linhas_vendidas, COALESCE(SUM(pedido_produto.quantidade), 0) AS unidades", false)
            ->from('pedidos')
            ->join('pedido_produto', 'pedido_produto.pedidos_id = pedidos.id')
            ->join('produtos', 'produtos.id = pedido_produto.produtos_id')
            ->join('categorias', 'categorias.id = produtos.categorias_id', 'left')
            ->join('grupos_fiscais_nfce sugestao', 'sugestao.id = produtos.grupo_fiscal_sugerido_id', 'left')
            ->where('pedidos.data_pedido >=', $inicio.' 00:00:00')
            ->where('pedidos.data_pedido <=', $fim.' 23:59:59')
            ->where_not_in('pedidos.status_pedido', array(4, 5))
            ->where('produtos.grupos_fiscais_nfce_id IS NULL', null, false)
            ->group_start()->where('produtos.nfce_ncm IS NULL', null, false)->or_where('produtos.nfce_ncm', '')->group_end()
            ->where("EXISTS (SELECT 1 FROM contas_receber cr_ok WHERE cr_ok.pedidos_id = pedidos.id AND cr_ok.status = 1 AND cr_ok.valor > 0 AND cr_ok.forma_pgto IN (2,3,4,5,6,7,8,9))", null, false)
            ->where("NOT EXISTS (SELECT 1 FROM contas_receber cr_pendente WHERE cr_pendente.pedidos_id = pedidos.id AND (cr_pendente.status <> 1 OR cr_pendente.valor <= 0))", null, false)
            ->group_by('produtos.id, produtos.titulo, categorias.nome, sugestao.nome, produtos.grupo_fiscal_sugestao_confianca')
            ->order_by('unidades', 'DESC')->get()->result();

        $arquivo = fopen($destino, 'wb');
        if (!$arquivo) throw new RuntimeException('Nao foi possivel gravar '.$destino.'.');
        // BOM + ponto e virgula deixam a abertura direta no Excel em PT-BR
        // previsivel, sem alterar acentos ou valores decimais.
        fwrite($arquivo, "\xEF\xBB\xBF");
        fputcsv($arquivo, array('produto_id', 'produto', 'categoria', 'grupo_sugerido', 'confianca', 'pedidos_afetados', 'linhas_vendidas', 'unidades'), ';');
        foreach ($linhas as $linha) {
            fputcsv($arquivo, array($linha->id, $linha->titulo, $linha->categoria, $linha->grupo_sugerido,
                $linha->grupo_fiscal_sugestao_confianca, $linha->pedidos_afetados, $linha->linhas_vendidas,
                number_format((float) $linha->unidades, 4, ',', '')), ';');
        }
        fclose($arquivo);
        $this->saida('RELATORIO: '.count($linhas).' produtos pendentes em '.$destino);
    }

    public function exportar_cupons($mes = null)
    {
        if (empty($this->fiscal['focus_nfe_token'])) throw new RuntimeException('FOCUS_NFE_TOKEN nao configurado.');
        list($inicio, $fim) = $this->periodo($mes);
        $destino = realpath(FCPATH.'../..').'/CUPONS_NFCE_HOMOLOGACAO_'.$mes;
        if (!is_dir($destino) && !mkdir($destino, 0770, true)) throw new RuntimeException('Nao foi possivel criar '.$destino);
        $notas = $this->db->select('notas_fiscais.pedidos_id, notas_fiscais.numero, notas_fiscais.serie, notas_fiscais.danfe_url')
            ->from('notas_fiscais')->join('pedidos', 'pedidos.id = notas_fiscais.pedidos_id')
            ->where('pedidos.data_pedido >=', $inicio.' 00:00:00')->where('pedidos.data_pedido <=', $fim.' 23:59:59')
            ->where('notas_fiscais.status', 'autorizada')->order_by('CAST(notas_fiscais.numero AS UNSIGNED)', 'ASC', false)
            ->get()->result();
        $ok = 0; $falhas = 0;
        foreach ($notas as $nota) {
            try {
                $nome = sprintf('NFCe-serie%s-numero%06d-pedido%d.html', $nota->serie, $nota->numero, $nota->pedidos_id);
                if (is_file($destino.'/'.$nome)) { $ok++; continue; }
                $arquivo = $this->focus_nfe->baixar_arquivo($nota->danfe_url);
                if (file_put_contents($destino.'/'.$nome, $arquivo['conteudo']) === false) throw new RuntimeException('falha ao gravar');
                $ok++;
            } catch (Exception $e) {
                $falhas++; $this->saida('#'.$nota->pedidos_id.' | '.$e->getMessage());
            }
        }
        $this->saida('EXPORTACAO: '.$ok.' cupons; '.$falhas.' falhas; pasta '.$destino);
    }

    public function diagnosticar($pedido_id = null)
    {
        $nota = $this->notas_fiscais_model->buscar_por_pedido((int) $pedido_id);
        if (!$nota) throw new RuntimeException('Nota nao encontrada.');
        $this->saida('status='.$nota->status.' referencia='.$nota->referencia.' erro='.$nota->erro);
        $this->saida('resposta='.$nota->resposta_json);
    }

    public function resumo_erros($mes = null)
    {
        list($inicio, $fim) = $this->periodo($mes);
        $linhas = $this->db->select('notas_fiscais.status, notas_fiscais.erro, COUNT(*) AS quantidade', false)
            ->from('notas_fiscais')->join('pedidos', 'pedidos.id = notas_fiscais.pedidos_id')
            ->where('pedidos.data_pedido >=', $inicio.' 00:00:00')->where('pedidos.data_pedido <=', $fim.' 23:59:59')
            ->where('notas_fiscais.status !=', 'autorizada')->group_by('notas_fiscais.status, notas_fiscais.erro')
            ->order_by('quantidade', 'DESC')->get()->result();
        foreach ($linhas as $linha) $this->saida($linha->quantidade.' | '.$linha->status.' | '.$linha->erro);
    }

    public function resumo_final($mes = null)
    {
        list($inicio, $fim) = $this->periodo($mes);
        $r = $this->db->select('COUNT(*) AS quantidade, SUM(notas_fiscais.valor_total) AS total,
                MIN(CAST(notas_fiscais.numero AS UNSIGNED)) AS numero_inicial,
                MAX(CAST(notas_fiscais.numero AS UNSIGNED)) AS numero_final', false)
            ->from('notas_fiscais')->join('pedidos', 'pedidos.id = notas_fiscais.pedidos_id')
            ->where('pedidos.data_pedido >=', $inicio.' 00:00:00')->where('pedidos.data_pedido <=', $fim.' 23:59:59')
            ->where('notas_fiscais.status', 'autorizada')->get()->row();
        $pendentes = $this->db->from('notas_fiscais')->join('pedidos', 'pedidos.id = notas_fiscais.pedidos_id')
            ->where('pedidos.data_pedido >=', $inicio.' 00:00:00')->where('pedidos.data_pedido <=', $fim.' 23:59:59')
            ->where('notas_fiscais.status !=', 'autorizada')->count_all_results();
        $this->saida(sprintf('AUTORIZADAS=%d TOTAL=%.2f NUMEROS=%d-%d NAO_AUTORIZADAS=%d',
            $r->quantidade, $r->total, $r->numero_inicial, $r->numero_final, $pendentes));
    }

    public function renovar_referencias($mes = null, $confirmacao = null)
    {
        if ($confirmacao !== 'RENOVAR-REJEITADAS') throw new RuntimeException('Confirmacao ausente.');
        list($inicio, $fim) = $this->periodo($mes);
        $notas = $this->db->select('notas_fiscais.*')->from('notas_fiscais')
            ->join('pedidos', 'pedidos.id = notas_fiscais.pedidos_id')
            ->where('pedidos.data_pedido >=', $inicio.' 00:00:00')->where('pedidos.data_pedido <=', $fim.' 23:59:59')
            ->where('notas_fiscais.status', 'rejeitada')->get()->result();
        foreach ($notas as $nota) {
            $nova = 'pedido'.$nota->pedidos_id.'nfceh'.((int) $nota->tentativa + 1);
            $this->notas_fiscais_model->atualizar($nota->id, array('referencia' => $nova,
                'status' => 'pendente', 'erro' => null, 'resposta_json' => null));
        }
        $this->saida(count($notas).' referencias rejeitadas renovadas para nova tentativa.');
    }

    public function reconciliar($mes = null, $confirmacao = null)
    {
        if ($confirmacao !== 'RECONCILIAR-HOMOLOGACAO') throw new RuntimeException('Confirmacao de reconciliacao ausente.');
        if (empty($this->fiscal['focus_nfe_token'])) throw new RuntimeException('FOCUS_NFE_TOKEN nao configurado.');
        list($inicio, $fim) = $this->periodo($mes);
        $notas = $this->db->select('notas_fiscais.*')->from('notas_fiscais')
            ->join('pedidos', 'pedidos.id = notas_fiscais.pedidos_id')
            ->where('pedidos.data_pedido >=', $inicio.' 00:00:00')
            ->where('pedidos.data_pedido <=', $fim.' 23:59:59')
            ->where('notas_fiscais.status !=', 'autorizada')->get()->result();
        $autorizadas = 0; $nao_autorizadas = 0;
        foreach ($notas as $nota) {
            try {
                $consulta = $this->focus_nfe->consultar_nfce($nota->referencia);
                $corpo = $consulta['resposta'];
                if (($corpo['status'] ?? null) === 'autorizado' && (string) ($corpo['status_sefaz'] ?? '') === '100') {
                    $this->notas_fiscais_model->atualizar($nota->id, array('status' => 'autorizada',
                        'numero' => $corpo['numero'] ?? null, 'serie' => $corpo['serie'] ?? null,
                        'chave_acesso' => $corpo['chave_nfe'] ?? null, 'protocolo' => $corpo['protocolo'] ?? null,
                        'xml_url' => $corpo['caminho_xml_nota_fiscal'] ?? null, 'danfe_url' => $corpo['caminho_danfe'] ?? null,
                        'resposta_json' => json_encode($corpo, JSON_UNESCAPED_UNICODE), 'erro' => null,
                        'autorizada_em' => date('Y-m-d H:i:s')));
                    $autorizadas++; $this->saida('#'.$nota->pedidos_id.' | RECONCILIADA | '.$corpo['numero']);
                } else {
                    $nao_autorizadas++;
                }
            } catch (Exception $e) {
                $nao_autorizadas++;
            }
        }
        $this->saida('RECONCILIACAO: '.$autorizadas.' autorizadas recuperadas; '.$nao_autorizadas.' ainda sem autorizacao.');
    }

    private function validar_pedido($pedido)
    {
        if ($pedido->nota_status === 'autorizada') return array('status' => 'autorizada', 'mensagem' => 'ja autorizada', 'total' => 0);
        if (in_array((int) $pedido->status_pedido, array(4, 5), true)) return array('status' => 'bloqueado', 'mensagem' => 'pedido cancelado', 'total' => 0);
        if (!$this->notas_fiscais_model->pedido_pronto_para_emitir((int) $pedido->id)) {
            return array('status' => 'bloqueado', 'mensagem' => 'pagamento nao elegivel ou ainda nao totalmente confirmado', 'total' => 0);
        }
        try {
            $resultado = $this->montar_payload((int) $pedido->id);
            return array('status' => 'apto', 'mensagem' => 'APTO', 'total' => (float) $resultado[3]['total']);
        } catch (Exception $e) {
            return array('status' => 'bloqueado', 'mensagem' => $e->getMessage(), 'total' => 0);
        }
    }

    private function emitir_um($pedido)
    {
        $pedido_id = (int) $pedido->id;
        $nota = $this->notas_fiscais_model->buscar_por_pedido($pedido_id);
        if ($nota && $nota->status === 'autorizada') return $nota;
        if (!$nota) {
            $agora = date('Y-m-d H:i:s');
            $this->db->insert('notas_fiscais', array('pedidos_id' => $pedido_id,
                'tipo_documento' => 'nfce', 'ambiente' => 'homologacao', 'provedor' => 'focus_nfe',
                'referencia' => 'pedido'.$pedido_id.'nfce', 'status' => 'pendente', 'tentativa' => 0,
                'created_at' => $agora, 'updated_at' => $agora));
            $nota = $this->notas_fiscais_model->buscar_por_pedido($pedido_id);
        }
        if (!$nota) throw new RuntimeException('Nao foi possivel criar a pendencia fiscal.');

        try {
            list($payload, $itens, $pagamentos, $totais) = $this->montar_payload($pedido_id);
            $this->notas_fiscais_model->salvar_detalhes($nota->id, $pagamentos, $itens);
            $this->notas_fiscais_model->atualizar($nota->id, array('status' => 'enviando',
                'tentativa' => (int) $nota->tentativa + 1, 'payload_json' => json_encode($payload, JSON_UNESCAPED_UNICODE),
                'valor_produtos' => $totais['produtos'], 'valor_frete' => $totais['frete'],
                'valor_desconto' => $totais['desconto'], 'valor_total' => $totais['total'], 'erro' => null));
            $resposta = $this->focus_nfe->emitir_nfce($nota->referencia, $payload);
            $corpo = $resposta['resposta'];
            $autorizada = $resposta['status_http'] >= 200 && $resposta['status_http'] < 300
                && ($corpo['status'] ?? null) === 'autorizado' && (string) ($corpo['status_sefaz'] ?? '') === '100';
            $this->notas_fiscais_model->atualizar($nota->id, array('status' => $autorizada ? 'autorizada' : 'rejeitada',
                'numero' => $corpo['numero'] ?? null, 'serie' => $corpo['serie'] ?? null,
                'chave_acesso' => $corpo['chave_nfe'] ?? null, 'protocolo' => $corpo['protocolo'] ?? null,
                'xml_url' => $corpo['caminho_xml_nota_fiscal'] ?? null, 'danfe_url' => $corpo['caminho_danfe'] ?? null,
                'resposta_json' => json_encode($corpo, JSON_UNESCAPED_UNICODE),
                'erro' => $autorizada ? null : ($corpo['mensagem_sefaz'] ?? 'NFC-e rejeitada.'),
                'autorizada_em' => $autorizada ? date('Y-m-d H:i:s') : null));
        } catch (Exception $e) {
            $this->notas_fiscais_model->atualizar($nota->id, array('status' => 'erro', 'erro' => $e->getMessage()));
            throw $e;
        }
        return $this->notas_fiscais_model->buscar_por_pedido($pedido_id);
    }

    /** Reusa exatamente o montador fiscal existente sem tornar sua API publica. */
    private function montar_payload($pedido_id)
    {
        $metodo = new ReflectionMethod('Nfce_service', 'montar_payload');
        $metodo->setAccessible(true);
        $resultado = $metodo->invoke($this->nfce_service, $pedido_id);
        $payload =& $resultado[0];
        $itens =& $resultado[1];
        $totais =& $resultado[3];

        // NFC-e com tpImp=6 rejeita vFrete. Para esta homologacao, o frete
        // compoe o valor comercial dos itens, preservando o total pago.
        if ((float) $totais['frete'] > 0) {
            foreach ($payload['items'] as $i => &$item) {
                $frete = isset($item['valor_frete']) ? (float) $item['valor_frete'] : 0.0;
                if ($frete > 0) {
                    $novo_total = round((float) $item['valor_bruto'] + $frete, 2);
                    $quantidade = (float) $item['quantidade_comercial'];
                    $item['valor_bruto'] = number_format($novo_total, 2, '.', '');
                    $item['valor_unitario_comercial'] = number_format($novo_total / $quantidade, 10, '.', '');
                    $itens[$i]['valor_total'] = $novo_total;
                    $itens[$i]['valor_unitario'] = $novo_total / (float) $itens[$i]['quantidade'];
                }
                // A simples presenca de vFrete, mesmo zerado em outro item,
                // aciona a rejeicao 753 no DANFE simplificado.
                unset($item['valor_frete']);
                $itens[$i]['valor_frete'] = 0;
            }
            unset($item);
            $totais['produtos'] = round((float) $totais['produtos'] + (float) $totais['frete'], 2);
            $totais['frete'] = 0.0;
            $payload['modalidade_frete'] = '9';
        }

        // Documento com 11/14 digitos, mas DV invalido, deve ser omitido na
        // NFC-e de consumidor nao identificado em vez de enviado a SEFAZ.
        $documento = isset($payload['cpf_destinatario']) ? $payload['cpf_destinatario']
            : (isset($payload['cnpj_destinatario']) ? $payload['cnpj_destinatario'] : null);
        if ($documento && !$this->documento_valido($documento)) {
            foreach (array_keys($payload) as $campo) {
                if (substr($campo, -12) === 'destinatario' || substr($campo, -13) === 'transportador') unset($payload[$campo]);
            }
            $payload['presenca_comprador'] = '1';
            $payload['modalidade_frete'] = '9';
        }
        return $resultado;
    }

    private function documento_valido($documento)
    {
        $numero = preg_replace('/\D/', '', (string) $documento);
        if (preg_match('/^(\d)\1+$/', $numero)) return false;
        if (strlen($numero) === 11) {
            for ($digito = 9; $digito < 11; $digito++) {
                $soma = 0;
                for ($i = 0; $i < $digito; $i++) $soma += (int) $numero[$i] * (($digito + 1) - $i);
                $dv = (10 * $soma) % 11;
                if ($dv === 10) $dv = 0;
                if ($dv !== (int) $numero[$digito]) return false;
            }
            return true;
        }
        if (strlen($numero) === 14) {
            $pesos = array(array(5,4,3,2,9,8,7,6,5,4,3,2), array(6,5,4,3,2,9,8,7,6,5,4,3,2));
            for ($d = 0; $d < 2; $d++) {
                $soma = 0;
                foreach ($pesos[$d] as $i => $peso) $soma += (int) $numero[$i] * $peso;
                $resto = $soma % 11; $dv = $resto < 2 ? 0 : 11 - $resto;
                if ($dv !== (int) $numero[12 + $d]) return false;
            }
            return true;
        }
        return false;
    }

    private function pedidos($inicio, $fim)
    {
        return $this->db->select("pedidos.id, pedidos.data_pedido, pedidos.status_pedido, pedidos.valor_total,
                notas_fiscais.status AS nota_status", false)
            ->from('pedidos')
            ->join('notas_fiscais', "notas_fiscais.pedidos_id = pedidos.id AND notas_fiscais.tipo_documento = 'nfce'", 'left')
            ->where('pedidos.data_pedido >=', $inicio.' 00:00:00')
            ->where('pedidos.data_pedido <=', $fim.' 23:59:59')
            ->order_by('pedidos.data_pedido', 'ASC')->order_by('pedidos.id', 'ASC')->get()->result();
    }

    private function periodo($mes)
    {
        if (!preg_match('/^\d{4}-\d{2}$/', (string) $mes)) throw new RuntimeException('Mes invalido; use AAAA-MM.');
        $inicio = $mes.'-01';
        return array($inicio, date('Y-m-t', strtotime($inicio)));
    }

    private function indice_existe($tabela, $indice)
    {
        return $this->db->query('SHOW INDEX FROM `'.$tabela.'` WHERE Key_name = '.$this->db->escape($indice))->num_rows() > 0;
    }

    private function validar_homologacao()
    {
        if (($this->fiscal['ambiente'] ?? null) !== 'homologacao') throw new RuntimeException('Config fiscal nao esta em homologacao.');
        $host = strtolower((string) parse_url($this->fiscal['focus_nfe_url_homologacao'] ?? '', PHP_URL_HOST));
        if ($host !== 'homologacao.focusnfe.com.br') throw new RuntimeException('Host de homologacao nao autorizado: '.$host.'.');
    }

    private function saida($texto)
    {
        echo $texto.PHP_EOL;
    }
}
