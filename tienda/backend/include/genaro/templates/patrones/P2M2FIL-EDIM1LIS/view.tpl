<?php

//<<$classname_maestro|capitalize>>
$comportamientoVentana= new IgepPantalla();

$panelMaestro = new IgepPanel('<<$classname_maestro|capitalize>>',"smty_datosFichaM");
$panelMaestro->activarModo("fil","estado_fil");
$panelMaestro->activarModo("edi","estado_edi");
$comportamientoVentana->agregarPanel($panelMaestro);

//<<$classname_detalle|capitalize>>
if(count(IgepSession::dameUltimaConsulta("<<$classname_maestro|capitalize>>"))>0){
	$panelDetalle = new IgepPanel('<<$classname_detalle|capitalize>>',"smty_datosTablaD");
	$panelDetalle->activarModo("lis","estado_lisDetalle");
	$comportamientoVentana->agregarPanelDependiente($panelDetalle,"<<$classname_maestro|capitalize>>");
}

$s->display('<<$nombreModulo|capitalize>>/p_<<$classname_maestro|capitalize>>.tpl');

?>
