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



                        <?php  
                        foreach($habilidades as $habilidade): 

                            $habilitado = 0;

                            foreach($usuario->habilidades as $user_hab):

                                if($user_hab->habilidades_id == $habilidade->id):
                                    $habilitado = 1;
                                endif;

                            endforeach;
                        ?>

                            <div class="col-sm-12">
                       
                                <!-- <h2><span class="label_tipo"> <?php echo $habilidade->descricao ?></span></h2>  -->
                        
                                <a  
                                class="btn btn-default btn-icon-action <?php echo ($habilitado==1)?'desativar': 'ativar' ?> mudar_status" 
                                data-usuario="<?php echo $usuario->id ?>" 
                                data-habilidade="<?php echo $habilidade->id ?>" 
                                data-status="<?php echo $habilitado ?>" 
                                data-placement="bottom"
                                id="<?php echo $habilidade->id ?>">
                                <i style="color:white" class="fa fa-check"></i>
                                </a> &nbsp;
                                <span class="label_tipo" for="label_tipo"> <?php echo $habilidade->descricao ?></span>
                                <!-- <label for="<?php echo $habilidade->id ?>">dd</label> -->
                            </div>



                        <?php endforeach; ?>
                        


                    </div>

                    <div class="hr-line-dashed"></div>

                    <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="text-right">
                                        <button class="btn btn-white" type="button" onclick="muda_habilidades_all(0, '<?php echo $usuario->id ?>')">Desabilitar todas</button>
                                        <button class="btn btn-primary" type="button" onclick="muda_habilidades_all(1, '<?php echo $usuario->id ?>')">Liberar todas</button>
                                    </div>
                                </div>
                            </div>
                        </div>



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

    function muda_habilidades_all(status, usuario){


        swal({
          title: "Atenção,",
          text: "Tem certeza que deseja Desativar/Ativar todas as Habilidades?",
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
                url: '<?php echo base_url(['permissoes', 'muda-all']) ?>',
                type: 'POST',
                data: {
                    status: status,
                    usuario: usuario
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



    }

    $('.mudar_status').click(function(){

      var usuario = ($(this).data('usuario'));
      var habilidade = ($(this).data('habilidade'));
      var status = ($(this).data('status'));
      swal({
          title: "Atenção,",
          text: "Tem certeza que deseja Desativar/Ativar este Habilidade?",
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
                    usuario: usuario,
                    habilidade: habilidade,
                    status: status
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