<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10"><h2>Notas fiscais emitidas</h2><ol class="breadcrumb"><li><a href="<?php echo base_url(array('loja','pedidos')) ?>">Pedidos</a></li><li class="active"><strong>NFC-e</strong></li></ol></div>
</div>
<div class="wrapper wrapper-content animated fadeInRight"><div class="ibox"><div class="ibox-content">
  <form class="row" method="get">
    <div class="col-sm-3"><label>De</label><input type="date" class="form-control" name="inicio" value="<?php echo html_escape($inicio) ?>"></div>
    <div class="col-sm-3"><label>Até</label><input type="date" class="form-control" name="fim" value="<?php echo html_escape($fim) ?>"></div>
    <div class="col-sm-3"><label>Situação</label><select class="form-control" name="status"><option value="">Todas</option><?php foreach(array('pendente','enviando','autorizada','rejeitada','erro','cancelada') as $opcao): ?><option value="<?php echo $opcao ?>" <?php echo $status === $opcao ? 'selected' : '' ?>><?php echo ucfirst($opcao) ?></option><?php endforeach ?></select></div>
    <div class="col-sm-3" style="padding-top:25px"><button class="btn btn-primary"><i class="fa fa-filter"></i> Filtrar</button></div>
  </form><hr>
  <div class="table-responsive"><table class="table table-striped table-bordered"><thead><tr><th>Emissão</th><th>Pedido</th><th>Cliente</th><th>NFC-e</th><th>Valor</th><th>Situação</th><th>Ações</th></tr></thead><tbody>
  <?php if (empty($notas)): ?><tr><td colspan="7" class="text-center">Nenhuma NFC-e no período.</td></tr><?php else: foreach($notas as $nota): ?><tr>
    <td><?php echo date('d/m/Y H:i', strtotime($nota->created_at)) ?></td><td>#<?php echo (int) $nota->pedidos_id ?></td><td><?php echo html_escape($nota->cliente_nome) ?></td>
    <td><?php echo $nota->numero ? 'Série '.html_escape($nota->serie).' / Nº '.html_escape($nota->numero) : '-' ?></td><td>R$ <?php echo number_format((float) ($nota->valor_total !== null ? $nota->valor_total : $nota->valor_pedido), 2, ',', '.') ?></td>
    <td><span class="label label-<?php echo $nota->status === 'autorizada' ? 'primary' : ($nota->status === 'erro' || $nota->status === 'rejeitada' ? 'danger' : 'warning') ?>"><?php echo html_escape($nota->status) ?></span><?php if($nota->erro): ?><br><small class="text-danger"><?php echo html_escape($nota->erro) ?></small><?php endif ?></td>
    <td><?php if($nota->status === 'autorizada'): ?><a target="_blank" class="btn btn-xs btn-primary" href="<?php echo base_url('loja/nfce/imprimir?pedido='.$nota->pedidos_id) ?>"><i class="fa fa-print"></i> Imprimir</a> <a class="btn btn-xs btn-default" href="<?php echo base_url('loja/nfce/xml?pedido='.$nota->pedidos_id) ?>">XML</a><?php else: ?><a class="btn btn-xs btn-default" href="<?php echo base_url('loja/detalhes-pedido?id='.$nota->pedidos_id) ?>">Pedido</a><?php endif ?></td>
  </tr><?php endforeach; endif ?></tbody></table></div>
</div></div></div>
