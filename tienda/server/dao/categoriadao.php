<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
$metodo = $_SERVER['REQUEST_METHOD'];
$objeto = new StdClass();

if ($metodo == 'GET') {

}else if($metodo == 'POST'){

	$objeto = json_decode($_POST['categoria']);

	if (strcmp($objeto->accion,"d") == 0) {
		borrar($objeto->idCategoria);
	}

	if (strcmp($objeto->accion,"i") == 0) {
		insertar($objeto);
	}
	if (strcmp($objeto->accion,"a") == 0) {
		actualizar($objeto);
	}
}

function borrar($idCategoria){

include("../connection.php");
$link = mysql_connect("localhost", $connection['user'], $connection['password'])  or die('No se pudo conectar' . mysql_error());

  mysql_query("SET NAMES utf8");
  mysql_select_db("shop") or die ("No se pudo seleccionar la base de datos");

  $SQL = "DELETE from categoria WHERE idCategoria = " . $idCategoria;

 mysql_query($SQL) or muere(mysql_error(),mysql_errno());
echo "Borrado OK.";
http_response_code(200);

 mysql_close($link);

}

function insertar($categoria){

include("../connection.php");
$link = mysql_connect("localhost", $connection['user'], $connection['password'])  or die('No se pudo conectar' . mysql_error());

  mysql_query("SET NAMES utf8");
  mysql_select_db("shop") or die ("No se pudo seleccionar la base de datos");

  $insertSql = "INSERT INTO categoria VALUES(null," . "'". $categoria->nombre. "'" . ");";
  mysql_query($insertSql) or muere(mysql_error(),mysql_errno());
  echo "Insertada correctamente.";
  http_response_code(200);
   mysql_close($link);

}

function actualizar($categoria){

  include("../connection.php");
  $link = mysql_connect("localhost", $connection['user'], $connection['password'])  or die('No se pudo conectar' . mysql_error());

  mysql_query("SET NAMES utf8");
  mysql_select_db("shop") or die ("No se pudo seleccionar la base de datos");

  $SQL = "UPDATE categoria set nombre=" . "'" .$categoria->nombre . "'"	. " WHERE idCategoria = " . $categoria->idCategoria . ";";

  $resultado = mysql_query($SQL,$link);
  if (!$resultado) {
      echo "No se ha podido actualizar porque tiene productos asociados.";
      http_response_code(400);
  }else{
  	echo "Actualizado correctamente.";
  	http_response_code(200);
  }
   mysql_close($link);
}

function muere($error,$codigo){
echo "Consulta fallida: " .$error;
http_response_code(400);
exit();
}
?>
