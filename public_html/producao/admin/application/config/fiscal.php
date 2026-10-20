<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| Configuracao fiscal de Montes Claros.
|
| A emissao permanece desabilitada enquanto os dados fiscais e as credenciais
| nao forem validados. Nenhum token ou certificado deve ser gravado neste
| repositorio: use variaveis de ambiente no servidor de producao.
*/
$config['fiscal'] = array(
    // Emissao pelo caixa liberada para a operacao de Montes Claros.
    'enabled' => TRUE,
    'emitente' => 'montes_claros',
    // NFC-e (modelo 65) e' o documento automatico para o cupom fiscal.
    'documento_padrao' => 'nfce',
    'ambiente' => 'producao',
    'provider' => 'focus_nfe',
    // A Focus deve controlar a numeracao da NFC-e. Ela e' independente da
    // numeracao da NF-e (modelo 55) hoje usada manualmente na Optimus.
    'numeracao_nfce' => 'focus',
    // Quando habilitada em producao, emite apos a confirmacao integral do pagamento.
    'emitir_automaticamente' => TRUE,
    'imprimir_automaticamente' => TRUE,
    'emitir_apenas_pagamento_nao_dinheiro' => TRUE,
    // Texto exigido pela contabilidade para constar nos dados adicionais.
    'informacoes_adicionais_padrao' => 'Empresa Optante pelo Simples Nacional- Não gera créditos de ICMS e IPI',
    // As credenciais nunca ficam no repositorio. Mantemos tokens separados
    // para impedir que uma troca de ambiente reutilize por engano o token de
    // homologacao em producao. O nome antigo continua como compatibilidade.
    'focus_nfe_token' => getenv('FOCUS_NFE_TOKEN_HOMOLOGACAO') ?: getenv('FOCUS_NFE_TOKEN') ?: null,
    'focus_nfe_token_producao' => getenv('FOCUS_NFE_TOKEN_PRODUCAO') ?: null,
    'focus_nfe_url_homologacao' => getenv('FOCUS_NFE_URL_HOMOLOGACAO') ?: 'https://homologacao.focusnfe.com.br',
    'focus_nfe_url_producao' => getenv('FOCUS_NFE_URL_PRODUCAO') ?: 'https://api.focusnfe.com.br',

    // Dados cadastrais do estabelecimento. Alimentam os registros 10 e 11 do
    // SINTEGRA e a validacao de operacao interna da NFC-e. Conferidos com o
    // XML autorizado da Focus e com o SINTEGRA de 06/2026 da contabilidade.
    'estabelecimento' => array(
        'cnpj' => '49765943000102',
        'inscricao_estadual' => '0045592750099',
        'razao_social' => 'FLORES E DECORACOES LTDA',
        'municipio' => 'MONTES CLAROS',
        'codigo_municipio' => '3143302',
        'uf' => 'MG',
        'telefone' => '3891281867',
        'logradouro' => 'Avenida Joao Luiz de Almeida',
        'numero' => '374',
        'complemento' => '',
        'bairro' => 'Vila Guilhermina',
        'cep' => '39400466',
        'contato' => 'Luciano Santana de Sousa',
        'telefone_contato' => '003891281867',
    ),

    // Codigos do registro 10 do SINTEGRA (mesmos valores usados pela
    // contabilidade no arquivo de 06/2026).
    'sintegra' => array(
        // 3 = arquivo com registros de mercadorias e de totais.
        'codigo_estrutura' => '3',
        // 3 = operacoes interestaduais e internas.
        'codigo_natureza' => '3',
        // 1 = arquivo normal (nao e' retificacao nem desfazimento).
        'codigo_finalidade' => '1',
    ),

    // Codigo IBGE dos municipios de entrega. E' obrigatorio no grupo de
    // entrega da NFC-e; municipio fora desta lista bloqueia a emissao com
    // mensagem explicita em vez de virar rejeicao da SEFAZ.
    'municipios_ibge' => array(
        'MONTES CLAROS' => '3143302',
    ),
);
