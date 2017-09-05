<?php

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
use \phpseclib\Net\SSH2;
use \phpseclib\Net\SCP;

/* function create random string panjang 10 char
1. string random dari shuffle dani kombinasi angka, huruf kecil dan besar
2. potong string pakai fungsi ceil, biar bisa dilimit 10 char
*/
function cfgString ($length = 10) {
	$cfgstr = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	$cfglocal = "filecfg/".$cfgstr.".local";
	return $cfglocal;
}

/* function cek config di database
1. cari semua string configurations_localfile di database
2. jika hasilnya < 1 berarti config belum ada, dan return jadi false
3. jika hasilnya > 1 berarti config udah ada, dan return jadi true
*/
function cfgExist($cfgfile, $dbconnection){
	$sql = $dbconnection->query("SELECT
		configurations.configurations_localfile
		FROM
		configurations
		INNER JOIN svcstatus ON configurations.configurations_svcstatus = svcstatus.svcstatus_id
		WHERE
		configurations.configurations_localfile = '".$cfgfile."'");
    if($sql->num_rows < 1) {
    	return false;
    }else{
    	return true;
    }
}

/* function download config dari server
1. ambil data ssh server, config file di server, dan nama config di local
2. konek ke server via ssh, selanjutnya download config via scp
3. save config, terus cek lagi deh, kalo ada filenya return true
*/
function cfgDownload($cfgid, $dbconnection){
	$sql = $dbconnection->prepare("SELECT
	configrecord.txtlocalcfg,
	configrecord.txtdircfg,
	configrecord.txtnmcfg,
	configrecord.txtipsvr,
	configrecord.txtloginsvr,
	configrecord.txtpswdsvr,
	configrecord.txtportsvr
	FROM
		configrecord
	WHERE
		configrecord.txtidcfg = ?");
	$sql->bind_param('i', $cfgid);
    $sql->execute();
    $sql->store_result();
    $sql->bind_result($cfgfile, $cfgdirsvr, $cfgfilesvr, $svrhost, $svrlogin, $svrpasswd, $svrport);
    $sql->fetch();

    $localfile = $cfgfile;
    $remotefile = $cfgdirsvr.$cfgfilesvr;

    $ssh = new SSH2($svrhost, $svrport);
    $sshconnection = $ssh->login($svrlogin, $svrpasswd);
    if (!$sshconnection) {
        return false;    
    } else {
    	$scp = new SCP($ssh);
    	$scp->get($remotefile, $localfile, SCP_LOCAL_FILE);

      	if(cfgLocalExist($cfgid, $dbconnection) == false) {
      		return false;
      	}else{
      		return true;
      	}
    }
    
}

/* function upload config dari local ke server
1. ambil data ssh server, config file di server, dan nama config di local
2. konek ke server via ssh, selanjutnya download config via scp
3. save config, terus cek lagi deh, kalo ada filenya return true
*/
function cfgUpload($cfgid, $dbconnection){
	$sql = $dbconnection->prepare("SELECT
	configrecord.txtlocalcfg,
	configrecord.txtdircfg,
	configrecord.txtnmcfg,
	configrecord.txtipsvr,
	configrecord.txtloginsvr,
	configrecord.txtpswdsvr,
	configrecord.txtportsvr
	FROM
		configrecord
	WHERE
		configrecord.txtidcfg = ?");
	$sql->bind_param('i', $cfgid);
    $sql->execute();
    $sql->store_result();
    $sql->bind_result($cfgfile, $cfgdirsvr, $cfgfilesvr, $svrhost, $svrlogin, $svrpasswd, $svrport);
    $sql->fetch();

    $localfile = $cfgfile;
    $remotefile = $cfgdirsvr.$cfgfilesvr;

    $ssh = new SSH2($svrhost, $svrport);
    $sshconnection = $ssh->login($svrlogin, $svrpasswd);
    if (!$sshconnection) {
        return false;    
    } else {
    	$scp = new SCP($ssh);
    	$scp->put($remotefile, $localfile, SCP_LOCAL_FILE);

      	if(cfgLocalExist($cfgid, $dbconnection) == false) {
      		return false;
      	}else{
      		return true;
      	}
    }

}


/* function cek file di local
1. cek pake fungsi bawaan php file_exists
2. kalo ada return true, gak ada return false
*/
function cfgLocalExist($cfgid, $dbconnection){
	$sql = $dbconnection->prepare("SELECT
	configrecord.txtlocalcfg
	FROM
		configrecord
	WHERE
		configrecord.txtidcfg = ?");
	$sql->bind_param('i', $cfgid);
    $sql->execute();
    $sql->store_result();
    $sql->bind_result($cfgfile);
    $sql->fetch();

    if (!file_exists($cfgfile)){
    	return false;
    }else{
    	return true;
    }
}

/* function display file config yng ada di local
1. ambil nama file di database
2. return nama filenya
*/
function cfgDisplay($cfgid, $dbconnection){
	$sql = $dbconnection->prepare("SELECT
	configrecord.txtlocalcfg
	FROM
		configrecord
	WHERE
		configrecord.txtidcfg = ?");
	$sql->bind_param('i', $cfgid);
    $sql->execute();
    $sql->store_result();
    $sql->bind_result($cfgfile);
    $sql->fetch();

    return $cfgfile;
}

?>