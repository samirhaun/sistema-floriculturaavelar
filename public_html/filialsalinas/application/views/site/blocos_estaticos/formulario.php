<style type="text/css">
    .div_imagem{
        text-align: center;
    }

    .preview-image .div_imagem img {
    height: 100px;
    }

    .div_imagem img {
    margin: 5px;
    }

</style>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Edição de bloco estático</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Site</a>
            </li>

            <li class="active">
                <strong>Edição de bloco "<?php echo $bloco->nome_secao ?>"</strong>
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
                    <form action="<?php echo base_url(array('site', 'atualizar-blocos-estaticos')) ?>" method="post" id="form-cadastro-banner" enctype="multipart/form-data">
                        
                        <input type="hidden" name="nome_unico" value="<?php echo $bloco->nome_unico ?>">
                        <input type="hidden" name="width_img1" value="<?php echo $bloco->width_img1 ?>">
                        <input type="hidden" name="height_img1" value="<?php echo $bloco->height_img1 ?>">

                        <div class="hr-line-dashed"></div>


                        <?php if(!empty($bloco->titulo_1)): ?>


                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Titulo 1:</label>
                                    <input type="text" name="titulo_1" class="form-control" value="<?php echo (isset($bloco)) ? $bloco->titulo_1 : '' ?>">
                                </div>
                            </div>

                        </div>


                       <?php endif; ?>

                       <?php if(!empty($bloco->texto_1)): ?>

                        <div class="row">
                            

                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Texto  1:</label>
                                    <textarea style="height:150px;" name="texto_1" class="form-control" cols="50"><?php echo (isset($bloco)) ? $bloco->texto_1 : '' ?></textarea>
                                </div>
                            </div>


                        </div>

                        <?php endif; ?>




                        <?php if(!empty($bloco->titulo_2)): ?>


                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Titulo 2:</label>
                                    <input type="text" name="titulo_2" class="form-control" value="<?php echo (isset($bloco)) ? $bloco->titulo_2 : '' ?>">
                                </div>
                            </div>



                        </div>

                        <?php endif; ?>


                        <?php if(!empty($bloco->texto_2)): ?>

                        <div class="row">
                            

                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Texto  2:</label>
                                    <textarea style="height:150px;" name="texto_2" class="form-control" cols="50"><?php echo (isset($bloco)) ? $bloco->texto_2 : '' ?></textarea>
                                </div>
                            </div>


                        </div>

                        <?php endif; ?>


                        <?php if(!empty($bloco->texto_3)): ?>

                        <div class="row">
                            

                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Texto  3 (limitar em até 400 caracteres):</label>
                                    <textarea style="height:150px;" name="texto_3" class="form-control" cols="50"><?php echo (isset($bloco)) ? $bloco->texto_3 : '' ?></textarea>
                                </div>
                            </div>


                        </div>

                        <?php endif; ?>




                        <?php if(!empty($bloco->texto_4)): ?>

                        <div class="row">
                            

                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Texto  4 (limitar em até 400 caracteres):</label>
                                    <textarea style="height:150px;" name="texto_4" class="form-control" cols="50"><?php echo (isset($bloco)) ? $bloco->texto_4 : '' ?></textarea>
                                </div>
                            </div>


                        </div>

                        <?php endif; ?>


                        <?php if(!empty($bloco->titulo_3)): ?>


                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Titulo 3:</label>
                                    <input type="text" name="titulo_3" class="form-control" value="<?php echo (isset($bloco)) ? $bloco->titulo_3 : '' ?>">
                                </div>
                            </div>



                        </div>

                        <?php endif; ?>


                        <?php if(!empty($bloco->texto_longo_1)): ?>

                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Texto longo 1: *</label>
                                    <textarea name="texto_longo_1" class="summernote"><?php echo isset($bloco) ? $bloco->texto_longo_1 : '' ?></textarea>
                                </div>
                            </div>
                        </div>

                        <?php endif; ?>



                        <div class="hr-line-dashed"></div>
                        <div class="row">
                            <div class="col-sm-12 error-file">
                                <label class="control-label">Imagem 1: * (<?php echo (isset($bloco)) ? $bloco->width_img1 : '' ?>px X <?php echo (isset($bloco)) ? $bloco->height_img1 : '' ?>px)</label>
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">Selecione a imagem</span>
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
                                    <?php if (isset($bloco)): ?>
                                        <img id="img-show" src="<?php echo base_url(array('../', 'assets', 'images', 'blocos', $bloco->imagem_1)) ?>">
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

                        <?php if (isset($bloco)): ?>
                            <input type="hidden" name="imagem_banner" value="<?php echo $bloco->imagem_1 ?>">
                        <?php endif ?>



                        <?php if(!empty($bloco->imagem_2)): ?>


                        <div class="row">
                            <div class="col-sm-12 error-file">
                                <label class="control-label">Imagem 2: * (<?php echo (isset($bloco)) ? $bloco->width_img2 : '' ?>px X <?php echo (isset($bloco)) ? $bloco->height_img2 : '' ?>px)</label>
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">Selecione a imagem</span>
                                        <span class="fileinput-exists">Alterar</span>
                                        <input type="file" id="banner2" name="banner2" class="file" />
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 preview-image">
                                <div class="lightBoxGallery2 div_imagem">
                                    <?php if (isset($bloco)): ?>
                                        <img id="img-show" src="<?php echo base_url(array('../', 'assets', 'images', 'blocos', $bloco->imagem_2)) ?>">
                                    <?php endif ?>

                                </div>
                            </div>
                        </div>

                        <?php if (isset($bloco->imagem_2)): ?>
                            <input type="hidden" name="imagem_banner2" value="<?php echo $bloco->imagem_2 ?>">
                        <?php endif ?>
                        


                        <?php endif; ?>



                        <?php if(!empty($bloco->imagem_3)): ?>


                        <div class="row">
                            <div class="col-sm-12 error-file">
                                <label class="control-label">Imagem 3: * (<?php echo (isset($bloco)) ? $bloco->width_img3 : '' ?>px X <?php echo (isset($bloco)) ? $bloco->height_img3 : '' ?>px)</label>
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">Selecione a imagem</span>
                                        <span class="fileinput-exists">Alterar</span>
                                        <input type="file" id="banner3" name="banner3" class="file" />
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 preview-image">
                                <div class="lightBoxGallery3 div_imagem">
                                    <?php if (isset($bloco)): ?>
                                        <img id="img-show" src="<?php echo base_url(array('../', 'assets', 'images', 'blocos', $bloco->imagem_3)) ?>">
                                    <?php endif ?>

                                </div>
                            </div>
                        </div>

                        <?php if (isset($bloco->imagem_3)): ?>
                            <input type="hidden" name="imagem_banner3" value="<?php echo $bloco->imagem_3 ?>">
                        <?php endif ?>
                        


                        <?php endif; ?>




                        <?php if(!empty($bloco->imagem_4)): ?>


                        <div class="row">
                            <div class="col-sm-12 error-file">
                                <label class="control-label">Imagem 4: * (<?php echo (isset($bloco)) ? $bloco->width_img4 : '' ?>px X <?php echo (isset($bloco)) ? $bloco->height_img4 : '' ?>px)</label>
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">Selecione a imagem</span>
                                        <span class="fileinput-exists">Alterar</span>
                                        <input type="file" id="banner3" name="banner4" class="file" />
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 preview-image">
                                <div class="lightBoxGallery4 div_imagem">
                                    <?php if (isset($bloco)): ?>
                                        <img id="img-show" src="<?php echo base_url(array('../', 'assets', 'images', 'blocos', $bloco->imagem_4)) ?>">
                                    <?php endif ?>

                                </div>
                            </div>
                        </div>

                        <?php if (isset($bloco->imagem_4)): ?>
                            <input type="hidden" name="imagem_banner4" value="<?php echo $bloco->imagem_4 ?>">
                        <?php endif ?>
                        


                        <?php endif; ?>


                        <div class="hr-line-dashed"></div>

                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="text-right">
                                        <button class="btn btn-primary" type="submit">Atualizar</button>
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
    // $.validator.addMethod("validate_file", function(value, element){
    //     if(value.length > 0){
    //         return true;
    //     }
    //     if($(".error-file").find('label.error').length){
    //         $(".error-file").find('label.error').remove();
    //     }
    //     $(".error-file").append('<label id="banner-error" class="error" for="banner"></label>');
    //     return false;
    // }, "Este campo é obrigatório.");

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


    $('#banner2').on('change', function(){
        input = $(this);
        if (input[0].files && input[0].files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.lightBoxGallery2').find('img').remove();
                $('.lightBoxGallery2').append('<img id="img-show" src="'+e.target.result+'">');
            }

            reader.readAsDataURL(input[0].files[0]);
        }else{
            $('.lightBoxGallery2').find('img').remove();
        }
    });


    $('#banner3').on('change', function(){
        input = $(this);
        if (input[0].files && input[0].files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.lightBoxGallery3').find('img').remove();
                $('.lightBoxGallery3').append('<img id="img-show" src="'+e.target.result+'">');
            }

            reader.readAsDataURL(input[0].files[0]);
        }else{
            $('.lightBoxGallery3').find('img').remove();
        }
    });


    $('#banner4').on('change', function(){
        input = $(this);
        if (input[0].files && input[0].files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.lightBoxGallery4').find('img').remove();
                $('.lightBoxGallery4').append('<img id="img-show" src="'+e.target.result+'">');
            }

            reader.readAsDataURL(input[0].files[0]);
        }else{
            $('.lightBoxGallery4').find('img').remove();
        }
    });

</script>


<script type="text/javascript">
    $(function () {

        $('.summernote').summernote({
            height: 300,
        });


        $('[data-toggle="tooltip"]').tooltip();

        //showNotification
        <?php if (isset($notification)): ?>
            showNotification(<?php echo '"'. $notification->type .'","'. $notification->title .'","'. $notification->message .'"' ?>)
        <?php endif ?>
    })


</script>
