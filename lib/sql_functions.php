<?php
//Biblioteca de conexión a SQLServer 2008.
//Se incorporan las funciones para abstracción de código.
//Permite utilizar una estructura de acceso a datos similar a MYSQL y POSTGRESQL
//Ayudando a disminuir la cantidad de código utilizado.
//Todos los recordsets son utilizados en matrices, pero se debe considerar el primer
//Autor Alfonso.
error_reporting(0);
include("configuracion.php");

function sql_connect($query){
$serverName = MSSQL_SERVERIP; //serverName\instanceName
$connectionInfo = array( "Database"=>MSSQL_NOMBREDB, "UID"=>MSSQL_USERNAME, "PWD"=>MSSQL_PASSWORD);
$con = sqlsrv_connect( $serverName, $connectionInfo);
$msquery = sqlsrv_query($con,$query);
$resultado[0] = "";
while ($row = sqlsrv_fetch_array($msquery, SQLSRV_FETCH_ASSOC)) {
  array_push($resultado,$row);
}
unset($resultado[0]);
return $resultado;  
}

function sql_getfield($query,$field) {
$serverName = MSSQL_SERVERIP; //serverName\instanceName
$connectionInfo = array( "Database"=>MSSQL_NOMBREDB, "UID"=>MSSQL_USERNAME, "PWD"=>MSSQL_PASSWORD);
$con = sqlsrv_connect( $serverName, $connectionInfo);
$msquery = sqlsrv_query($con,$query);
$row = sqlsrv_fetch_array($msquery);
$resultado = $row[$field];
sqlsrv_close($con);
return $resultado;
}

function sql_getfieldf($query,$field) {
$serverName = MSSQL_SERVERIP; //serverName\instanceName
$connectionInfo = array( "Database"=>MSSQL_NOMBREDB, "UID"=>MSSQL_USERNAME, "PWD"=>MSSQL_PASSWORD);
$con = sqlsrv_connect( $serverName, $connectionInfo);
$msquery = sqlsrv_query($con,$query);
$row = sqlsrv_fetch_array($msquery);
$resultado = date("d/m/Y",$row[$field]->getTimestamp());
sqlsrv_close($con);
return $resultado;
}

function sql_resultdt($result,$row,$field){  
	$row = $result[$row];    
	$resultado = date("d/m/Y H:i:s",$row[$field]->getTimestamp());
	$resultado = str_replace('//','',$resultado);
	return $resultado;
}

/*
function sql_insert_getid($query){
$serverName = MSSQL_SERVERIP; //serverName\instanceName
$connectionInfo = array( "Database"=>MSSQL_NOMBREDB, "UID"=>MSSQL_USERNAME, "PWD"=>MSSQL_PASSWORD);
$con = sqlsrv_connect( $serverName, $connectionInfo);
$msquery = sqlsrv_query($con,$query."; SELECT SCOPE_IDENTITY() AS id");
$next = sqlsrv_next_result($msquery);
$row = sqlsrv_fetch_array($msquery);
$resultado = $row['id'];
sqlsrv_close($con);
return $resultado;
}
*/

function sql_insert_getid($query){
	$serverName = MSSQL_SERVERIP; 
	$connectionInfo = array( "Database"=>MSSQL_NOMBREDB, "UID"=>MSSQL_USERNAME, "PWD"=>MSSQL_PASSWORD);
	$con = sqlsrv_connect( $serverName, $connectionInfo);
	//$con = false;
	if($con == false){
		throw new Exception('Error al realizar la conexión con la Base de Datos. Favor de intentar más tarde.');
	}
	$msquery = sqlsrv_query($con,$query."; SELECT SCOPE_IDENTITY() AS id");
	$next = sqlsrv_next_result($msquery);	
	$row = sqlsrv_fetch_array($msquery);
	$resultado = null;
	$resultado = $row['id'];
	sqlsrv_close($con);
	if(empty($resultado) || $resultado == null){
		throw new Exception('Error al insertar en la Base de Datos. Favor de intentar más tarde.');
	}
	/*if($msquery == false || $next == false || $row == false || empty($resultado)){
		throw new Exception('Error al insertar en la Base de Datos. Favor de intentar más tarde.');
	}*/
	return $resultado;
}

function do_sql($query){
$serverName = MSSQL_SERVERIP; //serverName\instanceName
$connectionInfo = array( "Database"=>MSSQL_NOMBREDB, "UID"=>MSSQL_USERNAME, "PWD"=>MSSQL_PASSWORD);
$con = sqlsrv_connect($serverName, $connectionInfo);
$msquery = sqlsrv_query($con,$query);
sqlsrv_close($msquery);
}

function sql_result($result,$row,$field){  
	$row = $result[$row];    
	$resultado = $row[$field];   
	return $resultado;
}

function sql_resultf($result,$row,$field){  
$row = $result[$row]; 
$resultado = date("d/m/Y",$row[$field]->getTimestamp());
$resultado = str_replace('//','',$resultado);
if($resultado == '31/12/1969'){
	$resultado = "";
}else if( $resultado == ''){
	$resultado = "";
}
return $resultado;
}

function sql_numrows($result){
$resultado = count($result);
return $resultado;
}
?>

