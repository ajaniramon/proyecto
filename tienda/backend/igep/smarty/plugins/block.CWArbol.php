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

function smarty_block_CWArbol($params, $content, &$smarty) {
	if(!isset($content)) // Si se abre la etiqueta {CWMarcoPanel}...
	{	
		$n_comp = "CWArbol";	
		// Necesitamos saber cuántas instancias de este componente existen ya / para poner el codigo o no
		$num=$smarty->igepPlugin->registrarInstancia($n_comp);
	} 
	else 
	{		
		$igepSmarty = new IgepSmarty();			
		$smarty->igepPlugin->registrarInclusionJS('TreeMenu.js');
		$smarty->igepPlugin->registrarInclusionJS('objBotonToolTip.js');
		
		if($params['arbol']) 
		{		
			$html_ObjArbol="<br/>".$params['arbol'];
		} 
		
		$tituloArbol = "Panel árbol";
		if($params['titulo']) 
		{		
			$tituloArbol = $params['titulo'];
		}
		
		$llamadas_js="";
		
		//Parche temporal, evita errores de vavascrip
		$llamadas_js.="var edi_paginacion;\n var lis_paginacion;";
		
		$llamadas_js .= "bttlArbol = new objBTTArbol('bttlArbol','','','');";
		$igepSmarty->addPreScript($llamadas_js);
		
		//Registramos el objeto paginador
		$smarty->igepPlugin->registerJSObj('bttlArbol');
	
		if ($params['estado'] == 'on') 
		{
			$estado = "display:block;";
		}
		else 
		{ 
			$estado = "display:none;"; 
		}
		
		$anchoArbol= '5';
		/*if($params['ancho'])
		{
			$anchoArbol = $params['ancho'];
		}
		$anchoPanel = 100 - $anchoArbol; */

		/* MOD(ENLAZA): Poder definir la altura del árbol - Es un nuevo parámetro de la tpl 'alturamax' */
		$alturaMaxArbol = '375';
		if($params['alturamax'])
		{
			$alturaMaxArbol = $params['alturamax'];
		}
		/* Fin MOD */
		 
		$html_arbol = "";
		$fin_arbol = "";
		
		$html_arbol .= "<div id='P_edi' style='".$estado.";'>\n";
		$html_arbol .= "<table style='width: 100%;' cellspacing='0' cellpadding='0'>\n";
		$html_arbol .= "<tr style='width: 100%; vertical-align:top' >\n";
		$html_arbol .= "<td id='celdaArbol' class='tree' style='width: auto;' >\n";
	
			$html_arbol .= "<div id='divArbolCab' style='display:block; max-height: ". $alturaMaxArbol ."px;'>\n";
				$html_arbol .= "<div class='BarPanelTree row'>";
			    	$html_arbol .= "<div class='col-md-12 text-left'>".$tituloArbol."";
					/*$html_arbol .= "<img src='".IMG_PATH_CUSTOM."botones/55.gif' border='0' alt='&gt;&lt;' title='Contraer panel Arbol' onClick=\"javascript:bttlArbol.accionarPanel(".$anchoArbol.");\" />";*/
					$html_arbol .= "<span class='glyphicon glyphicon-transfer' aria-hidden='true' onClick=\"javascript:bttlArbol.accionarPanel(".$anchoArbol.");\" ></span>";
					$html_arbol .= "</div>";
				$html_arbol .= "</div>\n";
			$html_arbol .= "</div>\n";
				
			$html_arbol .= "<div id='divArbolOculto' style='padding:8px; display:none;width:auto; max-height: ". $alturaMaxArbol ."px;'>\n";
				$html_arbol .= "<div class='BarPanelTreeZip row'>";
				
					/*$html_arbol .= "<img src='".IMG_PATH_CUSTOM."botones/54.gif' border='0' alt='&gt;&lt;' title='Expandir panel Arbol' onClick=\"javascript:bttlArbol.accionarPanel(".");\" />";*/
			    	$html_arbol .= "<span class='glyphicon glyphicon-transfer' aria-hidden='true' onClick=\"javascript:bttlArbol.accionarPanel(".");\" ></span>";
				
				$html_arbol .= "</div>\n";
			$html_arbol .= "</div>\n";
			$html_arbol .= "<div id='divArbol' style=' padding-left: 12px; padding-right: 12px;  display:block;max-height: ". $alturaMaxArbol ."px;overflow:auto;'>\n"; // MOD(ENLAZA): Poder definir la altura del árbol
				$html_arbol .= $html_ObjArbol;
			$html_arbol .= "</div><br>\n";	
			
		$html_arbol .= "</td>\n";
		$html_arbol .= "<td>&nbsp;</td>\n"; // Separación entre árbol y panel
		$html_arbol .= "<td id='celdaPanel' style='width: 100%;' >\n"; // Abrimos la celda q contendrá el panel
		
		$fin_arbol .= "</td>\n";
		$fin_arbol .= "</tr>\n";
		$fin_arbol .= "</table>\n";
		$fin_arbol .= "</div>\n";

		return  $igepSmarty->getPreScript().$html_arbol.$content.$fin_arbol."\n";		
	}
}
?>