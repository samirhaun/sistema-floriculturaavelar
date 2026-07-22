<style>
    .card-vendas {
        border-radius: 6px; color: #fff; padding: 12px 14px; margin-bottom: 10px;
        position: relative; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.12);
    }
    .card-vendas .card-icon { position: absolute; right: 10px; top: 8px; font-size: 34px; opacity: 0.18; }
    .card-vendas .card-label { font-size: 10px; text-transform: uppercase; letter-spacing: 0.8px; opacity: 0.85; }
    .card-vendas .card-value { font-size: 18px; font-weight: 700; margin-top: 2px; }

    .breakdown-box {
        border: 1px solid #e7eaec; border-radius: 6px; padding: 12px 15px;
        margin-bottom: 15px; background: #fafafa;
    }
    .breakdown-box h5 { margin-top: 0; font-weight: 600; font-size: 14px; color: #555; }
    .breakdown-box table { margin-bottom: 0; font-size: 12px; }
    .breakdown-box table td, .breakdown-box table th { padding: 4px 8px; border-top: none; border-bottom: 1px solid #eee; }
</style>

<div class="wrapper wrapper-content animated fadeInRight" id="tabela_resultados">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title hidden-print" style="min-height: 50px;">
                    <div class="row">
                        <div class="col-lg-6">
                            <h5>Resultado: <?php echo $filtro_inicio; ?> a <?php echo $filtro_fim; ?></h5>
                        </div>
                        <div class="col-lg-6 text-right">
                            <button class="btn btn-success" onclick="window.print()" type="button"><i class="fa fa-print"></i> Imprimir</button>
                        </div>
                    </div>
                </div>
                <div class="ibox-content">

                    <!-- CARDS -->
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="card-vendas" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="fa fa-shopping-cart card-icon"></i>
                                <div class="card-label">Total vendido</div>
                                <div class="card-value">R$ <?php echo number_format($total_vendido, 2, ',', '.'); ?></div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card-vendas" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                                <i class="fa fa-cubes card-icon"></i>
                                <div class="card-label">Itens vendidos</div>
                                <div class="card-value"><?php echo number_format($total_itens, 0, ',', '.'); ?></div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card-vendas" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <i class="fa fa-file-text card-icon"></i>
                                <div class="card-label">Pedidos</div>
                                <div class="card-value"><?php echo number_format($total_pedidos, 0, ',', '.'); ?></div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card-vendas" style="background: linear-gradient(135deg, #00b4db 0%, #0083B0 100%);">
                                <i class="fa fa-ticket card-icon"></i>
                                <div class="card-label">Ticket médio</div>
                                <div class="card-value">R$ <?php echo number_format($ticket_medio, 2, ',', '.'); ?></div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card-vendas" style="background: linear-gradient(135deg, #ff7e5f 0%, #feb47b 100%);">
                                <i class="fa fa-percent card-icon"></i>
                                <div class="card-label">Descontos aplicados</div>
                                <div class="card-value">R$ <?php echo number_format($total_descontos, 2, ',', '.'); ?></div>
                                <div style="font-size:11px; opacity:.85;"><?php echo number_format(isset($qtd_pedidos_desconto) ? $qtd_pedidos_desconto : 0, 0, ',', '.'); ?> pedidos com desconto</div>
                            </div>
                        </div>
                    </div>

                    <!-- VENDEDORES -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="breakdown-box">
                                <h5><i class="fa fa-user text-info"></i> Vendas por vendedor</h5>
                                <div class="table-responsive">
                                    <table class="table table-condensed" style="margin-bottom:0;">
                                        <thead>
                                            <tr>
                                                <th>Vendedor</th>
                                                <th class="text-right">Pedidos</th>
                                                <th class="text-right">Total vendido</th>
                                                <th class="text-right">Ticket médio</th>
                                                <th class="text-right" style="width:15%;">%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(!empty($vendas_vendedores)): ?>
                                                <?php foreach($vendas_vendedores as $v):
                                                    $total_vendedor = (float) $v->total;
                                                    $pct_vendedor = $total_vendido > 0 ? ($total_vendedor / $total_vendido * 100) : 0;
                                                    $ticket_vendedor = $v->total_pedidos > 0 ? ($total_vendedor / $v->total_pedidos) : 0;
                                                ?>
                                                <tr>
                                                    <td><strong><?php echo $v->vendedor; ?></strong></td>
                                                    <td class="text-right"><?php echo number_format($v->total_pedidos, 0, ',', '.'); ?></td>
                                                    <td class="text-right"><strong>R$ <?php echo number_format($total_vendedor, 2, ',', '.'); ?></strong></td>
                                                    <td class="text-right">R$ <?php echo number_format($ticket_vendedor, 2, ',', '.'); ?></td>
                                                    <td class="text-right">
                                                        <div style="display:flex; align-items:center; gap:8px;">
                                                            <div style="flex:1; height:10px; background:#e0e0e0; border-radius:5px; overflow:hidden;">
                                                                <div style="width:<?php echo min(100, round($pct_vendedor)); ?>%; height:100%; background:#23c6c8; border-radius:5px;"></div>
                                                            </div>
                                                            <span style="font-size:10px; min-width:35px; text-align:right;"><?php echo number_format($pct_vendedor, 1); ?>%</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr><td colspan="5" class="text-center text-muted">Nenhuma venda por vendedor encontrada</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FORMAS DE PAGAMENTO -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="breakdown-box">
                                <h5><i class="fa fa-credit-card text-warning"></i> Vendas por forma de pagamento</h5>
                                <div class="table-responsive">
                                    <table class="table table-condensed" style="margin-bottom:0;">
                                        <thead>
                                            <tr>
                                                <th>Forma de pagamento</th>
                                                <th class="text-right">Pedidos</th>
                                                <th class="text-right">Parcelas</th>
                                                <th class="text-right">Total</th>
                                                <th class="text-right" style="width:15%;">%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $nomes_forma_pgto = array(
                                                1 => 'Dinheiro', 9 => 'Pix', 2 => 'Débito',
                                                3 => 'Crédito 1x', 4 => 'Crédito 2x', 5 => 'Crédito 3x',
                                                6 => 'Crédito 4x', 7 => 'Duplicata', 8 => 'Cheque'
                                            );
                                            if(!empty($vendas_formas_pgto)):
                                                foreach($vendas_formas_pgto as $f):
                                                    $total_fp = (float) $f->total;
                                                    $pct_fp = $total_vendido > 0 ? ($total_fp / $total_vendido * 100) : 0;
                                                    $nome_fp = isset($nomes_forma_pgto[$f->forma_pgto]) ? $nomes_forma_pgto[$f->forma_pgto] : 'Outros';
                                                    $eh_cartao = in_array((int) $f->forma_pgto, array(2, 3, 4, 5, 6));
                                                    $badge_cartao = $eh_cartao ? ' <span class="badge" style="background:#f8ac59; color:#fff; font-size:9px;">D+1</span>' : '';
                                            ?>
                                            <tr>
                                                <td><strong><?php echo $nome_fp; ?></strong><?php echo $badge_cartao; ?></td>
                                                <td class="text-right"><?php echo number_format($f->total_pedidos, 0, ',', '.'); ?></td>
                                                <td class="text-right"><?php echo number_format($f->total_parcelas, 0, ',', '.'); ?></td>
                                                <td class="text-right"><strong>R$ <?php echo number_format($total_fp, 2, ',', '.'); ?></strong></td>
                                                <td class="text-right">
                                                    <div style="display:flex; align-items:center; gap:8px;">
                                                        <div style="flex:1; height:10px; background:#e0e0e0; border-radius:5px; overflow:hidden;">
                                                            <div style="width:<?php echo min(100, round($pct_fp)); ?>%; height:100%; background:#f8ac59; border-radius:5px;"></div>
                                                        </div>
                                                        <span style="font-size:10px; min-width:35px; text-align:right;"><?php echo number_format($pct_fp, 1); ?>%</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr><td colspan="5" class="text-center text-muted">Nenhuma venda por forma de pagamento encontrada</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                        <?php if(!empty($vendas_formas_pgto)): ?>
                                        <tfoot>
                                            <tr style="font-weight:700; border-top:2px solid #ddd;">
                                                <td>TOTAL</td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right">R$ <?php echo number_format($total_vendido, 2, ',', '.'); ?></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                        <?php endif; ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PRODUTOS -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="breakdown-box">
                                <h5><i class="fa fa-cube text-success"></i> Vendas por produto</h5>
                                <table class="table table-condensed" style="margin-bottom:0;">
                                    <thead><tr><th style="width:30px;"></th><th>Produto</th><th>Categoria</th><th class="text-right sortable" data-sort="qtd" style="cursor:pointer;">Qtd <i class="fa fa-sort"></i></th><th class="text-right">Preço venda</th><th class="text-right">Custo un.</th><th class="text-right sortable" data-sort="total" style="cursor:pointer;">Total <i class="fa fa-sort"></i></th><th class="text-right">Lucro</th><th class="text-right" style="width:15%;">%</th></tr></thead>
                                    <tbody>
                                        <?php
                                        $total_geral = $total_vendido;
                                        $idx = 0;
                                        if(!empty($produtos)):
                                        foreach($produtos as $p):
                                            $idx++;
                                            $pct = $total_geral > 0 ? ($p->total / $total_geral * 100) : 0;
                                        ?>
                                        <tr class="cat-toggle" data-target="#prod-detail-<?php echo $idx; ?>" style="cursor:pointer;">
                                            <td><i class="fa fa-chevron-right cat-chevron"></i></td>
                                            <td><strong><?php echo $p->produto; ?></strong></td>
                                            <td><?php echo $p->categoria ?: '-'; ?></td>
                                            <td class="text-right"><?php echo (int)$p->qtd; ?></td>
                                            <td class="text-right">R$ <?php echo number_format($p->preco_venda, 2, ',', '.'); ?></td>
                                            <td class="text-right" style="color:#888;"><?php echo $p->preco_custo > 0 ? 'R$ '.number_format($p->preco_custo, 2, ',', '.') : '-'; ?></td>
                                            <td class="text-right"><strong>R$ <?php echo number_format($p->total, 2, ',', '.'); ?></strong></td>
                                            <td class="text-right" style="color:<?php echo ($p->total_custo > 0 && $p->total > $p->total_custo) ? '#11998e' : '#888'; ?>;">
                                                <?php 
                                                $lucro = $p->total - $p->total_custo;
                                                if($p->total_custo > 0){
                                                    echo 'R$ '.number_format($lucro, 2, ',', '.');
                                                } else {
                                                    echo '-';
                                                }
                                                ?>
                                            </td>
                                            <td class="text-right">
                                                <div style="display:flex; align-items:center; gap:8px;">
                                                    <div style="flex:1; height:10px; background:#e0e0e0; border-radius:5px; overflow:hidden;">
                                                        <div style="width:<?php echo round($pct); ?>%; height:100%; background:<?php echo ($pct > 15 ? '#11998e' : ($pct > 5 ? '#23c6c8' : '#1ab394')); ?>; border-radius:5px;"></div>
                                                    </div>
                                                    <span style="font-size:10px; min-width:35px; text-align:right;"><?php echo number_format($pct, 1); ?>%</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="prod-detail-<?php echo $idx; ?>" class="cat-detail" style="display:none;">
                                            <td></td>
                                            <td colspan="7" style="padding:0 15px 8px;">
                                                <table class="table table-condensed" style="background:#f5f5f5; margin-bottom:0; font-size:11px;">
                                                    <thead><tr><th>Pedido</th><th>Cliente</th><th>Data</th><th class="text-right">Qtd</th><th class="text-right">Vl.Unit</th><th class="text-right">Subtotal</th></tr></thead>
                                                    <tbody>
                                                        <?php
                                                        $has_items = false;
                                                        if(!empty($detalhe_produtos)):
                                                            foreach($detalhe_produtos as $d):
                                                                if($d->produto == $p->produto):
                                                                    $has_items = true;
                                                        ?>
                                                        <tr>
                                                            <td>#<?php echo $d->pedido_id; ?></td>
                                                            <td><?php echo $d->cliente; ?></td>
                                                            <td><?php echo date('d/m/Y', strtotime($d->data_vencimento)); ?></td>
                                                            <td class="text-right"><?php echo (int)$d->quantidade; ?></td>
                                                            <td class="text-right">R$ <?php echo number_format($d->valor, 2, ',', '.'); ?></td>
                                                            <td class="text-right">R$ <?php echo number_format($d->subtotal, 2, ',', '.'); ?></td>
                                                        </tr>
                                                        <?php endif; endforeach; endif; ?>
                                                        <?php if(!$has_items): ?>
                                                        <tr><td colspan="6" class="text-muted">Nenhum lançamento</td></tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <?php endforeach; else: ?>
                                        <tr><td colspan="6" class="text-center text-muted">Nenhum produto encontrado</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot><tr style="font-weight:700; border-top:2px solid #ddd;"><td></td><td>TOTAL</td><td></td><td class="text-right"><?php echo number_format($total_itens, 0, ',', '.'); ?></td><td></td><td></td><td class="text-right">R$ <?php echo number_format($total_geral, 2, ',', '.'); ?></td><td></td><td></td></tr></tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
$('#tabela_resultados').on('click', '.cat-toggle', function(){
    var $target = $($(this).data('target'));
    var $chevron = $(this).find('.cat-chevron');
    $target.toggle();
    $chevron.toggleClass('fa-chevron-right fa-chevron-down');
});

$('#tabela_resultados').on('click', '.sortable', function(){
    var $th = $(this);
    var $table = $th.closest('table');
    var $tbody = $table.find('tbody');
    var rows = $tbody.find('tr:has(td)').not('.cat-detail').get();
    var sortBy = $th.data('sort');
    var asc = !$th.hasClass('sorted-asc');

    rows.sort(function(a, b){
        var valA = parseFloat($(a).find('td').eq(sortBy === 'qtd' ? 3 : 6).text().replace(/[^0-9,]/g, '').replace(',', '.')) || 0;
        var valB = parseFloat($(b).find('td').eq(sortBy === 'qtd' ? 3 : 6).text().replace(/[^0-9,]/g, '').replace(',', '.')) || 0;
        return asc ? valB - valA : valA - valB;
    });

    $th.closest('tr').find('.sortable i').attr('class', 'fa fa-sort');
    $th.find('i').attr('class', asc ? 'fa fa-sort-amount-desc' : 'fa fa-sort-amount-asc');
    $th.toggleClass('sorted-asc', asc).toggleClass('sorted-desc', !asc);

    $.each(rows, function(i, row){
        var detailRow = $(row).next('.cat-detail');
        $tbody.append(row);
        if(detailRow.length) $tbody.append(detailRow[0]);
    });
});
</script>
