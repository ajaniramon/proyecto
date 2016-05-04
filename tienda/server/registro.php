<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
session_start();
require_once("mail.php");
require_once("smtp.php");
$cliente = $_POST['cliente'];
$clienteObjeto = new stdClass();
$clienteObjeto = json_decode($cliente);

header("Content-Type: text/html; charset=utf-8");

include("connection.php");
$link = mysql_connect("localhost", $connection['user'], $connection['password'])  or die('No se pudo conectar' . mysql_error());
mysql_select_db("shop") or die ("No se pudo seleccionar la base de datos");

mysql_query("SET NAMES utf8");
$uuid = uniqid("user_",false);
$SQL = "INSERT INTO cliente(idcliente,nombre,apellido,dni,direccion,telefono,correo,contrasenya,empleado,enabled,token) VALUES(null," . "'" . $clienteObjeto->nombre . "'," . "'" . $clienteObjeto->apellido . "'," . "'" . $clienteObjeto->dni . "'," ."'" . $clienteObjeto->direccion . "',"."'" . $clienteObjeto->telefono . "'," . "'" . $clienteObjeto->correo . "'," . "'" . md5($clienteObjeto->contrasenya) . "','false',0,'".$uuid."');";

mysql_query($SQL) or die('Consulta fallida: ' . mysql_error());





$correo = new PHPMailer();
 
$correo->IsSMTP();
 
$correo->SMTPAuth = true;
 
$correo->SMTPSecure = 'tls';
 
$correo->Host = "smtp.gmail.com";
 
$correo->Port = 587;

$correo->Username = "warpigs.ajani@gmail.com";
 
$correo->Password = "fullhd1080";
 
$correo->SetFrom("support@ecorecipes.es", "EcoRecipes STORE");
 
$correo->AddAddress($clienteObjeto->correo);
 
$correo->Subject = "EcoRecipes - Activación de Cuenta";
 
$correo->MsgHTML("Tu enlace para activar tu cuenta es <a href='http://localhost/tienda/server/activa.php?uuid=".$uuid."'>este</a>");
 

 
if(!$correo->Send()) {
  echo "Hubo un error: " . $correo->ErrorInfo;
} else {
  echo "Mensaje enviado con exito.";
}
//echo "¡OK! Comprueba tu correo electrónico para activar tu cuenta. ";

?>
