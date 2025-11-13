<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Floricultura Jardins</title>

    <link href="<?php echo base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="<?php echo base_url() ?>assets/css/animate.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/css/style.css" rel="stylesheet">

</head>

<body class="white-bg">
    <div class="wrapper wrapper-content p-xl">
    <h2 class="text-center" style="margin-top: 0px;">Floricultura Jardins</h2>
        <?php if($pedido): ?>
        <div class="ibox-content">
            <div class="row">
            <div class="col-sm-6">
                                <h5>Cliente:</h5>
                                <address>
                                    <strong>
                                        <?php
                                        if($pedido->origem == 1):
                                            echo ($pedido->cliente_pedido) ;
                                            if(!empty($pedido->pessoa_entrega)): echo ' - Recebedor: '.$pedido->pessoa_entrega; endif;
                                        else:
                                            echo $pedido->nome_cliente;
                                        endif;
                                         ?>
                                    </strong>

                                    <br/>
<span><strong>Data entrega:</strong> <?php echo inverteData($pedido->data_entrega, '/')  ?></span>
                                    <br/>

                                    <span><strong>Hora entrega:</strong> <?php echo $pedido->hora_entrega ?></span>
                                    <br/>
                                    
                                    <br>
                                    CEP: <?php echo $pedido->cep_entrega ?>,<br>
                                    <?php echo $pedido->rua_entrega .', '. $pedido->numero_entrega .'<br>'. $pedido->bairro_entrega ?><br>
                                    <?php echo $pedido->cidade_entrega .', '. $pedido->estado_entrega .',<br>'. $pedido->complemento_entrega  ?><br>
                                    <?php 
                                        if(!empty($pedido->obs)): echo 'Obs:'.$pedido->obs; endif;
                                     ?>
                                    <!-- <abbr title="Telefones">Tel:</abbr> 
                                    <?php
                                        if($pedido->origem == 1):
                                            echo $pedido->cliente_telefone;
                                        else:
                                            echo $pedido->telefone_1 .' / '. $pedido->telefone_2;
                                        endif;
                                         ?>
\                                </address>-->
                            </div>
                            <div class="col-sm-6">
                                <h5>Contato:</h5>


                                    Email: 
                                    <?php
                                        if($pedido->origem == 1):
                                            echo $pedido->cliente_email;
                                        else:
                                            echo $pedido->email_cliente;
                                        endif;
                                         ?>
                                    <br>
                                    Telefone: 
                                        <?php
                                        if($pedido->origem == 1):
                                            echo $pedido->cliente_telefone;
                                        else:
                                            echo $pedido->cliente_telefone;
                                        endif;
                                         ?>
                                         <br>
                                    <br>
                                    <!-- <abbr title="Telefones">Tel:</abbr> <?php echo $pedido->telefone_1 .' / '. $pedido->telefone_2 ?>
\                                </address>-->
                            </div>

                            <div class="col-sm-6 text-right">
                                <h4>Nº do pedido.</h4>
                                <h4 class="text-navy"><?php echo $pedido->id ?></h4>

                                <p>


                                <?php
                                if($pedido->origem == 1):
                                    ?>

                                    <span><strong>Origem:</strong> Balcão</span>
                                    <br/>
                                    <span><strong>Forma de pagamento:</strong> 
                                    <?php 
                                    if($pedido->forma_pagamento_balcao == 1): 
                                        echo '<span class="wishlist-in-stock">Dinheiro</span>';
                                elseif($pedido->forma_pagamento_balcao == 2): 
                                            echo '<span class="wishlist-in-stock">Débito</span>';
                                            elseif($pedido->forma_pagamento_balcao == 3): 
                                                echo '<span class="wishlist-in-stock">Crédito 1x</span>';
                                                elseif($pedido->forma_pagamento_balcao == 4): 
                                                    echo '<span class="wishlist-in-stock">Crédito 2x</span>';
                                                    elseif($pedido->forma_pagamento_balcao == 5): 
                                                        echo '<span class="wishlist-in-stock">Crédito 3x</span>';
                                                        elseif($pedido->forma_pagamento_balcao == 6): 
                                                            echo '<span class="wishlist-in-stock">Crédito 4x</span>';
                                                            elseif($pedido->forma_pagamento_balcao == 7): 
                                                                echo '<span class="wishlist-in-stock">Duplicata</span>';
                                                                elseif($pedido->forma_pagamento_balcao == 8): 
                                                                    echo '<span class="wishlist-in-stock">Cheque</span>';
                                                                    elseif($pedido->forma_pagamento_balcao == 9): 
                                                                        echo '<span class="wishlist-in-stock">Pix</span>';
                                                                endif;
                                    ?></span>
                                    <br/>
                                    <span><strong>Vendedor:</strong> <?php echo $pedido->vendedor_pedido ?></span>
                                    <br/>
                                    
                                <?php
                                else:
                                    ?>
                                    <span><strong>Origem:</strong> Site</span>
                                    <br/>
                                    <span><strong>Tipo de pedido:</strong> <?php echo ($pedido->tipo_pagamento == '1') ? 'Entrega em residência' : 'Retirar na loja' ?></span>
                                    <br/>
                                    <span><strong>Forma de pagamento:</strong> 
                                    <?php 
                                    if($pedido->tipo_pagamento == 2): 
                                        echo '<span class="wishlist-in-stock">A escolher na loja</span>';
                                elseif($pedido->tipo_pagamento == 1
                                && $pedido->forma_pagamento == 1): 
                                            echo '<span class="wishlist-in-stock">Dinheiro</span>';
                                elseif($pedido->tipo_pagamento == 1
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

                                endif;
                                    ?>
                                   </span>
                                    
                                    <?php
                                endif;
                                ?>


                                    
                                </p>
                            </div>
            </div>
   
                <div class="table-responsive m-t">
  <table class="table invoice-table">
                                <thead>
                                    <tr>
                                        <th>Itens</th>
                                        <th>ID</th>
                                        <th>Quantidade</th>
                                        <th>Preço</th>
                                        <th>Total item</th>
                                    </tr>
                                </thead>
                                <tbody>

                                        <?php 
                                        $total_produtos = 0; 
                                        foreach ($produtos as $key => $produto): 
                                         ?>
                                        <tr>
                                            <td style="width: 70%;">
                                                <div>
                                                    <strong><?php echo $produto->titulo ?> <?php echo isset($produto->tamanho_nome )? 'TAM:'.$produto->tamanho_nome : '' ?></strong>
                                                </div>
                                                <small><?php echo $produto->descricao ?></small>
                                            </td>
                                            <td><?php echo $produto->id ?></td>
                                            <td><?php echo $produto->quantidade ?></td>
                                          
                                            <td>R$ <?php echo number_format($produto->valor, 2, ',', '.'); ?></td>
                                            <td>R$ <?php echo number_format($produto->valor*$produto->quantidade, 2, ',', '.'); ?></td>
                                        </tr>
                                    <?php 
                                    $total_produtos = $total_produtos + $produto->valor * $produto->quantidade;
                                    endforeach; 
                                    ?>
                                </tbody>
                            </table>
            </div>

            <table class="table invoice-total">
                            <tbody>
                            <tr>
                                <td><strong>Sub Total: </strong></td>
                                <td>R$ <?php echo number_format($total_produtos, 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>+FRETE: </strong></td>
                                <td>R$ <?php echo number_format($pedido->valor_frete, 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>-DESCONTO: </strong></td>
                                <td>R$ <?php echo number_format($pedido->valor_desconto, 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>TOTAL :</strong></td>
                                <td>R$ <?php echo number_format($pedido->valor_total, 2, ',', '.'); ?></td>
                            </tr>
                            </tbody>
                        </table>
                <!-- <div class="text-right">
                    <button class="btn btn-primary"><i class="fa fa-dollar"></i> Make A Payment</button>
                </div> -->

                <!-- <div class="well m-t"><strong>Comments</strong>
                    It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less
                </div> -->
        </div>
        <?php endif ?>
    </div>

    <!-- Mainly scripts -->
    <script src="<?php echo base_url() ?>assets/js/jquery-2.1.1.js"></script>
    <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>

    <!-- Custom and plugin javascript -->
    <!-- <script src="<?php echo base_url() ?>assets/js/inspinia.js"></script> -->

    <script type="text/javascript">
        window.print();
    </script>

</body>

</html>
