
            <!-- Breadcrumb Area start -->
            <section class="breadcrumb-area">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="breadcrumb-content">
                                <h1 class="breadcrumb-hrading">Detalhes produto</h1>
                                <ul class="breadcrumb-links">
                                    <li><a href="<?php echo base_url() ?>">Home</a></li>
                                    <li><a href="<?php echo base_url() ?>produtos">Produtos</a></li>
                                    <li><?php echo $produto->titulo ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Breadcrumb Area End -->
            <!-- Shop details Area start -->
            <section class="product-details-area mtb-60px">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <div class="product-details-img product-details-tab">
                                <div class="zoompro-wrap zoompro-2">
                                    <div class="zoompro-border zoompro-span" style="text-align: center;padding-bottom: 20px;">
                                        <img class="" width="360" src="<?php echo base_url() ?>assets/images/produtos/<?php echo $produto->imagem ?>" data-zoom-image="<?php echo base_url() ?>assets/images/produtos/<?php echo $produto->imagem ?>" alt="" />
                                    </div>
                                </div>
                                <div id="gallery" class="product-dec-slider-2">

                                
                                <?php 

                                if(!empty($galeria)): 

                                    foreach ($galeria as $foto):

                                ?>

                                <a data-image="<?php echo base_url() ?>assets/images/produtos/<?php echo $foto->imagens ?>" data-zoom-image="<?php echo base_url() ?>assets/images/produtos/<?php echo $foto->imagens ?>">
                                        <img src="<?php echo base_url() ?>assets/images/produtos/<?php echo $foto->imagens ?>" alt="" />
                                    </a>

                                <?php 

                                    endforeach;

                                endif; 

                                ?>
                                                
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <div class="product-details-content">
                                <h2><?php echo $produto->titulo ?></h2>
                                <!-- <p class="reference">Reference:<span> demo_17</span></p> -->
                                <div class="pro-details-rating-wrap">
                                    <!-- <div class="rating-product">
                                        <i class="ion-android-star"></i>
                                        <i class="ion-android-star"></i>
                                        <i class="ion-android-star"></i>
                                        <i class="ion-android-star"></i>
                                        <i class="ion-android-star"></i>
                                    </div> -->
                                    <span class=""><a style="text-decoration: none;color:black" class="reviews" href="<?= base_url() ?>produtos?categorias=<?= $produto->categoria_nome_tag ?>"><?php echo $produto->categoria ?></a></span>
                                </div>
                                <div class="pricing-meta">
                                    <ul>
                                        <li class="old-price not-cut">R$ <?php echo number_format($produto->valor, 2, ',', '.')  ?></li>
                                    </ul>
                                </div>
                                <p><?php echo $produto->detalhes ?></p>
                                <p>
                                    <br>
                                    *A imagem apresentada deste presente é similar ao produto que será oferecido, podendo haver variações em suas características.
                                </p>
                                <div class="pro-details-list">
                                    <!-- <ul>
                                        <li>- 0.5 mm Dail</li>
                                        <li>- Inspired vector icons</li>
                                        <li>- Very modern style</li>
                                    </ul> -->
                                </div>

                                <?php if($produto->estoque > 0): ?>
                               <input id="idproduto" value="<?php echo $produto->id ?>" type="hidden">
                                <div class="pro-details-quality mt-0px">
                                    <div class="cart-plus-minus">
                                       <div class="dec qtybutton">-</div>
                                        <input class="cart-plus-minus-box" type="text" name="quantidade" id="quantidade" value="1" />
                                       <div class="inc qtybutton">+</div>
                                    </div>
                                    <div class="pro-details-cart btn-hover">
                                        <a href="#"  id="adicionar_carrinho" > + Adicionar ao carrinho</a>
                                    </div>
                                </div>
                                <?php else: ?>
                                    <div class="pro-details-quality mt-0px">
                                    <div class="pro-details-cart btn-hover">
                                        <a href="javascript:void(0)" disabled="disabled"> Produto indisponível</a>
                                    </div>
                                  </div>
                                <?php endif; ?>


                                <!-- <div class="pro-details-wish-com">
                                    <div class="pro-details-wishlist">
                                        <a href="#"><i class="ion-android-favorite-outline"></i>Add to wishlist</a>
                                    </div>
                                    <div class="pro-details-compare">
                                        <a href="#"><i class="ion-ios-shuffle-strong"></i>Add to compare</a>
                                    </div>
                                </div>
                                <div class="pro-details-social-info">
                                    <span>Share</span>
                                    <div class="social-info">
                                        <ul>
                                            <li>
                                                <a href="#"><i class="ion-social-facebook"></i></a>
                                            </li>
                                            <li>
                                                <a href="#"><i class="ion-social-twitter"></i></a>
                                            </li>
                                            <li>
                                                <a href="#"><i class="ion-social-google"></i></a>
                                            </li>
                                            <li>
                                                <a href="#"><i class="ion-social-instagram"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div> -->
                                <div class="pro-details-policy">
                                    <ul>
                                        <li><img src="<?php echo base_url() ?>assets/images/icons/static-icons-1.png" alt="" /><span>Entregamos em Montes Claros e Norte de Minas</span></li>
                                        <!-- <li><img src="<?php echo base_url() ?>assets/images/icons/policy-2.png" alt="" /><span>Delivery Policy (Edit With Customer Reassurance Module)</span></li>
                                        <li><img src="<?php echo base_url() ?>assets/images/icons/policy-3.png" alt="" /><span>Return Policy (Edit With Customer Reassurance Module)</span></li> -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Shop details Area End -->
            <!-- product details description area start -->
         
            <!-- product details description area end -->
            <!-- Recent Add Product Area Start -->
            <section class="recent-add-area">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Section Title -->
                            <div class="section-title">
                                <h2>Produtos similares</h2>
                                <p>Diversas opções para você escolher</p>
                            </div>
                            <!-- Section Title -->
                        </div>
                    </div>
                    <!-- Recent Product slider Start -->
                    <div class="recent-product-slider owl-carousel owl-nav-style">


                    <?php 

                    if(!empty($outros_produtos)): 

                        foreach ($outros_produtos as $outro_produto):

                 ?>

                        <!-- Single Item -->
                        <article class="list-product">
                                                    <div class="img-block">
                                                        <a href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $outro_produto->nome_tag ?>" class="thumbnail">
                                                            <img class="first-img" src="<?php echo base_url() ?>assets/images/produtos/<?php echo $outro_produto->imagem ?>" alt="" />
                                                            <img class="second-img" src="<?php echo base_url() ?>assets/images/produtos/<?php echo $outro_produto->imagem ?>" alt="" />
                                                        </a>
                                                    </div>
                                                    <div class="product-decs">
                                                        <a class="inner-link" href="<?= base_url() ?>produtos?categorias=<?= $outro_produto->categoria_nome_tag ?>"><span><?= $outro_produto->categoria ?></span></a>
                                                        <h2><a href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $outro_produto->nome_tag ?>" class="product-link"><?php echo $outro_produto->titulo ?></a></h2>
                                                        <div class="rating-product">
                                                            <!-- <i class="ion-android-star"></i>
                                                            <i class="ion-android-star"></i>
                                                            <i class="ion-android-star"></i>
                                                            <i class="ion-android-star"></i>
                                                            <i class="ion-android-star"></i> -->
                                                        </div>
                                                        <div class="pricing-meta">
                                                            <ul>
                                                                <li class="old-price not-cut">R$ <?php echo number_format($outro_produto->valor, 2, ',', '.')  ?></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="add-to-link">
                                                        <ul>
                                                            <li class="cart"><a class="cart-btn" href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $outro_produto->nome_tag ?>">+ DETALHES </a></li>
                                                           
                                                        
                                                        </ul>
                                                    </div>
                                                </article>
                        <!-- Single Item -->
                        
                        <?php 

                   endforeach;
                   endif;


                   ?>

                                                

                                                

                                                

                                                

                                                
                    </div>
                    <!-- Recent product slider end -->
                </div>
            </section>
            <!-- Recent product area end -->
            <!-- Recent Add Product Area Start -->
        
            <!-- Recent product area end -->

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
            