<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url() ?>assets/images/favicon.png"/>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
          rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/animate.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/chosen.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/jquery.scrollbar.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/lightbox.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/magnific-popup.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/slick.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/fonts/flaticon.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/megamenu.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/woo-attribute.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/style.css"/>

    <script src="<?php echo base_url() ?>assets/js/jquery-1.12.4.min.js"></script>

    <link href="<?php echo base_url() ?>assets/css/sweetalert.css" rel="stylesheet">
    <script src="<?php echo base_url() ?>assets/js/sweetalert.min.js"></script>

    <title>Gury Informática & Fotografia</title>
</head>
<body>
<header id="header" class="header style-04 header-dark">
    <div class="header-top">
        <div class="container">
            <div class="header-top-inner">
                <ul id="menu-top-left-menu" class="azeroth-nav top-bar-menu">
                    <li id="menu-item-864"
                        class="menu-item menu-item-type-custom menu-item-object-custom menu-item-864"><a
                            class="azeroth-menu-item-title" title="Store Direction"
                            href="#"><span class="icon flaticon-localization"></span>Rua Padre Augusto, 123 - Montes Claros - MG</a></li>
                   <!--  <li id="menu-item-865"
                        class="menu-item menu-item-type-custom menu-item-object-custom menu-item-865"><a
                            class="azeroth-menu-item-title" title="Order Tracking"
                            href="#"><span class="icon flaticon-box"></span>Order
                        Tracking</a></li> -->
                </ul>
                <div class="azeroth-nav top-bar-menu right">
                    <ul class="wpml-menu">

                        <?php if (isset($_SESSION['perfil_usuario_cliente'])): ?>

                        <li class="menu-item azeroth-dropdown block-language">
                            <a href="#" data-azeroth="azeroth-dropdown">
                                <span class="flaticon-user"></span>
                                 Bem vindo <?php echo $_SESSION['perfil_usuario_cliente']->nome; ?>
                            </a>
                            <span class="toggle-submenu"></span>
                            <ul class="sub-menu">
                                <li class="menu-item">
                                    <a href="<?php echo base_url('meus-pedidos') ?>">
                                        Meus pedidos
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="<?php echo base_url('meus-dados') ?>">
                                        Minha conta
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="<?php echo base_url('sair') ?>">
                                        Sair
                                    </a>
                                </li>
                            </ul>
                        </li>

                       <?php else: ?>

                        <li class="menu-item">
                            <a href="<?php echo base_url('login-registrar') ?>" data-azeroth="azeroth-dropdown">
                                <span class="flaticon-user"></span>
                                 Entrar / Cadastrar
                            </a>
                            
                        </li>

                       <?php endif; ?>

                        <li class="menu-item">
                            <div class="wcml-dropdown product wcml_currency_switcher">
                                <ul>
                                    <li class="wcml-cs-active-currency">
                                        <a class="wcml-cs-item-toggle"><span><span class="icon flaticon-telephone"></span> (38) 3222 8400</span></a>          
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="header-middle">
        <div class="container">
            <div class="header-middle-inner">
                <div class="header-logo-menu">
                    <div class="block-menu-bar">
                        <a class="menu-bar menu-toggle" href="#">
                            <span></span>
                            <span></span>
                            <span></span>
                        </a>
                    </div>
                    <div class="header-logo">
                        <a href="<?php echo base_url() ?>"><img alt="Azeroth" src="<?php echo base_url() ?>assets/images/logo.png"
                                                                 class="logo"></a></div>
                </div>
                <div class="header-search-mid">
                    <div class="header-search">
                        <div class="block-search">
                            <form role="search" method="get"
                                  class="form-search block-search-form azeroth-live-search-form">
                                <div class="form-content search-box results-search">
                                    <div class="inner">
                                        <input autocomplete="off" class="searchfield txt-livesearch input" name="s"
                                               value="" placeholder="Pesquise um produto..." type="text">
                                    </div>
                                </div>
                                <input name="post_type" value="product" type="hidden">
                                <input name="taxonomy" value="product_cat" type="hidden">
                               <!--  <div class="category">
                                    <select title="product_cat" name="product_cat" id="1771262470"
                                            class="category-search-option"
                                            tabindex="-1" style="display: none;">
                                        <option value="0">Todas categorias</option>
                                        <option class="level-0" value="boy">Gabinetes</option>
                                        <option class="level-0" value="dress">Games</option>
                                        <option class="level-0" value="girl">Impressoras</option>
                                        <option class="level-0" value="hoodie">Som</option>
                                        <option class="level-0" value="new-arrivals">Telefonia</option>
                                        <option class="level-0" value="shoes">Vídeo</option>
                                    </select>
                                </div> -->
                                <button type="submit" class="btn-submit">
                                    <span class="flaticon-magnifying-glass-browser"></span>
                                </button>
                            </form><!-- block search -->
                        </div>
                    </div>
                </div>
                <div class="header-control">
                    <div class="header-control-inner">
                        <div class="meta-woo">
                            <div class="menu-item block-user block-woo azeroth-dropdown">

                                <?php if (isset($_SESSION['perfil_usuario_cliente'])): ?>

                                <a class="block-link" href="<?php echo base_url('meus-pedidos') ?>">
                                    <span class="flaticon-user"></span>
                                </a>
                                

                                <?php else: ?>

                                <a class="block-link" href="<?php echo base_url() ?>login-registrar">
                                    <span class="flaticon-user"></span>
                                </a>

                                <?php endif; ?>

                            </div>
                            <div class="block-minicart block-woo azeroth-mini-cart azeroth-dropdown">
                                <div class="shopcart-dropdown block-cart-link" data-azeroth="azeroth-dropdown">
                                    <a class="block-link link-dropdown" href="#">
                                        <span class="flaticon-online-shopping-cart"></span>
                                        <span class="count"> <?php if(isset($carrinho)): echo sizeof($carrinho); else: echo '0'; endif; ?> </span>
                                    </a>
                                </div>
                                <div class="widget woocommerce widget_shopping_cart">
                                    <div class="widget_shopping_cart_content">
                                        <ul class="woocommerce-mini-cart cart_list product_list_widget">

                                            <?php $total_carrinho = 0; if(isset($carrinho)): ?>
                                            <?php foreach ($carrinho as $key => $produto): ?>

                                            <li class="woocommerce-mini-cart-item mini_cart_item">
                                                <!-- <a href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>" class="remove remove_from_cart_button">×</a> -->
                                                <a href="<?php echo base_url() ?>detalhes-produto?nome_tag=<?php echo $produto->nome_tag ?>">
                                                    <img src="<?php echo base_url() ?>assets/images/produtos/<?php echo $produto->imagem ?>"
                                                         class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail"
                                                         alt="" width="600" height="778"><?php echo $produto->titulo ?>&nbsp;
                                                </a>
                                                <span class="quantity"><?php echo $produto->quantidade ?> × <span
                                                        class="woocommerce-Price-amount amount"><span
                                                        class="woocommerce-Price-currencySymbol">R$</span></span><?php echo number_format($produto->valor * $produto->quantidade, 2, ',', '.')  ?></span></span>
                                            </li>

                                            <?php $total_carrinho +=  $produto->valor*$produto->quantidade; ?>
                                          <?php endforeach; ?>
                                          <?php endif; ?>

                                           
                                            
                                        </ul>
                                        <p class="woocommerce-mini-cart__total total"><strong>Subtotal:</strong>
                                            <span class="woocommerce-Price-amount amount"><span
                                                    class="woocommerce-Price-currencySymbol">R$</span><?php echo number_format($total_carrinho, 2, ',', '.')  ?></span>
                                        </p>
                                        <p class="woocommerce-mini-cart__buttons buttons">
                                            <a href="<?php echo base_url() ?>carrinho" class="button wc-forward">Ver carrinho</a>
                                            <a href="<?php echo base_url() ?>finalizar-pedido" class="button checkout wc-forward">Finalizar pedido</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-wrap-stick">
        <div class="header-position">
            <div class="header-nav">
                <div class="container">
                    <div class="azeroth-menu-wapper"></div>
                    <div class="header-nav-inner">
                        <!-- block category -->
                        <div data-items="8"
                             class="vertical-wrapper block-nav-category has-vertical-menu show-button-all">
                            <div class="block-title">
                    <span class="before">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                                <span class="text-title">CATEGORIAS</span>
                            </div>
                            <div class="block-content verticalmenu-content">
                                <ul id="menu-vertical-menu" class="azeroth-nav vertical-menu default">


                                    <?php 

                                      if(!empty($categorias_produtos_all)): 


                                        foreach ($categorias_produtos_all as $categoria):

                                      ?>


                                    <li id="menu-item-886"
                                        class="menu-item menu-item-type-custom menu-item-object-custom menu-item-886"><a
                                            class="azeroth-menu-item-title" title="<?php echo $categoria->nome ?>" href="<?php echo base_url() ?>produtos?categoria=<?php echo $categoria->nome_tag ?>"><?php echo $categoria->nome ?></a></li>


                                    <?php 


                                        endforeach;

                                    endif; 

                                    ?>

                                    

                                    
                                </ul>
                                <div class="view-all-category">
                                    <a href="#" data-closetext="Close" data-alltext="All Categories"
                                       class="btn-view-all open-cate">Ver Todas</a>
                                </div>
                                
                            </div>
                        </div><!-- block category -->
                        <div class="box-header-nav menu-nocenter">
                            <ul id="menu-primary-menu"
                                class="clone-main-menu azeroth-clone-mobile-menu azeroth-nav main-menu">
                                <li id="menu-item-230"
                                    class="menu-item menu-item-type-post_type menu-item-object-megamenu menu-item-230 parent parent-megamenu item-megamenu menu-item-has-children">
                                    <a class="azeroth-menu-item-title" title="Home" href="<?php echo base_url() ?>">Home</a>
                                    
                                </li>
                                <li id="menu-item-231"
                                    class="menu-item menu-item-type-post_type menu-item-object-megamenu menu-item-230 parent parent-megamenu item-megamenu menu-item-has-children">
                                    <a class="azeroth-menu-item-title" title="Sobre" href="<?php echo base_url() ?>sobre">Sobre</a>
                                    
                                </li>
                                
                                <li id="menu-item-229"
                                    class="menu-item menu-item-type-post_type menu-item-object-megamenu menu-item-229 parent parent-megamenu item-megamenu menu-item-has-children">
                                    <a class="azeroth-menu-item-title" title="Produtos" href="<?php echo base_url() ?>produtos">Produtos</a>
                                  
                                </li>

                                <li id="menu-item-229"
                                    class="menu-item menu-item-type-post_type menu-item-object-megamenu menu-item-229 parent parent-megamenu item-megamenu menu-item-has-children">
                                    <a class="azeroth-menu-item-title" title="Fotografia" href="<?php echo base_url() ?>fotografia">Fotografia</a>
                                  
                                </li>


                                <li id="menu-item-237"
                                    class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-237 parent">
                                    <a class="azeroth-menu-item-title" title="Blog" href="<?php echo base_url() ?>noticias">Blog</a>
                                    
                                </li>

                                <li id="menu-item-237"
                                    class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-237 parent">
                                    <a class="azeroth-menu-item-title" title="Contato" href="<?php echo base_url() ?>contato">Contato</a>
                                    
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-mobile">
        <div class="header-mobile-left">
            <div class="block-menu-bar">
                <a class="menu-bar menu-toggle" href="#">
                    <span></span>
                    <span></span>
                    <span></span>
                </a>
            </div>
            <div class="header-search azeroth-dropdown">
                <div class="header-search-inner" data-azeroth="azeroth-dropdown">
                    <a href="#" class="link-dropdown block-link">
                        <span class="flaticon-magnifying-glass-browser"></span>
                    </a>
                </div>
                <div class="block-search">
                    <form role="search" method="get"
                          class="form-search block-search-form azeroth-live-search-form">
                        <div class="form-content search-box results-search">
                            <div class="inner">
                                <input autocomplete="off" class="searchfield txt-livesearch input" name="s" value=""
                                       placeholder="Start typing..." type="text">
                            </div>
                        </div>
                        <input name="post_type" value="product" type="hidden">
                        <input name="taxonomy" value="product_cat" type="hidden">
                        <div class="category">
                            <select title="product_cat" name="product_cat" id="1770352541"
                                    class="category-search-option" tabindex="-1"
                                    style="display: none;">
                                <option value="0">All Categories</option>
                                <option class="level-0" value="boy">Boy</option>
                                <option class="level-0" value="dress">Dress</option>
                                <option class="level-0" value="girl">Girl</option>
                                <option class="level-0" value="hoodie">Hoodie</option>
                                <option class="level-0" value="new-arrivals">New arrivals</option>
                                <option class="level-0" value="shoes">Shoes</option>
                                <option class="level-0" value="specials">Specials</option>
                                <option class="level-0" value="t-shirt">T-Shirt</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-submit">
                            <span class="flaticon-magnifying-glass-browser"></span>
                        </button>
                    </form><!-- block search -->
                </div>
            </div>
            <ul class="wpml-menu">
                <li class="menu-item azeroth-dropdown block-language">
                    <a href="#" data-azeroth="azeroth-dropdown">
                        <img src="<?php echo base_url() ?>assets/images/en.png"
                             alt="en" width="18" height="12">
                        English
                    </a>
                    <span class="toggle-submenu"></span>
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a href="#">
                                <img src="<?php echo base_url() ?>assets/images/it.png"
                                     alt="it" width="18" height="12">
                                Italiano
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item">
                    <div class="wcml-dropdown product wcml_currency_switcher">
                        <ul>
                            <li class="wcml-cs-active-currency">
                                <a class="wcml-cs-item-toggle">USD</a>
                                <ul class="wcml-cs-submenu">
                                    <li>
                                        <a>EUR</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <div class="header-mobile-mid">
            <div class="header-logo">
                <a href="<?php echo base_url() ?>"><img alt="Gury Informática"
                                                         src="<?php echo base_url() ?>assets/images/logo.png"
                                                         class="logo"></a></div>
        </div>
        <div class="header-mobile-right">
            <div class="header-control-inner">
                <div class="meta-woo">
                    <div class="menu-item block-user block-woo azeroth-dropdown">
                        <a class="block-link" href="#">
                            <span class="flaticon-user"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="menu-item woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--dashboard is-active">
                                <a href="#">Dashboard</a>
                            </li>
                            <li class="menu-item woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--orders">
                                <a href="#">Orders</a>
                            </li>
                            <li class="menu-item woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--downloads">
                                <a href="#">Downloads</a>
                            </li>
                            <li class="menu-item woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--edit-address">
                                <a href="#">Addresses</a>
                            </li>
                            <li class="menu-item woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--edit-account">
                                <a href="#">Account details</a>
                            </li>
                            <li class="menu-item woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--customer-logout">
                                <a href="#">Logout</a>
                            </li>
                        </ul>
                    </div>
                    <div class="block-minicart block-woo azeroth-mini-cart azeroth-dropdown">
                        <div class="shopcart-dropdown block-cart-link" data-azeroth="azeroth-dropdown">
                            <a class="block-link link-dropdown" href="#">
                                <span class="flaticon-online-shopping-cart"></span>
                                <span class="count">3</span>
                            </a>
                        </div>
                        <div class="widget woocommerce widget_shopping_cart">
                            <div class="widget_shopping_cart_content">
                                <ul class="woocommerce-mini-cart cart_list product_list_widget">
                                    <li class="woocommerce-mini-cart-item mini_cart_item">
                                        <a href="#" class="remove remove_from_cart_button">×</a>
                                        <a href="#">
                                            <img src="<?php echo base_url() ?>assets/images/apro134-1-600x778.jpg"
                                                 class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail"
                                                 alt="" width="600" height="778">T-shirt with skirt – Pink&nbsp;
                                        </a>
                                        <span class="quantity">1 × <span
                                                class="woocommerce-Price-amount amount"><span
                                                class="woocommerce-Price-currencySymbol">$</span>150.00</span></span>
                                    </li>
                                    <li class="woocommerce-mini-cart-item mini_cart_item">
                                        <a href="#" class="remove remove_from_cart_button">×</a>
                                        <a href="#">
                                            <img src="<?php echo base_url() ?>assets/images/apro1113-600x778.jpg"
                                                 class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail"
                                                 alt="" width="600" height="778">Short Sleeve Loose&nbsp;
                                        </a>
                                        <span class="quantity">1 × <span
                                                class="woocommerce-Price-amount amount"><span
                                                class="woocommerce-Price-currencySymbol">$</span>129.00</span></span>
                                    </li>
                                    <li class="woocommerce-mini-cart-item mini_cart_item">
                                        <a href="#" class="remove remove_from_cart_button">×</a>
                                        <a href="#">
                                            <img src="<?php echo base_url() ?>assets/images/apro201-1-600x778.jpg"
                                                 class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail"
                                                 alt="" width="600" height="778">Set Gile Navy Carvat&nbsp;
                                        </a>
                                        <span class="quantity">1 × <span
                                                class="woocommerce-Price-amount amount"><span
                                                class="woocommerce-Price-currencySymbol">$</span>139.00</span></span>
                                    </li>
                                </ul>
                                <p class="woocommerce-mini-cart__total total"><strong>Subtotal:</strong>
                                    <span class="woocommerce-Price-amount amount"><span
                                            class="woocommerce-Price-currencySymbol">$</span>418.00</span>
                                </p>
                                <p class="woocommerce-mini-cart__buttons buttons">
                                    <a href="#" class="button wc-forward">Viewcart</a>
                                    <a href="#" class="button checkout wc-forward">Checkout</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>