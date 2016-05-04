<?php

function muestraMensaje($msj, $tipo) {
	
	$mensaje = "<div class='mensaje".ucwords($tipo)."'>";
	$mensaje .= "<span>".$msj."</span>";
	$mensaje .= "</div>";
	
	echo $mensaje;
}
?>