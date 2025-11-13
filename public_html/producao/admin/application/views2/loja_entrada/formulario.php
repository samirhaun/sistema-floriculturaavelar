<!-- Select2 -->
<!-- <link href="<?php echo base_url() ?>assets/css/plugins/select2/select2.min.css" rel="stylesheet"> -->


<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo (isset($produto)) ? 'Editar entrada de produto' : 'Nova entrada de produto' ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Loja</a>
            </li>
            <li>
                <a href="<?php echo base_url(array('loja', 'produtos-entrada')) ?>">produtos</a>
            </li>
            <li class="active">
                <strong><?php echo (isset($produto)) ? 'Editar entrada de produto' : 'Nova entrada de produto' ?></strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">
    </div>
</div>
<div class="wrapper wrapper-content animated">
    <div class="row">
                <form action="<?php echo base_url(array('loja', 'salvar-produto-entrada')) ?>" method="post" id="form-cadastro-produto" enctype="multipart/form-data">
                        <div class="col-lg-12">
                            <div class="tabs-container">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="active"><a class="nav-link active" data-toggle="tab" href="#tab-1" > Dados da entrada</a></li>
                                    <li><a class="nav-link" data-toggle="tab" href="#tab-2">Produtos entrada</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div role="tabpanel" id="tab-1" class="tab-pane active">
                                        <div class="panel-body">

                                       
                    
                                            <?php if (isset($produto)): ?>
                                                <input type="hidden" name="id" value="<?php echo $produto->id ?>">
                                            <?php endif ?>
                                            
                                            

                                            

                                            <div class="row">
                                                <div class="col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Descrição: *</label>
                                                        <input type="text" name="descricao" class="form-control" value="<?php echo (isset($produto)) ? $produto->descricao : '' ?>" required>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Data: *</label>
                                                        <input type="text" name="data" class="form-control datepicker" value="<?php echo (isset($produto)) ? date('d/m/Y',strtotime($produto->data_hora)) : date('d/m/Y') ?>" required>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Hora: *</label>
                                                        <input type="text" name="hora" class="form-control" value="<?php echo (isset($produto)) ? date('H:i',strtotime($produto->data_hora)) : date('H:i') ?>" required>
                                                    </div>
                                                </div>
                                                                
                                            </div>

                                    
                                           
                    
                   
                                        

                                        </div>
                                    </div>


                                    <!-- PRODUTOS VINCULADOS -->

                                    <div role="tabpanel" id="tab-2" class="tab-pane">
                                        <div class="panel-body produtos-vinculados">

                                            <!-- BOTOES ADD/REMOVE  -->
                                            <div class="col-sm-12 col-xs-2 text-right">
                                                <button type="button" onlick="alert()" class="btn btn-danger remove help" title="Remover">Remover <i class="fa fa-minus"></i></button>
                                                <button type="button" class="btn btn-success add-prod help" title="Adicionar">Adicionar <i class="fa fa-plus"></i></button>
                                            </div>

                                           <!-- INICIANDO QTD PRODUTO ATUAL -->

                                           <input type="hidden" id="contador_qtd_produtos" value="<?php echo (isset($produto)) ? sizeof($produtos_vinculados) : 0 ?>">

                                           <!-- CASO SEJA EDIÇÃO PRIMEIRO -->
                                           <?php 
                                                if(!empty($produtos_vinculados)): 
                                                    foreach($produtos_vinculados as $prod_vinc):
                                           ?>

                                            <!-- ITEM PRODUTO EDITANDO -->
                                            <div class="row item">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label class="control-label">Produto:</label>
                                                        <select 
                                                        required="" 
                                                        class="form-control produtos_select" 
                                                        name="produto_collapse[]"
                                                        >
                                                        <option value="">Selecione</option>

                                                        <?php foreach ($produtos as $key => $produto): ?>
                                                            <option 
                                                            <?php if ($prod_vinc->produtos_id == $produto->id){echo 'selected';} ?>
                                                            value="<?php echo $produto->id ?>">
                                                            <?php echo $produto->titulo; ?>
                                                            </option>
                                                        <?php endforeach ?>
                                                        
                                                        </select>
                                                    </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Quantidade:</label>
                                                        <input 
                                                        type="number" 
                                                        min="1"
                                                        name="quantidade_collapse[]" 
                                                        class="qtd_change form-control input_diminui" 
                                                        value="<?php echo $prod_vinc->qtd ?>"
                                                        >
                                                    </div>
                                                    </div>
                                                        
                                            </div>
                                            <!-- FIM ITEM PRODUTO EDITANDO -->


                                           <?php 
                                                    endforeach;
                                                endif;
                                           ?>
                                          
                                           <!-- ITEM PRODUTO NOVO -->
                                           <div class="row item">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label class="control-label">Produto:</label>
                                                        <select 
                                                        required="" 
                                                        class="form-control produtos_select" 
                                                        name="produto_collapse[]" 
                                                        id="produto_collapse_1"
                                                        >
                                                        <option value="">Selecione</option>

                                                        <?php foreach ($produtos as $key => $produto): ?>
                                                            <option value="<?php echo $produto->id ?>"><?php echo $produto->titulo; ?></option>
                                                        <?php endforeach ?>
                                                        
                                                        </select>
                                                    </div>
                                                    </div>

                                                    <div class="col-md-4">
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
                                                        
                                            </div>
                                            <!-- FIM ITEM PRODUTO -->


                                        </div>
                                    </div>

                                    <!-- FIM PRODUTOS VINCULADOS -->

                                    <div class="hr-line-dashed"></div>
                                            <div class="row">
                                                <div class="col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="text-right">
                                                            <a href="<?php echo base_url(array('loja', 'produtos-entrada')) ?>" class="btn btn-white">Cancelar</a>
                                                            <button class="btn btn-primary" type="submit">Salvar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>




                                </div>


                            </div>
                        </div>
                </form>



        
    </div>
</div>

<!-- TEMPLATE ITEM PRODUTO VINCULADO -->
<script type="text/template" id="item-prod-add">
            <!-- ITEM PRODUTO -->
            <div class="row item">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label">Produto:</label>
                        <select 
                        required="" 
                        class="form-control produtos_select" 
                        name="produto_collapse[]" 
                        >
                        <option value="">Selecione</option>

                        <?php foreach ($produtos as $key => $produto): ?>
                            <option value="<?php echo $produto->id ?>"><?php echo $produto->titulo; ?></option>
                        <?php endforeach ?>
                        
                        </select>
                    </div>
                    </div>

                    <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Quantidade:</label>
                        <input 
                        type="number" 
                        min="1"
                        name="quantidade_collapse[]" 
                        class="qtd_change form-control input_diminui" 
                        value="" 
                        required
                        onfocusout="lanca_valor_total_produto(this.value,1)"
                        >
                    </div>
                    </div>
                        
            </div>
            <!-- FIM ITEM PRODUTO -->
</script>


<script type="text/javascript">

    $('input').setMask();

    $('[name=data]').mask('00/00/0000');
    $('[name=hora]').mask('00:00');


    $('.remove').click(function(){

    if ($('.item').size() > 1) {
    $('.item').last().remove();
    }

    lanca_valor_total_produto(null,null);

    });

    var validator = $('#form-cadastro-produto').validate({
        ignore: [],
        rules: {
            valor: {
                required: true,
                min: 0.01
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

        const $form = $('#form-cadastro-produto');

        $()
      .add($form.find('[name$="data"]'))
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


    <?php if (!isset($produto)): ?>
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

    $.validator.addClassRules("summernote", {
        validate_note: true
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


    $('#imagem').on('change', function(){
        input = $(this);
        if (input[0].files && input[0].files[0]) {
            $('.img-prod').find('.col-sm-2').not('.server').remove();
            for(i=0; i < input[0].files.length; i++){
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('.img-prod').find('.row').append('<div class="col-sm-2"><div class="container-img"><img id="img-show" src="'+e.target.result+'"></div></div>');
                }

                reader.readAsDataURL(input[0].files[i]);
            }
        }else{
            $('.img-prod').find('.col-sm-2').not('.server').remove();
        }
    });

    // $('.close-img').on('click', function(){
    //     $('#form-cadastro-produto').append('<input type="hidden" name="remove_imagem[]" value="'+$(this).data('id')+'" />');
    //     $('#form-cadastro-produto').append('<input type="hidden" name="nome_imagem[]" value="'+$(this).data('nome')+'" />');
    //     $(this).parents('.server').remove();
    // });
</script>
<!-- <script src="<?php echo base_url() ?>assets/js/plugins/select2/select2.full.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script> -->

<script language="javascript" type="text/javascript">
    /*
$('#galeria').on('change', function(){
  input = $(this);
  if (input[0].files && input[0].files[0]) {
      $('.galeria-preview').find('.col-sm-2').not('.server').remove();
      for(i=0; i < input[0].files.length; i++){
          var reader = new FileReader();

          reader.onload = function (e) {
              $('.galeria-preview').find('.row').append('<div class="col-sm-2"><div class="container-img"><img id="img-show" src="'+e.target.result+'"></div></div>');
          }

          reader.readAsDataURL(input[0].files[i]);
      }
  }else{
      $('.galeria-preview').find('.col-sm-2').not('.server').remove();
  }
});
*/

$(".select2_demo_1").select2();
$(".select2_demo_2").select2();
$(".select2_demo_3").select2({
    placeholder: "Select a state",
    allowClear: true
});





/*********  PRODUTOS VINCULADOS ***********/

//SELECT 2
// $(".produtos_select").select2({
//         placeholder: "Selecione um produto",
//         allowClear: true
// });

/* ADICIONANDO MAIS PRODUTOS */
$('.produtos-vinculados').on('click', '.add-prod', function(){

  //PEGANDO O CONTADOR DOS PRODUTOS
  var contador_products = parseInt ($("#contador_qtd_produtos").val()) + 1;


  //ADICIONANDO A DIV COM O CONTEUDO
  $('.produtos-vinculados').append($(
      $('#item-prod-add').html()
    )
  );

 


  //ATUALIZANDO QTD DE PRODUTOS
  $("#contador_qtd_produtos").val(contador_products);


// $(document).ready(function() {
//         $('.js-example-basic-single-modelo').select2().on('change', function (e) {
//           modelo_valor($(this))
//       })
//     });
});


</script>
