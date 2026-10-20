<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cliente minimo da Focus NFe para documentos fiscais eletronicos.
 *
 * O token e' obtido exclusivamente da variavel de ambiente FOCUS_NFE_TOKEN.
 * A biblioteca nao emite nada enquanto fiscal.enabled estiver desativado.
 */
class Focus_nfe {

    private $CI;
    private $configuracao;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->config->load('fiscal');
        $this->configuracao = $this->CI->config->item('fiscal');
    }

    public function emitir_nfe($referencia, array $dados)
    {
        $this->validar_emissao_habilitada();
        return $this->requisitar('POST', '/v2/nfe?ref='.rawurlencode($referencia), $dados);
    }

    /**
     * A NFC-e (modelo 65) e' autorizada de forma sincrona pela Focus.
     * A resposta desta chamada ja contem a autorizacao ou a rejeicao.
     */
    public function emitir_nfce($referencia, array $dados)
    {
        $this->validar_emissao_habilitada();
        return $this->requisitar('POST', '/v2/nfce?ref='.rawurlencode($referencia), $dados);
    }

    public function consultar_nfe($referencia)
    {
        $this->validar_token();
        return $this->requisitar('GET', '/v2/nfe/'.rawurlencode($referencia));
    }

    public function consultar_nfce($referencia)
    {
        $this->validar_token();
        return $this->requisitar('GET', '/v2/nfce/'.rawurlencode($referencia));
    }

    public function baixar_arquivo($caminho)
    {
        $this->validar_token();
        if (!preg_match('#^https?://#', $caminho)) {
            $url_base = $this->configuracao['ambiente'] === 'producao'
                ? $this->configuracao['focus_nfe_url_producao']
                : $this->configuracao['focus_nfe_url_homologacao'];
            $caminho = rtrim($url_base, '/').'/'.ltrim($caminho, '/');
        }

        $curl = curl_init($caminho);
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => $this->token_acesso().':',
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 60,
        ));
        $corpo = curl_exec($curl);
        $erro = curl_error($curl);
        $status_http = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $content_type = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
        curl_close($curl);

        if ($corpo === false || $status_http !== 200) {
            throw new RuntimeException('Falha ao baixar arquivo fiscal (HTTP '.$status_http.'): '.$erro);
        }

        return array('conteudo' => $corpo, 'content_type' => $content_type);
    }

    private function validar_emissao_habilitada()
    {
        if (empty($this->configuracao['enabled'])) {
            throw new RuntimeException('Emissao fiscal esta desabilitada ate a homologacao.');
        }

        $this->validar_token();
    }

    private function validar_token()
    {
        if (empty($this->token_acesso())) {
            $variavel = ($this->configuracao['ambiente'] ?? null) === 'producao'
                ? 'FOCUS_NFE_TOKEN_PRODUCAO'
                : 'FOCUS_NFE_TOKEN_HOMOLOGACAO';
            throw new RuntimeException($variavel.' nao foi configurado no servidor.');
        }
    }

    private function requisitar($metodo, $rota, $dados = null)
    {
        $url_base = $this->configuracao['ambiente'] === 'producao'
            ? $this->configuracao['focus_nfe_url_producao']
            : $this->configuracao['focus_nfe_url_homologacao'];

        $curl = curl_init(rtrim($url_base, '/').$rota);
        $cabecalhos = array('Accept: application/json');

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $metodo);
        curl_setopt($curl, CURLOPT_USERPWD, $this->token_acesso().':');
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 45);

        if ($dados !== null) {
            $cabecalhos[] = 'Content-Type: application/json';
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($dados));
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $cabecalhos);
        $corpo = curl_exec($curl);
        $erro = curl_error($curl);
        $status_http = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($corpo === false) {
            throw new RuntimeException('Falha de comunicacao com a Focus NFe: '.$erro);
        }

        $resposta = json_decode($corpo, true);
        if (!is_array($resposta)) {
            $resposta = array('corpo' => $corpo);
        }

        return array(
            'status_http' => $status_http,
            'resposta' => $resposta,
        );
    }

    /** Retorna somente a credencial correspondente ao ambiente ativo. */
    private function token_acesso()
    {
        if (($this->configuracao['ambiente'] ?? null) === 'producao') {
            return $this->configuracao['focus_nfe_token_producao'] ?? null;
        }
        return $this->configuracao['focus_nfe_token'] ?? null;
    }
}
