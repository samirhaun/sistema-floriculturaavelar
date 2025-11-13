
            <!-- Breadcrumb Area start -->
            <section class="breadcrumb-area">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="breadcrumb-content">
                                <h1 class="breadcrumb-hrading">Carrinho</h1>
                                <ul class="breadcrumb-links">
                                    <li><a href="<?php echo base_url() ?>">Home</a></li>
                                    <li>Carrinho</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Breadcrumb Area End -->
            <!-- cart area start -->
            <div class="cart-main-area mtb-60px">
                <div class="container">
                    <h3 class="cart-page-title">Meu carrinho</h3>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <form action="#">
                                <div class="table-content table-responsive cart-table-content">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Imagem</th>
                                                <th>Descrição produto</th>
                                                <th>Valor</th>
                                                <th>Qtd</th>
                                                <th>Subtotal</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        <?php $total_carrinho = 0; if(isset($carrinho)): ?>
                                  <?php foreach ($carrinho as $key => $produto): ?>

                                            <tr>
                                                <td class="product-thumbnail">
                                                    <a href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>"><img width="98" src="<?php echo base_url() ?>assets/images/produtos/<?php echo $produto->imagem ?>" alt="" /></a>
                                                </td>
                                                <td class="product-name"><a href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>"><?php echo $produto->titulo ?></a></td>
                                                <td class="product-price-cart"><span class="amount">R$<?php echo number_format($produto->valor, 2, ',', '.')  ?></span></td>
                                                <td class="product-quantity">
                                                    <div class="cart-plus-minus">
                                                       <div class="dec qtybutton" onclick="remove_carrinho_qtd(<?php echo $produto->id ?>)">-</div>
                                                        <input class="cart-plus-minus-box" type="text" name="qtybutton" id="quantidade_prod_<?php echo $produto->id ?>" value="<?php echo $produto->quantidade ?>" />
                                                       <div class="inc qtybutton" onclick="add_carrinho_qtd(<?php echo $produto->id ?>)">+</div>
                                                    </div>
                                                </td>
                                                <td class="product-subtotal">R$<?php echo number_format($produto->valor * $produto->quantidade, 2, ',', '.')  ?></td>
                                                <td class="product-remove">
                                                    <a href="javascript:void(0)" onclick="remover_produto_all(<?php echo $produto->id ?>)"><i class="fa fa-times"></i></a>
                                                </td>
                                            </tr>

                                            <?php $total_carrinho +=  $produto->valor*$produto->quantidade; ?>
                                  <?php endforeach; ?>
                                <?php endif; ?>


                                            
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="cart-shiping-update-wrapper">
                                            <div class="cart-shiping-update">
                                                <a href="<?php echo base_url() ?>produtos">Continuar comprando</a>
                                            </div>
                                            <div class="cart-clear">
                                                <!-- <button>Update Shopping Cart</button> -->
                                                <!-- <a href="#">Limpar carrinho</a> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                
                                <div class="col-lg-12 col-md-12">
                                    <div class="grand-totall">
                                        <div class="title-wrap">
                                            <h4 class="cart-bottom-title section-bg-gary-cart">Total carrinho</h4>
                                        </div>
                                        <h5>Total produtos <span>R$<?php echo number_format($total_carrinho, 2, ',', '.')  ?></span></h5>
                                        <div class="total-shipping">
                                            <!-- <h5>Total frete</h5> -->
                                            <ul>
                                                <li>Frete <span>R$7,00</span></li>
                                            </ul>
                                        </div>
                                        <h4 class="grand-totall-title">Total <span>R$<?php echo number_format($total_carrinho + 7, 2, ',', '.')  ?></span></h4>
                                        <a href="<?php echo base_url() ?>finalizar-pedido">Finalizar compra</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- cart area end -->


            <script type="text/javascript">
        
    function add_carrinho_qtd(idproduto){


        $.ajax({
          url:  '<?php echo base_url('add-carrinho') ?>',
          type: 'POST',
          data: {
              id: idproduto,
              quantidade: 1
          },
          beforeSend: function(){

          },
          success: function(result) {

            if(result == 'bad_qtd'){

                swal("Quantidade não permitida", "Quantidade superior ao estoque do produto", "warning");

                remove_carrinho_qtd(idproduto);

            }else{

                var url = "<?php echo base_url() ?>carrinho";
                location.href = url;

            }

          },
          complete: function(){

          }
      });


    }


    function remove_carrinho_qtd(idproduto){


        $.ajax({
          url:  '<?php echo base_url('remove-carrinho') ?>',
          type: 'POST',
          data: {
              id: idproduto
          },
          beforeSend: function(){

          },
          success: function(result) {

            if(result == 'bad_qtd'){

                swal("Quantidade não permitida", "Quantidade superior ao estoque do produto", "warning");

            }else{

                var url = "<?php echo base_url() ?>carrinho";
                location.href = url;

            }

          },
          complete: function(){

          }
      });


    }


    function remover_produto_all(idproduto){

             swal({
              title: "Remover",
              text: "Você deseja remover este produto do carrinho?",
              icon: "warning",
              buttons: true,
              buttons: ["Cancelar", "Remover"],
              dangerMode: true,
            })
            .then((willDelete) => {

              if (willDelete) {
                
                    $.ajax({
                          url:  '<?php echo base_url('remove-todos-carrinho') ?>',
                          type: 'POST',
                          data: {
                            id: idproduto
                          },
                          beforeSend: function(){
                          },
                          success: function(result) {
                            //result = JSON.parse(result);
                            window.location.href = '<?php echo base_url('carrinho') ?>';
                          },
                          complete: function(){
                          }
                    });

              } 

            });


    }


    function lista_produtos(){
        window.location.href = '<?php echo base_url('produtos') ?>';
    }

</script>
            