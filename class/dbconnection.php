<?php

$host = "localhost";
$username = "root";
$password = "Passw0rd?";
$dbname = "apikoliter";

$dbconnection = new mysqli($host, $username, $password, $dbname);
if ($dbconnection->connect_error) {
     die("Connection failed: " . $dbconnection->connect_error);
}

?>