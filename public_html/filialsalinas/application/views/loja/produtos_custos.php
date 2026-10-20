<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Custos dos produtos</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Loja</a>
            </li>
            <li class="active">
                <strong>Custos</strong>
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
                    <label style="font-weight:normal; margin:0;">
                        <input type="checkbox" id="filtro-sem-custo"> Mostrar só produtos sem custo cadastrado
                    </label>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-view" id="tabela-custos">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Categoria</th>
                                    <th class="text-right">Preço venda</th>
                                    <th class="text-right no-orderable" style="width:150px;">Custo un.</th>
                                    <th class="text-right no-orderable" style="width:110px;">Margem</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($produtos)): foreach($produtos as $produto): ?>
                                <tr data-produto-id="<?php echo $produto->id ?>" data-tem-custo="<?php echo ($produto->valor_custo > 0) ? '1' : '0' ?>">
                                    <td><?php echo $produto->titulo; ?></td>
                                    <td><?php echo $produto->categoria ?: '-'; ?></td>
                                    <td class="text-right" data-preco="<?php echo (float) $produto->valor ?>">R$ <?php echo number_format($produto->valor, 2, ',', '.'); ?></td>
                                    <td class="text-right">
                                        <input type="text" class="form-control input-sm input-custo text-right" style="display:inline-block; width:110px;"
                                               value="<?php echo ($produto->valor_custo > 0) ? number_format($produto->valor_custo, 2, ',', '') : '' ?>"
                                               placeholder="0,00">
                                    </td>
                                    <td class="text-right span-margem">-</td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="5" class="text-center text-muted">Nenhum produto encontrado</td></tr>
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
$(function(){
    $('input.input-custo').mask("#.##0,00", {reverse: true});

    function calculaMargem($tr){
        var preco = parseFloat($tr.find('[data-preco]').data('preco')) || 0;
        var custoTexto = $tr.find('.input-custo').val();
        var custo = parseFloat(String(custoTexto || '').replace(',', '.')) || 0;
        var $span = $tr.find('.span-margem');

        if(!custo || !preco){
            $span.text('-').css('color', '#888');
            return;
        }

        var margem = ((preco - custo) / preco) * 100;
        $span.text(margem.toFixed(1) + '%').css('color', margem >= 0 ? '#11998e' : '#d9534f');
    }

    $('#tabela-custos tbody tr[data-produto-id]').each(function(){
        calculaMargem($(this));
    });

    $('#tabela-custos').on('input', '.input-custo', function(){
        calculaMargem($(this).closest('tr'));
    });

    $('#tabela-custos').on('blur', '.input-custo', function(){
        var $input = $(this);
        var $tr = $input.closest('tr');
        var produtoId = $tr.data('produto-id');
        var valor = $input.val();

        $.ajax({
            url: '<?php echo base_url(['loja', 'salvar-custo-produto']) ?>',
            type: 'POST',
            data: { id: produtoId, valor_custo: valor },
            success: function(resp){
                if(resp && resp.sucesso){
                    $tr.attr('data-tem-custo', valor ? '1' : '0');
                    $input.css('border-color', '#1ab394');
                    setTimeout(function(){ $input.css('border-color', ''); }, 800);
                }else{
                    $input.css('border-color', '#d9534f');
                }
            },
            error: function(){
                $input.css('border-color', '#d9534f');
            }
        });
    });

    // Filtro "só sem custo" -- plugin de busca customizado do DataTables.
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex){
        if(settings.nTable.id !== 'tabela-custos' || !$('#filtro-sem-custo').is(':checked')){
            return true;
        }
        var $row = $(settings.aoData[dataIndex].nTr);
        return $row.attr('data-tem-custo') === '0';
    });

    $('#filtro-sem-custo').on('change', function(){
        $('#tabela-custos').DataTable().draw();
    });
});
</script>
