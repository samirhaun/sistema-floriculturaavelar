<style>
    .card-vendas {
        border-radius: 6px; color: #fff; padding: 12px 14px; margin-bottom: 10px;
        position: relative; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.12);
    }
    .card-vendas .card-icon { position: absolute; right: 10px; top: 8px; font-size: 34px; opacity: 0.18; }
    .card-vendas .card-label { font-size: 10px; text-transform: uppercase; letter-spacing: 0.8px; opacity: 0.85; }
    .card-vendas .card-value { font-size: 18px; font-weight: 700; margin-top: 2px; }
    .badge-aberto { background: #f5576c; color: #fff; font-size: 10px; }
    .badge-vencido { background: #d32f2f; color: #fff; font-size: 10px; }
    .badge-hoje { background: #f57c00; color: #fff; font-size: 10px; }
    .badge-futuro { background: #1976d2; color: #fff; font-size: 10px; }

    .breakdown-box {
        border: 1px solid #e7eaec; border-radius: 6px; padding: 12px 15px;
        margin-bottom: 15px; background: #fafafa;
    }
    .breakdown-box h5 { margin-top: 0; font-weight: 600; font-size: 14px; color: #555; }
    .breakdown-box table { margin-bottom: 0; font-size: 12px; }
    .breakdown-box table td, .breakdown-box table th { padding: 4px 8px; border-top: none; border-bottom: 1px solid #eee; }
    .btn-toggle-itens { cursor: pointer; background: none; border: none; padding: 0; font-size: 12px; color: #1976d2; }
    .btn-toggle-itens:hover { color: #0d47a1; }
    .row-itens { display: none; }
    .row-itens td { padding: 0 !important; border-top: none !important; }
    .row-itens .itens-inner { padding: 8px 15px; background: #f5f5f5; border-radius: 4px; margin: 4px 10px; }
    .row-itens .itens-inner table { font-size: 11px; }
    .row-itens .itens-inner table th { color: #888; font-weight: 600; }
</style>

<div class="wrapper wrapper-content animated fadeInRight" id="tabela_resultados">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title hidden-print" style="min-height: 50px;">
                    <div class="row">
                        <div class="col-lg-6"><h5>Pendências de pagamento</h5></div>
                        <div class="col-lg-6 text-right">
                            <button class="btn btn-success" onclick="window.print()" type="button"><i class="fa fa-print"></i> Imprimir</button>
                        </div>
                    </div>
                </div>
                <div class="ibox-content">

                    <!-- CARDS -->
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="card-vendas" style="background: linear-gradient(135deg, #d32f2f 0%, #f44336 100%);">
                                <i class="fa fa-exclamation-triangle card-icon"></i>
                                <div class="card-label">Total pendente</div>
                                <div class="card-value">R$ <?php echo number_format($total_pendente, 2, ',', '.'); ?></div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card-vendas" style="background: linear-gradient(135deg, #f57c00 0%, #ff9800 100%);">
                                <i class="fa fa-list-ol card-icon"></i>
                                <div class="card-label">Parcelas pendentes</div>
                                <div class="card-value"><?php echo number_format($qtd_parcelas, 0, ',', '.'); ?></div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card-vendas" style="background: linear-gradient(135deg, #455a64 0%, #607d8b 100%);">
                                <i class="fa fa-calendar card-icon"></i>
                                <div class="card-label">Ticket médio</div>
                                <div class="card-value">R$ <?php echo number_format($ticket_medio, 2, ',', '.'); ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- TABELA -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="breakdown-box">
                                <h5><i class="fa fa-clock-o text-danger"></i> Parcelas em aberto</h5>
                                <div class="table-responsive">
                                <table class="table table-condensed table-striped" style="font-size:12px;">
                                    <thead><tr><th>Pedido</th><th>Cliente</th><th>Vencimento</th><th>Valor</th><th>Atraso</th><th>Forma Pgto</th><th>Vendedor</th></tr></thead>
                                    <tbody>
                                        <?php
                                        $hoje = date('Y-m-d');
                                        $total_vencido = 0; $total_futuro = 0;
                                        $nomes_forma = array(1=>'Dinheiro',9=>'Pix',2=>'Débito',3=>'Crédito 1x',4=>'Crédito 2x',5=>'Crédito 3x',6=>'Crédito 4x',7=>'Duplicata',8=>'Cheque');

                                        if(!empty($pendencias)):
                                            $pedido_anterior = null;
                                            foreach($pendencias as $p):
                                                $dias = (strtotime($hoje) - strtotime($p->data_vencimento)) / 86400;
                                                if($dias > 0) $total_vencido += $p->valor;
                                                else $total_futuro += $p->valor;

                                                if($dias > 0) $badge = '<span class="badge badge-vencido">'.floor($dias).'d</span>';
                                                elseif($dias == 0) $badge = '<span class="badge badge-hoje">Hoje</span>';
                                                else $badge = '<span class="badge badge-futuro">'.abs(floor($dias)).'d</span>';

                                                $forma = isset($nomes_forma[$p->forma_pgto]) ? $nomes_forma[$p->forma_pgto] : '-';

                                                $tem_itens = isset($itens_pedidos[$p->pedido_cod]) && !empty($itens_pedidos[$p->pedido_cod]);
                                                $is_novo_pedido = ($p->pedido_cod !== $pedido_anterior);
                                        ?>
                                        <?php if($is_novo_pedido && $pedido_anterior !== null && $tem_itens): ?>
                                        <tr class="row-itens" id="itens-<?php echo $pedido_anterior; ?>">
                                            <td colspan="7">
                                                <div class="itens-inner">
                                                    <table class="table table-condensed">
                                                        <thead><tr><th>Produto</th><th>Qtd</th><th>Valor Total</th></tr></thead>
                                                        <tbody>
                                                            <?php foreach($itens_pedidos[$pedido_anterior] as $item): ?>
                                                            <tr>
                                                                <td><?php echo $item->produto_nome ? $item->produto_nome : 'Produto #'.$item->produtos_id; ?></td>
                                                                <td><?php echo $item->quantidade; ?></td>
                                                                <td>R$ <?php echo number_format($item->valor_total, 2, ',', '.'); ?></td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endif; ?>

                                        <?php if($is_novo_pedido && $pedido_anterior !== null && !isset($itens_pedidos[$pedido_anterior]) && isset($itens_pedidos[$p->pedido_cod])): ?>
                                        <?php endif; ?>

                                        <tr>
                                            <td>
                                                <?php if($tem_itens): ?>
                                                <button type="button" class="btn-toggle-itens" onclick="$('#itens-<?php echo $p->pedido_cod; ?>').toggle()">
                                                    <i class="fa fa-plus-circle"></i> #<?php echo $p->pedido_cod; ?>
                                                </button>
                                                <?php else: ?>
                                                #<?php echo $p->pedido_cod; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $p->cliente; ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($p->data_vencimento)); ?></td>
                                            <td>R$ <?php echo number_format($p->valor, 2, ',', '.'); ?></td>
                                            <td><?php echo $badge; ?></td>
                                            <td><?php echo $forma; ?></td>
                                            <td><?php echo $p->vendedor; ?></td>
                                        </tr>
                                        <?php $pedido_anterior = $p->pedido_cod; ?>
                                        <?php endforeach; ?>

                                        <?php if($pedido_anterior !== null && isset($itens_pedidos[$pedido_anterior])): ?>
                                        <tr class="row-itens" id="itens-<?php echo $pedido_anterior; ?>">
                                            <td colspan="7">
                                                <div class="itens-inner">
                                                    <table class="table table-condensed">
                                                        <thead><tr><th>Produto</th><th>Qtd</th><th>Valor Total</th></tr></thead>
                                                        <tbody>
                                                            <?php foreach($itens_pedidos[$pedido_anterior] as $item): ?>
                                                            <tr>
                                                                <td><?php echo $item->produto_nome ? $item->produto_nome : 'Produto #'.$item->produtos_id; ?></td>
                                                                <td><?php echo $item->quantidade; ?></td>
                                                                <td>R$ <?php echo number_format($item->valor_total, 2, ',', '.'); ?></td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endif; ?>

                                        <?php else: ?>
                                        <tr><td colspan="7" class="text-center text-muted">Nenhuma pendência encontrada</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr style="font-weight:700; border-top:2px solid #ddd;">
                                            <td>TOTAL</td>
                                            <td></td><td></td>
                                            <td>R$ <?php echo number_format($total_pendente, 2, ',', '.'); ?></td>
                                            <td></td><td></td><td></td>
                                        </tr>
                                        <tr style="font-size:11px; color:#888;">
                                            <td></td><td></td><td></td>
                                            <td><span class="badge badge-vencido">Vencido: R$ <?php echo number_format($total_vencido, 2, ',', '.'); ?></span></td>
                                            <td colspan="3"><span class="badge badge-futuro">A vencer: R$ <?php echo number_format($total_futuro, 2, ',', '.'); ?></span></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
