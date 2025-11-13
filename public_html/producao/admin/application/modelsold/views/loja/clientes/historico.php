    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Pedidos</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="#">Loja</a>
                </li>
                <li class="active">
                    <strong>Pedidos</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">

        </div>
    </div>
    <script type="text/javascript">


    </script>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">

                    <div class="ibox-content">
                        <div class="table-responsive">
                            <div class="row" style="margin-left: 0px;margin-bottom: 17px;">
                                <div class="col-sm-2" style="border-radius: 50%; height: 25px;
                                width: 25px; background-color: #2196f3; "></div>
                                <div class="col-sm-2">Entrada</div>
                                <div class="col-sm-2" style="border-radius: 50%; height: 25px;
                                width: 25px; background-color: #4caf50; "></div>
                                <div class="col-sm-2" >Em Produção</div>
                              
                                <div class="col-sm-2" style="border-radius: 50%; height: 25px;
                                width: 25px; background-color: #ff9800; "></div>
                                <div class="col-sm-2" >Proximo a Entrega</div>
                            </div>
                            <div class="row" style="margin-left: 0px;margin-bottom: 17px;">
                                <div class="col-sm-2" style="border-radius: 50%; height: 25px;
                                width: 25px; background-color: #f44336; "></div>
                                <div class="col-sm-2" >Atrasado</div>
                              
                                <div class="col-sm-2" style="border-radius: 50%; height: 25px;
                                width: 25px; background-color: #8a6d3b; "></div>
                                <div class="col-sm-2" >Concluido</div>
                                <div class="col-sm-2" style="border-radius: 50%; height: 25px;
                                width: 25px; background-color: #3c763d; "></div>
                                <div class="col-sm-2" >Orçamento</div>
                            </div><br>
                            <table class="table table-striped table-bordered table-hover dataTables-view" >
                                <thead>
                                    <tr>
                                        <th class="on-print">ID</th>
                                        <th class="on-print">Código</th>
                                        <th class="on-print">Usuário</th>
                                        <th class="on-print">Cliente</th>
                                        <th class="on-print">Data Entrada</th>
                                        <th class="on-print">Data Entrega</th>
                                        <th class="on-print">Status</th>
                                        <th class="no-orderable">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(!empty($pedidos)){
                                        foreach($pedidos as $pedido){
                                            ?>
                                            <tr class="gradeC" id="item-<?php echo $pedido->id ?>">
                                                <td><?php echo $pedido->id; ?></td>
                                                <td><?php echo $pedido->codigo_pedido; ?></td>
                                                <td><?php echo $pedido->nome_usuario; ?></td>
                                                <td><?php echo $pedido->nome_cliente; ?></td>
                                                <td><?php  echo ($pedido->data_solicitacao); ?></td>
                                                <td <?php $atual = strtotime(date('Y-m-d'));$entrega = explode('/', $pedido->data_entrega);$entrega = strtotime($entrega[2].'-'.$entrega[1].'-'.$entrega[0]);
                                                echo (($atual > $entrega && $pedido->status_pedido!=2) ? 'style="color:red"' : '');
                                                ?>
                                                ><?php $atual = strtotime(date('Y-m-d'));$entrega = explode('/', $pedido->data_entrega);$entrega = strtotime($entrega[2].'-'.$entrega[1].'-'.$entrega[0]);
                                                echo (($atual > $entrega && $pedido->status_pedido!=2) ? 'Atrasado - '.$pedido->data_entrega : $pedido->data_entrega);
                                                ?>
                                                    

                                                </td>


                                            <td <?php
                    switch ($pedido->status_pedido) {
                      case 0:
                      echo "style='background-color: #2196f3; color: white'";
                      break;
                      case 1:
                      echo "style='background-color: #4caf50; color: white'";
                      break;
                      case 2:
                      echo "style='background-color: #8a6d3b; color: white'";
                      break;
                      case 3:
                      echo "style='background-color: #ff9800; color: white'";
                      break;
                      case 4:
                      echo "style='background-color: #f44336; color: white'";
                      break;
                      case 5:
                      echo "style='background-color: #3c763d; color: white'";
                      break;

                      default:    
            # code...
                      break;
                    }
                    ?>><?php 

                                            switch ($pedido->status_pedido) {
                                                case 0:
                                                echo "Entrada";
                                                break;
                                                case 1:
                                                echo "Em produção";
                                                break;
                                                case 2:
                                                echo "Concluído";
                                                break;
                                                case 3:
                                                echo "Próximo a entrega";
                                                break;
                                                case 4:
                                                echo "Atrasado";
                                                break;
                                                case 5:
                                                echo "Orçamento";
                                                break;

                                                default:    
                                                        # code...
                                                break;
                                            }

                                            ?></td>
                                            <td class="text-center">

                                                <?php if ($pedido->status_pedido != 2) { ?>
                                                    <a href="<?php echo base_url(array('site', 'editar-pedido')) ?>?id=<?php echo $pedido->id ?>" class="btn btn-default btn-icon-action" data-toggle="tooltip" data-placement="bottom" title="Editar"><i class="fa fa-pencil-square-o"></i></a>
                                                <?php } ?>
                                                 
                                                

                                                <a href="<?php echo base_url(array('loja', 'detalhes-pedido')) .'?id='. $pedido->id ?>" class="btn btn-default btn-icon-action" data-toggle="tooltip" title="Visualizar Pedido"><i class="fa fa-plus-square"></i></a>

                                                <?php if ($pedido->status_pedido != 2) { ?>
                                                    <a href="javascript:void(0);" class="btn btn-default btn-icon-action editar-pedido" data-idpedido="<?php echo $pedido->id ?>" data-toggle="tooltip" title="Mudar Status"><i class="fa fa-refresh "></i></a>
                                                <?php } ?>
                                                

                                                <a href="<?php echo base_url('loja/fotos-pedidos') ?>?id=<?php echo $pedido->id ?>" class="btn btn-default btn-icon-action "  data-toggle="tooltip" title="Imagens"><i class="fa fa-photo "></i></a>

                                                <a href="javascript:void(0)" data-id="<?php echo $pedido->id ?>" class="btn btn-default btn-icon-action duplica_pedido"  data-toggle="tooltip" title="Duplicar Pedido"><i class="fa fa-files-o "></i></a>

                                                <!-- <a href="<?php echo base_url(array('loja', 'editar-produto')) ?>?id=<?php echo $pedido->id ?>" class="btn btn-default btn-icon-action" data-toggle="tooltip" data-placement="bottom" title="Editar"><i class="fa fa-pencil-square-o"></i></a> -->
                                                <?php if ($pedido->status_pedido != 2) { ?>
                                                    <a href="<?php echo base_url(array('site', 'excluir-pedido')) ?>" class="btn btn-default btn-icon-action delete-item" data-item="<?php echo $pedido->id ?>" data-toggle="tooltip" data-placement="bottom" title="Excluir"><i class="fa fa-trash"></i></a> 
                                                <?php } ?>
                                                
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
    $('.duplica_pedido').click(function(){
        var id = $(this).data('id');

        swal({
            title: "Atenção,",
            text: "Você tem certeza que deseja duplicar este pedido?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sim",
            cancelButtonText: "Não",
            closeOnConfirm: true,
            closeOnCancel: true },
            function (isConfirm) {
                if (isConfirm) {

                // swal("Deleted!", "Your imaginary file has been deleted.", "success");
                $.ajax({
                    url: '<?php echo base_url('loja/duplica-pedido') ?>',
                    type: 'POST',
                    data: {id: id},
                    beforeSend: function(){
                        $('#loading').removeClass('loading-off');
                    },
                    success: function(result) {
                      document.location.reload(true);
                  },
                  complete: function(){
                    $('#loading').addClass('loading-off');
                }
            });
            } else {
                // swal("Cancelled", "Your imaginary file is safe :)", "error");
            }
        });
    });

</script>


<div id="modal-pedido"></div>

<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();

        //showNotification
        <?php if (isset($notification)): ?>
            showNotification(<?php echo '"'. $notification->type .'","'. $notification->title .'","'. $notification->message .'"' ?>)
        <?php endif ?>

        $(".editar-pedido").on('click', function(){
            var $this = $(this);
            $('#modal-pedido').load('<?php echo base_url(array('loja', 'editar-pedido')) ?>'+'?id='+$this.data('idpedido'), function(){
                $('#myModal').modal('show');
            });
        })
    })


</script>
