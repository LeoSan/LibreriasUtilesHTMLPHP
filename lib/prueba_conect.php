<?php
include("configuracion.php");

$serverName = MSSQL_SERVERIP; //serverName\instanceName
echo "ServerName".$serverName;
$connectionInfo = array( "Database"=>MSSQL_NOMBREDB, "UID"=>MSSQL_USERNAME, "PWD"=>MSSQL_PASSWORD);
$con = sqlsrv_connect( $serverName, $connectionInfo) ;
if( $con ) {
     echo "Conexión establecida.<br />";
}else{
     echo "Conexión no se pudo establecer.<br />";
     die( print_r( sqlsrv_errors(), true));
}


?>


