<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Aplikasi Konfigurasi Linux Server Terpusat">
    <link rel="shortcut icon" href="assets/img/ico/favicon.png">
    <title>ApiKoLiter - Login</title>

    <!-- Base Styles -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">

    <!--sweetalert-->
    <script src="assets/js/sweetalert.js"></script>
    <link rel="stylesheet" href="assets/css/sweetalert.css">

</head>

  <body class="login-body">

      <h2 class="form-heading">Aplikasi Konfigurasi Linux Server Terpusat</h2>
      <div class="container log-row">   
          <form class="form-signin" method="post">
              <div class="login-wrap">
                  <input type="text" class="form-control" placeholder="Nama" name="txt_name" required autofocus>
                  <input type="password" class="form-control" placeholder="Password" name="txt_password" required>
                  <button class="btn btn-lg btn-success btn-block" type="submit" name="btn-login">Login</button>
              </div>
          </form>
      </div>

      <?php
      include_once ('class/dbconnection.php');
      include_once ('class/function.login.php');

        session_start();
        
        if(logincheck($dbconnection) == true){
          header('location: home.php');
        exit();

        } else {

          if(isset($_POST['btn-login'])){
            if(isset($_POST['txt_name']) && isset($_POST['txt_password'])){
              $txt_name = $_POST['txt_name'];
              $txt_password = $_POST['txt_password'];
              if(login($txt_name, $txt_password, $dbconnection) == true) {
                header('location: home.php');
                exit();
              }

      ?>
          <script>
          swal({
            title: "Login Gagal",
            text: "username atau password anda salah",
            type: "warning",
            showCancelButton: false,
            confirmButtonClass: 'btn-warning',
            confirmButtonText: 'Tutup'
          });
          </script>
      
      <?php
            }
          }
        }
      ?>
      
      <!--jquery-1.10.2.min-->
      <script src="assets/js/jquery-1.11.1.min.js"></script>
      <!--Bootstrap Js-->
      <script src="assets/js/bootstrap.min.js"></script>
      <script src="assets/js/jrespond.min.js"></script>

  </body>
</html>
