<!DOCTYPE html>
<html>
<head>
	<title>CUPOM</title>

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

	<span> <strong>  FLORES E DECORACOES LTDA </strong></span>
	<br>
	<span> CNPJ: 49.765.943/0001-02</span>
	<br>
    <br>
	<span>
    
                                    CEP: 39400-466,
                                    Avenida Doutor Joao Luiz de Almeida <br>
                                    nº 374, Bairro Vila Guilhermina - 
                                
                                    Montes Claros/MG
    </span>

	<br>

    <br>
    <span> <strong>  CLIENTE: </strong> <?php echo $pedido->cliente_pedido ?> - <?php echo $pedido->cliente_telefone ?></span>
    <br>
    --------------------------------------------------------------------------
    <br>
 

	

    <span> <strong>  CUPOM </strong></span>



    <br>
    --------------------------------------------------------------------------
    <br>
    <br>
    
    <table>
                                <thead>
                                    <tr>
                                        <th align="left">Produto</th>
                                        <th>ID</th>
                                        <th>Qtd</th>
                                        <th>Unitário</th>
                                        <th>Total</th> 
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
                                          
                                            <td><?php echo number_format($produto->valor, 2, ',', '.'); ?></td>
                                            <td><?php echo number_format($produto->valor*$produto->quantidade, 2, ',', '.'); ?></td> 
                                        </tr>
                                    <?php 
                                    $total_produtos = $total_produtos + $produto->valor * $produto->quantidade;
                                    endforeach; 
                                    ?>

                                    <?php $desconto = 0; ?>

                                    <tr>
                                        <td colspan="4" align="right" style="font-size: 13px;">SUBTOTAL</td>
                                        <td style="font-size: 13px;"><?php echo number_format($total_produtos, 2, ',', '.'); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" align="right" style="font-size: 13px;">FRETE</td>
                                        <td style="font-size: 13px;"><?php echo number_format($pedido->valor_frete, 2, ',', '.'); ?></td>
                                    </tr>

                                    <?php if($pedido->valor_desconto > 0): ?>
                                    <tr>
                                        <td colspan="4" align="right" style="font-size: 13px;">DESCONTO</td>
                                        <?php 
                                        if($pedido->tipo_desconto == 'porcentagem'):
                                            $desconto = $total_produtos * $pedido->valor_desconto / 100;
                                        else:
                                            $desconto = $pedido->valor_desconto;
                                        endif;
                                        ?>
                                        <td style="font-size: 13px;">- <?php echo number_format($desconto, 2, ',', '.'); ?></td>
                                    </tr>
                                    <?php endif; ?>

                                    <tr>
                                        <td colspan="4" align="right" style="font-size: 13px;">TOTAL</td>
                                        <td style="font-size: 13px;"><?php echo number_format($total_produtos + $pedido->valor_frete - $desconto, 2, ',', '.'); ?></td>
                                    </tr>
                                </tbody>
                            </table>


	
<br>
--------------------------------------------------------------------------
<br>

	
    <span>Forma de pagamento:

    <br>

    <?php foreach($pedido->receitas as $receita): ?>

<strong>Venc:</strong> <?php echo inverteData($receita->data_vencimento, '/')  ?> 
-  R$<?php echo number_format($receita->valor, 2, ',', '.'); ?>
- <strong>Pago:</strong>  <?php echo ($receita->status == 1) ? 'Sim' : 'Não'; ?>
- 
<?php 
if($receita->forma_pgto == 1): 
 echo '<span class="wishlist-in-stock">Dinheiro</span>';
   elseif($receita->forma_pgto == 2): 
     echo '<span class="wishlist-in-stock">Débito</span>';
     elseif($receita->forma_pgto == 3): 
         echo '<span class="wishlist-in-stock">Crédito 1x</span>';
         elseif($receita->forma_pgto == 4): 
             echo '<span class="wishlist-in-stock">Crédito 2x</span>';
             elseif($receita->forma_pgto == 5): 
                 echo '<span class="wishlist-in-stock">Crédito 3x</span>';
                 elseif($receita->forma_pgto == 6): 
                     echo '<span class="wishlist-in-stock">Crédito 4x</span>';
                     elseif($receita->forma_pgto == 7): 
                         echo '<span class="wishlist-in-stock">Duplicata</span>';
                         elseif($receita->forma_pgto == 8): 
                             echo '<span class="wishlist-in-stock">Cheque</span>';
                             elseif($receita->forma_pgto == 9): 
                                 echo '<span class="wishlist-in-stock">Pix</span>';
                         else:
                             echo '-';
                         endif;
?>

<br>

<?php endforeach; ?>
                                   
                                   

</span>    


	<br>

    <span> Data do pedido <?php  echo date("d/m/Y H:i", strtotime($pedido->data_pedido)); ?>
	</span>

    <br>


<span> Impresso em <?php echo date('d/m/Y') ?> às <?php echo date('H:i') ?>
	</span>

    <br>

    <!-- <span> Pago: <?php echo ($pedido->pedido_pago == 1) ? 'SIM' : 'A PAGAR' ?>
	</span> -->
	


	<div id="imprimir">
		<img onclick="imprimir()" src="<?php echo base_url() ?>assets/img/imprimir.png" width="50" class="tooltip" title="" aria-describedby="ui-tooltip-4">
	</div>
	

</body>
</html>

