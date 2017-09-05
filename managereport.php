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

    <!--bootstrap picker-->
    <link rel="stylesheet" type="text/css" href="assets/js/bootstrap-datepicker/css/datepicker.css"/>
    <link rel="stylesheet" type="text/css" href="assets/js/bootstrap-timepicker/compiled/timepicker.css"/>
    <link rel="stylesheet" type="text/css" href="assets/js/bootstrap-colorpicker/css/colorpicker.css"/>
    <link rel="stylesheet" type="text/css" href="assets/js/bootstrap-daterangepicker/daterangepicker-bs3.css"/>
    <link rel="stylesheet" type="text/css" href="assets/js/bootstrap-datetimepicker/css/datetimepicker.css"/>

    <!--sweetalert-->
    <script src="assets/js/sweetalert.js"></script>
    <link rel="stylesheet" href="assets/css/sweetalert.css">
</head>

<body class="sticky-header">

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
                if(isset($_GET['activity']) && file_exists("app/report_activity.php")) {

                    include_once ("app/report_activity.php");

                } elseif (isset($_GET['performance']) && file_exists("app/report_performance.php")) {

                    include_once ("app/report_performance.php");

                } elseif (isset($_GET['monitoring']) && file_exists("app/server_monitoring.php")) {

                    include_once ("app/server_monitoring.php");

                } else {

                    include_once ("app/forbidden.php");

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

<!--bootstrap picker-->
<script type="text/javascript" src="assets/js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="assets/js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="assets/js/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="assets/js/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="assets/js/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>

<!--picker initialization-->
<script src="assets/js/picker-init.js"></script>


<!--common scripts for all pages-->
<script src="assets/js/scripts.js"></script>

<?php $dbconnection->close(); ?>

</body>
</html>