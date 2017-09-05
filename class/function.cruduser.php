<?php

//function for checking existing users when add new users
function useradd($username, $useremail, $dbconnection){
	if($sql = $dbconnection->prepare("SELECT users.users_id FROM users WHERE users.users_name = ? OR users.users_email = ? AND users.users_delete = 0")){
		$sql->bind_param('ss', $username, $useremail);
		$sql->execute();
		$sql->store_result();
		$sql->bind_result($usrid);
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

//function for checking existing users when update users
function userupdate($userid, $username, $useremail, $dbconnection){
	if($sql = $dbconnection->prepare("SELECT users.users_id, users.users_name, users.users_email FROM users WHERE users.users_id <> ? AND users.users_delete = '0' AND users.users_name = ?")){
		$sql->bind_param('is', $userid, $username);
		$sql->execute();
		$sql->store_result();
		$sql->bind_result($usrid, $usrname, $usremail);
		$sql->fetch();

		if($sql->num_rows == 0){
			if($sql = $dbconnection->prepare("SELECT users.users_id, users.users_name, users.users_email FROM users WHERE users.users_id <> ? AND users.users_delete = '0' AND users.users_email = ?")){
				$sql->bind_param('is', $userid, $useremail);
				$sql->execute();
				$sql->store_result();
				$sql->bind_result($usrid, $usrname, $usremail);
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