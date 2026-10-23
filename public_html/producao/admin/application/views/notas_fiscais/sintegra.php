<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10"><h2>Arquivo SINTEGRA</h2><ol class="breadcrumb"><li><a href="<?php echo base_url(array('loja','pedidos')) ?>">Pedidos</a></li><li><a href="<?php echo base_url(array('loja','notas-fiscais')) ?>">NFC-e</a></li><li class="active"><strong>SINTEGRA</strong></li></ol></div>
</div>
<div class="wrapper wrapper-content animated fadeInRight"><div class="ibox"><div class="ibox-content">

  <form class="row" method="get">
    <div class="col-sm-3"><label>Mês de referência</label><input type="month" class="form-control" name="mes" value="<?php echo html_escape($mes) ?>"></div>
    <div class="col-sm-3" style="padding-top:25px"><button class="btn btn-primary"><i class="fa fa-refresh"></i> Conferir</button></div>
    <?php if (empty($erro) && (!empty($conferencia['documentos']) || !empty($conferencia['entradas']))): ?>
      <div class="col-sm-3" style="padding-top:25px"><a class="btn btn-success" href="<?php echo base_url('loja/sintegra/download?mes='.$mes) ?>"><i class="fa fa-download"></i> Baixar arquivo</a></div>
    <?php endif ?>
  </form><hr>

  <div class="alert alert-info">
    Este arquivo cobre as <strong>saídas por NFC-e</strong> (registros 10, 11, 61, 61R, 75 e 90) e as
    <strong>notas de compra importadas por XML</strong> logo abaixo (registro 50, emitente terceiros).
    A competência fiscal usa a <strong>data real de autorização da NFC-e</strong> e, nas compras, a <strong>data de entrada</strong>.
    A conferência comercial por data da venda deve ser feita em relatório separado, pois não pode alterar a data constante do documento fiscal.
    NF-e modelo 55 própria e os itens das compras (registros 51 e 54) continuam por conta da contabilidade.
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
      <div class="col-sm-3"><div class="widget style1 navy-bg"><h4>NFC-e no período</h4><h2><?php echo (int) $conferencia['documentos'] ?></h2></div></div>
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

    <hr>
    <h3><i class="fa fa-truck"></i> Notas de entrada (compras) <small>registro 50</small></h3>

    <?php if (empty($conferencia['entradas_disponiveis'])): ?>
      <div class="alert alert-warning">A tabela de notas de entrada ainda não existe. Execute a migration <code>021_sintegra_entradas_montes_claros.sql</code>.</div>
    <?php else: ?>

      <form class="row" method="post" enctype="multipart/form-data" action="<?php echo base_url('loja/sintegra/importar-entradas') ?>">
        <input type="hidden" name="mes" value="<?php echo html_escape($mes) ?>">
        <div class="col-sm-5"><label>XML das NF-e de compra</label><input type="file" class="form-control" name="xml_entradas[]" accept=".xml,text/xml" multiple required></div>
        <div class="col-sm-3"><label>Finalidade da compra</label>
          <select class="form-control" name="finalidade">
            <?php foreach (Sintegra_service::$finalidades_entrada as $valor => $rotulo): ?>
              <option value="<?php echo $valor ?>"><?php echo html_escape($rotulo) ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="col-sm-3" style="padding-top:25px"><button class="btn btn-primary"><i class="fa fa-upload"></i> Importar XML</button></div>
      </form>
      <p class="text-muted" style="margin-top:8px">
        Só serve para o SINTEGRA: não lança estoque nem contas a pagar. O CFOP do fornecedor é convertido para o CFOP de entrada
        conforme a finalidade (ex.: 5102 → 1102, 5405 → 1403, 6102 → 2102). A data de entrada considerada é a data de saída
        informada na nota (ou a emissão, quando não houver) e define em qual mês a nota entra.
      </p>

      <div class="row">
        <div class="col-sm-3"><div class="widget style1 lazur-bg"><h4>Notas de entrada</h4><h2><?php echo count($conferencia['entradas']) ?></h2></div></div>
        <div class="col-sm-3"><div class="widget style1 lazur-bg"><h4>Total das compras</h4><h2>R$ <?php echo number_format($conferencia['total_entradas'], 2, ',', '.') ?></h2></div></div>
        <div class="col-sm-3"><div class="widget style1 lazur-bg"><h4>Registros 50</h4><h2><?php echo (int) $conferencia['registros_50'] ?></h2></div></div>
      </div>

      <div class="table-responsive"><table class="table table-striped table-bordered">
        <thead><tr><th>Entrada</th><th>Emissão</th><th>Fornecedor</th><th>Nota / série</th><th>CFOP nota → entrada</th><th>Finalidade</th><th class="text-right">Valor</th><th class="text-right">ICMS</th><th></th></tr></thead>
        <tbody>
        <?php if (empty($conferencia['entradas'])): ?>
          <tr><td colspan="9" class="text-center">Nenhuma nota de entrada neste mês.</td></tr>
        <?php else: foreach ($conferencia['entradas'] as $entrada): ?>
          <tr>
            <td><?php echo date('d/m/Y', strtotime($entrada->data_entrada)) ?></td>
            <td><?php echo date('d/m/Y', strtotime($entrada->data_emissao)) ?></td>
            <td><?php echo html_escape($entrada->razao_emitente) ?><br><small class="text-muted"><?php echo html_escape($entrada->cnpj_emitente) ?> · <?php echo html_escape($entrada->uf_emitente) ?></small></td>
            <td><?php echo html_escape($entrada->numero_nota) ?> / <?php echo html_escape($entrada->serie) ?></td>
            <td><?php foreach ($entrada->cfops as $cfop): ?>
              <div><?php echo html_escape($cfop->cfop_nota) ?> → <strong><?php echo html_escape($cfop->cfop) ?></strong>
                <small class="text-muted">R$ <?php echo number_format((float) $cfop->valor_total, 2, ',', '.') ?><?php echo (float) $cfop->aliquota > 0 ? ' · '.number_format((float) $cfop->aliquota, 2, ',', '.').'%' : '' ?></small></div>
            <?php endforeach ?></td>
            <td><?php echo html_escape(isset(Sintegra_service::$finalidades_entrada[$entrada->finalidade]) ? Sintegra_service::$finalidades_entrada[$entrada->finalidade] : $entrada->finalidade) ?></td>
            <td class="text-right">R$ <?php echo number_format((float) $entrada->valor_total, 2, ',', '.') ?></td>
            <td class="text-right">R$ <?php echo number_format((float) $entrada->valor_icms, 2, ',', '.') ?></td>
            <td class="text-nowrap">
              <a class="btn btn-xs btn-default" title="Baixar XML" href="<?php echo base_url('loja/sintegra/entrada-xml?id='.(int) $entrada->id) ?>"><i class="fa fa-download"></i></a>
              <form method="post" action="<?php echo base_url('loja/sintegra/excluir-entrada') ?>" style="display:inline" onsubmit="return confirm('Remover esta nota de entrada? Ela deixa de sair no SINTEGRA.')">
                <input type="hidden" name="id" value="<?php echo (int) $entrada->id ?>">
                <input type="hidden" name="mes" value="<?php echo html_escape($mes) ?>">
                <button class="btn btn-xs btn-danger" title="Remover"><i class="fa fa-trash"></i></button>
              </form>
            </td>
          </tr>
        <?php endforeach; endif ?>
        </tbody>
      </table></div>

    <?php endif ?>

  <?php endif ?>

</div></div></div>

<?php if (!empty($notification)): ?>
<script>
  $(function(){ showNotification(<?php echo json_encode($notification['type']) ?>, <?php echo json_encode($notification['title']) ?>, <?php echo json_encode($notification['message']) ?>); });
</script>
<?php endif ?>
