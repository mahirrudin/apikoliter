<?php
include_once ('class/dbconnection.php');
include_once ('class/function.login.php');

  session_start();

if(logincheck($dbconnection) == false){ 
	header('location: index.php');
	exit();	
} else {
	if(isset($_SESSION['userid'], $_SESSION['username'], $_SESSION['usertype'])){
		$appuserid = $dbconnection->real_escape_string($_SESSION['userid']);
		$appusername = $dbconnection->real_escape_string($_SESSION['username']);
		$appuseraccess = $dbconnection->real_escape_string($_SESSION['usertype']);

	}
}

?>
    <!-- side navigation start -->
        <div class="sidebar-left">
            <!--responsive view logo start-->
            <div class="logo theme-logo-bg visible-xs-* visible-sm-*">
                <a href="#">
                    <span class="fa fa-send brand-name">piKoLiTer</span>
                </a>
            </div>
            <!--responsive view logo end-->

            <div class="sidebar-left-info">
                <!-- visible small devices start-->
                <div class=" search-field">  </div>
                <!-- visible small devices start-->

                <!--sidebar nav start-->
                <?php if($appuseraccess == "System Administrator") {

                ?>
                <ul class="nav nav-pills nav-stacked side-navigation">
                    <li>
                        <h3 class="navigation-title">Navigasi</h3>
                    </li>
                    <li><a href="home.php"><i class="fa fa-home"></i> <span>Home</span></a></li>

                    <li>
                        <h3 class="navigation-title">Mengelola Data</h3>
                    </li>

                    <li><a href="managedata.php?server"><i class="fa fa-th-list"></i><span> Data Server</span></a></li>
                    <li><a href="managedata.php?myprofile"><i class="fa fa-user"></i><span> Profil Saya</span></a></li>


                   <li>
                        <h3 class="navigation-title">Mengelola Server</h3>
                    </li>

                    <li><a href="manageserver.php?install"><i class="fa fa-download"></i><span> Instalasi Services </span></a></li>
                    <li><a href="manageserver.php?control"><i class="fa fa-wrench"></i><span> Mengelola Services </span></a></li>
                    <li><a href="manageserver.php?power"><i class="fa fa-server"></i> <span> Mengelola Power Server </span></a></li>

                    <li>
                        <h3 class="navigation-title">Laporan</h3>
                    </li>

                    <li><a href="managereport.php?performance"><i class="fa fa-bar-chart-o"></i><span> Performa Server</span></a></li>
                </ul>

                <?php 
            		} else {
            	?>

            	<ul class="nav nav-pills nav-stacked side-navigation">
                    <li>
                        <h3 class="navigation-title">Navigasi</h3>
                    </li>
                    <li><a href="home.php"><i class="fa fa-home"></i> <span>Home</span></a></li>

                    <li>
                        <h3 class="navigation-title">Mengelola Data</h3>
                    </li>

                    <li><a href="managedata.php?user"><i class="fa fa-th-list"></i><span> Data Users</span></a></li>
                    <li><a href="managedata.php?myprofile"><i class="fa fa-user"></i><span> Profil Saya</span></a></li>


                    <li>
                        <h3 class="navigation-title">Laporan</h3>
                    </li>

                    <li><a href="managereport.php?activity"><i class="fa fa-users"></i><span> Aktifitas Sysadmin</span></a></li>
                    <li><a href="managereport.php?performance"><i class="fa fa-bar-chart-o"></i><span> Performa Server</span></a></li>
                </ul>

                <?php
            		} 
                ?>
                <!--sidebar nav end-->

            </div>
        </div>
