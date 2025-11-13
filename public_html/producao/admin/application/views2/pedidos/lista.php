    <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Pedidos/vendas</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Loja</a>
            </li>
            <li class="active">
                <strong>Pedidos/vendas</strong>
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
                <div class="ibox-title">
                    <!-- <h5>FooTable with row toggler, sorting and pagination</h5> -->

                    <div class="ibox-tools">
                        <!-- <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a> -->
                        <a class="btn btn-primary" href="<?php echo base_url(array('site', 'novo-pedido')) ?>">
                            Realizar venda
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-view" >
                            <thead>
                                <tr>
                                    <th class="on-print">Origem</th>
                                    <th class="on-print">Código</th>
                                    <th class="on-print">Data pedido</th>
                                    <th class="on-print">Data/Hora entrega</th>
                                    <th class="on-print">Cliente / Recebedor</th>
                                    <th class="on-print">Telefone</th>
                                    <th class="on-print">Valor total</th>
                                    <th class="on-print">Tipo de pedido</th>
                                    <th class="on-print">Forma de pagamento</th>
                                    <th class="on-print">Status do pagamento</th>
                                    <th class="on-print">Status do pedido</th>
                                    <th class="no-orderable">Ações</th>
                                </tr>
                            </thead>


                            <tbody>
                            <?php
                                if(!empty($pedidos)){
                                    foreach($pedidos as $pedido){
                            ?>

                                        <!-- PEDIDO SITE -->
                                        <?php if($pedido->origem == 2): ?>

                                        <tr class="gradeC" id="item-<?php echo $pedido->id ?>">

                                            <?php 

                                            if($pedido->status_pedido == 0): $color_td = '';
                                            elseif($pedido->status_pedido == 1):  $color_td = '#000096';
                                            elseif($pedido->status_pedido == 2):  $color_td = '#000096';
                                            elseif($pedido->status_pedido == 3):  $color_td = '#81d255';
                                            elseif($pedido->status_pedido == 4):  $color_td = '#eea236';
                                            elseif($pedido->status_pedido == 5):  $color_td = '#eea236';
                                            endif;

                                                ?>

                                            <td style="color: <?php echo $color_td ?>">
                                            <?php 
                                            if($pedido->origem == 1): 
                                                echo 'Balcão'; 
                                            else: 
                                                echo '<img alt="image" width="15" src="'.base_url().'assets/img/carts.png">';
                                                echo ' Site'; 
                                            endif; ?>
                                            </td>
                                            <td style="color: <?php echo $color_td ?>"><?php echo $pedido->id; ?></td>
                                            <td style="color: <?php echo $color_td ?>"><?php  echo date("d/m/Y H:i", strtotime($pedido->data_pedido)); ?>ss</td>
                                            <td style="color: <?php echo $color_td ?>"><?php  echo date("d/m/Y", strtotime($pedido->data_entrega)); ?> <?php echo $pedido->hora_entrega; ?></td>
                                            <td style="color: <?php echo $color_td ?>"><?php echo $pedido->nome_cliente; ?></td>
                                            <td style="color: <?php echo $color_td ?>"><?php echo $pedido->telefone_cliente; ?></td>
                                            
                                            <td style="color: <?php echo $color_td ?>">R$ <?php echo number_format($pedido->valor_total, 2, ',', '.'); ?></td>

                                            <td style="color: <?php echo $color_td ?>">
                                            <?php echo ($pedido->tipo_pagamento == '1') ? 'Entrega em residência' : 'Retirar na loja' ?>
                                             </td>
                                            <td style="color: <?php echo $color_td ?>">
                                            <?php 
                                            

                                          if($pedido->tipo_pagamento == 2): 
                                                    echo '<span class="wishlist-in-stock">A escolher na loja</span>';
                                            elseif($pedido->tipo_pagamento == 1
                                            && $pedido->forma_pagamento == 1): 
                                                        echo '<span class="wishlist-in-stock">Dinheiro</span>';
                                            elseif($pedido->tipo_pagamento == 1
                                            && $pedido->forma_pagamento == 2): 
                                                        echo '<span class="wishlist-in-stock">Máquina de cartão</span>';
                                            
                                            elseif($pedido->tipo_pagamento == 1
                                            && $pedido->forma_pagamento == 3): 
                                                
                                                
                                                    if($pedido->tipo_pagamento_pagseguro == 1):
                                                    echo '<span class="wishlist-in-stock">Cartão de crédito</span>';
                                                elseif($pedido->tipo_pagamento_pagseguro == 2):
                                                        echo '<span class="wishlist-in-stock">Boleto</span>';
                                                elseif($pedido->tipo_pagamento_pagseguro == 3):
                                                            echo '<span class="wishlist-in-stock">Débito online</span>';
                                               elseif($pedido->tipo_pagamento_pagseguro == 4):
                                                                echo '<span class="wishlist-in-stock">Saldo pagseguro</span>';
                                               elseif($pedido->tipo_pagamento_pagseguro == 5):
                                                                    echo '<span class="wishlist-in-stock">Oi pago</span>';
                                                elseif($pedido->tipo_pagamento_pagseguro == 7):
                                                    echo '<span class="wishlist-in-stock">Depósito em conta</span>';
                                                else:
                                                        echo '<span class="wishlist-in-stock">Aguardando</span>';
                                               endif;

                                            endif;

                                            ?>
                                             </td>


                                             <td style="color: <?php echo $color_td ?>">
                                             <?php 
                                            
                                            if($pedido->tipo_pagamento == 2): 
                                                    echo '<span class="wishlist-in-stock">A realizar na loja</span>';
                                            elseif($pedido->tipo_pagamento == 1
                                            && $pedido->forma_pagamento == 1): 
                                                        echo '<span class="wishlist-in-stock">A realizar na entrega</span>';
                                            elseif($pedido->tipo_pagamento == 1
                                            && $pedido->forma_pagamento == 2): 
                                                        echo '<span class="wishlist-in-stock">A realizar na entrega</span>';

                                            elseif($pedido->tipo_pagamento == 1
                                            && $pedido->forma_pagamento == 3): 
                                                
                                                if($pedido->status_pagseguro == 0):
                                                    echo '<span class="wishlist-in-stock">Não iniciado</span>';
                                                elseif($pedido->status_pagseguro == 1):
                                                    echo '<span class="wishlist-in-stock">Aguardando</span>';
                                                elseif($pedido->status_pagseguro == 2):
                                                        echo '<span class="wishlist-in-stock">Em análise</span>';
                                                elseif($pedido->status_pagseguro == 3):
                                                            echo '<span class="wishlist-in-stock">Realizado</span>';
                                               elseif($pedido->status_pagseguro == 6):
                                                                echo '<span class="wishlist-in-stock">Devolvido</span>';
                                               elseif($pedido->status_pagseguro == 7):
                                                                    echo '<span class="wishlist-in-stock">Cancelado</span>';
                                               endif;

                                               elseif($pedido->status_pedido == 3): 
                                                echo '<span class="wishlist-in-stock">Realizado</span>';

                                            endif;



                                                ?>
                                             </td>
                                            
                                            <td style="color: <?php echo $color_td ?>">

                                                <?php 

                                                        if($pedido->status_pedido == 0): echo 'Aguardando confirmação da loja';
                                                        elseif($pedido->status_pedido == 1): echo 'Confirmado pela loja';
                                                        elseif($pedido->status_pedido == 2): echo 'Saiu para entrega';
                                                        elseif($pedido->status_pedido == 3): echo 'Pedido concluído';
                                                        elseif($pedido->status_pedido == 4): echo 'Pedido cancelado pela loja';
                                                        elseif($pedido->status_pedido == 5): echo 'Pedido cancelado pelo cliente';
                                                        endif;

                                                ?>
                                                    
                                            </td>

                                            <td class="text-center">
                                           
                                         
                                                <a href="<?php echo base_url(array('loja', 'detalhes-pedido')) .'?id='. $pedido->id ?>" class="btn btn-default btn-icon-action" data-toggle="tooltip" title="Visualizar Pedido"><i class="fa fa-plus-square"></i></a>

                                                <a href="javascript:void(0);" class="btn btn-default btn-icon-action editar-pedido" data-idpedido="<?php echo $pedido->id ?>" data-toggle="tooltip" title="Alterar status pedido"><i class="fa fa-truck"></i></a>

                                                <!-- <a <?php if ($pedido->status_pedido==10){ echo 'disabled'; } ?>
                                                        href="javascript:void(0);" class="btn btn-default btn-icon-action finalizar-pedido" data-idpedido="<?php echo $pedido->id ?>" data-toggle="tooltip" title="Finalizar Pedido"><i class="fa fa-thumbs-up"></i></a> -->

                                               
                                                <!-- <a href="<?php echo base_url(array('loja', 'excluir-produto')) ?>" class="btn btn-default btn-icon-action delete-item" data-item="<?php echo $pedido->id ?>" data-toggle="tooltip" data-placement="bottom" title="Excluir"><i class="fa fa-trash"></i></a> -->
                                            </td>
                                        </tr>

                                        <!-- PEDIDO BALCAO -->
                                        <?php else: ?>

                                            <tr class="gradeC" id="item-<?php echo $pedido->id ?>">

                                            <?php 

                                            if($pedido->status_pedido == 0): $color_td = '';
                                            elseif($pedido->status_pedido == 1):  $color_td = '#000096';
                                            elseif($pedido->status_pedido == 2):  $color_td = '#000096';
                                            elseif($pedido->status_pedido == 3):  $color_td = '#81d255';
                                            elseif($pedido->status_pedido == 4):  $color_td = '#eea236';
                                            elseif($pedido->status_pedido == 5):  $color_td = '#eea236';
                                            endif;

                                                ?>

                                            <td style="color: <?php echo $color_td ?>">
                                            <?php 
                                            if($pedido->origem == 1): 
                                                echo 'Balcão'; 
                                            else: 
                                                echo '<img alt="image" width="15" src="'.base_url().'assets/img/carts.png">';
                                                echo ' Site'; 
                                            endif; ?>
                                            </td>
                                            <td style="color: <?php echo $color_td ?>"><?php echo $pedido->id; ?></td>
                                            <td style="color: <?php echo $color_td ?>"><?php  echo date("d/m/Y H:i", strtotime($pedido->data_pedido)); ?></td>
                                            <td style="color: <?php echo $color_td ?>"><?php  echo date("d/m/Y", strtotime($pedido->data_entrega)); ?> <?php echo $pedido->hora_entrega; ?></td>
                                            <td style="color: <?php echo $color_td ?>"><?php echo $pedido->cliente_pedido; ?> / <?php echo $pedido->pessoa_entrega; ?></td>
                                            <td style="color: <?php echo $color_td ?>"><?php echo $pedido->cliente_telefone; ?></td>
                                            
                                            <td style="color: <?php echo $color_td ?>">R$ <?php echo number_format($pedido->valor_total, 2, ',', '.'); ?></td>

                                            <td style="color: <?php echo $color_td ?>">
                                            Balcão
                                             </td>
                                            <td style="color: <?php echo $color_td ?>">
                                            <?php 
                                            

                                          if($pedido->forma_pagamento_balcao == 1): 
                                                    echo '<span class="wishlist-in-stock">Dinheiro</span>';
                                            elseif($pedido->forma_pagamento_balcao == 2): 
                                                        echo '<span class="wishlist-in-stock">Débito</span>';
                                                        elseif($pedido->forma_pagamento_balcao == 3): 
                                                            echo '<span class="wishlist-in-stock">Crédito 1x</span>';
                                                            elseif($pedido->forma_pagamento_balcao == 4): 
                                                                echo '<span class="wishlist-in-stock">Crédito 2x</span>';
                                                                elseif($pedido->forma_pagamento_balcao == 5): 
                                                                    echo '<span class="wishlist-in-stock">Crédito 3x</span>';
                                                                    elseif($pedido->forma_pagamento_balcao == 6): 
                                                                        echo '<span class="wishlist-in-stock">Crédito 4x</span>';
                                                                        elseif($pedido->forma_pagamento_balcao == 7): 
                                                                            echo '<span class="wishlist-in-stock">Duplicata</span>';
                                                                            elseif($pedido->forma_pagamento_balcao == 8): 
                                                                                echo '<span class="wishlist-in-stock">Cheque</span>';
                                                                                elseif($pedido->forma_pagamento_balcao == 9): 
                                                                                    echo '<span class="wishlist-in-stock">Pix</span>';


                                          endif;


                                            ?>
                                             </td>


                                             <td style="color: <?php echo $color_td ?>">
                                             <!-- Pago balcãos -->
                                             <?php if($pedido->pedido_pago == 0) echo 'Não pago'; else echo 'Pago'; ?>
                                             </td>
                                            
                                            <td style="color: <?php echo $color_td ?>">

                                                <?php 

                                                        if($pedido->status_pedido == 0): echo 'Aguardando confirmação da loja';
                                                        elseif($pedido->status_pedido == 1): echo 'Confirmado pela loja';
                                                        elseif($pedido->status_pedido == 2): echo 'Saiu para entrega';
                                                        elseif($pedido->status_pedido == 3): echo 'Pedido concluído';
                                                        elseif($pedido->status_pedido == 4): echo 'Pedido cancelado pela loja';
                                                        elseif($pedido->status_pedido == 5): echo 'Pedido cancelado pelo cliente';
                                                        endif;

                                                ?>
                                                    
                                            </td>

                                            <td class="text-center" width="100">
                                           
                                         
                                                <a href="<?php echo base_url(array('loja', 'detalhes-pedido')) .'?id='. $pedido->id ?>" class="btn btn-default btn-icon-action" data-toggle="tooltip" title="Visualizar Pedido"><i class="fa fa-plus-square"></i></a>

                                                <?php if ($pedido->status_pedido != '4' && $pedido->status_pedido != '5'): ?>
                                                <a href="javascript:void(0);" class="btn btn-default btn-icon-action editar-pedido" data-idpedido="<?php echo $pedido->id ?>" data-toggle="tooltip" title="Alterar status pedido"><i class="fa fa-truck"></i></a>

                                                <!-- <a <?php if ($pedido->status_pedido==10){ echo 'disabled'; } ?>
                                                        href="javascript:void(0);" class="btn btn-default btn-icon-action finalizar-pedido" data-idpedido="<?php echo $pedido->id ?>" data-toggle="tooltip" title="Finalizar Pedido"><i class="fa fa-thumbs-up"></i></a> -->

                                                <a href="<?php echo base_url(array('site', 'editar-pedido')) ?>?id=<?php echo $pedido->id ?>" class="btn btn-default btn-icon-action" data-toggle="tooltip" data-placement="bottom" title="Editar"><i class="fa fa-pencil-square-o"></i></a>
                                                <!-- <a href="<?php echo base_url(array('loja', 'excluir-produto')) ?>" class="btn btn-default btn-icon-action delete-item" data-item="<?php echo $pedido->id ?>" data-toggle="tooltip" data-placement="bottom" title="Excluir"><i class="fa fa-trash"></i></a> -->
                                                <?php endif; ?>
                                            </td>
                                        </tr>

                                        <?php endif; ?>
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


      $(".finalizar-pedido").on('click', function(){
            var idpedido = $(this).data('idpedido');

               $.ajax({
                url:  '<?php echo base_url('finalizar-pedido') ?>',
                type: 'POST',
                data: {
                    //desconto:$('#cupom_valido').val(),
                    id: idpedido,

                },
                beforeSend: function(){
                    $('#loading').removeClass('loading-off');
                },
                success: function(result) {

                  result = JSON.parse(result);
                  if(result){
                     location.reload();
                  }

              
                },
                complete: function(){
                    $('#loading').addClass('loading-off');
                }
            });
        })   


    })




</script>