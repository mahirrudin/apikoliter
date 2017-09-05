<?php
error_reporting(0);

include_once ('class/function.config.control.php');
include_once ('class/function.activity.php');
include_once ('Net/SSH2.php');
include_once ('Net/SCP.php');

$contenturl = $_SERVER['SCRIPT_NAME'].'?config';

if($appuseraccess == "IT Manager") {

include ('forbidden.php');

} elseif ($appuseraccess == "System Administrator") {

    if(isset($_GET['file']) && !empty($_GET['file'])){ 
        $cfgid = $dbconnection->real_escape_string($_GET['file']);

        if(cfgLocalExist($cfgid, $dbconnection) == false){
            if(cfgDownload($cfgid, $dbconnection) == false){
                $alertmsg = '"Gagal Download Konfigurasi"';
                include ('alert/failed.php');
            }
        }else{
            $cfgfile = cfgDisplay($cfgid, $dbconnection);
            $cfgcontent = file_get_contents($cfgfile);
        }

        $cfgfile = cfgDisplay($cfgid, $dbconnection);
        $cfgcontent = file_get_contents($cfgfile);
    }

    if(isset($_POST['btn-update'])){
        
        $cfgname = cfgString();

        while(cfgExist($cfgname, $dbconnection) == true){

            $cfgname = cfgString();

        }

        // remove unwanted ^M for unix format
            $contents = preg_replace('/(\r\n|\r|\n)/s',"\n", $_POST['cfgcontent']);
            file_put_contents($cfgname, $contents);

            $sql = $dbconnection->prepare("SELECT
                configurations.configurations_svcstatus
                FROM
                configurations
                WHERE
                configurations.configurations_localfile = ?");
            $sql->bind_param('s', $cfgfile);
            $sql->execute();
            $sql->store_result();
            $sql->bind_result($svcstatusid);
            $sql->fetch();

            $addconfig = $dbconnection->prepare("INSERT INTO configurations (`configurations_svcstatus`, `configurations_user`, `configurations_localfile`) VALUES (?, ?, ?)");
            $addconfig->bind_param("iis", $svcstatusid, $appuserid, $cfgname);
            if (!$addconfig->execute()) {
                    $alertmsg = '"Gagal upload configuration ke server"';
                    include ('alert/failed.php');
                }else{
                    $sql = $dbconnection->prepare("SELECT configurations.configurations_id FROM configurations WHERE configurations.configurations_localfile = ?");
                    $sql->bind_param('s', $cfgname);
                    $sql->execute();
                    $sql->store_result();
                    $sql->bind_result($cfgid);
                    $sql->fetch();

                    if(cfgUpload($cfgid, $dbconnection) == true ) {
                        $contenturl = $_SERVER['SCRIPT_NAME'].'?config='.$svcstatusid.'&file='.$cfgid;

                        $actname = "konfigurasi services";
                        ActConfig($actname, $svcstatusid, $cfgid, $appuserid, $dbconnection);

                        $alertmsg = '"Sukses upload configurations ke server"';
                        include ('alert/success.php');
                    }else{
                        $alertmsg = '"Gagal update data configuration"';
                        include ('alert/failed.php');
                    }
                }
        

    }

    if(isset($_GET['config']) && !empty($_GET['config'])) { 

?>     
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <header class="panel-heading">
                                konfigurasi Services
                            </header>
                            <div class="panel-body">
                                
                                <div class="col-md-12">

                                    <!-- data config -->
                                    <div class="row">

                                        <div class="col-lg-12">
                                        <section class="panel">

                                                <table class="table responsive-data-table data-table">
                                                    <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Name Server</th>
                                                        <th>Name Service</th>
                                                        <th>Change Time</th>
                                                        <th>Change By</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        $svcsid = $dbconnection->real_escape_string($_GET['config']);
                                                        $sql = $dbconnection->query("SELECT
                                                            configrecord.txtnmsvr,
                                                            configrecord.txtnmsvc,
                                                            configrecord.txtnmusr,
                                                            configrecord.txtdatecfg,
                                                            configrecord.txtidcfg
                                                        FROM
                                                            configrecord
                                                        WHERE
                                                            configrecord.txtidsvcs = {$svcsid}");
                                                        $no = 1;

                                                        while($cfgrow=$sql->fetch_array()) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $no++; ?></td>
                                                        <td><?php echo $cfgrow['txtnmsvr']; ?></td>
                                                        <td><?php echo $cfgrow['txtnmsvc']; ?></td>
                                                        <td><?php echo $cfgrow['txtdatecfg']; ?></td>
                                                        <td><?php echo $cfgrow['txtnmusr']; ?></td>                              
                                                        <td class="hidden-xs">
                                                            <a href="manageserver.php?config=<?php echo $svcsid; ?>&file=<?php echo $cfgrow['txtidcfg']; ?>"><button name="btn-open" class="btn btn-default btn-xs"><i class="fa  fa-search-plus"></i> Open Configuration</button></a>
                                                            
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>                            

                                        </section>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <section class="panel">

                                            <!-- konfigurasi form -->

                                                <form action="" method="post">
                                                    <textarea name="cfgcontent" style="background:#000000;color:ffffff;width:100%;height:300px;"><?php if(!empty($cfgcontent)) {
                                                            echo $cfgcontent;
                                                        }else{
                                                            echo "";
                                                        } 
                                                        ?></textarea>
                                                    <button type="submit" name="btn-update" class="btn btn-primary">Update Configuration</button>
                                                </form>

                                            <!-- konfigurasi form --> 

                                            </section>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </section>
                    </div>
                </div>
<?php
    }else{
        include ('forbidden.php');
    }        

} else {

    include ('forbidden.php');
    
}
?>