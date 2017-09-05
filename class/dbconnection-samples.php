<?php

$host = "your_db_hosts";
$username = "your_db_user";
$password = "your_db_password";
$dbname = "your_db_name";

$dbconnection = new mysqli($host, $username, $password, $dbname);
if ($dbconnection->connect_error) {
     die("Connection failed: " . $dbconnection->connect_error);
}

?>