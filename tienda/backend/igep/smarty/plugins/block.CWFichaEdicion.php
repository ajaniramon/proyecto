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
require_once('igep/include/IgepSmarty.php');

function smarty_block_CWFichaEdicion($params, $content, &$smarty) 
{	
	//////////////////////////////////////////////////////////////////////
	// LECTURA DE VALORES DE LA PILA //
	//////////////////////////////////////////////////////////////////////
	//Puntero a la pila de etiquetas que contiene a CWFichaEdicion 
	$punteroPila= count($smarty->_tag_stack)-1;
	$CW = $smarty->_tag_stack[$punteroPila][0];	
	//Puntero a la etiqueta Padre CWContenedor 
	$punteroPilaPadre = $punteroPila - 1;		
	$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];
	//Puntero a la etiqueta Padre CWContenedor 
	$punteroPilaAbuelo = $punteroPilaPadre - 1;		

	//Reasignamos el parametro accion del CWPanel en el de CWFichaEdicion
	$smarty->_tag_stack[$punteroPila][1]['accion'] = $smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'];
	$solapaActiva = $smarty->_tag_stack[$punteroPila][1]['solapaActiva'];
	
	if($params['datos']) 
	{
		$datosTabla=$params['datos'];			
	} 
	/*else {
		//ERROR PARAMETRO OBLIGATORIO HABRA QUE VER COMO TRATARLO
		return("Vector de datos VACIO".$content."VECTOR VACIO");	
	}*/
    $numPaginas=count($datosTabla);
    		
	$indicePila = count($smarty->_tag_stack)-1;

	$numPagInsertar = 1;
	$smarty->_tag_stack[$indicePila][1]['numPagInsertar'] = $numPagInsertar;
	
	$numPaginasTotales = $numPaginas+$numPagInsertar;
		
	// Se abre la etiqueta
	if(!isset($content)) 
	{
		
		///////////////////////////////////////////
		// CODIGO NECESARIO PARA CADA COMPONENTE //
		///////////////////////////////////////////
		// Primero defino el nombre del componente.
		$n_comp='CWFichaEdicion';	
		// Necesitamos saber cuántas instancias de este componente existen ya / para poner el codigo o no
		$num=$smarty->igepPlugin->registrarInstancia($n_comp);
		
		if($params['id']) 
		{
			$nombre=$params['id'];
		} 
		else
		{
			$nombre=$n_comp.$num;
		}		
	} 
	else
	{
		$igepSmarty = new IgepSmarty();	
		
		if($params['id']) 
		{
			$idFichaEdicion = $params['id'];
		} 
		else 
		{
			$idFichaEdicion = 'FALTAID';
		}
  	  
		$mensaje_ini = "<!-- COMIENZA EL SUBPANEL EDICIÓN -->\n";	
		$htmlTabOn = "<input type='hidden' id='solapaActiva' name='solapaActiva' value='$solapaActiva'/>\n";
		$mensaje_fin = "<!-- FIN SUBPANEL EDICIÓN -->\n";
		return $mensaje_ini.$htmlTabOn.$content.$mensaje_fin;
	}
}
?>