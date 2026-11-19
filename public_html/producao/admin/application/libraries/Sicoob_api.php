<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Cliente da API de Conta Corrente do Sicoob (extrato).
 *
 * Autenticacao: OAuth2 client_credentials com mTLS usando o certificado
 * e-CNPJ (.pfx) cadastrado no portal developers.sicoob.com.br.
 * As credenciais ficam em application/uploads/sicoob/credenciais.php, que
 * nao vai para o git nem e sobrescrito pelo deploy.
 */
class Sicoob_api {

    private $cfg = null;
    private $erro_config = null;

    public function __construct()
    {
        $arquivo = APPPATH.'uploads/sicoob/credenciais.php';
        if (!is_file($arquivo)) {
            $this->erro_config = 'Credenciais do Sicoob nao configuradas (falta application/uploads/sicoob/credenciais.php no servidor).';
            return;
        }
        $cfg = include $arquivo;
        if (!is_array($cfg) || empty($cfg['client_id']) || empty($cfg['conta_corrente']) || empty($cfg['certificado_pfx'])) {
            $this->erro_config = 'Arquivo de credenciais do Sicoob incompleto (client_id, conta_corrente e certificado_pfx sao obrigatorios).';
            return;
        }
        if (!is_file($cfg['certificado_pfx'])) {
            $this->erro_config = 'Certificado .pfx do Sicoob nao encontrado no servidor.';
            return;
        }
        $this->cfg = array_merge(array(
            'url_token' => 'https://auth.sicoob.com.br/auth/realms/cooperado/protocol/openid-connect/token',
            'url_api' => 'https://api.sicoob.com.br/conta-corrente/v4',
            'scope' => 'cco_extrato cco_saldo',
            'certificado_senha' => '',
            'palavras_cartao' => array('SIPAG', 'CARTAO', 'CARTÃO', 'BANDEIRA'),
            'palavras_pix' => array('PIX'),
        ), $cfg);
    }

    public function configurado()
    {
        return $this->cfg !== null;
    }

    public function erro_configuracao()
    {
        return $this->erro_config;
    }

    public function conta()
    {
        return $this->cfg ? (string) $this->cfg['conta_corrente'] : '';
    }

    /** Classifica um credito do extrato em cartao, pix ou outros. */
    public function categoria($descricao, $complemento = '')
    {
        $texto = mb_strtoupper($descricao.' '.$complemento, 'UTF-8');
        foreach (array('pix' => 'palavras_pix', 'cartao' => 'palavras_cartao') as $categoria => $chave) {
            foreach ((array) $this->cfg[$chave] as $palavra) {
                if ($palavra !== '' && mb_strpos($texto, mb_strtoupper($palavra, 'UTF-8')) !== false) {
                    return $categoria;
                }
            }
        }
        return 'outros';
    }

    /**
     * Retorna as transacoes do mes, normalizadas:
     * array(data Y-m-d, tipo C|D, valor positivo, descricao, documento, cpf_cnpj, complemento)
     */
    public function extrato_mes($mes, $ano)
    {
        $url = rtrim($this->cfg['url_api'], '/').'/extrato/'.(int) $mes.'/'.(int) $ano
            .'?'.http_build_query(array('numeroContaCorrente' => $this->cfg['conta_corrente']));
        $resposta = $this->requisicao('GET', $url, null, array(
            'Authorization: Bearer '.$this->token(),
            'client_id: '.$this->cfg['client_id'],
            'x-sicoob-clientid: '.$this->cfg['client_id'],
            'Accept: application/json',
        ));

        $json = json_decode($resposta, true);
        if (!is_array($json)) {
            throw new Exception('Resposta invalida do extrato Sicoob.');
        }
        $base = isset($json['resultado']) ? $json['resultado'] : $json;
        $transacoes = isset($base['transacoes']) ? $base['transacoes'] : array();

        $saida = array();
        foreach ($transacoes as $t) {
            $valor_bruto = isset($t['valor']) ? $t['valor'] : 0;
            if (is_string($valor_bruto) && !is_numeric(trim($valor_bruto))) {
                // formato brasileiro: 1.234,56
                $valor_bruto = str_replace(',', '.', str_replace('.', '', trim($valor_bruto)));
            }
            $valor = (float) $valor_bruto;
            $tipo_txt = strtoupper(trim(isset($t['tipo']) ? (string) $t['tipo'] : ''));
            if ($tipo_txt === '') {
                $tipo = $valor < 0 ? 'D' : 'C';
            } else {
                $tipo = ($tipo_txt[0] === 'D') ? 'D' : 'C';
            }
            $data = isset($t['data']) ? $t['data'] : (isset($t['dataLote']) ? $t['dataLote'] : null);
            if (!$data) {
                continue;
            }
            $saida[] = array(
                'data' => date('Y-m-d', strtotime(substr($data, 0, 10))),
                'tipo' => $tipo,
                'valor' => round(abs($valor), 2),
                'descricao' => isset($t['descricao']) ? mb_substr(trim($t['descricao']), 0, 255) : null,
                'documento' => isset($t['numeroDocumento']) ? mb_substr(trim($t['numeroDocumento']), 0, 80) : null,
                'cpf_cnpj' => isset($t['cpfCnpj']) ? mb_substr(trim($t['cpfCnpj']), 0, 20) : null,
                'complemento' => isset($t['descInfComplementar']) ? mb_substr(trim($t['descInfComplementar']), 0, 255) : null,
            );
        }
        return $saida;
    }

    private function token()
    {
        $cache = APPPATH.'uploads/sicoob/token.json';
        if (is_file($cache)) {
            $dados = json_decode(@file_get_contents($cache), true);
            if (!empty($dados['access_token']) && !empty($dados['expira']) && $dados['expira'] > time() + 30) {
                return $dados['access_token'];
            }
        }

        $resposta = $this->requisicao('POST', $this->cfg['url_token'], http_build_query(array(
            'grant_type' => 'client_credentials',
            'client_id' => $this->cfg['client_id'],
            'scope' => $this->cfg['scope'],
        )), array('Content-Type: application/x-www-form-urlencoded'));

        $json = json_decode($resposta, true);
        if (empty($json['access_token'])) {
            throw new Exception('Sicoob nao retornou token de acesso.');
        }
        $expira = time() + (isset($json['expires_in']) ? (int) $json['expires_in'] : 300);
        @file_put_contents($cache, json_encode(array('access_token' => $json['access_token'], 'expira' => $expira)));
        return $json['access_token'];
    }

    private function requisicao($metodo, $url, $corpo, $headers)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $metodo,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_SSLCERT => $this->cfg['certificado_pfx'],
            CURLOPT_SSLCERTTYPE => 'P12',
            CURLOPT_SSLCERTPASSWD => $this->cfg['certificado_senha'],
        ));
        if ($corpo !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $corpo);
        }
        $resposta = curl_exec($ch);
        $erro = curl_error($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($resposta === false) {
            throw new Exception('Falha de conexao com o Sicoob: '.$erro);
        }
        if ($status < 200 || $status >= 300) {
            log_message('error', 'Sicoob '.$metodo.' '.$url.' HTTP '.$status.': '.substr($resposta, 0, 1000));
            throw new Exception('Sicoob respondeu HTTP '.$status.': '.substr(strip_tags($resposta), 0, 300));
        }
        return $resposta;
    }
}
