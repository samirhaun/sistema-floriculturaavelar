<div class="banner-wrapper no_background">
    <div class="banner-wrapper-inner">
        <nav class="woocommerce-breadcrumb">
          <span>
            <a href="<?php echo base_url() ?>meus-dados?finalizar=true">
                1
            <span class="flaticon-user"></span>
            Identificação
            </a>
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

          <span style="color: #000096;font-size: 16px;">
            3
            <span class="flaticon-truck"></span>
            Finalizar pedido
          </span>

        </nav>
    </div>
</div>
<main class="site-main  main-container no-sidebar">
    <div class="container">
        <div class="row">
            <div class="main-content col-sm-12">
                <div class="page-main-content">
                    <div class="woocommerce">
                        <div class="woocommerce-notices-wrapper"></div>
                        
                        <form name="checkout" method="post" action="<?php echo base_url() ?>loja/finalizar" class="checkout woocommerce-checkout"
                              action="" enctype="multipart/form-data"
                              novalidate="novalidate">
                            <div class="col2-set" id="customer_details">
                                <div class="col-1">
                                    <div class="woocommerce-billing-fields">
                                        <h3>Endereço para entrega</h3>
                                        <div class="woocommerce-billing-fields__field-wrapper">
                                            <!-- <p class="form-row form-row-first validate-required"
                                               id="billing_first_name_field" data-priority="10"><label
                                                    for="billing_first_name" class="">Nome&nbsp;<abbr
                                                    class="required" title="required">*</abbr></label><span
                                                    class="woocommerce-input-wrapper"><input type="text"
                                                                                             class="input-text "
                                                                                             name="billing_first_name"
                                                                                             id="billing_first_name"
                                                                                             placeholder="" value=""
                                                                                             autocomplete="given-name"></span>
                                            </p>
                                            <p class="form-row form-row-last validate-required"
                                               id="billing_last_name_field" data-priority="20"><label
                                                    for="billing_last_name" class="">Sobrenome&nbsp;<abbr
                                                    class="required" title="required">*</abbr></label><span
                                                    class="woocommerce-input-wrapper"><input type="text"
                                                                                             class="input-text "
                                                                                             name="billing_last_name"
                                                                                             id="billing_last_name"
                                                                                             placeholder="" value=""
                                                                                             autocomplete="family-name"></span>
                                            </p> -->

                                            <p class="form-row form-row-wide address-field validate-postcode"
                                               id="billing_postcode_field" data-priority="65"
                                               data-o_class="form-row form-row-wide address-field validate-postcode">
                                                <label for="cep" class="">CEP&nbsp;<span
                                                        class="optional">(opcional)</span></label><span
                                                    class="woocommerce-input-wrapper"><input type="text" 
                                                                                             class="input-text "
                                                                                             name="cep"
                                                                                             id="cep"
                                                                                             placeholder="" value="<?php echo $usuario->cep ?>"
                                                                                             autocomplete="postal-code"></span>
                                            </p>
                                            
                                        
                                            <p class="form-row form-row-wide address-field validate-required"
                                               id="billing_address_1_field" data-priority="50"><label
                                                    for="rua" class="">Rua&nbsp;<abbr
                                                    class="required" title="required">*</abbr></label><span
                                                    class="woocommerce-input-wrapper"><input type="text"
                                                                                             class="input-text "
                                                                                             name="rua"
                                                                                             id="rua"
                                                                                             placeholder="Rua"
                                                                                             value="<?php echo $usuario->rua ?>"
                                                                                             autocomplete="address-line1"
                                                                                             data-placeholder="Rua"></span>
                                            </p>
                                            <p class="form-row form-row-wide address-field validate-required"
                                               id="billing_address_1_field" data-priority="50"><label
                                                    for="numero" class="">Número&nbsp;<abbr
                                                    class="required" title="required">*</abbr></label><span
                                                    class="woocommerce-input-wrapper"><input type="text"
                                                                                             class="input-text "
                                                                                             name="numero"
                                                                                             id="numero"
                                                                                             placeholder="Número"
                                                                                             value="<?php echo $usuario->numero  ?>"
                                                                                             autocomplete="address-line1"
                                                                                             data-placeholder="Número"></span>
                                            </p>
                                            <p class="form-row form-row-wide address-field validate-required"
                                               id="billing_address_1_field" data-priority="50"><label
                                                    for="bairro" class="">Bairro&nbsp;<abbr
                                                    class="required" title="required">*</abbr></label><span
                                                    class="woocommerce-input-wrapper"><input type="text"
                                                                                             class="input-text "
                                                                                             name="bairro"
                                                                                             id="bairro"
                                                                                             placeholder="Bairro"
                                                                                             value="<?php echo $usuario->bairro ?>"
                                                                                             autocomplete="address-line1"
                                                                                             data-placeholder="Bairro"></span>
                                            </p>
                                            <p class="form-row form-row-wide address-field" id="billing_address_2_field"
                                               data-priority="60" ><label for="complemento"
                                                                                                class="screen-reader-text">Complemento&nbsp;<span
                                                        class="optional">(opcional)</span></label><span
                                                    class="woocommerce-input-wrapper"><input type="text"
                                                                                             class="input-text "
                                                                                             name="complemento"
                                                                                             id="complemento"
                                                                                             placeholder="Complemento (opcional)"
                                                                                             value="<?php echo $usuario->complemento  ?>"
                                                                                             autocomplete="address-line2"
                                                                                             data-placeholder="Complemento. (opcional)"></span>
                                            </p>
                                            
                                            <p class="form-row form-row-wide address-field validate-required"
                                               id="billing_city_field" data-priority="70"
                                               data-o_class="form-row form-row-wide address-field validate-required">
                                                <label for="cidade" class="">Cidade&nbsp;<abbr
                                                        class="required" title="required">*</abbr></label><span
                                                    class="woocommerce-input-wrapper"><input type="text"
                                                                                             class="input-text "
                                                                                             name="cidade"
                                                                                             id="cidade"
                                                                                             placeholder="" value="<?php echo $usuario->cidade ?>"
                                                                                             autocomplete="address-level2"></span>
                                            </p>
                                            <p class="form-row form-row-wide address-field validate-required"
                                               id="billing_city_field" data-priority="70"
                                               data-o_class="form-row form-row-wide address-field validate-required">
                                                <label for="estado" class="">Estado&nbsp;<abbr
                                                        class="required" title="required">*</abbr></label><span
                                                    class="woocommerce-input-wrapper"><input type="text"
                                                                                             class="input-text "
                                                                                             name="estado"
                                                                                             id="estado"
                                                                                             placeholder="" value="<?php echo $usuario->estado ?>"
                                                                                             autocomplete="address-level2"></span>
                                            </p>
                                            
                                            <p class="form-row form-row-wide validate-required validate-phone"
                                               id="billing_phone_field" data-priority="100"><label for="telefone"
                                                                                                   class="">Telefone&nbsp;<abbr
                                                    class="required" title="required">*</abbr></label><span
                                                    class="woocommerce-input-wrapper"><input type="tel"
                                                                                             class="input-text "
                                                                                             name="telefone"
                                                                                             id="telefone"
                                                                                             placeholder="" value="<?php echo $usuario->telefone ?>"
                                                                                             autocomplete="tel"></span>
                                            </p>
                                            <p class="form-row form-row-wide validate-required validate-email"
                                               id="billing_email_field" data-priority="110"><label for="billing_email"
                                                                                                   class="">Email
                                                &nbsp;<abbr class="required"
                                                                   title="required">*</abbr></label><span
                                                    class="woocommerce-input-wrapper"><input type="email"
                                                                                             disabled=""
                                                                                             class="input-text "
                                                                                             id="billing_email"
                                                                                             placeholder="" value="<?php echo $usuario->email ?>"
                                                                                             autocomplete="email username"></span>
                                            </p></div>
                                    </div>
                                    <!-- <div class="woocommerce-account-fields">
                                        <p class="form-row form-row-wide create-account woocommerce-validated">
                                            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                                                <input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox"
                                                       id="createaccount" type="checkbox" name="createaccount"
                                                       value="1"> <span>Create an account?</span>
                                            </label>
                                        </p>
                                    </div> -->
                                </div>
                                <div class="col-2">
                                    <div class="woocommerce-shipping-fields">
                                    </div>
                                    <div class="woocommerce-additional-fields">
                                        <h3>Informações adicionais</h3>
                                        <div class="woocommerce-additional-fields__field-wrapper">
                                            <p class="form-row notes" id="order_comments_field" data-priority=""><label
                                                    for="observacoes" class="">Observações&nbsp;<span
                                                    class="optional">(opcional)</span></label><span
                                                    class="woocommerce-input-wrapper"><textarea name="observacoes"
                                                                                                class="input-text "
                                                                                                id="observacoes"
                                                                                                placeholder="Caso deseje adicionar alguma obsevação ao seu pedido."
                                                                                                rows="2"
                                                                                                cols="5"></textarea></span>
                                            </p></div>
                                    </div>
                                </div>
                            </div>
                            <h3 id="order_review_heading">Seu pedido</h3>
                            <div id="order_review" class="woocommerce-checkout-review-order">
                                <table class="shop_table woocommerce-checkout-review-order-table">
                                    <thead>
                                    <tr>
                                        <th class="product-name">Produto</th>
                                        <th class="product-total">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>


                                    <?php if(isset($produtos)): $total_carrinho = 0; ?>
                                    <?php foreach ($produtos as $produto): ?>

                                    <tr class="cart_item">
                                        <td class="product-name" style="font-size: 12px;">
                                            <?php echo $produto->titulo ?> 
                                            <br>
                                            <strong class="product-quantity">×
                                            <?php echo $produto->quantidade ?></strong>
                                        </td>
                                        <td class="product-total">
                                            <span class="woocommerce-Price-amount amount"><span
                                                    class="woocommerce-Price-currencySymbol">R$</span><?php echo number_format($produto->valor * $produto->quantidade, 2, ',', '.')  ?></span></td>
                                    </tr>

                                    <?php $total_carrinho +=  $produto->valor*$produto->quantidade; ?>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                    
                                    
                                    </tbody>
                                    <tfoot>
                                    <tr class="cart-subtotal">
                                        <th>Subtotal</th>
                                        <td><span class="woocommerce-Price-amount amount"><span
                                                class="woocommerce-Price-currencySymbol">R$</span><?php echo number_format($total_carrinho, 2, ',', '.')  ?></span></td>
                                    </tr>
                                    <tr class="cart-subtotal">
                                        <th>Taxa de entrega</th>
                                        <td><span class="woocommerce-Price-amount amount"><span
                                                class="woocommerce-Price-currencySymbol">R$</span>7,00</span></td>
                                    </tr>
                                   <!--  <tr class="cart-subtotal">
                                        <th>Cupom de desconto</th>
                                        <td><span class="woocommerce-Price-amount amount"><span
                                                class="woocommerce-Price-currencySymbol">-R$</span>0,00</span></td>
                                    </tr> -->
                                    <tr class="order-total">
                                        <th>Total</th>
                                        <td><strong><span class="woocommerce-Price-amount amount"><span
                                                class="woocommerce-Price-currencySymbol">R$</span><?php echo number_format($total_carrinho + 7, 2, ',', '.')  ?></span></strong>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                                <!-- <input type="hidden" name="lang" value="en"> -->
                                <div id="payment" class="woocommerce-checkout-payment">
                                    <ul class="wc_payment_methods payment_methods methods">
                                        <li class="wc_payment_method payment_method_bacs">
                                            <input id="payment_method_bacs" type="radio" class="input-radio"
                                                   name="payment_method" value="1" checked="checked"
                                                   data-order_button_text="">
                                            <label for="payment_method_bacs">
                                                Entrega </label>
                                                <p style="padding-top: 10px;">
                                                    <select title="product_cat" name="forma_pagamento" class="orderby">
                                                        <option value="">Selecione a forma de pagamento</option>
                                                        <option value="1">Dinheiro</option>
                                                        <option value="2">Cartão de crédito/débito</option>
                                                    </select>
                                                </p>
                                            <div class="payment_box payment_method_bacs">

                                                <p>Pedidos são confirmados dentro de um prazo de até 25 minutos, e entregas são realizadas em até 120 minutos após a efetivação do pedido</p>
                                            </div>
                                        </li>
                                        <li class="wc_payment_method payment_method_cheque">
                                            <input id="payment_method_cheque" type="radio" class="input-radio"
                                                   name="payment_method" value="2" data-order_button_text="">
                                            <label for="payment_method_cheque">
                                                Retirar na loja </label>
                                            <div class="payment_box payment_method_cheque" style="display:none;">
                                                <p>retirar</p>
                                            </div>
                                        </li>
                                        
                                        
                                    </ul>
                                    <div class="form-row place-order">
                                        
                                        <div class="woocommerce-terms-and-conditions-wrapper">
                                            <div class="woocommerce-privacy-policy-text"><p>Caso queira visualizar nossa política de entrega e de retirada de pedidos na loja  <a
                                                        href="#"
                                                        class="woocommerce-privacy-policy-link" target="_blank">clique aqui</a>.</p>
                                            </div>
                                        </div>
                                        <button type="submit" class="button alt"
                                                id="place_order" value="Fazer pedido" data-value="Fazer pedido">Fazer pedido
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


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
  </script>