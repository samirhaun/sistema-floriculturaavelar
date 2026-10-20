<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10"><h2>Arquivo SINTEGRA</h2><ol class="breadcrumb"><li><a href="<?php echo base_url(array('loja','pedidos')) ?>">Pedidos</a></li><li><a href="<?php echo base_url(array('loja','notas-fiscais')) ?>">NFC-e</a></li><li class="active"><strong>SINTEGRA</strong></li></ol></div>
</div>
<div class="wrapper wrapper-content animated fadeInRight"><div class="ibox"><div class="ibox-content">

  <form class="row" method="get">
    <div class="col-sm-3"><label>Mês de referência</label><input type="month" class="form-control" name="mes" value="<?php echo html_escape($mes) ?>"></div>
    <div class="col-sm-3" style="padding-top:25px"><button class="btn btn-primary"><i class="fa fa-refresh"></i> Conferir</button></div>
    <?php if (empty($erro) && !empty($conferencia['documentos'])): ?>
      <div class="col-sm-3" style="padding-top:25px"><a class="btn btn-success" href="<?php echo base_url('loja/sintegra/download?mes='.$mes) ?>"><i class="fa fa-download"></i> Baixar arquivo</a></div>
    <?php endif ?>
  </form><hr>

  <div class="alert alert-info">
    Este arquivo cobre <strong>somente as saídas por NFC-e</strong> (registros 10, 11, 61, 61R, 75 e 90).
    A competência fiscal usa a <strong>data real de autorização da NFC-e</strong>. A conferência comercial por data da venda
    deve ser feita em relatório separado, pois não pode alterar a data constante do documento fiscal.
    As NF-e modelo 55 e as notas de entrada de fornecedor (registros 50, 51 e 54) não são emitidas por este
    sistema e continuam por conta da contabilidade.
  </div>

  <?php if (!empty($erro)): ?>
    <div class="alert alert-danger"><?php echo html_escape($erro) ?></div>
  <?php else: ?>

    <?php if (!empty($conferencia['inconsistencias'])): ?>
      <div class="alert alert-danger">
        <strong>Bloqueia a geração:</strong> há nota autorizada sem número ou sem valor no período.
        <ul><?php foreach ($conferencia['inconsistencias'] as $nota): ?>
          <li>Nota #<?php echo (int) $nota->id ?> — pedido <a href="<?php echo base_url('loja/detalhes-pedido?id='.$nota->pedidos_id) ?>">#<?php echo (int) $nota->pedidos_id ?></a></li>
        <?php endforeach ?></ul>
      </div>
    <?php endif ?>

    <?php if (!empty($conferencia['pendentes'])): ?>
      <div class="alert alert-warning">
        <strong>Fora do arquivo:</strong> <?php echo count($conferencia['pendentes']) ?> pedido(s) do período com nota não autorizada
        (pendente, erro ou rejeitada). Resolva antes de entregar o arquivo, senão o faturamento declarado fica menor que o real.
        <ul><?php foreach ($conferencia['pendentes'] as $nota): ?>
          <li>Pedido <a href="<?php echo base_url('loja/detalhes-pedido?id='.$nota->pedidos_id) ?>">#<?php echo (int) $nota->pedidos_id ?></a> — <?php echo html_escape($nota->status) ?><?php echo $nota->erro ? ': '.html_escape($nota->erro) : '' ?></li>
        <?php endforeach ?></ul>
      </div>
    <?php endif ?>

    <div class="row">
      <div class="col-sm-3"><div class="widget style1 navy-bg"><h4>Documentos</h4><h2><?php echo (int) $conferencia['documentos'] ?></h2></div></div>
      <div class="col-sm-3"><div class="widget style1 navy-bg"><h4>Total das notas (registro 61)</h4><h2>R$ <?php echo number_format($conferencia['total_liquido'], 2, ',', '.') ?></h2></div></div>
      <div class="col-sm-3"><div class="widget style1 navy-bg"><h4>Total dos itens (registro 61R)</h4><h2>R$ <?php echo number_format($conferencia['total_bruto'], 2, ',', '.') ?></h2></div></div>
      <div class="col-sm-3"><div class="widget style1 navy-bg"><h4>Linhas no arquivo</h4><h2><?php echo (int) $conferencia['registros'] ?></h2></div></div>
    </div>

    <?php if (abs($conferencia['total_bruto'] - $conferencia['total_liquido']) >= 0.01): ?>
      <p class="text-muted">
        A diferença de R$ <?php echo number_format(abs($conferencia['total_bruto'] - $conferencia['total_liquido']), 2, ',', '.') ?>
        entre os dois totais é esperada quando houve desconto ou frete no período: o registro 61 leva o valor do documento
        e o 61R leva o valor bruto dos produtos, conforme o layout.
      </p>
    <?php endif ?>

    <div class="table-responsive"><table class="table table-striped table-bordered">
      <thead><tr><th>Dia</th><th>Série</th><th>Nº inicial</th><th>Nº final</th><th>Documentos</th><th>Valor</th></tr></thead>
      <tbody>
      <?php if (empty($conferencia['dias'])): ?>
        <tr><td colspan="6" class="text-center">Nenhuma NFC-e autorizada no período.</td></tr>
      <?php else: foreach ($conferencia['dias'] as $dia): ?>
        <tr>
          <td><?php echo date('d/m/Y', strtotime($dia->dia)) ?></td>
          <td><?php echo html_escape($dia->serie) ?></td>
          <td><?php echo (int) $dia->numero_inicial ?></td>
          <td><?php echo (int) $dia->numero_final ?></td>
          <td><?php echo (int) $dia->documentos ?></td>
          <td>R$ <?php echo number_format((float) $dia->valor_total, 2, ',', '.') ?></td>
        </tr>
      <?php endforeach; endif ?>
      </tbody>
    </table></div>

    <p class="text-muted">
      Produtos no resumo mensal (registro 61R): <?php echo (int) $conferencia['itens'] ?> ·
      Cadastros de produto (registro 75): <?php echo (int) $conferencia['produtos'] ?>
    </p>

  <?php endif ?>

</div></div></div>
