<!-- reboot/shutdown -->
<?php
error_reporting(0);

include_once ('class/function.power.control.php');
include_once ('class/function.activity.php');
include_once ('Net/SSH2.php');

$contenturl = $_SERVER['SCRIPT_NAME'].'?power';

if($appuseraccess == "IT Manager") {

include ('forbidden.php');

} elseif ($appuseraccess == "System Administrator") {

    if(isset($_GET['power']) && !empty($_GET['power']) && isset($_GET['restart'])){
        $svrid = $dbconnection->real_escape_string($_GET['power']);
        if(ctlpower($svrid, 6, $dbconnection) == true){
            $actname = 'restart power server';
            if (ActPower($actname, $svrid, $appuserid, $dbconnection) == false) {
                $alertmsg = '"Data gagal diupdate"';
                include ('alert/failed.php');
            }else{
                $alertmsg = '"Restart Server Berhasil"';
                include ('alert/success.php');
            }
        }else{
            $alertmsg = '"Restart server gagal, koneksi SSH tidak stabil"';
            include ('alert/failed.php');
        }
    }

    if(isset($_GET['power']) && !empty($_GET['power']) && isset($_GET['shutdown'])){
        $svrid = $dbconnection->real_escape_string($_GET['power']);
        if(ctlpower($svrid, 0, $dbconnection) == true){
            $actname = 'shutdown power server';
            if (ActPower($actname, $svrid, $appuserid, $dbconnection) == false) {
                $alertmsg = '"Data gagal diupdate"';
                include ('alert/failed.php');
            }else{
                $alertmsg = '"Shutdown Server Berhasil"';
                include ('alert/success.php');
            }

        }else{
            $alertmsg = '"Shutdown server gagal, koneksi SSH tidak stabil"';
            include ('alert/failed.php');
        }
    }

?>
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Power Server
                        </header>
                        <div class="panel-body">
                            
                            <div class="col-md-12">

                                <!-- data server -->
                                <div class="row">

                                    <div class="col-md-12">
                                        <section class="panel">

                                            <table class="table responsive-data-table data-table">
                                                <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Server Name</th>
                                                    <th>Server Hostname</th>
                                                    <th>Linux Distribution</th>                   
                                                    <th>Server Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $sql = $dbconnection->query("SELECT
                                                    servers.servers_id AS txtidsvr,
                                                    servers.servers_name AS txtnmsvr,
                                                    servers.servers_hostname AS txtipsvr,
                                                    servers.servers_port AS txtportsvr,
                                                    servers.servers_distroid AS txtdistro,
                                                    distros.distro_name AS txtdistroname,
                                                    distros.distro_version AS txtdistrover
                                                    FROM
                                                    servers
                                                    INNER JOIN distros ON servers.servers_distroid = distros.distro_id
                                                    WHERE
                                                    servers.servers_delete = 0"); 

                                                $no = 1;

                                                  while($rowsvrdata=$sql->fetch_array())
                                                  {

                                                ?>

                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    <td><?php echo $rowsvrdata['txtnmsvr']; ?></td>
                                                    <td><?php echo $rowsvrdata['txtipsvr']; ?></td>                             
                                                    <td><?php echo $rowsvrdata['txtdistroname']." ".$rowsvrdata['txtdistrover']; ?></td>
                                                    <td class="hidden-xs">
                                                        <a onclick="return confirm('apakah yakin ingin melakukan restart server ?');" class="btn btn-warning btn-xs" href="?power=<?php echo $rowsvrdata['txtidsvr']; ?>&restart"><i class="fa fa-refresh"></i> Restart</a>
                                                        <a onclick="return confirm('setelah shutdown anda tidak bisa melakukan remote server, apakah yakin ingin melakukan shutdown server ? ');" class="btn btn-danger btn-xs" href="?power=<?php echo $rowsvrdata['txtidsvr']; ?>&shutdown"><i class="fa fa-stop"></i> Shutdown</a>
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
<!-- reboot/shutdown -->