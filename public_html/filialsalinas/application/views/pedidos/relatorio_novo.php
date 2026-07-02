<div id="header_faturamento">
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Fluxo de caixa</span></h2>
      <ol class="breadcrumb">
        <li>
          <a href="#">Relatórios</a>
        </li>
        <li class="active">
          <strong>Fluxo de caixa</strong>
        </li>
      </ol>
    </div>
    <div class="col-lg-2">
    </div>
  </div>
</div>

<style type="text/css">
  .paga_debito{
    color: #ec4758;
  }
  .paga{
    color: #009688;
  }
  .cancelada{
    color: #f44336;
  }
  .titulo-conta{
    background-color: #e3edef;
    color: black;
    font-weight: 600;
  }
  .footable-even td, .footable-odd td{
    padding: 2px !important;
  }
  thead tr th{
    padding: 2px !important;
  }
  tbody tr td{
    padding: 2px !important;
  }
  th{
    font-weight: 600;
  }


  .select2-selection--multiple{
    height: 56px;
    overflow-y: auto;
  }
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

<div class="wrapper wrapper-content animated fadeInRight hidden-print" style="padding: 20px 10px 0px;">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <div class="ibox-tools">
            <form action="<?php echo base_url(array('loja', 'buscar-pedidos-relatorios-novo')) ?>" method="post" id="form-faturamento">

              <div class="row">
                <div class="col-sm-2">
                  <div class="form-group text-left">
                    <label class="control-label">Data inicial:</label>
                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="Data de início"></i>
                    <input type="text" name="dia_inicial" id="dia_inicial" class="form-control data_mask" value="<?php echo (isset($filtro_inicio)) ? $filtro_inicio : date('d/m/Y', time()) ?>" alt="date" required>
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="form-group text-left">
                    <label class="control-label">Data final:</label>
                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="Data de fim"></i>

                    <input type="text" name="dia_final" id="dia_final" class="form-control data_mask" value="<?php echo (isset($filtro_fim)) ? $filtro_fim : date('d/m/Y', time()) ?>" alt="date" required>
                  </div>
                </div>

                <div class="col-sm-2">
                  <div class="form-group text-left">
                    <label class="control-label">Data referência:</label>
                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="Selecione uma referencia"></i>
                    <select class="form-control search-select-old" id="referencia" name="referencia" required>
                      <option <?php echo (isset($filtro_referencia) && $filtro_referencia == "pgto") ? 'selected' : '' ?> data-tipo="" value="pgto">Pagamento</option>
                      <option <?php echo (isset($filtro_referencia) && $filtro_referencia == "emissao") ? 'selected' : '' ?> data-tipo="" value="emissao">Data vencimento</option>
                      <!-- <option <?php echo (isset($filtro_referencia) && $filtro_referencia == "entrega") ? 'selected' : '' ?> data-tipo="" value="entrega">Data entrega</option> -->
                    </select>
                  </div>
                </div>


                <!-- <div class="col-sm-2">
                  <div class="form-group text-left">
                    <label class="control-label">Origem:</label>
                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="Selecione uma origem"></i>
                    <select class="form-control search-select-old" id="origem" name="origem" required>
                      <option <?php echo (isset($filtro_origem) && $filtro_origem == "all") ? 'selected' : '' ?> data-tipo="" value="all">Todas</option>
                      <option <?php echo (isset($filtro_origem) && $filtro_origem == "1") ? 'selected' : '' ?> data-tipo="" value="1">Balcão</option>
                      <option <?php echo (isset($filtro_origem) && $filtro_origem == "2") ? 'selected' : '' ?> data-tipo="" value="2">Site</option>
                    </select>
                  </div>
                </div> -->


                 <div class="col-sm-2">
                  <div class="form-group text-left">
                    <label class="control-label">Vendedor:</label>
                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="Selecione uma origem"></i>
                    <select class="form-control search-select-old select2class" id="vendedor" name="vendedor" required>
                      <option <?php echo (isset($filtro_vendedor) && $filtro_vendedor == "all") ? 'selected' : '' ?> data-tipo="" value="all">Todas</option>
                      <?php if (isset($vendedores)): ?>
                        <?php foreach ($vendedores as $val): ?>
                          <option value="<?php echo $val->id ?>" <?php echo (isset($filtro_vendedor) && $filtro_vendedor == $val->id) ? 'selected' : '' ?>><?php echo $val->descricao; ?></option>
                        <?php endforeach ?>
                    <?php endif ?>
                    </select>
                  </div>
                </div> 

                <!-- <div class="col-sm-2">
                  <div class="form-group text-left">
                    <label class="control-label">Entregador:</label>
                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="Selecione uma origem"></i>
                    <select class="form-control search-select-old select2class" id="entregador" name="entregador" required>
                      <option <?php echo (isset($filtro_entregador) && $filtro_entregador == "all") ? 'selected' : '' ?> data-tipo="" value="all">Todas</option>
                      <?php if (isset($entregadores)): ?>
                        <?php foreach ($entregadores as $val): ?>
                          <option value="<?php echo $val->id ?>" <?php echo (isset($filtro_entregador) && $filtro_entregador == $val->id) ? 'selected' : '' ?>><?php echo $val->descricao; ?></option>
                        <?php endforeach ?>
                    <?php endif ?>
                    </select>
                  </div>
                </div> -->


                <div class="col-md-2">
                  <div class="form-group text-left">
                  <label class="control-label">Plano de conta:</label>
                  <select required="" class="form-control select2class plano-contas-select" name="plano_contas_id">
                      <option value="all">Todos</option>

                      <?php foreach ($plano_contas as $key => $plano_conta): ?>
                      <option <?php echo (isset($filtro_plano_conta) && $filtro_plano_conta == $plano_conta->id) ? 'selected' : '' ?> value="<?php echo $plano_conta->id ?>" data-level="<?php echo isset($plano_conta->nivel) ? (int) $plano_conta->nivel : 0; ?>" data-filhos="<?php echo !empty($plano_conta->tem_filhos) ? 1 : 0; ?>"><?php echo isset($plano_conta->rotulo_select) ? $plano_conta->rotulo_select : $plano_conta->cod . ' - ' . strtoupper($plano_conta->descricao); ?></option>
                      <?php endforeach ?>
                      
                  </select>
                  </div>
              </div>

              <!-- <div class="col-sm-2">
                  <div class="form-group text-left">
                    <label class="control-label">Exibir receitas:</label>
                    <select class="form-control search-select-old" id="exibir_receitas" name="exibir_receitas" required>
                      <option <?php echo (isset($filtro_exibir_receitas) && $filtro_exibir_receitas == "1") ? 'selected' : '' ?> data-tipo="" value="1">Sim</option>
                      <option <?php echo (isset($filtro_exibir_receitas) && $filtro_exibir_receitas == "0") ? 'selected' : '' ?> data-tipo="" value="0">Não</option>
                    </select>
                  </div>
                </div>

                <div class="col-sm-2">
                  <div class="form-group text-left">
                    <label class="control-label">Exibir despesas:</label>
                    <select class="form-control search-select-old" id="exibir_despesas" name="exibir_despesas" required>
                     <option <?php echo (isset($filtro_exibir_despesas) && $filtro_exibir_despesas == "0") ? 'selected' : '' ?> data-tipo="" value="0">Não</option>
                      <option <?php echo (isset($filtro_exibir_despesas) && $filtro_exibir_despesas == "1") ? 'selected' : '' ?> data-tipo="" value="1">Sim</option>
                    </select>
                  </div>
                </div> -->

                <div class="col-sm-2">
                  <div class="form-group text-left">
                    <label class="control-label">Situaçao pagamento:</label>
                    <select class="select2_demo_2 form-control select-situacao_pgto" multiple="multiple" id="situacao_pgto" name="situacao_pgto[]">
                    <!-- <select class="form-control search-select-old" id="situacao_pgto" name="situacao_pgto" required> -->
                      <option <?php echo (isset($filtro_situacao_pgto) && in_array('all', $filtro_situacao_pgto) ||  !isset($filtro_situacao_pgto)) ? 'selected' : '' ?> data-tipo="" value="all">Todas</option>
                      <option <?php echo (isset($filtro_situacao_pgto) && in_array('1', $filtro_situacao_pgto)) ? 'selected' : '' ?> data-tipo="" value="1">Pago</option>
                      <!-- <option <?php echo (isset($filtro_situacao_pgto) && in_array('2', $filtro_situacao_pgto)) ? 'selected' : '' ?> data-tipo="" value="2">Pago apenas entrada</option> -->
                      <option <?php echo (isset($filtro_situacao_pgto) && in_array('0', $filtro_situacao_pgto)) ? 'selected' : '' ?> data-tipo="" value="0">Não pago</option>
                    </select>
                  </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group text-left">
                    <label class="control-label">Forma de pagamento:</label>
                    <select class="form-control select2class" id="forma_pgto_select" name="forma_pgto[]" multiple="multiple">
                        <option value="1" <?php if (isset($filtro_forma_pgto) && in_array(1, (array)$filtro_forma_pgto)){echo 'selected';} ?>>Dinheiro</option>
                        <option value="9" <?php if (isset($filtro_forma_pgto) && in_array(9, (array)$filtro_forma_pgto)){echo 'selected';} ?>>Pix</option>
                        <option value="2" <?php if (isset($filtro_forma_pgto) && in_array(2, (array)$filtro_forma_pgto)){echo 'selected';} ?>>Débito</option>
                        <option value="3" <?php if (isset($filtro_forma_pgto) && in_array(3, (array)$filtro_forma_pgto)){echo 'selected';} ?>>Crédito 1x</option>
                        <option value="4" <?php if (isset($filtro_forma_pgto) && in_array(4, (array)$filtro_forma_pgto)){echo 'selected';} ?>>Crédito 2x</option>
                        <option value="5" <?php if (isset($filtro_forma_pgto) && in_array(5, (array)$filtro_forma_pgto)){echo 'selected';} ?>>Crédito 3x</option>
                        <option value="6" <?php if (isset($filtro_forma_pgto) && in_array(6, (array)$filtro_forma_pgto)){echo 'selected';} ?>>Crédito 4x</option>
                        <option value="7" <?php if (isset($filtro_forma_pgto) && in_array(7, (array)$filtro_forma_pgto)){echo 'selected';} ?>>Duplicata</option>
                        <option value="8" <?php if (isset($filtro_forma_pgto) && in_array(8, (array)$filtro_forma_pgto)){echo 'selected';} ?>>Cheque</option>
                    </select>
                    <button type="button" class="btn btn-default btn-xs" style="margin-top:5px;" onclick="toggleTodasFormasPgto()">Todas</button>
                    </div>
                </div>
                




              </div>
              
              <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i>&nbsp;&nbsp; Pesquisar</button>
              <input type="hidden" name="page" id="page_input" value="1">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
  if (isset($resultado)){
    echo $resultado;
  }
?>

<script>
  $(document).ready(function() {
    $(".select2class").not(".plano-contas-select").select2();
    $(".plano-contas-select").select2({
      templateResult: formatPlanoContaSelect,
      templateSelection: formatPlanoContaSelect
    });
    $('.data_mask').mask('00/00/0000');

    $(".select2_demo_2").select2();


    const $form = $('#form-faturamento');

    $()
    .add($form.find('[name$="dia_inicial"]'))
    .datepicker({
    autoclose: false,
    calendarWeeks: false,
    clearBtn: false,
    enableOnReadonly: false,
    format: 'dd/mm/yyyy',
    forceParse: false,
    keyboardNavigation: false,
    language: 'pt-BR',
    maxViewMode: 1,
    todayBtn: "linked",
    todayHighlight: true,
    toggleActive: false,
    });

    $()
    .add($form.find('[name$="dia_final"]'))
    .datepicker({
    autoclose: false,
    calendarWeeks: false,
    clearBtn: false,
    enableOnReadonly: false,
    format: 'dd/mm/yyyy',
    forceParse: false,
    keyboardNavigation: false,
    language: 'pt-BR',
    maxViewMode: 1,
    todayBtn: "linked",
    todayHighlight: true,
    toggleActive: false,
    });


  });

  function formatPlanoContaSelect(item){
    if (!item.id || item.id === 'all') {
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

  function limpa_filtros_convenios(){
    $('.select-convenios').val('').trigger('change');
  }

    function limpa_filtros_procedimentos(){
    $('.select-procedimentos').val('').trigger('change');
  }

   function limpa_filtros_status(){
    $('.select-status').val('').trigger('change');
  }


   function limpa_filtros_profissionais(){
     $('.select-profissionais').val('').trigger('change');
   }

   function toggleTodasFormasPgto(){
     var $sel = $('#forma_pgto_select');
     var allValues = $sel.find('option').map(function(){ return this.value; }).get();
     var selected = $sel.val() || [];
     if(selected.length === allValues.length){
       $sel.val([]).trigger('change');
     } else {
       $sel.val(allValues).trigger('change');
     }
   }

  // Paginação via AJAX (Analítico)
  $('#tabela_resultados').on('click', '.btn-paginate', function(e){
    e.preventDefault();
    var page = $(this).data('page');
    var $btn = $(this);
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

    var activeTab = $('#tabela_resultados').find('.nav-tabs li.active a').attr('href') || '#tab-analitico';

    var formData = $('#form-faturamento').serializeArray();
    formData = formData.filter(function(item){ return item.name !== 'page'; });
    formData.push({name: 'page', value: page});

    doAjaxPagination(formData, activeTab, $btn);
  });

  // Paginação via AJAX (Lançamentos sub-tabs)
  $('#tabela_resultados').on('click', '.btn-sub-paginate', function(e){
    e.preventDefault();
    var page = $(this).data('page');
    var table = $(this).data('table');
    var $btn = $(this);
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

    var formData = $('#form-faturamento').serializeArray();
    formData = formData.filter(function(item){ return item.name !== 'page' && item.name !== 'page_receitas' && item.name !== 'page_despesas'; });
    formData.push({name: 'page', value: 1});
    formData.push({name: 'page_receitas', value: ($('#sub-rec').hasClass('active') ? page : ($('#page_receitas_val').val() || 1))});
    formData.push({name: 'page_despesas', value: ($('#sub-desp').hasClass('active') ? page : ($('#page_despesas_val').val() || 1))});
    formData.push({name: table, value: page});

    doAjaxPagination(formData, '#tab-lancamentos', $btn);
  });

  function doAjaxPagination(formData, activeTab, $btn){
    $.ajax({
      url: $('#form-faturamento').attr('action'),
      type: 'POST',
      data: formData,
      dataType: 'json',
      success: function(response){
        if(response.html){
          var $temp = $('<div>').html(response.html);
          var inner = $temp.find('#tabela_resultados').html();
          $('#tabela_resultados').html(inner);
          if(activeTab) $('#tabela_resultados').find('.nav-tabs a[href="' + activeTab + '"]').tab('show');
          $('html, body').animate({ scrollTop: $('#tabela_resultados').offset().top - 80 }, 300);
        }
      },
      error: function(){ swal('Erro', 'Erro ao trocar de página.', 'error'); },
      complete: function(){ $btn.prop('disabled', false); }
    });
  }

  // Accordion categorias
  $('#tabela_resultados').on('click', '.cat-toggle', function(){
    var $target = $($(this).data('target'));
    var $chevron = $(this).find('.cat-chevron');
    $target.toggle();
    $chevron.toggleClass('fa-chevron-right fa-chevron-down');
  });

  // Árvore plano de conta
  $('#tabela_resultados').on('click', '.tree-toggle', function(e){
    e.stopPropagation();
    var $target = $($(this).data('target'));
    var $chevron = $(this).find('.tree-chevron');
    $target.slideToggle(150);
    $chevron.toggleClass('fa-chevron-right fa-chevron-down');
  });

</script>
