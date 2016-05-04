<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
$page = $_GET['page']; // get the requested page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction

if(!$sidx) $sidx =1;
// connect to the database

include("../server/connection.php");
$db = mysql_connect("localhost", $connection['user'], $connection['password'])

or die("Connection Error: " . mysql_error());

mysql_select_db("shop") or die("Error conecting to db.");
mysql_set_charset('utf8');
$result = mysql_query("SELECT COUNT(*) AS count FROM articulo");
$row = mysql_fetch_array($result,MYSQL_ASSOC);
$count = $row['count'];

if( $count >0 ) {
	$total_pages = ceil($count/$limit);
} else {
	$total_pages = 0;
}
if ($page > $total_pages) $page=$total_pages;
$start = $limit*$page - $limit; // do not put $limit*($page - 1)
$SQL = "SELECT * FROM articulo";
$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    $responce->rows[$i]['id']=$row['idArticulo'];
    $responce->rows[$i]['cell']=array($row['idarticulo'],$row['nombre'],$row['descripcion'],$row['precio'],$row['imagen'],$row['stock'],$row['categoria']);
    $i++;
}
echo json_encode($responce);
?>
