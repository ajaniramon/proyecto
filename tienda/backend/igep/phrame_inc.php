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
 * Este fichero se incluye desde:
 * - aplicacion/phrame.php
 * - aplicacion/openApp.php
 *
 * En el primer caso hay que hacer algunas operaciones mas.
 * 
 * @package gvHIDRA
 */

try {

	if ($_REQUEST['action'] <> 'abrirAplicacion') {
		include_once('igep/include_class.php');
		IgepSession::session_start();	
	}
	include_once('igep/include_all.php');
	
	//error global
	global $g_error; 
	$g_error = new IgepError();
	
	// sigue activa la sesion? php puede limpiarlas a partir de 24 minutos de inactividad (por defecto)
	if (!IgepSession::isValid()) {
		$salto ="Location: index.php?view=igep/views/igep_noSession.php";
		header ($salto);
		exit;
	}
	
	/*recargamos los mappings?*/
	if(ConfigFramework::getConfig()->getReloadMappings())
		IgepSession::borraPanel(_MAPPING);
	if(!IgepSession::existePanel(_MAPPING)){
		$go_map  = new ComponentesMap();
		IgepSession::_guardaPanelIgep(_MAPPING,$go_map);
	}
	else
		$go_map = IgepSession::damePanel(_MAPPING);
	
	//release control to controller for further processing
	if(!IgepSession::existePanel(_CONTROLLER)){
		$controller = new ActionController($go_map->GetOptions());
		IgepSession::_guardaPanelIgep(_CONTROLLER,$controller);
	}
	else{
		$controller = IgepSession::damePanel(_CONTROLLER);
	}
	
	$controller->process($go_map->GetMappings(), $_REQUEST);

} catch (Exception $e) {
	IgepDebug::setDebug(PANIC,'Ha ocurrido una excepción no capturada (phrame):<pre>'.$e.'</pre>');
	// re-throw the exception
	throw $e;
}

?>
