
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.js"></script>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo (isset($noticia)) ? 'Editar cadastro do notícia' : 'Novo cadastro de notícia' ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Site</a>
            </li>
            <li>
                <a href="<?php echo base_url(array('site', 'noticias')) ?>">Notícias</a>
            </li>
            <li class="active">
                <strong><?php echo (isset($noticia)) ? 'Editar cadastro da noticia' : 'Novo cadastro de noticia' ?></strong>
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
                    <form action="<?php echo base_url(array('site', 'salvar-noticia')) ?>" method="post" id="form-cadastro-noticia" enctype="multipart/form-data">
                        <?php if (isset($noticia)): ?>
                            <input type="hidden" name="id" value="<?php echo $noticia->id ?>">
                        <?php endif ?>
                        <div class="hr-line-dashed"></div>
                        <div class="row">
                            <div class="col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Titulo: *</label>
                                    <input type="text" name="titulo" class="form-control" value="<?php echo (isset($noticia)) ? $noticia->titulo : '' ?>">
                                </div>
                            </div> <div class="col-sm-2 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Data: *</label>
                                    <input type="text" name="data" id="data" class="form-control" value="<?php 

                                        if(isset($noticia)){

                                            $date = new DateTime($noticia->data);
                                            echo $date->format('d-m-Y');
                                            }else{
                                                echo '';
                                            }

                                     ?>">
                                </div>
                            </div>

                                <div class="col-sm-12 col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">Introdução: * (texto breve com poucas palavras e sem imagens)</label>
                                        <textarea name="introducao" style="width: 100%" rows="6" class=""><?php echo isset($noticia) ? $noticia->texto_breve : '' ?></textarea>
                                    </div>
                                </div>
                            
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="row">
                            <div class="col-sm-12 error-file">
                                <label class="control-label">Imagem destaque: * (1170px X 700px)</label>
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">Selecione a imagem destaque</span>
                                        <span class="fileinput-exists">Alterar</span>
                                        <input type="file" id="noticia" name="noticia" class="file" />
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 preview-image">
                                <div class="lightBoxGallery">
                                    <?php if (isset($noticia)): ?>
                                        <img id="img-show" src="<?php echo base_url(array('../', 'assets', 'images', 'noticias', $noticia->imagem)) ?>">
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>

                        <?php if (isset($noticia)): ?>
                            <input type="hidden" name="imagem_noticia" value="<?php echo $noticia->imagem ?>">
                        <?php endif ?>
                        <div class="hr-line-dashed"></div>
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Conteúdo: *</label>
                                    <textarea name="conteudo" class="summernote"><?php echo isset($noticia) ? $noticia->texto : '' ?></textarea>
                                </div>
                            </div>
                        </div>
                       <div class="hr-line-dashed"></div>
                           
                        <div class="hr-line-dashed"></div>
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="text-right">
                                        <a href="<?php echo base_url(array('site', 'noticias')) ?>" class="btn btn-white">Cancelar</a>
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
    $('input[type="text"]').setMask();
    $("#form-cadastro-noticia").validate({});

    $(document).ready(function(){
        $('.summernote').summernote({
            height: 300,
        });
    });

    <?php if (!isset($noticia)): ?>
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
        $(".error-file").append('<label id="noticia-error" class="error" for="noticia"></label>');
        return false;
    }, "Este campo é obrigatório.");

    $('#noticia').on('change', function(){
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

<script type="text/javascript">
    $(document).ready(function(){
  $('#data').mask('00-00-0000');

});
</script>
