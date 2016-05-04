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

function smarty_function_CWDetalles($params, &$smarty)
 {
 	$igepSmarty = new IgepSmarty();			
 		
	//////////////////////////////////////////////////////////////////////
	// LECTURA DE VALORES DE LA PILA //
	//////////////////////////////////////////////////////////////////////
	//Número de elementos de la pila de Blocks
	$puntero = count($smarty->_tag_stack);
	//Puntero a la etiqueta Padre 
	$punteroPilaPadre = $puntero - 1; //Como es un function, el mismo no se apila...
	$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];			
	////////////////////////////////////////////////////////////////////////////////////
	///// FIN LECTURA DE VALORES DE LA PILA  /////
	////////////////////////////////////////////////////////////////////////////////////
	
	
	if ($params['detalles']) 
	{
		$detalles = $params['detalles'];
	}
	if ($params['panelActivo']) 
	{
		$panelActivo = $params['panelActivo'];
	}
	if ($params['claseManejadoraPadre']) 
	{
		$claseManejadoraPadre = $params['claseManejadoraPadre'];
	}
	
	$botonesDetalles =	"<tr><td colspan = '2'>";
	$botonesDetalles .= "<div id=\"detalles\">";
	
	$numDetalles = count($detalles);
	for($i=0;$i<$numDetalles;$i++)
	{
		$botonesDetalles .= "<a id=".$detalles[$i]['panelActivo']." ";
				
		if ($detalles[$i]['panelActivo'] == $panelActivo)
		{
			$class = "class='linkDetailOn'";
			$onClick = '';
			$href = "href=#";
		}
		else
		{
			$class = "class='linkDetail'";
			$href = "href='phrame.php?action=cambiarPanelDetalle&claseManejadora=".$claseManejadoraPadre."&";
			$href .= "panelActivo=".$detalles[$i]['panelActivo']."' ";
			$onClick = "onClick='javascript:aviso.mostrarMensajeCargando(\"Cargando\");'";
		}
			
		$botonesDetalles .= "target='oculto' ".$class." ".$href." $onClick>".$detalles[$i]['titDetalle']."</a>&nbsp;";		
	}

	$botonesDetalles .= "</div>";
	$botonesDetalles .= "</td></tr>";
	
	return $botonesDetalles;	
}
?>