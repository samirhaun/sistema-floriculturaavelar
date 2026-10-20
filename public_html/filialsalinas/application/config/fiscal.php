<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| Configuracao fiscal da filial Salinas.
|
| A emissao permanece desabilitada enquanto os dados fiscais e as credenciais
| nao forem validados. Nenhum token ou certificado deve ser gravado neste
| repositorio: use variaveis de ambiente no servidor de producao.
*/
$config['fiscal'] = array(
    'enabled' => FALSE,
    'emitente' => 'salinas',
    'documento_padrao' => 'nfe',
    'ambiente' => 'homologacao',
    'provider' => 'focus_nfe',
    'focus_nfe_token' => getenv('FOCUS_NFE_TOKEN') ?: null,
    'focus_nfe_url_homologacao' => getenv('FOCUS_NFE_URL_HOMOLOGACAO') ?: 'https://homologacao.focusnfe.com.br',
    'focus_nfe_url_producao' => getenv('FOCUS_NFE_URL_PRODUCAO') ?: 'https://api.focusnfe.com.br',
);
