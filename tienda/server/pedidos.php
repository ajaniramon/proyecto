<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
header("Content-Type: text/html;charset=utf-8");

include("connection.php");
$link = mysql_connect("localhost", $connection['user'], $connection['password'])  or die('No se pudo conectar' . mysql_error());

mysql_query("SET NAMES 'utf8'");

mysql_select_db('shop') or die('No se pudo seleccionar la base de datos');

$dni = $_GET['dni'];

$SQL = 'SELECT * FROM pedido WHERE dni = "' . $dni . '";';
$result = mysql_query($SQL) or die('Consulta fallida: ' . mysql_error());

$i=0;
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)){
  $pedido[$i] = array('fecha'=>$line['fecha'], 'total'=>$line['total'], 'idpedido'=>$line['idpedido']);
  $i++;
}

echo json_encode($pedido);
mysql_free_result($result);
mysql_close($link);

?>
