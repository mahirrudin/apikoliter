<?php

// fungsi untuk check username dan password login
function login($username, $password, $dbconnection){

	if($sql = $dbconnection->prepare("SELECT
            users.users_id,
            users.users_name,
            users.users_pass,
            users.users_type 
            FROM users
            WHERE 
            users.users_delete = 0
            AND
            users.users_name = ?")){
		$sql->bind_param('s', $username);
		$sql->execute();
		$sql->store_result();
		$sql->bind_result($usrid, $usrname, $usrpass, $usrtype);
		$sql->fetch();
		
		if($sql->num_rows == 1){
			if($usrpass == $password){

				$id = preg_replace("/[^0-9]+/", "", $usrid);
				$_SESSION['userid'] = $id;
				$_SESSION['username'] = $usrname;
				$_SESSION['usertype'] = $usrtype;

				return true;

			}else{

				return false;	
			}
		}else{

			return false;	
		}
	}
}

// fungsi untuk check login dari sesi yang disimpan
function logincheck($dbconnection){

	if(isset($_SESSION['userid'], $_SESSION['username'])){
		$idsession = $_SESSION['userid'];
		$usersession = $_SESSION['username'];
		
		if($sql = $dbconnection->prepare("SELECT users.users_name
					 FROM users 
					 WHERE 
					 users.users_delete = 0
					AND
					users.users_id = ? 
					 LIMIT 1")){
			$sql->bind_param('i', $idsession);
			$sql->execute();
			$sql->store_result();
			
			if($sql->num_rows == 1){
				$sql->bind_result($usrname);
				$sql->fetch();
				
				if($usersession == $usrname){

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

function openmenu( $menuHref, $class ) {

	$getFilePHP = $_SERVER['PHP_SELF'];
	$getFilePHP = explode( '/', $getFilePHP );
	$FilePHP = trim($getFilePHP[ count($getFilePHP)-1 ]);

	$explode_menu_href = explode(',', trim($menuHref));
	$flip_explode = array_flip($explode_menu_href);
	
	if ( isset($flip_explode[$FilePHP]) ) {
		echo $class;
	}

}