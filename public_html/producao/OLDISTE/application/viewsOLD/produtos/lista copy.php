<div class="main-container shop-page left-sidebar">
    <div class="container">
        <div class="row">
            <div class="main-content col-lg-9 col-md-8 col-sm-8 col-xs-12 has-sidebar">
                <div class="shop-control shop-before-control">
                    
                    <form class="woocommerce-ordering" method="get">
                        <select title="product_cat" name="filtro_classificacao" class="orderby" onchange="filtrar_classificacao()">
                            <!-- <option value="">Relevância</option> -->
                            <option <?php if(!empty($filtro_classificacao_selecionada) && $filtro_classificacao_selecionada == 'mais_recente') echo 'selected="selected"' ?> value="mais_recente">Mais recentes</option>
                           <!--  <option <?php if(!empty($filtro_classificacao_selecionada) && $filtro_classificacao_selecionada == 'mais_vendidos') echo 'selected="selected"' ?> value="mais_vendidos">Mais vendidos</option> -->
                            <option <?php if(!empty($filtro_classificacao_selecionada) && $filtro_classificacao_selecionada == 'menor_preco') echo 'selected="selected"' ?> value="menor_preco">Menor preço</option>
                            <option <?php if(!empty($filtro_classificacao_selecionada) && $filtro_classificacao_selecionada == 'maior_preco') echo 'selected="selected"' ?> value="maior_preco">Maior preço</option>
                            
                        </select>
                    </form>
                    
                </div>
                <div class="row auto-clear equal-container better-height azeroth-products">
                    <ul class="products columns-3">

                        <?php 

                        if(!empty($produtos)): 

                          foreach ($produtos as $produto):

                        ?>


                        <li class="product-item wow fadeInUp product-item rows-space-30 col-bg-4 col-lg-4 col-md-6 col-sm-6 col-xs-6 col-ts-6 style-01 post-23 product type-product status-publish has-post-thumbnail product_cat-dress product_cat-shoes product_cat-t-shirt product_tag-hat first instock shipping-taxable purchasable product-type-variable has-default-attributes"
                            data-wow-duration="1s" data-wow-delay="0ms" data-wow="fadeInUp">
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
                                                <<!-- div class="add-to-cart">
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
                        </li>


                        <?php 

                       endforeach;
                       endif;


                       ?>

                        
                    </ul>
                </div>
                <div class="shop-control shop-after-control">
                    <nav class="woocommerce-pagination">

                        <?php 

                            $qtd_prod_pagina = 9;

                            $total_produtos = $total_produtos_paginacao;
                            $total_paginas = ceil($total_produtos / $qtd_prod_pagina);
                         ?>

                         <?php 

                         for ($i=0; $i < $total_paginas; $i++): 

                          $ofset = $i * $qtd_prod_pagina;

                         ?>




                         <?php if($filtro_paginacao == $qtd_prod_pagina.'_'.$ofset){ ?>
                            <span class="page-numbers current"><?php echo  $i + 1 ?></span>
                         <?php }else{ ?>

                         <a class="page-numbers" href="<?php echo base_url() ?>produtos?categorias=<?php echo $categorias_selecionadas.'&marcas='.$marcas_selecionadas.'&preco='.$preco_selecionado.'&desconto='.$desconto_selecionado.'&filtro_classificacao='.$filtro_classificacao_selecionada.'&paginacao='.$qtd_prod_pagina.'_'.$ofset ?>"

                          > <?php echo  $i + 1 ?> </a>

                         <?php } ?>

                         



                         <?php endfor; ?>


                        <!-- <span class="page-numbers current">1</span>
                        <a class="page-numbers" href="">2</a>-->
                        <!-- <a class="page-numbers" href="#">></a>  -->

                    </nav>

                    <?php $most_result =  explode('_', $filtro_paginacao); ?>

                    <p class="woocommerce-result-count">Mostrando 
                                                      <?php if($most_result[1] == 0){ echo '1';}else{echo $most_result[1];} ?>
                                                      –
                                                      <?php echo $most_result[1] + $qtd_prod_pagina  ?> 
                                                      de 
                                                      <?php echo $total_produtos ?> 
                                                      resultados
                    </p>

                </div>
            </div>
            <div class="sidebar col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <div id="widget-area" class="widget-area shop-sidebar">
                    <div id="woocommerce_product_search-2" class="widget woocommerce widget_product_search">
                        <form class="woocommerce-product-search" id="form_pesquisa" 
                        action="">

                            <input id="woocommerce-product-search-field-0" class="search-field"
                                   placeholder="Buscar produto…" value="" name="pesquisa_pg_produtos" type="search">
                            <button type="button" onclick="submit_pesquisa()" value="Pesquisar">Pesquisar</button>

                        </form>
                    </div>
                    
                
                    <div id="woocommerce_layered_nav-6"
                         class="widget woocommerce widget_layered_nav woocommerce-widget-layered-nav"><h2
                            class="widgettitle">CATEGORIAS<span class="arrow"></span></h2>
                        <ul class="woocommerce-widget-layered-nav-list">

                            <?php 

                            if(!empty($categorias_produtos)): 

                              foreach ($categorias_produtos as $categoria):

                                //VAMOS VERIFICAR SE A CATEGORIA ESTÁ SELECIONADA
                                $selecionadas = explode(';', $categorias_selecionadas);

                                $select_categoria = 0;

                                if(!empty($selecionadas)){
                                    foreach ($selecionadas as $key => $value) {
                                            
                                            if(!empty($value) && $value == $categoria->nome_tag){
                                                $select_categoria = 1;
                                            }


                                    }
                                }
                                

                                //CASO ESTEJA SELECIONADA COLOCAR O BACKGROUND
                                if($select_categoria == 1){
                                    $class_selecionada = 'categoria_selecionada';
                                }else{
                                    $class_selecionada = '';
                                }

                            ?>

                            <li class="woocommerce-widget-layered-nav-list__item wc-layered-nav-term <?php echo $class_selecionada ?>">
                                <a rel="nofollow" 
                                href="<?php echo base_url() ?>produtos?categorias=<?php echo $categorias_selecionadas.$categoria->nome_tag.';'.'&marcas='.$marcas_selecionadas.'&preco='.$preco_selecionado.'&desconto='.$desconto_selecionado.'&filtro_classificacao='.$filtro_classificacao_selecionada ?>">

                                <?php echo $categoria->nome ?>   
                                </a>
                                </li>

                            <?php 

                           endforeach;
                           endif;


                           ?>


                        </ul>
                    </div>


                    <div id="woocommerce_layered_nav-6"
                         class="widget woocommerce widget_layered_nav woocommerce-widget-layered-nav"><h2
                            class="widgettitle">MARCAS<span class="arrow"></span></h2>
                        <ul class="woocommerce-widget-layered-nav-list">

                            <?php 

                            if(!empty($marcas_produtos)): 

                              foreach ($marcas_produtos as $marca):

                                //VAMOS VERIFICAR SE A CATEGORIA ESTÁ SELECIONADA
                                $selecionadas = explode(';', $marcas_selecionadas);

                                $select_marca = 0;

                                if(!empty($selecionadas)){
                                    foreach ($selecionadas as $key => $value) {
                                            
                                            if(!empty($value) && $value == $marca->nome_tag){
                                                $select_marca = 1;
                                            }


                                    }
                                }
                                

                                //CASO ESTEJA SELECIONADA COLOCAR O BACKGROUND
                                if($select_marca == 1){
                                    $class_selecionada = 'categoria_selecionada';
                                }else{
                                    $class_selecionada = '';
                                }

                            ?>

                            <li class="woocommerce-widget-layered-nav-list__item wc-layered-nav-term <?php echo $class_selecionada ?>">
                                <a rel="nofollow" 
                                href="<?php echo base_url() ?>produtos?categorias=<?php echo $categorias_selecionadas.'&marcas='.$marcas_selecionadas.$marca->nome_tag.';'.'&preco='.$preco_selecionado.'&desconto='.$desconto_selecionado.'&filtro_classificacao='.$filtro_classificacao_selecionada ?>">

                                <?php echo $marca->titulo ?>   
                                </a>
                                <!-- <span class="count">
                                (<?php echo $marca->total_produtos ?>)
                                </span> -->
                                </li>

                            <?php 

                           endforeach;
                           endif;


                           ?>


                        </ul>
                    </div>


                
                    <div id="woocommerce_layered_nav-6"
                         class="widget woocommerce widget_layered_nav woocommerce-widget-layered-nav"><h2
                            class="widgettitle">PREÇO<span class="arrow"></span></h2>
                        <ul class="woocommerce-widget-layered-nav-list">


                            <?php 

                            if(!empty($precos_produtos)): 

                              foreach ($precos_produtos as $preco):

                                //VAMOS VERIFICAR SE A CATEGORIA ESTÁ SELECIONADA
                                $selecionadas = explode(';', $preco_selecionado);

                                $select_preco = 0;

                                if(!empty($selecionadas)){
                                    foreach ($selecionadas as $key => $value) {
                                            
                                            if(!empty($value) && $value == $preco['valor']){
                                                $select_preco = 1;
                                            }


                                    }
                                }
                                

                                //CASO ESTEJA SELECIONADA COLOCAR O BACKGROUND
                                if($select_preco == 1){
                                    $class_selecionada = 'categoria_selecionada';
                                }else{
                                    $class_selecionada = '';
                                }

                            ?>

                            <li class="woocommerce-widget-layered-nav-list__item wc-layered-nav-term <?php echo $class_selecionada ?>">
                                <a rel="nofollow" 
                                   href="<?php echo base_url() ?>produtos?categorias=<?php echo $categorias_selecionadas.'&marcas='.$marcas_selecionadas.'&preco='.$preco_selecionado.$preco['valor'].';'.'&desconto='.$desconto_selecionado.'&filtro_classificacao='.$filtro_classificacao_selecionada ?>"><?php echo $preco['descricao'] ?></a>
                                </li>


                                <?php 

                           endforeach;
                           endif;


                           ?>
                            
                        </ul>
                    </div>

                    <div id="woocommerce_layered_nav-6"
                         class="widget woocommerce widget_layered_nav woocommerce-widget-layered-nav"><h2
                            class="widgettitle">DESCONTO<span class="arrow"></span></h2>
                        <ul class="woocommerce-widget-layered-nav-list">


                            <?php 

                            if(!empty($descontros_produtos)): 

                              foreach ($descontros_produtos as $desconto):

                                //VAMOS VERIFICAR SE A CATEGORIA ESTÁ SELECIONADA
                                $selecionadas = explode(';', $desconto_selecionado);

                                $select_desconto = 0;

                                if(!empty($selecionadas)){
                                    foreach ($selecionadas as $key => $value) {
                                            
                                            if(!empty($value) && $value == $desconto['valor']){
                                                $select_desconto = 1;
                                            }


                                    }
                                }
                                

                                //CASO ESTEJA SELECIONADA COLOCAR O BACKGROUND
                                if($select_desconto == 1){
                                    $class_selecionada = 'categoria_selecionada';
                                }else{
                                    $class_selecionada = '';
                                }

                            ?>

                            <li class="woocommerce-widget-layered-nav-list__item wc-layered-nav-term <?php echo $class_selecionada ?>">
                                <a rel="nofollow" 
                                   href="<?php echo base_url() ?>produtos?categorias=<?php echo $categorias_selecionadas.'&marcas='.$marcas_selecionadas.'&preco='.$preco_selecionado.'&desconto='.$desconto_selecionado.$desconto['valor'].';'.'&filtro_classificacao='.$filtro_classificacao_selecionada ?>"><?php echo $desconto['descricao'] ?></a>
                                </li>


                                <?php 

                           endforeach;
                           endif;


                           ?>
                            
                        </ul>
                    </div>


                
                </div><!-- .widget-area -->
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url() ?>assets/js/jquery-1.12.4.min.js"></script>

<script type="text/javascript">


    $(document).ready(function(){

        $('#form_pesquisa').on('keyup keypress', function(e) {
              var keyCode = e.keyCode || e.which;
              if (keyCode === 13) { 

                e.preventDefault();
                submit_pesquisa();

              }
            });

     });
        
        function submit_pesquisa() {

            var pesquisa = $("[name=pesquisa_pg_produtos]").val();

            var url = "<?php echo base_url() ?>produtos?categorias=<?php echo $categorias_selecionadas.'&marcas='.$marcas_selecionadas.'&preco='.$preco_selecionado.'&desconto='.$desconto_selecionado.'&filtro_classificacao='.$filtro_classificacao_selecionada ?>"+'&pesquisa='+pesquisa;
            
            location.href = url;

        }


        function filtrar_classificacao(){

            var filtro = $("[name=filtro_classificacao]").val();

            var url = "<?php echo base_url() ?>produtos?categorias=<?php echo $categorias_selecionadas.'&marcas='.$marcas_selecionadas.'&preco='.$preco_selecionado.'&desconto='.$desconto_selecionado ?>"+'&filtro_classificacao='+filtro;

            location.href = url;

        }

       
     



</script>