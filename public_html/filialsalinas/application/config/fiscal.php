<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| Configuracao fiscal da filial Salinas.
|
| Mesmo fluxo de NFC-e/SINTEGRA de Montes Claros. A emissao permanece
| desabilitada ate que o cadastro do estabelecimento abaixo e o token da Focus
| de Salinas sejam preenchidos e testados em homologacao. Nunca copie o bloco
| `estabelecimento` de Montes Claros: isso emitiria cupom no CNPJ errado.
| Nenhum token ou certificado deve ser gravado neste repositorio: use
| variaveis de ambiente no servidor.
*/
$config['fiscal'] = array(
    'enabled' => FALSE,
    'emitente' => 'salinas',
    // NFC-e (modelo 65) e' o documento automatico para o cupom fiscal.
    'documento_padrao' => 'nfce',
    'ambiente' => 'homologacao',
    'provider' => 'focus_nfe',
    // A Focus controla a numeracao da NFC-e.
    'numeracao_nfce' => 'focus',
    'emitir_automaticamente' => TRUE,
    'imprimir_automaticamente' => TRUE,
    'emitir_apenas_pagamento_nao_dinheiro' => TRUE,
    'informacoes_adicionais_padrao' => 'Empresa Optante pelo Simples Nacional- Não gera créditos de ICMS e IPI',
    // Variaveis proprias de Salinas: as duas filiais rodam no mesmo servidor e
    // o token de Montes Claros nao pode ser reutilizado aqui por engano.
    'focus_nfe_token' => getenv('FOCUS_NFE_TOKEN_SALINAS_HOMOLOGACAO') ?: null,
    'focus_nfe_token_producao' => getenv('FOCUS_NFE_TOKEN_SALINAS_PRODUCAO') ?: null,
    'focus_nfe_url_homologacao' => getenv('FOCUS_NFE_URL_HOMOLOGACAO') ?: 'https://homologacao.focusnfe.com.br',
    'focus_nfe_url_producao' => getenv('FOCUS_NFE_URL_PRODUCAO') ?: 'https://api.focusnfe.com.br',

    // Dados cadastrais do estabelecimento de Salinas (registros 10/11 do
    // SINTEGRA e validacao da NFC-e). PENDENTE: preencher com os dados da
    // contabilidade. Enquanto o CNPJ estiver vazio a emissao e o SINTEGRA
    // falham com mensagem explicita.
    'estabelecimento' => array(
        'cnpj' => '',
        'inscricao_estadual' => '',
        'razao_social' => '',
        'municipio' => 'SALINAS',
        'codigo_municipio' => '3157005',
        'uf' => 'MG',
        'telefone' => '',
        'logradouro' => '',
        'numero' => '',
        'complemento' => '',
        'bairro' => '',
        'cep' => '',
        'contato' => '',
        'telefone_contato' => '',
    ),

    'sintegra' => array(
        // 3 = arquivo com registros de mercadorias e de totais.
        'codigo_estrutura' => '3',
        // 3 = operacoes interestaduais e internas.
        'codigo_natureza' => '3',
        // 1 = arquivo normal (nao e' retificacao nem desfazimento).
        'codigo_finalidade' => '1',
    ),

    // Codigo IBGE dos municipios de entrega (obrigatorio no grupo de entrega).
    'municipios_ibge' => array(
        'SALINAS' => '3157005',
    ),
);
