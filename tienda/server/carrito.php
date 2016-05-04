<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));

session_start();
$carrito = $_POST["carrito"];

$obj_carrito = new stdClass(); // Construyes obj
$obj_carrito = json_decode($carrito);

date_default_timezone_set("Europe/Madrid");
$fecha_pedido = date("Y/m/d h:i:s", time());
echo $fecha_pedido;
/*meter el carrito a la bd*/
header("Content-Type: text/html; charset=utf-8");

include("connection.php");
$link = mysql_connect("localhost", $connection['user'], $connection['password']) or die('No se pudo conectar' . mysql_error());

mysql_query("SET NAMES utf8");
mysql_select_db("shop") or die ("No se pudo seleccionar la base de datos");

// Cuando haya un sistema de usuarios funcional, hay que pasar el DNI del usuario en el carrito para ponerle el pedido a su nombre.
$SQL = 'INSERT INTO pedido(fecha,total,dni) VALUES ("' . $fecha_pedido . '",' . $obj_carrito->total . ',"' . $_SESSION['dni'] . '");';

mysql_query($SQL) or die('Consulta fallida 2: ' . mysql_error());

// Cuando tengamos un sistema  de gestión de clientes, cambiar el dni por el del usuario.
$SQL_idPedido = "SELECT p.idpedido FROM pedido p, cliente c WHERE c.dni = p.dni AND c.dni ='" . $_SESSION['dni'] . "' ORDER BY p.fecha DESC";
$result_idPedido = mysql_query($SQL_idPedido) or die('Consulta fallida 3: ' . mysql_error());
$idPedido = mysql_fetch_array($result_idPedido); // Array de un dato (primera fila tiene la idPedido --> $row[0]

$ccc = $obj_carrito->ccc;
require("transaction.php");

$SQL_linea_pedido = "INSERT INTO linea_pedido(idpedido, idarticulo, unidad, precio, precioTotal) VALUES ";
$articulos = $obj_carrito->articulos;
$num_articulos = count($obj_carrito->articulos);

for ($i = 0; $i < $num_articulos;$i++) {
  $SQL_linea_pedido .= "(" . $idPedido[0] . "," . $articulos[$i]->id . "," . $articulos[$i]->cantidad . "," . $articulos[$i]->precio . "
  ," . ($articulos[$i]->cantidad * $articulos[$i]->precio) . ")";
  if($i != $num_articulos - 1){
    $SQL_linea_pedido .= ","; // Si no es la última línea, le ponemos una coma al final del values

  }
}
$SQL_linea_pedido .= ";";// Cerramos la consulta

mysql_query($SQL_linea_pedido) or die('Consulta fallida 1: ' . mysql_error());
mysql_close($link);
?>
