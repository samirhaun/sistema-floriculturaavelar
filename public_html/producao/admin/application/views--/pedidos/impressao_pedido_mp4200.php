<!DOCTYPE html>
<html>
<head>
	<title>IMPRESSAO</title>

	<style type="text/css">

	@media print {
  	
  		#imprimir{
  			display: none;
  		}

	}
		html, body {
      /*  width: 5.5in; 
        height: 8.5in; 
        display: block;*/
        font-family: "Arial";
        font-size: 12px;
        /*font-size: auto; NOT A VALID PROPERTY */
         }

         #imprimir{
         	cursor: pointer;
         }
	</style>


	<script type="text/javascript">
		window.print();



		function imprimir(){
			window.print();
		}
	</script>

</head>
<body>

	<span style="margin-left: 50px;"> FLORICULURA JARDINS</span>
	<br>
	<span style="margin-left: 50px;"> CNPJ: 49.765.943/0001-02</span>
	<br>


	<br>

	<span>
		Data entrega: <?php echo inverteData($pedido->data_entrega, '/')  ?>
	</span>

	<span style="margin-left: 40px;">
		Hora: <?php echo $pedido->hora_entrega ?>
	</span>

	<br>
	<br>

	<span>
		Cliente:
	</span>
	<span style="margin-left: 20px;">
           <?php
        if($pedido->origem == 1):
            echo ($pedido->cliente_pedido) ;
            if(!empty($pedido->pessoa_entrega)): echo ' - Recebedor: '.$pedido->pessoa_entrega; endif;
        else:
            echo $pedido->nome_cliente;
        endif;
            ?>
    </span>

	<br><br>

	<span>
		Endereço entrega:
	</span>
    <br>
	<span>
    
                                    CEP: <?php echo $pedido->cep_entrega ?>,
                                    <?php echo $pedido->rua_entrega .', '. $pedido->numero_entrega .'<br>'. $pedido->bairro_entrega ?>
                                    <?php echo $pedido->cidade_entrega .', '. $pedido->estado_entrega .','. $pedido->complemento_entrega  ?><br>
                                    <?php 
                                        if(!empty($pedido->obs)): echo 'Obs:'.$pedido->obs; endif;
                                     ?>
    </span>

	<br><br>

	<span>
    <?php
                                if($pedido->origem == 1):
                                    ?>

                                    <span>Origem: Balcão</span>
                                    <br/>
                                    <span>Forma de pagamento:
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
                                                                endif;
                                    ?></span>
                                    <br/>
                                    <span>Vendedor: <?php echo $pedido->vendedor_pedido ?></span>
                                    <br/>
                                    
                                <?php
                                else:
                                    ?>
                                    <span>Origem: Site</span>
                                    <br/>
                                    <span>Tipo de pedido:<?php echo ($pedido->tipo_pagamento == '1') ? 'Entrega em residência' : 'Retirar na loja' ?></span>
                                    <br/>
                                    <span>Forma de pagamento:
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
	</span>



    <br>
    
    <table>
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>ID</th>
                                        <th>Qtd</th>
                                        <!-- <th>Preço</th>
                                        <th>Total item</th> -->
                                    </tr>
                                </thead>
                                <tbody>

                                        <?php 
                                        $total_produtos = 0; 
                                        foreach ($produtos as $key => $produto): 
                                         ?>
                                        <tr>
                                            <td>
                                                <div>
                                                    <?php echo $produto->titulo ?> <?php echo isset($produto->tamanho_nome )? 'TAM:'.$produto->tamanho_nome : '' ?>
                                                </div>
                                                <small><?php echo $produto->descricao ?></small>
                                            </td>
                                            <td><?php echo $produto->id ?></td>
                                            <td><?php echo $produto->quantidade ?></td>
                                          
                                            <!-- <td>R$ <?php echo number_format($produto->valor, 2, ',', '.'); ?></td>
                                            <td>R$ <?php echo number_format($produto->valor*$produto->quantidade, 2, ',', '.'); ?></td> -->
                                        </tr>
                                    <?php 
                                    $total_produtos = $total_produtos + $produto->valor * $produto->quantidade;
                                    endforeach; 
                                    ?>
                                </tbody>
                            </table>


	
<br>
<br>
<span>
		Cód Pedido: 
	</span>
	<span style="margin-left: 32px;"><?php echo $pedido->id ?></span>

	<br>


<span> Impresso em <?php echo date('d/m/Y') ?> às <?php echo date('H:i') ?>
	</span>

    <br>

    <span> Pago: <?php echo ($pedido->pedido_pago == 1) ? 'SIM' : 'A PAGAR' ?>
	</span>
	


	<div id="imprimir">
		<img onclick="imprimir()" src="<?php echo base_url() ?>assets/img/imprimir.png" width="50" class="tooltip" title="" aria-describedby="ui-tooltip-4">
	</div>
	

</body>
</html>

