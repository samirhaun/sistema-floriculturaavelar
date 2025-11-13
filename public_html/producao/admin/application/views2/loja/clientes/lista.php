<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Clientes</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url('home') ?>">Home</a>
            </li>
            <li class="active">
                <strong>Cliente</strong>
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
                    <!-- <h5>FooTable with row toggler, sorting and pagination</h5> -->

                    <div class="ibox-tools">
                        <!-- <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a> -->
                        <a class="btn btn-primary" href="<?php echo base_url(array('loja', 'novo-cliente')) ?>">
                            <!-- <i class="fa fa-plus"></i> -->
                            Novo Cliente
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-view" >
                            <thead>
                                <tr>
                                    <th class="on-print">ID</th>
                                    <th class="on-print">Nome</th>
                                    <th class="on-print">E-mail</th>
                                    <th class="on-print">Telefone</th>
                                    <!-- <th class="on-print">Endereço</th> -->
                                    <th class="no-orderable">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                if(!empty($clientes)){
                                    foreach($clientes as $cliente){
                            ?>
                                        <tr class="gradeC" id="item-<?php echo $cliente->id ?>">
                                            <td><?php echo $cliente->id; ?></td>
                                            <td><?php echo $cliente->nome; ?></td>
                                            <td><?php echo $cliente->email; ?></td>
                                            <td><?php echo ($cliente->telefone) ?></td>
                                            <!-- <td><?php echo ($cliente->endereco) ?></td> -->
                                            <td class="text-center">
                                                <!-- <a href="#" class="btn btn-default btn-icon-action" data-toggle="tooltip" title=""><i class="fa fa-check"></i></a> -->
                                                <!-- <a href="#" class="btn btn-default btn-icon-action" data-toggle="tooltip" title="Visualizar"><i class="fa fa-file-text-o"></i></a> -->
                                                <a href="<?php echo base_url(array('loja', 'editar-cliente')) ?>?id=<?php echo $cliente->id ?>" class="btn btn-default btn-icon-action" data-toggle="tooltip" data-placement="bottom" title="Editar"><i class="fa fa-pencil-square-o"></i></a>

                                                <!-- <a href="<?php echo base_url(array('loja', 'historico-cliente')) ?>?id=<?php echo $cliente->id ?>" class="btn btn-default btn-icon-action" data-toggle="tooltip" data-placement="bottom" title="Historico"><i class="fa fa-book fa-fw"></i></a> -->

                                                <a href="<?php echo base_url(array('loja', 'excluir-cliente')) ?>" class="btn btn-default btn-icon-action delete-item" data-item="<?php echo $cliente->id ?>" data-toggle="tooltip" data-placement="bottom" title="Excluir"><i class="fa fa-trash"></i></a>





                                            </td>
                                        </tr>
                            <?php
                                    }
                                }
                             ?>
                            </tbody>
                        <!-- <tfoot>
                        <tr>
                            <th>Rendering engine</th>
                            <th>Browser</th>
                            <th>Platform(s)</th>
                            <th>Engine version</th>
                            <th>CSS grade</th>
                        </tr>
                        </tfoot> -->
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

        //showNotification
        <?php if (isset($notification)): ?>
            showNotification(<?php echo '"'. $notification->type .'","'. $notification->title .'","'. $notification->message .'"' ?>)
        <?php endif ?>
    })
</script>
