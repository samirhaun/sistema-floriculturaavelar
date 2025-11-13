
            <!-- Breadcrumb Area start -->
            <section class="breadcrumb-area">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="breadcrumb-content">
                                <h1 class="breadcrumb-hrading">Login / Cadastrar</h1>
                                <ul class="breadcrumb-links">
                                    <li><a href="<?php echo base_url() ?>">Home</a></li>
                                    <li>Login / Cadastrar</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Breadcrumb Area End -->
            <!-- login area start -->
            <div class="login-register-area mb-60px mt-53px">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7 col-md-12 ml-auto mr-auto">
                            <div class="login-register-wrapper">
                                <?php if(isset($_GET['finalizar'])): ?>
                                <p style="text-align: center;font-size: 16px;">Carrinho > Faça o acesso para finalizar seu pedido</p>
                                <br>
                                <?php endif; ?>

                                <div class="login-register-tab-list nav">
                                    <a data-toggle="tab" href="#lg1" <?php if(empty($_GET['cadastrando'])): ?> class="active" <?php endif; ?>>
                                        <h4>Já sou cadastrado</h4>
                                    </a>
                                    <a data-toggle="tab" href="#lg2" <?php if(isset($_GET['cadastrando'])): ?> class="active" <?php endif; ?>>
                                        <h4>Quero me cadastrar</h4>
                                    </a>
                                </div>
                                <div class="tab-content">
                                    <div id="lg1" class="tab-pane <?php if(empty($_GET['cadastrando'])): ?> active <?php endif; ?>">
                                        <div class="login-form-container">
                                            <div class="login-register-form">
                                                <form action="<?php echo base_url('logar') ?>" method="post">
                                                <?php if(isset($_GET['finalizar'])): ?>

                                                <input type="hidden" name="finalizar" value="1">

                                                <?php endif; ?>
                                                    <input type="text" name="email" id="email" placeholder="Seu email" required="" />
                                                    <input type="password" name="password" id="password" placeholder="Sua senha" required="" />
                                                    <div class="button-box">
                                                        <div class="login-toggle-btn">
                                                            <!-- <input type="checkbox" />
                                                            <a class="flote-none" href="javascript:void(0)">Remember me</a> -->
                                                            <a href="#">Esqueceu sua senha?</a>
                                                        </div>
                                                        <button type="submit"><span>Acessar</span></button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="lg2" class="tab-pane <?php if(isset($_GET['cadastrando'])): ?> active <?php endif; ?>">
                                        <div class="login-form-container">
                                            <div class="login-register-form">
                                                <form action="<?php echo base_url('cadastrar-usuario') ?>" method="post">
                                                <?php if(isset($_GET['finalizar'])): ?>

                                                <input type="hidden" name="finalizar" value="1">

                                                <?php endif; ?>
                                                    <input type="text"  name="nome" id="nome" value="<?php echo set_value('nome'); ?>" required placeholder="Nome completo" />

                                                    <input name="email" value="<?php echo set_value('email'); ?>" required placeholder="Seu email" type="email" />

                                                    <input type="text"  name="telefone" id="" value="<?php echo set_value('telefone'); ?>" required placeholder="Seu telefone" />

                                                    <input type="password" name="senha" id="" value="" required placeholder="Senha para acesso" />
                                                    <input type="password" name="confirma_senha" id=""  value="" required placeholder="Repita sua senha" />

                                                    <p>Ao efetuar o cadastro, sua conta já estará habilitada para efetuar pedidos, caso queira completar seus dados, como endereço para entrega para facilitar na hora de finalizar o peido, acesse seu perfil.</p>
                                                    <br>

                                                    
                                                    <div class="button-box">
                                                        <button type="submit"><span>Confirmar Cadastro</span></button>
                                                    </div>

                                                    

                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- login area end -->

  <script type="text/javascript">
      $(document).ready(function(){

         $('[name=telefone]').mask('(00) 0 0000-0000');


         /*ERRO LOGIN*/
         <?php if(isset($_GET['error_login'])): ?>
         swal("Erro ao fazer login", "E-mail ou senha incorretos");
         <?php endif; ?>

      });
  </script>
            