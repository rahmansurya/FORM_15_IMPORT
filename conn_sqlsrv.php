<?php 
$serverName = "<server-name>"; 
$uid = "<user>";   
$pwd = "<password>";  
$databaseName = "<db-name>"; 

$connectionInfo = array( "UID"=>$uid,                            
                         "PWD"=>$pwd,                            
                         "Database"=>$databaseName); 
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if (!$conn) {
    die("Koneksi database gagal: " . sqlsrv_errors());
}
?>
