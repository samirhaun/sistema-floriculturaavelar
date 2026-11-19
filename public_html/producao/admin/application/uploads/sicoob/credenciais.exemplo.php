<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// Copie para credenciais.php NO SERVIDOR (nunca no git) junto com o
// certificado .pfx do e-CNPJ. Esta pasta nao e enviada pelo deploy.
return array(
    'client_id'        => 'CLIENT_ID_DO_PORTAL_SICOOB',
    'conta_corrente'   => '000000',            // so numeros, sem digito separado
    'certificado_pfx'  => __DIR__.'/certificado.pfx',
    'certificado_senha'=> 'SENHA_DO_PFX',
    // opcionais
    'url_token'        => 'https://auth.sicoob.com.br/auth/realms/cooperado/protocol/openid-connect/token',
    'url_api'          => 'https://api.sicoob.com.br/conta-corrente/v4',
    'scope'            => 'cco_extrato cco_saldo',
    // trechos da descricao do extrato que identificam cada tipo de credito
    'palavras_cartao'  => array('SIPAG', 'CARTAO', 'CARTÃO', 'BANDEIRA', 'CIELO', 'REDE'),
    'palavras_pix'     => array('PIX'),
);
