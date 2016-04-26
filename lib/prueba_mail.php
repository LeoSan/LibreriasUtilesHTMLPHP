<?php
require_once('class.phpmailer.php');

$mail = new PHPMailer();
$mail->IsSMTP(); // 
$mail->SetLanguage("es", 'language/');
$mail->SMTPDebug  = 0;                    // Nivlel de debug SMTP
$mail->SMTPAuth   = false;                 // 
//$mail->SMTPSecure = "ssl";                // 
$mail->Host       = "172.27.1.5";            // 
$mail->Port       = 25;           // 
$mail->Username   = "soyusuario.ift@ift.org.mx";            // GMAIL username
$mail->Password   = "Soyusuario2015pd";            // GMAIL password       //

$mail->SetFrom('soyusuario.ift@ift.org.mx', 'Servicio portal Soy Usuario');

$mail->AddReplyTo("noresponder@ift.org.mx","No respoder");

$mail->Subject    = "Prueba mail";

$mail->AltBody    = "para ver este mensaje, porfavor utilice un cliente de email compatible con HTML"; // optional, comment out and test

$body = "Mensaje nuevo de prueba";

$mail->MsgHTML("Mensaje nuevo de prueba");
  
$mail->AddAddress("razola86@hotmail.com");
//$mail->AddAttachment($pathfile);  
if(!$mail->Send()) {
	echo("No Enviado: ".$mail->ErrorInfo);
} else { 
    echo("Enviado: ". $mail->Send());       
}


























