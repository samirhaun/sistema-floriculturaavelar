<?php
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Somente CLI.');
}
fwrite(STDERR, "Carregando CodeIgniter para o lote fiscal...\n");
putenv('FISCAL_CLI_HOMOLOGACAO=1');

$argumentos = $argv;
$metodo = isset($argumentos[1]) ? $argumentos[1] : 'index';
$parametros = array_slice($argumentos, 2);

// O index antigo desta instalacao nao preserva corretamente o primeiro
// segmento recebido pelo CLI. Forcamos apenas controlador/metodo e mantemos
// os parametros como segmentos para o dispatcher do CodeIgniter.
$routing = array('controller' => 'Cli_nfce_homologacao', 'function' => $metodo);
$_SERVER['argv'] = array_merge(array(__FILE__, 'Cli_nfce_homologacao', $metodo), $parametros);
$argv = $_SERVER['argv'];
$argc = count($argv);

require __DIR__.'/index.php';
