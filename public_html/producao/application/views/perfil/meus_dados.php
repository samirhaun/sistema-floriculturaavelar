
            <!-- Breadcrumb Area start -->
            <section class="breadcrumb-area">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="breadcrumb-content">
                                <h1 class="breadcrumb-hrading">Minha conta</h1>
                                <ul class="breadcrumb-links">
                                    <li><a href="<?php echo base_url() ?>">Home</a></li>
                                    <li>Minha conta</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Breadcrumb Area End -->
            <!-- account area start -->
            <div class="checkout-area mtb-60px">
                <div class="container">
                    <div class="row">
                        <div class="ml-auto mr-auto col-lg-9">
                            <div class="checkout-wrapper">
                                <div id="faq" class="panel-group">
                                <?php if(isset($_GET['finalizar'])): ?>
                                    <p style="text-align: center;font-size:16px;"> Complete seu cadastro caso deseje, e clique em "Salvar e continuar" abaixo para finalizar o carrinho</p>
                                    <br>
                                    <?php endif; ?>

                                    <div class="panel panel-default single-my-account">
                                        <div class="panel-heading my-account-title">
                                            <h3 class="panel-title"><span>1 .</span> <a data-toggle="collapse" data-parent="#faq" href="#my-account-1">Meus dados </a></h3>
                                        </div>
                                        <div id="my-accosunt-1" class="panel-collapse collapse show">

                                        <form  action="<?php echo base_url('salvar-editar-perfil') ?>" method="post" >

                                        <?php if(isset($_GET['finalizar'])): ?>

                                        <input type="hidden" name="finalizar" value="1">

                                        <?php endif; ?>

                                            <div class="panel-body">
                                                <div class="myaccount-info-wrapper">
                                                    <div class="account-info-wrapper">
                                                        <h4>Dados pessoais</h4>
                                                        <h5>*Campos obrigatórios</h5>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6">
                                                            <div class="billing-info">
                                                                <label>Nome completo *</label>
                                                                <input type="text" name="nome" value="<?php echo $perfil->nome; ?>" required />
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6">
                                                            <div class="billing-info">
                                                                <label>Email (não editável)</label>
                                                                <input type="text" value="<?php echo $perfil->email; ?>" readonly="readonly"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6">
                                                            <div class="billing-info">
                                                                <label>Telefone *</label>
                                                                <input type="text" name="telefone" value="<?php echo $perfil->telefone; ?>" required />
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6">
                                                            <div class="billing-info">
                                                                <label>CPF</label>
                                                                <input type="text" name="cpf" value="<?php echo $perfil->cpf; ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3">
                                                            <div class="billing-info">
                                                                <label>CEP</label>
                                                                <input type="text" name="cep" value="<?php echo $perfil->cep; ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-md-6">
                                                            <div class="billing-info">
                                                                <label>Rua</label>
                                                                <input type="text" name="rua" value="<?php echo $perfil->rua; ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3 col-md-3">
                                                            <div class="billing-info">
                                                                <label>Número</label>
                                                                <input type="text" name="numero" value="<?php echo $perfil->numero; ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="billing-info">
                                                                <label>Bairro</label>
                                                                <input type="text" name="bairro" value="<?php echo $perfil->bairro; ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="billing-info">
                                                                <label>Cidade</label>
                                                                <input type="text" name="cidade" value="<?php echo $perfil->cidade; ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="billing-info">
                                                                <label>Estado</label>
                                                                <input type="text" name="estado" value="<?php echo $perfil->estado; ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-12 col-md-12">
                                                            <div class="billing-info">
                                                                <label>Complemento</label>
                                                                <input type="text"  name="complemento" value="<?php echo $perfil->complemento; ?>" />
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="billing-back-btn">
                                                        <div class="billing-back">
                                                            <a href="#"><i class="fa fa-arrow-up"></i> topo</a>
                                                        </div>

                                                        <?php if(isset($_GET['finalizar'])): $btn_submit = 'Salvar e Continuar'; else: $btn_submit = 'Salvar dados'; endif; ?>

                                                        <div class="billing-btn">
                                                            <button type="submit"><?php echo $btn_submit; ?></button>
                                                        </div>
                                                    </div>
                                                </div>
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
            <!-- account area end -->


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