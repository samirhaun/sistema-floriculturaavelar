<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo (isset($video)) ? 'Editar cadastro do vídeo' : 'Novo cadastro de vídeo' ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Site</a>
            </li>
            <li>
                <a href="<?php echo base_url(array('site', 'videos')) ?>">Eventos</a>
            </li>
            <li class="active">
                <strong><?php echo (isset($video)) ? 'Editar cadastro do vídeo' : 'Novo cadastro de vídeo' ?></strong>
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
                    <form action="<?php echo base_url(array('site', 'salvar-video')) ?>" method="post" id="form-cadastro-video" enctype="multipart/form-data">
                        <?php if (isset($video)): ?>
                            <input type="hidden" name="id" value="<?php echo $video->id ?>">
                        <?php endif ?>
                        <div class="hr-line-dashed"></div>
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Titulo: *</label>
                                    <input type="text" name="titulo" class="form-control" value="<?php echo (isset($video)) ? $video->titulo : '' ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                         <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <label class="control-label">Código do vídeo no youTube: *</label>
                                    <input type="text" name="cod_video" id="cod_video" class="form-control" value="<?php echo (isset($video)) ? $video->cod_video : '' ?>" required>
                                    <span class="input-group-btn" style="top: 14px;">
                                        <button type="button" class="btn btn-primary" id="btn-play"><i class="fa fa-play" aria-hidden="true"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed line-video" style="<?php echo !isset($video) ? 'display: none' : 'display: block'; ?>"></div>
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3 body-video">
                                <?php if(isset($video) && $video->cod_video != ''): ?>
                                    <iframe width="500" height="300" src="http://www.youtube.com/embed/<?php echo $video->cod_video; ?>" frameborder="0" allowfullscreen></iframe>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="text-right">
                                        <a href="<?php echo base_url(array('site', 'videos')) ?>" class="btn btn-white">Cancelar</a>
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
    $("#form-cadastro-video").validate({});

    $('#btn-play').on('click', function(){
        var frame = '<iframe width="500" height="300" src="http://www.youtube.com/embed/'+$('#cod_video').val()+'" frameborder="0" allowfullscreen></iframe>';
        $(".body-video").html(frame);
        $(".line-video").css({'display':'block'});
    });
</script>
