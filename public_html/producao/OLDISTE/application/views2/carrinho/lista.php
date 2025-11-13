
<div class="banner-wrapper no_background">
    <div class="banner-wrapper-inner">
        <nav class="woocommerce-breadcrumb">
          <span>
            
            1 
            <span class="flaticon-user"></span>
            Identificação
            
          </span>

          <i class="fa fa-angle-right"></i>

          <span style="color: #000096;font-size: 16px;">
            
            2 
            <span class="flaticon-online-shopping-cart"></span>
            Meu Carrinho
            
          </span>

          <i class="fa fa-angle-right"></i>

          <span>
            <a href="<?php echo base_url() ?>finalizar-pedido">
            3 
            <span class="flaticon-truck"></span>
            Finalizar pedido
            </a>
          </span>

        </nav>
    </div>
</div>

<main class="site-main main-container no-sidebar">
    <div class="container">
        <div class="row">
            <div class="main-content col-sm-12">
                <div class="page-main-content">
                    <div class="woocommerce">
                        <div class="woocommerce-notices-wrapper"></div>
                        <form class="woocommerce-cart-form">
                            <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents"
                                   cellspacing="0">
                                <thead>
                                <tr>
                                    <th class="product-remove">&nbsp;</th>
                                    <th class="product-thumbnail">&nbsp;</th>
                                    <th class="product-name">Produto</th>
                                    <th class="product-price">Valor</th>
                                    <th class="product-quantity">Quantidade</th>
                                    <th class="product-subtotal">Total</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php $total_carrinho = 0; if(isset($carrinho)): ?>
                                  <?php foreach ($carrinho as $key => $produto): ?>

                                <tr class="woocommerce-cart-form__cart-item cart_item">
                                    <td class="product-remove">
                                        <a href="javascript:void()" onclick="remover_produto_all(<?php echo $produto->id ?>)" 
                                           class="remove" aria-label="Remover este produto" data-product_id="27"
                                           data-product_sku="885B712">×</a></td>
                                    <td class="product-thumbnail">
                                        <a href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>"><img
                                                src="<?php echo base_url() ?>assets/images/produtos/<?php echo $produto->imagem ?>"
                                                class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail"
                                                alt="" width="600" height="778"></a></td>
                                    <td class="product-name" data-title="Product">
                                        <a href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>"><?php echo $produto->titulo ?>
                                                </a></td>
                                    <td class="product-price" data-title="Price">
                                        <span class="woocommerce-Price-amount amount"><span
                                                class="woocommerce-Price-currencySymbol">R$</span><?php echo number_format($produto->valor, 2, ',', '.')  ?></span></td>
                                    <td class="product-quantity" data-title="Quantity">
                                        <div class="quantity">
                                                <span class="qty-label">Quantidade:</span>
                                                <div class="control">
                                                    <a class="btn-number qtyminus quantity-minus" href="javascript:void()" onclick="remove_carrinho_qtd(<?php echo $produto->id ?>)">-</a>
                                                    <input type="text" data-step="1" min="1" max="" name="quantity[25]" id="quantidade_prod_<?php echo $produto->id ?>" value="<?php echo $produto->quantidade ?>" title="Qty" class="input-qty input-text qty text" size="4" pattern="[0-9]*" inputmode="numeric">
                                                    <a class="btn-number qtyplus quantity-plus" href="javascript:void()"  onclick="add_carrinho_qtd(<?php echo $produto->id ?>)">+</a>
                                                </div>
                                            </div>
                                    </td>
                                    <td class="product-subtotal" data-title="Total">
                                        <span class="woocommerce-Price-amount amount"><span
                                                class="woocommerce-Price-currencySymbol">R$</span><?php echo number_format($produto->valor * $produto->quantidade, 2, ',', '.')  ?></span></td>
                                </tr>
                                    <?php $total_carrinho +=  $produto->valor*$produto->quantidade; ?>
                                  <?php endforeach; ?>
                                <?php endif; ?>


                               

                                <tr>
                                    <td colspan="6" class="actions">
                                        <div class="coupon">
                                            <!-- <label for="coupon_code">cupom:</label> <input type="text"
                                                                                            name="coupon_code"
                                                                                            class="input-text"
                                                                                            id="coupon_code" value=""
                                                                                            placeholder="Código do cupom">
                                            <button type="submit" class="button" name="apply_coupon"
                                                    value="Apply coupon">Aplicar cupom -->
                                            </button>
                                        </div>
                                        <button type="button" class="button" value="Continuar comprando" onclick="lista_produtos()" 
                                                >Continuar comprando
                                        </button>
                                       
                                       </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                        <div class="cart-collaterals">
                            <div class="cart_totals ">
                                <h2>Total do pedido</h2>
                                <table class="shop_table shop_table_responsive" cellspacing="0">
                                    <tbody>
                                    <tr class="cart-subtotal">
                                        <th>Subtotal</th>
                                        <td data-title="Subtotal"><span class="woocommerce-Price-amount amount"><span
                                                class="woocommerce-Price-currencySymbol">R$ </span><?php echo number_format($total_carrinho, 2, ',', '.')  ?></span></td>
                                    </tr>
                                    <tr class="cart-subtotal">
                                        <th>Taxa de entrega</th>
                                        <td data-title="Subtotal"><span class="woocommerce-Price-amount amount"><span
                                                class="woocommerce-Price-currencySymbol">R$ </span>7,00</span></td>
                                    </tr>
                                    <!-- <tr class="cart-subtotal">
                                        <th>Cupom de desconto</th>
                                        <td data-title="Subtotal"><span class="woocommerce-Price-amount amount"><span
                                                class="woocommerce-Price-currencySymbol">- R$ </span>0,00</span></td>
                                    </tr> -->
                                    <tr class="order-total">
                                        <th>Total</th>
                                        <td data-title="Total"><strong><span
                                                class="woocommerce-Price-amount amount"><span
                                                class="woocommerce-Price-currencySymbol">R$ </span><?php echo number_format($total_carrinho + 7, 2, ',', '.')  ?></span></strong>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="wc-proceed-to-checkout">
                                    <a href="<?php echo base_url() ?>finalizar-pedido"
                                       class="checkout-button button alt wc-forward">
                                        Finalizar pedido</a>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

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