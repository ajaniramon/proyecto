<?php
/**
 * Ficheros a incluir
 * $Revision: 1.2 $
 */

$al = GVHAutoLoad::singleton();

$al->registerClass('AppMainWindow', 'actions/principal/AppMainWindow.php');

// Registramos las clases que tengamos 
// $al->registerClass('NomClase','ruta a la clase');

$al->registerClass('PedidoMaestro','actions/Pedidos/PedidoMaestro.php');

$al->registerClass('PedidoDetalle','actions/Pedidos/PedidoDetalle.php');
$al->registerClass('Cliente','actions/Clientes/Cliente.php');
$al->registerClass('Articulo','actions/Articulos/Articulo.php');
$al->registerClass('Categoria','actions/Categorias/Categoria.php');?>