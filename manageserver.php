<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="Aplikasi Konfigurasi Linux Server Terpusat">
    <link rel="shortcut icon" href="javascript:;" type="image/png">

    <title>ApiKoLiter - Aplikasi Konfigurasi Linux Server Terpusat</title>

    <!--switchery-->
    <link href="assets/js/switchery/switchery.min.css" rel="stylesheet" type="text/css" media="screen" />
    <!--jquery-ui-->
    <link href="assets/js/jquery-ui/jquery-ui-1.10.1.custom.min.css" rel="stylesheet" />
    <!--common style-->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">
    <!--Data Table-->
    <link href="assets/js/data-table/css/jquery.dataTables.css" rel="stylesheet">
    <link href="assets/js/data-table/css/dataTables.tableTools.css" rel="stylesheet">
    <link href="assets/js/data-table/css/dataTables.colVis.min.css" rel="stylesheet">
    <link href="assets/js/data-table/css/dataTables.responsive.css" rel="stylesheet">
    <link href="assets/js/data-table/css/dataTables.scroller.css" rel="stylesheet">

    <!--sweetalert-->
    <script src="assets/js/sweetalert.js"></script>
    <link rel="stylesheet" href="assets/css/sweetalert.css">
</head>

<body class="sticky-header sidebar-collapsed">

    <section>

    <!-- sidebar left start-->
    <?php include 'navleft.php'; ?>
    <!-- sidebar left end-->

    <!-- body content start-->
    <div class="body-content" style="min-height: 1000px;">

    <!-- header section start-->
    <?php include 'navtop.php'; ?>
    <!-- header section end-->

    <!-- page head start-->
            <div class="page-head">
                <h3>
                    <span class="fa fa-send brand-name">piKoLiTer</span>
                </h3>
                <span class="sub-title">Aplikasi Konfigurasi Linux Server Terpusat</span>
            </div>
            <!-- page head end--> 
            
    		<!--body wrapper start-->
            <div class="wrapper">

            <!-- content begin here -->

            <?php 

                if(isset($_GET['install'])) {

                    include ("app/service_installation.php");

                } elseif (isset($_GET['control'])) {

                    include ("app/service_control.php");

                } elseif (isset($_GET['config'])) {

                    include ("app/service_configuration.php");

                } elseif (isset($_GET['power'])) {

                    include ("app/server_power.php");

                } else {

                    include ("app/forbidden.php");

                }

            ?>

            <!-- content end here -->

            </div>
            <!--body wrapper end-->

            <!--footer section start-->
            <footer>
                <?php echo date("Y"); ?> &copy; ApKoLiter - Aplikasi Konfigurasi Linux Server Terpusat.
            </footer>
            <!--footer section end-->

    </div>
    <!-- body content end-->

    </section>


<!-- Placed js at the end of the document so the pages load faster -->
<script src="assets/js/jquery-1.10.2.min.js"></script>
<script src="assets/js/jquery-migrate.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/modernizr.min.js"></script>

<!--Nice Scroll-->
<script src="assets/js/jquery.nicescroll.js" type="text/javascript"></script>

<!--switchery-->
<script src="assets/js/switchery/switchery.min.js"></script>
<script src="assets/js/switchery/switchery-init.js"></script>

<!--Data Table-->
<script src="assets/js/data-table/js/jquery.dataTables.min.js"></script>
<script src="assets/js/data-table/js/dataTables.tableTools.min.js"></script>
<script src="assets/js/data-table/js/bootstrap-dataTable.js"></script>
<script src="assets/js/data-table/js/dataTables.colVis.min.js"></script>
<script src="assets/js/data-table/js/dataTables.responsive.min.js"></script>
<script src="assets/js/data-table/js/dataTables.scroller.min.js"></script>
<!--data table init-->
<script src="assets/js/data-table-init.js"></script>

<!--common scripts for all pages-->
<script src="assets/js/scripts.js"></script>

<?php $dbconnection->close(); ?>

</body>
</html>