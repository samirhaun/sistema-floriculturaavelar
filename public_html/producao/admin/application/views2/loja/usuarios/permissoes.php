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
<style media="screen">
.ativar{
    border-radius: 0px;
    background: white;
}

.desativar{
    background:#208F75;
    border-radius:0px
}
.label_tipo{
    font-size: 12px;
}
</style>
<div class="wrapper wrapper-content animated">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">

                    <?php if (isset($usuario)): ?>
                        <input type="hidden" name="id" value="<?php echo $usuario->id ?>">
                    <?php endif ?>
                    <div class="hr-line-dashed"></div>
                    <div class="row">
                        <div style="margin-bottom: 30px" align="center" class="col-md-12">

                            <h1>Controle de acesso - <?php echo $usuario->nome ?> </h1>
                        </div>

                        <div class="col-md-6">
                            <div class="col-sm-12 col-xs-12">
                                <div class="col-md-8">
                                    <h2>Pedidos <span class="label_tipo"> * Criar, atualizar e deletar pedidos</span></h2> 
                                </div>
                                <a  class="btn btn-default btn-icon-action <?php echo ($permissoes->pedidos==1)?'desativar': 'ativar' ?> mudar_status" data-tipo="pedidos" data-id="<?php echo $permissoes->id ?>" data-placement="bottom"><i style="color:white" class="fa fa-check"></i></a> 
                            </div>

                            <div class="col-sm-12 col-xs-12">
                                <div class="col-md-8">
                                    <h2>Modelos <span class="label_tipo"> * Criar, atualizar e deletar modelos</span></h2> 
                                </div>
                                <a  class="btn btn-default btn-icon-action <?php echo ($permissoes->modelos==1)?'desativar': 'ativar' ?> mudar_status" data-tipo="modelos" data-id="<?php echo $permissoes->id ?>" data-placement="bottom"><i style="color:white" class="fa fa-check"></i></a> 
                            </div>

                            <div class="col-sm-12 col-xs-12">
                                <div class="col-md-8">
                                    <h2>Tecidos <span class="label_tipo"> * Criar, atualizar e deletar tecidos</span></h2>  
                                </div>
                                <a  class="btn btn-default btn-icon-action <?php echo ($permissoes->tecidos==1)?'desativar': 'ativar' ?> mudar_status" data-tipo="tecidos" data-id="<?php echo $permissoes->id ?>" data-placement="bottom"><i style="color:white" class="fa fa-check"></i></a> 
                            </div>

                            <div class="col-sm-12 col-xs-12">
                                <div class="col-md-8">
                                    <h2>Categorias <span class="label_tipo"> * Criar, atualizar e deletar categorias</span></h2> 
                                </div>
                                <a  class="btn btn-default btn-icon-action <?php echo ($permissoes->categorias==1)?'desativar': 'ativar' ?> mudar_status" data-tipo="categorias" data-id="<?php echo $permissoes->id ?>" data-placement="bottom"><i style="color:white" class="fa fa-check"></i></a> 
                            </div>

                            <div class="col-sm-12 col-xs-12">
                                <div class="col-md-8">
                                    <h2>usuários <span class="label_tipo"> * Criar, atualizar e deletar usuários</span></h2> 
                                </div>
                                <a  class="btn btn-default btn-icon-action <?php echo ($permissoes->usuarios==1)?'desativar': 'ativar' ?> mudar_status" data-tipo="usuarios" data-id="<?php echo $permissoes->id ?>" data-placement="bottom"><i style="color:white" class="fa fa-check"></i></a> 
                            </div>

                            <div class="col-sm-12 col-xs-12">
                                <div class="col-md-8">
                                    <h2>Clientes <span class="label_tipo"> * Criar, atualizar e deletar clientes</span></h2> 
                                </div>
                                <a  class="btn btn-default btn-icon-action <?php echo ($permissoes->clientes==1)?'desativar': 'ativar' ?> mudar_status" data-tipo="clientes" data-id="<?php echo $permissoes->id ?>" data-placement="bottom"><i style="color:white" class="fa fa-check"></i></a> 
                            </div>

                        </div>
                        <div class="col-md-6">


                            <div class="col-sm-12 col-xs-12">
                                <div class="col-md-8">
                                    <h2>Produtos <span class="label_tipo"> * Criar, atualizar e deletar produtos</span></h2> 
                                </div>
                                <a  class="btn btn-default btn-icon-action <?php echo ($permissoes->produtos==1)?'desativar': 'ativar' ?> mudar_status" data-tipo="produtos" data-id="<?php echo $permissoes->id ?>" data-placement="bottom"><i style="color:white" class="fa fa-check"></i></a> 
                            </div>


                            <div class="col-sm-12 col-xs-12">
                                <div class="col-md-8">
                                    <h2>Tamanhos <span class="label_tipo"> * Criar, atualizar e deletar produtos</span></h2> 
                                </div>
                                <a  class="btn btn-default btn-icon-action <?php echo ($permissoes->tamanhos==1)?'desativar': 'ativar' ?> mudar_status" data-tipo="tamanhos" data-id="<?php echo $permissoes->id ?>" data-placement="bottom"><i style="color:white" class="fa fa-check"></i></a> 
                            </div>


                            <div class="col-sm-12 col-xs-12">
                                <div class="col-md-8">
                                    <h2>Cores <span class="label_tipo"> * Criar, atualizar e deletar produtos</span></h2> 
                                </div>
                                <a  class="btn btn-default btn-icon-action <?php echo ($permissoes->cores==1)?'desativar': 'ativar' ?> mudar_status" data-tipo="cores" data-id="<?php echo $permissoes->id ?>" data-placement="bottom"><i style="color:white" class="fa fa-check"></i></a> 
                            </div>


                            <div class="col-sm-12 col-xs-12">
                                <div class="col-md-8">
                                    <h2>Catálogos <span class="label_tipo"> * Criar, atualizar e deletar produtos</span></h2> 
                                </div>
                                <a  class="btn btn-default btn-icon-action <?php echo ($permissoes->catalogos==1)?'desativar': 'ativar' ?> mudar_status" data-tipo="catalogos" data-id="<?php echo $permissoes->id ?>" data-placement="bottom"><i style="color:white" class="fa fa-check"></i></a> 
                            </div>

                            <div class="col-sm-12 col-xs-12">
                                <div class="col-md-8">
                                    <h2>Ver todos Pedidos <span class="label_tipo"> * Permite ver e editar pedidos de outros usuários</span></h2> 
                                </div>
                                <a  class="btn btn-default btn-icon-action <?php echo ($permissoes->ver_pedidos==1)?'desativar': 'ativar' ?> mudar_status" data-tipo="ver_pedidos" data-id="<?php echo $permissoes->id ?>" data-placement="bottom"><i style="color:white" class="fa fa-check"></i></a> 
                            </div>
                            <div class="col-sm-12 col-xs-12">
                                <div class="col-md-8">
                                    <h2>Gráficos <span class="label_tipo"> * Permite ver todos os gráficos</span></h2> 
                                </div>
                                <a  class="btn btn-default btn-icon-action <?php echo ($permissoes->graficos==1)?'desativar': 'ativar' ?> mudar_status" data-tipo="graficos" data-id="<?php echo $permissoes->id ?>" data-placement="bottom"><i style="color:white" class="fa fa-check"></i></a> 
                            </div>

                        </div>



                    </div>

                    <div class="hr-line-dashed"></div>



                </div>


            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
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




<script type="text/javascript">
    $('.mudar_status').click(function(){

      var id = ($(this).data('id'));
      var tipo = ($(this).data('tipo'));
      var element = $(this);
      swal({
          title: "Atenção,",
          text: "Tem certeza que deseja Desativar/Ativar este Item?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Sim",
          cancelButtonText: "Não",
          closeOnConfirm: true,
          closeOnCancel: true },
          function (isConfirm) {
              if (!isConfirm) {
                 return;
             }
             $.ajax({
                url: '<?php echo base_url(['permissoes', 'muda']) ?>',
                type: 'POST',
                data: {
                  id: id,
                  tipo: tipo
              },
              beforeSend: function(){
                $('#loading').removeClass('loading-off');
            },
            success: function(result) {
                result = JSON.parse(result);
                if (result.type=='success') {
                    setTimeout(function(){
                        location.reload();
                    }, 500);

                }
                showNotification(result.type, result.title, result.message);
            },
            complete: function(){
                $('#loading').addClass('loading-off');
            }
        });
         });
  });
</script>