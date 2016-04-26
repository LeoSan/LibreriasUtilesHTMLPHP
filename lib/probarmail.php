<?php 
require_once('class.phpmailer.php');

$mail = new PHPMailer();
$mail->IsSMTP(); // 
$mail->SetLanguage("es", 'language/');
$mail->SMTPDebug  = 0;                    // Nivlel de debug SMTP
$mail->SMTPAuth   = true;                 // 
$mail->SMTPSecure = "ssl";                // 
$mail->Host       = EMAIL_SRV;            // 
$mail->Port       = EMAIL_PORT;           // 
$mail->Username   = EMAIL_USR;            // GMAIL username
$mail->Password   = EMAIL_PWD;            // GMAIL password       //

$mail->SetFrom('soyusuario.ift@ift.org.mx', 'Servicio portal Soy Usuario');

$mail->AddReplyTo("noresponder@ift.org.mx","No respoder");

$mail->Subject    = "Hola";

$mail->AltBody    = "para ver este mensaje, porfavor utilice un cliente de email compatible con HTML"; // optional, comment out and test

$body = "Holaaaaa";

$mail->MsgHTML("Holaaa");
  
$mail->AddAddress("razola86@hotmail.com");
//$mail->AddAttachment($pathfile);  
if(!$mail->Send()) {
} else {            
}

?>