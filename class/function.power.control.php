<?php

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
use \phpseclib\Net\SSH2;

/* function untuk restart atau shutdown server
1. ambil informasi server dan koneksi ssh berdasarkan $_GET['power']
2. jika koneksi berhasil jalanin cli linux init 0 & init 6
3. cek keberhasilan command, pakai fungsi bawaan php 'similar_text'
*/
function ctlpower($svrid, $init, $dbconnection){
    if($sql = $dbconnection->prepare("SELECT
			servers.servers_hostname,
			servers.servers_login,
			servers.servers_password,
			servers.servers_port
			FROM
			servers
			WHERE
			servers.servers_delete = '0' AND
			servers.servers_id = ?") ){
        $sql->bind_param('i', $svrid);
        $sql->execute();
        $sql->store_result();
        $sql->bind_result($svrhost, $svrlogin, $svrpasswd, $svrport);
        $sql->fetch();

        $ssh = new SSH2($svrhost, $svrport);
        $sshconnection = $ssh->login($svrlogin, $svrpasswd);
        if (!$sshconnection) {
            return false;
        
        } else {

            $clipower = "init ".$init;
            $ssh->exec($clipower);

            return true;
        }
    }
}

?>