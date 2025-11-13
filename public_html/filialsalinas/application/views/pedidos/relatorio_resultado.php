<div class="wrapper wrapper-content animated fadeInRight" id="tabela_resultados">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title hidden-print" style="min-height: 64px;">
          <div class="row">
            <div class="col-lg-12 text-right">
              <button class="btn btn-success" onclick="imprimir_resultado()" type="button"><i class="fa fa-print"></i><span class="bold"> Imprimir</span></button>
                          
            </div>
          </div>
        </div>
        <div class="ibox-content">
          <style type="text/css">
            .table > tbody > tr > td{
                  padding: 1px !important;
                  vertical-align: middle;
            }
          </style>
          <table class="table table-stripped table-bordered toggle-arrow-tiny">
            <thead>
              <tr>
                <th>Origem</th>
                <th>Código</th>
                <th>Data solicitação</th>
                <th>Data pago</th>
                <th>Data entrega</th>
                <th>Cliente/Fornecedor</th>
                <th>Valor produtos</th>
                <th>Frete</th>
                <th>Subtotal</th>
                <th>Descontos</th>
                <th>Total</th>
                <th>Entrada</th>
                <th>Total pago</th>
                <th>Forma de pagamento</th>

              </tr>
            </thead>
            <tbody>
              <?php $t_produtos=0; $t_desconto=0; $t_frete=0; $t_geral=0; $t_entradas=0; $t_geral_prod_fret = 0;$t_geral_pago_pedido = 0;?>

              
              

              <?php
                                if(!empty($pedidos)){

                                    $array_conf_ped = array();

                                    foreach($pedidos as $pedido){

                                      

                                      if($pedido->status_pedido != 4 && $pedido->status_pedido != 5):

                                        if(!isset($array_conf_ped[$pedido->id])):

                                        $t_produtos = $t_produtos + $pedido->valor;

                                        if($pedido->tipo_desconto == 'porcentagem'):
                                          $t_desconto = $t_desconto + ($pedido->valor_desconto * $pedido->valor / 100);
                                        else:
                                          $t_desconto = $t_desconto + $pedido->valor_desconto;
                                        endif;

                                        $t_frete = $t_frete + $pedido->valor_frete;
                                        $t_geral = $t_geral + $pedido->valor_total;
                                        $t_entradas = $t_entradas + $pedido->valor_entrada;

                                        $t_geral_prod_fret = $t_geral_prod_fret + $pedido->valor + $pedido->valor_frete;

                                        $array_conf_ped[$pedido->id] = 1;

                                        endif;



                            ?>

                                       <!-- PEDIDO SITE -->
                                       <?php if($pedido->origem == 2): ?>

<tr>


    <td>
    <?php 
    if($pedido->origem == 1): 
        echo 'Balcão'; 
    else: 
        echo '<img alt="image" width="15" src="'.base_url().'assets/img/carts.png">';
        echo ' Site'; 
    endif; ?>
    </td>
    <td><?php echo $pedido->id; ?></td>
    <td><?php  echo date("d/m/Y H:i", strtotime($pedido->data_pedido)); ?></td>
    <td><?php  echo date("d/m/Y", strtotime($pedido->data_pago)); ?></td>
    <td><?php  echo date("d/m/Y", strtotime($pedido->data_entrega)); ?> <?php  echo $pedido->hora_entrega; ?></td>
    <td><?php echo $pedido->nome_cliente; ?></td>
    
    <td>R$ <?php echo number_format($pedido->valor, 2, ',', '.'); ?></td>
    <td>R$ <?php echo number_format($pedido->valor_frete, 2, ',', '.'); ?></td>
    <td>R$ <?php echo number_format($pedido->valor + $pedido->valor_frete, 2, ',', '.'); ?></td>
    <td>R$ -
      <?php 
      if($pedido->tipo_desconto == 'porcentagem'):
        echo number_format($pedido->valor_desconto * $pedido->valor / 100, 2, ',', '.'); 
      else:
        echo number_format($pedido->valor_desconto, 2, ',', '.'); 
      endif;
      ?>
    </td>
    <td>R$ <?php echo number_format($pedido->valor_entrada, 2, ',', '.'); ?></td>
    
    <td>R$ k<?php echo number_format($pedido->valor_total, 2, ',', '.'); ?></td>

    
    <td>
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

    <td>
    <?php 
    if($pedido->origem == 1): 
        echo 'Balcão'; 
    else: 
        echo '<img alt="image" width="15" src="'.base_url().'assets/img/carts.png">';
        echo ' Site'; 
    endif; ?>
    </td>
    <td><?php echo $pedido->id; ?></td>
    <td><?php  echo date("d/m/Y H:i", strtotime($pedido->data_pedido)); ?></td>

    <td>
    <?php 
      if($pedido->tipo_credito == 'entrada'):

        echo date("d/m/Y", strtotime($pedido->data_pgto_entrada));

      else:

        echo ($pedido->data_pago) ? date("d/m/Y", strtotime($pedido->data_pago)) : '-';
        
      endif;
      ?>
  
  </td>

    <td><?php  echo date("d/m/Y", strtotime($pedido->data_entrega)); ?> <?php  echo $pedido->hora_entrega; ?></td>
    <td><?php echo $pedido->cliente_pedido; ?></td>
    
    <td>R$ <?php echo number_format($pedido->valor, 2, ',', '.'); ?></td>
    <td>R$ <?php echo number_format($pedido->valor_frete, 2, ',', '.'); ?></td>
    <td>R$ <?php echo number_format($pedido->valor + $pedido->valor_frete, 2, ',', '.'); ?></td>
    <td>R$ -
      <?php 

      if($pedido->tipo_desconto == 'porcentagem'):
        echo number_format($pedido->valor_desconto * $pedido->valor / 100, 2, ',', '.'); 
      else:
        echo number_format($pedido->valor_desconto, 2, ',', '.'); 
      endif;
      
      ?>
    </td>
    <td>R$ <?php echo number_format($pedido->valor_total, 2, ',', '.'); ?></td>
    <td>R$ <?php echo number_format($pedido->valor_entrada, 2, ',', '.'); ?></td>
    
    
    <!-- <td>R$s <?php echo number_format($pedido->valor_total, 2, ',', '.'); ?></td> -->
    <td>R$
      <?php 
        if($pedido->tipo_credito == 'entrada' || $pedido->valor_entrada > 0):
        $tot_pago_ped = $pedido->valor_entrada;


        
        if($pedido->forma_pgto_entrada == 1): 
          $forma_pgto_cred = '<span class="wishlist-in-stock">Dinheiro</span>';
          elseif($pedido->forma_pgto_entrada == 2): 
              $forma_pgto_cred = '<span class="wishlist-in-stock">Débito</span>';
              elseif($pedido->forma_pgto_entrada == 3): 
                  $forma_pgto_cred = '<span class="wishlist-in-stock">Crédito 1x</span>';
                  elseif($pedido->forma_pgto_entrada == 4): 
                      $forma_pgto_cred = '<span class="wishlist-in-stock">Crédito 2x</span>';
                      elseif($pedido->forma_pgto_entrada == 5): 
                          $forma_pgto_cred = '<span class="wishlist-in-stock">Crédito 3x</span>';
                          elseif($pedido->forma_pgto_entrada == 6): 
                              $forma_pgto_cred = '<span class="wishlist-in-stock">Crédito 4x</span>';
                              elseif($pedido->forma_pgto_entrada == 7): 
                                  $forma_pgto_cred = '<span class="wishlist-in-stock">Duplicata</span>';
                                  elseif($pedido->forma_pgto_entrada == 8): 
                                      $forma_pgto_cred = '<span class="wishlist-in-stock">Cheque</span>';
                                      elseif($pedido->forma_pgto_entrada == 9): 
                                        $forma_pgto_cred = '<span class="wishlist-in-stock">Pix</span>';
                                      endif;


      else:
        if($pedido->valor_entrada > 0 && $pedido->pedido_pago == 1):
          $tot_pago_ped = $pedido->valor_total - $pedido->valor_entrada;
        elseif($pedido->pedido_pago == 1):
          $tot_pago_ped = $pedido->valor_total;
        else:
          $tot_pago_ped = 0;
        endif;



        if($pedido->forma_pagamento_balcao == 1): 
          $forma_pgto_cred = '<span class="wishlist-in-stock">Dinheiro</span>';
          elseif($pedido->forma_pagamento_balcao == 2): 
              $forma_pgto_cred = '<span class="wishlist-in-stock">Débito</span>';
              elseif($pedido->forma_pagamento_balcao == 3): 
                  $forma_pgto_cred = '<span class="wishlist-in-stock">Crédito 1x</span>';
                  elseif($pedido->forma_pagamento_balcao == 4): 
                      $forma_pgto_cred = '<span class="wishlist-in-stock">Crédito 2x</span>';
                      elseif($pedido->forma_pagamento_balcao == 5): 
                          $forma_pgto_cred = '<span class="wishlist-in-stock">Crédito 3x</span>';
                          elseif($pedido->forma_pagamento_balcao == 6): 
                              $forma_pgto_cred = '<span class="wishlist-in-stock">Crédito 4x</span>';
                              elseif($pedido->forma_pagamento_balcao == 7): 
                                  $forma_pgto_cred = '<span class="wishlist-in-stock">Duplicata</span>';
                                  elseif($pedido->forma_pagamento_balcao == 8): 
                                      $forma_pgto_cred = '<span class="wishlist-in-stock">Cheque</span>';
                                      elseif($pedido->forma_pagamento_balcao == 9): 
                                        $forma_pgto_cred = '<span class="wishlist-in-stock">Pix</span>';
                                      endif;


      endif;
      echo number_format($tot_pago_ped, 2, ',', '.');
      $t_geral_pago_pedido = $t_geral_pago_pedido + $tot_pago_ped;
      ?>
    </td>



    <td>
    <?php 
    

  echo $forma_pgto_cred;


  


    ?>
     </td>


     
    
    

    
</tr>

<?php endif; ?>



                                        <?php
                                        endif;
                                                }
                                    }
                            ?>


                              <?php 
                              $total_ct_pagar = 0; 
                              $total_ct_pago = 0; 
                              if(!empty($contas_pagar)):  
                              foreach($contas_pagar as $conta_pagar): 

                                $total_ct_pagar = $total_ct_pagar + $conta_pagar->valor;

                                if($conta_pagar->status == 1):
                                  $total_ct_pago = $total_ct_pago + $conta_pagar->valor;
                                endif;

                              ?>

                                <tr style="background: #e7c6c6;" class="gradeC" id="item-<?php echo $conta_pagar->id ?>">
                                <td>-</td>
                                <td><?php echo $conta_pagar->id ?></td>
                                <td><?php echo inverteData($conta_pagar->data_pago,'/'); ?></td>
                                <td colspan="5"><?php echo $conta_pagar->fornecedor ?> - <?php echo $conta_pagar->descricao ?></td>
                                <td colspan="2"><?php echo $conta_pagar->cod_plano_conta ?> - <?php echo $conta_pagar->plano_conta ?></td>
                                <td>-R$ <?php echo number_format($conta_pagar->valor, 2, ',', '.'); ?></td>
                                <td>-</td>
                                <td>-R$ <?php echo ($conta_pagar->status == 1) ? number_format($conta_pagar->valor, 2, ',', '.') : '0.00'; ?></td>
                                <td>
                                  <?php

if($conta_pagar->forma_pgto == 1): 
  echo 'Dinheiro';
elseif($conta_pagar->forma_pgto == 2): 
      echo 'Débito';
      elseif($conta_pagar->forma_pgto == 3): 
          echo 'Crédito 1x';
          elseif($conta_pagar->forma_pgto == 4): 
              echo 'Crédito 2x';
              elseif($conta_pagar->forma_pgto == 5): 
                  echo 'Crédito 3x';
                  elseif($conta_pagar->forma_pgto == 6): 
                      echo 'Crédito 4x';
                      elseif($conta_pagar->forma_pgto == 7): 
                          echo 'Duplicata';
                          elseif($conta_pagar->forma_pgto == 8): 
                              echo 'Cheque';
                              elseif($conta_pagar->forma_pgto == 9): 
                                echo 'Pix';
                          endif;

                                  ?>
                                </td>

                                </tr>



                              <?php endforeach; endif; ?>




            </tbody>

            <tfoot>


            <!-- infos -->

            <tr>
                <td colspan="6" class="text-right">
                    
                </td>

                <td colspan="1">
                    <b>TOTAL BRUTO PRODUTOS</b> 
                </td>

                <td colspan="1">
                    <b>TOTAL BRUTO FRETES</b> 
                </td>

                <td colspan="1">
                    <b>SUBTOTAL (PRODUTOS + FRETES)</b> 
                </td>

                <td colspan="1">
                    <b>TOTAL BRUTO DESCONTOS</b> 
                </td>

                <td colspan="1">
                    <b>SUBTOTAL - DESCONTOS</b> 
                </td>

               

                <td colspan="1">
                    <b>TOTAL ENTRADAS</b> 
                </td>

                <td colspan="1">
                    <b>TOTAL CREDITADO</b> 
                </td>

                <td colspan="1">
                    
                </td>

                

              </tr>


            <!-- infos -->

            <tr>
                <td colspan="6" class="text-right">
                    
                </td>

                <td colspan="1">
                    <b><?php echo 'R$ <br>' . number_format($t_produtos, 2, ',', '.'); ?></b> 
                </td>

                <td colspan="1">
                    <b><?php echo 'R$ ' . number_format($t_frete, 2, ',', '.'); ?></b> 
                </td>

                <td colspan="1">
                    <b><?php echo 'R$ ' . number_format($t_geral_prod_fret, 2, ',', '.'); ?></b> 
                </td>

                <td colspan="1">
                    <b><?php echo 'R$ -' . number_format($t_desconto, 2, ',', '.'); ?></b> 
                </td>

                <td colspan="1">
                    <b><?php echo 'R$ ' . number_format($t_geral_prod_fret - $t_desconto, 2, ',', '.'); ?></b> 
                </td>

               

                <td colspan="1">
                    <b><?php echo 'R$ ' . number_format($t_entradas, 2, ',', '.'); ?></b> 
                </td>

                <td colspan="1">
                    <b><?php echo 'R$ ' . number_format($t_geral_pago_pedido, 2, ',', '.'); ?></b> 
                </td>

                <td colspan="1">
                    
                </td>

                

              </tr>


              <!-- SE EXISTIR PAGAR VAMOS EXIBIR SOMATORIA E RESULTADO -->

              <?php if(!empty($contas_pagar)): ?>

                <tr style="background: #e7c6c6;"> 
                <td colspan="10" class="text-right">
                    
                </td>

               

               

                <td colspan="1">
                    <!-- <b><?php echo 'R$ -' . number_format($total_ct_pagar, 2, ',', '.'); ?></b>  -->
                </td>
                <td colspan="1">
                    -
                </td>

                <td colspan="1">
                    <b>TOTAL PAGO</b> 
                </td>

                <td colspan="1">
                    
                </td>

                

              </tr>

                <tr style="background: #e7c6c6;"> 
                <td colspan="10" class="text-right">
                    
                </td>

               

               

                <td colspan="1">
                    <!-- <b><?php echo 'R$ -' . number_format($total_ct_pagar, 2, ',', '.'); ?></b>  -->
                </td>
                <td colspan="1">
                    -
                </td>

                <td colspan="1">
                    <b><?php echo 'R$ -' . number_format(($t_geral_pago_pedido) - $total_ct_pago, 2, ',', '.'); ?></b> 
                </td>

                <td colspan="1">
                    
                </td>

                

              </tr>

              <tr style="background: #c6c7e7;"> 
                <td colspan="10" class="text-right">
                    
                </td>

               

               

                <td colspan="1">
                    <!-- <b><?php echo 'R$ -' . number_format(($t_geral_prod_fret - $t_desconto) - $total_ct_pagar, 2, ',', '.'); ?></b>  -->
                </td>
                <td colspan="1">
                    -
                </td>

                <td colspan="1">
                    <b>TOTAL RESULTADO</b> 
                </td>

                <td colspan="1">
                    
                </td>

                

              </tr>
              

              <tr style="background: #c6c7e7;"> 
                <td colspan="10" class="text-right">
                    
                </td>

               

               

                <td colspan="1">
                    <!-- <b><?php echo 'R$ -' . number_format(($t_geral_prod_fret - $t_desconto) - $total_ct_pagar, 2, ',', '.'); ?></b>  -->
                </td>
                <td colspan="1">
                    -
                </td>

                <td colspan="1">
                    <b><?php echo 'R$ -' . number_format($total_ct_pago, 2, ',', '.'); ?></b> 
                </td>

                <td colspan="1">
                    
                </td>

                

              </tr>
              

              <?php endif; ?>

              <!-- FIM PAGAR -->

              
              <!-- <tr>
                <td colspan="11" class="text-right">
                    <b>TOTAL PRODUTOS:</b> 
                    <?php echo 'R$ ' . number_format($t_produtos, 2, ',', '.'); ?>
                </td>
              </tr>

              <tr>
                <td colspan="11" class="text-right">
                    <b>+TOTAL FRETES:</b> 
                    <?php echo 'R$ ' . number_format($t_frete, 2, ',', '.'); ?>
                </td>
              </tr>

              <tr>
                <td colspan="11" class="text-right">
                    <b>+TOTAL ENTRADAS:</b> 
                    <?php echo 'R$ ' . number_format($t_entradas, 2, ',', '.'); ?>
                </td>
              </tr>

              <tr>
                <td colspan="11" class="text-right">
                    <b>-TOTAL DESCONTOS:</b> 
                    <?php echo 'R$ ' . number_format($t_desconto, 2, ',', '.'); ?>
                </td>
              </tr>

              <tr>
                <td colspan="11" class="text-right">
                    <b>-TOTAL CONTAS A PAGAR:</b> 
                    <?php echo 'R$ ' . number_format($total_ct_pagar, 2, ',', '.'); ?>
                </td>
              </tr>


              <tr>
                <td colspan="11" class="text-right">
                    <b>=TOTAL GERAL:</b> 
                     <?php echo 'R$ ' . number_format($t_produtos + $t_frete - $t_desconto - $total_ct_pagar, 2, ',', '.'); ?> 
                    <?php echo 'R$ ' . number_format($t_geral, 2, ',', '.'); ?>
                </td>
              </tr> -->

            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function imprimir_resultado(){
    
    window.print();

  }
</script>
