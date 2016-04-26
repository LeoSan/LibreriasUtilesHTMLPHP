<?php
error_reporting(0);
putenv('GDFONTPATH=' . realpath('.'));
$im = imagecreatetruecolor(400, 200);
$im = imagecreatefromjpeg('img_verificador.jpg');
$white = imagecolorallocate($im, 255, 255, 255);
$grey = imagecolorallocate($im, 128, 128, 128);
$black = imagecolorallocate($im, 0, 0, 0);
$line = $_GET["line"];
//$line = generateRandomString(5);
$text = gen_code($line);
$font = 'espacio.ttf';
for ($n = 0; $n <= 80; $n++){
$black = imagecolorallocate($im, mt_rand(0,100), mt_rand(0,100), mt_rand(0,100));
imagettftext($im, 80, 0, mt_rand(-400,400), mt_rand(-200,200), $black, $font, generateRandomString(mt_rand(1,5)));
imagettftext($im, 80, 0, mt_rand(-400,400), mt_rand(-200,200), $black, $font, generateRandomString(mt_rand(1,5)));
}
$font = 'washrasb.ttf';
imagettftext($im, 100, mt_rand(-5,5),1,140, $black, $font, $text);
header('Content-Type: image/png');
imagepng($im);
imagedestroy($im);

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
function gen_code($seed){
/*$base64shuffle = base64_encode($seed);
$base64shuffle = base64_encode($base64shuffle);
$base64shuffle = base64_encode($base64shuffle);*/
$cadena = substr(/*$base64shuffle*/$seed,0,5);
return $cadena;
}

//text_to_speech:
//http://translate.google.com/translate_tts?tl=es&q=equis,zeta,y%20griega,numero%20cuatro,numero%20tres

?>