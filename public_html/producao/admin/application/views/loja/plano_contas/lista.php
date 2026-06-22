<?php
$tem_filhos = array();
if (!empty($dados)) {
    foreach ($dados as $conta) {
        if (!empty($conta->plano_conta_id)) {
            $tem_filhos[(int) $conta->plano_conta_id] = true;
        }
    }
}
?>

<style>
    .plano-contas-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 14px;
    }

    .plano-contas-filter {
        max-width: 360px;
    }

    .plano-contas-tree tbody tr.is-group {
        background: #f8fafc;
        font-weight: 600;
    }

    .plano-contas-tree tbody tr.is-inactive {
        color: #9aa0a6;
    }

    .plano-tree-cell {
        display: flex;
        align-items: center;
        min-height: 24px;
        white-space: nowrap;
    }

    .plano-tree-toggle,
    .plano-tree-spacer {
        width: 24px;
        height: 24px;
        margin-right: 4px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 24px;
    }

    .plano-tree-toggle {
        border: 0;
        background: transparent;
        color: #6b7280;
        padding: 0;
    }

    .plano-tree-toggle:focus {
        outline: none;
    }

    .plano-tree-name {
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .plano-tree-code {
        font-family: monospace;
        color: #374151;
    }

    .plano-tree-level-1 .plano-tree-cell { padding-left: 22px; }
    .plano-tree-level-2 .plano-tree-cell { padding-left: 44px; }
    .plano-tree-level-3 .plano-tree-cell { padding-left: 66px; }
    .plano-tree-level-4 .plano-tree-cell { padding-left: 88px; }
    .plano-tree-level-5 .plano-tree-cell { padding-left: 110px; }

    .plano-tree-badge {
        display: inline-block;
        min-width: 86px;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 11px;
        text-align: center;
        background: #eef2f7;
        color: #4b5563;
    }

    .plano-tree-badge.is-entry {
        background: #e8f5ee;
        color: #24734b;
    }
</style>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Plano de Contas</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Loja</a>
            </li>
            <li class="active">
                <strong>Plano de Contas</strong>
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
                    <div class="ibox-tools">
                        <a class="btn btn-primary" href="<?php echo base_url(array('loja', 'novo-plano_contas')) ?>">
                            Novo
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="plano-contas-toolbar">
                        <input type="text" id="filtro-plano-contas" class="form-control plano-contas-filter" placeholder="Filtrar por codigo ou descricao">
                        <div>
                            <?php if (!empty($mostrar_inativos)): ?>
                                <a href="<?php echo base_url(array('loja', 'plano_contas')) ?>" class="btn btn-white btn-sm">Mostrar somente ativos</a>
                            <?php else: ?>
                                <a href="<?php echo base_url(array('loja', 'plano_contas')) ?>?mostrar_inativos=1" class="btn btn-white btn-sm">Mostrar inativos</a>
                            <?php endif; ?>
                            <button type="button" class="btn btn-white btn-sm" id="expandir-plano-contas">Expandir</button>
                            <button type="button" class="btn btn-white btn-sm" id="recolher-plano-contas">Recolher</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover plano-contas-tree">
                            <thead>
                                <tr>
                                    <th class="on-print" style="width: 150px;">Codigo</th>
                                    <th class="on-print">Conta</th>
                                    <th class="on-print" style="width: 130px;">Tipo</th>
                                    <th class="on-print" style="width: 100px;">Status</th>
                                    <th class="no-orderable" style="width: 110px;">Acoes</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($dados)): ?>
                                <?php foreach($dados as $valor): ?>
                                    <?php
                                        $nivel = isset($valor->nivel) ? (int) $valor->nivel : 0;
                                        $id = (int) $valor->id;
                                        $pai = !empty($valor->plano_conta_id) ? (int) $valor->plano_conta_id : 0;
                                        $tem_filho = !empty($tem_filhos[$id]);
                                        $classe_linha = $tem_filho ? 'is-group' : '';
                                        $classe_linha .= (!isset($valor->ativo) || !empty($valor->ativo)) ? '' : ' is-inactive';
                                    ?>
                                    <tr class="<?php echo trim($classe_linha); ?> plano-tree-level-<?php echo min($nivel, 5); ?>" id="item-<?php echo $id ?>" data-id="<?php echo $id; ?>" data-parent="<?php echo $pai; ?>" data-level="<?php echo $nivel; ?>">
                                        <td class="plano-tree-code"><?php echo $valor->cod; ?></td>
                                        <td>
                                            <div class="plano-tree-cell">
                                                <?php if ($tem_filho): ?>
                                                    <button type="button" class="plano-tree-toggle" data-id="<?php echo $id; ?>" title="Expandir/Recolher">
                                                        <i class="fa fa-caret-down"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <span class="plano-tree-spacer"></span>
                                                <?php endif; ?>
                                                <span class="plano-tree-name"><?php echo $valor->descricao; ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="plano-tree-badge <?php echo !empty($valor->lancamento) ? 'is-entry' : ''; ?>">
                                                <?php echo !empty($valor->lancamento) ? 'Lancamento' : 'Grupo'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo (!isset($valor->ativo) || !empty($valor->ativo)) ? 'Ativo' : 'Inativo'; ?></td>
                                        <td class="text-center">
                                            <a href="<?php echo base_url(array('loja', 'editar-plano_contas')) ?>?id=<?php echo $id; ?>" class="btn btn-default btn-icon-action" data-toggle="tooltip" data-placement="bottom" title="Editar"><i class="fa fa-pencil-square-o"></i></a>
                                            <a href="<?php echo base_url(array('loja', 'excluir-plano_contas')) ?>" class="btn btn-default btn-icon-action delete-item" data-item="<?php echo $id; ?>" data-toggle="tooltip" data-placement="bottom" title="Excluir"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
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

        var $linhas = $('.plano-contas-tree tbody tr');

        function filhosDe(id) {
            return $linhas.filter('[data-parent="' + id + '"]');
        }

        function setFilhosVisiveis(id, visivel) {
            filhosDe(id).each(function () {
                var $linha = $(this);
                var filhoId = $linha.data('id');
                var $toggle = $('.plano-tree-toggle[data-id="' + filhoId + '"]');

                if (visivel) {
                    $linha.show();
                    if (!$toggle.hasClass('is-collapsed')) {
                        setFilhosVisiveis(filhoId, true);
                    }
                } else {
                    $linha.hide();
                    setFilhosVisiveis(filhoId, false);
                }
            });
        }

        function recolher(id) {
            var $toggle = $('.plano-tree-toggle[data-id="' + id + '"]');
            $toggle.addClass('is-collapsed').find('i').removeClass('fa-caret-down').addClass('fa-caret-right');
            setFilhosVisiveis(id, false);
        }

        function expandir(id) {
            var $toggle = $('.plano-tree-toggle[data-id="' + id + '"]');
            $toggle.removeClass('is-collapsed').find('i').removeClass('fa-caret-right').addClass('fa-caret-down');
            setFilhosVisiveis(id, true);
        }

        $('.plano-tree-toggle').on('click', function () {
            var id = $(this).data('id');
            if ($(this).hasClass('is-collapsed')) {
                expandir(id);
            } else {
                recolher(id);
            }
        });

        $('#recolher-plano-contas').on('click', function () {
            $('.plano-tree-toggle').each(function () {
                recolher($(this).data('id'));
            });
        });

        $('#expandir-plano-contas').on('click', function () {
            $('.plano-tree-toggle').each(function () {
                expandir($(this).data('id'));
            });
        });

        $('#filtro-plano-contas').on('keyup', function () {
            var termo = $(this).val().toLowerCase();

            if (!termo) {
                $linhas.show();
                $('.plano-tree-toggle.is-collapsed').each(function () {
                    setFilhosVisiveis($(this).data('id'), false);
                });
                return;
            }

            $linhas.hide();

            $linhas.each(function () {
                var $linha = $(this);
                if ($linha.text().toLowerCase().indexOf(termo) !== -1) {
                    $linha.show();

                    var parentId = $linha.data('parent');
                    while (parentId && parentId !== 0) {
                        var $pai = $linhas.filter('[data-id="' + parentId + '"]');
                        $pai.show();
                        parentId = $pai.data('parent');
                    }
                }
            });
        });

        <?php if (isset($notification)): ?>
            showNotification(<?php echo '"'. $notification->type .'","'. $notification->title .'","'. $notification->message .'"' ?>)
        <?php endif ?>
    })
</script>
