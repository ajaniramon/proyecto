<?php
/* gvHIDRA. Herramienta Integral de Desarrollo Rбpido de Aplicaciones de la Generalitat Valenciana
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
*  Av. Blasco Ibбсez, 50
*  46010 VALENCIA
*  SPAIN
*  +34 96386 24 83
*  gvhidra@gva.es
*  www.gvpontis.gva.es
*
*/


/**
 * Ficheros auxiliares para la ejecucion de los tests
 * En este se definen algunas funciones auxiliares y se incluyen
 * todas las clases de gvHIDRA.
 * 
 * package gvHIDRA
 */

include_once ('igep/include_class.php');
include_once ('igep/include_all.php');

/**
 * Funcion que incluye todos los tests de un directorio,
 * y devuelve vector con lista de clases incluidas
 */
function includeDir($ruta) {
	$lista = opendir($ruta);
	$res = array();
	while (false !== ($filename = readdir($lista))) {
		if ( strpos($filename, 'Test.php') !== FALSE ) {
			$res[] = substr($filename,0,-4);
			include_once ($ruta.$filename);
		}
	}
	return $res;
}

/**
 * Funcion que ejecuta los tests de cada clase del vector
 */
function runTests($v_clases) {
	foreach($v_clases as $clase) {
		$x = new $clase;
		$x->main();
	}
}

$clases = includeDir('igep/tests/');
$aux = new AppMainWindow();
ConfigFramework::getConfig()->setLogStatus(LOG_NONE);

?>