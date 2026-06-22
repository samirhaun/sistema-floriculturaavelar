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
                                    <select class="form-control" name="plano_conta_id">
                                        <option value="">Sem conta pai</option>
                                        <?php if (!empty($planos_pai)): ?>
                                            <?php foreach ($planos_pai as $plano): ?>
                                                <?php
                                                    $selected = (isset($dados) && (int) $dados->plano_conta_id === (int) $plano->id) ? 'selected' : '';
                                                    $prefixo = str_repeat('&nbsp;&nbsp;&nbsp;', isset($plano->nivel) ? $plano->nivel : 0);
                                                ?>
                                                <option <?php echo $selected; ?> value="<?php echo $plano->id; ?>">
                                                    <?php echo $prefixo; ?><?php echo $plano->cod ? $plano->cod . ' - ' : ''; ?><?php echo $plano->descricao; ?>
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
    $("#form-cadastro-categoria").validate({});
</script>
