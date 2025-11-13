    <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Floricultura Jardins - CMS</title>
    <!-- Mainly scripts -->

    <link rel="shortcut icon" href="<?php echo base_url() ?>../assets/images/favicon/favicon.png"/>

    <link href="<?php echo base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet">

    <link href="<?php echo base_url() ?>assets/css/plugins/toastr/toastr.min.css" rel="stylesheet">

    <link href="<?php echo base_url() ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="<?php echo base_url() ?>assets/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/css/plugins/blueimp/css/blueimp-gallery.min.css" rel="stylesheet">

    <link href="<?php echo base_url() ?>assets/css/animate.css" rel="stylesheet">

    <link href="<?php echo base_url() ?>assets/css/plugins/summernote/summernote.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/css/plugins/summernote/summernote-bs3.css" rel="stylesheet">


    <link href="<?php echo base_url() ?>assets/css/plugins/iCheck/custom.css" rel="stylesheet">

    <link href="<?php echo base_url() ?>assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">

    <link href="<?php echo base_url() ?>assets/css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">

    <!-- Sweet Alert -->
    <link href="<?php echo base_url() ?>assets/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

    <link href="<?php echo base_url() ?>assets/css/plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/css/plugins/fullcalendar/fullcalendar.print.css" rel='stylesheet' media='print'>

    <link href="<?php echo base_url() ?>assets/css/style.css" rel="stylesheet">


    <link href="<?= base_url('assets/css/plugins/select2/select2.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/plugins/select2/custom.css') ?>" rel="stylesheet">

    <script src="<?php echo base_url() ?>assets/js/jquery-2.1.1.js"></script>
    <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="<?php echo base_url() ?>assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <script src="<?php echo base_url() ?>assets/js/plugins/dataTables/datatables.min.js"></script>

    <script src="<?php echo base_url() ?>assets/js/plugins/jasny/jasny-bootstrap.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="<?php echo base_url() ?>assets/js/plugins/toastr/toastr.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/inspinia.js"></script>
    <script src="<?php echo base_url() ?>assets/js/plugins/pace/pace.min.js"></script>
    <!-- Sweet alert -->
    <script src="<?php echo base_url() ?>assets/js/plugins/sweetalert/sweetalert.min.js"></script>

    <script src="<?php echo base_url() ?>assets/js/plugins/iCheck/icheck.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/meio.mask.js"></script>

    <script src="<?php echo base_url() ?>assets/js/plugins/summernote/summernote.min.js"></script>
    <script src="<?= base_url('assets/js/plugins/select2/select2.full.min.js') ?>"></script>
    <!-- Jquery Validate -->
    <script src="<?= base_url() ?>assets/js/plugins/validate/jquery.validate.min.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/validate/translate.messages.js"></script>

    <script src="<?php echo base_url() ?>assets/js/plugins/fullcalendar/moment.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/plugins/fullcalendar/fullcalendar.min.js"></script>

    <link href="<?= base_url('assets/css/plugins/datapicker/datepicker3.css') ?>" rel="stylesheet">

    <script src="<?php echo base_url() ?>assets/js/jquery.mask.min.js"></script>

    <script src="<?php echo base_url() ?>assets/js/plugins/blueimp/jquery.blueimp-gallery.min.js"></script>

    <!-- <script src="<?php echo base_url() ?>assets/js/popper.min.js"></script> -->

    <script src="<?= base_url('assets/js/plugins/datapicker/bootstrap-datepicker.js') ?>"></script>
    <script src="<?= base_url('assets/js/plugins/datapicker/bootstrap-datepicker.pt-BR.js') ?>"></script>

    <script type="text/javascript">
        $(function(){
            $('#side-menu').find('.active').removeClass('active');
            $('#<?php echo (isset($active_menu)) ? $active_menu['menu'] : '' ?>').addClass('active');
            $('#<?php echo (isset($active_menu)) ? $active_menu['submenu'] : '' ?>').addClass('active').parent('ul').addClass('collapse in');
        });
    </script>
</head>

<body>

    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    
                    <?php /*<li id="site">
                        <a href="#"><i class="fa fa-globe"></i> <span class="nav-label">Conteúdo portal</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <!-- <li id="site-banners"><a href="<?php echo base_url(array('site', 'banners')) ?>">Banners</a></li> -->
                            <!-- <li id="site-sobre"><a href="<?php echo base_url(array('site', 'sobre')) ?>">Sobre</a></li> -->
        <!--
                            <li id="site-login"><a href="<?php echo base_url(array('site', 'login')) ?>">Página Login</a></li>


                             <li id="site-proposta-pedagogica"><a href="<?php echo base_url(array('site', 'proposta-pedagogica')) ?>">Proposta pedagógica</a></li>
                             <li id="site-contrato"><a href="<?php echo base_url(array('site', 'contrato')) ?>">Contrato</a></li>
                            <li id="site-galerias-fotos"><a href="<?php echo base_url(array('site', 'galerias')) ?>">Galerias de fotos</a></li>
                            <li id="site-videos"><a href="<?php echo base_url(array('site', 'videos')) ?>">Vídeos</a></li>
                             <li id="site-sk-parque"><a href="<?php echo base_url(array('site', 'solido-parque')) ?>">Sólido parque</a></li>
                            <li id="site-matricula"><a href="<?php echo base_url(array('site', 'matricula')) ?>">Matrículas</a></li> -->


                            <li id="site-projetos-lei"><a href="<?php echo base_url(array('site', 'projetos')) ?>">Projetos de lei</a></li>
                            <li id="site-utilidade"><a href="<?php echo base_url(array('site', 'utilidade')) ?>">Utilidade Pública</a></li>
                            <li id="site-eventos"><a href="<?php echo base_url(array('site', 'eventos')) ?>">Eventos</a></li>
                            <li id="site-eleicao"><a href="<?php echo base_url(array('site', 'eleicao-final')) ?>">Eleição Oficial</a></li>
                            <li id="site-candidatos-oficiais"><a href="<?php echo base_url(array('site', 'candidatos-oficiais')) ?>">Candidatos Oficiais</a></li>
                            <li id="enquetes"><a href="<?php echo base_url(array('site', 'enquetes')) ?>">Enquetes</a></li>
                            <!--<li id="anuncios"><a href="<?php echo base_url(array('site', 'anuncios')) ?>">Anúncios</a></li>-->
                            <li id="denuncias"><a href="<?php echo base_url(array('site', 'lista-denuncias')) ?>">Denuncias</a></li>
                            <li id="Filtro_palavrao"><a href="<?php echo base_url(array('site', 'filtro-palavrao')) ?>">Filtro palavrões</a></li>
                            <li id="site-news-letter"><a href="<?php echo base_url(array('site', 'news-letter')) ?>">News letter</a></li>
                        </ul>
                    </li>
                    */ ?>

                    <?php
                    if(check_habilidade('visualizar_pedidos')
                    || check_habilidade('visualizar_cupons')
                    || check_habilidade('visualizar_plano_contas')
                    || check_habilidade('visualizar_fornecedores')
                    || check_habilidade('visualizar_contas_pagar')
                    ):
                    ?>

                    <li id="site">
                        <a href="#"><i class="fa fa-shopping-cart"></i> <span class="nav-label">Loja</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            
                            <?php if(check_habilidade('visualizar_pedidos')): ?>
                            <li id="loja-pedidos"><a href="<?php echo base_url(array('loja', 'pedidos')) ?>">Pedidos/Vendas</a></li>
                            <?php endif; ?>
                            
                            <?php if(check_habilidade('visualizar_cupons')): ?>
                            <li id="site-promo3"><a href="<?php echo base_url(array('loja', 'promocoes-slides')) ?>">Cupons </a></li>
                            <?php endif; ?>

                            <?php if(check_habilidade('visualizar_plano_contas')): ?>
                            <li id="Loja-plano_contas"><a href="<?php echo base_url(array('loja', 'plano_contas')) ?>">Plano de Contas</a></li>
                            <?php endif; ?>

                            <?php if(check_habilidade('visualizar_fornecedores')): ?>
                            <li id="Loja-fornecedores"><a href="<?php echo base_url(array('loja', 'fornecedores')) ?>">Fornecedores</a></li>
                            <?php endif; ?>


                            <?php if(check_habilidade('visualizar_contas_pagar')): ?>
                            <li id="Loja-contas_pagar"><a href="<?php echo base_url(array('loja', 'contas_pagar')) ?>">Contas a pagar</a></li>
                            <?php endif; ?>

                            


                            <!-- <li id="loja-demonstrativo"><a href="<?php echo base_url(array('loja', 'demonstrativo')) ?>">Relatório pedidos</a></li> -->
<!-- 
                            <li id="loja-tamanho"><a href="<?php echo base_url(array('loja', 'criar-tamanho')) ?>">Novo Tamanho</a></li> -->
                            <!-- <li id="loja-comentarios"><a href="<?php echo base_url(array('loja', 'comentarios')) ?>">Comentários</a></li> -->

                        </ul>
                    </li>

                    <?php
                    endif;
                    ?>

                    <?php
                    if(check_habilidade('visualizar_produtos')
                    || check_habilidade('visualizar_entrada_produtos')
                    || check_habilidade('visualizar_saida_baixa_produtos')
                    ):
                    ?>

                    <li id="site">
                        <a href="#"><i class="fa fa-barcode"></i> <span class="nav-label">Produtos</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">

                            <?php if(check_habilidade('visualizar_produtos')): ?>
                            <li id="Loja-produtos"><a href="<?php echo base_url(array('loja', 'produtos')) ?>">Produtos</a></li>
                            <?php endif; ?>

                            <?php if(check_habilidade('visualizar_entrada_produtos')): ?>
                            <li id="Loja-produtos-entrada"><a href="<?php echo base_url(array('loja', 'produtos-entrada')) ?>">Entrada</a></li>
                            <?php endif; ?>
                            
                            <?php if(check_habilidade('visualizar_saida_baixa_produtos')): ?>
                            <li id="Loja-produtos-saida"><a href="<?php echo base_url(array('loja', 'produtos-saida')) ?>">Saída/Baixa</a></li>
                            <?php endif; ?>

                        </ul>
                    </li>

                    <?php
                    endif;
                    ?>

                    <?php
                    if(check_habilidade('visualizar_usuarios')
                    || check_habilidade('visualizar_clientes')
                    || check_habilidade('visualizar_vendedores')
                    || check_habilidade('visualizar_eventos')
                    || check_habilidade('visualizar_tipos_flores')
                    || check_habilidade('visualizar_grupos_categoria')
                    || check_habilidade('visualizar_categorias')
                    ):
                    ?>
                    
                    <li id="home">
                        <a href="#"><i class="fa fa-globe"></i> <span class="nav-label">Cadastros</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <?php if(check_habilidade('visualizar_usuarios')): ?>
                            <li id="Loja-usuarios"><a href="<?php echo base_url(array('loja', 'usuarios')) ?>">Usuários </a></li>
                            <?php endif; ?>
                            <?php if(check_habilidade('visualizar_clientes')): ?>
                            <li id="Loja-clientes"><a href="<?php echo base_url(array('loja', 'clientes')) ?>">Clientes</a></li>
                            <?php endif; ?>
                            <?php if(check_habilidade('visualizar_vendedores')): ?>
                            <li id="Loja-vendedores"><a href="<?php echo base_url(array('loja', 'vendedores')) ?>">Vendedores</a></li>
                            <?php endif; ?>
                            <?php if(check_habilidade('visualizar_eventos')): ?>
                            <li id="Loja-eventos"><a href="<?php echo base_url(array('loja', 'eventos')) ?>">Eventos</a></li>
                            <?php endif; ?>
                            <?php if(check_habilidade('visualizar_tipos_flores')): ?>
                            <li id="Loja-marcas"><a href="<?php echo base_url(array('loja', 'marcas')) ?>">Tipos de flores </a></li>
                            <?php endif; ?>
                            <?php if(check_habilidade('visualizar_grupos_categoria')): ?>
                            <li id="Loja-grupos-categorias"><a href="<?php echo base_url(array('loja', 'grupos-categorias')) ?>">Grupos de Categorias</a></li>
                            <?php endif; ?>
                            <?php if(check_habilidade('visualizar_categorias')): ?>
                            <li id="Loja-categorias"><a href="<?php echo base_url(array('loja', 'categorias')) ?>">Categorias</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>

                    <?php
                    endif;
                    ?>

                    <!--   <li id="agenda">
                        <a href="#"><i class="fa fa-calendar"></i> <span class="nav-label">Agenda</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li id="site-eventos"><a href="<?php echo base_url(array('site', 'eventos')) ?>">Alojamentos</a></li>
                            <li id="agenda-lista"><a href="<?php echo base_url(array('agenda', 'lista')) ?>">Lista de agendamentos</a></li>


                        </ul>
                    </li> -->

                    <?php
                    if(check_habilidade('relatorios_fluxo_caixa_pedidos')
                    || check_habilidade('relatorios_posicao_estoque')
                    || check_habilidade('relatorios_estoque_minimo')
                    ):
                    ?>

                    <li id="site">
                        <a href="#"><i class="fa fa-files-o"></i> <span class="nav-label">Relatórios</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                        
                            <?php if(check_habilidade('relatorios_fluxo_caixa_pedidos')): ?>
                            <li id="loja-demonstrativo"><a href="<?php echo base_url(array('loja', 'demonstrativo')) ?>">Fluxo de caixa / Pedidos</a></li>
                            <?php endif; ?>

                            <?php if(check_habilidade('relatorios_posicao_estoque')): ?>
                            <li id="Loja-produtos-posicao-estoque"><a href="<?php echo base_url(array('loja', 'posicao-estoque')) ?>">Posição de estoque</a></li>
                            <?php endif; ?>

                            <?php if(check_habilidade('relatorios_estoque_minimo')): ?>
                            <li id="Loja-produtos-estoque-minimo"><a href="<?php echo base_url(array('loja', 'estoque-minimo')) ?>">Estoque mínimo</a></li>
                            <?php endif; ?>

                        </ul>
                    </li>

                    <?php
                    endif;
                    ?>


                     <!--  <li id="transparencia">
                        <a href="#"><i class="fa fa-calendar"></i> <span class="nav-label">Transparencia</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                       
                            <li id="lista-transparencia"><a href="<?php echo base_url(array('site/transparencia')) ?>">Lista</a></li>


                        </ul>
                    </li> -->

                    <?php
                    if(check_habilidade('dados_site')
                    ):
                    ?>

                    <li id="conteudo">
                        <a href="#"><i class="fa fa-desktop"></i> <span class="nav-label">Site</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">

                        

                            <li id="site-banners"><a href="<?php echo base_url(array('site', 'banners')) ?>">Banners</a></li>
                            <!-- <li id="site-banners"><a href="<?php echo base_url(array('site', 'blocos-estaticos?nome_unico=home_promocao')) ?>">Home promoção</a></li> -->

                            <!-- <li id="site-banners"><a href="<?php echo base_url(array('site', 'blocos-estaticos?nome_unico=legislacao')) ?>">Legislação</a></li> -->

                            <li id="site-banners"><a href="<?php echo base_url(array('site', 'blocos-estaticos?nome_unico=sobre')) ?>">Sobre</a></li>


                            <!-- <li id="site-banners"><a href="<?php echo base_url(array('site', 'blocos-estaticos?nome_unico=fotografia')) ?>">Fotografia</a></li> -->



                            <!-- <li id="site-noticias"><a href="<?php echo base_url(array('site', 'noticias')) ?>">Notícias</a></li> -->
                            <!-- <li id="site-promo1"><a href="<?php echo base_url(array('site', 'horario')) ?>">Horario de Funcionamento</a></li> -->
                            <!-- <li id="site-estrutura"><a href="<?php echo base_url(array('site', 'estrutura')) ?>">Estrutura</a></li> -->
                            <!-- <li id="site-secao1"><a href="<?php echo base_url(array('site', 'secao1')) ?>">Seção 1 </a></li> -->
                            <!-- <li id="site-promo3"><a href="<?php echo base_url(array('loja', 'promocoes-slides')) ?>">Promoções - Slides </a></li> -->
                            
                            <!-- <li id="site-contato"><a href="<?php echo base_url(array('site', 'contato')) ?>">Contato</a></li> -->
                            <!-- <li id="site-sobre"><a href="<?php echo base_url(array('site', 'sobre')) ?>">Sobre</a></li> -->
                            <!-- <li id="site-news-letter"><a href="<?php echo base_url(array('site', 'news-letter')) ?>">Newsletter</a></li> -->


                        </ul>
                    </li>

                    <?php
                    endif;
                    ?>



                    <?php /* ?>
                    <li id="institucional">
                        <a href="#"><i class="fa fa-globe"></i> <span class="nav-label">Institucional</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">

                            <li id="site-institucional"><a href="<?php echo base_url(array('site', 'institucional')) ?>">O Projeto Meritus</a></li>
                            <li id="ead"><a href="<?php echo base_url(array('site', 'ead')) ?>">EAD</a></li>
                            <li id="certificacao"><a href="<?php echo base_url(array('site', 'certificacao')) ?>">certificacao</a></li>
                            <li id="termos"><a href="<?php echo base_url(array('site', 'termos-de-uso')) ?>">Termos de Uso</a></li>
                            <li id="termos_de_uso"><a href="<?php echo base_url(array('site', 'editar-termos-uso')) ?>">Editar Termos de Uso</a></li>


                        </ul>
                    </li>


                  <li id="processos">
                        <a href="#"><i class="fa fa-gavel"></i> <span class="nav-label">Processos</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">

                            <li id="processos_aba"><a href="<?php echo base_url(array('site', 'processos')) ?>">lista dos Processos</a></li>


                        </ul>
                    </li>

    */ ?>
                    <!-- <li id="loja">
                        <a href="#"><i class="fa fa-shopping-bag"></i> <span class="nav-label">Loja</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li id="loja-produtos"><a href="<?php echo base_url(array('loja', 'produtos')) ?>">Produtos</a></li>
                            <li id="loja-categorias"><a href="<?php echo base_url(array('loja', 'categorias')) ?>">Categorias</a></li>
                            <li id="loja-clientes"><a href="<?php echo base_url(array('loja', 'clientes')) ?>">Clientes</a></li>
                            <li id="loja-pedidos"><a href="<?php echo base_url(array('loja', 'pedidos')) ?>">Pedidos</a></li>
                        </ul>
                    </li> -->
                </ul>
            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                       <!--  <form role="search" class="navbar-form-custom" action="search_results.html">
                            <div class="form-group">
                                <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
                            </div>
                        </form> -->
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <span class="m-r-sm text-muted welcome-message"><strong>Bem-vindo</strong> <?php echo $this->auth->get_nome_usuario() ?></span>
                        </li>
                        <!-- <li class="dropdown">
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                <i class="fa fa-envelope"></i>  <span class="label label-warning">16</span>
                            </a>
                            <ul class="dropdown-menu dropdown-messages">
                                <li>
                                    <div class="dropdown-messages-box">
                                        <a href="profile.html" class="pull-left">
                                            <img alt="image" class="img-circle" src="<?php echo base_url() ?>assets/img/a7.jpg">
                                        </a>
                                        <div class="media-body">
                                            <small class="pull-right">46h ago</small>
                                            <strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>. <br>
                                            <small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="dropdown-messages-box">
                                        <a href="profile.html" class="pull-left">
                                            <img alt="image" class="img-circle" src="<?php echo base_url() ?>assets/img/a4.jpg">
                                        </a>
                                        <div class="media-body ">
                                            <small class="pull-right text-navy">5h ago</small>
                                            <strong>Chris Johnatan Overtunk</strong> started following <strong>Monica Smith</strong>. <br>
                                            <small class="text-muted">Yesterday 1:21 pm - 11.06.2014</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="dropdown-messages-box">
                                        <a href="profile.html" class="pull-left">
                                            <img alt="image" class="img-circle" src="<?php echo base_url() ?>assets/img/profile.jpg">
                                        </a>
                                        <div class="media-body ">
                                            <small class="pull-right">23h ago</small>
                                            <strong>Monica Smith</strong> love <strong>Kim Smith</strong>. <br>
                                            <small class="text-muted">2 days ago at 2:30 am - 11.06.2014</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="text-center link-block">
                                        <a href="mailbox.html">
                                            <i class="fa fa-envelope"></i> <strong>Read All Messages</strong>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                <i class="fa fa-bell"></i>  <span class="label label-primary">8</span>
                            </a>
                            <ul class="dropdown-menu dropdown-alerts">
                                <li>
                                    <a href="mailbox.html">
                                        <div>
                                            <i class="fa fa-envelope fa-fw"></i> You have 16 messages
                                            <span class="pull-right text-muted small">4 minutes ago</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="profile.html">
                                        <div>
                                            <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                            <span class="pull-right text-muted small">12 minutes ago</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="grid_options.html">
                                        <div>
                                            <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                            <span class="pull-right text-muted small">4 minutes ago</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="text-center link-block">
                                        <a href="notifications.html">
                                            <strong>See All Alerts</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li> -->
                        <li>
                            <a href="<?php echo base_url(array('logout')) ?>">
                                <i class="fa fa-sign-out"></i> Sair
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
