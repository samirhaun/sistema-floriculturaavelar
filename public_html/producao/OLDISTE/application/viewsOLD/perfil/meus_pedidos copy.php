<style type="text/css">
    .shop_table .product-thumbnail img {
        max-width: 40px;
    }
</style>

<div class="single-thumb-vertical main-container shop-page no-sidebar">
    <div class="container">
        <div class="row">
            <div class="main-content col-sm-12">
                <div class="woocommerce-notices-wrapper"></div>
                <div id="product-27"
                     class="post-27 product type-product status-publish has-post-thumbnail product_cat-girl product_cat-new-arrivals product_cat-shoes product_tag-girl product_tag-sock first instock shipping-taxable purchasable product-type-variable has-default-attributes">
                    <div class="main-contain-summary">
                        <div class="contain-left has-gallery">

                            <div class="col-sm-12">

                                <div role="form" class="wpcf7">
                                        <h2>Meus pedidos</h2>
                                </div>
                            </div>

                            <table class="shop_table cart wishlist_table" data-pagination="no" data-per-page="5"
                               data-page="1" data-id="" data-token="">
                            <thead>
                            <tr>
                                <th class="product-thumbnail"></th>
                                <th class="product-name">
                                    <span class="nobr">Descrição</span>
                                </th>
                                <th class="product-price">
                    <span class="nobr">
                        Valor total                   </span>
                                </th>
                                <th class="product-stock-status">
                    <span class="nobr">
                        Status                    </span>
                                </th>
                                <th class="product-add-to-cart"> Data</th>
                            </tr>
                            </thead>
                            <tbody>


                            <?php 

                            if(!empty($pedidos)): 


                              foreach ($pedidos as $pedido):

                            ?>

                            <tr id="yith-wcwl-row-29" data-row-id="29">

                                <td class="product-thumbnail">


                                    <?php 

                                    if(!empty($pedido->produtos)): 

                                        foreach ($pedido->produtos as $produto):

                                    ?>

                                    <img src="<?php echo base_url() ?>assets/images/produtos/<?php echo $produto->imagem ?>"
                                             class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt=""
                                             width="600" height="778"> 

                                             <br>


                                   
                                    <?php 

                                        endforeach;

                                    endif; 

                                    ?>
                            
                                        
                                </td>
                                <td class="product-name">

                                    <?php 

                                    if(!empty($pedido->produtos)): 

                                        foreach ($pedido->produtos as $produto):

                                    ?>

                                   <?php echo $produto->quantidade ?> - <?php echo character_limiter($produto->titulo, 20);  ?><br>
                                   
                                    <?php 

                                        endforeach;

                                    endif; 

                                    ?>


                                </td>
                                <td class="product-price">
                                    <span class="woocommerce-Price-amount amount"><span
                                            class="woocommerce-Price-currencySymbol">R$ </span><?php echo number_format($pedido->valor, 2, ',', '.')  ?></span></td>
                                <td class="product-stock-status" style="text-align: center;">
                                    
                                    <?php 

                                    if($pedido->status_pedido == 0): echo '<span class="wishlist-in-stock">Aguardando confirmação</span>';
                                    elseif($pedido->status_pedido == 1): 
                                        echo '<span class="wishlist-in-stock icon-confirmado"><i class="fa fa-thumbs-o-up icon-status" aria-hidden="true"></i><br>Confirmado</span>';
                                    elseif($pedido->status_pedido == 2): 
                                        echo '<span class="wishlist-in-stock icon-confirmado"><span class="flaticon-truck icon-status"></span></i><br> Saiu para entrega</span>';
                                    elseif($pedido->status_pedido == 3): 
                                        echo '<span class="wishlist-in-stock icon-concluido"><i class="fa fa-smile-o icon-status" aria-hidden="true"></i><br>Pedido concluído</span>';
                                    elseif($pedido->status_pedido == 4): 
                                        echo '<span class="wishlist-in-stock icon-cancelado"><i class="fa fa-frown-o icon-status" aria-hidden="true"></i><br>Pedido cancelado pela loja</span>';
                                    elseif($pedido->status_pedido == 5): 
                                        echo '<span class="wishlist-in-stock icon-cancelado"><i class="fa fa-frown-o icon-status" aria-hidden="true"></i><br>Pedido cancelado pelo cliente</span>';
                                    endif;

                                    ?>
                                    
                                    

                                </td>
                                <td class="product-add-to-cart">
                                    <!-- Date added -->
                                    <!-- Add to cart button -->
                                    <a href="#"
                                       data-quantity="1"
                                       class="button product_type_simple add_to_cart_button ajax_add_to_cart add_to_cart button alt"
                                       data-product_id="29" data-product_sku="003D754"
                                       aria-label="Add “Short Sleeve Loose” to your cart" rel="nofollow"> <?php echo date("d/m/Y", strtotime($pedido->data_pedido))  ?></a>
                                    <!-- Change wishlist -->
                                    <!-- Remove from wishlist -->
                                </td>
                            </tr>


                            <?php 


                                endforeach;

                            endif; 

                            ?>


                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="6">
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    

                        </div>

                        <div class="single-product-policy">
                            <div class="azeroth-iconbox style-01">
                                <a href="<?php echo base_url('meus-pedidos') ?>">
                                <div class="iconbox-inner">
                                    <div class="icon">
                                        <span class="flaticon-truck"></span>
                                        <span class="flaticon-truck"></span>
                                    </div>
                                    <div class="content">
                                        <h4 class="title">Meus pedidos</h4>
                                        <div class="desc">Pedidos concluídos e em andamento.
                                        </div>
                                    </div>
                                </div>
                              </a>
                            </div>
                            
                            <div class="azeroth-iconbox style-01">
                                <a href="<?php echo base_url('meus-dados') ?>">
                                <div class="iconbox-inner">
                                    <div class="icon">
                                        <span class="flaticon-refresh-left-arrow"></span>
                                        <span class="flaticon-refresh-left-arrow"></span>
                                    </div>
                                    <div class="content">
                                        <h4 class="title">Minha conta</h4>
                                        <div class="desc">Atualize informações e endereço de entrega.
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>

                        </div>
                    </div>
                    
                </div>
            </div>
          
            
        </div>
    </div>
</div>

<script type="text/javascript">
      $(document).ready(function(){

         $('[name=telefone]').mask('(00) 0 0000-0000');
         $('[name=cpf]').mask('000.000.000-00', {reverse: true});
         $('[name=cep]').mask('00000-000');



         //CONFIRMAÇÃO DE CADASTRO
        <?php if (isset($_GET['efetuado'])):  ?>

        swal("Pedido realiado com sucesso!", "Em até 25 minutos o status do seu pedido será atualizado e você poderá acompanhá-lo pelo site. Obrigado por escolher Gury!", "success")

        <?php endif; ?>


         function limpa_formulário_cep() {
                    // Limpa valores do formulário de cep.
                    $("[name=rua]").val("");
                    $("[name=bairro]").val("");
                    $("[name=cidade]").val("");
                    $("[name=estado]").val("");
                    $("#ibge").val("");
                }

                //Quando o campo cep perde o foco.
                $("[name=cep]").blur(function() {

                    //Nova variável "cep" somente com dígitos.
                    var cep = $(this).val().replace(/\D/g, '');

                    //Verifica se campo cep possui valor informado.
                    if (cep != "") {

                        //Expressão regular para validar o CEP.
                        var validacep = /^[0-9]{8}$/;

                        //Valida o formato do CEP.
                        if(validacep.test(cep)) {

                            //Preenche os campos com "..." enquanto consulta webservice.
                            $("[name=rua]").val("");
                            $("[name=bairro]").val("");
                            $("[name=cidade]").val("");
                            $("[name=estado]").val("");
                            $("[name=ibge]").val("");

                            //Consulta o webservice viacep.com.br/
                            $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                                if (!("erro" in dados)) {
                                    //Atualiza os campos com os valores da consulta.
                                    $("[name=rua]").val(dados.logradouro);
                                    $("[name=bairro]").val(dados.bairro);
                                    $("[name=cidade]").val(dados.localidade);
                                    $("[name=estado]").val(dados.uf);
                                } //end if.
                                else {
                                    //CEP pesquisado não foi encontrado.
                                    limpa_formulário_cep();
                                    swal("CEP não encontrado.");
                                }
                            });
                        } //end if.
                        else {
                            //cep é inválido.
                            limpa_formulário_cep();
                            swal("Formato de CEP inválido.");
                        }
                    } //end if.
                    else {
                        //cep sem valor, limpa formulário.
                        limpa_formulário_cep();
                    }
                });


      });
  </script>