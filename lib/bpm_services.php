<?php
error_reporting(0);
require_once "lib/nusoap.php";

function bpm_registrar_reclamacion($id_reclamacion){
$client = new nusoap_client(BPM_URL, true);
$result = $client->call("RegistrarReclamacion", array("soap_version"=> SOAP_1_1, "idReclamacion" => $id_reclamacion));
}

function bpm_preregistro_usuarios($id_proveedor_usuario){
$client = new nusoap_client(BPM_URL, true);
$result = $client->call("PreregistroUsuarios", array("soap_version"=> SOAP_1_1, "idUsuario" => $id_proveedor_usuario));
log_file_service($result);
}


function log_file_service($msg){
	$logfile = "logservice.txt";
	$fileh = fopen($logfile,'a');
	$msg = date("m-d-Y h:i:s -->").($msg)."<-- \n";
	fwrite($fileh, $msg);
	fclose($fileh);
}


function bpm_primercontacto($id_reclamacion){
$folder_id = sql_getfield("SELECT folder_id FROM reclamaciones WHERE id_reclamacion = ".$id_reclamacion,"folder_id");
$client = new nusoap_client(BPM_URL, true);
$result = $client->call("PrimerContacto", array("soap_version"=> SOAP_1_1, "folderid" => $folder_id));
}

function bpm_solicitardatos($id_reclamacion){
$folder_id = sql_getfield("SELECT folder_id FROM reclamaciones WHERE id_reclamacion = ".$id_reclamacion,"folder_id");
$client = new nusoap_client(BPM_URL, true);
$result = $client->call("SolicitarDatos", array("soap_version"=> SOAP_1_1, "folderid" => $folder_id));
}

function bpm_enviardatos($id_reclamacion){
$folder_id = sql_getfield("SELECT folder_id FROM reclamaciones WHERE id_reclamacion = ".$id_reclamacion,"folder_id");
$client = new nusoap_client(BPM_URL, true);
$result = $client->call("EnviarDatos", array("soap_version"=> SOAP_1_1, "folderid" => $folder_id));
}

function bpm_enviarrespuesta($id_reclamacion){
$folder_id = sql_getfield("SELECT folder_id FROM reclamaciones WHERE id_reclamacion = ".$id_reclamacion,"folder_id");
$client = new nusoap_client(BPM_URL, true);
$result = $client->call("EnviarRespuesta", array("soap_version"=> SOAP_1_1, "folderid" => $folder_id));
}

function bpm_enviarevaluacion($id_reclamacion){
$folder_id = sql_getfield("SELECT folder_id FROM reclamaciones WHERE id_reclamacion = ".$id_reclamacion,"folder_id");
$client = new nusoap_client(BPM_URL, true);
$result = $client->call("EnviarEvaluacion", array("soap_version"=> SOAP_1_1, "folderid" => $folder_id));
}

function bpm_resolucionprofeco($id_reclamacion){
$folder_id = sql_getfield("SELECT folder_id FROM reclamaciones WHERE id_reclamacion = ".$id_reclamacion,"folder_id");
$client = new nusoap_client(BPM_URL, true);
$result = $client->call("ResolucionProfeco", array("soap_version"=> SOAP_1_1, "folderid" => $folder_id));
}

?>