<?php
/* gvHIDRA. Herramienta Integral de Desarrollo Rápido de Aplicaciones de la Generalitat Valenciana
*
* Copyright (C) 2006 Generalitat Valenciana.
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,USA.
*
* For more information, contact:
*
*  Generalitat Valenciana
*  Conselleria d'Infraestructures i Transport
*  Av. Blasco Ibáñez, 50
*  46010 VALENCIA
*  SPAIN
*  +34 96386 24 83
*  gvhidra@gva.es
*  www.gvpontis.gva.es
*
*/
/**
* Este fichero mandará en toda la aplicación y será el primero a ejecutarse.
* Si es la primera entrada a la aplicación redigire a openApp.php
* En otro caso muestra la plantilla correspondiente.
* 
* package gvHIDRA
*/
if (empty($_REQUEST['action']) && empty($_GET['view'])) {
	// si es la primera entrada
	header('Location: openApp.php?'.$_SERVER['QUERY_STRING']);
	exit;
}
try {
	include_once('igep/include_class.php');
	IgepSession::session_start();
	include_once('igep/include_all.php');

	// sigue activa la sesion? php puede limpiarlas a partir de 24 minutos de inactividad (por defecto)
	if (!IgepSession::isValid()) {
		$aux = new CustomMainWindow(); // inicializa templates_c
		$vcorta='igep/views/igep_noSession.php';
	} else {
		//Control del seguridad
		if($_GET[_VIEW]) {
			//Si queremos acceder a un view en concreto
			if(strpos($_GET[_VIEW],'igep/')!==0) {
				//Si el view no es de igep
				$go_map = IgepSession::damePanel(_MAPPING);
				$secure_fw = $go_map->secureFW();
				//Si no es una accion programada, devolvemos error
				if(!in_array($_GET[_VIEW],$secure_fw))
					throw new Exception('SecureAlert: acceso a ruta no permitada.');
			}
			$vcorta = $_GET[_VIEW]; 
		}
		else
			$vcorta = 'igep/views/aplicacion.php';
	}
	$directorioBase = dirname($_SERVER['SCRIPT_FILENAME'])."/";
	$vista = $directorioBase.$vcorta;

	global $s;
	$s = new Smarty_Phrame();
	include($vista);
	
} catch (Exception $e) {
	IgepDebug::setDebug(PANIC,'Ha ocurrido una excepción no capturada (index):<pre>'.$e.'</pre>');
	// re-throw the exception
	throw $e;
}
?>