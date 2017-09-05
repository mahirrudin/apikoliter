<?php
error_reporting(0);
include('Net/SSH2.php');

$host = "localhost";
$username = "root";
$password = "";
$dbname = "apikoliter";

$dbconnection = new mysqli($host, $username, $password, $dbname);
if ($dbconnection->connect_error) {
     die("Connection failed: " . $dbconnection->connect_error);
}

$sql = "SELECT
  servers.servers_id AS txtidsvr,
  servers.servers_hostname AS txtipsvr,
  servers.servers_login AS txtloginsvr,
  servers.servers_password AS txtpswdsvr,
  servers.servers_port AS txtportsvr
FROM
  servers
WHERE
  servers.servers_delete = '0'";
  $results = $dbconnection->query($sql);

  if ($results->num_rows > 0) {
  while($dataserver = $results->fetch_object()) {
    $ssh = new Net_SSH2($dataserver->txtipsvr, $dataserver->txtportsvr);
  	$sshconnection = $ssh->login($dataserver->txtloginsvr, $dataserver->txtpswdsvr);
    	if ($sshconnection == false) {
        $svrid = $dataserver->txtidsvr;
        $sql = "INSERT INTO svrstatus (`svrstatus_serverid`, `svrstatus_date`, `svrstatus_down`) VALUES ('$svrid', now(), '1');";
          if (!$dbconnection->query($sql)) {
            echo "SSH connection ".$dataserver->txtipsvr." failed, and also query error : ".mysqli_error($dbconnection);
        	   } 
          } else {
            $svrid = $dataserver->txtidsvr;
            $cpuusage = $ssh->exec("top -bn1 | awk '/Cpu/ { cpu = 100 - $8 }; END { print cpu }'");
            $ramtotal = $ssh->exec("awk '/MemTotal/ { print $2 }' /proc/meminfo");
            $ramfree = $ssh->exec("awk '/MemFree/ { print $2 }' /proc/meminfo");
            $ramusage = $ramtotal - $ramfree;
            $hddtotal = $ssh->exec("df -k / | tail -1 | awk '{print $2}'");
            $hddusage = $ssh->exec("df -k / | tail -1 | awk '{print $3}'");
            $ssh->disconnect();

            $sql = "INSERT INTO svrstatus (`svrstatus_serverid`, `svrstatus_date`, `svrstatus_down`, `svrstatus_cpuusage`, `svrstatus_ramtotal`, `svrstatus_ramusage`, `svrstatus_hddtotal`, `svrstatus_hddusage`) VALUES ('$svrid', now(), '0', '$cpuusage', '$ramtotal', '$ramusage', '$hddtotal', '$hddusage');";
              if (!$dbconnection->query($sql)) {
                echo "SSH connection ".$dataserver->txtipsvr." success, but query error : ".mysqli_error($dbconnection);
                  }
          }
      }

  }

mysqli_free_result($results); 
$dbconnection->close();

?>