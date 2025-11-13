
            <!-- Breadcrumb Area start -->
            <section class="breadcrumb-area">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="breadcrumb-content">
                                <h1 class="breadcrumb-hrading">Finalizar compra</h1>
                                <ul class="breadcrumb-links">
                                    <li><a href="<?php echo base_url() ?>">Home</a></li>
                                    <li><a href="<?php echo base_url() ?>carrinho">Carrinho</a></li>
                                    <li>Finalizar compra</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Breadcrumb Area End -->
            <!-- checkout area start -->
            <form name="checkout" id="checkout" method="post" action="<?php echo base_url() ?>loja/finalizar">
            <div class="checkout-area mt-60px mb-40px">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="billing-info-wrap">
                                <h3>Endereço de entrega</h3>
                                <div class="row">

                                <div class="col-lg-4 col-md-4">
                                        <div class="billing-info mb-20px">
                                            <label>CEP *</label>
                                            <input type="text"  name="cep"
                                                                id="cep" 
                                                                value="<?php echo $usuario->cep ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-8">
                                        <div class="billing-info mb-20px">
                                            <label>Rua *</label>
                                            <input type="text" name="rua"
                                                                id="rua"
                                                                value="<?php echo $usuario->rua ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                        <div class="billing-info mb-20px">
                                            <label>Número *</label>
                                            <input type="text" name="numero"
                                                                                             id="numero"
                                                                                             value="<?php echo $usuario->numero  ?>" />
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="billing-info mb-20px">
                                            <label>Bairro *</label>
                                            <input type="text" name="bairro"
                                                                                             id="bairro"
                                                                                             value="<?php echo $usuario->bairro ?>"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="billing-info mb-20px">
                                            <label>Cidade *</label>
                                            <input type="text" name="cidade"
                                                                                             id="cidade"
                                                                                             value="<?php echo $usuario->cidade ?>"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="billing-info mb-20px">
                                            <label>Estado *</label>
                                            <input type="text" name="estado"
                                                                                             id="estado"
                                                                                             value="<?php echo $usuario->estado ?>"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="billing-info mb-20px">
                                            <label>Complemento (opcional)</label>
                                            <input type="text" name="complemento"
                                                                                             id="complemento"
                                                                                             
                                                                                             value="<?php echo $usuario->complemento  ?>"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="billing-info mb-20px">
                                            <label>Telefone *</label>
                                            <input type="text" name="telefone"
                                                                                             id="telefone"
                                                                                             placeholder="" value="<?php echo $usuario->telefone ?>"/>
                                        </div>
                                    </div>

                                    <!-- <div class="col-lg-12">
                                        <div class="billing-select mb-20px">
                                            <label>Country</label>
                                            <select>
                                                <option>Select a country</option>
                                                <option>Azerbaijan</option>
                                                <option>Bahamas</option>
                                                <option>Bahrain</option>
                                                <option>Bangladesh</option>
                                                <option>Barbados</option>
                                            </select>
                                        </div>
                                    </div> -->
                                   
                                    <!-- <div class="col-lg-12">
                                        <div class="billing-info mb-20px">
                                            <label>Town / City</label>
                                            <input type="text" />
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="billing-info mb-20px">
                                            <label>State / County</label>
                                            <input type="text" />
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="billing-info mb-20px">
                                            <label>Postcode / ZIP</label>
                                            <input type="text" />
                                        </div>
                                    </div> -->
                                    
                                </div>
                                <!-- <div class="checkout-account mb-50px">
                                    <input class="checkout-toggle2" type="checkbox" />
                                    <label>Create an account?</label>
                                </div>
                                <div class="checkout-account-toggle open-toggle2 mb-30">
                                    <input placeholder="Email address" type="email" />
                                    <input placeholder="Password" type="password" />
                                    <button class="btn-hover checkout-btn" type="submit">register</button>
                                </div> -->
                                <div class="additional-info-wrap">
                                    <h4>Observações</h4>
                                    <div class="additional-info">
                                        <label>Informações adicionais</label>
                                        <textarea name="observacoes"
                                                                                                id="observacoes"
                                                                                                placeholder="Caso deseje adicionar alguma obsevação ao seu pedido."></textarea>
                                    </div>
                                </div>
                                <!-- <div class="checkout-account mt-25">
                                    <input class="checkout-toggle" type="checkbox" />
                                    <label>Ship to a different address?</label>
                                </div> -->
                                <!-- <div class="different-address open-toggle mt-30">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="billing-info mb-20px">
                                                <label>First Name</label>
                                                <input type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="billing-info mb-20px">
                                                <label>Last Name</label>
                                                <input type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="billing-info mb-20px">
                                                <label>Company Name</label>
                                                <input type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="billing-select mb-20px">
                                                <label>Country</label>
                                                <select>
                                                    <option>Select a country</option>
                                                    <option>Azerbaijan</option>
                                                    <option>Bahamas</option>
                                                    <option>Bahrain</option>
                                                    <option>Bangladesh</option>
                                                    <option>Barbados</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="billing-info mb-20px">
                                                <label>Street Address</label>
                                                <input class="billing-address" placeholder="House number and street name" type="text" />
                                                <input placeholder="Apartment, suite, unit etc." type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="billing-info mb-20px">
                                                <label>Town / City</label>
                                                <input type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="billing-info mb-20px">
                                                <label>State / County</label>
                                                <input type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="billing-info mb-20px">
                                                <label>Postcode / ZIP</label>
                                                <input type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="billing-info mb-20px">
                                                <label>Phone</label>
                                                <input type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="billing-info mb-20px">
                                                <label>Email Address</label>
                                                <input type="text" />
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="your-order-area">
                                <h3>Detalhes do pedido</h3>
                                <div class="your-order-wrap gray-bg-4">
                                    <div class="your-order-product-info">
                                        <div class="your-order-top">
                                            <ul>
                                                <li>Produto</li>
                                                <li>Total</li>
                                            </ul>
                                        </div>
                                        <div class="your-order-middle">
                                            
                                            <ul>
                                            <?php if(isset($produtos)): $total_carrinho = 0; ?>
                                                 <?php foreach ($produtos as $produto): ?>
                                                <li><span class="order-middle-left"><?php echo $produto->titulo ?> X <?php echo $produto->quantidade ?></span> <span class="order-price">R$<?php echo number_format($produto->valor * $produto->quantidade, 2, ',', '.')  ?> </span></li>
                                                <?php $total_carrinho +=  $produto->valor*$produto->quantidade; ?>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                        <div class="your-order-bottom">
                                            <ul>
                                                <li class="your-order-shipping">Subtotal</li>
                                                <li>R$<?php echo number_format($total_carrinho, 2, ',', '.')  ?></li>
                                            </ul>
                                        </div>
                                        <div class="your-order-bottom">
                                            <ul>
                                                <li class="your-order-shipping">Frete</li>
                                                <li>R$7,00</li>
                                            </ul>
                                        </div>
                                        <div class="your-order-total">
                                            <ul>
                                                <li class="order-total">Total</li>
                                                <li>R$<?php echo number_format($total_carrinho + 7, 2, ',', '.')  ?></li>
                                            </ul>
                                        </div>
                                        
                                    </div>
                                    <!-- <div class="payment-method">
                                        <div class="payment-accordion element-mrg">
                                            <div class="panel-group" id="accordion">
                                                <div class="panel payment-accordion">
                                                    <div class="panel-heading" id="method-one">
                                                        <h4 class="panel-title">
                                                            <a data-toggle="collapse" data-parent="#accordion" href="#method1">
                                                                Forma de pagamento
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="method1" class="panel-collapse collapse show">
                                                        <div class="panel-body">
                                                            <p>Pedidos são confirmados dentro de um prazo de até 25 minutos, e entregas são realizadas em até 120 minutos após a efetivação do pedido.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel payment-accordion">
                                                    <div class="panel-heading" id="method-two">
                                                        <h4 class="panel-title">
                                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#method2">
                                                                Cartão de crédito na entrega
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="method2" class="panel-collapse collapse">
                                                        <div class="panel-body">
                                                            <p>Máquina levada até o estabelecimento.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel payment-accordion">
                                                    <div class="panel-heading" id="method-three">
                                                        <h4 class="panel-title">
                                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#method3">
                                                                Dinheiro na entrega
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="method3" class="panel-collapse collapse">
                                                        <div class="panel-body">
                                                            <p>Caso precise de troco, favor informar.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->

                                    <div class="billing-info-wrap">
                                <div class="row">

                                    <div class="col-lg-12 col-md-12">
                                        <div class="billing-info mb-20px">
                                            <label>Tipo de pedido *</label>
                                            <select onchange="verifica_tipo_pedido(this.value)" name="payment_method">
                                                <option value="1">Entrega</option>
                                                <option value="2">Retirar na loja (frete isento)</option>
                                            </select>
                                        </div>
                                    </div>
                                
                                   <div class="col-lg-12 col-md-12 div_forma_pgto">
                                        <div class="billing-info mb-20px">
                                            <label>Forma de pagamento *</label>
                                            <select onchange="verifica_forma_pgto(this.value)" name="forma_pagamento">
                                                <option value="3">Online</option>
                                                <option value="2">Máquina de cartão na entrega</option>
                                                <option value="1">Dinheiro na entrega</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-lg-12 col-md-12 precisa_troco" style="display: none;">
                                        <div class="billing-info mb-20px">
                                            <label>Precisa de troco?</label>
                                            <input type="text"  name="troco"
                                                                                             id="troco"
                                                                                             placeholder="quero troco para R$"/>
                                        </div>
                                    </div>





                                </div>
                                    </div>

                                </div>
                                <div class="Place-order mt-25">
                                    <a class="btn-hover" href="#" onclick="$('#checkout').submit()">Finalizar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
            <!-- checkout area end -->

            <script type="text/javascript">
      $(document).ready(function(){

         $('[name=telefone]').mask('(00) 0 0000-0000');
         $('[name=cep]').mask('00000-000');


         function limpa_formulário_cep() {
                    // Limpa valores do formulário de cep.
                    $("[name=rua]").val("");
                    $("[name=bairro]").val("");
                    $("[name=cidade]").val("");
                    $("[name=estado]").val("");
                    //$("#ibge").val("");
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
                            //$("[name=ibge]").val("");

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

      function verifica_tipo_pedido(valor){
          if(valor == 1){
            $('.div_forma_pgto').show();
          }else{
            $('.div_forma_pgto').hide();
          }
      }


      function verifica_forma_pgto(valor){
          if(valor == 1){
            $('.precisa_troco').show();
          }else{
            $('.precisa_troco').hide();
          }
      }

  </script>
        