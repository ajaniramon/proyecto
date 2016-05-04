<?php

//MAESTRO
$comportamientoVentana= new IgepPantalla();
$panelMaestro = new IgepPanel('<<$classname_maestro|capitalize>>',"smty_datosTablaM");
$panelMaestro->activarModo("fil","estado_fil");
$panelMaestro->activarModo("lis","estado_lis");
$datosPanel = $comportamientoVentana->agregarPanel($panelMaestro);

//DETALLES
