<?php

$url = "http://ecobanco-vicentedaw2.rhcloud.com/api/transaccion";
$ccc_shop = "10499400880563987412";
$pin_shop = "0846";
$concepto = "EcoRecipes - Pago de compra " . $idPedido[0];
$ccc_client =  $ccc;
$importe = $obj_carrito->total;

$transaction = new stdClass();
$transaction->cuentaOrigen = $ccc_client;
$transaction->cuentaDestino = $ccc_shop;
$transaction->importe = $importe;
$transaction->concepto = $concepto;
$transaction->pin = $pin_shop;

$data = json_encode($transaction);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json" , "Content-Length:" . strlen($data)));

$result = curl_exec($ch);

if(!curl_errno($ch)) {
	$info = curl_getinfo($ch);
	if ($info["http_code"]!=200) {
		http_response_code(400);
		$resultado =  "Hubo un problema al procesar el pago. Consulte con su banco.";
	}else{
		$resultado = "La compra se ha realizado satisfactoriamente.";
	}
	/*echo "TIME: " . $info["total_time"] . "<br>";
	echo "STATUS: " . $info["http_code"] . "";
	echo "<br><br>";*/
}

curl_close($ch);

echo $resultado;

?>