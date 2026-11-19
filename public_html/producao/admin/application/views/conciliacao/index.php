<?php
$brl = function ($v) { return 'R$ '.number_format((float) $v, 2, ',', '.'); };
$dt = function ($d) { return date('d/m/Y', strtotime($d)); };
$dif = function ($v) use ($brl) {
    if (abs($v) < 0.01) { return '<span class="label label-primary">OK</span>'; }
    return '<span class="label label-danger">'.($v > 0 ? '+' : '').$brl($v).'</span>';
};
$nomes_categoria = array('cartao' => 'Cartão', 'pix' => 'PIX', 'outros' => 'Outros');
?>
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>Conciliação bancária</h2>
    <ol class="breadcrumb"><li><a href="#">Relatórios</a></li><li class="active"><strong>Conciliação bancária</strong></li></ol>
  </div>
</div>

<div class="wrapper wrapper-content">
  <?php if (!empty($mensagem)): ?><div class="alert alert-success"><?php echo html_escape($mensagem); ?></div><?php endif; ?>
  <?php if (!empty($erro)): ?><div class="alert alert-danger"><?php echo html_escape($erro); ?></div><?php endif; ?>
  <?php if (!$configurado): ?><div class="alert alert-warning"><?php echo html_escape($erro_config); ?></div><?php endif; ?>

  <div class="ibox"><div class="ibox-content">
    <form method="get" class="form-inline" action="<?php echo base_url(array('loja', 'conciliacao-bancaria')); ?>" style="display:inline-block;">
      <label>De</label> <input type="date" name="inicio" class="form-control" value="<?php echo $inicio; ?>">
      <label>até</label> <input type="date" name="fim" class="form-control" value="<?php echo $fim; ?>">
      <button class="btn btn-default">Filtrar</button>
    </form>
    <form method="post" action="<?php echo base_url(array('loja', 'conciliacao-bancaria', 'importar')); ?>" style="display:inline-block; margin-left:10px;">
      <input type="hidden" name="inicio" value="<?php echo $inicio; ?>">
      <input type="hidden" name="fim" value="<?php echo $fim; ?>">
      <button class="btn btn-primary" <?php echo $configurado ? '' : 'disabled'; ?>><i class="fa fa-refresh"></i> Importar extrato do Sicoob</button>
    </form>
    <p class="text-muted" style="margin-top:8px;">
      Última importação: <?php echo $ultima_importacao ? date('d/m/Y H:i', strtotime($ultima_importacao)) : 'nunca'; ?>.
      Cartão esperado = vendas em cartão pela data de liquidação (D+1) menos taxas de maquininha e antecipação.
      PIX esperado = receitas PIX pela data do pagamento.
    </p>
  </div></div>

  <div class="ibox"><div class="ibox-title"><h5>Resumo por dia</h5></div><div class="ibox-content table-responsive">
    <table class="table table-condensed table-hover">
      <thead><tr>
        <th>Dia</th>
        <th class="text-right">PIX sistema</th><th class="text-right">PIX banco</th><th>Dif.</th>
        <th class="text-right">Cartão bruto</th><th class="text-right">(−) Taxas</th><th class="text-right">Cartão líquido</th><th class="text-right">Cartão banco</th><th>Dif.</th>
        <th class="text-right">Outros créditos</th>
      </tr></thead>
      <tbody>
      <?php $t = array('pix_esperado'=>0,'pix_banco'=>0,'cartao_bruto'=>0,'cartao_taxas'=>0,'cartao_esperado'=>0,'cartao_banco'=>0,'outros_banco'=>0); ?>
      <?php if (empty($resumo)): ?>
        <tr><td colspan="10" class="text-center text-muted">Nenhum movimento no período</td></tr>
      <?php else: foreach ($resumo as $dia => $r): foreach ($t as $k => $_) { $t[$k] += $r[$k]; } ?>
        <tr>
          <td><?php echo $dt($dia); ?></td>
          <td class="text-right"><?php echo $brl($r['pix_esperado']); ?></td>
          <td class="text-right"><?php echo $brl($r['pix_banco']); ?></td>
          <td><?php echo $dif($r['pix_dif']); ?></td>
          <td class="text-right"><?php echo $brl($r['cartao_bruto']); ?></td>
          <td class="text-right"><?php echo $brl($r['cartao_taxas']); ?></td>
          <td class="text-right"><?php echo $brl($r['cartao_esperado']); ?></td>
          <td class="text-right"><?php echo $brl($r['cartao_banco']); ?></td>
          <td><?php echo $dif($r['cartao_dif']); ?></td>
          <td class="text-right"><?php echo $brl($r['outros_banco']); ?></td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
      <?php if (!empty($resumo)): ?>
      <tfoot><tr style="font-weight:700;">
        <td>TOTAL</td>
        <td class="text-right"><?php echo $brl($t['pix_esperado']); ?></td>
        <td class="text-right"><?php echo $brl($t['pix_banco']); ?></td>
        <td><?php echo $dif($t['pix_banco'] - $t['pix_esperado']); ?></td>
        <td class="text-right"><?php echo $brl($t['cartao_bruto']); ?></td>
        <td class="text-right"><?php echo $brl($t['cartao_taxas']); ?></td>
        <td class="text-right"><?php echo $brl($t['cartao_esperado']); ?></td>
        <td class="text-right"><?php echo $brl($t['cartao_banco']); ?></td>
        <td><?php echo $dif($t['cartao_banco'] - $t['cartao_esperado']); ?></td>
        <td class="text-right"><?php echo $brl($t['outros_banco']); ?></td>
      </tr></tfoot>
      <?php endif; ?>
    </table>
  </div></div>

  <div class="ibox"><div class="ibox-title"><h5>Extrato importado</h5></div><div class="ibox-content table-responsive">
    <table class="table table-condensed table-striped">
      <thead><tr><th>Data</th><th>Descrição</th><th>Complemento</th><th>Documento</th><th>Categoria</th><th class="text-right">Valor</th></tr></thead>
      <tbody>
      <?php if (empty($extrato)): ?>
        <tr><td colspan="6" class="text-center text-muted">Extrato ainda não importado para este período</td></tr>
      <?php else: foreach ($extrato as $e): ?>
        <tr>
          <td><?php echo $dt($e->data); ?></td>
          <td><?php echo html_escape($e->descricao); ?></td>
          <td><?php echo html_escape($e->complemento); ?></td>
          <td><?php echo html_escape($e->documento); ?></td>
          <td><?php echo $e->tipo === 'C' ? $nomes_categoria[$e->categoria] : 'Débito'; ?></td>
          <td class="text-right" style="color:<?php echo $e->tipo === 'C' ? '#009688' : '#ec4758'; ?>"><?php echo ($e->tipo === 'D' ? '−' : '').$brl($e->valor); ?></td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div></div>
</div>
