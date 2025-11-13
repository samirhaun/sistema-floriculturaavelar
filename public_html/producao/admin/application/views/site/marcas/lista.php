<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Tipos de flores</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Loja</a>
            </li>
            <li class="active">
                <strong>Tipos de flores</strong>
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
                        <a class="btn btn-primary" href="<?php echo base_url(array('site', 'nova-marcas')) ?>">
                            <!-- <i class="fa fa-plus"></i> -->
                            Nova
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-view" >
                            <thead>
                                <tr>
                                    <th class="on-print">ID</th>
                                    <th class="on-print">Titulo</th>
                                    <!-- <th class="on-print">Imagem</th> -->
                                    <th class="no-orderable">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                if(!empty($marcas)){
                                    foreach($marcas as $noticia){
                            ?>
                                        <tr class="gradeC" id="item-<?php echo $noticia->id ?>">
                                            <td><?php echo $noticia->id; ?></td>
                                            <td><?php echo $noticia->titulo; ?></td>
                                          <!--   <td>
                                              <img style="    max-width: 63px;" src="<?php echo base_url() ?>../assets/images/marcas/<?php echo $noticia->imagem; ?>" alt="">
                                              </td> -->
                                            <td class="text-center">
                                                <!-- <a href="#" class="btn btn-default btn-icon-action" data-toggle="tooltip" title=""><i class="fa fa-check"></i></a> -->
                                                <!-- <a href="#" class="btn btn-default btn-icon-action" data-toggle="tooltip" title="Visualizar"><i class="fa fa-file-text-o"></i></a> -->

                                                <a href="<?php echo base_url(array('loja', 'editar-marcas')) ?>?id=<?php echo $noticia->id ?>" class="btn btn-default btn-icon-action" data-toggle="tooltip" data-placement="bottom" title="Editar"><i class="fa fa-pencil-square-o"></i></a>

                                                <a href="<?php echo base_url(array('site', 'excluir-marca')) ?>" class="btn btn-default btn-icon-action delete-item" data-item="<?php echo $noticia->id ?>" data-toggle="tooltip" data-placement="bottom" title="Excluir"><i class="fa fa-trash"></i></a>
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
    })
</script>
