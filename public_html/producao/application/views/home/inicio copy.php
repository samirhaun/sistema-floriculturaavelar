<div class="fullwidth-template">
    <div class="slide-home-04">
        <div class="response-product product-list-owl owl-slick equal-container better-height"
             data-slick="{&quot;arrows&quot;:false,&quot;slidesMargin&quot;:0,&quot;dots&quot;:true,&quot;infinite&quot;:false,&quot;speed&quot;:300,&quot;slidesToShow&quot;:1,&quot;rows&quot;:1}"
             data-responsive="[{&quot;breakpoint&quot;:480,&quot;settings&quot;:{&quot;slidesToShow&quot;:1,&quot;slidesMargin&quot;:&quot;0&quot;}},{&quot;breakpoint&quot;:768,&quot;settings&quot;:{&quot;slidesToShow&quot;:1,&quot;slidesMargin&quot;:&quot;0&quot;}},{&quot;breakpoint&quot;:992,&quot;settings&quot;:{&quot;slidesToShow&quot;:1,&quot;slidesMargin&quot;:&quot;0&quot;}},{&quot;breakpoint&quot;:1200,&quot;settings&quot;:{&quot;slidesToShow&quot;:1,&quot;slidesMargin&quot;:&quot;0&quot;}},{&quot;breakpoint&quot;:1500,&quot;settings&quot;:{&quot;slidesToShow&quot;:1,&quot;slidesMargin&quot;:&quot;0&quot;}}]">

             <?php 

            if(!empty($banners)): 


              foreach ($banners as $banner):

            ?>

            <div class="slide-wrap">

                <?php if(!empty($banner->link)): echo '<a href="'.$banner->link.'">'; endif; ?>
                <img src="<?php echo base_url() ?>assets/images/banners/<?php echo $banner->imagem ?>" alt="<?php echo $banner->titulo ?>">
                <?php if(!empty($banner->link)): echo '</a>'; endif; ?>

                <div class="slide-info">
                    <div class="container">

                    </div>
                </div>
            </div>

            <?php 


                endforeach;

            endif; 

            ?>


        
        
        </div>
    </div>
    
    <div class="section-022">
        <div class="container">
            <div class="azeroth-tabs style-01">
                <div class="tab-head">
                    <ul class="tab-link equal-container " data-loop="1">

                        <li class="active">
                            <a class="loaded" data-ajax="0" data-animate="" data-section="1547652538969-4e9e849f-123a"
                               data-id="330" href="#1547652538969-4e9e849f-123a-5d80aefaa70e2">
                                <span>Ofertas</span>
                            </a>
                        </li>


                        <?php 

                          if(!empty($categorias_produtos)): 

                            $count_categoria = 1;


                            foreach ($categorias_produtos as $categoria_produto):

                          ?>

                          <li class="">
                            <a class="loaded" data-ajax="0" data-animate="" data-section="<?php echo $categoria_produto->nome_tag ?>"
                               data-id="330" href="#<?php echo $categoria_produto->nome_tag ?>-5d80aefaa70e2">
                                <span><?php echo $categoria_produto->nome ?></span>
                            </a>
                         </li>



                         <?php 



                          endforeach;

                          endif; 

                          ?>


                    </ul>
                </div>
                <div class="tab-container">

                    <div class="tab-panel active" id="1547652538969-4e9e849f-123a-5d80aefaa70e2">
                        <div class="azeroth-products style-01">
                            <div class="response-product product-list-grid row auto-clear equal-container better-height ">
                                

                                <?php 

                              if(!empty($produtos_ofertas)): 

                                foreach ($produtos_ofertas as $produto):

                              ?>
                                

                                <div class="product-item recent-product style-01 rows-space-30 col-bg-3 col-lg-3 col-md-4 col-sm-4 col-xs-6 col-ts-6 post-93 product type-product status-publish has-post-thumbnail product_cat-boy product_cat-girl product_cat-new-arrivals product_tag-girl product_tag-sock first instock shipping-taxable purchasable product-type-simple">
                                    
                                    <div class="product-inner tooltip-left">
                                        <div class="product-thumb">
                                            <a class="thumb-link"
                                               href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>">
                                                <img class="img-responsive"
                                                     src="<?php echo base_url() ?>assets/images/produtos/<?php echo $produto->imagem ?>"
                                                     alt="<?php echo $produto->titulo ?>" width="270" height="350">
                                            </a>
                                            
                                            <div class="flash">

                                                <?php if($produto->porcentagem_desconto > 0): ?>
                                                <span class="onsale"><span class="number">-<?php echo $produto->porcentagem_desconto ?>%</span></span>
                                                <?php endif; ?>

                                                <?php if($produto->novo == 1): ?>
                                                <span class="onnew"><span class="text">Novo</span></span>
                                                <?php endif; ?>

                                            </div>


                                            <div class="group-button">
                                            
                                                <a href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>" class="button yith-wcqv-button">Mais detalhes</a>
                                               <!--  <div class="add-to-cart">
                                                    <a href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>"
                                                       class="button product_type_simple add_to_cart_button ajax_add_to_cart">Adicionar ao carrinho</a>
                                                </div> -->
                                            </div>
                                        </div>
                                        <div class="product-info equal-elem">
                                            <h3 class="product-name product_title">
                                                <a href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>"><?php echo $produto->titulo ?></a>
                                            </h3>
                                            <div class="rating-wapper nostar">
                                                <!-- <div class="star-rating"><span style="width:0%">Rated <strong
                                                        class="rating">0</strong> out of 5</span></div> -->
                                                <span class="review"><?php echo $produto->valor_forma_pagamento ?></span></div>
                                            <span class="price">
                                             <ins><span
                                                    class="woocommerce-Price-amount amount"><span
                                                    class="woocommerce-Price-currencySymbol">R$</span><?php echo number_format($produto->valor, 2, ',', '.')  ?></span></ins></span>
                                        </div>
                                    </div>

                                </div>


                                <?php 

                                endforeach;

                            endif; 

                            ?>

                             

                            </div>
                            <!-- OWL Products -->
                        </div>
                    </div>


                    <?php 

                      if(!empty($categorias_produtos)): 


                        foreach ($categorias_produtos as $categoria_produto):

                      ?>


                    <div class="tab-panel" id="<?php echo $categoria_produto->nome_tag ?>-5d80aefaa70e2">
                        <div class="azeroth-products style-01">
                            <div class="response-product product-list-grid row auto-clear equal-container better-height ">


                                <?php 

                              if(!empty($categoria_produto->produtos)): 

                                foreach ($categoria_produto->produtos as $produto):

                              ?>
                                

                                <div class="product-item recent-product style-01 rows-space-30 col-bg-3 col-lg-3 col-md-4 col-sm-4 col-xs-6 col-ts-6 post-93 product type-product status-publish has-post-thumbnail product_cat-boy product_cat-girl product_cat-new-arrivals product_tag-girl product_tag-sock first instock shipping-taxable purchasable product-type-simple">
                                    
                                    <div class="product-inner tooltip-left">
                                        <div class="product-thumb">
                                            <a class="thumb-link"
                                               href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>">
                                                <img class="img-responsive"
                                                     src="<?php echo base_url() ?>assets/images/produtos/<?php echo $produto->imagem ?>"
                                                     alt="<?php echo $produto->titulo ?>" width="270" height="350">
                                            </a>
                                            
                                            <div class="flash">

                                                <?php if($produto->porcentagem_desconto > 0): ?>
                                                <span class="onsale"><span class="number">-<?php echo $produto->porcentagem_desconto ?>%</span></span>
                                                <?php endif; ?>

                                                <?php if($produto->novo == 1): ?>
                                                <span class="onnew"><span class="text">Novo</span></span>
                                                <?php endif; ?>

                                            </div>


                                            <div class="group-button">
                                            
                                                <a href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>" class="button yith-wcqv-button">Mais detalhes</a>
                                                <div class="add-to-cart">
                                                    <a href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>"
                                                       class="button product_type_simple add_to_cart_button ajax_add_to_cart">Adicionar ao carrinho</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-info equal-elem">
                                            <h3 class="product-name product_title">
                                                <a href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>"><?php echo $produto->titulo ?></a>
                                            </h3>
                                            <div class="rating-wapper nostar">
                                                <!-- <div class="star-rating"><span style="width:0%">Rated <strong
                                                        class="rating">0</strong> out of 5</span></div> -->
                                                <span class="review"><?php echo $produto->valor_forma_pagamento ?></span></div>
                                            <span class="price">
                                             <ins><span
                                                    class="woocommerce-Price-amount amount"><span
                                                    class="woocommerce-Price-currencySymbol">R$</span><?php echo number_format($produto->valor, 2, ',', '.')  ?></span></ins></span>
                                        </div>
                                    </div>

                                </div>


                                <?php 

                                endforeach;

                            endif; 

                            ?>

                             

                            </div>
                            <!-- OWL Products -->
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
    <div class="section-023">
        <div class="azeroth-banner style-11 left-center">
            <div class="banner-inner">
                <figure class="banner-thumb">
                    <img src="<?php echo base_url() ?>assets/images/blocos/<?php echo $blocos_estaticos[0]->imagem_1; ?>"
                         class="attachment-full size-full" alt=""></figure>
                <div class="banner-info container">
                    <div class="banner-content">
                        <div class="title-wrap">
                            <div class="banner-label">
                                <?php echo $blocos_estaticos[0]->titulo_1; ?>
                            </div>
                            <h6 class="title">
                                <?php echo $blocos_estaticos[0]->texto_1; ?> </h6>
                        </div>
                        <div class="button-wrap">
                            <div class="subtitle">
                                <?php echo $blocos_estaticos[0]->texto_2; ?>
                            </div>
                            <a class="button" target=" _blank" href="#"><span>Ver ofertas</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
  

    <div class="section-001" style="padding-top: 20px;">
        <div class="container">
            <div class="azeroth-heading style-01">
                <div class="heading-inner">
                    <h3 class="title">
                        Blog Gury</h3>
                    <div class="subtitle">
                        Fique ligado e atento a dicas, novidades de mercado e  informativos para você
                    </div>
                </div>
            </div>
            <div class="azeroth-blog style-01">
                <div class="blog-list-owl owl-slick equal-container better-height"
                     data-slick="{&quot;arrows&quot;:false,&quot;slidesMargin&quot;:30,&quot;dots&quot;:true,&quot;infinite&quot;:false,&quot;speed&quot;:300,&quot;slidesToShow&quot;:3,&quot;rows&quot;:1}"
                     data-responsive="[{&quot;breakpoint&quot;:480,&quot;settings&quot;:{&quot;slidesToShow&quot;:1,&quot;slidesMargin&quot;:&quot;10&quot;}},{&quot;breakpoint&quot;:768,&quot;settings&quot;:{&quot;slidesToShow&quot;:1,&quot;slidesMargin&quot;:&quot;10&quot;}},{&quot;breakpoint&quot;:992,&quot;settings&quot;:{&quot;slidesToShow&quot;:2,&quot;slidesMargin&quot;:&quot;20&quot;}},{&quot;breakpoint&quot;:1200,&quot;settings&quot;:{&quot;slidesToShow&quot;:2,&quot;slidesMargin&quot;:&quot;20&quot;}},{&quot;breakpoint&quot;:1500,&quot;settings&quot;:{&quot;slidesToShow&quot;:3,&quot;slidesMargin&quot;:&quot;30&quot;}}]">
                    


                     <?php 

                      if(!empty($ultimas_noticias)): 

                        foreach ($ultimas_noticias as $noticia):

                      ?>

                    <article
                            class="post-item post-grid rows-space-0 post-195 post type-post status-publish format-standard has-post-thumbnail hentry category-boy category-girl category-life-style tag-boy tag-life-style">
                        <div class="post-inner blog-grid">
                            <div class="post-thumb">
                                <a href="<?php echo base_url() ?>detalhes-noticia?tag=<?php echo $noticia->nome_url ?>" tabindex="0">
                                    <img src="<?php echo base_url() ?>assets/images/noticias/<?php echo $noticia->imagem ?>"
                                         class="img-responsive attachment-370x330 size-370x330" alt=""
                                         width="370" > </a>
                                <!-- <a class="datebox" href="#" tabindex="0">
                                    <span>19</span>
                                    <span>Nov</span>
                                </a> -->
                            </div>
                            <div class="post-content">
                                <div class="post-meta">
                                   
                                </div>
                                <div class="post-info equal-elem"  >
                                    <h2 class="post-title"><a
                                            href="<?php echo base_url() ?>detalhes-noticia?tag=<?php echo $noticia->nome_url ?>"
                                            tabindex="0"><?php echo $noticia->titulo ?></a></h2>
                                   <?php echo $noticia->texto_breve ?>
                                </div>
                            </div>
                        </div>
                    </article>


                    <?php 

                        endforeach;

                    endif; 

                    ?>

                    
                    
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>