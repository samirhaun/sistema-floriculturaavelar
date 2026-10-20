<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo (isset($bairro)) ? 'Editar bairro' : 'Novo bairro' ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Cadastros</a>
            </li>
            <li>
                <a href="<?php echo base_url(array('site', 'bairros-frete')) ?>">Fretes por Bairro</a>
            </li>
            <li class="active">
                <strong><?php echo (isset($bairro)) ? 'Editar bairro' : 'Novo bairro' ?></strong>
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
                    <form action="<?php echo base_url(array('site', 'salvar-bairro-frete')) ?>" method="post" id="form-cadastro-bairro-frete">
                        <?php if (isset($bairro)): ?>
                            <input type="hidden" name="id" value="<?php echo $bairro->id ?>">
                        <?php endif ?>
                        <div class="hr-line-dashed"></div>
                        <div class="row">
                            <div class="col-sm-6 col-md-5 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Nome do bairro:*</label>
                                    <input type="text" id="nome" name="nome" required="" class="form-control" value="<?php echo (isset($bairro)) ? $bairro->nome : '' ?>">
                                    <div id="aviso-parecido" class="text-warning" style="display:none; margin-top:6px;"></div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Valor do frete:*</label>
                                    <input type="text" id="valor_frete" name="valor_frete" required="" class="form-control" value="<?php echo (isset($bairro)) ? number_format($bairro->valor_frete, 2, ',', '') : '' ?>">
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Status:</label><br>
                                    <label class="i-checks">
                                        <input type="checkbox" name="ativo" value="1" <?php echo (!isset($bairro) || $bairro->ativo) ? 'checked' : '' ?>> Ativo
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="text-right">
                                        <a href="<?php echo base_url(array('site', 'bairros-frete')) ?>" class="btn btn-white">Cancelar</a>
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
    $('#valor_frete').mask("#.##0,00", {reverse: true});
    $("#form-cadastro-bairro-frete").validate({});

    var idAtual = <?php echo isset($bairro) ? (int) $bairro->id : 'null' ?>;
    var timeoutParecido = null;

    $('#nome').on('input', function(){
        var nome = $(this).val();
        clearTimeout(timeoutParecido);

        if(nome.trim().length < 3){
            $('#aviso-parecido').hide();
            return;
        }

        timeoutParecido = setTimeout(function(){
            $.ajax({
                url: '<?php echo base_url(['site', 'checar-bairro-parecido']) ?>',
                type: 'GET',
                data: { nome: nome, id: idAtual },
                success: function(resp){
                    if(resp.parecidos && resp.parecidos.length){
                        var nomes = resp.parecidos.map(function(b){ return b.nome + ' (R$ ' + b.valor_frete + ')'; });
                        $('#aviso-parecido')
                            .text('Já existe um bairro parecido cadastrado: ' + nomes.join(', ') + ' — confira antes de salvar pra não duplicar.')
                            .show();
                    }else{
                        $('#aviso-parecido').hide();
                    }
                }
            });
        }, 400);
    });
</script>
