<div class="main-container right-sidebar has-sidebar">
    <!-- POST LAYOUT -->
    <div class="container">
        <div class="row">
            <div class="main-content col-lg-9 col-md-8 col-sm-12 col-xs-12">
                <article
                        class="post-item post-single post-195 post type-post status-publish format-standard has-post-thumbnail hentry category-boy category-girl category-life-style tag-boy tag-life-style">
                    <div class="single-post-thumb">
                        <div class="post-thumb">
                            <img src="<?php echo base_url() ?>assets/images/noticias/<?php echo $noticia->imagem ?>"
                                 class="attachment-full size-full wp-post-image" alt=""></div>
                    </div>
                    <div class="single-post-info">
                        <h2 class="post-title"><a href="#"><?php echo $noticia->titulo ?></a></h2>
                        <div class="post-meta">
                            <div class="date">
                                <a href="#"><?php echo date("d/m/Y", strtotime($noticia->data))  ?> </a>
                            </div>
                         
                        </div>
                    </div>
                    <div class="post-content">
                        <div id="output" style="text-align: justify;">
                            
                            <?php echo $noticia->texto ?>

                        </div>
                       
                    </div>
                    
                    
                </article>
                
            </div>
            <div class="sidebar azeroth_sidebar col-lg-3 col-md-4 col-sm-12 col-xs-12">

                <div id="widget-area" class="widget-area sidebar-blog">
                

                    <div id="widget_azeroth_post-2" class="widget widget-azeroth-post"><h2 class="widgettitle">Últimas Ofertas<span class="arrow"></span></h2>
                        <div class="azeroth-posts">


                            <?php 

                              if(!empty($ultimas_ofertas)): 

                                foreach ($ultimas_ofertas as $produto):

                              ?>
                                   
                            <article
                                    class="post-195 post type-post status-publish format-standard has-post-thumbnail hentry category-boy category-girl category-life-style tag-boy tag-life-style">
                                <div class="post-item-inner">
                                    <div class="post-thumb">
                                        <a href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>">
                                            <img src="<?php echo base_url() ?>assets/images/produtos/<?php echo $produto->imagem ?>"
                                                 class="img-responsive attachment-83x83 size-83x83" alt="" width="83"
                                                 > </a>
                                    </div>
                                    <div class="post-info">
                                        <div class="block-title">
                                            <h2 class="post-title"><a
                                                    href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>"><?php echo $produto->titulo ?></a></h2>
                                        </div>
                                        <div class="date">R$ </span><?php echo number_format($produto->valor, 2, ',', '.')  ?></div>
                                    </div>
                                </div>
                            </article>

                            <?php 

                        endforeach;

                    endif; 

                    ?>
                            
                            
                            
                        </div>
                    </div>

                    <div id="tag_cloud-3" class="widget widget_tag_cloud"><h2 class="widgettitle">Categorias<span
                            class="arrow"></span></h2>
                        <div class="tagcloud">

                            <?php 

                              if(!empty($categorias_produtos)): 

                                foreach ($categorias_produtos as $categoria):

                              ?>

                             <a href="<?php echo base_url() ?>produtos?categorias=<?php echo $categoria->nome_tag ?>;" class="tag-cloud-link tag-link-46 tag-link-position-1" aria-label=""><?php echo $categoria->nome ?></a>


                            <?php 

                                endforeach;

                            endif; 

                            ?>

                            

                           </div>
                    </div>


                    <div id="widget_azeroth_socials-2" class="widget widget-azeroth-socials">
                        <h2 class="widgettitle">Na rede<span class="arrow"></span></h2>
                        <div class="content-socials">
                            <ul class="socials-list">
                                <li>
                                    <a href="https://facebook.com" target="_blank">
                                        <span class="fa fa-facebook"></span>
                                        Facebook </a>
                                </li>
                                <li>
                                    <a href="https://www.instagram.com" target="_blank">
                                        <span class="fa fa-instagram"></span>
                                        Instagram </a>
                                </li>
                                <li>
                                    <a href="https://twitter.com" target="_blank">
                                        <span class="fa fa-twitter"></span>
                                        Twitter </a>
                                </li>
                                <li>
                                    <a href="https://www.pinterest.com" target="_blank">
                                        <span class="fa fa-pinterest-p"></span>
                                        Pinterest </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    
                </div><!-- .widget-area -->
            </div>
        </div>
    </div>
</div>