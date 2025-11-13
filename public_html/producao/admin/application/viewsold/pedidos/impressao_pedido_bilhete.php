<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Floricultura Jardins</title>

    <link href="<?php echo base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="<?php echo base_url() ?>assets/css/animate.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/css/style.css" rel="stylesheet">

</head>

<body class="white-bg">
    <div class="wrapper wrapper-content p-xl">
    <!-- <h2 class="text-center" style="margin-top: 0px;">Floricultura Jardins</h2> -->
        <?php if($pedido): ?>
        <div class="ibox-content" style="margin: 3cm 3cm 0 3cm;">
            <div class="row">
            <div class="col-sm-12">
                             
            <?php echo $pedido->bilhete ?>
            </div>

            </div>

        </div>
        </div>
        <?php endif ?>
    </div>

    <!-- Mainly scripts -->
    <script src="<?php echo base_url() ?>assets/js/jquery-2.1.1.js"></script>
    <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>

    <!-- Custom and plugin javascript -->
    <!-- <script src="<?php echo base_url() ?>assets/js/inspinia.js"></script> -->

    <script type="text/javascript">
        window.print();
    </script>

</body>

</html>
