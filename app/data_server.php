<!-- data server -->
<?php
error_reporting(1);

include_once ('class/function.crudserver.php');
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
use \phpseclib\Net\SSH2;

$contenturl = $_SERVER['SCRIPT_NAME'].'?server';

if($appuseraccess == "IT Manager") {

include ('forbidden.php');

} elseif ($appuseraccess == "System Administrator") {


    if(isset($_POST['btn-svradd'])){
      $svrname = $dbconnection->real_escape_string($_POST['txtnmsvr']);
      $svrhost = $dbconnection->real_escape_string($_POST['txtipsvr']);
      $svrport = $dbconnection->real_escape_string($_POST['txtportsvr']);
      $svrlogin = $dbconnection->real_escape_string($_POST['txtloginsvr']);
      $svrpasswd = $dbconnection->real_escape_string($_POST['txtpswdsvr']);
      $svrdistro = $dbconnection->real_escape_string($_POST['txtdistro']);

        if(serveradd($svrname, $svrhost, $dbconnection) == true){

        $ssh = new SSH2($svrhost, $svrport);
        $sshconnection = $ssh->login($svrlogin, $svrpasswd);
            if (!$sshconnection) {
            $alertmsg = '"Koneksi SSH gagal, data tidak bisa diupdate"';
            include ('alert/failed.php');
            } else {
            $svrkernel = $ssh->exec('uname -r');
            $svrprocie = $ssh->exec('cat /proc/cpuinfo | grep "model name" | cut -f2 -d":" | head -n1');

            $sql = $dbconnection->prepare("INSERT INTO `servers` (`servers_name`, `servers_hostname`, `servers_login`, `servers_password`, `servers_port`, `servers_distroid`, `servers_kernel`, `servers_procie`, `servers_date`) VALUES (?, ?, ?, ?, ?, ?, '$svrkernel', '$svrprocie', now())");
            $sql->bind_param("ssssii", $svrname, $svrhost, $svrlogin, $svrpasswd, $svrport, $svrdistro);
                if (!$sql->execute()) {
                    $alertmsg = '"Koneksi SSH sukses, data tidak bisa ditambahkan"';
                    include ('alert/failed.php');
                }else{
                    $alertmsg = '"Data server berhasil ditambahkan"';
                    include ('alert/success.php');
                }

            }
        }else{
            $alertmsg = '"Nama atau Hostname server sudah ada"';
            include ('alert/failed.php');
        }

    }

    if(isset($_GET['delete'])){
      $sql = $dbconnection->prepare("UPDATE servers SET servers_delete='1' WHERE servers_id=? LIMIT 1");
      $sql->bind_param("i", $_GET['delete']);
      if(!$sql->execute()){
        $alertmsg = '"Data server gagal dihapus"';
        include ('alert/failed.php');
      }else{
        $sql = $dbconnection->prepare("DELETE FROM svrstatus WHERE svrstatus_serverid=? LIMIT 1");
        $sql->bind_param("i", $_GET['delete']);
        $sql->execute();
        $alertmsg = '"Data server dan statistiknya berhasil dihapus"';
        include ('alert/success.php');
      }
    }

    if (isset($_GET['update'])){

      $svrid = $dbconnection->real_escape_string($_GET['update']);
      $sql = $dbconnection->query("SELECT
        servers.servers_id AS txtidsvr,
        servers.servers_name AS txtnmsvr,
        servers.servers_hostname AS txtipsvr,
        servers.servers_port AS txtportsvr,
        servers.servers_distroid AS txtdistro,
        distros.distro_name AS txtdistroname,
        distros.distro_version AS txtdistrover,
        servers.servers_login AS txtloginsvr,
        servers.servers_password AS txtpswdsvr
        FROM
        servers
        INNER JOIN distros ON servers.servers_distroid = distros.distro_id
        WHERE
        servers.servers_delete = 0 AND
        servers.servers_id = {$svrid} ");

      $getserverdata = $sql->fetch_array();
    }


    if(isset($_POST['btn-svrupdate'])){

      $svrid = $dbconnection->real_escape_string($_POST['txtidsvr']);
      $svrname = $dbconnection->real_escape_string($_POST['txtnmsvr']);
      $svrhost = $dbconnection->real_escape_string($_POST['txtipsvr']);
      $svrport = $dbconnection->real_escape_string($_POST['txtportsvr']);
      $svrlogin = $dbconnection->real_escape_string($_POST['txtloginsvr']);
      $svrpasswd = $dbconnection->real_escape_string($_POST['txtpswdsvr']);
      $svrdistro = $dbconnection->real_escape_string($_POST['txtdistro']);

      if(serverupdate($svrid, $svrname, $svrhost, $dbconnection) == true){

        $ssh = new SSH2($svrhost, $svrport);
        $sshconnection = $ssh->login($svrlogin, $svrpasswd);
          if (!$sshconnection) {
                $alertmsg = '"Koneksi SSH gagal, data tidak bisa diupdate"';
                include ('alert/failed.php');
          } else {

            $svrkernel = $ssh->exec('uname -r');
            $svrprocie = $ssh->exec('cat /proc/cpuinfo | grep "model name" | cut -f2 -d":" | head -n1');
      
            $sql = $dbconnection->prepare("UPDATE servers SET `servers_name`=?, `servers_hostname`=?, `servers_login`=?, `servers_password`=?, `servers_port`=?, `servers_distroid`=?, `servers_kernel`='$svrkernel', `servers_procie`='$svrprocie', `servers_date`=now() WHERE `servers_id`=?");
            $sql->bind_param("ssssiii", $svrname, $svrhost, $svrlogin, $svrpasswd, $svrport, $svrdistro, $svrid);
            if (!$sql->execute()) {
                $alertmsg = '"Koneksi SSH sukses, data tidak bisa diupdate"';
                include ('alert/failed.php');
            } else {
                $alertmsg = '"Data server berhasil diupdate"';
                include ('alert/success.php');
            }
          }

      }else{
        $alertmsg = '"Nama atau Hostname server sudah ada"';
        include ('alert/failed.php');
      }
      
    }

?>
            <div class="row">

                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Data Servers
                        </header>
                        <div class="panel-body">
                            <form name="report_monitoring" class="form-inline" role="form" method="post">
                                <div class="form-group">
                                <a class="btn btn-success" href="managedata.php?server&add"><i class="fa fa-plus"></i> Add New Servers</a>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>

                <?php if(isset($_GET['add']) || isset($_GET['update'])) { ?>
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading head-border">
                            Kelola Data Server
                            <span class="tools pull-right">
                                <a class="fa fa-repeat box-refresh" href="javascript:;"></a>
                                <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>
                                <a class="t-close fa fa-times" href="javascript:;"></a>
                            </span>
                        </div>
                        <div class="panel-body"  style="display: block;">
                            <form class="form-horizontal" role="form" method="post">
                                <div class="panel-body col-md-6">

                                    <div class="form-group">
                                        <label for="txtnmsvr" class="col-lg-4 col-sm-2 control-label">Nama Server</label>
                                        <div class="col-lg-8">
                                            <input type="hidden" name="txtidsvr" value="<?php if(isset($_GET['update'])) echo $getserverdata['txtidsvr']; ?>">
                                            <input type="text" class="form-control" name="txtnmsvr" required placeholder="nama server" value="<?php if(isset($_GET['update'])) echo $getserverdata['txtnmsvr']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="txtipsvr" class="col-lg-4 col-sm-2 control-label">Hostname Server</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="txtipsvr" required placeholder="fqdn hostname atau alamat ip" value="<?php if(isset($_GET['update'])) echo $getserverdata['txtipsvr']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="txtdistro" class="col-lg-4 col-sm-2 control-label">Linux Distro</label>
                                        <div class="col-lg-8">
                                            <select name="txtdistro" class="form-control">
                                            <?php
                                              $querygetdistro = $dbconnection->query("SELECT
                                                distros.distro_id AS txtdistroid,
                                                distros.distro_name AS txtdistroname,
                                                distros.distro_version AS txtdistrover
                                                FROM
                                                distros");
                                              while ($datadistro = $querygetdistro->fetch_array()) {
                                                ?>

                                                <option value="<?php echo $datadistro['txtdistroid']; ?>">
                                                <?php echo $datadistro['txtdistroname']." ".$datadistro['txtdistrover']; ?>
                                                </option>
                                              
                                              <?php
                                                  } 
                                            ?>
                                            </select>
                                        </div>
                                    </div>
                                
                                </div>

                                <div class="panel-body col-md-6">

                                    <div class="form-group">
                                        <label for="txtloginsvr" class="col-lg-3 col-sm-2 control-label">SSH Login</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="txtloginsvr" required placeholder="root" value="<?php if(isset($_GET['update'])) echo $getserverdata['txtloginsvr']; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="txtpswdsvr" class="col-lg-3 col-sm-2 control-label">SSH Password</label>
                                        <div class="col-lg-8">
                                            <input type="password" class="form-control" name="txtpswdsvr" required placeholder="password" value="<?php if(isset($_GET['update'])) echo $getserverdata['txtpswdsvr']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="txtport" class="col-lg-3 col-sm-2 control-label">SSH Port</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="txtportsvr" required placeholder="22" value="<?php if(isset($_GET['update'])) echo $getserverdata['txtportsvr']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-lg-offset-3 col-lg-8">
                                        <?php 
                                            if(isset($_GET['add'])) {
                                                echo '<button type="submit" name="btn-svradd" class="btn btn-success btn-block">Add Server</button>';

                                            } elseif (isset($_GET['update'])) {
                                                echo '<button type="submit" name="btn-svrupdate" class="btn btn-primary btn-block">Update Server</button>';
                                            } else {

                                            }
                                        ?>   
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <div class="col-lg-12">
                    <section class="panel">
                        <table class="table responsive-data-table data-table">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Hostname</th>
                                <th>Port</th>
                                <th>Login</th>
                                <th>Password</th>
                                <th>Linux</th>
                                <th>Kernel</th>
                                <th>Processor</th>                              
                                <th>Action</th>
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
                                    distros.distro_version AS txtdistrover,
                                    servers.servers_login AS txtloginsvr,
                                    servers.servers_password AS txtpswdsvr,
                                    servers.servers_kernel AS txtkernelsvr,
                                    servers.servers_procie AS txtprociesvr
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
                                <td><?php echo $rowsvrdata['txtportsvr']; ?></td>
                                <td><?php echo $rowsvrdata['txtloginsvr']; ?></td>
                                <td><?php echo $rowsvrdata['txtpswdsvr']; ?></td>
                                <td><?php echo $rowsvrdata['txtdistroname']." ".$rowsvrdata['txtdistrover']; ?></td>
                                <td><?php echo $rowsvrdata['txtkernelsvr']; ?></td>
                                <td><?php echo $rowsvrdata['txtprociesvr']; ?></td>       
                                <td class="hidden-xs">
                                    <a class="btn btn-primary btn-xs" href="?server&update=<?php echo $rowsvrdata['txtidsvr']; ?>"><i class="fa fa-pencil"> Update</i></a>
                                    <a onclick="return confirm('are you sure want to delete ?');" class="btn btn-danger btn-xs" href="?server&delete=<?php echo $rowsvrdata['txtidsvr']; ?>"><i class="fa fa-lock"> Delete</i></a>
                                </td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </section>
                </div>
            </div>
<?php 
    } else {

        include ('forbidden.php');
        
    }
?>
<!-- data server -->