<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
Normaliza nome de bairro para comparação/busca: maiúsculas, sem acento, sem
pontuação, com abreviações comuns expandidas (ex: "Dr." e "Doutor" viram a
mesma string). Usado tanto na tela de cadastro de bairros_frete (avisar
duplicata parecida) quanto na busca do autocomplete (achar "Dr. Delfino"
digitando "doutor").
*/
function normalizar_bairro($texto)
{
    $texto = mb_strtoupper((string) $texto, 'UTF-8');

    $com_acento = ['Á','À','Â','Ã','Ä','É','È','Ê','Ë','Í','Ì','Î','Ï','Ó','Ò','Ô','Õ','Ö','Ú','Ù','Û','Ü','Ç','Ñ'];
    $sem_acento = ['A','A','A','A','A','E','E','E','E','I','I','I','I','O','O','O','O','O','U','U','U','U','C','N'];
    $texto = str_replace($com_acento, $sem_acento, $texto);

    $texto = preg_replace('/[^A-Z0-9 ]/', ' ', $texto);
    $texto = trim(preg_replace('/\s+/', ' ', $texto));

    // Dicionário de abreviações comuns em nome de bairro/logradouro.
    // Revisável -- crescer conforme aparecerem novos casos nas listas reais.
    static $abreviacoes = [
        'DR'    => 'DOUTOR',
        'DRA'   => 'DOUTORA',
        'PE'    => 'PADRE',
        'SRA'   => 'SENHORA',
        'SR'    => 'SENHOR',
        'STO'   => 'SANTO',
        'STA'   => 'SANTA',
        'PROF'  => 'PROFESSOR',
        'PROFA' => 'PROFESSORA',
        'CEL'   => 'CORONEL',
        'GAL'   => 'GENERAL',
        'ENG'   => 'ENGENHEIRO',
        'VL'    => 'VILA',
        'JD'    => 'JARDIM',
        'CJ'    => 'CONJUNTO',
        'COND'  => 'CONDOMINIO',
        'RES'   => 'RESIDENCIAL',
        'PCA'   => 'PRACA',
    ];

    $palavras = explode(' ', $texto);
    foreach ($palavras as &$palavra) {
        if (isset($abreviacoes[$palavra])) {
            $palavra = $abreviacoes[$palavra];
        }
    }
    unset($palavra);

    return implode(' ', $palavras);
}

/*
Heurística de apoio para a tela de cadastro sugerir "já existe um bairro
parecido" -- não decide sozinha, só sugere revisão manual antes de gravar
duas linhas para o mesmo lugar.
*/
function bairros_parecidos($normalizadoA, $normalizadoB, $limiar = 85)
{
    if ($normalizadoA === $normalizadoB) {
        return true;
    }

    similar_text($normalizadoA, $normalizadoB, $percentual);
    return $percentual >= $limiar;
}
