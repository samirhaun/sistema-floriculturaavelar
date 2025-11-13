<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Cadastro de Cliente</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url('home') ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url(array('loja', 'clientes')) ?>">Clientes</a>
            </li>
            <li class="active">
                <strong><?php echo (isset($cliente)) ? 'Editar cadastro de Clientes' : 'Novo cadastro de Clientes' ?></strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">
    </div>
</div>
<div class="wrapper wrapper-content animated">
    <div class="row">
        <div class="col-lg-12">

              <div class="tabs-container">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="active"><a class="nav-link active" data-toggle="tab" href="#tab-1" > Dados do cliente</a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#tab-2">Endereço(s) do cliente</a></li>
                    </ul>

                    <form action="<?php echo base_url(array('loja', 'salvar-cliente')) ?>" method="post" id="form-cadastro-categoria" enctype="multipart/form-data">
                    
                    <?php if (isset($cliente)): ?>
                            <input type="hidden" name="id" value="<?php echo $cliente->id ?>">
                    <?php endif ?>

                    <div class="tab-content">
                    
                        <div role="tabpanel" id="tab-1" class="tab-pane active">
                            <div class="panel-body">
                                
                            <div class="row">
                            <div class="col-sm-3 col-xs-4">
                                <div class="form-group">
                                    <label class="control-label">Nome: *</label>
                                    <input type="text" name="nome" class="form-control" value="<?php echo (isset($cliente)) ? $cliente->nome : '' ?>" required>
                                </div>
                            </div>

                            <div class="col-sm-3 col-xs-4">
                                <div class="form-group">
                                    <label class="control-label">E-mail: </label>
                                    <input type="text" name="email" class="form-control" value="<?php echo (isset($cliente)) ? $cliente->email : '' ?>" >
                                </div>
                            </div>

                            <div class="col-sm-3 col-xs-4">
                                <div class="form-group">
                                    <label class="control-label">Telefone: *</label>
                                    <input type="text" name="telefone" class="form-control" value="<?php echo (isset($cliente)) ? $cliente->telefone : '' ?>" required>
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-4">
                                <div class="form-group">
                                    <label class="control-label">CPF: </label>
                                    <input type="text" name="cpf" class="form-control" value="<?php echo (isset($cliente)) ? $cliente->cpf : '' ?>" >
                                </div>
                            </div>

                        </div>

                            </div>
                        </div>

                        <div role="tabpanel" id="tab-2" class="tab-pane">
                            <div class="panel-body enderecos-vinculados">

                            <!-- BOTOES ADD/REMOVE  -->
                            <div class="col-sm-12 col-xs-2 text-right">
                                <button type="button" onlick="alert()" class="btn btn-danger remove help" title="Remover">Remover <i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-success add-prod help" title="Adicionar">Adicionar <i class="fa fa-plus"></i></button>
                            </div>

                            <!-- INICIANDO QTD PRODUTO ATUAL -->

                            <input type="hidden" id="contador_qtd_enderecos" value="<?php echo (isset($produto)) ? sizeof($produtos_vinculados) : 0 ?>">


                            <!-- CASO SEJA EDIÇÃO PRIMEIRO -->
                            <?php 
                                if(!empty($enderecos_vinculados)): 
                                    foreach($enderecos_vinculados as $end_vinc):
                            ?>

                            <!-- ITEM EDITANDO  -->

                            <input type="hidden" name="ideditando[]" value="<?php echo $end_vinc->id ?>">

                            <div class="row item">

                                <div class="col-sm-3 col-xs-8">
                                    <div class="form-group">
                                        <label class="control-label">Descrição do endereço:</label>
                                        <input type="text" name="descricao[]" class="form-control" value="<?php echo $end_vinc->descricao ?>" >
                                    </div>
                                </div>

                                <div class="col-sm-2 col-xs-8">
                                    <div class="form-group">
                                        <label class="control-label">Cep:</label>
                                        <input type="text" name="cep[]" class="form-control cep" value="<?php echo $end_vinc->cep ?>" >
                                    </div>
                                </div>
                                
                        
                                <div class="col-sm-4 col-xs-8">
                                    <div class="form-group">
                                        <label class="control-label">Rua:</label>
                                        <input type="text" name="rua[]" class="form-control" value="<?php echo $end_vinc->rua ?>" >
                                    </div>
                                </div>
                                
                            
                                <div class="col-sm-3 col-xs-4">
                                    <div class="form-group">
                                        <label class="control-label">Número: </label>
                                        <input type="text" name="numero[]" class="form-control" value="<?php echo $end_vinc->numero ?>" >
                                    </div>
                                </div>

                                <div class="col-sm-3 col-xs-8">
                                    <div class="form-group">
                                        <label class="control-label">Bairro:</label>
                                        <input type="text" name="bairro[]" class="form-control" value="<?php echo $end_vinc->bairro ?>" >
                                    </div>
                                    
                                </div>

                                <div class="col-sm-3 col-xs-8">
                                    <div class="form-group">
                                        <label class="control-label">Cidade:</label>
                                        <input type="text" name="cidade[]" class="form-control" value="<?php echo $end_vinc->cidade ?>" >
                                    </div>
                                    
                                </div>

                                <div class="col-sm-2 col-xs-8">
                                    <div class="form-group">
                                        <label class="control-label">Estado:</label>
                                        <input type="text" name="estado[]" class="form-control" value="<?php echo $end_vinc->estado ?>" >
                                    </div>
                                    
                                </div>

                                <div class="col-sm-4 col-xs-8">
                                    <div class="form-group">
                                        <label class="control-label">Complemento:</label>
                                        <input type="text" name="complemento[]" class="form-control" value="<?php echo $end_vinc->complemento ?>" >
                                    </div>
                                    
                                </div>

                              </div>

                              <div class="hr-line-dashed"></div>

                           

                            <?php 
                                    endforeach;
                                endif;
                            ?>


                            <!-- ITEM NOVO  -->

                            <input type="hidden" name="ideditando[]" value="0">

                            <div class="row item">

                                <div class="col-sm-3 col-xs-8">
                                    <div class="form-group">
                                        <label class="control-label">Descrição do endereço:</label>
                                        <input type="text" name="descricao[]" class="form-control" value="" >
                                    </div>
                                </div>

                                <div class="col-sm-2 col-xs-8">
                                    <div class="form-group">
                                        <label class="control-label">Cep:</label>
                                        <input type="text" name="cep[]" class="form-control cep" value="" >
                                    </div>
                                </div>
                                
                        
                                <div class="col-sm-4 col-xs-8">
                                    <div class="form-group">
                                        <label class="control-label">Rua:</label>
                                        <input type="text" name="rua[]" class="form-control" value="" >
                                    </div>
                                </div>
                                
                            
                                <div class="col-sm-3 col-xs-4">
                                    <div class="form-group">
                                        <label class="control-label">Número: </label>
                                        <input type="text" name="numero[]" class="form-control" value="" >
                                    </div>
                                </div>

                                <div class="col-sm-3 col-xs-8">
                                    <div class="form-group">
                                        <label class="control-label">Bairro:</label>
                                        <input type="text" name="bairro[]" class="form-control" value="" >
                                    </div>
                                    
                                </div>

                                <div class="col-sm-3 col-xs-8">
                                    <div class="form-group">
                                        <label class="control-label">Cidade:</label>
                                        <input type="text" name="cidade[]" class="form-control" value="" >
                                    </div>
                                    
                                </div>

                                <div class="col-sm-2 col-xs-8">
                                    <div class="form-group">
                                        <label class="control-label">Estado:</label>
                                        <input type="text" name="estado[]" class="form-control" value="" >
                                    </div>
                                    
                                </div>

                                <div class="col-sm-4 col-xs-8">
                                    <div class="form-group">
                                        <label class="control-label">Complemento:</label>
                                        <input type="text" name="complemento[]" class="form-control" value="" >
                                    </div>
                                    
                                </div>

                              </div>

                            </div>
                        </div>

                    </div>

                    <div class="hr-line-dashed"></div>

                    <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="text-right">
                                        <a href="<?php echo base_url(array('loja', 'clientes')) ?>" class="btn btn-white">Cancelar</a>
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


<!-- TEMPLATE ITEM PRODUTO VINCULADO -->
<script type="text/template" id="item-end-add">

<!-- ITEM NOVO  -->

<input type="hidden" name="ideditando[]" value="0">

<div class="hr-line-dashed"></div>

<div class="row item">

<div class="col-sm-3 col-xs-8">
    <div class="form-group">
        <label class="control-label">Descrição do endereço:</label>
        <input type="text" name="descricao[]" class="form-control" value="" >
    </div>
</div>

<div class="col-sm-2 col-xs-8">
    <div class="form-group">
        <label class="control-label">Cep:</label>
        <input type="text" name="cep[]" class="form-control cep" value="" >
    </div>
</div>


<div class="col-sm-4 col-xs-8">
    <div class="form-group">
        <label class="control-label">Rua:</label>
        <input type="text" name="rua[]" class="form-control" value="" >
    </div>
</div>


<div class="col-sm-3 col-xs-4">
    <div class="form-group">
        <label class="control-label">Número: </label>
        <input type="text" name="numero[]" class="form-control" value="" >
    </div>
</div>

<div class="col-sm-3 col-xs-8">
    <div class="form-group">
        <label class="control-label">Bairro:</label>
        <input type="text" name="bairro[]" class="form-control" value="" >
    </div>
    
</div>

<div class="col-sm-3 col-xs-8">
    <div class="form-group">
        <label class="control-label">Cidade:</label>
        <input type="text" name="cidade[]" class="form-control" value="" >
    </div>
    
</div>

<div class="col-sm-2 col-xs-8">
    <div class="form-group">
        <label class="control-label">Estado:</label>
        <input type="text" name="estado[]" class="form-control" value="" >
    </div>
    
</div>

<div class="col-sm-4 col-xs-8">
    <div class="form-group">
        <label class="control-label">Complemento:</label>
        <input type="text" name="complemento[]" class="form-control" value="" >
    </div>
    
</div>

</div>

</script>


<script type="text/javascript">

$('input[name="cpf"]').mask('000.000.000-00', {reverse: true});
  $('input[name="telefone"]').mask('(00) 00000-0000');
  $('.cep').mask('00000-000');

</script>
<script type="text/javascript">
    var teste;
    $('input[type="text"]').setMask();
    $("#form-cadastro-categoria").validate({});

    <?php if (!isset($cliente)): ?>
        $.validator.addClassRules("file", {
            validate_file: true
        });
    <?php endif ?>

    //metodo para validar o valor
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

    $('#imagem').on('change', function(){
        input = $(this);
        if (input[0].files && input[0].files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.lightBoxGallery').find('img').remove();
                $('.lightBoxGallery').append('<img id="img-show" src="'+e.target.result+'">');
            }

            reader.readAsDataURL(input[0].files[0]);
        }else{
            $('.lightBoxGallery').find('img').remove();
        }
    });


    $(document).ready(function(){





function limpa_formulário_cep() {
           // Limpa valores do formulário de cep.
           $("[name=rua]").val("");
           $("[name=bairro]").val("");
           $("[name=cidade]").val("");
           $("[name=estado]").val("");
           $("#ibge").val("");
       }

       //Quando o campo cep perde o foco.
       $("[name=cep222]").blur(function() {

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



/* ADICIONANDO MAIS PRODUTOS */
$('.enderecos-vinculados').on('click', '.add-prod', function(){

//PEGANDO O CONTADOR DOS PRODUTOS
var contador_enderecos = parseInt ($("#contador_qtd_enderecos").val()) + 1;


//ADICIONANDO A DIV COM O CONTEUDO
$('.enderecos-vinculados').append($(
    $('#item-end-add').html()
  )
);




//ATUALIZANDO QTD DE PRODUTOS
$("#contador_qtd_enderecos").val(contador_enderecos);


// $(document).ready(function() {
//         $('.js-example-basic-single-modelo').select2().on('change', function (e) {
//           modelo_valor($(this))
//       })
//     });
});

</script>
