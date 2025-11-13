<?php if(isset($_GET['finalizar'])): ?>
<div class="banner-wrapper no_background">
    <div class="banner-wrapper-inner">
        <nav class="woocommerce-breadcrumb">
          <span style="color: #000096;font-size: 16px;">
            1 
            <span class="flaticon-user"></span>
            Identificação
          </span>

          <i class="fa fa-angle-right"></i>

          <span>
            <a href="<?php echo base_url() ?>carrinho">
            2 
            <span class="flaticon-online-shopping-cart"></span>
            Meu Carrinho
            </a>
          </span>

          <i class="fa fa-angle-right"></i>

          <span>
            <a href="<?php echo base_url() ?>finalizar-pedido">
            3 
            <span class="flaticon-truck"></span>
            Finalizar pedido
            </a>
          </span>

        </nav>
    </div>
</div>
<?php endif; ?>
<div class="single-thumb-vertical main-container shop-page no-sidebar">
    <div class="container">
        <div class="row">
            <div class="main-content col-sm-12">
                <div class="woocommerce-notices-wrapper"></div>
                <div id="product-27"
                     class="post-27 product type-product status-publish has-post-thumbnail product_cat-girl product_cat-new-arrivals product_cat-shoes product_tag-girl product_tag-sock first instock shipping-taxable purchasable product-type-variable has-default-attributes">
                    <div class="main-contain-summary">
                        <div class="contain-left has-gallery">
                    
                         <form class="wpcf7-form" action="<?php echo base_url('salvar-editar-perfil') ?>" method="post" >

                            <?php if(isset($_GET['finalizar'])): ?>

                                      <input type="hidden" name="finalizar" value="1">

                            <?php endif; ?>

                            <div class="col-sm-12">

                                <div role="form" class="wpcf7">
                                        <h2>Meus dados</h2>
                                </div>
                            </div>

                            <div class="col-sm-6">

                                <div role="form" class="wpcf7">
                                        <p><label> Nome *<br>
                                            <span class="wpcf7-form-control-wrap your-name">
                                                <input name="nome" value="<?php echo $perfil->nome; ?>" size="40"
                                                       class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required"
                                                       type="text" required></span>
                                        </label></p>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div role="form" class="wpcf7">
                                        <p><label> Email (não editável)<br>
                                            <span class="wpcf7-form-control-wrap your-name">
                                                <input value="<?php echo $perfil->email; ?>" size="40"
                                                       class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required"
                                                       type="text" readonly="readonly"></span>
                                        </label></p>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div role="form" class="wpcf7">
                                        <p><label> Telefone *<br>
                                            <span class="wpcf7-form-control-wrap your-name">
                                                <input name="telefone" value="<?php echo $perfil->telefone; ?>" size="40"
                                                       class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required"
                                                       type="text" required></span>
                                        </label></p>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div role="form" class="wpcf7">
                                        <p><label> CPF<br>
                                            <span class="wpcf7-form-control-wrap your-name">
                                                <input name="cpf" value="<?php echo $perfil->cpf; ?>" size="40"
                                                       class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required"
                                                       type="text"></span>
                                        </label></p>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div role="form" class="wpcf7">
                                        <p><label> CEP<br>
                                            <span class="wpcf7-form-control-wrap your-name">
                                                <input name="cep" value="<?php echo $perfil->cep; ?>" size="40"
                                                       class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required"
                                                       type="text" ></span>
                                        </label></p>
                                </div>
                            </div>

                            <div class="col-sm-9">
                                <div role="form" class="wpcf7">
                                        <p><label> Rua<br>
                                            <span class="wpcf7-form-control-wrap your-name">
                                                <input name="rua" value="<?php echo $perfil->rua; ?>" size="40"
                                                       class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required"
                                                       type="text" ></span>
                                        </label></p>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div role="form" class="wpcf7">
                                        <p><label> Número<br>
                                            <span class="wpcf7-form-control-wrap your-name">
                                                <input name="numero" value="<?php echo $perfil->numero; ?>" size="40"
                                                       class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required"
                                                       type="text" ></span>
                                        </label></p>
                                </div>
                            </div>

                            <div class="col-sm-9">
                                <div role="form" class="wpcf7">
                                        <p><label> Bairro<br>
                                            <span class="wpcf7-form-control-wrap your-name">
                                                <input name="bairro" value="<?php echo $perfil->bairro; ?>" size="40"
                                                       class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required"
                                                       type="text"></span>
                                        </label></p>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div role="form" class="wpcf7">
                                        <p><label> Cidade<br>
                                            <span class="wpcf7-form-control-wrap your-name">
                                                <input name="cidade" value="<?php echo $perfil->cidade; ?>" size="40"
                                                       class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required"
                                                       type="text"></span>
                                        </label></p>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div role="form" class="wpcf7">
                                        <p><label> Estado<br>
                                            <span class="wpcf7-form-control-wrap your-name">
                                                <input name="estado" value="<?php echo $perfil->estado; ?>" size="40"
                                                       class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required"
                                                       type="text"></span>
                                        </label></p>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div role="form" class="wpcf7">
                                        <p><label> Complemento<br>
                                            <span class="wpcf7-form-control-wrap your-name">
                                                <input name="complemento" value="<?php echo $perfil->complemento; ?>" size="40"
                                                       class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required"
                                                       type="text"></span>
                                        </label></p>

                                        <?php if(isset($_GET['finalizar'])): $btn_submit = 'Salvar e Continuar'; else: $btn_submit = 'Salvar'; endif; ?>

                                        <p><input value="<?php echo $btn_submit; ?>" class="wpcf7-form-control wpcf7-submit" type="submit"></p>
                                </div>
                            </div>

                         </form>



                        </div>

                        <div class="single-product-policy">
                            <div class="azeroth-iconbox style-01">
                                <a href="<?php echo base_url('meus-pedidos') ?>">
                                <div class="iconbox-inner">
                                    <div class="icon">
                                        <span class="flaticon-truck"></span>
                                        <span class="flaticon-truck"></span>
                                    </div>
                                    <div class="content">
                                        <h4 class="title">Meus pedidos</h4>
                                        <div class="desc">Pedidos concluídos e em andamento.
                                        </div>
                                    </div>
                                </div>
                              </a>
                            </div>
                            
                            <div class="azeroth-iconbox style-01">
                                <a href="<?php echo base_url('meus-dados') ?>">
                                <div class="iconbox-inner">
                                    <div class="icon">
                                        <span class="flaticon-refresh-left-arrow"></span>
                                        <span class="flaticon-refresh-left-arrow"></span>
                                    </div>
                                    <div class="content">
                                        <h4 class="title">Minha conta</h4>
                                        <div class="desc">Atualize informações e endereço de entrega.
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>

                        </div>
                    </div>
                    
                </div>
            </div>
          
            
        </div>
    </div>
</div>

<script type="text/javascript">
      $(document).ready(function(){

         $('[name=telefone]').mask('(00) 0 0000-0000');
         $('[name=cpf]').mask('000.000.000-00', {reverse: true});
         $('[name=cep]').mask('00000-000');


         function limpa_formulário_cep() {
                    // Limpa valores do formulário de cep.
                    $("[name=rua]").val("");
                    $("[name=bairro]").val("");
                    $("[name=cidade]").val("");
                    $("[name=estado]").val("");
                    $("#ibge").val("");
                }

                //Quando o campo cep perde o foco.
                $("[name=cep]").blur(function() {

                    //Nova variável "cep" somente com dígitos.
                    var cep = $(this).val().replace(/\D/g, '');

                    //Verifica se campo cep possui valor informado.
                    if (cep != "") {

                        //Expressão regular para validar o CEP.
                        var validacep = /^[0-9]{8}$/;

                        //Valida o formato do CEP.
                        if(validacep.test(cep)) {

                            //Preenche os campos com "..." enquanto consulta webservice.
                            $("[name=rua]").val("");
                            $("[name=bairro]").val("");
                            $("[name=cidade]").val("");
                            $("[name=estado]").val("");
                            $("[name=ibge]").val("");

                            //Consulta o webservice viacep.com.br/
                            $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                                if (!("erro" in dados)) {
                                    //Atualiza os campos com os valores da consulta.
                                    $("[name=rua]").val(dados.logradouro);
                                    $("[name=bairro]").val(dados.bairro);
                                    $("[name=cidade]").val(dados.localidade);
                                    $("[name=estado]").val(dados.uf);
                                } //end if.
                                else {
                                    //CEP pesquisado não foi encontrado.
                                    limpa_formulário_cep();
                                    swal("CEP não encontrado.");
                                }
                            });
                        } //end if.
                        else {
                            //cep é inválido.
                            limpa_formulário_cep();
                            swal("Formato de CEP inválido.");
                        }
                    } //end if.
                    else {
                        //cep sem valor, limpa formulário.
                        limpa_formulário_cep();
                    }
                });


      });
  </script>