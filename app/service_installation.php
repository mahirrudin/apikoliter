<!-- instalasi service -->
<?php
//error_reporting(E_ALL & ~E_NOTICE);
error_reporting(0);

include_once ('class/function.services.install.php');
include_once ('class/function.config.control.php');
include_once ('class/function.activity.php');
include_once ('Net/SSH2.php');

$contenturl = $_SERVER['SCRIPT_NAME'].'?install';

if($appuseraccess == "IT Manager") {

include ('forbidden.php');

} elseif ($appuseraccess == "System Administrator") {


if(!empty($_GET['install']) && !empty($_GET['service'])){
    $svrid = $dbconnection->real_escape_string($_GET['install']);
    $svcid = $dbconnection->real_escape_string($_GET['service']);

    if(pkgnotinstalled($svrid, $svcid, $dbconnection) == true){

        if(pkgdoinstall($svrid, $svcid, $dbconnection) == true){

            // insert data ke tabel svcstatus
            $addsvcs = $dbconnection->prepare("INSERT INTO `svcstatus` (`svcstatus_serverid`, `svcstatus_servicesid`, `svcstatus_installed`, `svcstatus_daemon`) VALUES (?, ?, '1', '1')");
            $addsvcs->bind_param("ii", $svrid, $svcid);
            if (!$addsvcs->execute()) {
                    $alertmsg = '"Gagal update services database"';
                    include ('alert/failed.php');
                }else{
                    // insert data ke tabel configurations
                    $svcstatusid = $addsvcs->insert_id;
                    $cfgname = cfgString();

                    while(cfgExist($cfgname, $dbconnection) == true){

                        $cfgname = cfgString();

                    }

                    $addconfig = $dbconnection->prepare("INSERT INTO configurations (`configurations_svcstatus`, `configurations_user`, `configurations_localfile`) VALUES (?, ?, ?)");
                    $addconfig->bind_param("iis", $svcstatusid, $appuserid, $cfgname);
                    if (!$addconfig->execute()) {
                        $alertmsg = '"Gagal update configuration pada database"';
                        include ('alert/failed.php');
                    }else{
                        // catat aktifitas ke database
                        $actname = "instalasi services";
                        if (ActInstall($actname, $svcid, $svrid, $appuserid, $dbconnection) == false) {
                            $alertmsg = '"Data instalasi gagal tersimpan"';
                            include ('alert/failed.php');
                        }else{
                            $alertmsg = '"Instalasi services sukses, dan data instalasi berhasil tersimpan"';
                            include ('alert/success.php');
                        }
                    }
                }

        }else{
            $alertmsg = '"Instalasi gagal, komunikasi SSH tidak stabil"';
            include ('alert/failed.php');
        }

    }else{

        $alertmsg = '"Instalasi gagal, package services sudah terinstall"';
        include ('alert/failed.php');

    }        
}

?>
            <div class="row">

                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Instalasi Services
                        </header>
                        <div class="panel-body">
                            <div class="col-md-12">
                            <div class="panel-body">
                            <form>
                                <select required type="submit" name="install" class="form-control" onchange="this.form.submit()">
                                    <option>Pilih Server . . . . </option>
                                <?php
                                $sql = $dbconnection->query("SELECT servers.servers_id AS txtidsvr, servers.servers_name AS txtnmsvr FROM servers WHERE servers.servers_delete = 0"); 
                                while($rowsvrdata=$sql->fetch_array())
                                    {
                                ?>
                                    <option value="<?php echo $rowsvrdata['txtidsvr']; ?>"><?php echo $rowsvrdata['txtnmsvr']; ?></option>
                                <?php } ?>
                                </select>
                            </form>
                            </div>
                            </div>
                            <?php if(!empty($_GET['install'])) {
                                    $txtidsvr = $_GET['install'];

                                    $sql = $dbconnection->query("SELECT
                                        services.services_id as txtidsvc,
                                        services.services_name as txtnmsvc
                                    FROM
                                        services,
                                        servers
                                    WHERE
                                        servers.servers_distroid = services.services_distroid
                                    AND servers.servers_id = {$txtidsvr} ");

                                    while($rowsvcdata=$sql->fetch_array()) 
                                        {
                                ?>

                                            <div class="col-md-4">
                                                <div class="panel-body">
                                                    <a class="btn btn-lg btn-default btn-block" href="manageserver.php?install=<?php echo $txtidsvr.'&service='.$rowsvcdata['txtidsvc']; ?>"><i class="fa fa-cubes"></i> <?php echo $rowsvcdata['txtnmsvc']; ?></a>
                                                </div>
                                            </div>
                                <?php

                                        }
                             
                                }
                                
                                ?>
                        </div>

                    </section>
                </div>
            </div>
<?php 
} else {

    include ('forbidden.php');
    
}
?><!-- instalasi service -->