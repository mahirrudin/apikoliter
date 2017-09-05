<!-- data all user -->

<?php

include_once ('class/function.cruduser.php');

$contenturl = $_SERVER['SCRIPT_NAME'].'?user';

if($appuseraccess == "System Administrator") {

include ('forbidden.php');

} elseif ($appuseraccess == "IT Manager") {

    if(isset($_POST['btn-usradd'])) {

    $username = $dbconnection->real_escape_string($_POST['txtname']);
    $userpass = $dbconnection->real_escape_string($_POST['txtpass']);
    $useremail = $dbconnection->real_escape_string($_POST['txtemail']);
    $usertype = $dbconnection->real_escape_string($_POST['txttype']);

        if(useradd($username, $useremail, $dbconnection) == true){

        $sql = $dbconnection->prepare("INSERT INTO users (users_name, users_pass, users_email, users_type, users_registered) VALUES (?, ?, ?, ?, now() )");
        $sql->bind_param("ssss",$username, $userpass, $useremail, $usertype);
            if(!$sql->execute()){
                $alertmsg = '"Data tidak berhasil ditambahkan"';
                include ('alert/failed.php');
            }else{
                $alertmsg = '"Data user berhasil ditambahkan"';
                include ('alert/success.php');
            }

        }else{
            $alertmsg = '"Nama atau email user sudah ada"';
            include ('alert/failed.php');
        }
    }

    if(isset($_GET['update'])) {
      $userid = $dbconnection->real_escape_string($_GET['update']);
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
                users.users_id = {$userid}");
      $getuserdata = $sql->fetch_array();
    }

    if(isset($_GET['delete'])) {
      $userid = $dbconnection->real_escape_string($_GET['delete']);
      $sql = $dbconnection->prepare("UPDATE users SET users_delete='1' WHERE users_id=? LIMIT 1");
      $sql->bind_param("i", $userid);
        if(!$sql->execute()){
            $alertmsg = '"Data tidak berhasil dihapus"';
            include ('alert/failed.php');
        }else{
            $alertmsg = '"Data user berhasil dihapus"';
            include ('alert/success.php');
        }
    }

    if(isset($_POST['btn-usrupdate'])) {
     $username = $dbconnection->real_escape_string($_POST['txtname']);
     $userpass = $dbconnection->real_escape_string($_POST['txtpass']);
     $useremail = $dbconnection->real_escape_string($_POST['txtemail']);
     $usertype = $dbconnection->real_escape_string($_POST['txttype']);
     $userid = $dbconnection->real_escape_string($_POST['txtid']);
        if(userupdate($userid, $username, $useremail, $dbconnection) == true){
            $sql = $dbconnection->prepare("UPDATE users SET users_name=?, users_pass=?, users_email=?, users_type=? WHERE users_id=?");
            $sql->bind_param("ssssi",$username, $userpass, $useremail, $usertype, $userid);
            if(!$sql->execute()){
                $alertmsg = '"Data tidak berhasil diupdate"';
                include ('alert/failed.php');
            }else{
                $alertmsg = '"Data user berhasil diupdate"';
                include ('alert/success.php');
            }
        }else{
            $alertmsg = '"Nama atau email user sudah ada"';
            include ('alert/failed.php');
        }
    }

?>
            <div class="row">

                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Data Users
                        </header>
                        <div class="panel-body">
                            <form name="report_monitoring" class="form-inline" role="form" method="post">
                                <div class="form-group">
                                <a class="btn btn-success" href="?user&add"><i class="fa fa-plus"></i> Add New Users</a>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>

                <?php if(isset($_GET['add']) || isset($_GET['update'])) { ?>
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading head-border">
                            Kelola Data Users
                            <span class="tools pull-right">
                                <a class="fa fa-repeat box-refresh" href="javascript:;"></a>
                                <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>
                                <a class="t-close fa fa-times" href="javascript:;"></a>
                            </span>
                        </div>
                            <div class="panel-body" style="display: block;">

                                <form class="form-horizontal" role="form" method="post">
                                    <div class="panel-body col-md-6">
                                    
                                        <div class="form-group">
                                            <label for="txtname" class="col-lg-3 col-sm-2 control-label">Nama</label>
                                            <div class="col-lg-8">
                                                <input type="hidden" class="form-control" name="txtid" value="<?php if(isset($_GET['update'])) echo $getuserdata['txtid']; ?>">
                                                <input type="text" class="form-control" name="txtname" required placeholder="nama" value="<?php if(isset($_GET['update'])) echo $getuserdata['txtname']; ?>">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="txtemail" class="col-lg-3 col-sm-2 control-label">Email</label>
                                            <div class="col-lg-8">
                                                <input type="email" class="form-control" name="txtemail" required placeholder="email" value="<?php if(isset($_GET['update'])) echo $getuserdata['txtemail']; ?>">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="panel-body col-md-6">

                                        <div class="form-group">
                                            <label for="txtpass" class="col-lg-3 col-sm-2 control-label">Password</label>
                                            <div class="col-lg-8">
                                                <input type="password" class="form-control" name="txtpass" required placeholder="password" value="<?php if(isset($_GET['update'])) echo $getuserdata['txtpass']; ?>">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="txttype" class="col-lg-3 col-sm-2 control-label">Hak Akses</label>
                                            <div class="col-lg-8">
                                                <select name="txttype" class="form-control">                                                   
                                                    <option value="System Administrator">System Administrator</option>
                                                    <option value="IT Manager" <?php if(isset($_GET['update']) && $getuserdata['txttype'] == "IT Manager") {echo "selected";} ?>>IT Manager</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-lg-offset-3 col-lg-8">
                                            <?php 
                                                if(isset($_GET['add'])) {
                                                    echo '<button type="submit" name="btn-usradd" class="btn btn-success btn-block">ADD USER</button>';

                                                } elseif (isset($_GET['update'])) {
                                                    echo '<button type="submit" name="btn-usrupdate" class="btn btn-primary btn-block">UPDATE USER</button>';
                                                } else {

                                                }
                                            ?>    
                                                
                                            </div>
                                        </div>
                                    
                                    </div>
                                </form>
                            </div>
                    </div>
                </div>
                <?php } ?>

                <div class="col-lg-12">
                    <section class="panel">
                        <table class="table responsive-data-table data-table">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Hak Akses</th>
                                <th>Terdaftar</th>                           
                                <th class="hidden-xs"><i class="fa fa-cog"></i> Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
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
                                    users.users_type = 'System Administrator'");
                              $no = 1;
                                while($rowuserdata = $sql->fetch_array())
                            { ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $rowuserdata['txtname']; ?></td>
                                <td><?php echo $rowuserdata['txtemail']; ?></td>
                                <td><?php echo $rowuserdata['txttype']; ?></td>
                                <td><?php echo $rowuserdata['txtdate']; ?></td>                         
                                <td class="hidden-xs">
                                    <a class="btn btn-primary btn-xs" href="?user&update=<?php echo $rowuserdata['txtid']; ?>"><i class="fa fa-pencil"> update</i></a>
                                    <a onclick="return confirm('are you sure want to delete ?');" class="btn btn-danger btn-xs" href="?user&delete=<?php echo $rowuserdata['txtid']; ?>"><i class="fa fa-trash-o"> delete</i></a>
                                </td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </section>
                </div>

            </div>

<?php 
    } else {

        include ('forbidden.php');
        
    }
?> 

<!-- data all user -->