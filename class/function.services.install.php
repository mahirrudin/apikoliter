<?php

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
use \phpseclib\Net\SSH2;

/* function installation services
1. ambil informasi server dan koneksi ssh berdasarkan $_GET['install']
2. jika koneksi berhasil jalanin command apt-get install atau yum install
3. cek keberhasilan command, pakai fungsi bawaan php 'similar_text'
*/
function pkgdoinstall($svrid, $svcid, $dbconnection){
    if($sql = $dbconnection->prepare("SELECT
			servers.servers_hostname,
			servers.servers_login,
			servers.servers_password,
			servers.servers_port,
			servers.servers_distroid,
			services.services_pkg,
            services.services_ctl
			FROM
			services,
			servers
			WHERE
			servers.servers_delete = '0' AND
			servers.servers_id = ? AND
			services.services_id = ? ") ){
        $sql->bind_param('ii', $svrid, $svcid);
        $sql->execute();
        $sql->store_result();
        $sql->bind_result($svrhost, $svrlogin, $svrpasswd, $svrport, $svrdistro, $svcpkg, $svcctl);
        $sql->fetch();

        if($svrdistro == '1') {
            $cliinstall = "apt-get install ".$svcpkg." -y";
            $clistart = "systemctl start ".$svcctl;
        }else{
            $cliinstall = "yum install ".$svcpkg." -y";
            $clistart = "systemctl start ".$svcctl;
        }

        $ssh = new SSH2($svrhost, $svrport);
        $sshconnection = $ssh->login($svrlogin, $svrpasswd);
        if ($sshconnection) { 
            $ssh->exec($cliinstall);

            /*while(pkgnotinstalled($svrid, $svcid, $dbconnection) == true){
                $ssh->exec(' ping -c 10 localhost');
            }*/

            $ssh->exec($clistart);
            //sleep(60);
            return true;
        } else {
            return false;
        }
    }
}

/* function check paket services di server
1. ambil informasi server dan koneksi ssh berdasarkan $_GET['install']
2. jika koneksi berhasil jalanin command cek paket sudah keinstall atau belum
3. cek keberhasilan command, pakai fungsi bawaan php 'similar_text'
*/
function pkgnotinstalled($svrid, $svcid, $dbconnection){
    if($sql = $dbconnection->prepare("SELECT
			servers.servers_hostname,
			servers.servers_login,
			servers.servers_password,
			servers.servers_port,
			servers.servers_distroid,
			services.services_pkg
			FROM
			services,
			servers
			WHERE
			servers.servers_delete = '0' AND
			servers.servers_id = ? AND
			services.services_id = ? ") ){
        $sql->bind_param('ii', $svrid, $svcid);
        $sql->execute();
        $sql->store_result();
        $sql->bind_result($svrhost, $svrlogin, $svrpasswd, $svrport, $svrdistro, $svcpkg);
        $sql->fetch();

        $ssh = new SSH2($svrhost, $svrport);
        $sshconnection = $ssh->login($svrlogin, $svrpasswd);
        if (!$sshconnection) {
            return false;
        
        } else {

            if($svrdistro == 1 ){

                $notexist = $svcpkg." deinstall";
                $pkgcheck = "dpkg --get-selections | grep ".$svcpkg." | head -n1";
                $cli_pkgcheck = $ssh->exec($pkgcheck);    
                similar_text($cli_pkgcheck, $notexist, $percent);
                if($percent >= '70' || $percent == '0'){
                    // echo $cli_pkgcheck;
                    // echo $percent;
                    return true;
                }else{
                    echo $cli_pkgcheck;
                    echo $percent;
                    return false;
                }

            }else{

                $notexist = "package ".$svcpkg." is not installed";
                $pkgcheck = "rpm -q ".$svcpkg;
                $cli_pkgcheck = $ssh->exec($pkgcheck);
                similar_text($cli_pkgcheck, $notexist, $percent);
                if($percent > 50){
                    // echo $cli_pkgcheck;
                    // echo $percent;
                    return true;
                }else{
                    // echo $cli_pkgcheck;
                    // echo $percent;
                    return false;
                }
            }

        }

    }else{
        return false;
    }
}

?>