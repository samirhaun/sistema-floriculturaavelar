<div class="banner-wrapper no_background">
    <div class="banner-wrapper-inner">
        <nav class="woocommerce-breadcrumb">
            <a href="<?php echo base_url() ?>">Home</a>
            <i class="fa fa-angle-right"></i>
            <a href="<?php echo base_url() ?>produtos">Produtos</a>
            <i class="fa fa-angle-right"></i>
            <a href="<?php echo base_url() ?>produtos"><?php echo $produto->categoria ?></a>
            <i class="fa fa-angle-right"></i><?php echo $produto->titulo ?>
        </nav>
    </div>
</div>

<div class="single-thumb-vertical main-container shop-page no-sidebar">
    <div class="container">
        <div class="row">
            <div class="main-content col-sm-12">
                <div class="woocommerce-notices-wrapper"></div>
                <div id="product-27"
                     class="post-27 product type-product status-publish has-post-thumbnail product_cat-girl product_cat-new-arrivals product_cat-shoes product_tag-girl product_tag-sock first instock shipping-taxable purchasable product-type-variable has-default-attributes">
                    <div class="main-contain-summary">
                        <div class="contain-left has-gallery">
                            <div class="single-left">
                                <div class="woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-4 images">
                                    <a href="#" class="woocommerce-product-gallery__trigger">
                                        <img draggable="false" class="emoji" alt="🔍"
                                             src="https://s.w.org/images/core/emoji/11/svg/1f50d.svg"></a>
                                    <div class="flex-viewport">
                                        <figure class="woocommerce-product-gallery__wrapper">

                                            <div class="woocommerce-product-gallery__image">
                                                <img alt=""
                                                     src="<?php echo base_url() ?>assets/images/produtos/<?php echo $produto->imagem ?>">
                                            </div>


                                            <?php 

                                            if(!empty($galeria)): 

                                                foreach ($galeria as $foto):

                                            ?>

                                            <div class="woocommerce-product-gallery__image">
                                                <img alt=""
                                                     src="<?php echo base_url() ?>assets/images/produtos/<?php echo $foto->imagens ?>">
                                            </div>

                                            <?php 

                                                endforeach;

                                            endif; 

                                            ?>




                                        </figure>
                                    </div>
                                    <ol class="flex-control-nav flex-control-thumbs">

                                        <li><img
                                                src="<?php echo base_url() ?>assets/images/produtos/<?php echo $produto->imagem ?>"
                                                alt="">
                                        </li>

                                        <?php 

                                        if(!empty($galeria)): 

                                            foreach ($galeria as $foto):

                                        ?>

                                        <li><img
                                                src="<?php echo base_url() ?>assets/images/produtos/<?php echo $foto->imagens ?>"
                                                alt="">
                                        </li>

                                        <?php 

                                            endforeach;

                                        endif; 

                                        ?>




                                    </ol>
                                </div>
                            </div>
                            <div class="summary entry-summary">
                               <!--  <div class="flash">
                                    <span class="onnew"><span class="text">New</span></span></div> -->
                                <h1 class="product_title entry-title"><?php echo $produto->titulo ?></h1>
                                <p class="price"> <span
                                        class="woocommerce-Price-amount amount"><span
                                        class="woocommerce-Price-currencySymbol">R$ </span><?php echo number_format($produto->valor, 2, ',', '.')  ?></span></p>
                              <!--   <p class="stock in-stock">
                                    Availability: <span> In stock</span>
                                </p> -->


                                <p class="stock in-stock">
                                    <?php echo $produto->valor_forma_pagamento ?>
                                </p>
                                
                                <div class="woocommerce-product-details__short-description">
                                    <?php echo $produto->detalhes ?>
                                </div>
                                <form class="variations_form cart">
                                    
                                    <div class="single_variation_wrap">
                                        <div class="woocommerce-variation single_variation"></div>

                                        <?php if($produto->estoque > 0): ?>

                                        <div class="woocommerce-variation-add-to-cart variations_button">
                                            <div class="quantity">
                                                <span class="qty-label">Quantidade:</span>
                                                <div class="control">
                                                    <a class="btn-number qtyminus quantity-minus" href="#">-</a>
                                                    <input type="text" data-step="1" min="0" max="" name="quantity[25]" id="quantidade" value="0" title="Qty" class="input-qty input-text qty text" size="4" pattern="[0-9]*" inputmode="numeric">
                                                    <a class="btn-number qtyplus quantity-plus" href="#">+</a>
                                                </div>
                                            </div>
                                            <button id="adicionar_carrinho" type="button"
                                                    class="single_add_to_cart_button button alt wc-variation-selection-needed">
                                                Comprar
                                            </button>
                                            <input name="add-to-cart" value="27" type="hidden">
                                            <input id="idproduto" value="<?php echo $produto->id ?>" type="hidden">
                                            <!-- <input id="total_estoque" value="0" type="hidden"> -->
                                        </div>

                                        <?php else: ?>

                                            <div class="woocommerce-variation-add-to-cart variations_button woocommerce-variation-add-to-cart-disabled">
                                        
                                            <button type="submit"
                                                    class="single_add_to_cart_button button alt wc-variation-selection-needed">
                                                Produto indisponível
                                            </button>
                                            </div>


                                        <?php endif; ?>


                                    </div>
                                </form>
                                
                                <div class="clear"></div>
                            
                            
                                
                            </div>
                        </div>
                    </div>
                  
                </div>
            </div>
            <div class="col-sm-12 col-xs-12 woo_related-product">
                <div class="block-title">
                    <h2 class="product-grid-title">
                        <span>Outros produtos</span>
                    </h2>
                </div>
                <div class="owl-slick owl-products equal-container better-height"
                     data-slick="{&quot;arrows&quot;:false,&quot;slidesMargin&quot;:30,&quot;dots&quot;:true,&quot;infinite&quot;:false,&quot;slidesToShow&quot;:4}"
                     data-responsive="[{&quot;breakpoint&quot;:480,&quot;settings&quot;:{&quot;slidesToShow&quot;:2,&quot;slidesMargin&quot;:&quot;10&quot;}},{&quot;breakpoint&quot;:768,&quot;settings&quot;:{&quot;slidesToShow&quot;:2,&quot;slidesMargin&quot;:&quot;10&quot;}},{&quot;breakpoint&quot;:992,&quot;settings&quot;:{&quot;slidesToShow&quot;:3,&quot;slidesMargin&quot;:&quot;20&quot;}},{&quot;breakpoint&quot;:1200,&quot;settings&quot;:{&quot;slidesToShow&quot;:3,&quot;slidesMargin&quot;:&quot;20&quot;}},{&quot;breakpoint&quot;:1500,&quot;settings&quot;:{&quot;slidesToShow&quot;:3,&quot;slidesMargin&quot;:&quot;30&quot;}}]">
                    


                     <?php 

                    if(!empty($outros_produtos)): 

                        foreach ($outros_produtos as $outro_produto):

                 ?>

                    <div class="product-item style-01 post-35 product type-product status-publish has-post-thumbnail product_cat-dress product_cat-new-arrivals product_cat-shoes product_tag-boy product_tag-hat product_tag-sock first instock shipping-taxable purchasable product-type-simple  ">
                        <div class="product-inner tooltip-left">
                            <div class="product-thumb">
                                <a class="thumb-link"
                                               href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $outro_produto->nome_tag ?>">
                                                <img class="img-responsive"
                                                     src="<?php echo base_url() ?>assets/images/produtos/<?php echo $outro_produto->imagem ?>"
                                                     alt="<?php echo $outro_produto->titulo ?>" width="270" height="350">
                                            </a>
                                
                                <div class="flash">
                                                
                                                <?php if($outro_produto->porcentagem_desconto > 0): ?>
                                                <span class="onsale"><span class="number">-<?php echo $outro_produto->porcentagem_desconto ?>%</span></span>
                                                <?php endif; ?>

                                                <?php if($outro_produto->novo == 1): ?>
                                                <span class="onnew"><span class="text">Novo</span></span>
                                                <?php endif; ?>

                                </div>
                                <div class="group-button">
                                    
                                    <a href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $outro_produto->nome_tag ?>" class="button yith-wcqv-button">Ver</a>
                                   <!--  <div class="add-to-cart">
                                        <a href="#" class="button product_type_variable add_to_cart_button">Add carrinho</a>
                                    </div> -->
                                </div>
                            </div>
                            <div class="product-info equal-elem">
                                <h3 class="product-name product_title">
                                    <a href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $outro_produto->nome_tag ?>" tabindex="0"><?php echo $outro_produto->titulo ?></a>
                                </h3>
                                <div class="rating-wapper nostar">
                                    
                                    <span class="review"><?php echo $outro_produto->valor_forma_pagamento ?></span></div>
                                <span class="price"> <ins><span
                                        class="woocommerce-Price-amount amount"><span
                                        class="woocommerce-Price-currencySymbol">R$</span> <?php echo number_format($outro_produto->valor, 2, ',', '.')  ?></span></ins></span>
                            </div>
                        </div>
                    </div>


                    <?php 

                   endforeach;
                   endif;


                   ?>


                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">


  $('#adicionar_carrinho').click(function(){

    //VAMOS VERIFICAR SE QTD É MAIOR QUE TOTAL EM ESTOQUE

      var $this = $(this);
      $.ajax({
          url:  '<?php echo base_url('add-carrinho') ?>',
          type: 'POST',
          data: {
              id: $('#idproduto').val(),
              quantidade: $('#quantidade').val()
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

});

</script>