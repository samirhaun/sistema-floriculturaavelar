<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>Floricultura Jardins</title>
        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url() ?>assets/images/favicon/favicon.png" />

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800&display=swap" rel="stylesheet" />
        <!-- All CSS Flies   -->
        <!--===== Vendor CSS (Bootstrap & Icon Font) =====-->
        <!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/plugins/bootstrap.min.css" />
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/plugins/font-awesome.min.css" />
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/plugins/ionicons.min.css" /> -->
        <!--===== Plugins CSS (All Plugins Files) =====-->
        <!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/plugins/jquery-ui.min.css" />
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/plugins/meanmenu.css" />
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/plugins/nice-select.css" />
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/plugins/owl-carousel.css" />
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/plugins/slick.css" /> -->
        <!--===== Main Css Files =====-->
        <!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/style.css" /> -->
        <!-- ===== Responsive Css Files ===== -->
        <!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/responsive.css" /> -->

        <!--====== Use the minified version files listed below for better performance and remove the files listed above ======-->

        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/vendor/plugins.min.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/style.min.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/responsive.min.css">

        <script src="<?php echo base_url() ?>assets/js/vendor/jquery-3.5.1.min.js"></script>

        <link href="<?php echo base_url() ?>assets/css/sweetalert.css" rel="stylesheet">
        <script src="<?php echo base_url() ?>assets/js/sweetalert.min.js"></script>


        <style>
            .count-cart:after {
                content: "<?php if(isset($carrinho)): echo sizeof($carrinho); else: echo '0'; endif; ?>";
            }
        </style>
    </head>

    <body>
        <!-- main layout start from here -->
        <!--====== PRELOADER PART START ======-->

        <!-- <div id="preloader">
        <div class="preloader">
            <span></span>
            <span></span>
        </div>
    </div> -->

        <!--====== PRELOADER PART ENDS ======-->
        <div id="main">
            <!-- Header Start -->
            <header class="main-header">
                <!-- Header Top Start -->
                <div class="header-top-nav">
                    <div class="container-fluid">
                        <div class="row">
                            <!--Left Start-->
                            <div class="col-lg-4 col-md-4">
                                <div class="left-text">
                                <?php if (isset($_SESSION['perfil_usuario_cliente'])): ?>
                                    <p>Bem vindo <?php echo $_SESSION['perfil_usuario_cliente']->nome; ?></p>
                                <?php else: ?>
                                    <p>Bem vindo a Floricultura Jardins!</p>
                                <?php endif; ?>

                                </div>
                            </div>
                            <!--Left End-->
                            <!--Right Start-->
                            <div class="col-lg-8 col-md-8 text-right">
                                <div class="header-right-nav">
                                    <ul class="res-xs-flex">
                                        
                                        <?php if (isset($_SESSION['perfil_usuario_cliente'])): ?>
                                        <li class="after-n">
                                            <a href="<?php echo base_url('meus-pedidos') ?>"><i class="ion-ios-shuffle-strong"></i>Meus pedidos</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('meus-dados') ?>"><i class="ion-android-person"></i>Minha conta</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('sair') ?>">Sair</a>
                                        </li>
                                        <?php else: ?>
                                        <li>
                                            <a href="<?php echo base_url('login-registrar') ?>">Entrar / Cadastrar</a>
                                        </li>
                                        <?php endif; ?>
                                    </ul>   
                                </div>
                            </div>
                            <!--Right End-->
                        </div>
                    </div>
                </div>
                <!-- Header Top End -->
                <!-- Header Buttom Start -->
                <div class="header-navigation sticky-nav">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- Logo Start -->
                            <div class="col-md-2 col-sm-2">
                                <div class="logo">
                                    <a href="<?php echo base_url() ?>"><img src="<?php echo base_url() ?>assets/images/logo.png" alt="" /></a>
                                </div>
                            </div>
                            <!-- Logo End -->
                            <!-- Navigation Start -->
                            <div class="col-md-10 col-sm-10">
                                <!--Main Navigation Start -->
                                <div class="main-navigation d-none d-lg-block">
                                    <ul>
                                        
                                        <li><a href="<?php echo base_url() ?>">Home</a></li>
                                      
                                        <li class="menu-dropdown">
                                            <a href="<?php echo base_url() ?>produtos">Tipos de flores <i class="ion-ios-arrow-down"></i></a>
                                            <ul class="sub-menu">
                                                <li><a href="<?php echo base_url() ?>produtos">Rosas</a></li>
                                                <li><a href="<?php echo base_url() ?>produtos">Girassol</a></li>
                                                <li><a href="<?php echo base_url() ?>produtos">Orquídea</a></li>
                                                <li><a href="<?php echo base_url() ?>produtos">Cravos</a></li>
                                                <li><a href="<?php echo base_url() ?>produtos">Outras flores</a></li>
                                            </ul>
                                        </li>

                                        <?php 

                                        if(!empty($grupos_categorias)): 


                                            foreach ($grupos_categorias as $grupo):

                                        ?>

                                        <li class="menu-dropdown">
                                            <a href="#"><?= $grupo->nome ?> <i class="ion-ios-arrow-down"></i></a>
                                            <ul class="sub-menu">

                                            <?php 

                                            if(!empty($grupo->categorias)): 

                                                foreach ($grupo->categorias as $categoria):

                                            ?>

                                                <li><a href="<?= base_url() ?>produtos?categorias=<?= $categoria->nome_tag ?>"><?= $categoria->nome ?></a></li>

                                                <?php 

                                                endforeach;

                                                endif; 

                                                ?>

                                            </ul>
                                        </li>

                                       
                                       <?php 

                                        endforeach;

                                        endif; 

                                        ?>

                                       
                                        
                                        
                                    </ul>
                                </div>
                                <!--Main Navigation End -->
                                <!--Header Bottom Account Start -->
                                <div class="header_account_area">
                                    <!--Seach Area Start -->
                                    <div class="header_account_list search_list">
                                        <a href="javascript:void(0)"><i class="ion-ios-search-strong"></i></a>
                                        <div class="dropdown_search">
                                            <form action="<?php echo base_url() ?>produtos" method="get">
                                                <input placeholder="Pesquise um produto ..." name="pesquisa" type="text" />
                                               
                                                <button type="submit"><i class="ion-ios-search-strong"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                    <!--Seach Area End -->
                                    <!--Contact info Start -->
                                    <div class="contact-link">
                                        <div class="phone">
                                            <p>Telefone:</p>
                                            <a href="tel:(38)3083 0232">(38) 3083 0232</a>
                                        </div>
                                    </div>
                                    <!--Contact info End -->
                                    <?php 
                                    $total_carrinho = 0; 
                                    if(isset($carrinho)): 
                                        foreach ($carrinho as $key => $produto):
                                            $total_carrinho +=  $produto->valor*$produto->quantidade;
                                        endforeach;
                                    endif;
                                    
                                    ?>
                                    <!--Cart info Start -->
                                    <div class="cart-info d-flex">
                                        <div class="mini-cart-warp">
                                            <a href="#" class="count-cart"><span>R$ <?php echo number_format($total_carrinho, 2, ',', '.')  ?></span></a>
                                            <div class="mini-cart-content">
                                                <ul>

                                                <?php if(isset($carrinho)): ?>
                                                <?php foreach ($carrinho as $key => $produto): ?>

                                                    <li class="single-shopping-cart">
                                                        <div class="shopping-cart-img">
                                                            <a href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>"><img alt="" src="<?php echo base_url() ?>assets/images/produtos/<?php echo $produto->imagem ?>" /></a>
                                                            <span class="product-quantity"><?php echo $produto->quantidade ?>x</span>
                                                        </div>
                                                        <div class="shopping-cart-title">
                                                            <h4><a href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>"><?php echo $produto->titulo ?></a></h4>
                                                            <span>R$<?php echo number_format($produto->valor * $produto->quantidade, 2, ',', '.')  ?></span>
                                                            <!-- <div class="shopping-cart-delete">
                                                                <a href="#"><i class="ion-android-cancel"></i></a>
                                                            </div> -->
                                                        </div>
                                                    </li>

                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                                    
                                                    
                                                </ul>
                                                <div class="shopping-cart-total">
                                                    <!-- <h4>Subtotal : <span>R$80,00</span></h4> -->
                                                    <!-- <h4>Shipping : <span>$7.00</span></h4> -->
                                                    <!-- <h4>Entrega : <span>R$5,00</span></h4> -->
                                                    <h4 class="shop-total">Subtotal : <span>R$<?php echo number_format($total_carrinho, 2, ',', '.')  ?></span></h4>
                                                </div>
                                                <div class="shopping-cart-btn text-center">
                                                    <a class="default-btn" href="<?php echo base_url() ?>carrinho">finalizar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--Cart info End -->
                                </div>
                            </div>
                        </div>
                        <!-- mobile menu -->
                        <div class="mobile-menu-area">
                            <div class="mobile-menu">
                                <nav id="mobile-menu-active">
                                    <ul class="menu-overflow">
                                        <li><a href="#">Home</a></li>
                                      
                                        <li class="menu-dropdown">
                                            <a href="#">Tipos de flores <i class="ion-ios-arrow-down"></i></a>
                                            <ul class="sub-menu">
                                                <li><a href="#">Rosas</a></li>
                                                <li><a href="#">Girassol</a></li>
                                                <li><a href="#">Orquídea</a></li>
                                                <li><a href="#">Cravos</a></li>
                                                <li><a href="#">Outras flores</a></li>
                                            </ul>
                                        </li>

                                        <li class="menu-dropdown">
                                            <a href="#">Buquês de flores <i class="ion-ios-arrow-down"></i></a>
                                            <ul class="sub-menu">
                                                <li><a href="#">Rosas</a></li>
                                                <li><a href="#">Girassol</a></li>
                                                <li><a href="#">Orquídea</a></li>
                                                <li><a href="#">Cravos</a></li>
                                                <li><a href="#">Outras flores</a></li>
                                            </ul>
                                        </li>

                                        <li class="menu-dropdown">
                                            <a href="#">Ocasiões <i class="ion-ios-arrow-down"></i></a>
                                            <ul class="sub-menu">
                                                <li><a href="#">Rosas</a></li>
                                                <li><a href="#">Girassol</a></li>
                                                <li><a href="#">Orquídea</a></li>
                                                <li><a href="#">Cravos</a></li>
                                                <li><a href="#">Outras flores</a></li>
                                            </ul>
                                        </li>

                                        <li class="menu-dropdown">
                                            <a href="#">Flores em vasos <i class="ion-ios-arrow-down"></i></a>
                                            <ul class="sub-menu">
                                                <li><a href="#">Rosas</a></li>
                                                <li><a href="#">Girassol</a></li>
                                                <li><a href="#">Orquídea</a></li>
                                                <li><a href="#">Cravos</a></li>
                                                <li><a href="#">Outras flores</a></li>
                                            </ul>
                                        </li>

                                        <li class="menu-dropdown">
                                            <a href="#">Arranjo de flores <i class="ion-ios-arrow-down"></i></a>
                                            <ul class="sub-menu">
                                                <li><a href="#">Rosas</a></li>
                                                <li><a href="#">Girassol</a></li>
                                                <li><a href="#">Orquídea</a></li>
                                                <li><a href="#">Cravos</a></li>
                                                <li><a href="#">Outras flores</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <!-- mobile menu end-->
                    </div>
                </div>
                <!--Header Bottom Account End -->
            </header>
            <!-- Header End -->