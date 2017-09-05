<?php
include_once ('class/function.cruduser.php');

if(isset($appuserid)) {

$contenturl = $_SERVER['SCRIPT_NAME'].'?myprofile';

$sql = $dbconnection->query("SELECT
                users.users_id AS txtid,
                users.users_name AS txtname,
                users.users_email AS txtemail,
                users.users_pass AS txtpass,
                users.users_type AS txttype,
                users.users_registered AS txtdate
                FROM
                users
                WHERE
                users.users_delete = 0 AND 
                users.users_id = {$appuserid}");
      $getuserdata = $sql->fetch_array();

}

if(isset($_POST['btn-myupdate'])) {
 $userid = $getuserdata['txtid'];
 $username = $getuserdata['txtname'];
 $userpass = $dbconnection->real_escape_string($_POST['txtpass']);
 $useremail = $dbconnection->real_escape_string($_POST['txtemail']);

  if(userupdate($userid, $username, $useremail, $dbconnection) == true){
        $sql = $dbconnection->prepare("UPDATE users SET users_pass=?, users_email=? WHERE users_id=?");
        $sql->bind_param("ssi", $userpass, $useremail, $userid);
        if(!$sql->execute()){
            $alertmsg = '"Data tidak berhasil diupdate"';
            include ('alert/failed.php');
        }else{
            $alertmsg = '"Data user berhasil diupdate"';
            include ('alert/success.php');
        }
    }else{
        $alertmsg = '"Email sudah ada"';
        include ('alert/failed.php');
    }

}

?>
            <!-- profile saya -->
            <div class="row">
                <div class="col-lg-12">

                    <div class="panel col-lg-12">
                    <div class="panel">
                        <div class="panel-heading head-border">
                            Profile Saya
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="post">

                                <div class="form-group">
                                    <label for="txtname" class="col-lg-3 col-sm-2 control-label">Name</label>
                                    <div class="col-lg-8 col-md-6">
                                        <input type="text" class="form-control" name="txtname" disabled value="<?php if(isset($_GET['myprofile'])) { echo $getuserdata['txtname']; } ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="txtemail" class="col-lg-3 col-sm-2 control-label">Email</label>
                                    <div class="col-lg-8 col-md-6">
                                        <input type="text" class="form-control" name="txtemail" value="<?php if(isset($_GET['myprofile'])) {echo $getuserdata['txtemail']; } ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="txtpass" class="col-lg-3 col-sm-2 control-label">New Password</label>
                                    <div class="col-lg-8 col-md-6">
                                        <input type="password" class="form-control" name="txtpass" value="<?php if(isset($_GET['myprofile'])) {echo $getuserdata['txtname']; } ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                <label class="col-lg-3 col-sm-2 control-label"> </label>
                                    <div class="col-lg-8 col-md-6">
                                        <button type="submit" name="btn-myupdate" class="btn btn-success btn-block">Update Profile</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    </div>

                </div>               
            </div>
            <!-- profile saya -->