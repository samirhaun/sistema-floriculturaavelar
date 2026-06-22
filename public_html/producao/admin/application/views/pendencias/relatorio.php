<div id="header_faturamento">
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Pendências de pagamento</h2>
      <ol class="breadcrumb">
        <li><a href="#">Relatórios</a></li>
        <li class="active"><strong>Pendências</strong></li>
      </ol>
    </div>
  </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight hidden-print" style="padding: 20px 10px 0px;">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <div class="ibox-tools">
            <form action="<?php echo base_url(array('loja', 'relatorio-pendencias', 'buscar')) ?>" method="post" id="form-pendencias">
              <div class="row">
                <div class="col-sm-2">
                  <div class="form-group text-left">
                    <label class="control-label">Vencimento de:</label>
                    <input type="text" name="dia_inicial" class="form-control data_mask" value="<?php echo isset($filtro_inicio) ? $filtro_inicio : ''; ?>" placeholder="Opcional">
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="form-group text-left">
                    <label class="control-label">Vencimento até:</label>
                    <input type="text" name="dia_final" class="form-control data_mask" value="<?php echo isset($filtro_fim) ? $filtro_fim : ''; ?>" placeholder="Opcional">
                  </div>
                </div>
              </div>
              <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Pesquisar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php if(isset($resultado)) echo $resultado; ?>

<script>
$(document).ready(function(){
  $('.data_mask').mask('00/00/0000');
  $('[name="dia_inicial"], [name="dia_final"]').datepicker({
    autoclose: false, format: 'dd/mm/yyyy', language: 'pt-BR',
    todayBtn: "linked", todayHighlight: true, toggleActive: false
  });
});
</script>
