<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));

session_start();
$credencial = $_POST['credencial'];
$credencialObjeto = new stdClass();
$credencialObjeto = json_decode($credencial);

header("Content-Type: text/html; charset=utf-8");

include("connection.php");
$link = mysql_connect("localhost", $connection['user'], $connection['password'])  or die('No se pudo conectar' . mysql_error());

mysql_query("SET NAMES utf8");
mysql_select_db("shop") or die ("No se pudo seleccionar la base de datos");

$queryLogin = "SELECT * FROM cliente WHERE correo = '". $credencialObjeto->email . "' AND contrasenya = '".md5($credencialObjeto->contrasenya) . "'";

if (!$result = mysql_query($queryLogin)) {
	die(mysql_error());
}
$resultSet = mysql_fetch_array($result);

if (mysql_num_rows($result) == 1) {
	if ($resultSet[9] == 0 || $resultSet[9] == null) {
		http_response_code(400);
		die("La cuenta no está activada.");
	}
	  $_SESSION['logged'] = true;
	  $_SESSION['idUsuario'] = $resultSet[0];
    $_SESSION['nombre'] = $resultSet[1];
    $_SESSION['apellido'] = $resultSet[2];
	  $_SESSION['dni'] = $resultSet[3];
	  $_SESSION['correo'] = $resultSet[6];
    $_SESSION['empleado'] = $resultSet[8];
	  
  http_response_code(200);
}else if(mysql_num_rows($result) == 0){
	  http_response_code(400);

}else{
	  http_response_code(500);

}

?>
