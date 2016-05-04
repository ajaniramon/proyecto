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

function smarty_block_CWContenedor($params, $content, &$smarty) 
{
	if(!isset($content)) // Si se abre la etiqueta {CWContenedor}...
	{	
		$n_comp = "CWContenedor";	
		// Necesitamos saber cuántas instancias de este componente existen ya / para poner el codigo o no
		$num=$smarty->igepPlugin->registrarInstancia($n_comp);
	} 
	else 
	{	
		//Puntero a la pila de etiquetas que contiene a CWFila
		$punteroPilaPadre = count($smarty->_tag_stack)-2;
		$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];
		$estiloPanel = '';
		if ($CWPadre == 'CWPanel')
		{
			$panel = $smarty->_tag_stack[$punteroPilaPadre][1]['id'];
			switch($panel)
			{
				case 'fil':
					$estiloPanel = 'backgroundFil';
				break;
				case 'lis':
				case 'lisDetalle':
					$estiloPanel = 'backgroundLis';
				break;
				case 'edi':
				case 'ediDetalle':
					$estiloPanel = 'backgroundEdi';
				break;
				default:
					$estiloPanel = 'background';
				break;
			}
		}	
			
		$ini_contenedor = "<!-- INI: CWContenedor -->\n";
	
		/*$ini_contenedor .= "<table style='width: 100%; border-style:solid;' cellpadding='2' cellspacing='0'>\n";*/
		$ini_contenedor .= "<div class=\"".$estiloPanel."\">\n";

		

		$fin_contenedor .= "</div>\n";

		$fin_contenedor .= "<!-- FIN: CWContenedor -->\n";
		
		return $ini_contenedor.$content.$fin_contenedor;		
	}
}
?>