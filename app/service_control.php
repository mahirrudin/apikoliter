<!-- control service -->
<?php
error_reporting(0);

include_once ('class/function.services.control.php');
include_once ('class/function.config.control.php');
include_once ('class/function.activity.php');
include_once ('Net/SSH2.php');

$contenturl = $_SERVER['SCRIPT_NAME'].'?control';


if($appuseraccess == "IT Manager") {

include ('forbidden.php');

} elseif ($appuseraccess == "System Administrator") {

// tombol - tombol
    // enable services startup
    if(isset($_GET['control']) && isset($_GET['enable']) && !empty($_GET['control'])){
        $svcsid=$dbconnection->real_escape_string($_GET['control']);
        $action='enable';

        if(svcstartup($svcsid, $action, $dbconnection) == true){

            $actname =$action." startup services";
            ActControl($actname, $svcsid, $appuserid, $dbconnection);
            
            $alertmsg = '"services startup berhasil di enable"';
            include ('alert/success.php');
        } else {
            $alertmsg = '"services gagal di enable, koneksi ssh tidak stabil"';
            include ('alert/failed.php');
        }
    }

    // disable services startup
    if(isset($_GET['control']) && isset($_GET['disable']) && !empty($_GET['control'])){
        $svcsid=$dbconnection->real_escape_string($_GET['control']);
        $action='disable';

        if(svcstartup($svcsid, $action, $dbconnection) == true){

            $actname =$action." startup services";
            ActControl($actname, $svcsid, $appuserid, $dbconnection);

            $alertmsg = '"services startup berhasil di disable"';
            include ('alert/success.php');
        } else {
            $alertmsg = '"services gagal di disable, koneksi ssh tidak stabil"';
            include ('alert/failed.php');
        }
    }

    // start services daemon
    if(isset($_GET['control']) && isset($_GET['start']) && !empty($_GET['control'])){
        $svcsid=$dbconnection->real_escape_string($_GET['control']);
        $action='start';

        if(svcdaemon($svcsid, $action, $dbconnection) == true){

            $actname =$action." daemon services";
            ActControl($actname, $svcsid, $appuserid, $dbconnection);

            $alertmsg = '"services daemon berhasil di start"';
            include ('alert/success.php');
            
        } else {
            $alertmsg = '"services gagal di start, koneksi ssh tidak stabil"';
            include ('alert/failed.php');
        }
    }

    // restart services daemon
    if(isset($_GET['control']) && isset($_GET['restart']) && !empty($_GET['control'])){
        $svcsid=$dbconnection->real_escape_string($_GET['control']);
        $action='restart';

        if(svcdaemon($svcsid, $action, $dbconnection) == true){

            $actname =$action." daemon services";
            ActControl($actname, $svcsid, $appuserid, $dbconnection);
            
            $alertmsg = '"services daemon berhasil di restart"';
            include ('alert/success.php');
            
        } else {
            $alertmsg = '"services gagal di start, koneksi ssh tidak stabil"';
            include ('alert/failed.php');
        }
    }

    // stop services daemon
    if(isset($_GET['control']) && isset($_GET['stop']) && !empty($_GET['control'])){
        $svcsid=$dbconnection->real_escape_string($_GET['control']);
        $action='stop';

        if(svcdaemon($svcsid, $action, $dbconnection) == true){

            $actname =$action." daemon services";
            ActControl($actname, $svcsid, $appuserid, $dbconnection);

            $alertmsg = '"services daemon berhasil di stop"';
            include ('alert/success.php');
            
        } else {
            $alertmsg = '"services gagal di start, koneksi ssh tidak stabil"';
            include ('alert/failed.php');
        }
    }

    // uninstall services
    if(isset($_GET['control']) && isset($_GET['uninstall']) && !empty($_GET['control'])){
        $svcsid=$dbconnection->real_escape_string($_GET['control']);

        if(svcuninstall($svcsid, $dbconnection) == true){
            $sqluninstall = $dbconnection->prepare("UPDATE svcstatus SET svcstatus_installed='0', svcstatus_startup='0', svcstatus_daemon='0' WHERE svcstatus_id=? LIMIT 1");
            $sqluninstall->bind_param("i", $svcsid);
            if($sqluninstall->execute() == true){

                $actname ="uninstalasi services";
                ActUninstall($actname, $svcsid, $appuserid, $dbconnection);

                $alertmsg = '"services berhasil di uninstall, dan data perubahan status berhasil tersimpan"';
                include ('alert/success.php');

            }else{
                $alertmsg = '"services gagal diupdate pada database"';
                include ('alert/failed.php');
            }
            
        }else{
            $alertmsg = '"services gagal diuninstall, koneksi ssh tidak stabil"';
            include ('alert/failed.php');
        }
    }
// tombol - tombol

?>
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Services Terinstall
                        </header>
                        <div class="panel-body">
                            
                            <div class="col-md-12">

                                <!-- data service -->
                                <div class="row">

                                    <div class="col-lg-12">
                                        <section class="panel">

                                            <table class="table responsive-data-table data-table">
                                                <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Server Name</th>
                                                    <th>Service Name</th>                   
                                                    <th>Config Service</th>
                                                    <th>Status Startup</th>
                                                    <th>Status Daemon</th>
                                                    <th>Action Startup</th>
                                                    <th>Action Daemon</th>
                                                    <th>Action Uninstall</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $sql = $dbconnection->query("SELECT
                                                        servicesinstalled.txtidsvcs,
                                                        servicesinstalled.txtnmsvr,
                                                        servicesinstalled.txtnmsvc,
                                                        servicesinstalled.txtstartup,
                                                        servicesinstalled.txtdaemon
                                                        FROM
                                                        servicesinstalled");
                                                $no = 1;

                                                while($installed=$sql->fetch_array()) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    <td><?php echo $installed['txtnmsvr']; ?></td>
                                                    <td><?php echo $installed['txtnmsvc']; ?></td>                              
                                                    <td><a class="btn btn-primary btn-xs" href=<?php echo "?config=".$installed['txtidsvcs']; ?>><i class="fa fa-edit"></i> Configuration</a>
                                                    </td>
                                                    <td><?php if($installed['txtstartup'] == 1 ){
                                                            echo '<i class="fa fa-unlock-alt"> Enabled</i>';
                                                            }else{
                                                            echo '<i class="fa fa-lock"> Disabled</i>';
                                                            } ?>    
                                                    </td>
                                                    <td><?php if($installed['txtdaemon'] == 1 ){
                                                            echo '<i class="fa fa-play"> Started</i>';
                                                            }else{
                                                            echo '<i class="fa fa-stop"> Stopped</i>';
                                                            } ?>
                                                    </td>
                                                    <td><a class="btn btn-success btn-xs" href=<?php echo '"?control='.$installed['txtidsvcs'].'&enable"'; ?>><i class="fa fa-unlock-alt"></i> Enable</a>
                                                        <a class="btn btn-danger btn-xs" href=<?php echo '"?control='.$installed['txtidsvcs'].'&disable"'; ?>><i class="fa fa-lock"></i> Disable</a>
                                                    </td>
                                                    <td><a class="btn btn-success btn-xs" href=<?php echo '"?control='.$installed['txtidsvcs'].'&start"'; ?>><i class="fa fa-play"></i> Start</a>
                                                        <a class="btn btn-warning btn-xs" href=<?php echo '"?control='.$installed['txtidsvcs'].'&restart"'; ?>><i class="fa fa-refresh"></i> Restart</a>
                                                        <a class="btn btn-danger btn-xs" href=<?php echo '"?control='.$installed['txtidsvcs'].'&stop"'; ?>><i class="fa fa-stop"></i> Stop</a>
                                                    </td>
                                                    <td><a class="btn btn-default btn-xs" href=<?php echo '"?control='.$installed['txtidsvcs'].'&uninstall"'; ?>><i class="fa fa-upload"></i> Uninstall</a>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </section>
                                    </div>
                                </div>           

                            </div>

                        </div>
                    </section>
                </div>
            </div>
<?php         

} else {

    include ('forbidden.php');
    
}
?>
<!-- control service -->