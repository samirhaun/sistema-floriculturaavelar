<?php if(isset($_GET['finalizar'])): ?>
<div class="banner-wrapper no_background">
    <div class="banner-wrapper-inner">
        <nav class="woocommerce-breadcrumb">
          <span style="color: #000096;font-size: 16px;">
            <span class="flaticon-user"></span>
            Identificação
          </span>

          <i class="fa fa-angle-right"></i>

          <span>
            <span class="flaticon-online-shopping-cart"></span>
            Meu Carrinho
          </span>

          <i class="fa fa-angle-right"></i>

          <span>
            <span class="flaticon-truck"></span>
            Finalizar pedido
          </span>

        </nav>
    </div>
</div>
<?php endif; ?>

<main class="site-main  main-container no-sidebar">
    <div class="container">
        <div class="row">
            <div class="main-content col-sm-12">
                <div class="page-main-content">
                    <div class="woocommerce">
                        <div class="woocommerce-notices-wrapper"></div>
                        <div class="u-columns col2-set" id="customer_login">
                            <div class="u-column1 col-1">
                                <h2>Login</h2>
                                <form class="woocommerce-form woocommerce-form-login login" action="<?php echo base_url('logar') ?>" method="post">

                                    <?php if(isset($_GET['finalizar'])): ?>

                                      <input type="hidden" name="finalizar" value="1">

                                    <?php endif; ?>

                                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                        <label for="email">Seu email&nbsp;<span
                                                class="required">*</span></label>
                                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text"
                                               name="email" id="email" autocomplete="email" value="" required=""></p>
                                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                        <label for="password">Senha&nbsp;<span class="required">*</span></label>
                                        <input class="woocommerce-Input woocommerce-Input--text input-text"
                                               type="password" name="password" id="password"
                                               autocomplete="current-password" required="">
                                    </p>
                                    <p class="form-row">
                                        
                                        <button type="submit" class="woocommerce-Button button">Entrar
                                        </button>
                                        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox inline">
                                            <input class="woocommerce-form__input woocommerce-form__input-checkbox"
                                                   name="rememberme" type="checkbox" id="rememberme" value="forever">
                                            <span>Lembrar me</span>
                                        </label>
                                    </p>
                                    <p class="woocommerce-LostPassword lost_password">
                                        <a href="my-account.htmllost-password/">Esqueceu sua senha?</a>
                                    </p>
                                </form>
                            </div>
                            <div class="u-column2 col-2">
                                <h2>Quero me cadastrar</h2>
                                <form method="post" action="<?php echo base_url('cadastrar-usuario') ?>" class="woocommerce-form woocommerce-form-register register">

                                    <?php if(isset($_GET['finalizar'])): ?>

                                      <input type="hidden" name="finalizar" value="1">

                                    <?php endif; ?>


                                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">

                                        <label for="reg_email">Nome completo&nbsp;<span
                                                class="required">*</span></label>
                                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text"
                                               name="nome" id="nome" value="<?php echo set_value('nome'); ?>" required></p>

                                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                        <label for="reg_email">Seu email&nbsp;<span
                                                class="required">*</span></label>
                                        <input type="email" class="woocommerce-Input woocommerce-Input--text input-text"
                                               name="email" id="reg_email" autocomplete="email" value="<?php echo set_value('email'); ?>" required></p>

                                               <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                        <label for="reg_email">Seu telefone&nbsp;<span
                                                class="required">*</span></label>
                                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text"
                                               name="telefone" id="" value="<?php echo set_value('telefone'); ?>" required></p>

                                               <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                        <label for="reg_email">Senha para acesso&nbsp;<span
                                                class="required">*</span></label>
                                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text"
                                               name="senha" id="" value="" required></p>

                                               <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                        <label for="reg_email">Repita sua senha&nbsp;<span
                                                class="required">*</span></label>
                                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text"
                                               name="confirma_senha" id=""  value="" required></p>

                                    <div class="woocommerce-privacy-policy-text"><p>Ao efetuar o cadastro, sua conta já estará habilitada para efetuar pedidos, caso queira completar seus dados, como endereço para entrega para facilitar na hora de finalizar o peido, acesse seu perfil.</p>
                                    </div>
                                    <p class="woocommerce-FormRow form-row">
                                        
                                        <button type="submit" class="woocommerce-Button button">Confirmar castro
                                        </button>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

  <script type="text/javascript">
      $(document).ready(function(){

         $('[name=telefone]').mask('(00) 0 0000-0000');


         /*ERRO LOGIN*/
         <?php if(isset($_GET['error_login'])): ?>
         swal("Erro ao fazer login", "E-mail ou senha incorretos");
         <?php endif; ?>

      });
  </script>