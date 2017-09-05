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
            if($appuseraccess == "IT Manager") { ?>
            
                <div class="row">
                    <section class="panel">
                        <header class="panel-heading">
                            Welcome, <?php echo $appusername; ?>
                        </header>
                        <div class="panel-body">
                            <div class="col-md-12">
                            <div class="panel-body">

                                <div class="col-md-4">
                                    <div class="panel-body">
                                    <a class="btn btn-lg btn-default btn-block" href="managedata.php?user"><i class="fa fa-cubes"></i> Mengelola Data User</a> 
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="panel-body">
                                    <a class="btn btn-lg btn-default btn-block" href="managereport.php?performance"><i class="fa fa-bar-chart-o"></i> Laporan Performa Server</a> 
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="panel-body">
                                    <a class="btn btn-lg btn-default btn-block" href="managereport.php?activity"><i class="fa fa-users"></i> Laporan Aktifitas Sysadmin</a> 
                                    </div>
                                </div>

                            </div>
                            </div>
                        </div>
                    </section>
                </div>

            <?php 
            }elseif($appuseraccess == "System Administrator") { ?>

                <div class="row">
                    <section class="panel">
                        <header class="panel-heading">
                            Welcome, <?php echo $appusername; ?>
                        </header>
                        <div class="panel-body">
                            <div class="col-md-12">
                            <div class="panel-body">

                                <div class="col-md-4">
                                    <div class="panel-body">
                                    <a class="btn btn-lg btn-default btn-block" href="managedata.php?server"><i class="fa fa-th-list"></i> Mengelola Data Server</a> 
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="panel-body">
                                    <a class="btn btn-lg btn-default btn-block" href="manageserver.php?install"><i class="fa fa-download"></i> Instalasi Services</a> 
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="panel-body">
                                    <a class="btn btn-lg btn-default btn-block" href="manageserver.php?control"><i class="fa fa-wrench"></i> Mengelola Services</a> 
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="panel-body">
                                    <a class="btn btn-lg btn-default btn-block" href="manageserver.php?power"><i class="fa fa-server"></i> Mengelola Power Server</a> 
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="panel-body">
                                    <a class="btn btn-lg btn-default btn-block" href="managereport.php?performance"><i class="fa fa-bar-chart-o"></i> Laporan Performa Server</a> 
                                    </div>
                                </div>

                            </div>
                            </div>
                        </div>
                    </section>
                </div>

            <?php }else{
                
                include ('app/forbidden.php'); 
            } ?>
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

<!--right slidebar-->
<script src="assets/js/slidebars.min.js"></script>

<!--switchery-->
<script src="assets/js/switchery/switchery.min.js"></script>
<script src="assets/js/switchery/switchery-init.js"></script>

<!--Sparkline Chart-->
<script src="assets/js/sparkline/jquery.sparkline.js"></script>
<script src="assets/js/sparkline/sparkline-init.js"></script>


<!--common scripts for all pages-->
<script src="assets/js/scripts.js"></script>

</body>
</html>