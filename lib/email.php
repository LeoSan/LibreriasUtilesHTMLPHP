<?php
require_once('class.phpmailer.php');

function send_mail_registro($addrs, $titulo, $msg)
{
    $mail = new PHPMailer();
    $mail->IsSMTP(); //
    $mail->SetLanguage("es", 'language/');
    $mail->SMTPDebug = 0;                    // Nivlel de debug SMTP
    $mail->SMTPAuth = false;                 //
//$mail->SMTPSecure = "ssl";                // 
    $mail->Host = EMAIL_SRV;            //
    $mail->Port = EMAIL_PORT;           //
    $mail->Username = EMAIL_USR;            // GMAIL username
    $mail->Password = EMAIL_PWD;            // GMAIL password       //

    $mail->SetFrom(EMAIL_USR, 'Servicio portal Soy Usuario');

    $mail->AddBCC("soyusuario.ift@ift.org.mx", "No respoder");

    $mail->Subject = $titulo;

    $mail->AltBody = "para ver este mensaje, porfavor utilice un cliente de email compatible con HTML"; // optional, comment out and test

    $body = $msg;

    $mail->MsgHTML($msg);

    $mail->AddAddress($addrs);
//$mail->AddAttachment($pathfile);  
    if (!$mail->Send()) {
        log_file("No Enviado_correo: " . $mail->ErrorInfo . " correo: $addrs ");
    } else {
        log_file("Enviado_correo: " . $addrs);
    }
}


function send_mail_registro_masivo($addrs, $titulo, $msg)
{
    $mail = new PHPMailer();
    $mail->IsSMTP(); //
    $mail->SetLanguage("es", 'language/');
    $mail->SMTPDebug = 0;                    // Nivlel de debug SMTP
    $mail->SMTPAuth = false;                 //
//$mail->SMTPSecure = "ssl";                //
    $mail->Host = EMAIL_SRV;            //
    $mail->Port = EMAIL_PORT;           //
    $mail->Username = EMAIL_USR;            // GMAIL username
    $mail->Password = EMAIL_PWD;            // GMAIL password       //

    $mail->SetFrom(EMAIL_USR, 'Servicio portal Soy Usuario');

    $mail->AddBCC("soyusuario.ift@ift.org.mx", "No respoder");
//    $mail->AddBCC("crissy_mx@hotmail.com", "No respoder");
//    $mail->AddBCC("david.penia@hotmail.com", "No respoder");
//    $mail->AddBCC("tnt_delucio007@hotmail.com", "No respoder");
//    $mail->AddBCC("patricia.delucio@outlook.com", "No respoder");

    $mail->Subject = $titulo;

    $mail->AltBody = "para ver este mensaje, porfavor utilice un cliente de email compatible con HTML"; // optional, comment out and test

    $body = $msg;

    $mail->MsgHTML($msg);

    $mail->AddAddress($addrs);
//$mail->AddAttachment($pathfile);
    if (!$mail->Send()) {
        log_file("No Enviado_correo: " . $mail->ErrorInfo . " correo: $addrs ");
    } else {
        log_file("Enviado_correo: " . $addrs);
    }
}


function send_mail_single($addrs, $titulo, $msg)
{
    $mail = new PHPMailer();
    $mail->IsSMTP(); //
    $mail->SetLanguage("es", 'language/');
    $mail->SMTPDebug = 0;                    // Nivlel de debug SMTP
    $mail->SMTPAuth = false;                 //
//$mail->SMTPSecure = "ssl";                // 
    $mail->Host = EMAIL_SRV;            //
    $mail->Port = EMAIL_PORT;           //
    $mail->Username = EMAIL_USR;            // GMAIL username
    $mail->Password = EMAIL_PWD;            // GMAIL password       //

    $mail->SetFrom(EMAIL_USR, 'Servicio portal Soy Usuario');

//$mail->AddReplyTo("soyusuario.ift@ift.org.mx","No respoder");

    $mail->Subject = $titulo;

    $mail->AltBody = "para ver este mensaje, porfavor utilice un cliente de email compatible con HTML"; // optional, comment out and test

    $body = $msg;

    $mail->MsgHTML(utf8_decode($msg));

    $mail->AddAddress($addrs);
    $mail->AddBCC("soyusuario.ift@ift.org.mx", "No responder");
//$mail->AddAttachment($pathfile);
    if (!$mail->Send()) {
        log_file("No Enviado_correo: " . $mail->ErrorInfo . " correo: $addrs ");
    } else {
        log_file("Enviado_correo: " . $addrs);
    }
}

function send_mail_attach($addrs, $titulo, $msg, $pathfile1, $pathfile2)
{
    $mail = new PHPMailer();
    $mail->IsSMTP(); //
    $mail->SetLanguage("es", 'language/');
    $mail->SMTPDebug = 0;                    // Nivlel de debug SMTP
    $mail->SMTPAuth = false;                 //
//$mail->SMTPSecure = "ssl";                // 
    $mail->Host = EMAIL_SRV;            //
    $mail->Port = EMAIL_PORT;           //
    $mail->Username = EMAIL_USR;            // GMAIL username
    $mail->Password = EMAIL_PWD;            // GMAIL password       //

    $mail->SetFrom(EMAIL_USR, 'Servicio portal Soy Usuario');

    $mail->AddReplyTo("noresponder@ift.org.mx", "No respoder");

    $mail->Subject = $titulo;

    $mail->AltBody = "para ver este mensaje, porfavor utilice un cliente de email compatible con HTML"; // optional, comment out and test

    $body = $msg;

    $mail->MsgHTML($msg);

    $mail->AddAddress($addrs);
    $mail->AddBCC("soyusuario.ift@ift.org.mx", "No respoder");
//$mail->AddBCC("correosift@gmail.com","No respoder");
    $mail->AddAttachment($pathfile1);
    $mail->AddAttachment($pathfile2);
    if (!$mail->Send()) {
        log_file("No Enviado_correoAttach: " . $mail->ErrorInfo . " correo: $addrs ");
    } else {
        log_file("Enviado_correoAttach: " . $addrs);
    }
}

function send_solicitud_documentos_extra($id_reclamacion)
{
    $id_datos_usuario = sql_getfield("SELECT id_datos_usuario FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_datos_usuario");
    $folio = sql_getfield("SELECT folio FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "folio");
    $id_usuario = sql_getfield("SELECT id_usuario FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_usuario");
    $email_contacto = sql_getfield("SELECT email FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "email");
    $compania = sql_getfield("select isnull(id_compania, 0) as id_compania from reclamaciones_datos_usuario where id_datos_usuario = " . $id_datos_usuario, "id_compania");
    if ($compania != 0) {
        $tel_mensajes = sql_getfield("select isnull(tel_mensajes, 'NA') as tel_mensajes from reclamaciones_datos_usuario where id_datos_usuario = " . $id_datos_usuario, "tel_mensajes");
        $sms = sms($tel_mensajes, $compania, 2, $folio);
    }
    $email_usuario = sql_getfield("SELECT email FROM usuarios WHERE id_usuario = " . $id_usuario, "email");
    $url_acceso = URL_BASE . "/expediente.php?id_reclamacion=" . $id_reclamacion;
    $htmlmsg = "<p>Estimado(a) usuario(a),</p>
		<p>Con la finalidad de dar una correcta atención a tu inconformidad con folio <b>" . $folio . "</b>, se solicita que proporciones algunos datos adicionales. Favor de ingresar a la página " . $url_acceso . " con tu clave de usuario y contraseña para solventar el requerimiento. </p>
		<p>Con fundamento en el artículo 17-A de la Ley Federal de Procedimiento Administrativo, te informamos que cuentas con un plazo de 30 días naturales para dar respuesta al requerimiento. En caso de no recibir contestación, tu inconformidad será desechada, dejando a salvo tus derechos para presentarla ante la autoridad que corresponda.</p>
		<p>Ante cualquier duda o aclaración, ponemos a tu disposición el correo electrónico atencion@ift.org.mx y el número gratuito 01800 2000 120.</p> 
		<p>Saludos cordiales,</p>
		<p>Atención Ciudadana, IFT.</p>
		<p>Este correo electrónico ha sido generado de manera automática, favor de no responder.</p>";
    send_mail_single($email_usuario, "Solicitud de datos adicionales, Soy usuario", utf8_decode($htmlmsg));
    send_mail_single($email_contacto, "Solicitud de datos adicionales, Soy usuario", utf8_decode($htmlmsg));
}

function send_reclamacionfinalizada($id_reclamacion)
{
    $folio = sql_getfield("SELECT folio FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "folio");
    $id_datos_usuario = sql_getfield("SELECT id_datos_usuario FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_datos_usuario");
    $id_usuario = sql_getfield("SELECT id_usuario FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_usuario");
    $email_contacto = sql_getfield("SELECT email FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "email");
    $email_usuario = sql_getfield("SELECT email FROM usuarios WHERE id_usuario = " . $id_usuario, "email");
    $compania = sql_getfield("select isnull(id_compania, 0) as id_compania from reclamaciones_datos_usuario where id_datos_usuario = " . $id_datos_usuario, "id_compania");
    if ($compania != 0) {
        $tel_mensajes = sql_getfield("select isnull(tel_mensajes, 'NA') as tel_mensajes from reclamaciones_datos_usuario where id_datos_usuario = " . $id_datos_usuario, "tel_mensajes");
        $sms = sms($tel_mensajes, $compania, 3, $folio);
    }
    $url_acceso = URL_BASE . "/expediente.php?id_reclamacion=" . $id_reclamacion;
    $htmlmsg = "<p>Estimado(a) usuario(a),</p>
<p>Tu inconformidad con folio <b>" . $folio . "</b> ha sido resuelta por tu prestador de servicios. Para consultarla, puedes ingresar al sistema a través de la dirección electrónica http://www.soyusuario.ift.org.mx con tu clave de usuario y contraseña, sección &#34;Histórico&#34;.</p´>
<p>Ante cualquier duda o aclaración, ponemos a tu disposición el correo electrónico atencion@ift.org.mx y el número gratuito 01800 2000 120.</p> 
<p>Saludos cordiales,</p>
<p>Atención Ciudadana, IFT.</p>
<p>Este correo electrónico ha sido generado de manera automática, favor de no responder.</p>";
    send_mail_single($email_usuario, utf8_decode("Respuesta a tu inconformidad, Soy usuario"), utf8_decode($htmlmsg));
    send_mail_single($email_contacto, utf8_decode("Respuesta a tu inconformidad, Soy usuario"), utf8_decode($htmlmsg));
}

function send_acuseconformidad($id_reclamacion)
{
    $folio = sql_getfield("SELECT folio FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "folio");
    $id_datos_usuario = sql_getfield("SELECT id_datos_usuario FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_datos_usuario");

    $nombre_titular = sql_getfield("SELECT nombre FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "nombre");
    $paterno_titular = sql_getfield("SELECT apellido_paterno FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "apellido_paterno");
    $materno_titular = sql_getfield("SELECT apellido_materno FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "apellido_materno");
    $titular = $nombre_titular . " " . $paterno_titular . " " . $materno_titular;

    $id_usuario = sql_getfield("SELECT id_usuario FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_usuario");
    $id_proveedor = sql_getfield("SELECT id_proveedor FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_proveedor");
    $razon_social = sql_getfield("SELECT nombre_fiscal FROM cat_proveedores WHERE id_proveedor = " . $id_proveedor, "nombre_fiscal");
    $respuesta_prv = sql_getfield("SELECT mensaje FROM reclamaciones_mensajes_proveedor WHERE id_reclamacion=" . $id_reclamacion . " AND id_estatus = 7", "mensaje");
    $email_contacto = sql_getfield("SELECT email FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "email");
    $email_usuario = sql_getfield("SELECT email FROM usuarios WHERE id_usuario = " . $id_usuario, "email");
    $url_acceso = URL_BASE . "/expediente.php?id_reclamacion=" . $id_reclamacion;

    $htmlmsg = "<p>Estimado(a) usuario(a),</p>
<p>El titular de la inconformidad con folio <b>" . $folio . "</b> ha aceptado satisfactoriamente la resolución de la misma, se anexa en este correo la carta de aceptación en formato PDF.</p´>
<p>Ante cualquier duda o aclaración, ponemos a tu disposición el correo electrónico atencion@ift.org.mx y el número gratuito 01800 2000 120.</p> 
<p>Saludos cordiales,</p>
<p>Atención Ciudadana, IFT.</p>
<p>Este correo electrónico ha sido generado de manera automática, favor de no responder.</p>";

    require_once('lib/html2pdf.class.php');

    try {
        $pdf_html = file_get_contents("plantillas/formato_acuseconformidad.html");
        $pdf_html = str_replace("i_dia", date(d), $pdf_html);
        $pdf_html = str_replace("i_mes", nombre_mes(date(m)), $pdf_html);
        $pdf_html = str_replace("i_ano", date(Y), $pdf_html);
        $pdf_html = str_replace("i_folio", $folio, $pdf_html);
        $pdf_html = str_replace("i_respuesta_proveedor", $respuesta_prv, $pdf_html);
        $pdf_html = str_replace("i_nombre_titular", $titular, $pdf_html);
        $pdf_html = str_replace("i_razon_social", $razon_social, $pdf_html);

        $html2pdf = new HTML2PDF('P', 'Letter', 'es', 'false', 'UTF-8');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($pdf_html);
        $secuname = randomstr(32);
        $pdf_archivo = 'acuses_pdf/carta_conformidad_' . $folio . ".pdf";
        $pdf_expediente = 'documentos_reclamaciones/' . $secuname;
        $nombre_pdf = 'carta_conformidad_' . $folio . ".pdf";
        $html2pdf->Output($pdf_archivo, 'F');
        copy($pdf_archivo, $pdf_expediente);
        $size_bytes = filesize($pdf_expediente);
        $query = "INSERT INTO reclamaciones_archivos (nombre,nombre_original,size_bytes,id_reclamacion) VALUES (";
        $query = $query . "'" . $secuname . "','" . $nombre_pdf . "'," . $size_bytes . "," . $id_reclamacion . ")";
        do_sql($query);
    } catch (HTML2PDF_exception $e) {
        echo($e);
        return;
    }

    $result = sql_connect("SELECT email FROM proveedor_usuarios WHERE id_proveedor = " . $id_proveedor);
    for ($n = 1; $n <= sql_numrows($result); $n++) {
        $target_email = sql_result($result, $n, "email");
//send_mail_attach($target_email,utf8_decode("Acuse de conformidad"),utf8_decode($htmlmsg),$pdf_archivo);
    }
    send_mail_attach($email_contacto, utf8_decode("Acuse de conformidad"), utf8_decode($htmlmsg), $pdf_archivo);
    send_mail_attach($email_usuario, utf8_decode("Acuse de conformidad"), utf8_decode($htmlmsg), $pdf_archivo);
}

function send_reclamacionaprofeco($id_reclamacion)
{
    $id_datos_usuario = sql_getfield("SELECT id_datos_usuario FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_datos_usuario");
    $id_usuario = sql_getfield("SELECT id_usuario FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_usuario");
    $email_contacto = sql_getfield("SELECT email FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "email");
    $id_proveedor = sql_getfield("SELECT id_proveedor FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_proveedor");

    /*$email_usuario    = sql_getfield("SELECT email FROM usuarios WHERE id_usuario = ".$id_usuario,"email"); */
    $url_acceso = URL_BASE . "/expediente.php?id_reclamacion=" . $id_reclamacion;


    $folio_inconformidad = sql_getfield("SELECT folio FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "folio");
    $proveedor = sql_getfield("SELECT titulo FROM cat_proveedores WHERE id_proveedor = " . $id_proveedor, "titulo");
    $fecha_inconformidad = sql_getfieldf("SELECT fecha_registro FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "fecha_registro");
    $id_tipo_servicio = sql_getfield("SELECT id_servicio FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_servicio");
    $id_tipo_inconformidad = sql_getfield("SELECT id_tipo_reclamacion FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_tipo_reclamacion");
    $id_tipo_motivo = sql_getfield("SELECT id_subtipo_reclamacion FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_subtipo_reclamacion");
    $compania = sql_getfield("select isnull(id_compania, 0) as id_compania from reclamaciones_datos_usuario where id_datos_usuario = " . $id_datos_usuario, "id_compania");
    if ($compania != 0) {
        $tel_mensajes = sql_getfield("select isnull(tel_mensajes, 'NA') as tel_mensajes from reclamaciones_datos_usuario where id_datos_usuario = " . $id_datos_usuario, "tel_mensajes");
        $sms = sms($tel_mensajes, $compania, 5, $folio_inconformidad);
    }
    $nombre_usuario = sql_getfield("SELECT nombre FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "nombre");
    $apellido_paterno_usuario = sql_getfield("SELECT apellido_paterno FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "apellido_paterno");
    $apellido_materno_usuairo = sql_getfield("SELECT apellido_materno FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "apellido_materno");
    $fecha_nacimiento_usuario = sql_getfieldf("SELECT fecha_nacimiento FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "fecha_nacimiento");
    $calle_usuario = sql_getfield("SELECT calle FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "calle");
    $num_int_usuario = sql_getfield("SELECT num_int FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "num_int");
    $num_ext_usuario = sql_getfield("SELECT num_ext FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "num_ext");
    $id_estado_usuario = sql_getfield("SELECT id_entidadfederativa FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "id_entidadfederativa");
    $id_delegacion_municipio_usuario = sql_getfield("SELECT id_municipio FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "id_municipio");
    $id_colonia_usuario = sql_getfield("SELECT id_colonia FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "id_colonia");
    $cp_usuario = sql_getfield("SELECT cp FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "cp");
    $usuario_login = sql_getfield("SELECT login FROM usuarios WHERE id_usuario = " . $id_usuario, "login");
    $usuario_password = sql_getfield("SELECT password FROM usuarios WHERE id_usuario = " . $id_usuario, "password");
    $usuario_email = sql_getfield("SELECT email FROM usuarios WHERE id_usuario = " . $id_usuario, "email");
    $usuario_tel_movil = sql_getfield("SELECT tel_celular FROM usuarios WHERE id_usuario = " . $id_usuario, "tel_celular");
    $usuario_tel_local = sql_getfield("SELECT tel_fijo FROM usuarios WHERE id_usuario = " . $id_usuario, "tel_fijo");
    $usuario_tel_adicional = sql_getfield("SELECT tel_contacto FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "tel_contacto");
    $drescripcion_falla = sql_getfield("SELECT descripcion FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "descripcion");
    $id_forma_pago = sql_getfield("SELECT id_forma_pago FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_forma_pago");
    $id_tipo_plan = sql_getfield("SELECT id_tipoplan FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_tipoplan");
    $numero_contrato = sql_getfield("SELECT numero_telefonoocontrato FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "numero_telefonoocontrato");
    $numero_telefono_falla = sql_getfield("SELECT numero_telefono_falla FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "numero_telefono_falla");
    $marca_telefono = sql_getfield("SELECT marcatelefono FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "marcatelefono");
    $modelo_telefono = sql_getfield("SELECT modelotelefono FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "modelotelefono");
    $numero_imei = sql_getfield("SELECT numero_imei FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "numero_imei");
    $nombre_archivo_1 = "";
    $nombre_archivo_2 = "";
    $nombre_archivo_3 = "";
    $nombre_archivo_4 = "";
    $nombre_archivo_5 = "";
    $nombre_archivo_6 = "";
    $nombre_archivo_7 = "";
    $nombre_archivo_8 = "";
    $nombre_archivo_9 = "";
    $nombre_archivo_10 = "";
    $archivos = sql_connect("SELECT * FROM reclamaciones_archivos WHERE id_reclamacion = $id_reclamacion");
    $rows = sql_numrows($archivos);
    $nombre_archivo = array();
    for ($i = 1; $i <= $rows; $i++) {
        array_push($nombre_archivo, sql_result($archivos, $i, "nombre"));
        //"$nombre_archivo_$i" = sql_result($archivos,$i,"nombre");
    }
    for ($i = $rows + 1; $i <= 10; $i++) {
        array_push($nombre_archivo, "");
    }
    $nombre_archivo_1 = $nombre_archivo[0];
    $nombre_archivo_2 = $nombre_archivo[1];
    $nombre_archivo_3 = $nombre_archivo[2];
    $nombre_archivo_4 = $nombre_archivo[3];
    $nombre_archivo_5 = $nombre_archivo[4];
    $nombre_archivo_6 = $nombre_archivo[5];
    $nombre_archivo_7 = $nombre_archivo[6];
    $nombre_archivo_8 = $nombre_archivo[7];
    $nombre_archivo_9 = $nombre_archivo[8];
    $nombre_archivo_10 = $nombre_archivo[9];

    /*log_file("datos:$folio_inconformidad--
    $fecha_inconformidad--
    $id_tipo_servicio--
    $id_tipo_inconformidad--
    $id_tipo_motivo--
    $nombre_usuario--
    $apellido_paterno_usuario--
    $apellido_materno_usuairo--
    $calle_usuario--
    $num_int_usuario--
    $num_ext_usuario--
    $id_estado_usuario--
    $id_delegacion_municipio_usuario--
    $id_colonia_usuario--
    $cp_usuario--
    $usuario_login--
    $usuario_password--
    $usuario_email--
    $usuario_tel_movil--
    $usuario_tel_local--
    $usuario_tel_adicional--
    $drescripcion_falla--
    $id_forma_pago--
    $id_tipo_plan--
    $numero_contrato--
    $numero_telefono_falla--
    $marca_telefono--
    $modelo_telefono--
    $numero_imei--
    $nombre_archivo_1--
    $nombre_archivo_2--
    $nombre_archivo_3--
    $nombre_archivo_4--
    $nombre_archivo_5--
    $nombre_archivo_6--
    $nombre_archivo_7--
    $nombre_archivo_8--
    $nombre_archivo_9--
    $nombre_archivo_10");*/
//'proveedor' => "$proveedor",
    $data = array(
        'folio_inconformidad' => "$folio_inconformidad",
        'id_proveedor' => $id_proveedor,
        'fecha_inconformidad' => "" . fecha_a_profeco($fecha_inconformidad) . "",
        'id_tipo_servicio' => $id_tipo_servicio,
        'id_tipo_inconformidad' => $id_tipo_inconformidad,
        'id_tipo_motivo' => "$id_tipo_motivo",
        'nombre_usuario' => "" . utf8_encode($nombre_usuario) . "",
        'apellido_paterno_usuario' => "" . utf8_encode($apellido_paterno_usuario) . "",
        'apellido_materno_usuario' => "" . utf8_encode($apellido_materno_usuairo) . "",
        'calle_usuario' => "" . utf8_encode($calle_usuario) . "",
        'num_int_usuario' => "$num_int_usuario",
        'num_ext_usuario' => "$num_ext_usuario",
        'id_estado_usuario' => $id_estado_usuario,
        'id_delegacion_municipio_usuario' => $id_delegacion_municipio_usuario,
        'id_colonia_usuario' => $id_colonia_usuario,
        'cp_usuario' => "$cp_usuario",
        'usuario_login' => "$usuario_login",
        'usuario_password' => "$usuario_password",
        'usuario_email' => "$usuario_email",
        'usuario_tel_movil' => "$usuario_tel_movil",
        'usuario_tel_local' => "$usuario_tel_local",
        'usuario_tel_adicional' => "$usuario_tel_adicional",
        'fecha_nacimiento' => "" . fecha_a_profeco($fecha_nacimiento_usuario) . "",
        'descripcion_falla' => "" . utf8_encode($drescripcion_falla) . "",
        'id_forma_pago' => "$id_forma_pago",
        'id_tipo_plan' => "$id_tipo_plan",
        'numero_contrato' => "$numero_contrato",
        'numero_telefono_falla' => "$numero_telefono_falla",
        'marca_telefono' => "$marca_telefono",
        'modelo_telefono' => "$modelo_telefono",
        'numero_imei' => "$numero_imei",
        'nombre_archivo_1' => "$nombre_archivo_1",
        'nombre_archivo_2' => "$nombre_archivo_2",
        'nombre_archivo_3' => "$nombre_archivo_3",
        'nombre_archivo_4' => "$nombre_archivo_4",
        'nombre_archivo_5' => "$nombre_archivo_5",
        'nombre_archivo_6' => "$nombre_archivo_6",
        'nombre_archivo_7' => "$nombre_archivo_7",
        'nombre_archivo_8' => "$nombre_archivo_8",
        'nombre_archivo_9' => "$nombre_archivo_9",
        'nombre_archivo_10' => "$nombre_archivo_10"
    );
    log_file("josn env:" . json_encode($data));
    /*$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://200.53.148.113/ws_concilianet/quejas.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $respuesta = curl_exec($ch);*/
    $client = new nusoap_client('http://200.53.148.113/ws_concilianet/quejas.php?wsdl', 'wsdl');
//$client = new nusoap_client('http://192.168.183.113/ws_concilianet/quejas.php?wsdl','wsdl');
    $err = $client->getError();
    if ($err) {
        log_file("error:" . $err);
    }
    $respuesta = $client->call('entregaQueja', $data);
    if ($client->fault) {
        log_file('Fallo');
        log_file(print_r($respuesta));
    } else {    // Chequea errores
        $err = $client->getError();
        if ($err) {        // Muestra el error
            log_file('Error' . $err);
        } else {        // Muestra el resultado
            log_file('Resultado: ' . $respuesta);
        }
    }
//log_file("json res:".json_decode($respuesta));

//$cid = ftp_connect("200.53.148.113");
    $cid = ftp_connect("192.168.183.113");
    $resultado = ftp_login($cid, "ftp_ift", "3!3_*2=rTsdIF1_");
    if ((!$cid) || (!$resultado)) {
        log_file("Fallo en la conexión");
    } else {
        log_file("Conectado.");
    }
    ftp_pasv($cid, true);
//ftp_pasv ($cid, true) ;
    if ($nombre_archivo_1 != "") {
        if (ftp_put($cid, $nombre_archivo_1, "C:\\wamp\\www\\documentos_reclamaciones\\$nombre_archivo_1", FTP_BINARY)) ;
        log_file("Se ha subido correctamente el archivo 1");
    }
    if ($nombre_archivo_2 != "") {
        if (ftp_put($cid, $nombre_archivo_2, "C:\\wamp\\www\\documentos_reclamaciones\\$nombre_archivo_2", FTP_BINARY)) ;
        log_file("Se ha subido correctamente el archivo 2");
    }
    if ($nombre_archivo_3 != "") {
        if (ftp_put($cid, $nombre_archivo_3, "C:\\wamp\\www\\documentos_reclamaciones\\$nombre_archivo_3", FTP_BINARY)) ;
        log_file("Se ha subido correctamente el archivo 3");
    }
    if ($nombre_archivo_4 != "") {
        if (ftp_put($cid, $nombre_archivo_4, "C:\\wamp\\www\\documentos_reclamaciones\\$nombre_archivo_4", FTP_BINARY)) ;
        log_file("Se ha subido correctamente el archivo 4");
    }
    if ($nombre_archivo_5 != "") {
        if (ftp_put($cid, $nombre_archivo_5, "C:\\wamp\\www\\documentos_reclamaciones\\$nombre_archivo_5", FTP_BINARY)) ;
        log_file("Se ha subido correctamente el archivo 5");
    }
    if ($nombre_archivo_6 != "") {
        if (ftp_put($cid, $nombre_archivo_6, "C:\\wamp\\www\\documentos_reclamaciones\\$nombre_archivo_6", FTP_BINARY)) ;
        log_file("Se ha subido correctamente el archivo 6");
    }
    if ($nombre_archivo_7 != "") {
        if (ftp_put($cid, $nombre_archivo_7, "C:\\wamp\\www\\documentos_reclamaciones\\$nombre_archivo_7", FTP_BINARY)) ;
        log_file("Se ha subido correctamente el archivo 7");
    }
    if ($nombre_archivo_8 != "") {
        if (ftp_put($cid, $nombre_archivo_8, "C:\\wamp\\www\\documentos_reclamaciones\\$nombre_archivo_8", FTP_BINARY)) ;
        log_file("Se ha subido correctamente el archivo 8");
    }
    if ($nombre_archivo_9 != "") {
        if (ftp_put($cid, $nombre_archivo_9, "C:\\wamp\\www\\documentos_reclamaciones\\$nombre_archivo_9", FTP_BINARY)) ;
        log_file("Se ha subido correctamente el archivo 9");
    }
    if ($nombre_archivo_10 != "") {
        if (ftp_put($cid, $nombre_archivo_10, "C:\\wamp\\www\\documentos_reclamaciones\\$nombre_archivo_10", FTP_BINARY)) ;
        log_file("Se ha subido correctamente el archivo 10");
    }
    ftp_quit($cid);
    $htmlmsg = "<p>Estimado(a) usuario(a),</p>
<p>Tu inconformidad con folio <b>" . $folio . "</b> ha sido canalizada a la Procuraduría Federal del Consumidor (PROFECO). Para consultarla, puedes ingresar al sistema a través de la dirección electrónica <dirección> con tu clave de usuario y contraseña en la sección &#34;Histórico&#34;.</p´>
<p>Ante cualquier duda o aclaración, ponemos a tu disposición el correo electrónico atencion@ift.org.mx y el número gratuito 01800 2000 120.</p> 
<p>Saludos cordiales,</p>
<p>Atención Ciudadana, IFT.</p>
<p>Este correo electrónico ha sido generado de manera automática, favor de no responder.</p>";
    send_mail_single($usuario_email, utf8_decode("Inconformidad en PROFECO, Soy usuario"), utf8_decode($htmlmsg));
    send_mail_single($email_contacto, utf8_decode("Inconformidad en PROFECO, Soy usuario"), utf8_decode($htmlmsg));
}

///////////////////////////////////////
function log_file($msg)
{
    $logfile = "logs.txt";
    $fileh = fopen($logfile, 'a');
    $msg = date("m-d-Y h:i:s -->") . ($msg) . "<-- \n";
    fwrite($fileh, $msg);
    fclose($fileh);
}

/////////////////////////////////////////////////
function send_reclamacionconciliada($id_reclamacion)
{
    $folio = sql_getfield("SELECT folio FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "folio");
    $id_datos_usuario = sql_getfield("SELECT id_datos_usuario FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_datos_usuario");
    $id_usuario = sql_getfield("SELECT id_usuario FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_usuario");
    $email_contacto = sql_getfield("SELECT email FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "email");
    $email_usuario = sql_getfield("SELECT email FROM usuarios WHERE id_usuario = " . $id_usuario, "email");
    $compania = sql_getfield("select isnull(id_compania, 0) as id_compania from reclamaciones_datos_usuario where id_datos_usuario = " . $id_datos_usuario, "id_compania");
    if ($compania != 0) {
        $tel_mensajes = sql_getfield("select isnull(tel_mensajes, 'NA') as tel_mensajes from reclamaciones_datos_usuario where id_datos_usuario = " . $id_datos_usuario, "tel_mensajes");
        $sms = sms($tel_mensajes, $compania, 6, $folio);
    }
    $url_acceso = URL_BASE . "/expediente.php?id_reclamacion=" . $id_reclamacion;
    $htmlmsg = "<p>Estimado(a) usuario(a),</p>
<p>Tu inconformidad con folio <b>" . $folio . "</b> ha sido antendida por la Procuraduría Federal del Consumidor (PROFECO). Para consultarla, puedes ingresar al sistema a través de la dirección electrónica <dirección> con tu clave de usuario y contraseña, sección &#34;Histórico&#34;.</p´>
<p>Ante cualquier duda o aclaración, ponemos a tu disposición el correo electrónico atencion@ift.org.mx y el número gratuito 01800 2000 120.</p> 
<p>Saludos cordiales,</p>
<p>Atención Ciudadana, IFT.</p>
<p>Este correo electrónico ha sido generado de manera automática, favor de no responder.</p>";
    send_mail_single($email_usuario, utf8_decode("Atención PROFECO, Soy usuario"), utf8_decode($htmlmsg));
    send_mail_single($email_contacto, utf8_decode("Atención PROFECO, Soy usuario"), utf8_decode($htmlmsg));
}

///////////////////////////////////////////////////////
function send_reclamacionaprofecoWError($id_reclamacion)
{
    $id_datos_usuario = sql_getfield("SELECT id_datos_usuario FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_datos_usuario");
    $id_usuario = sql_getfield("SELECT id_usuario FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_usuario");
    $email_contacto = sql_getfield("SELECT email FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "email");
    $id_proveedor = sql_getfield("SELECT id_proveedor FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_proveedor");

    /*$email_usuario    = sql_getfield("SELECT email FROM usuarios WHERE id_usuario = ".$id_usuario,"email"); */
    $url_acceso = URL_BASE . "/expediente.php?id_reclamacion=" . $id_reclamacion;


    $folio_inconformidad = sql_getfield("SELECT folio FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "folio");
    $proveedor = sql_getfield("SELECT titulo FROM cat_proveedores WHERE id_proveedor = " . $id_proveedor, "titulo");
    $fecha_inconformidad = sql_getfieldf("SELECT fecha_registro FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "fecha_registro");
    $id_tipo_servicio = sql_getfield("SELECT id_servicio FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_servicio");
    $id_tipo_inconformidad = sql_getfield("SELECT id_tipo_reclamacion FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_tipo_reclamacion");
    $id_tipo_motivo = sql_getfield("SELECT id_subtipo_reclamacion FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_subtipo_reclamacion");
    $compania = sql_getfield("select isnull(id_compania, 0) as id_compania from reclamaciones_datos_usuario where id_datos_usuario = " . $id_datos_usuario, "id_compania");
    if ($compania != 0) {
        $tel_mensajes = sql_getfield("select isnull(tel_mensajes, 'NA') as tel_mensajes from reclamaciones_datos_usuario where id_datos_usuario = " . $id_datos_usuario, "tel_mensajes");
        $sms = sms($tel_mensajes, $compania, 5, $folio_inconformidad);
    }
    $nombre_usuario = sql_getfield("SELECT nombre FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "nombre");
    $apellido_paterno_usuario = sql_getfield("SELECT apellido_paterno FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "apellido_paterno");
    $apellido_materno_usuairo = sql_getfield("SELECT apellido_materno FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "apellido_materno");
    $fecha_nacimiento_usuario = sql_getfieldf("SELECT fecha_nacimiento FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "fecha_nacimiento");
    $calle_usuario = sql_getfield("SELECT calle FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "calle");
    $num_int_usuario = sql_getfield("SELECT num_int FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "num_int");
    $num_ext_usuario = sql_getfield("SELECT num_ext FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "num_ext");
    $id_estado_usuario = sql_getfield("SELECT id_entidadfederativa FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "id_entidadfederativa");
    $id_delegacion_municipio_usuario = sql_getfield("SELECT id_municipio FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "id_municipio");
    $id_colonia_usuario = sql_getfield("SELECT id_colonia FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "id_colonia");
    $cp_usuario = sql_getfield("SELECT cp FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "cp");
    $usuario_login = sql_getfield("SELECT login FROM usuarios WHERE id_usuario = " . $id_usuario, "login");
    $usuario_password = sql_getfield("SELECT password FROM usuarios WHERE id_usuario = " . $id_usuario, "password");
    $usuario_email = sql_getfield("SELECT email FROM usuarios WHERE id_usuario = " . $id_usuario, "email");
    $usuario_tel_movil = sql_getfield("SELECT tel_celular FROM usuarios WHERE id_usuario = " . $id_usuario, "tel_celular");
    $usuario_tel_local = sql_getfield("SELECT tel_fijo FROM usuarios WHERE id_usuario = " . $id_usuario, "tel_fijo");
    $usuario_tel_adicional = sql_getfield("SELECT tel_contacto FROM reclamaciones_datos_usuario WHERE id_datos_usuario = " . $id_datos_usuario, "tel_contacto");
    $drescripcion_falla = sql_getfield("SELECT descripcion FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "descripcion");
    $id_forma_pago = sql_getfield("SELECT id_forma_pago FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_forma_pago");
    $id_tipo_plan = sql_getfield("SELECT id_tipoplan FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "id_tipoplan");
    $numero_contrato = sql_getfield("SELECT numero_telefonoocontrato FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "numero_telefonoocontrato");
    $numero_telefono_falla = sql_getfield("SELECT numero_telefono_falla FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "numero_telefono_falla");
    $marca_telefono = sql_getfield("SELECT marcatelefono FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "marcatelefono");
    $modelo_telefono = sql_getfield("SELECT modelotelefono FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "modelotelefono");
    $numero_imei = sql_getfield("SELECT numero_imei FROM reclamaciones WHERE id_reclamacion = " . $id_reclamacion, "numero_imei");
    $nombre_archivo_1 = "";
    $nombre_archivo_2 = "";
    $nombre_archivo_3 = "";
    $nombre_archivo_4 = "";
    $nombre_archivo_5 = "";
    $nombre_archivo_6 = "";
    $nombre_archivo_7 = "";
    $nombre_archivo_8 = "";
    $nombre_archivo_9 = "";
    $nombre_archivo_10 = "";
    $archivos = sql_connect("SELECT * FROM reclamaciones_archivos WHERE id_reclamacion = $id_reclamacion");
    $rows = sql_numrows($archivos);
    $nombre_archivo = array();
    for ($i = 1; $i <= $rows; $i++) {
        array_push($nombre_archivo, sql_result($archivos, $i, "nombre"));
        //"$nombre_archivo_$i" = sql_result($archivos,$i,"nombre");
    }
    for ($i = $rows + 1; $i <= 10; $i++) {
        array_push($nombre_archivo, "");
    }
    $nombre_archivo_1 = $nombre_archivo[0];
    $nombre_archivo_2 = $nombre_archivo[1];
    $nombre_archivo_3 = $nombre_archivo[2];
    $nombre_archivo_4 = $nombre_archivo[3];
    $nombre_archivo_5 = $nombre_archivo[4];
    $nombre_archivo_6 = $nombre_archivo[5];
    $nombre_archivo_7 = $nombre_archivo[6];
    $nombre_archivo_8 = $nombre_archivo[7];
    $nombre_archivo_9 = $nombre_archivo[8];
    $nombre_archivo_10 = $nombre_archivo[9];

    /*log_file("datos:$folio_inconformidad--
    $fecha_inconformidad--
    $id_tipo_servicio--
    $id_tipo_inconformidad--
    $id_tipo_motivo--
    $nombre_usuario--
    $apellido_paterno_usuario--
    $apellido_materno_usuairo--
    $calle_usuario--
    $num_int_usuario--
    $num_ext_usuario--
    $id_estado_usuario--
    $id_delegacion_municipio_usuario--
    $id_colonia_usuario--
    $cp_usuario--
    $usuario_login--
    $usuario_password--
    $usuario_email--
    $usuario_tel_movil--
    $usuario_tel_local--
    $usuario_tel_adicional--
    $drescripcion_falla--
    $id_forma_pago--
    $id_tipo_plan--
    $numero_contrato--
    $numero_telefono_falla--
    $marca_telefono--
    $modelo_telefono--
    $numero_imei--
    $nombre_archivo_1--
    $nombre_archivo_2--
    $nombre_archivo_3--
    $nombre_archivo_4--
    $nombre_archivo_5--
    $nombre_archivo_6--
    $nombre_archivo_7--
    $nombre_archivo_8--
    $nombre_archivo_9--
    $nombre_archivo_10");*/
//'proveedor' => "$proveedor",
    $data = array(
        'folio_inconformidad' => "$folio_inconformidad",
        'id_proveedor' => $id_proveedor,
        'fecha_inconformidad' => "" . fecha_a_profeco($fecha_inconformidad) . "",
        'id_tipo_servicio' => $id_tipo_servicio,
        'id_tipo_inconformidad' => $id_tipo_inconformidad,
        'id_tipo_motivo' => "$id_tipo_motivo",
        'nombre_usuario' => "" . utf8_encode($nombre_usuario) . "",
        'apellido_paterno_usuario' => "" . utf8_encode($apellido_paterno_usuario) . "",
        'apellido_materno_usuario' => "" . utf8_encode($apellido_materno_usuairo) . "",
        'calle_usuario' => "" . utf8_encode($calle_usuario) . "",
        'num_int_usuario' => "$num_int_usuario",
        'num_ext_usuario' => "$num_ext_usuario",
        'id_estado_usuario' => $id_estado_usuario,
        'id_delegacion_municipio_usuario' => $id_delegacion_municipio_usuario,
        'id_colonia_usuario' => $id_colonia_usuario,
        'cp_usuario' => "$cp_usuario",
        'usuario_login' => "$usuario_login",
        'usuario_password' => "$usuario_password",
        'usuario_email' => "$usuario_email",
        'usuario_tel_movil' => "$usuario_tel_movil",
        'usuario_tel_local' => "$usuario_tel_local",
        'usuario_tel_adicional' => "$usuario_tel_adicional",
        'fecha_nacimiento' => "" . fecha_a_profeco($fecha_nacimiento_usuario) . "",
        'descripcion_falla' => "" . utf8_encode($drescripcion_falla) . "",
        'id_forma_pago' => "$id_forma_pago",
        'id_tipo_plan' => "$id_tipo_plan",
        'numero_contrato' => "$numero_contrato",
        'numero_telefono_falla' => "$numero_telefono_falla",
        'marca_telefono' => "$marca_telefono",
        'modelo_telefono' => "$modelo_telefono",
        'numero_imei' => "$numero_imei",
        'nombre_archivo_1' => "$nombre_archivo_1",
        'nombre_archivo_2' => "$nombre_archivo_2",
        'nombre_archivo_3' => "$nombre_archivo_3",
        'nombre_archivo_4' => "$nombre_archivo_4",
        'nombre_archivo_5' => "$nombre_archivo_5",
        'nombre_archivo_6' => "$nombre_archivo_6",
        'nombre_archivo_7' => "$nombre_archivo_7",
        'nombre_archivo_8' => "$nombre_archivo_8",
        'nombre_archivo_9' => "$nombre_archivo_9",
        'nombre_archivo_10' => "$nombre_archivo_10"
    );

    log_file("josn env:" . json_encode($data));
    /*$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://200.53.148.113/ws_concilianet/quejas.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $respuesta = curl_exec($ch);*/
    $client = new nusoap_client('http://200.53.148.113/ws_concilianet/quejas.php?wsdl', 'wsdl');
//$client = new nusoap_client('http://192.168.183.113/ws_concilianet/quejas.php?wsdl','wsdl');
    $err = $client->getError();
    if ($err) {
        log_file("error:" . $err);
    }

    $respuesta = $client->call('entregaQueja', $data);
    if ($client->fault) {
        log_file('Fallo');
        log_file(print_r($respuesta));
    } else {    // Chequea errores
        $err = $client->getError();
        if ($err) {        // Muestra el error
            log_file('Error' . $err);
        } else {        // Muestra el resultado
            log_file('Resultado: ' . $respuesta);
        }
    }
//log_file("json res:".json_decode($respuesta));

//$cid = ftp_connect("200.53.148.113");
    $cid = ftp_connect("192.168.183.113");
    $resultado = ftp_login($cid, "ftp_ift", "3!3_*2=rTsdIF1_");
    if ((!$cid) || (!$resultado)) {
        log_file("Fallo en la conexión");
    } else {
        log_file("Conectado.");
    }
    ftp_pasv($cid, true);
//ftp_pasv ($cid, true) ;
    if ($nombre_archivo_1 != "") {
        if (ftp_put($cid, $nombre_archivo_1, "C:\\wamp\\www\\documentos_reclamaciones\\$nombre_archivo_1", FTP_BINARY)) ;
        log_file("Se ha subido correctamente el archivo 1");
    }
    if ($nombre_archivo_2 != "") {
        if (ftp_put($cid, $nombre_archivo_2, "C:\\wamp\\www\\documentos_reclamaciones\\$nombre_archivo_2", FTP_BINARY)) ;
        log_file("Se ha subido correctamente el archivo 2");
    }
    if ($nombre_archivo_3 != "") {
        if (ftp_put($cid, $nombre_archivo_3, "C:\\wamp\\www\\documentos_reclamaciones\\$nombre_archivo_3", FTP_BINARY)) ;
        log_file("Se ha subido correctamente el archivo 3");
    }
    if ($nombre_archivo_4 != "") {
        if (ftp_put($cid, $nombre_archivo_4, "C:\\wamp\\www\\documentos_reclamaciones\\$nombre_archivo_4", FTP_BINARY)) ;
        log_file("Se ha subido correctamente el archivo 4");
    }
    if ($nombre_archivo_5 != "") {
        if (ftp_put($cid, $nombre_archivo_5, "C:\\wamp\\www\\documentos_reclamaciones\\$nombre_archivo_5", FTP_BINARY)) ;
        log_file("Se ha subido correctamente el archivo 5");
    }
    if ($nombre_archivo_6 != "") {
        if (ftp_put($cid, $nombre_archivo_6, "C:\\wamp\\www\\documentos_reclamaciones\\$nombre_archivo_6", FTP_BINARY)) ;
        log_file("Se ha subido correctamente el archivo 6");
    }
    if ($nombre_archivo_7 != "") {
        if (ftp_put($cid, $nombre_archivo_7, "C:\\wamp\\www\\documentos_reclamaciones\\$nombre_archivo_7", FTP_BINARY)) ;
        log_file("Se ha subido correctamente el archivo 7");
    }
    if ($nombre_archivo_8 != "") {
        if (ftp_put($cid, $nombre_archivo_8, "C:\\wamp\\www\\documentos_reclamaciones\\$nombre_archivo_8", FTP_BINARY)) ;
        log_file("Se ha subido correctamente el archivo 8");
    }
    if ($nombre_archivo_9 != "") {
        if (ftp_put($cid, $nombre_archivo_9, "C:\\wamp\\www\\documentos_reclamaciones\\$nombre_archivo_9", FTP_BINARY)) ;
        log_file("Se ha subido correctamente el archivo 9");
    }
    if ($nombre_archivo_10 != "") {
        if (ftp_put($cid, $nombre_archivo_10, "C:\\wamp\\www\\documentos_reclamaciones\\$nombre_archivo_10", FTP_BINARY)) ;
        log_file("Se ha subido correctamente el archivo 10");
    }
    ftp_quit($cid);
}

?>
