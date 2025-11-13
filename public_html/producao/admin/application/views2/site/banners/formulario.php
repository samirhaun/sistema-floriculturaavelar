<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo (isset($banner)) ? 'Editar cadastro do banner' : 'Novo cadastro de banner' ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Site</a>
            </li>
            <li>
                <a href="<?php echo base_url(array('site', 'banners')) ?>">banners</a>
            </li>
            <li class="active">
                <strong><?php echo (isset($banner)) ? 'Editar cadastro do banner' : 'Novo cadastro de banner' ?></strong>
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
                    <form action="<?php echo base_url(array('site', 'salvar-banner')) ?>" method="post" id="form-cadastro-banner" enctype="multipart/form-data">
                        <?php if (isset($banner)): ?>
                            <input type="hidden" name="id" value="<?php echo $banner->id ?>">
                        <?php endif ?>
                        <div class="hr-line-dashed"></div>
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Titulo:</label>
                                    <input type="text" name="titulo" class="form-control" value="<?php echo (isset($banner)) ? $banner->titulo : '' ?>">
                                </div>
                            </div>

                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Subtítulo (limitar em até 40 caracteres):</label>
                                    <input type="text" name="texto1" class="form-control" value="<?php echo (isset($banner)) ? $banner->texto1 : '' ?>">
                                </div>
                            </div>

                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">link:</label>
                                    <input type="text" name="link" class="form-control" value="<?php echo (isset($banner)) ? $banner->link : '' ?>">
                                </div>
                            </div>

                            

                        </div>

                       <!--  <div class="row">
                            

                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Texto 2 (limitar em até 80 caracteres):</label>
                                    <input type="text" name="texto2" class="form-control" value="<?php echo (isset($banner)) ? $banner->texto2 : '' ?>">
                                </div>
                            </div>
 
                            

                            <div class="col-sm-2 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Posição informações:</label>
                                    <select name="posicao_informacoes" class="form-control">
                                        <option <?php echo (isset($banner) && $banner->posicao_informacoes == 'esquerda') ? 'selected="selected"' : '' ?> value="esquerda">Esquerda</option>
                                        <option <?php echo (isset($banner) && $banner->posicao_informacoes == 'direita') ? 'selected="selected"' : '' ?> value="direita">Direita</option>
                                    </select>
                                </div>
                            </div> 

                        </div> -->

                        <div class="hr-line-dashed"></div>
                        <div class="row">
                            <div class="col-sm-12 error-file">
                                <label class="control-label">Banner: * (1920px X 660px)</label>
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">Selecione o banner</span>
                                        <span class="fileinput-exists">Alterar</span>
                                        <input type="file" id="banner" name="banner" class="file" />
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 preview-image">
                                <div class="lightBoxGallery">
                                    <?php if (isset($banner)): ?>
                                        <img id="img-show" src="<?php echo base_url(array('../', 'assets', 'images', 'banners', $banner->imagem)) ?>">
                                    <?php endif ?>

                                    <!-- <div id="blueimp-gallery" class="blueimp-gallery">
                                        <div class="slides"></div>
                                        <h3 class="title">Banner</h3>
                                        <a class="prev">‹</a>
                                        <a class="next">›</a>
                                        <a class="close">×</a>
                                        <a class="play-pause"></a>
                                        <ol class="indicator"></ol>
                                    </div> -->

                                </div>
                            </div>
                        </div>

                        <?php if (isset($banner)): ?>
                            <input type="hidden" name="imagem_banner" value="<?php echo $banner->imagem ?>">
                        <?php endif ?>
                        <div class="hr-line-dashed"></div>
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="text-right">
                                        <a href="<?php echo base_url(array('site', 'banners')) ?>" class="btn btn-white">Cancelar</a>
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

<script type="text/javascript">
var teste;
    $('input[type="text"]').setMask();
    $("#form-cadastro-banner").validate({});

    <?php if (!isset($banner)): ?>
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
        $(".error-file").append('<label id="banner-error" class="error" for="banner"></label>');
        return false;
    }, "Este campo é obrigatório.");

    $('#banner').on('change', function(){
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
