<?php

//MAESTRO
$comportamientoVentana= new IgepPantalla();
$panelMaestro = new IgepPanel('<<$classname_maestro|capitalize>>',"smty_datosFichaM");
$panelMaestro->activarModo("fil","estado_fil");
$panelMaestro->activarModo("edi","estado_edi");
$datosPanel = $comportamientoVentana->agregarPanel($panelMaestro);

//DETALLES
