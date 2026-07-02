<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo (isset($dados)) ? 'Editar cadastro de plano de conta' : 'Novo cadastro de plano de conta' ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Loja</a>
            </li>
            <li>
                <a href="<?php echo base_url(array('loja', 'plano_contas')) ?>">Plano de contas</a>
            </li>
            <li class="active">
                <strong><?php echo (isset($dados)) ? 'Editar cadastro de plano de conta' : 'Novo cadastro de plano de conta' ?></strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">
    </div>
</div>

<style type="text/css">
    .plano-conta-option{
        display: block;
        line-height: 1.35;
    }
    .plano-conta-grupo{
        font-weight: 700;
        color: #1f2933;
    }
    .plano-conta-level-1{
        padding-left: 18px;
        font-weight: 600;
        color: #374151;
    }
    .plano-conta-level-2{
        padding-left: 36px;
        color: #4b5563;
    }
    .plano-conta-level-3{
        padding-left: 54px;
        color: #6b7280;
        font-size: 12px;
    }
</style>

<div class="wrapper wrapper-content animated">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <form action="<?php echo base_url(array('loja', 'salvar-plano_contas')) ?>" method="post" id="form-cadastro-categoria" enctype="multipart/form-data">
                        <?php if (isset($dados)): ?>
                            <input type="hidden" name="id" value="<?php echo $dados->id ?>">
                        <?php endif ?>

                        <div class="hr-line-dashed"></div>

                        <div class="row">
                            <div class="col-sm-8 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Descricao: *</label>
                                    <input type="text" name="descricao" class="form-control" value="<?php echo (isset($dados)) ? $dados->descricao : '' ?>" required>
                                </div>
                            </div>

                            <div class="col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Codigo:</label>
                                    <input type="text" name="cod" class="form-control" value="<?php echo (isset($dados)) ? $dados->cod : '' ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-8 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Conta pai:</label>
                                    <select class="form-control plano-contas-select" name="plano_conta_id">
                                        <option value="">Sem conta pai</option>
                                        <?php if (!empty($planos_pai)): ?>
                                            <?php foreach ($planos_pai as $plano): ?>
                                                <?php
                                                    $selected = (isset($dados) && (int) $dados->plano_conta_id === (int) $plano->id) ? 'selected' : '';
                                                    $prefixo = str_repeat('&nbsp;&nbsp;&nbsp;', isset($plano->nivel) ? $plano->nivel : 0);
                                                ?>
                                                <option <?php echo $selected; ?> value="<?php echo $plano->id; ?>" data-level="<?php echo isset($plano->nivel) ? (int) $plano->nivel : 0; ?>" data-filhos="<?php echo !empty($plano->tem_filhos) ? 1 : 0; ?>">
                                                    <?php echo isset($plano->rotulo_select) ? $plano->rotulo_select : $prefixo . ($plano->cod ? $plano->cod . ' - ' : '') . strtoupper($plano->descricao); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-2 col-xs-6">
                                <div class="form-group">
                                    <label class="control-label">Lancamento:</label>
                                    <div class="checkbox checkbox-primary">
                                        <input id="lancamento" type="checkbox" name="lancamento" value="1" <?php echo (!isset($dados) || !empty($dados->lancamento)) ? 'checked' : ''; ?>>
                                        <label for="lancamento">Sim</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-2 col-xs-6">
                                <div class="form-group">
                                    <label class="control-label">Ativo:</label>
                                    <div class="checkbox checkbox-primary">
                                        <input id="ativo" type="checkbox" name="ativo" value="1" <?php echo (!isset($dados) || !isset($dados->ativo) || !empty($dados->ativo)) ? 'checked' : ''; ?>>
                                        <label for="ativo">Sim</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="text-right">
                                        <a href="<?php echo base_url(array('loja', 'plano_contas')) ?>" class="btn btn-white">Cancelar</a>
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
    function formatPlanoContaSelect(item){
        if (!item.id) {
            return item.text;
        }

        var $option = $(item.element);
        var level = parseInt($option.data('level'), 10) || 0;
        var temFilhos = parseInt($option.data('filhos'), 10) || 0;
        var text = $.trim(item.text.replace(/\|--/g, '').replace(/\s+/g, ' '));
        var classes = 'plano-conta-option plano-conta-level-' + Math.min(level, 3);

        if (temFilhos) {
            classes += ' plano-conta-grupo';
        }

        return $('<span class="' + classes + '"></span>').text(text);
    }

    if ($.fn.select2) {
        $("[name=plano_conta_id]").select2({
            placeholder: "Sem conta pai",
            allowClear: true,
            templateResult: formatPlanoContaSelect,
            templateSelection: formatPlanoContaSelect
        });
    }

    $("#form-cadastro-categoria").validate({});
</script>
