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
               <th>Produto</th>
                <th>Alerta estoque mínimo</th>
                <th>Quantidade disponível no estoque</th>

              </tr>
            </thead>
            <tbody>

              
              

              <?php
                                if(!empty($dados)){
                                    foreach($dados as $dado){





                            ?>

                                       <!-- PEDIDO SITE -->


                                    <tr>

                                        <td><?php echo $dado['desc_prod']; ?></td>
                                        <td><?php echo $dado['alerta_estoque_minimo']; ?></td>
                                        <td><?php echo $dado['qtd_disponivel']; ?></td>

                                        
                                    </tr>

                                    <?php
                               }
                            }
                            ?>

            </tbody>


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
