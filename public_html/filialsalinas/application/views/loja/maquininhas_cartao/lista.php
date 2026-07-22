<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Maquininhas de cartao</h2>
        <ol class="breadcrumb">
            <li><a href="#">Loja</a></li>
            <li class="active"><strong>Maquininhas de cartao</strong></li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="ibox-tools">
                        <a class="btn btn-primary" href="<?php echo base_url(array('loja', 'nova-maquininha-cartao')) ?>">Nova</a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-view">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Grupos</th>
                                    <th>Taxas cadastradas</th>
                                    <th>Status</th>
                                    <th class="no-orderable">Acoes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($dados)): foreach ($dados as $valor): ?>
                                    <tr id="item-<?php echo $valor->id ?>">
                                        <td><?php echo $valor->id ?></td>
                                        <td><strong><?php echo $valor->nome ?></strong></td>
                                        <td><?php echo (int) $valor->total_grupos ?></td>
                                        <td>
                                            <?php if (!empty($valor->taxas)): ?>
                                                <table class="table table-condensed no-margins">
                                                    <thead>
                                                        <tr>
                                                            <th>Grupo</th>
                                                            <th>Bandeiras</th>
                                                            <th>Debito</th>
                                                            <th>Cred. 1x</th>
                                                            <th>Cred. 2x</th>
                                                            <th>Cred. 3x</th>
                                                            <th>Cred. 4x</th>
                                                            <th>Antecipacao</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($valor->taxas as $taxa): ?>
                                                            <tr>
                                                                <td><?php echo $taxa->grupo_bandeira ?></td>
                                                                <td><?php echo $taxa->bandeiras ? $taxa->bandeiras : '-' ?></td>
                                                                <td><?php echo number_format($taxa->taxa_debito, 2, ',', '.') ?>%</td>
                                                                <td><?php echo number_format($taxa->taxa_credito_1x, 2, ',', '.') ?>%</td>
                                                                <td><?php echo number_format($taxa->taxa_credito_2x, 2, ',', '.') ?>%</td>
                                                                <td><?php echo number_format($taxa->taxa_credito_3x, 2, ',', '.') ?>%</td>
                                                                <td><?php echo number_format($taxa->taxa_credito_4x, 2, ',', '.') ?>%</td>
                                                                <td><?php echo number_format($taxa->taxa_antecipacao, 4, ',', '.') ?>%</td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            <?php else: ?>
                                                <span class="text-muted">Nenhum grupo cadastrado</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $valor->ativo ? 'Ativa' : 'Inativa' ?></td>
                                        <td class="text-center">
                                            <a href="<?php echo base_url(array('loja', 'editar-maquininha-cartao')) ?>?id=<?php echo $valor->id ?>" class="btn btn-default btn-icon-action" data-toggle="tooltip" title="Editar"><i class="fa fa-pencil-square-o"></i></a>
                                            <a href="<?php echo base_url(array('loja', 'excluir-maquininha-cartao')) ?>" class="btn btn-default btn-icon-action delete-item" data-item="<?php echo $valor->id ?>" data-toggle="tooltip" title="Excluir"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
        <?php if (isset($notification)): ?>
            showNotification(<?php echo '"'. $notification->type .'","'. $notification->title .'","'. $notification->message .'"' ?>)
        <?php endif ?>
    });
</script>
