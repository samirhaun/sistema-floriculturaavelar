<?php
    $taxas = array();
    if (isset($dados) && !empty($dados->taxas)) {
        $taxas = $dados->taxas;
    } else {
        $taxas[] = (object) array(
            'id' => '',
            'grupo_bandeira' => 'Geral',
            'bandeiras' => '',
            'taxa_debito' => isset($dados) ? $dados->taxa_debito : 0,
            'taxa_credito_1x' => isset($dados) ? $dados->taxa_credito_1x : 0,
            'taxa_credito_2x' => isset($dados) ? $dados->taxa_credito_2x : 0,
            'taxa_credito_3x' => isset($dados) ? $dados->taxa_credito_3x : 0,
            'taxa_credito_4x' => isset($dados) ? $dados->taxa_credito_4x : 0,
        );
    }
?>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo (isset($dados)) ? 'Editar maquininha de cartao' : 'Nova maquininha de cartao' ?></h2>
        <ol class="breadcrumb">
            <li><a href="#">Loja</a></li>
            <li><a href="<?php echo base_url(array('loja', 'maquininhas-cartao')) ?>">Maquininhas de cartao</a></li>
            <li class="active"><strong><?php echo (isset($dados)) ? 'Editar' : 'Nova' ?></strong></li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <form action="<?php echo base_url(array('loja', 'salvar-maquininha-cartao')) ?>" method="post" id="form-maquininha">
                        <?php if (isset($dados)): ?>
                            <input type="hidden" name="id" value="<?php echo $dados->id ?>">
                        <?php endif ?>

                        <div class="hr-line-dashed"></div>

                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label class="control-label">Nome: *</label>
                                    <input type="text" name="nome" class="form-control" value="<?php echo (isset($dados)) ? $dados->nome : '' ?>" required>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Ativa</label>
                                    <div class="checkbox checkbox-primary">
                                        <input type="checkbox" name="ativo" id="ativo" value="1" <?php echo (!isset($dados) || $dados->ativo) ? 'checked' : '' ?>>
                                        <label for="ativo">Sim</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Taxas por grupo de bandeira</label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="tabela-taxas-bandeira">
                                            <thead>
                                                <tr>
                                                    <th>Grupo *</th>
                                                    <th>Bandeiras</th>
                                                    <th>Debito %</th>
                                                    <th>Credito 1x %</th>
                                                    <th>Credito 2x %</th>
                                                    <th>Credito 3x %</th>
                                                    <th>Credito 4x %</th>
                                                    <th class="text-center no-orderable">Acoes</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($taxas as $taxa): ?>
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="taxa_id[]" value="<?php echo $taxa->id ?>">
                                                            <input type="text" name="grupo_bandeira[]" class="form-control" value="<?php echo $taxa->grupo_bandeira ?>" required>
                                                        </td>
                                                        <td><input type="text" name="bandeiras[]" class="form-control" value="<?php echo $taxa->bandeiras ?>" placeholder="Visa, Master, Elo"></td>
                                                        <td><input type="text" name="taxa_debito[]" class="form-control taxa-mask" value="<?php echo number_format($taxa->taxa_debito, 2, ',', '.') ?>"></td>
                                                        <td><input type="text" name="taxa_credito_1x[]" class="form-control taxa-mask" value="<?php echo number_format($taxa->taxa_credito_1x, 2, ',', '.') ?>"></td>
                                                        <td><input type="text" name="taxa_credito_2x[]" class="form-control taxa-mask" value="<?php echo number_format($taxa->taxa_credito_2x, 2, ',', '.') ?>"></td>
                                                        <td><input type="text" name="taxa_credito_3x[]" class="form-control taxa-mask" value="<?php echo number_format($taxa->taxa_credito_3x, 2, ',', '.') ?>"></td>
                                                        <td><input type="text" name="taxa_credito_4x[]" class="form-control taxa-mask" value="<?php echo number_format($taxa->taxa_credito_4x, 2, ',', '.') ?>"></td>
                                                        <td class="text-center"><button type="button" class="btn btn-white remover-taxa"><i class="fa fa-trash"></i></button></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="button" class="btn btn-success" id="adicionar-taxa"><i class="fa fa-plus"></i> Adicionar grupo</button>
                                </div>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group text-right">
                                    <a href="<?php echo base_url(array('loja', 'maquininhas-cartao')) ?>" class="btn btn-white">Cancelar</a>
                                    <button class="btn btn-primary" type="submit">Salvar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/template" id="template-taxa-bandeira">
    <tr>
        <td>
            <input type="hidden" name="taxa_id[]" value="">
            <input type="text" name="grupo_bandeira[]" class="form-control" required>
        </td>
        <td><input type="text" name="bandeiras[]" class="form-control" placeholder="Visa, Master, Elo"></td>
        <td><input type="text" name="taxa_debito[]" class="form-control taxa-mask" value="0,00"></td>
        <td><input type="text" name="taxa_credito_1x[]" class="form-control taxa-mask" value="0,00"></td>
        <td><input type="text" name="taxa_credito_2x[]" class="form-control taxa-mask" value="0,00"></td>
        <td><input type="text" name="taxa_credito_3x[]" class="form-control taxa-mask" value="0,00"></td>
        <td><input type="text" name="taxa_credito_4x[]" class="form-control taxa-mask" value="0,00"></td>
        <td class="text-center"><button type="button" class="btn btn-white remover-taxa"><i class="fa fa-trash"></i></button></td>
    </tr>
</script>

<script type="text/javascript">
    $(function() {
        function aplicaMascaraTaxa()
        {
            $('.taxa-mask').mask('000,00', {reverse: true});
        }

        aplicaMascaraTaxa();
        $('#form-maquininha').validate({});

        $('#adicionar-taxa').on('click', function() {
            $('#tabela-taxas-bandeira tbody').append($('#template-taxa-bandeira').html());
            aplicaMascaraTaxa();
        });

        $('#tabela-taxas-bandeira').on('click', '.remover-taxa', function() {
            if ($('#tabela-taxas-bandeira tbody tr').length > 1) {
                $(this).closest('tr').remove();
            }
        });
    });
</script>
