<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Gera o arquivo SINTEGRA (Convenio ICMS 57/95) das saidas por NFC-e.
 *
 * Escopo desta versao: registros 10, 11, 61, 61R, 75 e 90 -- ou seja, o que
 * este sistema emite. Os registros 50/51/54 (NF-e modelo 55 propria e notas
 * de entrada de fornecedor) nao tem origem aqui e continuam por conta da
 * contabilidade; ver plans/fiscal/auditoria-nfce-sintegra.md.
 *
 * Todo registro tem 126 posicoes, terminador CRLF e codificacao ASCII.
 * Campos numericos vao com zeros a esquerda e sem separador; campos texto
 * vao alinhados a esquerda e completados com brancos.
 */
class Sintegra_service {

    const TAMANHO_REGISTRO = 126;
    const MODELO_NFCE = '65';

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

        return array(
            'dias' => $dados['diario'],
            'documentos' => $documentos,
            'total_liquido' => round($total_liquido, 2),
            'total_bruto' => round($total_bruto, 2),
            'itens' => count($dados['produtos_vendidos']),
            'produtos' => count($dados['produtos']),
            'registros' => count($dados['diario']) + count($dados['produtos_vendidos']) + count($dados['produtos']) + 3,
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
        );
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
