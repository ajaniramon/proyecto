
$panelDetalle = new IgepPanel('<<$classname_detalle|capitalize>>',"smty_datos<<$classname_detalle|capitalize>>");
$panelDetalle->activarModo("lis","estado_lis");
$datosPanelDetalle = $comportamientoVentana->agregarPanelDependiente($panelDetalle,"<<$classname_maestro|capitalize>>");

