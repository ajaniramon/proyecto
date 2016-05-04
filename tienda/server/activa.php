<?php
$uuid = $_GET["uuid"];
$link = mysqli_connect("localhost","root","root","shop") or die("Error conectando a la BD:".mysqli_error()); //cambiar datos BD
$query = "SELECT * FROM cliente WHERE token = '".$uuid."';";

$result = mysqli_query($link,$query) or die("Error consultando la BD:" .mysqli_error());
 if(mysqli_num_rows($result) == 1){
 	$fila = mysqli_fetch_array($result,MYSQLI_ASSOC);
 	if ($fila["enabled"] == 1){
 		http_response_code(400);
 		die("La cuenta ya se encuentra activa.");
 	}
 	$query = "UPDATE cliente SET enabled=1 WHERE idcliente = " . $fila["idcliente"];
 	mysqli_query($link,$query) or die("Error activando la cuenta: ".mysqli_error());
 	echo "La cuenta se ha activado correctamente.";
 }else{
 	http_response_code(400);
 	die("El token no se corresponde con ninguna cuenta existente.");
 }


?>