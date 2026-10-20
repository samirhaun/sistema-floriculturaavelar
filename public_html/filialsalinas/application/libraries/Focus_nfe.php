<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cliente minimo da Focus NFe para NF-e.
 * O token e' obtido exclusivamente da variavel de ambiente FOCUS_NFE_TOKEN.
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
        if (empty($this->configuracao['enabled'])) {
            throw new RuntimeException('Emissao fiscal esta desabilitada ate a homologacao.');
        }

        return $this->requisitar('POST', '/v2/nfe?ref='.rawurlencode($referencia), $dados);
    }

    public function consultar_nfe($referencia)
    {
        return $this->requisitar('GET', '/v2/nfe/'.rawurlencode($referencia));
    }

    private function requisitar($metodo, $rota, $dados = null)
    {
        if (empty($this->configuracao['focus_nfe_token'])) {
            throw new RuntimeException('FOCUS_NFE_TOKEN nao foi configurado no servidor.');
        }

        $url_base = $this->configuracao['ambiente'] === 'producao'
            ? $this->configuracao['focus_nfe_url_producao']
            : $this->configuracao['focus_nfe_url_homologacao'];
        $curl = curl_init(rtrim($url_base, '/').$rota);
        $cabecalhos = array('Accept: application/json');

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $metodo);
        curl_setopt($curl, CURLOPT_USERPWD, $this->configuracao['focus_nfe_token'].':');
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

        return array('status_http' => $status_http, 'resposta' => $resposta);
    }
}
