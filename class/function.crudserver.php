<?php

//fungsi untuk check existing servers when add new
function serveradd($svrname, $svrhost, $dbconnection){
	if($sql = $dbconnection->prepare("SELECT servers.servers_id FROM servers WHERE servers.servers_name = ? OR servers.servers_hostname = ? AND servers.servers_delete = 0")){
		$sql->bind_param('ss', $svrname, $svrhost);
		$sql->execute();
		$sql->store_result();
		$sql->bind_result($svrid);
		$sql->fetch();

		if($sql->num_rows == 0){
			return true;
		}else{
			return false;
		}

	}else{
		return false;
	}
}

//function for checking existing server when update users
function serverupdate($svrid, $svrname, $svrhost, $dbconnection){
	if($sql = $dbconnection->prepare("SELECT servers.servers_id, servers.servers_name, servers.servers_hostname FROM servers WHERE servers.servers_id <> ? AND servers.servers_delete = '0' AND servers.servers_name = ?")){
		$sql->bind_param('is', $svrid, $svrname);
		$sql->execute();
		$sql->store_result();
		$sql->bind_result($idsvr, $namesvr, $hostsvr);
		$sql->fetch();

		if($sql->num_rows == 0){
			if($sql = $dbconnection->prepare("SELECT servers.servers_id, servers.servers_name, servers.servers_hostname FROM servers WHERE servers.servers_id <> ? AND servers.servers_delete = '0' AND servers.servers_hostname = ?")){
				$sql->bind_param('is', $svrid, $svrhost);
				$sql->execute();
				$sql->store_result();
				$sql->bind_result($idsvr, $namesvr, $hostsvr);
				$sql->fetch();
				if($sql->num_rows == 0){
					return true;
				}else{
					return false;
				}
				
			}else{
				return false;
			}

		}else{
			return false;
		}

	}else{
		return false;
	}
}

?>