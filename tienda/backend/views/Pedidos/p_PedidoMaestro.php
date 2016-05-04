<?php

//PedidoMaestro
$comportamientoVentana= new IgepPantalla();

$panelMaestro = new IgepPanel('PedidoMaestro',"smty_datosTablaM");
$panelMaestro->activarModo("fil","estado_fil");
$panelMaestro->activarModo("lis","estado_lis");
$comportamientoVentana->agregarPanel($panelMaestro);

//PedidoDetalle
	$panelDetalle = new IgepPanel('PedidoDetalle',"smty_datosTablaD");
	$panelDetalle->activarModo("lis","estado_lis");
	$comportamientoVentana->agregarPanelDependiente($panelDetalle,"PedidoMaestro");

$s->display('Pedidos/p_PedidoMaestro.tpl');

?>