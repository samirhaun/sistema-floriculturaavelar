<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Loja</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Loja</a>
            </li>
            <li class="active">
                <strong>loja</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">

    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <!-- <h5>FooTable with row toggler, sorting and pagination</h5> -->

                    <div class="ibox-tools">
                        <!-- <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a> -->
                        <a class="btn btn-primary" href="<?php echo base_url(array('loja', 'novo-produto')) ?>">
                            <!-- <i class="fa fa-plus"></i> -->
                            Novo produto
                        </a>
                    </div>
                    <ul class="nav nav-tabs" style="border-bottom:none; margin-top:10px;">
                        <li class="<?php echo ($status_filtro == 'ativos') ? 'active' : '' ?>">
                            <a href="<?php echo base_url(array('loja', 'produtos')) ?>?status=ativos">Ativos</a>
                        </li>
                        <li class="<?php echo ($status_filtro == 'inativos') ? 'active' : '' ?>">
                            <a href="<?php echo base_url(array('loja', 'produtos')) ?>?status=inativos">Inativos</a>
                        </li>
                        <li class="<?php echo ($status_filtro == 'todos') ? 'active' : '' ?>">
                            <a href="<?php echo base_url(array('loja', 'produtos')) ?>?status=todos">Todos</a>
                        </li>
                    </ul>
                </div>
                <div class="ibox-content">
                    <?php if($status_filtro == 'ativos'): ?>
                    <div class="alert alert-warning" style="margin-bottom:15px;">
                        <i class="fa fa-exclamation-triangle"></i>
                        Produtos com fundo amarelo nunca foram vendidos nenhuma vez -- candidatos a desativar pra limpar a lista.
                    </div>
                    <?php endif; ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-view" >
                            <thead>
                                <tr>
                                    <th class="on-print">ID</th>
                                    <th class="on-print">Titulo</th>
                                    <th class="on-print">Descricao</th>
                                    <th class="on-print">Valor</th>
                                    <th class="on-print">Situação</th>
                                    <th class="on-print">Oferta</th>
                                    <th class="no-orderable">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                if(!empty($produtos)){
                                    foreach($produtos as $produto){
                            ?>
                                        <tr class="gradeC" id="item-<?php echo $produto->id ?>" <?php echo ($produto->ativo == 1 && $produto->total_vendas == 0) ? 'style="background-color:#fcf8e3;"' : '' ?>>
                                            <td><?php echo $produto->id; ?></td>
                                            <td><?php echo $produto->titulo; ?> <?php echo ($produto->ativo == 1 && $produto->total_vendas == 0) ? '<span class="label label-warning">nunca vendido</span>' : '' ?></td>
                                            <td><?php echo $produto->descricao; ?></td>
                                            <td><?php echo $produto->valor; ?></td>
                                            <td><?php echo ($produto->ativo == 1)? 'Ativado': 'Desativado'; ?></td>
                                            <td><?php echo ($produto->destaque == 1)? 'SIM': '-'; ?></td>
                                            <td class="text-center">
                                                <!-- <a href="#" class="btn btn-default btn-icon-action" data-toggle="tooltip" title=""><i class="fa fa-check"></i></a> -->
                                                <!-- <a href="#" class="btn btn-default btn-icon-action" data-toggle="tooltip" title="Visualizar"><i class="fa fa-file-text-o"></i></a> -->
                                                    <a href="<?php echo base_url(array('loja', 'editar-produto')) ?>?id=<?php echo $produto->id ?>" class="btn btn-default btn-icon-action" data-toggle="tooltip" data-placement="bottom" title="Editar"><i class="fa fa-pencil-square-o"></i></a>

                                                    <a href="<?php echo base_url(array('loja', 'fotos-produto')) ?>?id=<?php echo $produto->id ?>" class="btn btn-default btn-icon-action" data-toggle="tooltip" data-placement="bottom" title="FOtos"><i class="fa fa-file-image-o"></i></a>

                                                    <?php if ($produto->destaque==1){ ?>
                                                        <a href="<?php echo base_url(array('loja', 'destacar-produto')) ?>?id=<?php echo $produto->id ?>" class="btn btn-default btn-icon-action" data-toggle="tooltip" data-placement="bottom" title="Retirar oferta"><i style="color: #e0cc16;" class="fa fa fa-star"></i></a>
                                                  <?php }else{ ?>
                                                        <a href="<?php echo base_url(array('loja', 'destacar-produto')) ?>?id=<?php echo $produto->id ?>" class="btn btn-default btn-icon-action" data-toggle="tooltip" data-placement="bottom" title="Marcar como Oferta"><i class="fa fa fa-star-o"></i></a>
                                                  <?php } ?>

                                                <?php if($produto->ativo == 1){ ?>

                                                <a href="<?php echo base_url(array('loja', 'desativar-produto')) ?>?id=<?php echo $produto->id ?>" class="btn btn-default btn-icon-action confirma-toggle-ativo" data-item="<?php echo $produto->id ?>" data-produto="<?php echo htmlspecialchars($produto->titulo) ?>" data-acao="desativar" data-toggle="tooltip" data-placement="bottom" title="Desativar Produto"><i style="color: blue;" class="fa fa-minus-circle">
                                                </i>
                                            </a>

                                                <?php }else{ ?>

                                                <a href="<?php echo base_url(array('loja', 'desativar-produto')) ?>?id=<?php echo $produto->id ?>" class="btn btn-default btn-icon-action confirma-toggle-ativo" data-item="<?php echo $produto->id ?>" data-produto="<?php echo htmlspecialchars($produto->titulo) ?>" data-acao="ativar" data-toggle="tooltip" data-placement="bottom" title="Ativar Produto"><i style="color: red;" class="fa fa-plus-circle"></i></a>


                                                <?php } ?>


                                                <?php if ($produto->tamanhos == 1): ?>
                                                          <a href="<?php echo base_url(array('loja', 'tamanhos')) ?>?id=<?php echo $produto->id ?>" class="btn btn-default btn-icon-action" data-toggle="tooltip" data-placement="bottom" title="Tamanhos"><img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB2aWV3Qm94PSIwIDAgNTEyLjAwMDAyIDUxMi4wMDAwMiIgd2lkdGg9IjE2cHgiIGhlaWdodD0iMTZweCI+PHBhdGggZD0ibTAgMHY1MTJoNTEyem0zMCA3Mi40MjU3ODEgMjkuNjEzMjgxIDI5LjYxMzI4MS0yMi4wODIwMzEgMjIuMDgyMDMyIDIxLjIxNDg0NCAyMS4yMTA5MzcgMjIuMDc4MTI1LTIyLjA3ODEyNSA0NC42MTcxODcgNDQuNjEzMjgyLTIyLjA4MjAzMSAyMi4wNzgxMjQgMjEuMjE0ODQ0IDIxLjIxNDg0NCAyMi4wNzgxMjUtMjIuMDgyMDMxIDQ0LjYxMzI4MSA0NC42MTMyODEtMjIuMDc4MTI1IDIyLjA4MjAzMiAyMS4yMTA5MzggMjEuMjE0ODQzIDIyLjA4MjAzMS0yMi4wODIwMzEgNDQuNjEzMjgxIDQ0LjYxMzI4MS0yMi4wODIwMzEgMjIuMDgyMDMxIDIxLjIxNDg0MyAyMS4yMTA5MzggMjIuMDgyMDMyLTIyLjA3ODEyNSA0NC42MTMyODEgNDQuNjEzMjgxLTIyLjA4MjAzMSAyMi4wNzgxMjUgMjEuMjE0ODQ0IDIxLjIxNDg0NCAyMi4wNzgxMjQtMjIuMDgyMDMxIDQ0LjYxMzI4MiA0NC42MTcxODctMjIuMDc4MTI1IDIyLjA3ODEyNSAyMS4yMTA5MzcgMjEuMjE0ODQ0IDIyLjA4MjAzMi0yMi4wODIwMzEgMjkuNjEzMjgxIDI5LjYxMzI4MWgtNDA5LjU3NDIxOXptMCAwIiBmaWxsPSIjMDAwMDAwIi8+PHBhdGggZD0ibTk3Ljc2NTYyNSA0MTQuMjM0Mzc1aDE3OC4yMTA5MzdsLTE3OC4yMTA5MzctMTc4LjIxMDkzN3ptMzAtMTA1Ljc4MTI1IDc1Ljc4MTI1IDc1Ljc4MTI1aC03NS43ODEyNXptMCAwIiBmaWxsPSIjMDAwMDAwIi8+PC9zdmc+Cg==" /></a>
                                                <?php endif ?>



                                            </td>
                                        </tr>
                            <?php
                                    }
                                }
                             ?>
                            </tbody>
                        <!-- <tfoot>
                        <tr>
                            <th>Rendering engine</th>
                            <th>Browser</th>
                            <th>Platform(s)</th>
                            <th>Engine version</th>
                            <th>CSS grade</th>
                        </tr>
                        </tfoot> -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();

        //showNotification
        <?php if (isset($notification)): ?>
            showNotification(<?php echo '"'. $notification->type .'","'. $notification->title .'","'. $notification->message .'"' ?>)
        <?php endif ?>

        // Confirma antes de ativar/desativar -- o link agia direto no clique,
        // sem aviso nenhum, fácil de acionar sem querer.
        $('.confirma-toggle-ativo').click(function(e){
            e.preventDefault();

            var url = $(this).attr('href');
            var produto = $(this).data('produto');
            var vaiDesativar = $(this).data('acao') === 'desativar';

            swal({
                title: 'Atenção,',
                text: (vaiDesativar
                    ? 'Desativar "' + produto + '"? Ele deixa de aparecer pra venda.'
                    : 'Ativar "' + produto + '" de novo?'),
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: vaiDesativar ? '#DD6B55' : '#1ab394',
                confirmButtonText: vaiDesativar ? 'Sim, desativar' : 'Sim, ativar',
                cancelButtonText: 'Cancelar',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if(isConfirm){
                    window.location.href = url;
                }
            });
        });
    })
</script>
