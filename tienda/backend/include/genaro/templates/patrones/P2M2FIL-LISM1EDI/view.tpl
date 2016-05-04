<?php

//<<$classname_maestro|capitalize>>
$comportamientoVentana= new IgepPantalla();

$panelMaestro = new IgepPanel('<<$classname_maestro|capitalize>>',"smty_datosTablaM");
$panelMaestro->activarModo("fil","estado_fil");
$panelMaestro->activarModo("lis","estado_lis");
$comportamientoVentana->agregarPanel($panelMaestro);

//<<$classname_detalle|capitalize>>
if(count(IgepSession::dameUltimaConsulta("<<$classname_maestro|capitalize>>"))>0){
	$panelDetalle = new IgepPanel('<<$classname_detalle|capitalize>>',"smty_datosFichaD");
	$panelDetalle->activarModo("edi","estado_edi");
	$comportamientoVentana->agregarPanelDependiente($panelDetalle,"<<$classname_maestro|capitalize>>");
}

$s->display('<<$nombreModulo|capitalize>>/p_<<$classname_maestro|capitalize>>.tpl');

?>
