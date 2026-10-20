<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Fretes por Bairro</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Cadastros</a>
            </li>
            <li class="active">
                <strong>Fretes por Bairro</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">

    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="ibox-tools">
                        <a class="btn btn-primary" href="<?php echo base_url(array('site', 'novo-bairro-frete')) ?>">
                            Novo bairro
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-view" >
                            <thead>
                                <tr>
                                    <th class="on-print">ID</th>
                                    <th>Bairro</th>
                                    <th>Valor do frete</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                if(!empty($bairros)){
                                    foreach($bairros as $bairro){
                            ?>
                                        <tr class="gradeC" id="item-<?php echo $bairro->id ?>">
                                            <td><?php echo $bairro->id; ?></td>
                                            <td><?php echo $bairro->nome; ?></td>
                                            <td>R$ <?php echo number_format($bairro->valor_frete, 2, ',', '.'); ?></td>
                                            <td>
                                                <?php if($bairro->ativo): ?>
                                                    <span class="label label-primary">Ativo</span>
                                                <?php else: ?>
                                                    <span class="label label-default">Inativo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="<?php echo base_url(array('site', 'editar-bairro-frete')) ?>?id=<?php echo $bairro->id ?>" class="btn btn-default btn-icon-action" data-toggle="tooltip" data-placement="bottom" title="Editar"><i class="fa fa-pencil-square-o"></i></a>
                                                <a href="<?php echo base_url(array('site', 'excluir-bairro-frete')) ?>" class="btn btn-default btn-icon-action delete-item" data-item="<?php echo $bairro->id ?>" data-toggle="tooltip" data-placement="bottom" title="Desativar"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                            <?php
                                    }
                                }
                             ?>
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
    })
</script>
