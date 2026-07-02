<style>
    .card-resumo {
        border-radius: 6px; color: #fff; padding: 10px 12px; margin-bottom: 10px;
        position: relative; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.12);
        height: 72px;
    }
    .card-resumo .card-icon { position: absolute; right: 8px; top: 6px; font-size: 32px; opacity: 0.18; }
    .card-resumo .card-label { font-size: 10px; text-transform: uppercase; letter-spacing: 0.8px; opacity: 0.85; }
    .card-resumo .card-value { font-size: 16px; font-weight: 700; margin-top: 2px; }
    .card-resumo.is-clickable { cursor: pointer; }
    .card-resumo .card-hint { font-size: 10px; margin-top: 2px; opacity: 0.9; }
    .card-receber { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .card-recebido { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .card-pagar { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .card-pago { background: linear-gradient(135deg, #fc4a1a 0%, #f7b733 100%); }
    .card-saldo { background: linear-gradient(135deg, #00b4db 0%, #0083B0 100%); }
    .card-previsao { background: linear-gradient(135deg, #8E2DE2 0%, #4A00E0 100%); }

    .tabela-fluxo { font-size: 13px; }
    .tabela-fluxo thead th { background: #f8f9fa; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
    .tabela-fluxo tbody td { vertical-align: middle; }
    .badge-pago { background: #11998e; color: #fff; font-size: 10px; }
    .badge-aberto { background: #f5576c; color: #fff; font-size: 10px; }
    .badge-cancelada { background: #b71c1c; color: #fff; font-size: 10px; }
    .linha-cancelada td { background: #ffebee !important; color: #b71c1c; }

    .breakdown-box {
        border: 1px solid #e7eaec; border-radius: 6px; padding: 12px 15px;
        margin-bottom: 15px; background: #fafafa;
    }
    .breakdown-box h5 { margin-top: 0; font-weight: 600; font-size: 14px; color: #555; }
    .breakdown-box table { margin-bottom: 0; font-size: 12px; }
    .breakdown-box table td, .breakdown-box table th { padding: 4px 8px; border-top: none; border-bottom: 1px solid #eee; }

    .pagination-wrapper { text-align: center; margin-top: 15px; }
    .pagination-wrapper .btn { margin: 0 3px; }

    .nav-tabs { margin-bottom: 15px; }
    .nav-tabs > li > a { font-size: 13px; font-weight: 600; }

    .tree-table td { vertical-align: middle; }
    .tree-level-0 { font-weight: 700; background: #f0f0f0; }
    .tree-level-1 { background: #f8f8f8; }
    .tree-level-2 { }
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
                            <button class="btn btn-success" onclick="imprimir_resultado()" type="button"><i class="fa fa-print"></i> Imprimir</button>
                        </div>
                    </div>
                </div>
                <div class="ibox-content">

                    <!-- TABS -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#tab-analitico" aria-controls="tab-analitico" role="tab" data-toggle="tab"><i class="fa fa-bar-chart"></i> Analítico</a></li>
                        <li role="presentation"><a href="#tab-lancamentos" aria-controls="tab-lancamentos" role="tab" data-toggle="tab"><i class="fa fa-list"></i> Lançamentos</a></li>
                    </ul>

                    <div class="tab-content">

                        <!-- ========== TAB ANALÍTICO ========== -->
                        <div role="tabpanel" class="tab-pane active" id="tab-analitico">

                            <!-- CARDS DE RESUMO -->
                            <div class="row">
                                <div class="col-lg-4 col-md-6">
                                    <div class="card-resumo card-receber is-clickable" data-toggle="modal" data-target="#modal-total-a-receber">
                                        <i class="fa fa-arrow-up card-icon"></i>
                                        <div class="card-label">(A) Total a Receber por vencimento</div>
                                        <div class="card-value">R$ <?php echo number_format($card_total_a_receber, 2, ',', '.'); ?></div>
                                        <div class="card-hint"><?php echo !empty($card_receitas_abertas) ? count($card_receitas_abertas) : 0; ?> parcelas abertas</div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="card-resumo card-recebido">
                                        <i class="fa fa-check-circle card-icon"></i>
                                        <div class="card-label">(B) Total Recebido</div>
                                        <div class="card-value">R$ <?php echo number_format($card_total_recebido, 2, ',', '.'); ?></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="card-resumo card-pagar">
                                        <i class="fa fa-arrow-down card-icon"></i>
                                        <div class="card-label">(C) Total a Pagar</div>
                                        <div class="card-value">R$ <?php echo number_format($card_total_a_pagar, 2, ',', '.'); ?></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="card-resumo card-pago">
                                        <i class="fa fa-money card-icon"></i>
                                        <div class="card-label">(D) Total Pago (Despesas)</div>
                                        <div class="card-value">R$ <?php echo number_format($card_total_pago_desp, 2, ',', '.'); ?></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="card-resumo card-saldo">
                                        <i class="fa fa-balance-scale card-icon"></i>
                                        <div class="card-label">Saldo (B - D)</div>
                                        <div class="card-value">R$ <?php echo number_format($card_saldo, 2, ',', '.'); ?></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="card-resumo card-previsao">
                                        <i class="fa fa-chart-line card-icon"></i>
                                        <div class="card-label">Previsão (A - C)</div>
                                        <div class="card-value">R$ <?php echo number_format($card_previsao, 2, ',', '.'); ?></div>
                                    </div>
                                </div>
                            </div>

                            <?php
                            $nomes_forma_pgto = array(
                                1 => 'Dinheiro', 9 => 'Pix', 2 => 'Débito',
                                3 => 'Crédito 1x', 4 => 'Crédito 2x', 5 => 'Crédito 3x',
                                6 => 'Crédito 4x', 7 => 'Duplicata', 8 => 'Cheque'
                            );

                            // -- Forma pgto receitas --
                            $resumo_receitas = array(); $total_r_pago = 0; $total_r_aberto = 0;
                            if(!empty($totais_forma_pgto_receitas)):
                                foreach($totais_forma_pgto_receitas as $t):
                                    $fp = $t->forma_pgto ?: 0;
                                    if(!isset($resumo_receitas[$fp])) $resumo_receitas[$fp] = array('pago' => 0, 'aberto' => 0);
                                    if($t->status == 1) { $resumo_receitas[$fp]['pago'] += $t->total; $total_r_pago += $t->total; }
                                    else { $resumo_receitas[$fp]['aberto'] += $t->total; $total_r_aberto += $t->total; }
                                endforeach;
                            endif;

                            // -- Forma pgto despesas --
                            $resumo_despesas = array(); $total_d_pago = 0; $total_d_aberto = 0;
                            if(!empty($totais_forma_pgto_despesas)):
                                foreach($totais_forma_pgto_despesas as $t):
                                    $fp = $t->forma_pgto ?: 0;
                                    if(!isset($resumo_despesas[$fp])) $resumo_despesas[$fp] = array('pago' => 0, 'aberto' => 0);
                                    if($t->status == 1) { $resumo_despesas[$fp]['pago'] += $t->total; $total_d_pago += $t->total; }
                                    else { $resumo_despesas[$fp]['aberto'] += $t->total; $total_d_aberto += $t->total; }
                                endforeach;
                            endif;

                            // -- Categorias --
                            $total_categorias = 0;
                            if(!empty($totais_por_categoria)):
                                foreach($totais_por_categoria as $cat) $total_categorias += $cat->total;
                            endif;

                            // -- Plano conta tree --
                            function build_plano_tree($itens) {
                                $tree = array();
                                foreach($itens as $item) {
                                    $parts = explode('.', $item->cod);
                                    $path = '';
                                    $current = &$tree;
                                    foreach($parts as $i => $part) {
                                        $path = ($path === '') ? $part : $path . '.' . $part;
                                        $is_leaf = ($i === count($parts) - 1);
                                        if(!isset($current[$path])) {
                                            $label = $is_leaf ? $item->plano_conta : (isset($GLOBALS['planos_lookup'][$path]) ? $GLOBALS['planos_lookup'][$path] : 'Grupo ' . $path);
                                            $current[$path] = array(
                                                'cod' => $path,
                                                'label' => $label,
                                                'total' => 0,
                                                'qtd' => 0,
                                                'children' => array(),
                                                'is_leaf' => $is_leaf
                                            );
                                        }
                                        if($is_leaf) {
                                            $current[$path]['total'] = $item->total;
                                            $current[$path]['qtd'] = $item->qtd;
                                            $current[$path]['label'] = $item->plano_conta;
                                        }
                                        $current = &$current[$path]['children'];
                                    }
                                }
                                return $tree;
                            }

                            function sum_tree(&$node) {
                                $sum_total = $node['total'];
                                $sum_qtd = $node['qtd'];
                                foreach($node['children'] as &$child) {
                                    $s = sum_tree($child);
                                    $sum_total += $s['total'];
                                    $sum_qtd += $s['qtd'];
                                }
                                $node['total'] = $sum_total;
                                $node['qtd'] = $sum_qtd;
                                return array('total' => $sum_total, 'qtd' => $sum_qtd);
                            }
                            ?>

                            <!-- FORMA PGTO -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="breakdown-box">
                                        <h5><i class="fa fa-arrow-up text-info"></i> Receitas por forma de pagamento</h5>
                                        <table class="table table-condensed">
                                            <thead><tr><th>Forma</th><th class="text-right">Pago</th><th class="text-right">Aberto</th><th class="text-right">Total</th></tr></thead>
                                            <tbody>
                                                <?php foreach($resumo_receitas as $fp => $v):
                                                    $nome_fp = isset($nomes_forma_pgto[$fp]) ? $nomes_forma_pgto[$fp] : 'Outros';
                                                ?>
                                                <tr>
                                                    <td><?php echo $nome_fp; ?></td>
                                                    <td class="text-right">R$ <?php echo number_format($v['pago'], 2, ',', '.'); ?></td>
                                                    <td class="text-right">R$ <?php echo number_format($v['aberto'], 2, ',', '.'); ?></td>
                                                    <td class="text-right"><strong>R$ <?php echo number_format($v['pago'] + $v['aberto'], 2, ',', '.'); ?></strong></td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php if(empty($resumo_receitas)): ?>
                                                <tr><td colspan="4" class="text-center text-muted">Nenhum registro</td></tr>
                                                <?php endif; ?>
                                            </tbody>
                                            <?php if(!empty($resumo_receitas)): ?>
                                            <tfoot><tr style="font-weight:700; border-top:2px solid #ddd;"><td>TOTAL</td><td class="text-right">R$ <?php echo number_format($total_r_pago, 2, ',', '.'); ?></td><td class="text-right">R$ <?php echo number_format($total_r_aberto, 2, ',', '.'); ?></td><td class="text-right">R$ <?php echo number_format($total_r_pago + $total_r_aberto, 2, ',', '.'); ?></td></tr></tfoot>
                                            <?php endif; ?>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="breakdown-box">
                                        <h5><i class="fa fa-arrow-down text-danger"></i> Despesas por forma de pagamento</h5>
                                        <table class="table table-condensed">
                                            <thead><tr><th>Forma</th><th class="text-right">Pago</th><th class="text-right">Aberto</th><th class="text-right">Total</th></tr></thead>
                                            <tbody>
                                                <?php foreach($resumo_despesas as $fp => $v):
                                                    $nome_fp = isset($nomes_forma_pgto[$fp]) ? $nomes_forma_pgto[$fp] : 'Outros';
                                                ?>
                                                <tr>
                                                    <td><?php echo $nome_fp; ?></td>
                                                    <td class="text-right">R$ <?php echo number_format($v['pago'], 2, ',', '.'); ?></td>
                                                    <td class="text-right">R$ <?php echo number_format($v['aberto'], 2, ',', '.'); ?></td>
                                                    <td class="text-right"><strong>R$ <?php echo number_format($v['pago'] + $v['aberto'], 2, ',', '.'); ?></strong></td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php if(empty($resumo_despesas)): ?>
                                                <tr><td colspan="4" class="text-center text-muted">Nenhum registro</td></tr>
                                                <?php endif; ?>
                                            </tbody>
                                            <?php if(!empty($resumo_despesas)): ?>
                                            <tfoot><tr style="font-weight:700; border-top:2px solid #ddd;"><td>TOTAL</td><td class="text-right">R$ <?php echo number_format($total_d_pago, 2, ',', '.'); ?></td><td class="text-right">R$ <?php echo number_format($total_d_aberto, 2, ',', '.'); ?></td><td class="text-right">R$ <?php echo number_format($total_d_pago + $total_d_aberto, 2, ',', '.'); ?></td></tr></tfoot>
                                            <?php endif; ?>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- STATUS -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="breakdown-box">
                                        <h5><i class="fa fa-pie-chart text-warning"></i> Resumo por status</h5>
                                        <table class="table table-condensed">
                                            <thead><tr><th></th><th class="text-right">Receitas</th><th class="text-right">Despesas</th><th class="text-right">Resultado</th></tr></thead>
                                            <tbody>
                                                <tr><td><span class="badge badge-pago" style="font-size:11px;">PAGO</span></td><td class="text-right">R$ <?php echo number_format($total_r_pago, 2, ',', '.'); ?></td><td class="text-right">R$ <?php echo number_format($total_d_pago, 2, ',', '.'); ?></td><td class="text-right"><strong>R$ <?php echo number_format($total_r_pago - $total_d_pago, 2, ',', '.'); ?></strong></td></tr>
                                                <tr><td><span class="badge badge-aberto" style="font-size:11px;">ABERTO</span></td><td class="text-right">R$ <?php echo number_format($total_r_aberto, 2, ',', '.'); ?></td><td class="text-right">R$ <?php echo number_format($total_d_aberto, 2, ',', '.'); ?></td><td class="text-right"><strong>R$ <?php echo number_format($total_r_aberto - $total_d_aberto, 2, ',', '.'); ?></strong></td></tr>
                                            </tbody>
                                            <tfoot><tr style="font-weight:700; border-top:2px solid #ddd;"><td>TOTAL</td><td class="text-right">R$ <?php echo number_format($total_r_pago + $total_r_aberto, 2, ',', '.'); ?></td><td class="text-right">R$ <?php echo number_format($total_d_pago + $total_d_aberto, 2, ',', '.'); ?></td><td class="text-right">R$ <?php echo number_format(($total_r_pago + $total_r_aberto) - ($total_d_pago + $total_d_aberto), 2, ',', '.'); ?></td></tr></tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- CATEGORIAS ACCORDION -->
                            <?php if(!empty($totais_por_categoria)): ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="breakdown-box">
                                        <h5><i class="fa fa-tags text-success"></i> Vendas por categoria</h5>
                                        <table class="table table-condensed" style="margin-bottom:0;">
                                            <thead><tr><th style="width:30px;"></th><th>Categoria</th><th class="text-right">Total</th><th class="text-right" style="width:25%;">%</th></tr></thead>
                                            <tbody>
                                                <?php $cat_idx = 0; foreach($totais_por_categoria as $cat):
                                                    $pct = $total_categorias > 0 ? ($cat->total / $total_categorias * 100) : 0;
                                                    $cat_idx++;
                                                ?>
                                                <tr class="cat-toggle" data-target="#cat-detail-<?php echo $cat_idx; ?>" style="cursor:pointer;">
                                                    <td><i class="fa fa-chevron-right cat-chevron"></i></td>
                                                    <td><strong><?php echo $cat->categoria ?: 'Sem categoria'; ?></strong></td>
                                                    <td class="text-right"><strong>R$ <?php echo number_format($cat->total, 2, ',', '.'); ?></strong></td>
                                                    <td class="text-right">
                                                        <div style="display:flex; align-items:center; gap:8px;">
                                                            <div style="flex:1; height:10px; background:#e0e0e0; border-radius:5px; overflow:hidden;">
                                                                <div style="width:<?php echo round($pct); ?>%; height:100%; background:<?php echo ($pct > 20 ? '#11998e' : ($pct > 10 ? '#23c6c8' : '#1ab394')); ?>; border-radius:5px;"></div>
                                                            </div>
                                                            <span style="font-size:10px; min-width:35px; text-align:right;"><?php echo number_format($pct, 1); ?>%</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr id="cat-detail-<?php echo $cat_idx; ?>" class="cat-detail" style="display:none;">
                                                    <td></td>
                                                    <td colspan="3" style="padding:0 15px 8px;">
                                                        <table class="table table-condensed" style="background:#f5f5f5; margin-bottom:0; font-size:11px;">
                                                            <thead><tr><th>Pedido</th><th>Cliente</th><th>Produto</th><th class="text-right">Qtd</th><th class="text-right">Vl.Unit</th><th class="text-right">Subtotal</th></tr></thead>
                                                            <tbody>
                                                                <?php
                                                                $cat_key = $cat->categoria ?: 'Sem categoria';
                                                                $has_items = false;
                                                                if(!empty($detalhe_categoria)):
                                                                    foreach($detalhe_categoria as $d):
                                                                        if(($d->categoria ?: 'Sem categoria') == $cat_key):
                                                                            $has_items = true;
                                                                ?>
                                                                <tr>
                                                                    <td>#<?php echo $d->pedido_id; ?></td>
                                                                    <td><?php echo $d->cliente; ?></td>
                                                                    <td><?php echo $d->produto; ?></td>
                                                                    <td class="text-right"><?php echo $d->quantidade; ?></td>
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
                                                <?php endforeach; ?>
                                            </tbody>
                                            <tfoot><tr style="font-weight:700; border-top:2px solid #ddd;"><td></td><td>TOTAL</td><td class="text-right">R$ <?php echo number_format($total_categorias, 2, ',', '.'); ?></td><td></td></tr></tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- PLANO DE CONTA TREE -->
                            <?php
                            $total_plano = 0;
                            if(!empty($totais_por_plano_conta)):
                                foreach($totais_por_plano_conta as $pl) $total_plano += $pl->total;
                            endif;
                            ?>
                            <?php if(!empty($totais_por_plano_conta)): ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="breakdown-box">
                                        <h5><i class="fa fa-sitemap text-danger"></i> Saídas por plano de conta</h5>

                                        <?php
                                        // Cabeçalho
                                        ?>
                                        <div style="display:flex; font-weight:700; font-size:11px; text-transform:uppercase; color:#888; padding:4px 8px; border-bottom:2px solid #eee; margin-bottom:4px;">
                                            <div style="flex:1;">Plano de conta</div>
                                            <div style="width:50px; text-align:right;">Qtd</div>
                                            <div style="width:130px; text-align:right;">Total</div>
                                            <div style="width:130px; text-align:right;">%</div>
                                        </div>

                                        <?php
                                        $GLOBALS['planos_lookup'] = isset($planos_lookup) ? $planos_lookup : array();
                                        $GLOBALS['detalhe_plano_conta'] = isset($detalhe_plano_conta) ? $detalhe_plano_conta : array();
                                        $tree = build_plano_tree($totais_por_plano_conta);
                                        foreach($tree as &$node) sum_tree($node);

                                        function render_tree_node($node, $level, $grand_total) {
                                            $has_children = !empty($node['children']);
                                            $hash = 'node-' . md5($node['cod']);
                                            $is_leaf = !$has_children;
                                            $pct = $grand_total > 0 ? ($node['total'] / $grand_total * 100) : 0;
                                            ?>
                                            <div class="tree-node" style="margin-left:0;">
                                                <div class="tree-row-header tree-toggle" data-target="#<?php echo $hash; ?>" style="display:flex; align-items:center; padding:4px 8px; cursor:pointer; border-radius:4px; <?php echo $level == 0 ? 'background:#f0f0f0;' : ($level == 1 ? 'background:#f7f7f7;' : ''); ?>">
                                                    <div style="flex:1; padding-left:<?php echo $level * 20; ?>px;">
                                                        <i class="fa <?php echo $has_children ? 'fa-chevron-down' : 'fa-chevron-right'; ?> tree-chevron" style="font-size:10px; width:12px;"></i>
                                                        <strong><?php echo $node['cod']; ?></strong>
                                                        <span style="color:<?php echo $has_children ? '#888' : '#333'; ?>; <?php echo $has_children ? 'font-style:italic;' : ''; ?>"><?php echo $node['label']; ?></span>
                                                    </div>
                                                    <div style="width:50px; text-align:right; font-size:12px;"><?php echo $node['qtd']; ?></div>
                                                    <div style="width:130px; text-align:right; font-weight:700; font-size:12px;">R$ <?php echo number_format($node['total'], 2, ',', '.'); ?></div>
                                                    <div style="width:130px; text-align:right;">
                                                        <div style="display:flex; align-items:center; gap:6px;">
                                                            <div style="flex:1; height:6px; background:#e0e0e0; border-radius:3px; overflow:hidden;">
                                                                <div style="width:<?php echo round($pct); ?>%; height:100%; background:#f5576c; border-radius:3px;"></div>
                                                            </div>
                                                            <span style="font-size:10px; min-width:32px; text-align:right;"><?php echo number_format($pct, 1); ?>%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="<?php echo $hash; ?>" class="tree-children" style="<?php echo $has_children ? '' : 'display:none;'; ?>">
                                                    <?php if($is_leaf): ?>
                                                        <div style="padding:4px 12px 8px <?php echo ($level + 1) * 16 + 20; ?>px; font-size:11px;">
                                                            <table class="table table-condensed" style="background:#fff5f5; margin-bottom:0; font-size:11px;">
                                                                <thead><tr><th>ID</th><th>Fornecedor</th><th>Descrição</th><th>Vencimento</th><th class="text-right">Valor</th><th>Status</th></tr></thead>
                                                                <tbody>
                                                                    <?php
                                                                    $has_detail = false;
                                                                    if(!empty($GLOBALS['detalhe_plano_conta'])):
                                                                        foreach($GLOBALS['detalhe_plano_conta'] as $dp):
                                                                            if($dp->plano_conta == $node['label']):
                                                                                $has_detail = true;
                                                                    ?>
                                                                    <tr>
                                                                        <td>#<?php echo $dp->id; ?></td>
                                                                        <td><?php echo $dp->fornecedor; ?></td>
                                                                        <td><?php echo $dp->descricao; ?></td>
                                                                        <td><?php echo date('d/m/Y', strtotime($dp->data_vencimento)); ?></td>
                                                                        <td class="text-right">R$ <?php echo number_format($dp->valor, 2, ',', '.'); ?></td>
                                                                        <td><?php echo ($dp->status == 1) ? '<span class="badge badge-pago">Pago</span>' : '<span class="badge badge-aberto">Aberto</span>'; ?></td>
                                                                    </tr>
                                                                    <?php endif; endforeach; endif; ?>
                                                                    <?php if(!$has_detail): ?>
                                                                    <tr><td colspan="6" class="text-muted">Nenhum lançamento</td></tr>
                                                                    <?php endif; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php foreach($node['children'] as $child): ?>
                                                        <?php render_tree_node($child, $level + 1, $grand_total); ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                            <?php
                                        }

                                        foreach($tree as $root):
                                            render_tree_node($root, 0, $total_plano);
                                        endforeach;
                                        ?>

                                        <!-- Totais -->
                                        <div style="display:flex; font-weight:700; padding:6px 8px; border-top:2px solid #ddd; margin-top:6px;">
                                            <div style="flex:1;">TOTAL</div>
                                            <div style="width:50px; text-align:right;"><?php echo array_sum(array_column($totais_por_plano_conta, 'qtd')); ?></div>
                                            <div style="width:130px; text-align:right;">R$ <?php echo number_format($total_plano, 2, ',', '.'); ?></div>
                                            <div style="width:130px;"></div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- RESUMO FINAL -->
                            <hr>
                            <div class="row">
                                <div class="col-md-6 col-md-offset-6">
                                    <table class="table table-condensed" style="font-size:13px;">
                                        <tr><td><strong>(A) TOTAL A RECEBER:</strong></td><td class="text-right">R$ <?php echo number_format($card_total_a_receber, 2, ',', '.'); ?></td></tr>
                                        <tr><td><strong>(B) TOTAL RECEBIDO:</strong></td><td class="text-right">R$ <?php echo number_format($card_total_recebido, 2, ',', '.'); ?></td></tr>
                                        <tr><td><strong>(C) -TOTAL A PAGAR:</strong></td><td class="text-right">R$ <?php echo number_format($card_total_a_pagar, 2, ',', '.'); ?></td></tr>
                                        <tr><td><strong>(D) -TOTAL PAGO:</strong></td><td class="text-right">R$ <?php echo number_format($card_total_pago_desp, 2, ',', '.'); ?></td></tr>
                                        <tr style="background:#e8eaf6;"><td><strong>(A - C) = PROVISÃO:</strong></td><td class="text-right">R$ <?php echo number_format($card_previsao, 2, ',', '.'); ?></td></tr>
                                        <tr style="background:#e0f2f1;"><td><strong>(B - D) = SALDO:</strong></td><td class="text-right">R$ <?php echo number_format($card_saldo, 2, ',', '.'); ?></td></tr>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <!-- ========== FIM TAB ANALÍTICO ========== -->

                        <!-- ========== TAB LANÇAMENTOS ========== -->
                        <div role="tabpanel" class="tab-pane" id="tab-lancamentos">

                            <ul class="nav nav-pills" style="margin-bottom:12px;">
                                <li class="active"><a href="#sub-rec" data-toggle="tab"><i class="fa fa-arrow-up text-info"></i> Contas a Receber (<?php echo $total_receitas_count; ?>)</a></li>
                                <li><a href="#sub-desp" data-toggle="tab"><i class="fa fa-arrow-down text-danger"></i> Contas a Pagar (<?php echo $total_despesas_count; ?>)</a></li>
                            </ul>

                            <div class="tab-content">
                                <!-- SUB-TAB RECEITAS -->
                                <div class="tab-pane active" id="sub-rec">
                                    <?php if($total_pages_receitas > 1): ?>
                                    <div class="pagination-wrapper hidden-print" style="margin-bottom:8px;">
                                        <span style="font-size:11px;"><?php echo $total_receitas_count; ?> registros — pág. <?php echo $page_receitas; ?>/<?php echo $total_pages_receitas; ?></span>
                                        <div class="btn-group btn-group-sm" style="margin-left:8px;">
                                            <?php if($page_receitas > 1): ?><button class="btn btn-white btn-sub-paginate" type="button" data-table="page_receitas" data-page="<?php echo $page_receitas - 1; ?>"><i class="fa fa-chevron-left"></i></button><?php endif; ?>
                                            <?php for($p = max(1, $page_receitas - 1); $p <= min($total_pages_receitas, $page_receitas + 1); $p++): ?>
                                            <button class="btn btn-sub-paginate <?php echo ($p == $page_receitas) ? 'btn-primary' : 'btn-white'; ?>" type="button" data-table="page_receitas" data-page="<?php echo $p; ?>"><?php echo $p; ?></button>
                                            <?php endfor; ?>
                                            <?php if($page_receitas < $total_pages_receitas): ?><button class="btn btn-white btn-sub-paginate" type="button" data-table="page_receitas" data-page="<?php echo $page_receitas + 1; ?>"><i class="fa fa-chevron-right"></i></button><?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <div class="table-responsive">
                                    <table class="table table-striped table-bordered tabela-fluxo">
                                        <thead><tr><th>Código</th><th>Cliente</th><th>Vencimento</th><th>Valor</th><th>Status</th><th>Data Pago</th><th>Forma Pgto</th></tr></thead>
                                        <tbody>
                                            <?php if(!empty($pedidos_pag)): foreach($pedidos_pag as $r): ?>
                                            <?php $cancelada_relatorio = !empty($r->cancelada_relatorio); ?>
                                            <tr class="<?php echo $cancelada_relatorio ? 'linha-cancelada' : ''; ?>">
                                                <td><?php echo $r->id; ?></td>
                                                <td><?php echo $r->cliente_pedido; ?></td>
                                                <td><?php echo date("d/m/Y", strtotime($r->vencimento_receita)); ?></td>
                                                <td>R$ <?php echo number_format($r->valor_receita, 2, ',', '.'); ?><?php echo $cancelada_relatorio ? ' <span class="badge badge-cancelada">fora dos totais</span>' : ''; ?></td>
                                                <td><?php echo $cancelada_relatorio ? '<span class="badge badge-cancelada">Cancelada</span>' : (($r->status_receita == 0) ? '<span class="badge badge-aberto">Em aberto</span>' : '<span class="badge badge-pago">Pago</span>'); ?></td>
                                                <td><?php echo ($r->data_pago_receita) ? date("d/m/Y", strtotime($r->data_pago_receita)) : '-'; ?></td>
                                                <td><?php echo isset($nomes_forma_pgto[$r->forma_pgto_receita]) ? $nomes_forma_pgto[$r->forma_pgto_receita] : '-'; ?></td>
                                            </tr>
                                            <?php endforeach; else: ?>
                                            <tr><td colspan="7" class="text-center text-muted">Nenhuma receita</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                    </div>
                                </div>

                                <!-- SUB-TAB DESPESAS -->
                                <div class="tab-pane" id="sub-desp">
                                    <?php if($total_pages_despesas > 1): ?>
                                    <div class="pagination-wrapper hidden-print" style="margin-bottom:8px;">
                                        <span style="font-size:11px;"><?php echo $total_despesas_count; ?> registros — pág. <?php echo $page_despesas; ?>/<?php echo $total_pages_despesas; ?></span>
                                        <div class="btn-group btn-group-sm" style="margin-left:8px;">
                                            <?php if($page_despesas > 1): ?><button class="btn btn-white btn-sub-paginate" type="button" data-table="page_despesas" data-page="<?php echo $page_despesas - 1; ?>"><i class="fa fa-chevron-left"></i></button><?php endif; ?>
                                            <?php for($p = max(1, $page_despesas - 1); $p <= min($total_pages_despesas, $page_despesas + 1); $p++): ?>
                                            <button class="btn btn-sub-paginate <?php echo ($p == $page_despesas) ? 'btn-primary' : 'btn-white'; ?>" type="button" data-table="page_despesas" data-page="<?php echo $p; ?>"><?php echo $p; ?></button>
                                            <?php endfor; ?>
                                            <?php if($page_despesas < $total_pages_despesas): ?><button class="btn btn-white btn-sub-paginate" type="button" data-table="page_despesas" data-page="<?php echo $page_despesas + 1; ?>"><i class="fa fa-chevron-right"></i></button><?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <div class="table-responsive">
                                    <table class="table table-striped table-bordered tabela-fluxo" style="background:#fff5f5;">
                                        <thead><tr><th>Código</th><th>Fornecedor</th><th>Vencimento</th><th>Valor</th><th>Status</th><th>Data Pago</th><th>Forma Pgto</th></tr></thead>
                                        <tbody>
                                            <?php if(!empty($contas_pag)): foreach($contas_pag as $c): ?>
                                            <tr>
                                                <td><?php echo $c->id; ?></td>
                                                <td><?php echo $c->fornecedor; ?></td>
                                                <td><?php echo date("d/m/Y", strtotime($c->data_vencimento)); ?></td>
                                                <td>R$ <?php echo number_format($c->valor, 2, ',', '.'); ?></td>
                                                <td><?php echo ($c->status == 0) ? '<span class="badge badge-aberto">Em aberto</span>' : '<span class="badge badge-pago">Paga</span>'; ?></td>
                                                <td><?php echo ($c->data_pago) ? date("d/m/Y", strtotime($c->data_pago)) : '-'; ?></td>
                                                <td><?php echo isset($nomes_forma_pgto[$c->forma_pgto]) ? $nomes_forma_pgto[$c->forma_pgto] : '-'; ?></td>
                                            </tr>
                                            <?php endforeach; else: ?>
                                            <tr><td colspan="7" class="text-center text-muted">Nenhuma despesa</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- ========== FIM TAB LANÇAMENTOS ========== -->

                    </div>
                    <!-- FIM TAB CONTENT -->

                    <input type="hidden" id="page_val" value="<?php echo $page; ?>">
                    <input type="hidden" id="page_receitas_val" value="<?php echo $page_receitas; ?>">
                    <input type="hidden" id="page_despesas_val" value="<?php echo $page_despesas; ?>">

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-total-a-receber" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Total a receber por vencimento</h4>
            </div>
            <div class="modal-body">
                <p class="text-muted" style="font-size:12px;">
                    Este card sempre usa data de vencimento no periodo filtrado, mesmo quando a referencia do relatorio for pagamento.
                </p>
                <div class="table-responsive">
                    <table class="table table-condensed table-striped" style="font-size:12px;">
                        <thead>
                            <tr><th>Pedido</th><th>Cliente</th><th>Vencimento</th><th>Forma Pgto</th><th>Vendedor</th><th class="text-right">Valor</th></tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($card_receitas_abertas)): foreach($card_receitas_abertas as $r): ?>
                            <tr>
                                <td>#<?php echo $r->pedido_id; ?></td>
                                <td><?php echo $r->cliente_pedido; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($r->vencimento_receita)); ?></td>
                                <td><?php echo isset($nomes_forma_pgto[$r->forma_pgto_receita]) ? $nomes_forma_pgto[$r->forma_pgto_receita] : '-'; ?></td>
                                <td><?php echo $r->vendedor_pedido; ?></td>
                                <td class="text-right">R$ <?php echo number_format($r->valor_receita, 2, ',', '.'); ?></td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="6" class="text-center text-muted">Nenhuma parcela aberta por vencimento neste periodo</td></tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr style="font-weight:700;">
                                <td colspan="5">TOTAL</td>
                                <td class="text-right">R$ <?php echo number_format($card_total_a_receber, 2, ',', '.'); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function imprimir_resultado(){ window.print(); }
</script>
