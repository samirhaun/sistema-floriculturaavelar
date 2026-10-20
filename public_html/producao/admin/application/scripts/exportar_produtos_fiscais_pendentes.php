<?php
/* Executado somente via CLI para exportar a revisão fiscal mensal. */
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Somente CLI.\n");
}

define('BASEPATH', true);
define('ENVIRONMENT', 'production');
require dirname(__DIR__).'/config/database.php';

$mes = isset($argv[1]) ? $argv[1] : '2026-08';
if (!preg_match('/^\d{4}-\d{2}$/', $mes)) exit("Mes invalido. Use AAAA-MM.\n");
$destino = isset($argv[2]) ? $argv[2] : '/tmp/RELATORIO_PRODUTOS_FISCAIS_PENDENTES_'.$mes.'.csv';
$inicio = $mes.'-01';
$fim = date('Y-m-t', strtotime($inicio));

$configuracao = $db['default'];
$mysqli = mysqli_connect($configuracao['hostname'], $configuracao['username'], $configuracao['password'], $configuracao['database']);
if (!$mysqli) exit("Falha na conexao com o banco.\n");
mysqli_set_charset($mysqli, 'utf8');

$inicio_sql = mysqli_real_escape_string($mysqli, $inicio.' 00:00:00');
$fim_sql = mysqli_real_escape_string($mysqli, $fim.' 23:59:59');
$sql = "SELECT p.id, p.titulo, c.nome AS categoria, gs.nome AS grupo_sugerido,
        p.grupo_fiscal_sugestao_confianca, COUNT(DISTINCT ped.id) AS pedidos_afetados,
        COUNT(*) AS linhas_vendidas, COALESCE(SUM(pp.quantidade),0) AS unidades
    FROM pedidos ped
    JOIN pedido_produto pp ON pp.pedidos_id = ped.id
    JOIN produtos p ON p.id = pp.produtos_id
    LEFT JOIN categorias c ON c.id = p.categorias_id
    LEFT JOIN grupos_fiscais_nfce gs ON gs.id = p.grupo_fiscal_sugerido_id
    WHERE ped.data_pedido >= '{$inicio_sql}' AND ped.data_pedido <= '{$fim_sql}'
      AND ped.status_pedido NOT IN (4,5)
      AND p.grupos_fiscais_nfce_id IS NULL
      AND (p.nfce_ncm IS NULL OR p.nfce_ncm = '')
      AND EXISTS (SELECT 1 FROM contas_receber cr_ok WHERE cr_ok.pedidos_id = ped.id
                  AND cr_ok.status = 1 AND cr_ok.valor > 0 AND cr_ok.forma_pgto IN (2,3,4,5,6,7,8,9))
      AND NOT EXISTS (SELECT 1 FROM contas_receber cr_pendente WHERE cr_pendente.pedidos_id = ped.id
                      AND (cr_pendente.status <> 1 OR cr_pendente.valor <= 0))
    GROUP BY p.id, p.titulo, c.nome, gs.nome, p.grupo_fiscal_sugestao_confianca
    ORDER BY unidades DESC, p.titulo ASC";
$resultado = mysqli_query($mysqli, $sql);
if (!$resultado) exit("Falha na consulta do relatorio.\n");

$arquivo = fopen($destino, 'wb');
if (!$arquivo) exit("Nao foi possivel gravar o relatorio.\n");
fwrite($arquivo, "\xEF\xBB\xBF");
fputcsv($arquivo, array('produto_id', 'produto', 'categoria', 'grupo_sugerido', 'confianca', 'pedidos_afetados', 'linhas_vendidas', 'unidades'), ';');
$linhas = 0;
while ($linha = mysqli_fetch_assoc($resultado)) {
    fputcsv($arquivo, array($linha['id'], $linha['titulo'], $linha['categoria'], $linha['grupo_sugerido'],
        $linha['grupo_fiscal_sugestao_confianca'], $linha['pedidos_afetados'], $linha['linhas_vendidas'],
        number_format((float) $linha['unidades'], 4, ',', '')), ';');
    $linhas++;
}
fclose($arquivo);
echo "RELATORIO={$destino}; PRODUTOS={$linhas}\n";
