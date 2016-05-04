<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
$examp = $_GET["q"]; //query number
$page = $_GET['page']; // get the requested page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction
$id = $_GET['id'];
if(!$sidx) $sidx =1;
// connect to the database

include("../server/connection.php");
$conn = mysql_connect("localhost", $connection['user'], $connection['password']);

mysql_select_db("shop") or die("Error connecting to db.");
mysql_set_charset('utf8');
switch ($examp) {
    case 1:
		$result = mysql_query("SELECT COUNT(*) AS count FROM linea_pedido");
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		$count = $row['count'];
		if( $count >0 ) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 0;
		}
        if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)
		if ($start<0) $start = 0;
        $SQL = "SELECT d.idpedido ,d.idarticulo ,a.nombre , d.unidad  ,d.precioTotal  FROM linea_pedido d ,articulo a WHERE  d.idPedido=".$id." and d.idarticulo=a.idarticulo ORDER BY $sidx $sord LIMIT $start , $limit";

		$result = mysql_query( $SQL ) or die("Couldnt execute query.".mysql_error());
        $i=0;
		while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
			$responce->rows[$i]['id']=$row['idpedido'];
            $responce->rows[$i]['cell']=array($row['idarticulo'],$row['nombre'],$row['unidad'],$row['precioTotal']);
            $i++;
		}
        echo json_encode($responce);
        break;
}
?>
