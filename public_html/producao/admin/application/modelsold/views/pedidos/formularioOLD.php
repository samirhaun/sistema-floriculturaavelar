
<style media="screen">
.horarios{
  border-right: solid 1px;
  padding: 12px 4px 0px 16px;
}
</style>
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2><?php echo (isset($pedido)) ? 'Editar cadastro do pedido' : 'Novo cadastro de pedido' ?></h2>
    <ol class="breadcrumb">
      <li>
        <a href="#">Loja</a>
      </li>
      <li>
        <a href="<?php echo base_url(array('loja', 'pedidos')) ?>">pedidos</a>
      </li>
      <li class="active">
        <strong><?php echo (isset($pedido)) ? 'Editar cadastro do pedido' : 'Novo cadastro de pedido' ?></strong>
      </li>
    </ol>
  </div>
  <div class="col-lg-2">
  </div>
</div>
<div class="wrapper wrapper-content animated">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-content">
          <div class="hr-line-dashed"></div>
          <form action="<?php echo base_url(array('site', 'salvar-pedido')) ?>" method="post" id="form-cadastro-pedido" enctype="multipart/form-data">
            <?php if (isset($pedido)): ?>
              <input type="hidden" name="id" value="<?php echo $pedido->id ?>">
            <?php endif ?>

            <div class="row">
              <div class="col-sm-2 col-xs-12">
                <div class="form-group">
                  <label class="control-label">Codigo do Pedido: </label>
                  <input type="text" name="cod" class="form-control" value="<?php echo (isset($pedido)) ? $pedido->id : '' ?>" alt="" disabled>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label class="control-label">Data solicitação: </label>
                  <input type="text" name="data_solicitacao" class="form-control data_mask" value="<?php echo (isset($pedido)) ? inverteData($pedido->data_pedido,'/')  : date('d/m/Y') ?>"  readonly="readonly">
                </div>
              </div>


              <div class="col-md-7">
                <div class="form-group">
                  <label class="control-label">Vendedor:</label>
                  <select required="" class="form-control" name="vendedores_id">
                    <option value="">Selecione</option>

                    <?php foreach ($vendedores as $key => $vendedor): ?>
                      <option <?php if (isset($pedido) && $vendedor->id == $pedido->vendedores_id){echo 'selected';} ?> value="<?php echo $vendedor->id ?>"><?php echo $vendedor->descricao; ?></option>
                    <?php endforeach ?>
                    
                  </select>
                </div>
              </div>


              <div class="col-md-5">
                <div class="form-group">
                  <label class="control-label">Cliente:</label>
                  <select required="" class="form-control js-example-basic-single" name="clientes_id" onchange="busca_enderecos_cliente(this.value)">
                    <option value="">Cliente</option>
                    <option value="novo">Cadastrar novo</option>

                    <?php foreach ($clientes as $key => $cliente): ?>
                      <option <?php if (isset($pedido) && $cliente->id == $pedido->clientes_id){echo 'selected';} ?> value="<?php echo $cliente->id ?>"><?php echo $cliente->nome; ?> - <?php echo $cliente->telefone; ?></option>
                    <?php endforeach ?>
                    
                  </select>
                </div>
              </div>


              <div class="col-md-7">
                <div class="form-group">
                  <label class="control-label">Endereço(s) Cliente:</label>
                  <select class="form-control js-example-basic-single" name="endereco_cliente" onchange="seta_endereco_cliente(this.value)" id="enderecos_cliente">


                     <option value="">Selecione um cliente</option>
                     <option value="novo">Cadastrar novo</option>

                     <?php if (isset($pedido) && !empty($enderecos)): ?>

                      <?php foreach ($enderecos as $endereco): ?>

                        <option value="<?php echo $endereco->id ?>"><?php echo $endereco->descricao ?> - <?php echo $endereco->rua ?>, <?php echo $endereco->bairro ?>, n <?php echo $endereco->numero ?>, <?php echo $endereco->cidade ?> <?php echo $endereco->estado ?></option>

                      <?php endforeach ?>


                      <?php else: ?>

                      

                      <?php endif; ?>
                    
                  </select>
                </div>
              </div>


              <div id="descricao_novo_endereco" style="display: none;"> 
                          
                  <div class="col-md-5">
                    <div class="form-group">
                  </div>
                </div>

                <div class="col-md-7">
                    <div class="form-group">
                    <label class="control-label">Descrição endereço:</label>
                    <input type="text" name="descricao_novo_endereco" class="form-control" value="" >
                  </div>
                </div>

              </div>

              <div id="descricao_novo_cliente" style="display: none;"> 
                          
                    <div class="col-sm-3 col-xs-4">
                        <div class="form-group">
                            <label class="control-label">Nome cliente: *</label>
                            <input type="text" name="nome_cliente_new" class="form-control" value="">
                        </div>
                    </div>

                    <div class="col-sm-3 col-xs-4">
                        <div class="form-group">
                            <label class="control-label">E-mail cliente: </label>
                            <input type="text" name="email_cliente_new" class="form-control" value="" >
                        </div>
                    </div>

                    <div class="col-sm-3 col-xs-4">
                        <div class="form-group">
                            <label class="control-label">Telefone cliente: *</label>
                            <input type="text" name="telefone_cliente_new" class="form-control" value="">
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-4">
                        <div class="form-group">
                            <label class="control-label">CPF cliente: </label>
                            <input type="text" name="cpf_cliente_new" class="form-control" value="" >
                        </div>
                    </div>

                    <div class="col-sm-3 col-xs-4">
                        <div class="form-group">
                            <label class="control-label">Data de nascimento cliente: </label>
                            <input type="text" name="nascimento_cliente_new" class="form-control" value="" >
                        </div>
                    </div>

              </div>



            </div>

            <div class="hr-line-dashed"></div>

            <div class="row">

            <!-- <div class="row">
              <div class="col-md-12">
              <h4>ads</h4>
              </div>
            </div> -->

            <div class="col-md-4">
                <div class="form-group">
                 <label class="control-label">Nome recebedor:*</label>
                 <input type="text" name="nome_cliente" class="form-control" value="<?php echo (isset($pedido)) ? $pedido->pessoa_entrega : '' ?>"  required>
               </div>
             </div>


              <div class="col-md-2">
                <div class="form-group">
                 <label class="control-label">Data de entrega:*</label>
                 <input type="text" name="data_entrega" class="form-control data_mask datepicker" value="<?php echo (isset($pedido)) ? inverteData($pedido->data_entrega,'/')  : '' ?>"  required>
               </div>
             </div>

             <div class="col-md-3">
                <div class="form-group">
                 <label class="control-label">Horário de entrega:*</label>
                 <input type="text" name="hora_entrega" class="form-control hora_mask" value="<?php echo (isset($pedido)) ? $pedido->hora_entrega : '' ?>"  required>
               </div>
             </div>


             <div class="col-md-3">
                <div class="form-group">
                 <label class="control-label">Cep de entrega:</label>
                 <input type="text" name="cep_entrega" class="form-control" value="<?php echo (isset($pedido)) ? $pedido->cep_entrega : '' ?>"  >
               </div>
             </div>


             <div class="col-md-3">
                <div class="form-group">
                 <label class="control-label">Rua de entrega:</label>
                 <input type="text" name="rua_entrega" class="form-control" value="<?php echo (isset($pedido)) ? $pedido->rua_entrega : '' ?>"  required>
               </div>
             </div>

             <div class="col-md-2">
                <div class="form-group">
                 <label class="control-label">Número de entrega:</label>
                 <input type="text" name="numero_entrega" class="form-control" value="<?php echo (isset($pedido)) ? $pedido->numero_entrega : '' ?>"  required>
               </div>
             </div>

             <div class="col-md-3">
                <div class="form-group">
                 <label class="control-label">Bairro de entrega:</label>
                 <input type="text" name="bairro_entrega" class="form-control" value="<?php echo (isset($pedido)) ? $pedido->bairro_entrega : '' ?>"  required>
               </div>
             </div>

             <div class="col-md-3">
                <div class="form-group">
                 <label class="control-label">Cidade de entrega:</label>
                 <input type="text" name="cidade_entrega" class="form-control" value="<?php echo (isset($pedido)) ? $pedido->cidade_entrega : '' ?>"  required>
               </div>
             </div>

             <div class="col-md-1">
                <div class="form-group">
                 <label class="control-label">Estado:</label>
                 <input type="text" name="estado_entrega" class="form-control" value="<?php echo (isset($pedido)) ? $pedido->estado_entrega : '' ?>" >
               </div>
             </div>


             <div class="col-md-9">
                <div class="form-group">
                 <label class="control-label">Obs entrega:</label>
                 <input type="text" name="obs" class="form-control" value="<?php echo (isset($pedido)) ? $pedido->obs : '' ?>"  >
               </div>
             </div>

             <div class="col-md-3">
                <div class="form-group">
                  <label class="control-label">Evento:</label>
                  <select class="form-control js-example-basic-single" name="eventos_id">
                    <option value="">Evento</option>

                    <?php foreach ($eventos as $key => $evento): ?>
                      <option <?php if (isset($pedido) && $evento->id == $pedido->eventos_id){echo 'selected';} ?> value="<?php echo $evento->id ?>"><?php echo $evento->descricao; ?></option>
                    <?php endforeach ?>
                    
                  </select>
                </div>
              </div>


              <div class="col-md-12">
                <div class="form-group">
                  
                  <input type="checkbox" id="possui_bilhete" name="possui_bilhete" <?php if (isset($pedido) && !empty($pedido->bilhete)){echo 'checked="checked"';} ?> />

                  <label class="control-label" for="possui_bilhete" onclick="verifica_bilhete()"> Possui bilhete?</label>

                </div>
              </div>

              <div class="col-sm-12 col-xs-12" id="bilhete" <?php if (isset($pedido) && empty($pedido->bilhete) || empty($pedido)){echo 'style="display: none;"';} ?> >
                <div class="form-group">
                    <label class="control-label">Bilhete: </label>
                    <textarea name="bilhete" class="summernote"><?php if (isset($pedido) && !empty($pedido->bilhete)){echo $pedido->bilhete;} ?></textarea>
                </div>
              </div>



       

        </div>




        <div class="hr-line-dashed"></div>
        
        <!-- INICIANDO PARAMETROS -->

        <?php 
        // var_dump($produtos_pedidos);
        if(!empty($produtos_pedidos)):
          $cont_ped = sizeof($produtos_pedidos);
        else:
          $cont_ped = 1;
        endif;
        ?>

        <input type="hidden" id="contador_qtd_produtos" value="<?php echo $cont_ped; ?>">

        <?php 


        foreach ($produtos as $key => $produto): 
        
        ?>
          
          <input type="hidden" id="valor_produto_<?php echo $produto->id ?>" value="<?php echo $produto->valor; ?>">
          
        <?php endforeach ?>


        <!-- FIM PARAMETROS -->

        <div class="row">
          <div class=" col-md-12">


            <div class="horarios">
              <div align="center" class="col-md-12">
           
                <h3 style="margin: 0px">Produtos</h3>
                <div id="modalModelo">
                                    
                </div>
              </div>
              <div class="col-sm-12 col-xs-2 text-right">
                <button type="button" onlick="alert()" class="btn btn-danger remove help" title="Remover">Remover <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-success add help" title="Adicionar">Adicionar <i class="fa fa-plus"></i></button>

              </div>

              <?php if (!empty($produtos_pedidos)){ $total_existentes = sizeof($produtos_pedidos); $contador_existentes = 1; ?>
                <?php foreach ($produtos_pedidos as $key => $produto_pedido): ?>
                  <div class="hr-line-dashed"></div>

                  <div class="row item">

                      <div class="col-md-6">
                            <div class="form-group">
                            <?php if($contador_existentes == 1): ?> <label class="control-label">Produto:</label> <?php endif; ?>
                              <select 
                              required="" 
                              class="form-control produtos_select" 
                              name="produto_collapse[]" 
                              id="produto_collapse_<?php echo $contador_existentes ?>" 
                              onchange="lanca_valor_produto(this.value, <?php echo $contador_existentes ?>)"
                              >
                                <option value="">Selecione</option>

                                <?php foreach ($produtos as $key => $produto): ?>
                                  <option <?php if (isset($produto_pedido) && $produto_pedido->produtos_id == $produto->id){echo 'selected';} ?> value="<?php echo $produto->id ?>">cod: <?php echo $produto->id; ?> - <?php echo $produto->titulo; ?></option>
                                <?php endforeach ?>
                                
                              </select>
                            </div>
                          </div>

                          <div class="col-sm-2 col-xs-10">
                                <div class="form-group">
                                <?php if($contador_existentes == 1): ?> <label class="control-label">Valor unitário:</label><?php endif; ?>
                                  <input 
                                  type="" 
                                  name="valor_collapse[]"  
                                  class="form-control soma_valor_unitario" 
                                  id="valor_collapse_<?php echo $contador_existentes ?>" 
                                  value="<?php echo number_format($produto_pedido->valor_total /  $produto_pedido->quantidade, 2, '.', '.');  ?>" 
                                  required
                                  readonly="readonly"
                                  >
                                </div>
                              </div>


                      <div class="col-sm-2 col-xs-10">
                        <div class="form-group">
                        <?php if($contador_existentes == 1): ?> <label class="control-label">Quantidade:</label><?php endif; ?>
                          <input 
                          type="number" 
                          min="1"
                          name="quantidade_collapse[]" 
                          id="quantidade_collapse_<?php echo $contador_existentes ?>" 
                          class="qtd_change form-control" 
                          value="<?php echo $produto_pedido->quantidade ?>" 
                          required
                          onfocusout="lanca_valor_total_produto(this.value,<?php echo $contador_existentes ?>)"
                          >
                        </div>
                      </div>


                      <div class="col-sm-2 col-xs-10">
                                <div class="form-group">
                                <?php if($contador_existentes == 1): ?><label class="control-label">Valor total:</label><?php endif; ?>
                                  <input 
                                  type="" 
                                  name="valor_total_collapse[]" 
                                  id="valor_total_collapse_<?php echo $produto_pedido->quantidade ?>"  
                                  class="form-control soma_valor_unitario class_valor soma_valor_produtos" 
                                  value="<?php echo $produto_pedido->valor_total ?>" 
                                  required
                                  readonly="readonly"
                                  >
                                </div>
                      </div>
                          

                            



                              
                              


                              
                        </div>            




                  

         <?php $contador_existentes++; endforeach; ?>
       <?php }else{ ?>
        <div class="row item">

          <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Produto:</label>
                  <select 
                  required="" 
                  class="form-control produtos_select" 
                  name="produto_collapse[]" 
                  id="produto_collapse_1" 
                  onchange="lanca_valor_produto(this.value,1)"
                  >
                    <option value="">Selecione</option>

                    <?php foreach ($produtos as $key => $produto): ?>
                      <option value="<?php echo $produto->id ?>">cod: <?php echo $produto->id; ?> - <?php echo $produto->titulo; ?></option>
                    <?php endforeach ?>
                    
                  </select>
                </div>
              </div>

              <div class="col-sm-2 col-xs-10">
                    <div class="form-group">
                      <label class="control-label">Valor unitário:</label>
                      <input 
                      type="" 
                      name="valor_collapse[]"  
                      class="form-control input_diminui soma_valor_unitario valor_mask" 
                      id="valor_collapse_1" 
                      value="" 
                      required
                      readonly="readonly"
                      >
                    </div>
                  </div>
          

          <div class="col-sm-2 col-xs-10">
            <div class="form-group">
              <label class="control-label">Quantidade:</label>
              <input 
              type="number" 
              min="1"
              name="quantidade_collapse[]" 
              id="quantidade_collapse_1" 
              class="qtd_change form-control input_diminui" 
              value="" 
              required
              onfocusout="lanca_valor_total_produto(this.value,1)"
              >
            </div>
          </div>


          <div class="col-sm-2 col-xs-10">
                    <div class="form-group">
                      <label class="control-label">Valor total:</label>
                      <input 
                      type="" 
                      name="valor_total_collapse[]" 
                      id="valor_total_collapse_1"  
                      class="form-control input_diminui soma_valor_unitario class_valor valor_mask soma_valor_produtos" 
                      value="" 
                      required
                      readonly="readonly"
                      >
                    </div>
          </div>
               
          
                
          


                  
                  


                  
            </div>

          <?php } ?>

        </div>
      </div>


    </div>

    <div class="hr-line-dashed"></div>


    <div class="row" style="margin-top: 15px;">
         
          <div class="col-sm-2 col-xs-10">
            <div class="form-group">
              <label class="control-label">Valor Total:</label>
              <input 
              name="valor" 
              id="valor" 
              class="form-control valor_mask" 
              value="<?php echo (isset($pedido)) ? $pedido->valor : '0.00' ?>" 
              required
              readonly="readonly"
              >
            </div>
          </div>

          <?php 

          if(isset($pedido)):
            $desconto = explode('.',$pedido->valor_desconto);
            $desconto = $desconto[0];
          else:
            $desconto = 0;
          endif;

          ?>

          <div class="col-sm-2 col-xs-10">
            <div class="form-group">
              <label class="control-label">- Desconto %:</label>
              <input 
              name="valor_desconto" 
              id="valor_desconto" 
              class="form-control valor_porcentagem_mask" 
              value="<?php echo $desconto; ?>" 
              onkeyup="calcula_pedito_total()"
              >
            </div>
          </div>

          <div class="col-sm-2 col-xs-10">
            <div class="form-group">
              <label class="control-label">Frete:</label>
              <input 
              name="valor_frete" 
              id="valor_frete" 
              class="form-control valor_mask" 
              value="<?php echo (isset($pedido)) ? $pedido->valor_frete : '0.00' ?>" 
              onkeyup="calcula_pedito_total()"
              >
            </div>
          </div>

          <div class="col-sm-3 col-xs-10">
            <div class="form-group">
              <label class="control-label">Valor Final:</label>
              <input 
              name="valor_total" 
              id="valor_total" 
              class="form-control" 
              value="<?php echo (isset($pedido)) ? $pedido->valor_total : '0.00' ?>" 
              readonly="readonly"
              required
              >
            </div>
          </div>

          


            <div class="col-md-3">
                <div class="form-group">
                  <label class="control-label">Forma de pagamento:</label>
                  <select class="form-control" name="forma_pagamento">
                   <option value="">Selecione</option>
                    <option  <?php if (isset($pedido) && $pedido->forma_pagamento_balcao == 1){echo 'selected';} ?> value="1">Dinheiro</option>
                    <option  <?php if (isset($pedido) && $pedido->forma_pagamento_balcao == 9){echo 'selected';} ?> value="9">Pix</option>
                    <option <?php if (isset($pedido) && $pedido->forma_pagamento_balcao == 2){echo 'selected';} ?> value="2">Débito</option>
                    <option <?php if (isset($pedido) && $pedido->forma_pagamento_balcao == 3){echo 'selected';} ?> value="3">Crédito 1x</option>
                    <option <?php if (isset($pedido) && $pedido->forma_pagamento_balcao == 4){echo 'selected';} ?> value="4">Crédito 2x</option>
                    <option <?php if (isset($pedido) && $pedido->forma_pagamento_balcao == 5){echo 'selected';} ?> value="5">Crédito 3x</option>
                    <option <?php if (isset($pedido) && $pedido->forma_pagamento_balcao == 6){echo 'selected';} ?> value="6">Crédito 4x</option>
                    <option <?php if (isset($pedido) && $pedido->forma_pagamento_balcao == 7){echo 'selected';} ?> value="7">Duplicata</option>
                    <option <?php if (isset($pedido) && $pedido->forma_pagamento_balcao == 8){echo 'selected';} ?> value="8">Cheque</option>
                    
                  </select>
                </div>
              </div>


              <div class="col-md-2">
                <div class="form-group">
                 <label class="control-label">Cupom de desconto:</label>
                 <input type="text" name="cupom_aplicado" class="form-control" <?php echo (isset($pedido) && !empty($pedido->cupom_aplicado)) ? 'disabled' : '' ?> value="<?php echo (isset($pedido)) ? $pedido->cupom_aplicado : '' ?>" onchange="aplica_cupom(this.value)">
               </div>
             </div>


             <div class="col-md-2">
                <div class="form-group">
                 <label class="control-label" for="pedido_pago">Pedido pago:</label>
                 <br>
                 <input type="checkbox" value="1" name="pedido_pago" id="pedido_pago" <?php echo (isset($pedido) && !empty($pedido->pedido_pago ==1)) ? 'checked' : '' ?>>
               </div>
             </div>

             <?php if(isset($pedido) && $pedido->pedido_pago==0): ?>
             <div class="col-md-2">
                <div class="form-group">
                 <label class="control-label">Data pago:</label>
                 <input type="text" name="data_pago" class="form-control data_mask datepicker" value="">
               </div>
             </div>
             <?php endif; ?>

    </div>


    <div class="hr-line-dashed"></div>


    <div class="row" style="margin-top: 15px;">

         <div class="col-sm-3 col-xs-10">
            <div class="form-group">
              <label class="control-label">Entrada:</label>
              <input 
              name="valor_entrada" 
              id="valor_entrada" 
              class="form-control valor_mask" 
              value="<?php echo (isset($pedido)) ? $pedido->valor_entrada : '0.00' ?>" 
              >
            </div>
          </div>

          <div class="col-md-2">
                <div class="form-group">
                 <label class="control-label">Data pgto entrada:</label>
                 <input type="text" name="data_pgto_entrada" class="form-control data_mask datepicker" value="<?php echo (isset($pedido->data_pgto_entrada)) ? inverteData($pedido->data_pgto_entrada,'/')  : '' ?>">
               </div>
             </div>

             <div class="col-md-3">
                <div class="form-group">
                  <label class="control-label">Forma de pgto entrada:</label>
                  <select class="form-control" name="forma_pgto_entrada">
                    <option value="">Selecione</option>
                    <option  <?php if (isset($pedido) && $pedido->forma_pgto_entrada == 1){echo 'selected';} ?> value="1">Dinheiro</option>
                    <option  <?php if (isset($pedido) && $pedido->forma_pgto_entrada == 9){echo 'selected';} ?> value="9">Pix</option>
                    <option <?php if (isset($pedido) && $pedido->forma_pgto_entrada == 2){echo 'selected';} ?> value="2">Débito</option>
                    <option <?php if (isset($pedido) && $pedido->forma_pgto_entrada == 3){echo 'selected';} ?> value="3">Crédito 1x</option>
                    <option <?php if (isset($pedido) && $pedido->forma_pgto_entrada == 4){echo 'selected';} ?> value="4">Crédito 2x</option>
                    <option <?php if (isset($pedido) && $pedido->forma_pgto_entrada == 5){echo 'selected';} ?> value="5">Crédito 3x</option>
                    <option <?php if (isset($pedido) && $pedido->forma_pgto_entrada == 6){echo 'selected';} ?> value="6">Crédito 4x</option>
                    <option <?php if (isset($pedido) && $pedido->forma_pgto_entrada == 7){echo 'selected';} ?> value="7">Duplicata</option>
                    <option <?php if (isset($pedido) && $pedido->forma_pgto_entrada == 8){echo 'selected';} ?> value="8">Cheque</option>
                    
                  </select>
                </div>
              </div>

    </div>

    <div class="hr-line-dashed"></div>
    <div class="row">
      <div class="col-sm-12 col-xs-12">
        <div class="form-group">
          <div class="text-right">
            <a href="<?php echo base_url(array('loja', 'pedidos')) ?>" class="btn btn-white">Cancelar</a>
            <button class="btn btn-primary" type="submit">Salvar</button>
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


<script type="text/template" id="item-horario">
    
  
  <div class="row item">

          <div class="col-md-6">
                <div class="form-group">
                  <select 
                  required="" 
                  class="form-control produtos_select" 
                  name="produto_collapse[]" 
                  id="produto_collapse_update"
                  >
                    <option value="">Selecione</option>

                    <?php foreach ($produtos as $key => $produto): ?>
                      <option value="<?php echo $produto->id ?>"><?php echo $produto->titulo; ?></option>
                    <?php endforeach ?>
                    
                  </select>
                </div>
              </div>

              <div class="col-sm-2 col-xs-10">
                    <div class="form-group">
                      <input 
                      type="" 
                      name="valor_collapse[]"  
                      class="form-control input_diminui" 
                      id="valor_collapse_update" 
                      value="" 
                      required
                      readonly="readonly"
                      >
                    </div>
                  </div>
          

          <div class="col-sm-2 col-xs-10">
            <div class="form-group">
              <input 
              type="number" 
              min="1"
              name="quantidade_collapse[]" 
              id="quantidade_collapse_update" 
              class="qtd_change form-control input_diminui" 
              value="" 
              required
              >
            </div>
          </div>


          <div class="col-sm-2 col-xs-10">
                    <div class="form-group">
                      <input 
                      type="" 
                      name="valor_total_collapse[]" 
                      id="valor_total_collapse_update"  
                      class="form-control input_diminui class_valor soma_valor_produtos" 
                      value="" 
                      required
                      readonly="readonly">
                    </div>
          </div>
                  

                  
            </div>


</script>


<style type="text/css">
.input_diminui{
  height: 25px;
  padding: 0px;
  padding-left: 5px;
}
</style>


<style type="text/css">

.hr-line-dashed2{
  border-top: 1px dashed #e7eaec;
  color: #ffffff;
  background-color: #ffffff;
  height: 1px;
  margin: 7px 0;
}
</style>


<script>
      $("#chamaModalModelo").on('click', function(){
      
            $('#modalModelo').load('<?php echo base_url(array('loja', 'modal-modelo')) ?>', function(){
                $('#modal_Modelo').modal('show');
            });
        })
</script>

<script type="text/javascript">

$('input[name="cpf_cliente_new"]').mask('000.000.000-00', {reverse: true});
  $('input[name="telefone_cliente_new"]').mask('(00) 00000-0000');
  $('input[name="nascimento_cliente_new"]').mask('00/00/0000');


  $('#calcular_total').click(function(e){



    e.preventDefault();
    var qtd = new Array();
    $('.qtd_change').each(function (index, value) { 
     qtd[index] = (($(this).val()))
   });

    var soma = 0;

    $('.soma_valor_produtos').each(function (index) { 


      // if ($(this).val()) {
      //   var numb = ($(this).val()).replace(",", ".");
      //   // numb = numb.toFixed(2);
      //   soma = (parseFloat(numb)*parseInt(qtd[index])+parseFloat((soma)));
      // }

      console.log($(this).val());


      // soma = (soma.toFixed(2));
      // soma = (soma.replace(".", ","));


    });
    soma = (soma.toFixed(2));
    soma = (soma.replace(".", ","));
    $('input[name="valor"]').val(soma);
  })

</script>



<script>
function modelo_valor(elementos){

      var modelo = elementos.val();

      var quantidade = elementos.closest('.item').find('.qtd_change').val();

        $.ajax({
          url: '<?php echo base_url(['site/modelo-pedidos']) ?>',
          type: 'POST',
          data: {
            id: modelo,
        },

        success: function(result) {
            result = JSON.parse(result)
            soma = (parseFloat(result[0].valor)*parseInt(quantidade));
            soma = (soma.toFixed(2));
            soma = (soma.replace(".", ","));
            elementos.closest('.item').find('.soma_valor_unitario').val(soma);
        },

    });
  }
</script>


<!-- <?php if (isset($pedido)){ ?>
  <script type="text/javascript">

    function soma_valores(num){
      var soma = 0;

      $('.soma_valor_unitario').each(function (index, value) { 

        if ($(this).val()) {
          soma = parseFloat($(this).val())+parseFloat((soma));

        }
      });

      $('input[name="valor"]').val(soma);

    }
  </script>
<?php }else{ ?>
  <script type="text/javascript">
    var soma = 0;
    $('input[name="valor"]').val(soma);

    function soma_valores(num){
      var soma = 0;

      $('.soma_valor_unitario').each(function (index, value) { 
      console.log($('.qtd_change').val())
        if ($(this).val()) {
          soma = (parseFloat($(this).val())+parseFloat((soma)))*parseInt($('.qtd_change').val()[index]) ;

        }
      });

      $('input[name="valor"]').val(soma);

    }
  </script>
  <?php } ?> -->


  <script type="text/javascript">

    $('.remove').click(function(){

      if ($('.item').size() > 1) {
        $('.item').last().remove();
      }

      lanca_valor_total_produto(null,null);

    });


    $('.remove_existente').click(function(){



    });


    function lanca_valor_produto(idproduto,countproduto){

      var valor_produto = $('#valor_produto_'+idproduto).val();

      $('#valor_collapse_'+countproduto).val(valor_produto);


    }

    function verifica_estoque_produtos(idproduto, qtd){

      

    }

    function lanca_valor_total_produto(qtd,countproduto){

      //ANTES DE TUDO IREMOS VERIFICAR SE PRODUTO POSSUI ESTOQUE OU PRODUTOS VINCULADOS
      var idprod =  $('#produto_collapse_'+countproduto).val(); 


      $.ajax({
          url: '<?php echo base_url(['loja/verifica-estoque-produtos']) ?>',
          type: 'POST',
          data: {
            idproduto: idprod,
            qtd: qtd,
        },

        success: function(result) {
          
            result = JSON.parse(result)

            // console.log(result)

            

            //CASO PARA ESTOQUE UNICO
            if(result.tipo_estoque == 'unico'){
              

              //DISPONIVEL PARA VENDA
              if(parseInt(result.qtd_disponivel) >= parseInt(qtd)){

                      /* SOMANDO TOTAL DOS PRODUTOS NO ITEM */

                      if(qtd && countproduto){

                      var valor_produto = $('#valor_collapse_'+countproduto).val();


                      var total_produto = valor_produto * qtd;


                      $('#valor_total_collapse_'+countproduto).val(total_produto.toFixed(2));

                      }

                      /* SOMANDO TOTAL DOS PRODUTOS NO PEDIDO */

                      var soma = 0;

                      $('.soma_valor_produtos').each(function (index, value) { 

                      if ($(this).val()) {
                        var numb = $(this).val();
                        soma = parseFloat(soma) + parseFloat(numb);
                      }

                      });

                      $('#valor').val(soma.toFixed(2));

                      /* SOMANDO TOTAL DO PEIDO */

                      calcula_pedito_total();

                      /* FIM CALCULOS PEDIDO */

                
                
              }else{

                alert('Não permitido. Quantidade disponível: '+ result.qtd_disponivel);

                $('#quantidade_collapse_'+countproduto).val(0);

                // calcula_pedito_total();

              }
             
            //CASO PARA ESTOQUE COMPOSTO
            }else{

              //VAMOS PERCORRER TODOS OS PRODUTOS PARA ALERTAR DA INDISPONIBILIDADE
              var indisponiveis = '';

              for (let index = 0; index < result.itens.length; index++) {

                var em_estoque = parseInt(result.itens[index].qtd_disponivel);

                var precisa_para_produzir = parseInt(qtd) * parseInt(result.itens[index].qtd_utilizada_producao);

                // console.log(em_estoque)
                // console.log(precisa_para_produzir)
 
                //INDISPONIVEL PARA VENDA
                if(parseInt(em_estoque) < parseInt(precisa_para_produzir)){
                  // let indisponiveis = indisponiveis.concat(result.itens[index].desc_prod + 'Indisponível para venda.<br>');
                  // indisponiveis.concat(indisponiveis, result.itens[index].desc_prod + 'Indisponível para venda.<br>');
                  alert(result.itens[index].desc_prod + ' Indisponível para produção do produto.');
                  $('#quantidade_collapse_'+countproduto).val(0);
                  
                }else{

                  /* SOMANDO TOTAL DOS PRODUTOS NO ITEM */

                  if(qtd && countproduto){

                  var valor_produto = $('#valor_collapse_'+countproduto).val();


                  var total_produto = valor_produto * qtd;


                  $('#valor_total_collapse_'+countproduto).val(total_produto.toFixed(2));

                  }

                  /* SOMANDO TOTAL DOS PRODUTOS NO PEDIDO */

                  var soma = 0;

                  $('.soma_valor_produtos').each(function (index, value) { 

                  if ($(this).val()) {
                    var numb = $(this).val();
                    soma = parseFloat(soma) + parseFloat(numb);
                  }

                  });

                  $('#valor').val(soma.toFixed(2));

                  /* SOMANDO TOTAL DO PEIDO */

                  calcula_pedito_total();

                  /* FIM CALCULOS PEDIDO */

                }

                
              }

              // console.log(indisponiveis)

              //VAMOS ALERTAR O USUARIO E ZERAR O CAMPO DO PRODUTO
              // if(indisponiveis != ''){
              //   alert(indisponiveis)
              // }



            }





                // console.log(result.qtd_disponivel)
            // soma = (parseFloat(result[0].valor)*parseInt(quantidade));
            // soma = (soma.toFixed(2));
            // soma = (soma.replace(".", ","));
            // elementos.closest('.item').find('.soma_valor_unitario').val(soma);
            },

     });







    }


    function calcula_pedito_total(){

      var valor_produtos = $('#valor').val();
      var frete = $('#valor_frete').val();
      var desconto = $('#valor_desconto').val();

      //VALIDANDO DESCONTO DO USUARIO
      if(desconto > 0){

        var total_desconto_usuario = "<?php echo $usuario->desconto_maximo ?>";

        if(parseInt(desconto) > parseInt(total_desconto_usuario)){
          alert('Desconto não permitido');
          $('#valor_desconto').val(0);
          var desconto_geral = 0;
        }else{
          //desconto por porcentagem
          var desconto_geral = (parseFloat(valor_produtos) + parseFloat(frete)) * parseFloat(desconto) / 100;
        }

      }else{    
        var desconto_geral = 0;
      }

      


      var total_geral = parseFloat(valor_produtos) + parseFloat(frete) - parseFloat(desconto_geral);

      $('#valor_total').val(total_geral.toFixed(2));

    }



  </script>


  <script type="text/javascript">

$(function() {

    const $form = $('#form-cadastro-pedido');

    $()
    .add($form.find('[name$="data_entrega"]'))
    .datepicker({
    autoclose: false,
    calendarWeeks: false,
    clearBtn: false,
    enableOnReadonly: false,
    format: 'dd/mm/yyyy',
    forceParse: false,
    keyboardNavigation: false,
    language: 'pt-BR',
    maxViewMode: 1,
    startDate: moment().format('DD/MM/YYYY'),
    todayBtn: "linked",
    todayHighlight: true,
    toggleActive: false,
    });

    $()
    .add($form.find('[name$="data_pago"]'))
    .datepicker({
    autoclose: false,
    calendarWeeks: false,
    clearBtn: false,
    enableOnReadonly: false,
    format: 'dd/mm/yyyy',
    forceParse: false,
    keyboardNavigation: false,
    language: 'pt-BR',
    maxViewMode: 1,
    startDate: moment().format('DD/MM/YYYY'),
    todayBtn: "linked",
    todayHighlight: true,
    toggleActive: false,
    });

    $()
    .add($form.find('[name$="data_pgto_entrada"]'))
    .datepicker({
    autoclose: false,
    calendarWeeks: false,
    clearBtn: false,
    enableOnReadonly: false,
    format: 'dd/mm/yyyy',
    forceParse: false,
    keyboardNavigation: false,
    language: 'pt-BR',
    maxViewMode: 1,
    startDate: moment().format('DD/MM/YYYY'),
    todayBtn: "linked",
    todayHighlight: true,
    toggleActive: false,
    });


});



    $('.data_mask').mask('00/00/0000');
    $('.hora_mask').mask('00:00');
    // $('.soma_valor_unitario').mask('00000,00', {reverse: true});
    $('.valor_mask').mask('00000,00', {reverse: true});
    $('.valor_porcentagem_mask').mask('00', {reverse: true});
    $('[name=cep_entrega]').mask('00000-000');

    // $('.class_valor').mask('00000,00', {reverse: true});

    $("[name=vendedores_id]").select2({
        placeholder: "Selecione um vendedor",
        allowClear: true
    });


    $("[name=forma_pagamento]").select2({
        placeholder: "Selecione uma forma de pagamento",
        allowClear: true
    });

    $("[name=forma_pgto_entrada]").select2({
        placeholder: "Selecione uma forma de pagamento",
        allowClear: true
    });

    $("[name=clientes_id]").select2({
        placeholder: "Selecione um cliente",
        allowClear: true
    });

    $("#enderecos_cliente").select2({
        placeholder: "Selecione",
        allowClear: true
    });

    $(".produtos_select").select2({
        placeholder: "Selecione um produto",
        allowClear: true
    });


    $("[name=eventos_id]").select2({
        placeholder: "Selecione um evento",
        allowClear: true
    });


    /* ADICIONANDO MAIS PRODUTOS */
    $('.horarios').on('click', '.add', function(){

    // $('.horarios .add').addClass('hidden');
    // $('.horarios .add').addClass('hidden');

      //PEGANDO O CONTADOR DOS PRODUTOS
      var contador_products = parseInt ($("#contador_qtd_produtos").val()) + 1;

    
      //ADICIONANDO A DIV COM O CONTEUDO
      $('.horarios').append($(
          $('#item-horario').html()
        )
      );

      //SETANDO SELECT2
      $("#produto_collapse_update").select2({
          placeholder: "Selecione um produto",
          allowClear: true
      });

      //SETANDO OS IDS
      var produto = document.getElementById("produto_collapse_update");
      produto.setAttribute('onchange','lanca_valor_produto(this.value,'+contador_products+');');
      produto.id = 'produto_collapse_'+contador_products;
      

      var valor_produto = document.getElementById("valor_collapse_update");
      valor_produto.id = 'valor_collapse_'+contador_products;

      var qtd_produto = document.getElementById("quantidade_collapse_update");
      qtd_produto.setAttribute('onfocusout','lanca_valor_total_produto(this.value,'+contador_products+');');
      qtd_produto.id = 'quantidade_collapse_'+contador_products;

      var total_produto = document.getElementById("valor_total_collapse_update");
      total_produto.id = 'valor_total_collapse_'+contador_products;


      //ATUALIZANDO QTD DE PRODUTOS
      $("#contador_qtd_produtos").val(contador_products);


    // $(document).ready(function() {
    //         $('.js-example-basic-single-modelo').select2().on('change', function (e) {
    //           modelo_valor($(this))
    //       })
    //     });
  });

    $('.remove-item').on('click', function(){
      var id = ($(this).data('id'));
      var target = $(this).closest('.row');

      swal({
        title: "Atenção,",
        text: "Tem certeza que deseja excluir?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, excluir",
        cancelButtonText: "Não, cancelar",
        closeOnConfirm: true,
        closeOnCancel: true },
        function (isConfirm) {
          if (!isConfirm) {
           return;
         }
         $.ajax({
          url: '<?php echo base_url(['site', 'item', 'excluir']) ?>',
          type: 'POST',
          data: {
            id_item: id
          },
          beforeSend: function(){
            $('#loading').removeClass('loading-off');
          },
          success: function(result) {
            result = JSON.parse(result);
            console.log(result);
            showNotification(result.type, result.title, result.message);
            target.remove();
            target.remove().last().find('button.add').removeClass('hidden');
          },
          complete: function(){
            $('#loading').addClass('loading-off');
          }
        });
       });
    });


    $('input').setMask();
    var validator = $('#form-cadastro-pedido').validate({
      ignore: [],
      rules: {
        valor: {
          required: true,

        },
        descricao_breve:{
          required: true,
          maxlength: 255
        },
        comprimento_embalagem: {
          required: true,
          min: 16
        },
        altura_embalagem: {
          required: true,
          min: 2
        },
        largura_embalagem: {
          required: true,
          min: 11
        }
      }
    });

    $(function() {
      $('.summernote').summernote({
        height: 150,
      });

        //remove o input file do summernote, para evitar upload de imagens
        $('.note-group-select-from-files').remove();

        //galeria
        $(document).on('change.bs.fileinput', '.fileinput', function(e) {
          var $this = $(this),
          $input = $this.find('input[type=file]'),
          $span = $this.find('.fileinput-filename');
          if($input[0].files !== undefined && $input[0].files.length > 1) {
            $span.text($.map($input[0].files, function(val) { return val.name; }).join(', '));
          }
          $span.attr('title', $span.text());
        });

        $(document).on('clear.bs.fileinput', '.fileinput', function(e) {
          $('.lightBoxGallery').find('.col-sm-2').not('.server').remove();
        });
      });


    <?php if (!isset($pedido)): ?>
      $.validator.addClassRules("file", {
        validate_file: true
      });
    <?php endif ?>

    //metodo para validar o file
    $.validator.addMethod("validate_file", function(value, element){
      if(value.length > 0){
        return true;
      }

      if($(".error-file").find('label.error').length){
        $(".error-file").find('label.error').remove();
      }
      $(".error-file").append('<label id="imagem-error" class="error" for="imagem"></label>');
      return false;
    }, "Este campo é obrigatório.");

    /*
    $.validator.addClassRules("summernote", {
      validate_note: flase
    });

    $.validator.addMethod("validate_note", function(value, element){
      cleanText = $(".summernote").summernote('code').replace(/<\/?[^>]+(>|$)/g, "");
      if(cleanText.length > 0){
        return true;
      }

        // console.log(validator.numberOfInvalids());

        if($(".error-note").find('label.error').length){
          $(".error-note").find('label.error').remove();
        }
        $(".error-note").append('<label id="descricao-error" class="error" for="descricao"></label>');
        // $('html,body').animate({ scrollTop: $('.summernote').offset().top}, 'slow');
        return false;
      }, "Este campo é obrigatório.");
*/

    $('#imagem').on('change', function(){
      input = $(this);
      if (input[0].files && input[0].files[0]) {
        $('.lightBoxGallery').find('.col-sm-2').not('.server').remove();
        for(i=0; i < input[0].files.length; i++){
          var reader = new FileReader();

          reader.onload = function (e) {
            $('.lightBoxGallery').find('.row').append('<div class="col-sm-2"><div class="container-img"><img id="img-show" src="'+e.target.result+'"></div></div>');
          }

          reader.readAsDataURL(input[0].files[i]);
        }
      }else{
        $('.lightBoxGallery').find('.col-sm-2').not('.server').remove();
      }
    });

    $('.close-img').on('click', function(){
      $('#form-cadastro-pedido').append('<input type="hidden" name="remove_imagem[]" value="'+$(this).data('id')+'" />');
      $('#form-cadastro-pedido').append('<input type="hidden" name="nome_imagem[]" value="'+$(this).data('nome')+'" />');
      $(this).parents('.server').remove();
    });



    function limpa_formulário_cep() {
           // Limpa valores do formulário de cep.
           $("[name=rua_entrega]").val("");
           $("[name=bairro_entrega]").val("");
           $("[name=cidade_entrega]").val("");
           $("[name=estado_entrega]").val("");
           $("#ibge").val("");
       }

       //Quando o campo cep perde o foco.
       $("[name=cep_entrega]").blur(function() {

           //Nova variável "cep" somente com dígitos.
           var cep = $(this).val().replace(/\D/g, '');

           //Verifica se campo cep possui valor informado.
           if (cep != "") {

               //Expressão regular para validar o CEP.
               var validacep = /^[0-9]{8}$/;

               //Valida o formato do CEP.
               if(validacep.test(cep)) {

                   //Preenche os campos com "..." enquanto consulta webservice.
                   $("[name=rua_entrega]").val("");
                   $("[name=bairro_entrega]").val("");
                   $("[name=cidade_entrega]").val("");
                   $("[name=estado_entrega]").val("");

                   //Consulta o webservice viacep.com.br/
                   $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                       if (!("erro" in dados)) {
                           //Atualiza os campos com os valores da consulta.
                           $("[name=rua_entrega]").val(dados.logradouro);
                           $("[name=bairro_entrega]").val(dados.bairro);
                           $("[name=cidade_entrega]").val(dados.localidade);
                           $("[name=estado_entrega]").val(dados.uf);
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



       function soma_total_produtos(valor){
          alert('afasdf')
       }
       

       /* PEGANDO ENDEREÇOS CLIENTE */

       function busca_enderecos_cliente(id){

        if(id == 'novo'){

          $('#descricao_novo_cliente').show();
          $('input[name="nome_cliente_new"]').attr("required","required");
          $('input[name="telefone_cliente_new"]').attr("required","required");

        }else{

          $('#descricao_novo_cliente').hide();
          $('input[name="nome_cliente_new"]').removeAttr('required');
          $('input[name="telefone_cliente_new"]').removeAttr('required');

          $.ajax({
                url: '<?php echo base_url(['site/enderecos-cliente']) ?>',
                type: 'POST',
                data: {
                  id: id,
              },

              success: function(result) {
                  result = JSON.parse(result)

                  // console.log(result.enderecos);

                  let str = '<option value="">Selecione</option>';
                  str += '<option value="novo">Cadastrar novo endereço</option>';

                  

                  for (let index = 0; index < result.enderecos.length; index++) {
                    
                    str += '<option value="'+result.enderecos[index].id+'">'+result.enderecos[index].descricao+' - '+result.enderecos[index].rua+', '+result.enderecos[index].bairro+' , n '+result.enderecos[index].numero+'</option>';

                  }

                  // console.log(str);

                  $('#enderecos_cliente').html(str);

                  
              },

            });


          }



       }


       //SETANDO ENDEREÇO CLIENTE

       function seta_endereco_cliente(id){

        // console.log(id)

        if(id == 'novo'){
          $('#descricao_novo_endereco').show();
          $("[name=cep_entrega]").val('');
          $("[name=rua_entrega]").val('');
          $("[name=numero_entrega]").val('');
          $("[name=bairro_entrega]").val('');
          $("[name=cidade_entrega]").val('');
          $("[name=estado_entrega]").val('');
          $("[name=obs]").val('');
        }else{

          $('#descricao_novo_endereco').hide();

          $.ajax({
                url: '<?php echo base_url(['site/enderecos-cliente-setar']) ?>',
                type: 'POST',
                data: {
                  id: id,
              },

              success: function(result) {
                  result = JSON.parse(result)

                  console.log(result.endereco);

                  $("[name=cep_entrega]").val(result.endereco.cep);
                  $("[name=rua_entrega]").val(result.endereco.rua);
                  $("[name=numero_entrega]").val(result.endereco.numero);
                  $("[name=bairro_entrega]").val(result.endereco.bairro);
                  $("[name=cidade_entrega]").val(result.endereco.cidade);
                  $("[name=estado_entrega]").val(result.endereco.estado);
                  $("[name=obs]").val(result.endereco.complemento);
                  // $("[name=cidade_entrega]").val(result.endereco.cidade);

                  
              },

            });

        }



       }

       //verificando se possui bilhete

       function verifica_bilhete(){


        let checkbox = $('#possui_bilhete');

        if(checkbox.is(":checked")){
          $('#bilhete').hide();
        }else{
          $('#bilhete').show();
        }

       }

       //VALIDANDO CUPOM

       function aplica_cupom(cupom){
        // console.log(cupom)

        if(cupom){

              $.ajax({
                      url: '<?php echo base_url(['site/get-cupom']) ?>',
                      type: 'POST',
                      data: {
                        cupom: cupom,
                    },

                    success: function(result) {

                        result = JSON.parse(result)

                        if(!result.cupom){
                          alert('Cupom inválido!')
                        }else{

                          var total_pedido = $('#valor_total').val();

                          var total_cupom =  parseInt(result.cupom.porcentagem_desconto) * parseInt(total_pedido) / 100;

                          var total_menos_cupom = parseFloat(total_pedido) - parseFloat(total_cupom);

                          $('#valor_total').val(total_menos_cupom.toFixed(2));

                          $('#valor_desconto').val(result.cupom.porcentagem_desconto);

                          alert('Cupom de '+result.cupom.porcentagem_desconto+' % aplicado!')

                        }

                        
                    },

                  });


                }

       }

  </script>


  
