<?php
    $comportamientoVentana= new IgepPantalla();

    $panel = new IgepPanel('<<$classname|capitalize>>',"smty_datosTabla","smty_datosFicha");
    $panel->activarModo("fil","estado_fil");
    $panel->activarModo("lis","estado_lis");
    $comportamientoVentana->agregarPanel($panel);

    $s->display('<<$nombreModulo|capitalize>>/p_<<$classname|capitalize>>.tpl');
?>
