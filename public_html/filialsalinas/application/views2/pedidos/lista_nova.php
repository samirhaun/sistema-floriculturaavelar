<style>

    .confirmado{
        color: #000096;
    }

    .saiu-entrega{
        color: #000096;
    }

    .concluido{
        color: #81d255;
    }

    .cancelado-loja{
        color: #eea236;
    }

    .cancelado-cliente{
        color: #eea236;
    }


</style>

<div class="row wrapper border-bottom white-bg page-heading">
<div class="col-lg-10">
        <h2>Pedidos/vendas</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Loja</a>
            </li>
            <li class="active">
                <strong>Pedidos/vendas</strong>
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
                        <a class="btn btn-primary" href="<?php echo base_url(array('site', 'novo-pedido')) ?>">
                            Realizar venda
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover pedidos" >
                            <thead>
                                <tr>
                                <th class="on-print">Origem</th>
                                    <th class="on-print">Código</th>
                                    <th class="on-print">Data pedido</th>
                                    <th class="on-print">Data/Hora entrega</th>
                                    <th class="on-print">Cliente / Recebedor</th>
                                    <th class="on-print">Telefone</th>
                                    <th class="on-print">Valor total</th>
                                    <th class="on-print">Tipo de pedido</th>
                                    <th class="on-print">Forma de pagamento</th>
                                    <th class="on-print">Status do pagamento</th>
                                    <th class="on-print">Status do pedido</th> 
                                    <th class="on-print">Entregador</th> 
                                    <th class="no-orderable">Ações</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal-pedido"></div>

<script type="text/javascript">
    $(function () {
        //showNotification
        <?php if (isset($notification)): ?>
            showNotification(<?php echo '"'. $notification->type .'","'. $notification->title .'","'. $notification->message .'"' ?>)
        <?php endif ?>
    });

    $(document).ready(function () {

        


        var pedidos = <?php echo $pedidos ?>;

        $('.pedidos tfoot th').each(function () {
            var title = $(this).text();
            $(this).html('<input type="text" placeholder="Pesquisar por '+title+'" />');
        });

        var table = $('.pedidos').DataTable({
            data: pedidos,
            order: [[0, "desc"]],
            // columns: [
            //     { title: "ID" },
            //     { title: "Nome" },
            //     { title: "CPF" },
            //     { title: "E-mail" },
            //     { title: "Telefone" },
            //     { title: "Dia. Venc." },
            //     { title: "Pgto." },

            // ],
            deferRender: true,
            // columnDefs: [
            //     {
            //         render: function (data, type, row) {
            //             console.log(data);
            //             if (data == 0) {
            //                 return 'Boleto';
            //             } else {
            //                 return 'Débito em conta';
            //             }
            //         },
            //         targets: [6, 7]
            //     }
            // ],
            // columnDefs: [
            //     {
            //         targets: -1,
            //         data: null,
            //         defaultContent: '<a class="btn btn-xs btn-default informacoes" data-toggle="tooltip" title="Informações" data-placement="top"><i class="fa fa-info"></i></a>\
            //                          <a class="btn btn-xs btn-default painel_financeiro" data-toggle="tooltip" title="Painel financeiro" data-placement="top"><i class="fa fa-usd"></i></a>\
            //                          <a class="btn btn-xs btn-default imprimir_contrato" data-toggle="tooltip" title="Imprimir contrato" data-placement="top"><i class="fa fa-print"></i></a>\
            //                          <a class="btn btn-xs btn-default editar" data-toggle="tooltip" title="Editar" data-placement="top"><i class="fa fa-pencil"></i></a>\
            //                          <a class="btn btn-xs btn-default excluir"     data-toggle="tooltip" title="Excluir"     data-placement="top" data-url="<?php echo base_url() ?>associados/confirma-exclusao?id=" onclick="excluirAssociado(this)"><i class="fa fa-trash"></i></a>\
            //                          <a class="btn btn-xs btn-default imprimir_carta_cobranca" data-toggle="tooltip" title="Imprimir carta cobrança" data-placement="top" style="display:none"><i class="fa fa-print"></i></a>\
            //                          <a class="btn btn-xs btn-default imprimir_carteirinha" data-toggle="tooltip" title="Imprimir carteirinha associado e beneficiários" data-placement="top"><i class="fa fa-address-card-o"></i></a>\
            //                          <a class="btn btn-xs btn-default imprimir_carteirinha_unica" data-toggle="tooltip" title="Imprimir carteirinha apenas associado" data-placement="top"><i class="fa fa-address-card-o"></i></a>\
            //                          '
            //     }
            // ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.12/i18n/Portuguese-Brasil.json",
                buttons: {
                    copy: 'Copiar',
                    copyTitle: 'Copiado',
                    copyKeys: '',
                    copySuccess: {
                        _: '%d Linhas copidas',
                        1: '1 linha copiada'
                    },
                    print:'Imprimir',
                }
            },
            pageLength: 25,
            responsive: true,
            // dom: '<"html5buttons"B>lTfgitp',
            // columnDefs: [ {
            //     'targets': 'no-orderable',
            //     'orderable': false
            // }],
            buttons: [
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: '.on-print'
                    }
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: '.on-print'
                    }
                },
                {
                    extend: 'excel',
                    title: 'table-doc',
                    exportOptions: {
                        columns: '.on-print'
                    }
                },
                {
                    extend: 'pdf',
                    title: 'table-pdf',
                    exportOptions: {
                        columns: '.on-print'
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: '.on-print'
                    },
                    customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                    }
                }
            ],
            drawCallback: function () {
                $("tr").each(function () {
                    var linhas = $(this).children();
                    linhas.each(function () {
                        switch ($(this).text()){
                            case "Confirmado pela loja":
                                $(this).parent().addClass("confirmado");
                                break;
                            case "Saiu para entrega":
                                $(this).parent().addClass("saiu-entrega");
                                break;
                                case "Pedido concluído":
                                $(this).parent().addClass("concluido");
                                break;
                                case "Pedido cancelado pela loja":
                                $(this).parent().addClass("cancelado-loja");
                                break;
                                case "Pedido cancelado pelo cliente":
                                $(this).parent().addClass("cancelado-cliente");
                                break;
                        }
                    });
                    // console.log(text);
                });
            },
        });

        $('.associados tbody').on( 'click', 'a.editar', function () {
            var data = table.row( $(this).parents('tr') ).data();
            window.location = "<?php echo base_url() ?>associados/editar?id=" + data[0];
        });

        $('.associados tbody').on( 'click', 'a.painel_financeiro', function () {
            var data = table.row( $(this).parents('tr') ).data();
            window.location = "<?php echo base_url() ?>associados/painel-financeiro?id=" + data[0];
        });

        $('.associados tbody').on( 'click', 'a.imprimir_contrato', function () {
            var data = table.row( $(this).parents('tr') ).data();
            window.open("<?php echo base_url() ?>associados/imprimir-contrato?id=" + data[0], "_blank");
        });

        $('.associados tbody').on( 'click', 'a.imprimir_carta_cobranca', function () {
            var data = table.row( $(this).parents('tr') ).data();
            window.open("<?php echo base_url() ?>associados/imprimir-carta-cobranca?id=" + data[0], "_blank");
        });

        $('.associados tbody').on( 'click', 'a.imprimir_carteirinha', function () {
            var data = table.row( $(this).parents('tr') ).data();
            window.open("<?php echo base_url() ?>associados/imprimir-carteirinha?id=" + data[0], "_blank");
        });

        $('.associados tbody').on( 'click', 'a.imprimir_carteirinha_unica', function () {
            var data = table.row( $(this).parents('tr') ).data();
            window.open("<?php echo base_url() ?>associados/imprimir-carteirinha-unica?id=" + data[0], "_blank");
        });


        $('.associados tbody').on( 'click', 'a.informacoes', function () {

            //adicionando loading
            $('#loading').removeClass('loading-off');
            
            var data = table.row( $(this).parents('tr') ).data();
            console.log("Foo");
            $('#modal-info').load("<?php echo base_url() ?>associados/info?id=" + data[0], function(){

                $('#modalInfo').modal();

                $('#loading').addClass('loading-off');
            });

        });


        $('.associados tbody').on( 'click', 'a.excluir', function () {
            var data = table.row( $(this).parents('tr') ).data();
            // window.location = "<?php echo base_url() ?>associados/excluir?id=" + data[0];
            $('#modal-confirma-exclusao').load("<?php echo base_url() ?>associados/confirma-exclusao?id=" + data[0], function(){
                $('#modalConfirmaExclusao').modal({
                  backdrop: 'static',
                  keyboard: false
                });
            });
        });

        // Executa o tooltip depois de 1 segundo, para esperar a tabela renderizar
        setTimeout(function() {$('[data-toggle="tooltip"]').tooltip();}, 1000);


        // $(".editar-pedido").on('click', function(){
        //     alert('asdfasdf')
        //     var $this = $(this);
        //     $('#modal-pedido').load('<?php echo base_url(array('loja', 'editar-pedido')) ?>'+'?id='+$this.data('idpedido'), function(){
        //         $('#myModal').modal('show');
        //     });
        // })


    });




    //IMPRIMINDO CONTRATO E RECIBO
    <?php if (isset($id_inserido)): ?>

        var base_url = '<?php echo base_url() ?>';
        var id_inserido = '<?php echo $id_inserido ?>'; 
            
        swal({
            title: "Imprimir Contrato e Recibo?",
            text: "Imprimir contrato e recibo!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "SIM!",
            closeOnConfirm: true
            }, function () {
                window.open(base_url+"associados/imprimir-contrato?id="+id_inserido,"_blank");
                window.open(base_url+"associados/imprimir-recibo-adesao?id="+id_inserido,"_blank");

        });

    <?php endif ?>

    // DAR SUBMIT NO FORM AO TROCAR O FILTRO
    $("[name=status]").change(function () {
        // console.log("Foo");
        $("#formFiltroAssociados").submit();
    });
    $("[name=categoria]").change(function () {
        // console.log("Foo");
        $("#formFiltroAssociados").submit();
    });
    $("[name=forma_pagamento]").change(function () {
        // console.log("Foo");
        $("#formFiltroAssociados").submit();
    });
    $("[name=dia_pagamento]").change(function () {
        // console.log("Foo");
        $("#formFiltroAssociados").submit();
    });


    function editando_pedido(pedido)
    {
        
            $('#modal-pedido').load('<?php echo base_url(array('loja', 'editar-pedido')) ?>'+'?id='+pedido, function(){
                $('#myModal').modal('show');
            });
    }
    


</script>

