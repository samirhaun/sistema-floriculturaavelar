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
                <th>Código pedido</th>
                <th>Cliente/Fornecedor</th>
                <th>Data vencimento</th>
                <th>Valor</th>
                <th>Status</th>
                <th>Data pagamento</th>
                <th>Total pago</th>
                <th>Forma de pagamento</th>

              </tr>
            </thead>
            <tbody>
              <?php $total_receitas = 0; $total_receitas_pagas = 0;?>

              
              

              <?php
                                if(!empty($pedidos)){

                                    $array_conf_ped = array();

                                    foreach($pedidos as $pedido){

                                      

                                      

                                      if($pedido->status_receita == 1):

                                        $total_receitas_pagas = $total_receitas_pagas + $pedido->valor_receita;

                                      else:
                                        $total_receitas = $total_receitas + $pedido->valor_receita;
                                      endif;



                            ?>


    <tr class="gradeC" id="item-<?php echo $pedido->id ?>">

    <?php 

    if($pedido->status_receita == 0): $color_td = '';
    elseif($pedido->status_receita == 1):  $color_td = '#000096';
    endif;

        ?>

    <td><?php echo $pedido->id; ?></td>
    <td><?php echo $pedido->cliente_pedido; ?></td>
    <td><?php  echo date("d/m/Y", strtotime($pedido->vencimento_receita)); ?></td>
    <td>R$ <?php echo number_format($pedido->valor_receita, 2, ',', '.'); ?></td>
    <td><?php echo ($pedido->status_receita == 0) ? 'Em aberto' : 'Pago'; ?></td>
    <td><?php echo ($pedido->data_pago_receita) ? date("d/m/Y", strtotime($pedido->data_pago_receita)) : '-'; ?></td>
    <td><?php echo ($pedido->status_receita == 1) ? number_format($pedido->valor_receita, 2, ',', '.') : '-'; ?></td>

  
    <td>
      <?php 
        
        if($pedido->forma_pgto_receita == 1): 
          $forma_pgto_cred = '<span class="wishlist-in-stock">Dinheiro</span>';
          elseif($pedido->forma_pgto_receita == 2): 
              $forma_pgto_cred = '<span class="wishlist-in-stock">Débito</span>';
              elseif($pedido->forma_pgto_receita == 3): 
                  $forma_pgto_cred = '<span class="wishlist-in-stock">Crédito 1x</span>';
                  elseif($pedido->forma_pgto_receita == 4): 
                      $forma_pgto_cred = '<span class="wishlist-in-stock">Crédito 2x</span>';
                      elseif($pedido->forma_pgto_receita == 5): 
                          $forma_pgto_cred = '<span class="wishlist-in-stock">Crédito 3x</span>';
                          elseif($pedido->forma_pgto_receita == 6): 
                              $forma_pgto_cred = '<span class="wishlist-in-stock">Crédito 4x</span>';
                              elseif($pedido->forma_pgto_receita == 7): 
                                  $forma_pgto_cred = '<span class="wishlist-in-stock">Duplicata</span>';
                                  elseif($pedido->forma_pgto_receita == 8): 
                                      $forma_pgto_cred = '<span class="wishlist-in-stock">Cheque</span>';
                                      elseif($pedido->forma_pgto_receita == 9): 
                                        $forma_pgto_cred = '<span class="wishlist-in-stock">Pix</span>';
                                      else:
                                        $forma_pgto_cred = '-';
                                      endif;


                                      echo $forma_pgto_cred;
     
      ?>
    </td>





    
</tr>




                                        <?php
                                        
                                                }
                                    }
                            ?>


                              <?php 
                              $total_ct_pagar = 0; 
                              $total_ct_pago = 0; 
                              if(!empty($contas_pagar)):  
                              foreach($contas_pagar as $conta_pagar): 

                                

                                if($conta_pagar->status == 1):
                                  $total_ct_pago = $total_ct_pago + $conta_pagar->valor;
                                else:
                                  $total_ct_pagar = $total_ct_pagar + $conta_pagar->valor;
                                endif;

                              ?>

                                <tr style="background: #e7c6c6;" class="gradeC" id="item-<?php echo $conta_pagar->id ?>">
                               
                                

                                <td>-</td>
    <td><?php echo $conta_pagar->fornecedor; ?></td>
    <td><?php  echo date("d/m/Y", strtotime($conta_pagar->data_vencimento)); ?></td>
    <td>R$ <?php echo number_format($conta_pagar->valor, 2, ',', '.'); ?></td>
    <td><?php echo ($conta_pagar->status == 0) ? 'Em aberto' : 'Paga'; ?></td>
    <td><?php echo ($conta_pagar->data_pago) ? date("d/m/Y", strtotime($conta_pagar->data_pago)) : '-'; ?></td>
    <td><?php echo ($conta_pagar->status == 1) ? number_format($conta_pagar->valor, 2, ',', '.') : '-'; ?></td>

  
    <td>
      <?php 
        
        if($conta_pagar->forma_pgto == 1): 
          $forma_pgto_cred = '<span class="wishlist-in-stock">Dinheiro</span>';
          elseif($conta_pagar->forma_pgto == 2): 
              $forma_pgto_cred = '<span class="wishlist-in-stock">Débito</span>';
              elseif($conta_pagar->forma_pgto == 3): 
                  $forma_pgto_cred = '<span class="wishlist-in-stock">Crédito 1x</span>';
                  elseif($conta_pagar->forma_pgto == 4): 
                      $forma_pgto_cred = '<span class="wishlist-in-stock">Crédito 2x</span>';
                      elseif($conta_pagar->forma_pgto == 5): 
                          $forma_pgto_cred = '<span class="wishlist-in-stock">Crédito 3x</span>';
                          elseif($conta_pagar->forma_pgto == 6): 
                              $forma_pgto_cred = '<span class="wishlist-in-stock">Crédito 4x</span>';
                              elseif($conta_pagar->forma_pgto == 7): 
                                  $forma_pgto_cred = '<span class="wishlist-in-stock">Duplicata</span>';
                                  elseif($conta_pagar->forma_pgto == 8): 
                                      $forma_pgto_cred = '<span class="wishlist-in-stock">Cheque</span>';
                                      elseif($conta_pagar->forma_pgto == 9): 
                                        $forma_pgto_cred = '<span class="wishlist-in-stock">Pix</span>';
                                      else:
                                        $forma_pgto_cred = '-';
                                      endif;


                                      echo $forma_pgto_cred;
     
      ?>
    </td>

                                </tr>



                              <?php endforeach; endif; ?>




            </tbody>

            <tfoot>


            <!-- infos -->

            


            <!-- infos -->


             
              <!-- FIM PAGAR -->

              <tr>
                <td colspan="11" class="text-right">
                    <b>(A) TOTAL A RECEBER:</b> 
                    <?php echo 'R$ ' . number_format($total_receitas, 2, ',', '.'); ?>
                </td>
              </tr>

              <tr>
                <td colspan="11" class="text-right">
                    <b>(B) TOTAL RECEBIDO:</b> 
                    <?php echo 'R$ ' . number_format($total_receitas_pagas, 2, ',', '.'); ?>
                </td>
              </tr>


              <tr>
                <td colspan="11" class="text-right">
                    <b>(C) -TOTAL A PAGAR:</b> 
                    <?php echo 'R$ ' . number_format($total_ct_pagar, 2, ',', '.'); ?>
                </td>
              </tr>

              <tr>
                <td colspan="11" class="text-right">
                    <b>(D) -TOTAL PAGO:</b> 
                    <?php echo 'R$ ' . number_format($total_ct_pago, 2, ',', '.'); ?>
                </td>
              </tr>


              <tr>
                <td colspan="11" class="text-right">
                    <b>(A - C) =PROVISAO:</b> 
                     <?php echo 'R$ ' . number_format($total_receitas - $total_ct_pagar, 2, ',', '.'); ?> 
                </td>
              </tr>

              <tr>
                <td colspan="11" class="text-right">
                    <b>(B - D) =SALDO:</b> 
                    <?php echo 'R$ ' . number_format($total_receitas_pagas - $total_ct_pago, 2, ',', '.'); ?> 
                </td>
              </tr>

              
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
