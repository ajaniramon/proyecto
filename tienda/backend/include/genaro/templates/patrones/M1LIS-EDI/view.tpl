
$panelDetalle = new IgepPanel("<<$classname_detalle|capitalize>>","smty_datosTabla<<$classname_detalle|capitalize>>","smty_datosFicha<<$classname_detalle|capitalize>>");
$panelDetalle->activarModo("lis","estado_lisDetalle");
$panelDetalle->activarModo("edi","estado_ediDetalle");
$datosPanelDetalle = $comportamientoVentana->agregarPanelDependiente($panelDetalle,"<<$classname_maestro|capitalize>>");

