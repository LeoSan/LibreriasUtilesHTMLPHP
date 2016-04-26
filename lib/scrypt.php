<?php
//Biblioteca de funciones de seguridad.
error_reporting(E_ALL);
ini_set('display_errors', 1);


//header("x-frame-options", "DENY");
//header( "Set-Cookie: hidden=value; HttpOnly;Secure" );
//header( "Set-Cookie: name=value; HttpOnly;Secure" );
header("X-XSS-Protection: 1");
header('Content-Type: text/html; charset=utf-8');
session_set_cookie_params(0, NULL, NULL, TRUE, TRUE);
date_default_timezone_set('America/Mexico_City');
require_once("sql_functions.php");
require_once("bpm_services.php");
require_once("email.php");

function randomstr($length) {
    $characters = '023456789abcdefgijkmnopqrstuwxyzABCDEFGIJKLMNOPQRSTUWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function gen_captcha($seed){
/*$base64shuffle = base64_encode($seed);
$base64shuffle = base64_encode($base64shuffle);
$base64shuffle = base64_encode($base64shuffle);*/
$cadena = substr(/*$base64shuffle*/$seed,0,5);
return $cadena;
}

function gen_talkcaptcha($seed){
$sonido["a"]="ah,";$sonido["b"]="vee,";$sonido["c"]="ce,";$sonido["d"]="de,";$sonido["e"]="e,";$sonido["f"]="efe,";$sonido["g"]="ge,";
$sonido["h"]="hache.,";$sonido["i"]="i,latina,,";$sonido["j"]="jota,";$sonido["k"]="ca,";$sonido["l"]="ele,";$sonido["m"]="eme,";$sonido["n"]="ene,";
$sonido["o"]="oh,";$sonido["p"]="pe,";$sonido["q"]="kuh,";$sonido["r"]="erre,";$sonido["s"]="ese,";$sonido["t"]="te,";$sonido["u"]="u,";
$sonido["v"]="ube.,";$sonido["w"]="doble,u,";$sonido["x"]="equis,";$sonido["y"]="i,griega,";$sonido["z"]="zeta,";$sonido["0"]="cero,";
$sonido["1"]="uno,";$sonido["2"]="dos,";$sonido["3"]="tres,";$sonido["4"]="cuatro,";$sonido["5"]="cinco,";$sonido["6"]="seis,";
$sonido["7"]="siete,";$sonido["8"]="ocho,";$sonido["9"]="nueve,";
$seed = gen_captcha($seed);    
$componentes = str_split(strtolower($seed));
foreach ($componentes as &$value) {
    $talkstring = $talkstring . $sonido[$value].",";
}
$url = "http://translate.google.com/translate_tts?tl=es&q=El%20codigo%20es%20el%20siguiente%20,,,".$talkstring;
$mp3name = randomstr(16);
get_mp3captcha($url, $mp3name);
return $mp3name.".mp3";      
}

function get_mp3captcha($url,$seed){
    $remote_file = $url;
if ($fp_remote = fopen($remote_file, "rb")) {
    $local_file = "temp_audio/".$seed.".mp3";
    if ($fp_local = fopen($local_file, "wb")) {
        while ($buffer = fread($fp_remote, 8192)) {
            fwrite($fp_local, $buffer);
        }
        fclose($fp_local);       
    }
    fclose($fp_remote);
}    
}

function encrypt($pure_string, $encryption_key) {
    $pure_string = base64_encode($pure_string);
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
    return base64_encode($encrypted_string);
}

function decrypt($encrypted_string, $encryption_key) {
    $encrypted_string = base64_decode($encrypted_string);
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
    return base64_decode($decrypted_string);
}

function sql_clean($cadena){
//Simple, rápida y sencilla forma de prevenir SQL injection.
$resultado = str_replace("DROP TABLE","",$cadena);
$resultado = str_replace("+","",$cadena);
$resultado = str_replace("*","",$cadena);
$resultado = str_replace("(","",$resultado);
$resultado = str_replace(")","",$resultado);
$resultado = str_replace(" OR ","",$resultado);
$resultado = str_replace("'","",$resultado);
$resultado = str_replace('"','',$resultado);
$resultado = str_replace('=','',$resultado);
$resultado = str_replace(";","",$resultado);
$resultado = str_replace("<SCRIPT>","",$resultado);
$resultado = str_replace("</SCRIPT>","",$resultado);
$resultado = str_replace("onmouseover","",$resultado);
$resultado = str_replace("onmouseclick","",$resultado);
$resultado = str_replace("onmouseleft","",$resultado);
$resultado = str_replace("onmouseover=prompt","",$resultado);

return $resultado;
}

function pack_session($login,$password,$ip,$tipo){
 if ($tipo == 'usr'){
 $datestream = date("Y-m-d H-i-s");
 $id_usuario = sql_getfield("SELECT id_usuario FROM usuarios WHERE activo = 1 and verificado = 1 AND login='".$login."' AND password='".$password."'","id_usuario");
 if (strlen($id_usuario) == 0){return false;}//Si no existe el usuario.
 $nombre_usuario   = sql_getfield("SELECT nombre FROM usuarios WHERE id_usuario=".$id_usuario,"nombre");
 $apellido_usuario = sql_getfield("SELECT apellido_paterno FROM usuarios WHERE id_usuario=".$id_usuario,"apellido_paterno");
 $nombre_display = $nombre_usuario . " " . $apellido_usuario;
 $datastream = 'id_usuario='.$id_usuario.'*nombre_display='.$nombre_display.'*date='.$datestream."*ip=".$ip."*tipo=".$tipo;
 $crypted = encrypt($datastream,"YoSoyUsuarioIFT2014");
 log_eventos($tipo,$nombre_display,"Acceso al portal","acceso correcto");
 return $crypted;}
    
 if ($tipo == 'prv'){
 $datestream = date("Y-m-d H-i-s");
 $id_usuario = sql_getfield("SELECT id_proveedor_usuario FROM proveedor_usuarios WHERE activo = 1 AND login='".$login."' AND password='".$password."'","id_proveedor_usuario");
 $id_proveedor = sql_getfield("SELECT id_proveedor FROM proveedor_usuarios WHERE login='".$login."' AND password='".$password."'","id_proveedor");
 if (strlen($id_usuario) == 0){return false;}//Si no existe el usuario.
 if ($id_proveedor == 1){return false;}//Si es profeco.
 $nombre_usuario   = sql_getfield("SELECT nombre FROM proveedor_usuarios WHERE id_proveedor_usuario=".$id_usuario,"nombre");
 $apellido_usuario = sql_getfield("SELECT apellido_paterno FROM proveedor_usuarios WHERE id_proveedor_usuario=".$id_usuario,"apellido_paterno");
 $nombre_display = $nombre_usuario . " " . $apellido_usuario;
 $datastream = 'id_usuario='.$id_usuario.'*nombre_display='.$nombre_display.'*date='.$datestream."*ip=".$ip."*tipo=".$tipo."*id_proveedor=".$id_proveedor;
 $crypted = encrypt($datastream,"YoSoyUsuarioIFT2014");
  log_eventos($tipo,$nombre_display,"Acceso al portal","acceso correcto");
 return $crypted;} 

 if ($tipo == 'profeco'){
 $datestream = date("Y-m-d H-i-s");
 $id_usuario = sql_getfield("SELECT id_proveedor_usuario FROM proveedor_usuarios WHERE activo = 1 AND login='".$login."' AND password='".$password."'","id_proveedor_usuario");
 $id_proveedor = sql_getfield("SELECT id_proveedor FROM proveedor_usuarios WHERE login='".$login."' AND password='".$password."'","id_proveedor");
 if (strlen($id_usuario) == 0){return false;}//Si no existe el usuario.
 if ($id_proveedor != 1){return false;}//Si es profeco.
 $nombre_usuario   = sql_getfield("SELECT nombre FROM proveedor_usuarios WHERE id_proveedor_usuario=".$id_usuario,"nombre");
 $apellido_usuario = sql_getfield("SELECT apellido_paterno FROM proveedor_usuarios WHERE id_proveedor_usuario=".$id_usuario,"apellido_paterno");
 $nombre_display = $nombre_usuario . " " . $apellido_usuario;
 $datastream = 'id_usuario='.$id_usuario.'*nombre_display='.$nombre_display.'*date='.$datestream."*ip=".$ip."*tipo=".$tipo."*id_proveedor=".$id_proveedor;
 $crypted = encrypt($datastream,"YoSoyUsuarioIFT2014");
  log_eventos($tipo,$nombre_display,"Acceso al portal","acceso correcto");
 return $crypted;} 
}

function depack_session($session_data){   
 $decrypted = decrypt($session_data,"YoSoyUsuarioIFT2014");
 if(strlen($decrypted > 0)){return false;}
 $data = explode("*",$decrypted);
 foreach ($data as &$value) {
    $datos = explode("=",$value);
    if ($datos[0] == 'id_usuario'){$resultado["id_usuario"]=$datos[1];}
    if ($datos[0] == 'nombre_display'){$resultado["nombre_display"]=$datos[1];}
    if ($datos[0] == 'date'){$resultado["date"]=$datos[1];}
    if ($datos[0] == 'ip'){$resultado["ip"]=$datos[1];}
    if ($datos[0] == 'tipo'){$resultado["tipo"]=$datos[1];}
    if ($datos[0] == 'id_proveedor'){$resultado["id_proveedor"]=$datos[1];}
}
return $resultado;
}

function blacklist_check($login,$password,$ip){
 $tries         = 0;   
 $datestream    = date("Y-m-d H:i:s");
 $login_test    = sql_getfield("SELECT id_usuario FROM usuarios WHERE login='".$login."'","id_usuario");
 $password_test = sql_getfield("SELECT id_usuario FROM usuarios WHERE password='".$password."'","id_usuario");
 
 if(strlen($login_test) != 0){
	if($password_test != $login_test){
		$result       = sql_connect("SELECT * FROM sistema_blacklist WHERE id_usuario=".$login_test);    
		$id_blacklist = sql_result($result,1,"id");
		$tries        = sql_result($result,1,"login_errors");    
		if (strlen($id_blacklist)==0){
			$test_sql     = "INSERT INTO sistema_blacklist (id_usuario,last_try) VALUES (".$login_test.",'".$datestream."')";    
			$id_blacklist = sql_insert_getid("INSERT INTO sistema_blacklist (id_usuario,last_try) VALUES (".$login_test.",'".$datestream."')");
		}
		$last_try  = sql_getfield("SELECT last_try FROM sistema_blacklist WHERE id=".$id_blacklist,"last_try");
		$last_time = $last_try->date;
		$span = dif_minutes($datestream,$last_try); 
		if ($span < 30){
			if ($tries > 1){
				return true;
			}else{
				$tries = $tries + 1;
				do_sql("UPDATE sistema_blacklist SET login_errors = ".$tries.", last_try = '".$datestream."' WHERE id=".$id_blacklist);
				return false;    
			}
		}
		if ($span >= 30){
			do_sql("UPDATE sistema_blacklist SET login_errors = 1, last_try='".$datestream."' WHERE id=".$id_blacklist);
			return false;
		}
	}
 }//Final de que login exista.
  
}

function activo_check($login,$password,$ip){
	/*$tries         = 0;   
	$datestream    = date("Y-m-d H:i:s");*/
	$login_test    = sql_getfield("SELECT id_usuario FROM usuarios WHERE login='".$login."'","id_usuario");
	$password_test = sql_getfield("SELECT id_usuario FROM usuarios WHERE password='".$password."'","id_usuario");

	if(strlen($login_test) != 0){
		if($password_test != $login_test){
			$result = sql_getfield("SELECT verificado FROM usuarios WHERE login='".$login."'","verificado"); 
			if($result = 0){
				return false;
			}else{
				return true;
			}
		}
	}//Final de que login exista.
}

function dif_minutes($datetime1,$datetime2){
	$to_time   = strtotime($datetime2);
	$from_time = strtotime($datetime1);
	return (abs($to_time - $from_time)) / 60;        
}

function utf8html($cadena){
return htmlentities($cadena, ENT_QUOTES);    
}

function is_odd($numero){
if ($numero % 2 == 0) {
return(false);
}else{return(true);}
}

function fecha_a_sql($fecha){
	if($fecha != ""){
		$datos = explode("/", $fecha);
		if ($datos[0]==''){$datos = explode("/",$fecha);}
		return $datos[2] . "-" .  $datos [1] . "-" .  $datos [0];
	}else{
		return $fecha;
	}
} 

function fecha_a_profeco($fecha){
	if($fecha != ""){
		$datos = explode("/", $fecha);
		if ($datos[0]==''){$datos = explode("/",$fecha);}
		return $datos[2] . "-" .  $datos [1] . "-" .  $datos [0];
	}else{
		return $fecha;
	}
} 

function html_translate($cadena){
 $cadena = str_replace("Á",'&Aacute;',$cadena);
 $cadena = str_replace("É",'&Eacute;',$cadena);
 $cadena = str_replace("Í",'&Iacute;',$cadena);
 $cadena = str_replace("Ó",'&Oacute;',$cadena);
 $cadena = str_replace("Ú",'&Uacute;',$cadena);
 
 $cadena = str_replace("á",'&aacute;',$cadena);
 $cadena = str_replace("é",'&eacute;',$cadena);
 $cadena = str_replace("í",'&iacute;',$cadena);
 $cadena = str_replace("ó",'&oacute;',$cadena);
 $cadena = str_replace("ú",'&uacute;',$cadena);
 
 $cadena = str_replace("Ñ",'&Ntilde;',$cadena);
 $cadena = str_replace("ñ",'&ntilde;',$cadena);
 $cadena = str_replace("Ü",'&Uuml;',$cadena);
 $cadena = str_replace("ü",'&uuml;',$cadena);    
 return $cadena;
}

function fecha_de_sql($fecha){
    $datos = explode("-", $fecha);
    if ($datos[0]==''){$datos = explode("/",$fecha);}
    $pureday = explode(" ",$datos[2]);
    
    return $pureday[0] . "/" .  $datos [1] . "/" .  $datos [0];       
}

function safe($query){
  $query = str_replace("&","",$query);  
  $query = str_replace("%","",$query);
  $query = str_replace("'","",$query);
  $query = str_replace("=","",$query);
  $query = str_replace("?","",$query);
  $query = str_replace(";","",$query);
 return ($query);
}

function log_eventos($tipo,$nombre_usr,$seccion,$evento){
 $fecha_stamp = date("Y-m-d H:i:s");
 $query = "INSERT INTO sistema_log
           (tipo_usuario
           ,nombre_usuario
           ,seccion
           ,evento
           ,fecha_hora_evento)
     VALUES
           ('".$tipo."'
           ,'".$nombre_usr."'
           ,'".$seccion."'
           ,'".$evento."'
           ,'".$fecha_stamp."')";
    do_sql($query);
}

function nombre_mes($num_mes){
   if ($num_mes == 1){return "Enero";} 
   if ($num_mes == 2){return "Febrero";} 
   if ($num_mes == 3){return "Marzo";} 
   if ($num_mes == 4){return "Abril";} 
   if ($num_mes == 5){return "Mayo";} 
   if ($num_mes == 6){return "Junio";} 
   if ($num_mes == 7){return "Julio";} 
   if ($num_mes == 8){return "Agosto";} 
   if ($num_mes == 9){return "Septiembre";} 
   if ($num_mes == 10){return "Octubre";} 
   if ($num_mes == 11){return "Noviembre";} 
   if ($num_mes == 12){return "Diciembre";} 
}

function contains_str($palabra,$enunciado){
if (strpos(strtolower($enunciado),strtolower($palabra)) !== false) {
return true;
}
return false;
}

function sms($celular, $compania, $tipomsj, $inconformidad){
	$cel = $celular;
	if( $tipomsj == 1 ){
		$msj = "Tu inconformidad se registró con el folio $inconformidad. Ingresa a www.soyusuario.ift.org.mx para darle seguimiento. Si tienes dudas llama al 018002000120.";
	}if( $tipomsj == 2 ){
		$msj = "Tu inconformidad $inconformidad requiere datos adicionales. Ingresa a www.soyusuario.ift.org.mx para proporcionarlos. Si tienes dudas llama al 018002000120.";
	}if( $tipomsj == 3 ){
		$msj = "Tu inconformidad $inconformidad ha sido atendida. Ingresa a www.soyusuario.ift.org.mx para consultarla. Si tienes dudas llama al 018002000120.";
	}if( $tipomsj == 4 ){
		$msj = "Tu inconformidad $inconformidad ha sido atendida. Ingresa a www.soyusuario.ift.org.mx para consultarla. Si tienes dudas llama al 018002000120.";
	}if( $tipomsj == 5 ){
		$msj = "Dirigiste tu inconformidad $inconformidad a PROFECO, quien te contactará en breve, te sugerimos estar pendiente. Para cualquier duda llama al 018004688722.";
	}if( $tipomsj == 6 ){
		$msj = "Tu queja $inconformidad fue resuelta por PROFECO, ingresa a www.soyusuario.ift.org.mx para consultarla. Si tienes dudas llama al 018002000120.";
	}
	if( $compania == 1 ){
		$carr = "IUSACELL";
	}if( $compania == 2 ){
		$carr = "MOVISTAR";
	}if( $compania == 3 ){
		$carr = "NEXTEL";
	}if( $compania == 4 ){
		$carr = "TELCEL";
	}if( $compania == 5 ){
		$carr = "UNEFON";
	}if( $compania == 6 ){
		$carr = "VIRGIN MOBILE";
	}
	$data = array(
		'user' => 'gloria.delucio@ift.org.mx',
		'pass' => 'ufN9Vq85',
		'api_id' => 1439738393,
		'ani' => "$cel",
		'msj' => "$msj",
		'carr' => "$carr"
	);
	$lista = "user=gloria.delucio@ift.org.mx&pass=ufN9Vq85&api_id=1439738393&ani=$cel&msj=".urlencode($msj)."&carr=$carr";
	
	//$response = http_post_fields('https://ws.gtp.bz/api/http_bulk/http.php', $data);
	foreach($data as $key=>$value) { 
		$fields_string .= $key.'='.$value.'&'; 
	}
	rtrim($fields_string, '&');
	
	$url ='https://ws.gtp.bz/api/http_bulk/http.php?'.$lista;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://ws.gtp.bz/api/http_bulk/http.php?'.$lista);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	/*curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);*/
	$respuesta = curl_exec($ch);
	
	$logfile = "logMensajes.txt";
	$fileh = fopen($logfile,'a');
	$msg = date("m-d-Y h:i:s -->").("josn env: ".json_encode($data))."<-- \n";
	fwrite($fileh, $msg);
	$msg = date("m-d-Y h:i:s -->").("url: ".$url)."<-- \n";
	fwrite($fileh, $msg);
	$msg = date("m-d-Y h:i:s -->").("Respuesta: ".json_decode($respuesta, true))."<-- \n";
	fwrite($fileh, $msg);
	fclose($fileh);
	
}

function string_detectar_caracter($mystring){

    $findme   = array('Á', 'á', 'É', 'é', 'Í', 'í', 'Ó', 'ó','Ú','ú','Ü','ü','Ñ','ñ', '$', '%', '&', '/', '(', ')', '=', '?', '¿','!','{', ',', '}', '[', ']');
    $cadena_caracter = false;
    for ($i=0; $i<= count($findme); $i++){
        $pos = strpos($mystring, $findme[$i]);
        if ($pos === false) {
            $cadena_caracter = false;
        } else {
            $cadena_caracter = true;
            break;
        }
    }
    return  $cadena_caracter;
}


?>

