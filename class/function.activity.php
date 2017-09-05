<?php

/* function catat aktifitas
1. tentuin aktifitasnya, terus save di database deh
*/

function ActControl($actname, $svcsid, $userid, $dbconnection){

	$sqlget = $dbconnection->prepare("SELECT
			servicesinstalled.txtidsvc,
			servicesinstalled.txtidsvr
			FROM
			servicesinstalled
			WHERE
			servicesinstalled.txtidsvcs = ?");
	$sqlget->bind_param('i', $svcsid);
    $sqlget->execute();
    $sqlget->store_result();
    $sqlget->bind_result($svcid, $svrid);
    $sqlget->fetch();

	$sqlact = $dbconnection->prepare("INSERT INTO activity (`activity_name`, `activity_serviceid`, `activity_configid`, `activity_serverid`, `activity_userid`, `activity_date`) VALUES (?, ?, NULL, ?, ?, now())");
    $sqlact->bind_param("siii", $actname, $svcid, $svrid, $userid);
    $sqlact->execute();
}

function ActUninstall($actname, $svcsid, $userid, $dbconnection){

	$sqlget = $dbconnection->prepare("SELECT
			svcstatus.svcstatus_servicesid,
			svcstatus.svcstatus_serverid
			FROM
			svcstatus
			WHERE
			svcstatus.svcstatus_id = ?");
	$sqlget->bind_param('i', $svcsid);
    $sqlget->execute();
    $sqlget->store_result();
    $sqlget->bind_result($svcid, $svrid);
    $sqlget->fetch();

	$sqlact = $dbconnection->prepare("INSERT INTO activity (`activity_name`, `activity_serviceid`, `activity_configid`, `activity_serverid`, `activity_userid`, `activity_date`) VALUES (?, ?, NULL, ?, ?, now())");
    $sqlact->bind_param("siii", $actname, $svcid, $svrid, $userid);
    $sqlact->execute();
}

function ActPower($actname, $svrid, $userid, $dbconnection){
    $sql = $dbconnection->prepare("INSERT INTO activity (`activity_name`, `activity_serviceid`, `activity_configid`, `activity_serverid`, `activity_userid`, `activity_date`) VALUES (?, NULL, NULL, ?, ?, now())");
    $sql->bind_param("sii", $actname, $svrid, $userid);
    if ($sql->execute() == false) {
        return false;
    }else{
        return true;
    }
}

function ActInstall($actname, $svcid, $svrid, $userid, $dbconnection){
	$sql = $dbconnection->prepare("INSERT INTO activity (`activity_name`, `activity_serviceid`, `activity_configid`, `activity_serverid`, `activity_userid`, `activity_date`) VALUES (?, ?, NULL, ?, ?, now())");
    $sql->bind_param("siii", $actname, $svcid, $svrid, $userid);
    if ($sql->execute() == false) {
        return false;
    }else{
        return true;
    }
}

function ActConfig($actname, $svcsid, $cfgid, $userid, $dbconnection){

    $sqlget = $dbconnection->prepare("SELECT
            servicesinstalled.txtidsvc,
            servicesinstalled.txtidsvr
            FROM
            servicesinstalled
            WHERE
            servicesinstalled.txtidsvcs = ?");
    $sqlget->bind_param('i', $svcsid);
    $sqlget->execute();
    $sqlget->store_result();
    $sqlget->bind_result($svcid, $svrid);
    $sqlget->fetch();

    $sqlact = $dbconnection->prepare("INSERT INTO activity (`activity_name`, `activity_serviceid`, `activity_configid`, `activity_serverid`, `activity_userid`, `activity_date`) VALUES (?, ?, ?, ?, ?, now())");
    $sqlact->bind_param("siiii", $actname, $svcid, $cfgid, $svrid, $userid);
    $sqlact->execute();
}

?>