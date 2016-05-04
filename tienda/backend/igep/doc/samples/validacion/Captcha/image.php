<?php
   
$string = $_GET['text'];
$key = 'ejemplo';
//decriptacion
$result = '';

$string = rawurldecode($string);
for($i=0; $i<strlen($string); $i++) {
	$char = substr($string, $i, 1);
	$keychar = substr($key, ($i % strlen($key))-1, 1);
	$char = chr(ord($char)-ord($keychar));
	$result.=$char;
}

$text = $result;
$captcha = imagecreatefromgif("captcha_bg.gif");
$colText = imagecolorallocate($captcha, 70, 70,70);
imagestring($captcha, 5, 16, 7, $text, $colText);
header("Content-type: image/gif");
imagegif($captcha);
