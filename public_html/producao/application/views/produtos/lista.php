

            <!-- Breadcrumb Area start -->
            <section class="breadcrumb-area">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="breadcrumb-content">
                                <h1 class="breadcrumb-hrading">Produtos</h1>
                                <ul class="breadcrumb-links">
                                    <li><a href="<?php echo base_url() ?>">Home</a></li>
                                    <li>Produtos</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Breadcrumb Area End -->
            <!-- Shop Category Area End -->
            <div class="shop-category-area">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-9 order-lg-last col-md-12 order-md-first">
                            <!-- Shop Top Area Start -->
                            <div class="shop-top-bar">
                                <!-- Left Side start -->
                                <div class="shop-tab nav mb-res-sm-15">
                                    <!-- <a class="active" href="#shop-1" data-toggle="tab">
                                        <i class="fa fa-th show_grid"></i>
                                    </a>
                                    <a href="#shop-2" data-toggle="tab">
                                        <i class="fa fa-list-ul"></i>
                                    </a> -->
                                    <?php 


                                    $total_produtos = $total_produtos_paginacao;

                                    $most_result =  explode('_', $filtro_paginacao);

                                    $qtd_prod_pagina = 8;
                                    
                                    ?>

                                    <p>Mostrando 
                                                      <?php if($most_result[1] == 0){ echo '1';}else{echo $most_result[1];} ?>
                                                      –
                                                      <?php echo $most_result[1] + $qtd_prod_pagina  ?> 
                                                      de 
                                                      <?php echo $total_produtos ?> 
                                                      resultados</p>
                                </div>
                                <!-- Left Side End -->
                                <!-- Right Side Start -->
                                <div class="select-shoing-wrap">
                                    <div class="shot-product">
                                        <p>Ordenar:</p>
                                    </div>
                                    <div class="shop-select">
                                        <select name="filtro_classificacao" onchange="filtrar_classificacao()">
                                            <option <?php if(!empty($filtro_classificacao_selecionada) && $filtro_classificacao_selecionada == 'mais_recente') echo 'selected="selected"' ?> value="mais_recente">Mais recente</option>
                                            <option <?php if(!empty($filtro_classificacao_selecionada) && $filtro_classificacao_selecionada == 'menor_preco') echo 'selected="selected"' ?> value="menor_preco">Menor preço</option>
                                            <option <?php if(!empty($filtro_classificacao_selecionada) && $filtro_classificacao_selecionada == 'maior_preco') echo 'selected="selected"' ?> value="maior_preco">Maior preço</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Right Side End -->
                            </div>
                            <!-- Shop Top Area End -->

                            <!-- Shop Bottom Area Start -->
                            <div class="shop-bottom-area mt-35">
                                <!-- Shop Tab Content Start -->
                                <div class="tab-content jump">
                                    <!-- Tab One Start -->
                                    <div id="shop-1" class="tab-pane active">
                                        <div class="row">


                                        <?php 

                                        if(!empty($produtos)): 

                                        foreach ($produtos as $produto):

                                        ?>


                                          <div class="col-xl-3 col-md-6 col-lg-4 col-sm-6 col-xs-12">
                                            <article class="list-product">
                                                    <div class="img-block">
                                                        <a href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>" class="thumbnail">
                                                            <img class="first-img" src="<?php echo base_url() ?>assets/images/produtos/<?php echo $produto->imagem ?>" alt="<?php echo $produto->titulo ?>" />
                                                            <img class="second-img" src="<?php echo base_url() ?>assets/images/produtos/<?php echo $produto->imagem ?>" alt="<?php echo $produto->titulo ?>" />
                                                        </a>
                                                    </div>
                                                    <div class="product-decs">
                                                        <a class="inner-link" href="<?= base_url() ?>produtos?categorias=<?= $produto->categoria_nome_tag ?>"><span><?php echo $produto->categoria ?></span></a>
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
                                                                <!-- <a href="<?php echo base_url() ?>detalhes-produto?tag=arranjo-mini-suculenta"><i class="ion-android-favorite-outline"></i></a> -->
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
                                    </div>
                                    <!-- Tab One End -->
                                    
                                </div>
                                <!-- Shop Tab Content End -->
                                <!--  Pagination Area Start -->
                                <div class="pro-pagination-style text-center">
                                    <ul>



                                    <?php 

                            $qtd_prod_pagina = 8;

                            $total_produtos = $total_produtos_paginacao;
                            $total_paginas = ceil($total_produtos / $qtd_prod_pagina);
                            ?>

                            <?php 

                            for ($i=0; $i < $total_paginas; $i++): 

                            $ofset = $i * $qtd_prod_pagina;

                            ?>




                            <?php if($filtro_paginacao == $qtd_prod_pagina.'_'.$ofset){ ?>
                                <!-- <span class="page-numbers current"><?php echo  $i + 1 ?></span> -->
                                <li><a class="active" href="#"><?php echo  $i + 1 ?></a></li>
                            <?php }else{ ?>

                            <!-- <a class="page-numbers" href="<?php echo base_url() ?>produtos?categorias=<?php echo $categorias_selecionadas.'&marcas='.$marcas_selecionadas.'&preco='.$preco_selecionado.'&desconto='.$desconto_selecionado.'&filtro_classificacao='.$filtro_classificacao_selecionada.'&paginacao='.$qtd_prod_pagina.'_'.$ofset ?>"

                            > <?php echo  $i + 1 ?> </a> -->

                            <li><a href="<?php echo base_url() ?>produtos?categorias=<?php echo $categorias_selecionadas.'&marcas='.$marcas_selecionadas.'&preco='.$preco_selecionado.'&desconto='.$desconto_selecionado.'&filtro_classificacao='.$filtro_classificacao_selecionada.'&paginacao='.$qtd_prod_pagina.'_'.$ofset ?>"><?php echo  $i + 1 ?></a></li>

                            <?php } ?>

                            



                            <?php endfor; ?>
                                        <!-- <li>
                                            <a class="prev" href="#"><i class="ion-ios-arrow-left"></i></a>
                                        </li> -->
                                        
                                        
                                        <!-- <li>
                                            <a class="next" href="#"><i class="ion-ios-arrow-right"></i></a>
                                        </li> -->
                                    </ul>
                                </div>
                                <!--  Pagination Area End -->
                            </div>
                            <!-- Shop Bottom Area End -->
                        </div>
                        <!-- Sidebar Area Start -->
                        <div class="col-lg-3 order-lg-first col-md-12 order-md-last mb-res-md-60px mb-res-sm-60px">
                            <div class="left-sidebar">
                                <div class="sidebar-heading">
                                    <div class="main-heading">
                                        <h2>Filtrar por</h2>
                                    </div>
                                    <!-- Sidebar single item -->
                                    <div class="sidebar-widget">
                                        <h4 class="pro-sidebar-title">Categorias</h4>
                                        <div class="sidebar-widget-list">
                                            <ul>


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
                                    $class_selecionada = 'checked';
                                }else{
                                    $class_selecionada = '';
                                }

                            ?>


                                  <li>
                                                    <div class="sidebar-widget-list-left">
                                                        <input type="checkbox" <?php echo $class_selecionada ?> disabled/> 
                                                        <a href="<?php echo base_url() ?>produtos?categorias=<?php echo $categorias_selecionadas.$categoria->nome_tag.';'.'&marcas='.$marcas_selecionadas.'&preco='.$preco_selecionado.'&desconto='.$desconto_selecionado.'&filtro_classificacao='.$filtro_classificacao_selecionada ?>"><?php echo $categoria->nome ?> <span></span> </a>
                                                        <span class="checkmark"></span>
                                                    </div>
                                                </li>

                            <?php 

                           endforeach;
                           endif;


                           ?>
                                                <!-- <li>
                                                    <div class="sidebar-widget-list-left">
                                                        <input type="checkbox" /> <a href="#">Buquês de Flores<span>(17)</span> </a>
                                                        <span class="checkmark"></span>
                                                    </div>
                                                </li> -->
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- Sidebar single item -->
                                </div>
                                <!-- Sidebar single item -->
                                
                                <!-- Sidebar single item -->
                                <div class="sidebar-widget mt-30">
                                    <h4 class="pro-sidebar-title">Tipos de flores</h4>
                                    <div class="sidebar-widget-list">
                                        <ul>


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
                                    $class_selecionada = 'checked';
                                }else{
                                    $class_selecionada = '';
                                }

                                    ?>

                                

                                        <li>
                                                <div class="sidebar-widget-list-left">
                                                    <input type="checkbox" <?php echo $class_selecionada ?> disabled /> <a href="<?php echo base_url() ?>produtos?categorias=<?php echo $categorias_selecionadas.'&marcas='.$marcas_selecionadas.$marca->nome_tag.';'.'&preco='.$preco_selecionado.'&desconto='.$desconto_selecionado.'&filtro_classificacao='.$filtro_classificacao_selecionada ?>"><?php echo $marca->titulo ?>  <span></span> </a>
                                                    <span class="checkmark"></span>
                                                </div>
                                            </li>

                                    <?php 

                                endforeach;
                                endif;


                                ?>


                                            
                                            

                                        </ul>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <!-- Sidebar Area End -->
                    </div>
                </div>
            </div>
            <!-- Shop Category Area End -->

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
            