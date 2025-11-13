<div id="header_faturamento">
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Fluxo de caixa/ Pedidos</span></h2>
      <ol class="breadcrumb">
        <li>
          <a href="#">Relatórios</a>
        </li>
        <li class="active">
          <strong>Fluxo de caixa/ Pedidos</strong>
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
</style>

<div class="wrapper wrapper-content animated fadeInRight hidden-print" style="padding: 20px 10px 0px;">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <div class="ibox-tools">
            <form action="<?php echo base_url(array('loja', 'buscar-pedidos-relatorios')) ?>" method="post" id="form-faturamento">

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
                      <option <?php echo (isset($filtro_referencia) && $filtro_referencia == "emissao") ? 'selected' : '' ?> data-tipo="" value="emissao">Data solicitação/vencimento</option>
                      <option <?php echo (isset($filtro_referencia) && $filtro_referencia == "entrega") ? 'selected' : '' ?> data-tipo="" value="entrega">Data entrega</option>
                    </select>
                  </div>
                </div>


                <div class="col-sm-2">
                  <div class="form-group text-left">
                    <label class="control-label">Origem:</label>
                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="Selecione uma origem"></i>
                    <select class="form-control search-select-old" id="origem" name="origem" required>
                      <option <?php echo (isset($filtro_origem) && $filtro_origem == "all") ? 'selected' : '' ?> data-tipo="" value="all">Todas</option>
                      <option <?php echo (isset($filtro_origem) && $filtro_origem == "1") ? 'selected' : '' ?> data-tipo="" value="1">Balcão</option>
                      <option <?php echo (isset($filtro_origem) && $filtro_origem == "2") ? 'selected' : '' ?> data-tipo="" value="2">Site</option>
                    </select>
                  </div>
                </div>


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

                <div class="col-sm-2">
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
                </div>


                <div class="col-md-2">
                  <div class="form-group text-left">
                  <label class="control-label">Plano de conta:</label>
                  <select required="" class="form-control select2class" name="plano_contas_id">
                      <option value="all">Todos</option>

                      <?php foreach ($plano_contas as $key => $plano_conta): ?>
                      <option <?php echo (isset($filtro_plano_conta) && $filtro_plano_conta == $plano_conta->id) ? 'selected' : '' ?> value="<?php echo $plano_conta->id ?>"><?php echo $plano_conta->cod; ?> - <?php echo $plano_conta->descricao; ?></option>
                      <?php endforeach ?>
                      
                  </select>
                  </div>
              </div>

              <div class="col-sm-2">
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
                </div>

                <div class="col-sm-2">
                  <div class="form-group text-left">
                    <label class="control-label">Situaçao pagamento:</label>
                    <select class="select2_demo_2 form-control select-situacao_pgto" multiple="multiple" id="situacao_pgto" name="situacao_pgto[]">
                    <!-- <select class="form-control search-select-old" id="situacao_pgto" name="situacao_pgto" required> -->
                      <option <?php echo (isset($filtro_situacao_pgto) && in_array('all', $filtro_situacao_pgto) ||  !isset($filtro_situacao_pgto)) ? 'selected' : '' ?> data-tipo="" value="all">Todas</option>
                      <option <?php echo (isset($filtro_situacao_pgto) && in_array('1', $filtro_situacao_pgto)) ? 'selected' : '' ?> data-tipo="" value="1">Pago</option>
                      <option <?php echo (isset($filtro_situacao_pgto) && in_array('2', $filtro_situacao_pgto)) ? 'selected' : '' ?> data-tipo="" value="2">Pago apenas entrada</option>
                      <option <?php echo (isset($filtro_situacao_pgto) && in_array('0', $filtro_situacao_pgto)) ? 'selected' : '' ?> data-tipo="" value="0">Não pago</option>
                    </select>
                  </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group text-left">
                    <label class="control-label">Forma de pagamento:</label>
                    <select class="form-control" name="forma_pgto">
                        <option value="">Selecione</option>
                        <option <?php if (isset($filtro_forma_pgto) && $filtro_forma_pgto == 1){echo 'selected';} ?> value="1">Dinheiro</option>
                        <option <?php if (isset($filtro_forma_pgto) && $filtro_forma_pgto == 9){echo 'selected';} ?> value="9">Pix</option>
                        <option <?php if (isset($filtro_forma_pgto) && $filtro_forma_pgto == 2){echo 'selected';} ?> value="2">Débito</option>
                        <option <?php if (isset($filtro_forma_pgto) && $filtro_forma_pgto == 3){echo 'selected';} ?> value="3">Crédito 1x</option>
                        <option <?php if (isset($filtro_forma_pgto) && $filtro_forma_pgto == 4){echo 'selected';} ?> value="4">Crédito 2x</option>
                        <option <?php if (isset($filtro_forma_pgto) && $filtro_forma_pgto == 5){echo 'selected';} ?> value="5">Crédito 3x</option>
                        <option <?php if (isset($filtro_forma_pgto) && $filtro_forma_pgto == 6){echo 'selected';} ?> value="6">Crédito 4x</option>
                        <option <?php if (isset($filtro_forma_pgto) && $filtro_forma_pgto == 7){echo 'selected';} ?> value="7">Duplicata</option>
                        <option <?php if (isset($filtro_forma_pgto) && $filtro_forma_pgto == 8){echo 'selected';} ?> value="8">Cheque</option>
                        
                    </select>
                    </div>
                </div>
                




              </div>
              
              <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i>&nbsp;&nbsp; Pesquisar</button>
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
    $(".select2class").select2();
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

</script>
