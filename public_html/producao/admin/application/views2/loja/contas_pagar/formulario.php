<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo (isset($dados)) ? 'Editar cadastro de conta a pagar' : 'Novo cadastro de conta a pagar' ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Loja</a>
            </li>
            <li>
                <a href="<?php echo base_url(array('loja', 'contas_pagar')) ?>">Contas a pagar</a>
            </li>
            <li class="active">
                <strong><?php echo (isset($dados)) ? 'Editar cadastro de conta a pagar' : 'Novo cadastro de conta a pagar' ?></strong>
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
                    <form action="<?php echo base_url(array('loja', 'salvar-contas_pagar')) ?>" method="post" id="form-cadastro-categoria" enctype="multipart/form-data">
                        <?php if (isset($dados)): ?>
                            <input type="hidden" name="id" value="<?php echo $dados->id ?>">
                        <?php endif ?>
                        <div class="hr-line-dashed"></div>
                        <div class="row">


                        <div class="col-md-3">
                            <div class="form-group">
                            <label class="control-label">Fornecedor:</label>
                            <select required="" class="form-control" name="fornecedores_id">
                                <option value="">Selecione</option>

                                <?php foreach ($fornecedores as $key => $fornecedor): ?>
                                <option <?php if (isset($dados) && $dados->fornecedores_id == $fornecedor->id){echo 'selected';} ?> value="<?php echo $fornecedor->id ?>"><?php echo $fornecedor->nome; ?></option>
                                <?php endforeach ?>
                                
                            </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                            <label class="control-label">Plano de conta:</label>
                            <select required="" class="form-control" name="plano_contas_id">
                                <option value="">Selecione</option>

                                <?php foreach ($plano_contas as $key => $plano_conta): ?>
                                <option <?php if (isset($dados) && $dados->plano_contas_id == $plano_conta->id){echo 'selected';} ?> value="<?php echo $plano_conta->id ?>"><?php echo $plano_conta->cod; ?> - <?php echo $plano_conta->descricao; ?></option>
                                <?php endforeach ?>
                                
                            </select>
                            </div>
                        </div>

                        <div class="col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Data Vencimento: *</label>
                                    <input type="text" name="data_vencimento" class="form-control data" value="<?php echo (isset($dados)) ? inverteData($dados->data_vencimento,'/') : '' ?>" required>
                                </div>
                            </div>

                            <div class="col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Valor: *</label>
                                    <input type="text" name="valor" class="form-control valor_mask" value="<?php echo (isset($dados)) ? $dados->valor : '' ?>" required>
                                </div>
                            </div>


                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Descrição: *</label>
                                    <input type="text" name="descricao" class="form-control" value="<?php echo (isset($dados)) ? $dados->descricao : '' ?>" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                            <div class="form-group">
                            <label class="control-label">Pago:</label>
                            <select required="" class="form-control" name="status" id="status">
                                <option <?php if (isset($dados) && $dados->status == 0){echo 'selected';} ?> value="0">Não</option>
                                <option <?php if (isset($dados) && $dados->status == 1){echo 'selected';} ?> value="1">Sim</option>
                            </select>
                            </div>
                            </div>


                            <div class="col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Data Pago:</label>
                                    <input type="text" name="data_pago" id="data_pago" class="form-control data" value="<?php echo (isset($dados->data_pago)) ? inverteData($dados->data_pago,'/') : '' ?>">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                <label class="control-label">Forma de pagamento:</label>
                                <select required="" class="form-control" name="forma_pgto">
                                    <option value="">Selecione</option>
                                    <option  <?php if (isset($dados) && $dados->forma_pgto == 1){echo 'selected';} ?> value="1">Dinheiro</option>
                                    <option  <?php if (isset($dados) && $dados->forma_pgto == 9){echo 'selected';} ?> value="9">Pix</option>
                                    <option <?php if (isset($dados) && $dados->forma_pgto == 2){echo 'selected';} ?> value="2">Débito</option>
                                    <option <?php if (isset($dados) && $dados->forma_pgto == 3){echo 'selected';} ?> value="3">Crédito 1x</option>
                                    <option <?php if (isset($dados) && $dados->forma_pgto == 4){echo 'selected';} ?> value="4">Crédito 2x</option>
                                    <option <?php if (isset($dados) && $dados->forma_pgto == 5){echo 'selected';} ?> value="5">Crédito 3x</option>
                                    <option <?php if (isset($dados) && $dados->forma_pgto == 6){echo 'selected';} ?> value="6">Crédito 4x</option>
                                    <option <?php if (isset($dados) && $dados->forma_pgto == 7){echo 'selected';} ?> value="7">Duplicata</option>
                                    <option <?php if (isset($dados) && $dados->forma_pgto == 8){echo 'selected';} ?> value="8">Cheque</option>
                                    
                                </select>
                                </div>
                            </div>


                           
                        </div>

                        <div class="hr-line-dashed"></div>
                     

                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="text-right">
                                        <a href="<?php echo base_url(array('loja', 'contas_pagar')) ?>" class="btn btn-white">Cancelar</a>
                                        <button class="btn btn-primary" type="button" onclick="salva_form()">Salvar</button>
                                    </div>
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

<script type="text/javascript">

    function salva_form(){

       var pago = $("#status").val();
       var data_pago = $("#data_pago").val();

       if(pago == '1' && data_pago == ''){
         alert('Data Pago obrigatória!');
         $("#data_pago").focus();
       }else{
        $('#form-cadastro-categoria').submit();
       }

    }


$(function() {

    $("[name=fornecedores_id]").select2({
        placeholder: "Selecione um fornecedor",
        allowClear: true
    });

    $("[name=plano_contas_id]").select2({
        placeholder: "Selecione um plano de conta",
        allowClear: true
    });

    const $form = $('#form-cadastro-categoria');

    $()
    .add($form.find('[name$="data_vencimento"]'))
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

    $('.data').mask('00/00/0000');


    

});

     $('input[name="cpf"]').mask('000.000.000-00', {reverse: true});
     $('.valor_mask').mask('00000,00', {reverse: true});

var teste;
    $('input[type="text"]').setMask();
    $("#form-cadastro-categoria").validate({});

    <?php if (!isset($usuario)): ?>
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
</script>
