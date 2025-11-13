

            <!-- Breadcrumb Area start -->

            <section class="breadcrumb-area">

                <div class="container">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="breadcrumb-content">

                                <h1 class="breadcrumb-hrading">Meus pedidos</h1>

                                <ul class="breadcrumb-links">

                                    <li><a href="<?php echo base_url() ?>">Home</a></li>

                                    <li>Meus pedidos</li>

                                </ul>

                            </div>

                        </div>

                    </div>

                </div>

            </section>

            <!-- Breadcrumb Area End -->

            <!-- cart area start -->

            <div class="cart-main-area mtb-60px">

                <div class="container">

                    <h3 class="cart-page-title">Meus pedidos</h3>







                    <!-- <span style="cursor: pointer;" title="Receber via pagseguro" id="btn_pagseguro">

           <img src="<?= base_url(); ?>assets/img/pagseguro.jpg"  style="margin-left:5px;"/>

        </span> -->





                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">

                            <form action="#">

                                <div class="table-content table-responsive cart-table-content">

                                    <table>

                                        <thead>

                                            <tr>

                                                <th></th>

                                                <th>Descrição</th>

                                                <th>Preço</th>

                                                <th>Qtd</th>

                                                <th>Total</th>

                                                <th>Forma de pagamento</th>

                                                <th>Status pagamento</th>

                                                <th>Status pedido</th>

                                            </tr>

                                        </thead>

                                        <tbody>







                                        <?php 



                                        if(!empty($pedidos)): 





                                        foreach ($pedidos as $pedido):



                                        ?>

                                            <tr>

                                                <td class="product-thumbnail">

                                                <?php 



                                                if(!empty($pedido->produtos)): 



                                                    foreach ($pedido->produtos as $produto):



                                                ?>

                                                    <a href="#"><img src="<?php echo base_url() ?>assets/images/produtos/<?php echo $produto->imagem ?>" alt="" style="width: 50px;margin-bottom:7px;" /></a>



                                                    <?php 



                                        endforeach;



                                    endif; 



                                    ?>

                                                </td>

                                                <td class="product-name">

                                                <?php 



                                                if(!empty($pedido->produtos)): 



                                                    foreach ($pedido->produtos as $produto):



                                                ?>



                                                <?php echo character_limiter($produto->titulo, 40);  ?><br>



                                                <?php 



                                                    endforeach;



                                                endif; 



                                                ?>

                                                </td>

                                                <td class="product-price-cart">

                                                <?php 



                                                if(!empty($pedido->produtos)): 



                                                    foreach ($pedido->produtos as $produto):



                                                ?>



                                                R$<?php echo number_format($produto->valor, 2, ',', '.')  ?><br>



                                                <?php 



                                                    endforeach;



                                                endif; 



                                                ?>

                                                </td>

                                                <td class="product-quantity">

                                                <?php 



                                                if(!empty($pedido->produtos)): 



                                                    foreach ($pedido->produtos as $produto):



                                                ?>



                                                <?php echo $produto->quantidade ?><br>



                                                <?php 



                                                    endforeach;



                                                endif; 



                                                ?>

                                                </td>

                                                <td class="product-subtotal">R$<?php echo number_format($pedido->valor, 2, ',', '.')  ?></td>
                                                <td class="product-wishlist-cart">
                                                <?php 
                                                    if($pedido->status_pedido == 4 || $pedido->status_pedido == 5): 
                                                        echo '<span class="wishlist-in-stock">Cancelado</span>';
                                                    elseif($pedido->status_pedido == 0 
                                                    && $pedido->tipo_pagamento == 2
                                                    || $pedido->status_pedido == 1
                                                    && $pedido->tipo_pagamento == 2): 
                                                            echo '<span class="wishlist-in-stock">A escolher na loja</span>';
                                                    elseif($pedido->status_pedido == 0
                                                    && $pedido->tipo_pagamento == 1
                                                    && $pedido->forma_pagamento == 1
                                                    || $pedido->status_pedido == 1
                                                    && $pedido->tipo_pagamento == 1
                                                    && $pedido->forma_pagamento == 1): 
                                                                echo '<span class="wishlist-in-stock">Dinheiro</span>';
                                                    elseif($pedido->status_pedido == 0
                                                    && $pedido->tipo_pagamento == 1
                                                    && $pedido->forma_pagamento == 2
                                                    || $pedido->status_pedido == 1
                                                    && $pedido->tipo_pagamento == 1
                                                    && $pedido->forma_pagamento == 2): 
                                                                echo '<span class="wishlist-in-stock">Máquina de cartão</span>';
                                                    
                                                    elseif($pedido->tipo_pagamento == 1
                                                    && $pedido->forma_pagamento == 3): 
                                                        
                                                        
                                                            if($pedido->tipo_pagamento_pagseguro == 1):
                                                            echo '<span class="wishlist-in-stock">Cartão de crédito</span>';
                                                        elseif($pedido->tipo_pagamento_pagseguro == 2):
                                                                echo '<span class="wishlist-in-stock">Boleto</span>';
                                                        elseif($pedido->tipo_pagamento_pagseguro == 3):
                                                                    echo '<span class="wishlist-in-stock">Débito online</span>';
                                                       elseif($pedido->tipo_pagamento_pagseguro == 4):
                                                                        echo '<span class="wishlist-in-stock">Saldo pagseguro</span>';
                                                       elseif($pedido->tipo_pagamento_pagseguro == 5):
                                                                            echo '<span class="wishlist-in-stock">Oi pago</span>';
                                                        elseif($pedido->tipo_pagamento_pagseguro == 7):
                                                            echo '<span class="wishlist-in-stock">Depósito em conta</span>';
                                                        else:
                                                                echo '<span class="wishlist-in-stock">Aguardando</span>';
                                                       endif;
                                                   elseif($pedido->status_pedido == 3): 
                                                        echo '<span class="wishlist-in-stock">Realizado</span>';
        
                                                    endif;
                                                ?>
                                                </td>
                                                <td class="product-wishlist-cart">

                                                <?php 
                                            
                                            if($pedido->status_pedido == 4 || $pedido->status_pedido == 5): 
                                                echo '<span class="wishlist-in-stock">Cancelado</span>';
                                            elseif($pedido->status_pedido == 0 
                                            && $pedido->tipo_pagamento == 2
                                            || $pedido->status_pedido == 1
                                            && $pedido->tipo_pagamento == 2): 
                                                    echo '<span class="wishlist-in-stock">A realizar na loja</span>';
                                            elseif($pedido->status_pedido == 0
                                            && $pedido->tipo_pagamento == 1
                                            && $pedido->forma_pagamento == 1
                                            || $pedido->status_pedido == 1
                                            && $pedido->tipo_pagamento == 1
                                            && $pedido->forma_pagamento == 1): 
                                                        echo '<span class="wishlist-in-stock">A realizar na entrega</span>';
                                            elseif($pedido->status_pedido == 0
                                            && $pedido->tipo_pagamento == 1
                                            && $pedido->forma_pagamento == 2
                                            || $pedido->status_pedido == 1
                                            && $pedido->tipo_pagamento == 1
                                            && $pedido->forma_pagamento == 2): 
                                                        echo '<span class="wishlist-in-stock">A realizar na entrega</span>';

                                            elseif($pedido->tipo_pagamento == 1
                                            && $pedido->forma_pagamento == 3): 
                                                
                                                if($pedido->status_pagseguro == 0):
                                                    echo '<span class="wishlist-in-stock">Não iniciado</span>';
                                                elseif($pedido->status_pagseguro == 1):
                                                    echo '<span class="wishlist-in-stock">Aguardando</span>';
                                                elseif($pedido->status_pagseguro == 2):
                                                        echo '<span class="wishlist-in-stock">Em análise</span>';
                                                elseif($pedido->status_pagseguro == 3):
                                                            echo '<span class="wishlist-in-stock">Realizado</span>';
                                               elseif($pedido->status_pagseguro == 6):
                                                                echo '<span class="wishlist-in-stock">Devolvido</span>';
                                               elseif($pedido->status_pagseguro == 7):
                                                                    echo '<span class="wishlist-in-stock">Cancelado</span>';
                                               endif;

                                               elseif($pedido->status_pedido == 3): 
                                                echo '<span class="wishlist-in-stock">Realizado</span>';

                                            endif;



                                                ?>

                                                </td>

                                                <td class="product-wishlist-cart">

                                                    <!-- <a href="#">add to cart</a> -->



                                                    <?php 



                                            if($pedido->status_pedido == 0): echo '<span class="wishlist-in-stock">Aguardando confirmação</span>';

                                            elseif($pedido->status_pedido == 1 && $pedido->tipo_pagamento == 1): 

                                                echo '<span class="wishlist-in-stock icon-confirmado"><i class="ion-thumbsup icon-status" aria-hidden="true"></i><br>Confirmado</span>';

                                            elseif($pedido->status_pedido == 1 && $pedido->tipo_pagamento == 2): 

                                                    echo '<span class="wishlist-in-stock icon-confirmado"><i class="ion-thumbsup icon-status" aria-hidden="true"></i><br>Confirmado e aguardando retirada na loja</span>';

                                            elseif($pedido->status_pedido == 2): 

                                                echo '<span class="wishlist-in-stock icon-confirmado"><span class="fa fa-truck icon-status"></span></i><br> Saiu para entrega</span>';

                                            elseif($pedido->status_pedido == 3): 

                                                echo '<span class="wishlist-in-stock icon-concluido"><i class="fa fa-smile icon-status" aria-hidden="true"></i><br>Pedido concluído</span>';

                                            elseif($pedido->status_pedido == 4): 

                                                echo '<span class="wishlist-in-stock icon-cancelado"><i class="fa fa-frown icon-status" aria-hidden="true"></i><br>Pedido cancelado pela loja</span>';

                                            elseif($pedido->status_pedido == 5): 

                                                echo '<span class="wishlist-in-stock icon-cancelado"><i class="fa fa-frown icon-status" aria-hidden="true"></i><br>Pedido cancelado pelo cliente</span>';

                                            endif;



                                            ?>



                                                </td>

                                            </tr>

                                            



                                            <?php 





                                endforeach;



                            endif; 



                            ?>

                                            





                                        </tbody>

                                    </table>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

            <!-- cart area end -->





            <script type="text/javascript" src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js"> </script> 





            <script>



                         //CONFIRMAÇÃO DE CADASTRO

        <?php if (isset($_GET['efetuado_not_on'])):  ?>



        swal("Pedido realiado com sucesso!", "Em até 25 minutos o status do seu pedido será atualizado e você poderá acompanhá-lo pelo site. Obrigado por escolher Floricultura Jardins!", "success")



        <?php endif; ?>



        //PAGAMENTO PEDIDO

        <?php if (isset($_GET['pagar']) && isset($_GET['cod_ped'])):  ?>

            pagar_pedido("<?php echo $_GET['cod_ped'] ?>");

        <?php endif; ?>



               



               



                  



                   function pagar_pedido(pedido){



                         // e.preventDefault();



                    // var id = '';

                    // var obj = $(".parcelas_gerar:checked");

                    // if (obj.length > 0) {

                    //     obj.each(function() {

                    //         id += ';' + $(this).val();

                    //     });

                    //     id = id.substr(1);



                    // }



                    $.ajax({

                        url: '<?= base_url(); ?>loja/pagar_pelo_pagseguro',

                        type: 'POST',

                        data: {

                            id_pedido: pedido

                        },

                        beforeSend: function() {

                            //$('#' + id).html('<img src="<?= base_url() ?>assets/img/ajax-loader.gif" alt="Carregando" style="margin-top:50px; text-align: center;" />');

                        },

                        success: function(result) {

                        

                            if(result){



                        PagSeguroLightbox({



                        code: result



                        }, {



                        success : function(transactionCode) {



                            swal({



                            icon: "success",



                            title: "Obrigado pela compra!",



                        });



                        },



                        abort : function() {



                        }



                        });



                        }

                        



                        }

                    });



                   }

            </script>