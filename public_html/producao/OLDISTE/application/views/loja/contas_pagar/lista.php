<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Contas a pagar</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Loja</a>
            </li>
            <li class="active">
                <strong>Contas a pagar</strong>
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
                        <a class="btn btn-primary" href="<?php echo base_url(array('loja', 'novo-contas_pagar')) ?>">
                            <!-- <i class="fa fa-plus"></i> -->
                            Nova
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-view" >
                            <thead>
                                <tr>
                                    <th class="on-print">ID</th>
                                    <th class="on-print">Fornecedor</th>
                                    <th class="on-print">Descrição</th>
                                    <th class="on-print">Valor</th>
                                    <th class="on-print">Plano de conta</th>
                                    <th class="on-print">Data Vencimento</th>
                                    <th class="on-print">Status</th>
                                    <th class="on-print">Data pagamento</th>

                                    <th class="no-orderable">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                if(!empty($dados)){
                                    foreach($dados as $valor){
                            ?>
                                        <tr class="gradeC" id="item-<?php echo $valor->id ?>">
                                            <td><?php echo $valor->id; ?></td>
                                            <td><?php echo $valor->fornecedor; ?></td>
                                            <td><?php echo $valor->descricao; ?></td>
                                            <td><?php echo $valor->valor; ?></td>
                                            <td><?php echo $valor->cod_plano_conta; ?> - <?php echo $valor->plano_conta; ?></td>
                                            <td><?php echo inverteData($valor->data_vencimento,'/'); ?></td>
                                            <td><?php echo $valor->status; ?></td>
                                            <td><?php echo (isset($valor->data_pago)) ? inverteData($valor->data_pago,'/') : '' ?></td>

                                            <td class="text-center">
                                                <!-- <a href="#" class="btn btn-default btn-icon-action" data-toggle="tooltip" title=""><i class="fa fa-check"></i></a> -->
                                                <!-- <a href="#" class="btn btn-default btn-icon-action" data-toggle="tooltip" title="Visualizar"><i class="fa fa-file-text-o"></i></a> -->
                                                    <a href="<?php echo base_url(array('loja', 'editar-contas_pagar')) ?>?id=<?php echo $valor->id ?>" class="btn btn-default btn-icon-action" data-toggle="tooltip" data-placement="bottom" title="Editar/Pagar"><i class="fa fa-pencil-square-o"></i></a>

                                                  <a href="<?php echo base_url(array('loja', 'excluir-contas_pagar')) ?>" class="btn btn-default btn-icon-action delete-item" data-item="<?php echo $valor->id ?>" data-toggle="tooltip" data-placement="bottom" title="Excluir"><i class="fa fa-trash"></i></a>



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
