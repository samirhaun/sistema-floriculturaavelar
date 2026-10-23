<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Gera o arquivo SINTEGRA (Convenio ICMS 57/95) das saidas por NFC-e e das
 * notas de compra importadas por XML.
 *
 * Escopo desta versao: registros 10, 11, 50 (entradas de terceiros), 61, 61R,
 * 75 e 90. Os registros 51/54 e a NF-e modelo 55 propria nao tem origem aqui
 * e continuam por conta da contabilidade; ver plans/fiscal/auditoria-nfce-sintegra.md.
 *
 * Todo registro tem 126 posicoes, terminador CRLF e codificacao ASCII.
 * Campos numericos vao com zeros a esquerda e sem separador; campos texto
 * vao alinhados a esquerda e completados com brancos.
 */
class Sintegra_service {

    const TAMANHO_REGISTRO = 126;
    const MODELO_NFCE = '65';

    /** Finalidade da compra: decide o CFOP de entrada do registro 50. */
    public static $finalidades_entrada = array(
        'revenda' => 'Revenda (comercialização)',
        'uso_consumo' => 'Uso e consumo',
        'imobilizado' => 'Ativo imobilizado',
    );

    private $CI;
    private $fiscal;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->config->load('fiscal');
        $this->fiscal = $this->CI->config->item('fiscal');
        $this->CI->load->model('loja/sintegra_model');
    }

    /** Retorna o conteudo do arquivo pronto para download. */
    public function gerar($inicio, $fim)
    {
        $dados = $this->coletar($inicio, $fim);
        if (!empty($dados['inconsistencias'])) {
            throw new RuntimeException('Existem notas autorizadas sem numero ou sem valor no periodo. Corrija antes de gerar o arquivo.');
        }

        $linhas = array();
        $linhas[] = $this->registro_10($inicio, $fim);
        $linhas[] = $this->registro_11();
        foreach ($dados['entradas'] as $entrada) {
            foreach ($entrada->cfops as $cfop) {
                $linhas[] = $this->registro_50_entrada($entrada, $cfop);
            }
        }
        foreach ($dados['diario'] as $dia) {
            $linhas[] = $this->registro_61($dia);
        }
        foreach ($dados['produtos_vendidos'] as $item) {
            $linhas[] = $this->registro_61r($inicio, $item);
        }
        foreach ($dados['produtos'] as $produto) {
            $linhas[] = $this->registro_75($inicio, $fim, $produto);
        }
        $linhas[] = $this->registro_90($linhas);

        return implode("\r\n", $linhas)."\r\n";
    }

    /**
     * Numeros do periodo para conferencia na tela antes do download.
     * O total do registro 61 (liquido, ja com desconto) e o total do 61R
     * (bruto, por definicao do layout) so coincidem quando nao houve
     * desconto no periodo -- por isso os dois aparecem.
     */
    public function conferencia($inicio, $fim)
    {
        $dados = $this->coletar($inicio, $fim);
        $total_liquido = 0.0;
        $documentos = 0;
        foreach ($dados['diario'] as $dia) {
            $total_liquido += (float) $dia->valor_total;
            $documentos += (int) $dia->documentos;
        }
        $total_bruto = 0.0;
        foreach ($dados['produtos_vendidos'] as $item) {
            $total_bruto += (float) $item->valor_bruto;
        }
        $total_entradas = 0.0;
        $registros_50 = 0;
        foreach ($dados['entradas'] as $entrada) {
            $total_entradas += (float) $entrada->valor_total;
            $registros_50 += count($entrada->cfops);
        }

        return array(
            'dias' => $dados['diario'],
            'documentos' => $documentos,
            'total_liquido' => round($total_liquido, 2),
            'total_bruto' => round($total_bruto, 2),
            'itens' => count($dados['produtos_vendidos']),
            'produtos' => count($dados['produtos']),
            'entradas' => $dados['entradas'],
            'entradas_disponiveis' => $this->CI->sintegra_model->entradas_disponiveis(),
            'total_entradas' => round($total_entradas, 2),
            'registros_50' => $registros_50,
            'registros' => $registros_50 + count($dados['diario']) + count($dados['produtos_vendidos']) + count($dados['produtos']) + 3,
            'inconsistencias' => $dados['inconsistencias'],
            'pendentes' => $dados['pendentes'],
        );
    }

    private function coletar($inicio, $fim)
    {
        return array(
            'diario' => $this->CI->sintegra_model->resumo_diario($inicio, $fim),
            'produtos_vendidos' => $this->CI->sintegra_model->resumo_por_produto($inicio, $fim),
            'produtos' => $this->CI->sintegra_model->produtos_do_periodo($inicio, $fim),
            'inconsistencias' => $this->CI->sintegra_model->notas_inconsistentes($inicio, $fim),
            'pendentes' => $this->CI->sintegra_model->notas_pendentes($inicio, $fim),
            'entradas' => $this->CI->sintegra_model->entradas_do_periodo($inicio, $fim),
        );
    }

    // ------------------------------------------------------------------
    // Leitura do XML de compra
    // ------------------------------------------------------------------

    /**
     * Le a NF-e de um fornecedor e devolve a nota e o resumo por CFOP de
     * entrada + aliquota, prontos para gravar. Lanca RuntimeException com a
     * mensagem para o usuario quando o XML nao serve para o registro 50.
     */
    public function ler_entrada_xml($conteudo, $finalidade)
    {
        if (!isset(self::$finalidades_entrada[$finalidade])) {
            throw new RuntimeException('finalidade da compra invalida.');
        }

        $anterior = libxml_use_internal_errors(true);
        $xml = simplexml_load_string($conteudo, 'SimpleXMLElement', LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($anterior);
        if ($xml === false) throw new RuntimeException('XML invalido ou corrompido.');

        $nos = $xml->xpath('//*[local-name()="infNFe"]');
        $inf = !empty($nos) ? $nos[0] : null;
        if (!$inf || !isset($inf->ide) || !isset($inf->emit) || !isset($inf->dest) || !isset($inf->det)) {
            throw new RuntimeException('o arquivo nao contem uma NF-e reconhecivel.');
        }

        $protocolo = $xml->xpath('//*[local-name()="infProt"]/*[local-name()="cStat"]');
        if (!empty($protocolo) && !in_array((string) $protocolo[0], array('100', '150'), true)) {
            throw new RuntimeException('a NF-e nao esta autorizada (cStat '.(string) $protocolo[0].').');
        }

        $ide = $inf->ide;
        $emit = $inf->emit;
        $dest = $inf->dest;

        $chave = preg_replace('/\D/', '', preg_replace('/^NFe/', '', (string) $inf['Id']));
        if (strlen($chave) !== 44) throw new RuntimeException('chave de acesso de 44 digitos nao encontrada.');

        $cnpj_proprio = preg_replace('/\D/', '', $this->campo('cnpj'));
        $cnpj_destino = preg_replace('/\D/', '', (string) $dest->CNPJ);
        if ($cnpj_destino !== $cnpj_proprio) {
            throw new RuntimeException('a nota nao foi emitida para o CNPJ da loja (destinatario '.($cnpj_destino ?: 'sem CNPJ').').');
        }
        // Produtor rural emite com CPF; o campo do registro 50 aceita CPF com zeros a esquerda.
        $cnpj_emitente = preg_replace('/\D/', '', (string) (isset($emit->CNPJ) ? $emit->CNPJ : $emit->CPF));
        if ($cnpj_emitente === '') throw new RuntimeException('CNPJ/CPF do fornecedor nao encontrado.');
        if ($cnpj_emitente === $cnpj_proprio) {
            throw new RuntimeException('a nota foi emitida pela propria loja, nao e entrada de fornecedor.');
        }

        $emissao = strtotime((string) $ide->dhEmi);
        if (!$emissao) throw new RuntimeException('data de emissao invalida.');
        // Sem data de entrada informada, a melhor aproximacao do recebimento e
        // a data de saida da mercadoria; na falta dela, a emissao.
        $saida = !empty($ide->dhSaiEnt) ? strtotime((string) $ide->dhSaiEnt) : false;
        $data_entrada = date('Y-m-d', $saida ? $saida : $emissao);

        $total = isset($inf->total->ICMSTot) ? $inf->total->ICMSTot : null;
        $valor_nota = $total ? (float) $total->vNF : 0.0;
        $cfops = $this->resumo_cfop_entrada($inf, $finalidade, $valor_nota);

        $ie = strtoupper(preg_replace('/[^0-9A-Za-z]/', '', (string) $emit->IE));
        return array(
            'nota' => array(
                'chave_nfe' => $chave,
                'modelo' => preg_replace('/\D/', '', (string) $ide->mod) ?: '55',
                'numero_nota' => preg_replace('/\D/', '', (string) $ide->nNF),
                'serie' => (string) $ide->serie,
                'data_emissao' => date('Y-m-d H:i:s', $emissao),
                'data_entrada' => $data_entrada,
                'cnpj_emitente' => $cnpj_emitente,
                'ie_emitente' => $ie !== '' ? $ie : 'ISENTO',
                'razao_emitente' => (string) $emit->xNome,
                'uf_emitente' => (string) $emit->enderEmit->UF,
                'cnpj_destino' => $cnpj_destino,
                'finalidade' => $finalidade,
                'valor_total' => round($valor_nota, 2),
                'valor_bc_icms' => $total ? round((float) $total->vBC, 2) : 0,
                'valor_icms' => $total ? round((float) $total->vICMS, 2) : 0,
                'valor_ipi' => $total ? round((float) $total->vIPI, 2) : 0,
                'xml_conteudo' => $conteudo,
                'importado_em' => date('Y-m-d H:i:s'),
            ),
            'cfops' => $cfops,
        );
    }

    /**
     * Agrupa os itens por CFOP de entrada + aliquota: o leiaute pede um
     * registro 50 por combinacao. O valor de cada grupo segue a formula do
     * vNF; a diferenca de arredondamento vai para o maior grupo, para que a
     * soma dos registros feche com o total da nota.
     */
    private function resumo_cfop_entrada($inf, $finalidade, $valor_nota)
    {
        $grupos = array();
        foreach ($inf->det as $det) {
            $prod = $det->prod;
            $cfop_nota = preg_replace('/\D/', '', (string) $prod->CFOP);
            if (strlen($cfop_nota) !== 4) throw new RuntimeException('item '.(string) $det['nItem'].' sem CFOP valido.');
            $cfop = $this->cfop_entrada($cfop_nota, $finalidade);

            $icms = null;
            if (isset($det->imposto->ICMS)) {
                foreach ($det->imposto->ICMS->children() as $grupo_icms) { $icms = $grupo_icms; break; }
            }
            $bc = $icms && isset($icms->vBC) ? (float) $icms->vBC : 0.0;
            $valor_icms = $icms && isset($icms->vICMS) ? (float) $icms->vICMS : 0.0;
            $aliquota = ($icms && isset($icms->pICMS) && $valor_icms > 0) ? (float) $icms->pICMS : 0.0;
            $st = $icms ? (float) $icms->vICMSST + (float) $icms->vFCPST : 0.0;
            $ipi = isset($det->imposto->IPI->IPITrib->vIPI) ? (float) $det->imposto->IPI->IPITrib->vIPI : 0.0;
            $ii = isset($det->imposto->II->vII) ? (float) $det->imposto->II->vII : 0.0;
            $desonerado = $icms && isset($icms->vICMSDeson) ? (float) $icms->vICMSDeson : 0.0;

            $valor = (float) $prod->vProd - (float) $prod->vDesc + (float) $prod->vFrete
                + (float) $prod->vSeg + (float) $prod->vOutro + $ipi + $st + $ii - $desonerado;

            $chave = $cfop.'|'.number_format($aliquota, 2, '.', '');
            if (!isset($grupos[$chave])) {
                $grupos[$chave] = array('cfop_nota' => array(), 'cfop' => $cfop, 'aliquota' => $aliquota,
                    'valor_total' => 0.0, 'valor_bc_icms' => 0.0, 'valor_icms' => 0.0);
            }
            $grupos[$chave]['cfop_nota'][$cfop_nota] = $cfop_nota;
            $grupos[$chave]['valor_total'] += $valor;
            $grupos[$chave]['valor_bc_icms'] += $bc;
            $grupos[$chave]['valor_icms'] += $valor_icms;
        }

        $soma = 0.0;
        $maior = null;
        foreach ($grupos as $chave => $grupo) {
            $grupos[$chave]['valor_total'] = round($grupo['valor_total'], 2);
            $soma += $grupos[$chave]['valor_total'];
            if ($maior === null || $grupos[$chave]['valor_total'] > $grupos[$maior]['valor_total']) $maior = $chave;
        }
        if ($maior !== null && $valor_nota > 0) {
            $grupos[$maior]['valor_total'] = round($grupos[$maior]['valor_total'] + ($valor_nota - $soma), 2);
        }

        $resumo = array();
        foreach ($grupos as $grupo) {
            $total = $grupo['valor_total'];
            $bc = round($grupo['valor_bc_icms'], 2);
            $icms = round($grupo['valor_icms'], 2);
            // Sem ICMS destacado (fornecedor do Simples, isencao) o valor cai
            // em isentas/nao tributadas; com destaque, o que excede a base
            // (ST, IPI, frete fora da base) vai para outras.
            $resumo[] = array(
                'cfop_nota' => implode(',', $grupo['cfop_nota']),
                'cfop' => $grupo['cfop'],
                'aliquota' => round($grupo['aliquota'], 2),
                'valor_total' => $total,
                'valor_bc_icms' => $bc,
                'valor_icms' => $icms,
                'valor_isentas' => $icms > 0 ? 0 : $total,
                'valor_outras' => $icms > 0 ? max(0, round($total - $bc, 2)) : 0,
            );
        }
        return $resumo;
    }

    /**
     * Converte o CFOP de saida do fornecedor no CFOP de entrada da loja.
     * O validador recusa registro 50 de terceiros com CFOP 5xxx/6xxx.
     */
    public function cfop_entrada($cfop_nota, $finalidade)
    {
        $origem = substr($cfop_nota, 0, 1);
        if (in_array($origem, array('1', '2', '3'), true)) return $cfop_nota;
        $mapa_origem = array('5' => '1', '6' => '2', '7' => '3');
        if (!isset($mapa_origem[$origem])) throw new RuntimeException('CFOP '.$cfop_nota.' nao reconhecido.');
        $prefixo = $mapa_origem[$origem];
        $sufixo = substr($cfop_nota, 1);

        // Venda com substituicao tributaria (substituto ou substituido).
        $venda_st = in_array($sufixo, array('401', '402', '403', '404', '405'), true);
        // Venda de mercadoria comum (producao propria ou de terceiros).
        $venda = in_array($sufixo, array('101', '102', '103', '104', '105', '106', '115', '117', '118', '119', '120', '122'), true);

        $destino = array(
            'revenda' => array('venda' => '102', 'venda_st' => '403'),
            'uso_consumo' => array('venda' => '556', 'venda_st' => '407'),
            'imobilizado' => array('venda' => '551', 'venda_st' => '406'),
        );
        if ($venda_st) return $prefixo.$destino[$finalidade]['venda_st'];
        if ($venda) return $prefixo.$destino[$finalidade]['venda'];
        // Demais operacoes (bonificacao 5910, remessa 5949, devolucao 5202...)
        // tem o espelho de entrada com o mesmo final.
        return $prefixo.$sufixo;
    }

    // ------------------------------------------------------------------
    // Registros
    // ------------------------------------------------------------------

    private function registro_10($inicio, $fim)
    {
        $sintegra = isset($this->fiscal['sintegra']) ? $this->fiscal['sintegra'] : array();
        return $this->montar(array(
            array('10', 2, 'texto'),
            array($this->campo('cnpj'), 14, 'numero_texto'),
            array($this->campo('inscricao_estadual'), 14, 'texto'),
            array($this->campo('razao_social'), 35, 'texto'),
            array($this->campo('municipio'), 30, 'texto'),
            array($this->campo('uf'), 2, 'texto'),
            array($this->campo('telefone'), 10, 'numero_texto'),
            array($this->data($inicio), 8, 'texto'),
            array($this->data($fim), 8, 'texto'),
            array(isset($sintegra['codigo_estrutura']) ? $sintegra['codigo_estrutura'] : '3', 1, 'texto'),
            array(isset($sintegra['codigo_natureza']) ? $sintegra['codigo_natureza'] : '3', 1, 'texto'),
            array(isset($sintegra['codigo_finalidade']) ? $sintegra['codigo_finalidade'] : '1', 1, 'texto'),
        ));
    }

    private function registro_11()
    {
        $estabelecimento = isset($this->fiscal['estabelecimento']) ? $this->fiscal['estabelecimento'] : array();
        return $this->montar(array(
            array('11', 2, 'texto'),
            array($this->campo('logradouro'), 34, 'texto'),
            array($this->campo('numero'), 5, 'numero_texto'),
            array(isset($estabelecimento['complemento']) ? $estabelecimento['complemento'] : '', 22, 'texto'),
            array($this->campo('bairro'), 15, 'texto'),
            array($this->campo('cep'), 8, 'numero_texto'),
            array($this->campo('contato'), 28, 'texto'),
            array($this->campo('telefone_contato'), 12, 'numero_texto'),
        ));
    }

    private function registro_50_entrada($entrada, $cfop)
    {
        return $this->montar(array(
            array('50', 2, 'texto'),
            array($entrada->cnpj_emitente, 14, 'numero_texto'),
            array($entrada->ie_emitente ? $entrada->ie_emitente : 'ISENTO', 14, 'texto'),
            // Na entrada o leiaute pede a data de recebimento.
            array($this->data($entrada->data_entrada), 8, 'texto'),
            array($entrada->uf_emitente, 2, 'texto'),
            array($entrada->modelo, 2, 'numero_texto'),
            array($entrada->serie, 3, 'texto'),
            // O campo tem 6 posicoes; a NF-e numera ate 9 digitos.
            array(substr(preg_replace('/\D/', '', $entrada->numero_nota), -6), 6, 'numero_texto'),
            array($cfop->cfop, 4, 'numero_texto'),
            array('T', 1, 'texto'),
            array($cfop->valor_total, 13, 'valor'),
            array($cfop->valor_bc_icms, 13, 'valor'),
            array($cfop->valor_icms, 13, 'valor'),
            array($cfop->valor_isentas, 13, 'valor'),
            array($cfop->valor_outras, 13, 'valor'),
            array($cfop->aliquota, 4, 'aliquota'),
            array('N', 1, 'texto'),
        ));
    }

    private function registro_61($dia)
    {
        // Emitente proprio: CNPJ e IE do destinatario vao em branco.
        return $this->montar(array(
            array('61', 2, 'texto'),
            array('', 14, 'texto'),
            array('', 14, 'texto'),
            array($this->data($dia->dia), 8, 'texto'),
            array(self::MODELO_NFCE, 2, 'texto'),
            array($dia->serie, 3, 'texto'),
            array('', 2, 'texto'),
            array($dia->numero_inicial, 6, 'numero_texto'),
            array($dia->numero_final, 6, 'numero_texto'),
            array($dia->valor_total, 13, 'valor'),
            // Simples Nacional (CSOSN 102): sem base de calculo e sem ICMS
            // destacado, entao o faturamento inteiro cai em "outras".
            array(0, 13, 'valor'),
            // O campo "valor do ICMS" tem 12 posicoes no registro 61.
            // Usa-lo com 13 deslocava isentas, outras e aliquota.
            array(0, 12, 'valor'),
            array(0, 13, 'valor'),
            array($dia->valor_total, 13, 'valor'),
            // O validador SINTEGRA exige o campo de aliquota como numerico,
            // mesmo quando nao ha ICMS destacado pelo Simples Nacional.
            array(0, 4, 'aliquota'),
            array('', 1, 'texto'),
        ));
    }

    private function registro_61r($inicio, $item)
    {
        return $this->montar(array(
            array('61', 2, 'texto'),
            array('R', 1, 'texto'),
            array(date('mY', strtotime($inicio)), 6, 'texto'),
            array($item->produtos_id, 14, 'texto'),
            array($item->quantidade, 13, 'quantidade'),
            array($item->valor_bruto, 16, 'valor'),
            array(0, 16, 'valor'),
            // O campo e numerico no leiaute do registro 61R. 0000 indica
            // que nao houve aliquota/destaque de ICMS no totalizador.
            array(0, 4, 'aliquota'),
            array('', 54, 'texto'),
        ));
    }

    private function registro_75($inicio, $fim, $produto)
    {
        return $this->montar(array(
            array('75', 2, 'texto'),
            array($this->data($inicio), 8, 'texto'),
            array($this->data($fim), 8, 'texto'),
            array($produto->id, 14, 'texto'),
            array($produto->ncm, 8, 'numero_texto'),
            array($produto->titulo, 53, 'texto'),
            array($produto->unidade ? $produto->unidade : 'UN', 6, 'texto'),
            array(0, 5, 'valor'),
            array(0, 4, 'aliquota'),
            array(0, 5, 'valor'),
            array(0, 13, 'valor'),
        ));
    }

    private function registro_90(array $linhas)
    {
        $totais = array();
        foreach ($linhas as $linha) {
            // O 61R conta junto com o 61: o layout totaliza por tipo de registro.
            $tipo = substr($linha, 0, 2);
            $totais[$tipo] = isset($totais[$tipo]) ? $totais[$tipo] + 1 : 1;
        }
        unset($totais['10'], $totais['11']);
        ksort($totais);
        // O total geral (99) inclui o proprio registro 90.
        $totais['99'] = count($linhas) + 1;

        $campos = array(
            array('90', 2, 'texto'),
            array($this->campo('cnpj'), 14, 'numero_texto'),
            array($this->campo('inscricao_estadual'), 14, 'texto'),
        );
        foreach ($totais as $tipo => $total) {
            $campos[] = array($tipo, 2, 'texto');
            $campos[] = array($total, 8, 'numero_texto');
        }
        $usado = 30 + (count($totais) * 10);
        $campos[] = array('', self::TAMANHO_REGISTRO - $usado - 1, 'texto');
        // Ultima posicao: quantidade de registros tipo 90 no arquivo.
        $campos[] = array(1, 1, 'numero_texto');

        return $this->montar($campos);
    }

    // ------------------------------------------------------------------
    // Formatacao
    // ------------------------------------------------------------------

    private function montar(array $campos)
    {
        $linha = '';
        foreach ($campos as $campo) {
            list($valor, $tamanho, $tipo) = $campo;
            switch ($tipo) {
                case 'valor':      $linha .= $this->numero($valor, $tamanho, 2); break;
                case 'quantidade': $linha .= $this->numero($valor, $tamanho, 3); break;
                case 'aliquota':   $linha .= $this->numero($valor, $tamanho, 2); break;
                case 'numero_texto': $linha .= str_pad(preg_replace('/\D/', '', (string) $valor), $tamanho, '0', STR_PAD_LEFT); break;
                default:           $linha .= $this->texto($valor, $tamanho);
            }
        }
        if (strlen($linha) !== self::TAMANHO_REGISTRO) {
            throw new RuntimeException('Registro SINTEGRA com '.strlen($linha).' posicoes (esperado '.self::TAMANHO_REGISTRO.'): '.substr($linha, 0, 2));
        }
        return $linha;
    }

    private function texto($valor, $tamanho)
    {
        // Campos alfanumericos sao alinhados a esquerda. Dados de cadastro
        // com espaco inicial (por exemplo, titulos de produto) sao rejeitados
        // pelo validador antes mesmo de completar o campo com brancos.
        $valor = trim((string) $valor);
        // A imagem PHP usada no servidor nao translitera alguns caracteres
        // portugueses pelo iconv. Fazemos o mapa antes da conversao para que
        // o arquivo fique ASCII sem substituir acentos por '?'.
        $valor = strtr($valor, array(
            'Á'=>'A', 'À'=>'A', 'Ã'=>'A', 'Â'=>'A', 'Ä'=>'A',
            'á'=>'a', 'à'=>'a', 'ã'=>'a', 'â'=>'a', 'ä'=>'a',
            'É'=>'E', 'È'=>'E', 'Ê'=>'E', 'Ë'=>'E',
            'é'=>'e', 'è'=>'e', 'ê'=>'e', 'ë'=>'e',
            'Í'=>'I', 'Ì'=>'I', 'Î'=>'I', 'Ï'=>'I',
            'í'=>'i', 'ì'=>'i', 'î'=>'i', 'ï'=>'i',
            'Ó'=>'O', 'Ò'=>'O', 'Õ'=>'O', 'Ô'=>'O', 'Ö'=>'O',
            'ó'=>'o', 'ò'=>'o', 'õ'=>'o', 'ô'=>'o', 'ö'=>'o',
            'Ú'=>'U', 'Ù'=>'U', 'Û'=>'U', 'Ü'=>'U',
            'ú'=>'u', 'ù'=>'u', 'û'=>'u', 'ü'=>'u',
            'Ç'=>'C', 'ç'=>'c', 'Ñ'=>'N', 'ñ'=>'n'
        ));
        // O leiaute da SEF/MG exige ASCII. A conversao campo a campo mantem o
        // alinhamento em bytes e evita caracteres UTF-8 invalidos no validador.
        $convertido = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $valor);
        if ($convertido === false) $convertido = $valor;
        $convertido = preg_replace('/[\x00-\x1F\x7F]/', ' ', $convertido);
        return str_pad(substr($convertido, 0, $tamanho), $tamanho, ' ', STR_PAD_RIGHT);
    }

    private function numero($valor, $tamanho, $decimais)
    {
        // number_format evita a notacao cientifica que a conversao direta de
        // float para string produz em valores grandes.
        $inteiro = number_format(abs((float) $valor), $decimais, '', '');
        if (!ctype_digit($inteiro) || strlen($inteiro) > $tamanho) {
            throw new RuntimeException('Valor nao cabe no campo do SINTEGRA: '.$valor);
        }
        return str_pad($inteiro, $tamanho, '0', STR_PAD_LEFT);
    }

    private function data($valor)
    {
        $tempo = strtotime((string) $valor);
        if (!$tempo) throw new RuntimeException('Data invalida para o SINTEGRA: '.$valor);
        return date('Ymd', $tempo);
    }

    private function campo($nome)
    {
        $estabelecimento = isset($this->fiscal['estabelecimento']) ? $this->fiscal['estabelecimento'] : array();
        if (empty($estabelecimento[$nome])) {
            throw new RuntimeException('Dado do estabelecimento ausente em config/fiscal.php: '.$nome.'.');
        }
        return $estabelecimento[$nome];
    }
}
