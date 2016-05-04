<?php

function arrayTablasBD($database, $mdb) {
	
	$array = $mdb->listTables($database);
	
	if (PEAR::isError($array)) {
			muestraMensaje("ERROR: Fallo al obtener las tablas de la base de datos ".$database, 'error');
			$array = null;
		}
		
	return $array;
}

function arrayNombresCamposTabla($tabla, $mdb) {
	
	$array = $mdb->listTableFields($tabla);
	
	if (PEAR::isError($array)) {
		muestraMensaje("ERROR: Fallo al obtener los campos de la tabla ".$tabla, 'error');
		$array = null;
	}
	
	return $array;
}
?>