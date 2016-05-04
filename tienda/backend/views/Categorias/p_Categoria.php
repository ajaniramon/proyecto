<?php
    $comportamientoVentana= new IgepPantalla();

    $panel = new IgepPanel('Categoria',"smty_datosTabla","smty_datosFicha");
    $panel->activarModo("fil","estado_fil");
    $panel->activarModo("lis","estado_lis");
    $panel->activarModo("edi","estado_edi");
    $comportamientoVentana->agregarPanel($panel);

    $s->display('Categorias/p_Categoria.tpl');
?>