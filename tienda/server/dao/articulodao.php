<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
$metodo = $_SERVER['REQUEST_METHOD'];
$objeto = new StdClass();

if ($metodo == 'GET') {

}else if($metodo == 'POST'){

	$objeto = json_decode($_POST['articulo']);

	if (strcmp($objeto->accion,"d") == 0) {
		borrar($objeto->idArticulo);
	}

	if (strcmp($objeto->accion,"i") == 0) {
		insertar($objeto);
	}
	if (strcmp($objeto->accion,"a") == 0) {
		actualizar($objeto);
	}
}

function borrar($idArticulo){

include("../connection.php");
$link = mysql_connect("localhost", $connection['user'], $connection['password'])  or die('No se pudo conectar' . mysql_error());

  mysql_query("SET NAMES utf8");
  mysql_select_db("shop") or die ("No se pudo seleccionar la base de datos");
  $SQL = "DELETE FROM articulo WHERE idArticulo = " . $idArticulo . ";";

  $success =  mysql_query($SQL,$link);

 if (!$success) {
 	echo "Error al borrar. El artículo ha sido pedido alguna vez.";
 	http_response_code(400);
 }else{
 	echo "Borrado correctamente.";
 	http_response_code(200);
 }
 mysql_close($link);
}

function insertar($articulo){

include("../connection.php");
$link = mysql_connect("localhost", $connection['user'], $connection['password'])  or die('No se pudo conectar' . mysql_error());

  mysql_query("SET NAMES utf8");
  mysql_select_db("shop") or die ("No se pudo seleccionar la base de datos");

  $SQL = "INSERT INTO articulo VALUES(null,'" . $articulo->nombre . "','" . $articulo->descripcion . "'," . $articulo->precio . ",'articulos/" . $articulo->imagen . "'," . $articulo->stock ."," . $articulo->categoria . ");";
  mysql_query($SQL,$link) or muere(mysql_error(),mysql_errno());
  echo "Insertado correctamente.";
  http_response_code(200);
   mysql_close($link);


}

function actualizar($articulo){
	//echo "Actualizando articulo " . json_encode($articulo) . "php";

include("../connection.php");
$link = mysql_connect("localhost", $connection['user'], $connection['password'])  or die('No se pudo conectar' . mysql_error());

  	mysql_query("SET NAMES utf8");
  	mysql_select_db("shop") or die ("No se pudo seleccionar la base de datos");

  	$SQL = "UPDATE articulo SET nombre = " . "'" . $articulo->nombre . "', descripcion ='" . $articulo->descripcion . "', precio=" . $articulo->precio . ", imagen='" . $articulo->imagen . "', stock=" . $articulo->stock . " WHERE idArticulo = " . $articulo->idArticulo . ";";
  	$resultado = mysql_query($SQL,$link);
  	if (!$resultado) {
  		echo "Hubo un fallo al actualizar: " . mysql_error();
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
