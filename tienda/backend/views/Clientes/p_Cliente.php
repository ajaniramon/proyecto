<?php
    $comportamientoVentana= new IgepPantalla();

    $panel = new IgepPanel('Cliente',"smty_datosTabla","smty_datosFicha");
    $panel->activarModo("fil","estado_fil");
    $panel->activarModo("lis","estado_lis");
    $comportamientoVentana->agregarPanel($panel);

    $s->display('Clientes/p_Cliente.tpl');
?>