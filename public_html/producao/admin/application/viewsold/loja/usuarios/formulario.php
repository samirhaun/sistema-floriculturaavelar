<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Cadastro de usuario</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url('home') ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url(array('loja', 'usuarios')) ?>">usuarios</a>
            </li>
            <li class="active">
                <strong><?php echo (isset($usuario)) ? 'Editar cadastro de usuarios' : 'Novo cadastro de usuarios' ?></strong>
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
                    <form action="<?php echo base_url(array('loja', 'salvar-usuario')) ?>" method="post" id="form-cadastro-categoria" enctype="multipart/form-data">
                        <?php if (isset($usuario)): ?>
                            <input type="hidden" name="id" value="<?php echo $usuario->id ?>">
                        <?php endif ?>
                        <div class="hr-line-dashed"></div>
                        <div class="row">
                            <div class="col-sm-3 col-xs-4">
                                <div class="form-group">
                                    <label class="control-label">Nome: *</label>
                                    <input type="text" name="nome" class="form-control" value="<?php echo (isset($usuario)) ? $usuario->nome : '' ?>" required>
                                </div>
                            </div>

                             <div class="col-sm-3 col-xs-4">
                                <div class="form-group">
                                    <label class="control-label">E-mail: </label>
                                    <input type="text" name="email" class="form-control" value="<?php echo (isset($usuario)) ? $usuario->email : '' ?>" >
                                </div>
                            </div>

                            <?php if (!isset($usuario)): ?>
                                <div class="col-sm-3 col-xs-4">
                                <div class="form-group">
                                    <label class="control-label">Senha: </label>
                                    <input type="text" name="senha" class="form-control" value="" >
                                </div>
                            </div>
                            <?php endif ?>


                             <div class="col-sm-3 col-xs-4">
                                <div class="form-group">
                                    <label class="control-label">CPF: *</label>
                                    <input type="text" name="cpf" class="form-control" value="<?php echo (isset($usuario)) ? $usuario->cpf : '' ?>" required>
                                </div>
                            </div>

                            <div class="col-sm-3 col-xs-4">
                                <div class="form-group">
                                    <label class="control-label">Desconto máximo permitido: *</label>
                                    <input type="text" name="desconto_maximo" class="form-control" value="<?php echo (isset($usuario)) ? $usuario->desconto_maximo : '0' ?>" required>
                                </div>
                            </div>

                        </div>

                        <div class="hr-line-dashed"></div>
                     

                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="text-right">
                                        <a href="<?php echo base_url(array('loja', 'usuarios')) ?>" class="btn btn-white">Cancelar</a>
                                        <button class="btn btn-primary" type="submit">Salvar</button>
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

     $('input[name="cpf"]').mask('000.000.000-00', {reverse: true});

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
