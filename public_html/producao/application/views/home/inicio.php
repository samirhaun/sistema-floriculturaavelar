
            <!-- Slider Arae Start -->
            <div class="slider-area">
                <div class="slider-active-3 owl-carousel slider-hm8 owl-dot-style">


                <?php 

            if(!empty($banners)): 


              foreach ($banners as $banner):

            ?>


                    <!-- Slider Single Item Start -->
                    <div class="slider-height-6 d-flex align-items-start justify-content-start bg-img" style="background-image: url(<?php echo base_url() ?>assets/images/banners/<?php echo $banner->imagem ?>);">
                        <div class="container">
                            <div class="slider-content-1 slider-animated-1 text-left">
                                <!-- <span class="animated">100% NATURAL</span> -->
                                <h1 class="animated">
                                <?php echo $banner->titulo ?>
                                </h1>
                                <p class="animated"><?php echo $banner->texto1 ?></p>

                                <?php if(!empty($banner->link)):  ?>
                                <a href="<?php echo $banner->link ?>" class="shop-btn animated">SAIBA MAIS</a>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                    <!-- Slider Single Item End -->


                    <?php 


                endforeach;

            endif; 

            ?>
                    
                    

                    
                </div>
            </div>
            <!-- Slider Arae End -->
            <!-- Static Area Start -->
           
            <!-- Static Area End -->
            <!-- Best Sells Area Start -->
            <section class="best-sells-area  mtb-60px">
                <div class="container">
                    <!-- Section Title Start -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section-title">
                                <h2>Produtos em destaque</h2>
                                <p>Flores e presentes para todo Norte de Minas</p>
                            </div>
                        </div>
                    </div>
                    <!-- Section Title End -->
                    <!-- Best Sell Slider Carousel Start -->
                    <div class="best-sell-slider owl-carousel owl-nav-style">


                    <?php 

                    if(!empty($produtos_ofertas)): 

                    foreach ($produtos_ofertas as $produto):

                    ?>
                        <!-- Single Item -->
                        <article class="list-product">
                            <div class="img-block">
                                <a href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>" class="thumbnail">
                                    <img class="first-img" src="<?php echo base_url() ?>assets/images/produtos/<?php echo $produto->imagem ?>" alt="<?php echo $produto->titulo ?>" />
                                    <img class="second-img" src="<?php echo base_url() ?>assets/images/produtos/<?php echo $produto->imagem ?>" alt="<?php echo $produto->titulo ?>" />
                                </a>
                            </div>
                            <div class="product-decs">
                                <a class="inner-link" href="<?= base_url() ?>produtos?categorias=<?= $produto->tag_categoria ?>"><span><?php echo $produto->nome_categoria ?></span></a>
                                <h2><a href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>" class="product-link"><?php echo $produto->titulo ?></a></h2>
                                <div class="rating-product">
                                    <!-- <i class="ion-android-star"></i>
                                    <i class="ion-android-star"></i>
                                    <i class="ion-android-star"></i>
                                    <i class="ion-android-star"></i>
                                    <i class="ion-android-star"></i> -->
                                </div>
                                <div class="pricing-meta">
                                    <ul>
                                        <li class="old-price not-cut">R$ <?php echo number_format($produto->valor, 2, ',', '.')  ?></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="add-to-link">
                                <ul>
                                    <li class="cart"><a class="cart-btn" href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>">+ DETALHES </a></li>
                                    <li>
                                        <!-- <a href="#"><i class="ion-android-favorite-outline"></i></a> -->
                                    </li>
                                   
                                </ul>
                            </div>
                        </article>


                        <?php 

                        endforeach;

                        endif; 

                        ?>
                        
                        
                        
                       
                        
                   
                      
                     
                    
                      
                      
                      
                        <!-- Single Item -->
                    </div>
                    <!-- Best Sells Carousel End -->
                </div>
            </section>
            <!-- Best Sells Slider End -->

            
            <!-- Category Area End  -->
            <!-- Hot deal area Start -->
            <section class="hot-deal-area">
                <div class="container">
                    <div class="row">
                  
                        <!-- New Arrivals Area Start -->
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- Section Title -->
                                    <div class="section-title ml-0px mt-res-sx-30px">
                                        <h2>Outros produtos</h2>
                                        <p>Aproveite ofertas e diversos modelos</p>
                                    </div>
                                    <!-- Section Title -->
                                </div>
                            </div>
                            <!-- New Product Slider Start -->
                            <div class="new-product-slider owl-carousel owl-nav-style">


                            <?php 

                              if(!empty($produtos_nao_ofertas)): 

                                foreach ($produtos_nao_ofertas as $produto):

                              ?>

                                <!-- Product Single Item -->
                                <div class="product-inner-item">
                                    <article class="list-product mb-30px">
                                        <div class="img-block">
                                            <a href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>" class="thumbnail">
                                                <img class="first-img" src="<?php echo base_url() ?>assets/images/produtos/<?php echo $produto->imagem ?>" alt="" />
                                                <img class="second-img" src="<?php echo base_url() ?>assets/images/produtos/<?php echo $produto->imagem ?>" alt="" />
                                            </a>
                                        </div>
                                        <div class="product-decs">
                                            <a class="inner-link" href="<?= base_url() ?>produtos?categorias=<?= $produto->tag_categoria ?>"><span><?= $produto->nome_categoria ?></span></a>
                                            <h2><a href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>" class="product-link"><?php echo $produto->titulo ?></a></h2>
                                            <div class="rating-product">
                                                <!-- <i class="ion-android-star"></i>
                                                <i class="ion-android-star"></i>
                                                <i class="ion-android-star"></i>
                                                <i class="ion-android-star"></i>
                                                <i class="ion-android-star"></i> -->
                                            </div>
                                            <div class="pricing-meta">
                                                <ul>
                                                    <li class="old-price not-cut">R$ <?php echo number_format($produto->valor, 2, ',', '.')  ?></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="add-to-link">
                                            <ul>
                                                <li class="cart"><a class="cart-btn" href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>">+ DETALHES </a></li>
                                                <li>
                                                    <a href="#"><i class="ion-android-favorite-outline"></i></a>
                                                </li>
                                               
                                            </ul>
                                        </div>
                                    </article>
                                    
                                </div>


                                <?php 

                                endforeach;

                            endif; 

                            ?>

                                

                                
                               
                                
                                
                            </div>
                            <!-- Product Slider End -->
                        </div>
                    </div>
                </div>
            </section>
            <!-- Hot Deal Area End -->
            <!-- Banner Area Start -->
           