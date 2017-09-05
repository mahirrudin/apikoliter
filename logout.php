<?php
session_start();
// session_destroy();
unset($_SESSION['userid']);
unset($_SESSION['username']);
unset($_SESSION['usertype']);

session_destroy($_SESSION['userid']);
session_destroy($_SESSION['username']);
session_destroy($_SESSION['usertype']);

header('location:login.php');
exit
?>