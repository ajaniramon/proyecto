
$panelDetalle = new IgepPanel('<<$classname_detalle|capitalize>>','smty_datos<<$classname_detalle|capitalize>>');
$panelDetalle->activarModo("edi","estado_ediDetalle");
$datosPanelDetalle = $comportamientoVentana->agregarPanelDependiente($panelDetalle,"<<$classname_maestro|capitalize>>");

