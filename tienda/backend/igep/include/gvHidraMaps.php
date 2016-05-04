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
* Mapeado de las acciones genéricas
* @version	$Id: gvHidraMaps.php 5609 2015-01-27 16:53:29Z pascual_dav $
* @author David: <pascual_dav@externos.gva.es>
* @author Vero: <navarro_ver@externos.gva.es>
* @author Toni: <felix_ant@externos.gva.es>
* @package gvHIDRA
*/
class gvHidraMaps extends MappingManager
{
	/**
	* constructor function
	* @return void
	*/
	function gvHidraMaps ()
	{

		$this->_SetOptions();

		/*camposDependientes*/
		$this->_AddMapping('gvHrefreshUI', 'IgepAccionesGenericas');
		$this->_AddForward('gvHrefreshUI', 'IgepOperacionOculto', 'index.php?view=igep/views/igep_actualizar.php&panel=listar');

		$this->_AddMapping('gvHautocomplete', 'IgepAccionesGenericas');

		/*ventanaSeleccion*/
		$this->_AddMapping('launchSelectionWindow', 'IgepAccionesGenericas');
		$this->_AddForward('launchSelectionWindow', 'IgepOperacionOculto', 'index.php?view=igep/views/igep_ventanaSeleccion.php');

		$this->_AddMapping('abrirVentanaSeleccion', 'IgepAccionesGenericas');
		$this->_AddForward('abrirVentanaSeleccion', 'IgepOperacionOculto', 'index.php?view=igep/views/igep_ventanaSeleccion.php');

		$this->_AddMapping('buscarVentanaSeleccion', 'IgepAccionesGenericas', '');
		$this->_AddForward('buscarVentanaSeleccion', 'IgepOperacionOculto', 'index.php?view=igep/views/igep_ventanaSeleccion.php');

		$this->_AddMapping('ordenarTabla', 'IgepAccionesGenericas');
		$this->_AddForward('ordenarTabla', 'IgepOperacionOculto', 'index.php?view=igep/views/igep_ordenarTabla.php');

		$this->_AddMapping('IgepSaltoVentana', 'IgepAccionesGenericas');
		$this->_AddForward('IgepSaltoVentana', 'IgepSaltoVentana', 'phrame.php');
		$this->_AddForward('IgepSaltoVentana', 'IgepOperacionOculto', 'index.php?view=igep/views/igep_saltoModal.php');

		$this->_AddMapping('IgepRegresoVentana', 'IgepAccionesGenericas');
		$this->_AddForward('IgepRegresoVentana', 'IgepSaltoVentana', 'phrame.php');
		$this->_AddForward('IgepRegresoVentana', 'IgepOperacionOculto', 'index.php?view=igep/views/igep_saltoModal.php');

		$this->_AddMapping('IgepInicioAplicacion', 'IgepAccionesGenericas');
		$this->_AddForward('IgepInicioAplicacion', 'IgepOperacionOculto', 'index.php?view=igep/views/aplicacion.php');

		$this->_AddMapping('cambiarPanelDetalle', 'IgepAccionesGenericas');
		$this->_AddForward('cambiarPanelDetalle', 'IgepOperacionOculto', 'index.php?view=igep/views/igep_cambiarDetalle.php');

		$this->_AddMapping('cerrarAplicacion', 'gvHidraMainWindow');
		$this->_AddForward('cerrarAplicacion', 'gvHidraCloseApp', 'index.php?view=igep/views/gvHidraCloseApp.php');

		$this->_AddMapping('abrirAplicacion', 'gvHidraMainWindow');
		$this->_AddForward('abrirAplicacion', 'gvHidraOpenApp', 'index.php?view=igep/views/aplicacion.php');
		$this->_AddForward('abrirAplicacion', 'gvHidraCloseApp', 'index.php?view=igep/views/gvHidraCloseApp.php');

		$this->_AddMapping('defaultPrint', 'IgepAccionesGenericas');

		$this->_AddMapping('exportCSV', 'IgepAccionesGenericas');

		$this->_AddMapping('focusChanged', 'IgepAccionesGenericas');
		$this->_AddForward('focusChanged', 'IgepOperacionOculto', 'index.php?view=igep/views/igep_actualizar.php&panel=listar');

		//Cargamos el mapeo específico de la organización (si existe)
		if(class_exists('CustomMapping'))
		{
			$mapping = new CustomMapping();
			$this->_MergeMappings($mapping);
		}
	}
}//FIN clase IgepComponentesMap
?>
