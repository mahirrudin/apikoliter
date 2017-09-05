<?php
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
use \phpseclib\Net\SSH2;

/* function daemon services
1. ambil informasi server dan koneksi ssh berdasarkan svcstatus_id
2. jika koneksi berhasil jalanin command systemctl start/stop/restart services
3. cek keberhasilan command, pakai fungsi bawaan php 'similar_text'
4. update svcstatus_daemon di database
*/
function svcdaemon($svcsid, $action, $dbconnection){
    if($sql = $dbconnection->prepare("SELECT
            servicesinstalled.txtipsvr,
            servicesinstalled.txtloginsvr,
            servicesinstalled.txtpswdsvr,
            servicesinstalled.txtportsvr,
            servicesinstalled.txtctlsvc,
            servicesinstalled.txtdistroid
        FROM
            servicesinstalled
        WHERE
            servicesinstalled.txtidsvcs = ?")){
        $sql->bind_param('i', $svcsid);
        $sql->execute();
        $sql->store_result();
        $sql->bind_result($svrhost, $svrlogin, $svrpasswd, $svrport, $svcsctl, $distroid);
        $sql->fetch();

        $ssh = new SSH2($svrhost, $svrport);
        $sshconnection = $ssh->login($svrlogin, $svrpasswd);
        if (!$sshconnection) {
            return false;
        
        } else {

            // execute command cli services
            $clidaemon = "systemctl ".$action." ".$svcsctl;
            $ssh->exec($clidaemon);

            $active = "active (running)";
            $inactive = "inactive (dead)";
            $commands = "systemctl status ".$svcsctl." | grep -Po '(?<=Active: ).*(?= since)'";

            if($action == 'stop'){
                if($svcsctl == "apache2" ){
                    $commands = "systemctl status ".$svcsctl." | grep -Po '(?<=Active: ).*'";
                }else{
                    $commands = "systemctl status ".$svcsctl." | grep -Po '(?<=Active: ).*(?= since)'";
                }
                $commands_result = $ssh->exec("$commands");
                similar_text($commands_result, $inactive, $percent);
                //echo "<pre> cli command : ".$commands."</pre>";
                //echo "<pre> cli output : ".$commands_result." : ".$percent."</pre>";
                    if($percent > 60){
                        $sql = $dbconnection->prepare("UPDATE svcstatus SET svcstatus_daemon='0' WHERE svcstatus_id=?");
                        $sql->bind_param("i", $svcsid);
                            if ($sql->execute() == false) {
                                return false;
                            }else{
                                return true;
                            }
                    }else{
                        return false;
                    }

            }elseif($action == 'start'){
                $commands_result = $ssh->exec("$commands");
                similar_text($commands_result, $active, $percent);
                //echo "<pre> cli command : ".$commands."</pre>";
                //echo "<pre> cli output : ".$commands_result." : ".$percent."</pre>";
                    if($percent > 60){
                        $sql = $dbconnection->prepare("UPDATE svcstatus SET svcstatus_daemon='1' WHERE svcstatus_id=?");
                        $sql->bind_param("i", $svcsid);
                            if ($sql->execute() == false) {
                                return false;
                            }else{
                                return true;
                            }
                    }else{
                        return false;
                    }

            }elseif($action == 'restart'){
                $commands_result = $ssh->exec("$commands");
                similar_text($commands_result, $active, $percent);
                //echo "<pre> cli command : ".$commands."</pre>";
                //echo "<pre> cli output : ".$commands_result." : ".$percent."</pre>";
                    if($percent > 60){
                        $sql = $dbconnection->prepare("UPDATE svcstatus SET svcstatus_daemon='1' WHERE svcstatus_id=?");
                        $sql->bind_param("i", $svcsid);
                            if ($sql->execute() == false) {
                                return false;
                            }else{
                                return true;
                            }
                    }else{
                        return false;
                    }

            }else{
                return false;
            }

        }

    }else{
        return false;
    }
}

/* function startup services
1. ambil informasi server dan koneksi ssh berdasarkan svcstatus_id
2. jika koneksi berhasil jalanin command systemctl enable/disable services
3. update svcstatus_startup di database
*/
function svcstartup($svcsid, $action, $dbconnection){
    if($sql = $dbconnection->prepare("SELECT
            servicesinstalled.txtipsvr,
            servicesinstalled.txtloginsvr,
            servicesinstalled.txtpswdsvr,
            servicesinstalled.txtportsvr,
            servicesinstalled.txtctlsvc
        FROM
            servicesinstalled
        WHERE
            servicesinstalled.txtidsvcs = ?")){
        $sql->bind_param('i', $svcsid);
        $sql->execute();
        $sql->store_result();
        $sql->bind_result($svrhost, $svrlogin, $svrpasswd, $svrport, $svcsctl);
        $sql->fetch();

        $ssh = new SSH2($svrhost, $svrport);
        $sshconnection = $ssh->login($svrlogin, $svrpasswd);
        if (!$sshconnection) {
            return false;
        
        } else {
        
            $clistartup = "systemctl ".$action." ".$svcsctl;
            //echo "<pre> cli command : ".$clistartup."</pre>";
            $ssh->exec($clistartup);

                if($action == "enable"){
                    $sqlstartup = $dbconnection->prepare("UPDATE svcstatus SET svcstatus_startup='1' WHERE svcstatus_id=?");
                }else{
                    $sqlstartup = $dbconnection->prepare("UPDATE svcstatus SET svcstatus_startup='0' WHERE svcstatus_id=?");
                }
                $sqlstartup->bind_param("i", $svcsid);
                    if(!$sqlstartup->execute()){
                        return false;
                    }else{
                        return true;
                    }

        }

    }else{
        return false;
    }
}

/* function uninstall services
1. ambil informasi server dan koneksi ssh berdasarkan svcstatus_id
2. jika koneksi berhasil, cek distro.
3.  a. jika distro == 1 artinya ubuntu, jalanin command apt-get remove
    b. jika distro == 2 artinya centos, jalanin command yum remove
4. cek keberhasilan command, pakai fungsi bawaan php 'similar_text'
5. kalo paket gak ada ( nilai > 50% ), return true
6. selain itu, return false
*/
function svcuninstall($svcsid, $dbconnection){
    if($sql = $dbconnection->prepare("SELECT
            servicesinstalled.txtipsvr,
            servicesinstalled.txtloginsvr,
            servicesinstalled.txtpswdsvr,
            servicesinstalled.txtportsvr,
            servicesinstalled.txtpkgsvc,
            servicesinstalled.txtdistroid
        FROM
            servicesinstalled
        WHERE
            servicesinstalled.txtidsvcs = ?")){
        $sql->bind_param('i', $svcsid);
        $sql->execute();
        $sql->store_result();
        $sql->bind_result($svrhost, $svrlogin, $svrpasswd, $svrport, $svcpkg, $svcdistro);
        $sql->fetch();

        $ssh = new SSH2($svrhost, $svrport);
        $sshconnection = $ssh->login($svrlogin, $svrpasswd);
        if (!$sshconnection) {
            return false;
            
            } else {

            if($svcdistro == 1 ){

                $cli_uninstall = "apt-get remove ".$svcpkg." -y";
                $ssh->exec($cli_uninstall);

                $notexist = $svcpkg." deinstall";
                $pkgcheck = "dpkg --get-selections | grep ".$svcpkg." | head -n1";
                $cli_pkgcheck = $ssh->exec($pkgcheck);    
                similar_text($cli_pkgcheck, $notexist, $percent);
                if($percent > 50 || $percent == '0'){
                    return true;
                }else{
                    return false;
                }

            }else{

                $cli_uninstall = "yum remove ".$svcpkg." -y";
                $ssh->exec($cli_uninstall);

                $notexist = "package ".$svcpkg." is not installed";
                $pkgcheck = "rpm -q ".$svcpkg;
                $cli_pkgcheck = $ssh->exec($pkgcheck);
                similar_text($cli_pkgcheck, $notexist, $percent);
                if($percent > 50){
                    return true;
                }else{
                    return false;
                }
            }

        }

    }else{
        return false;
    }
}

?>