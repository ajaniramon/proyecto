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

function smarty_block_CWBarraSupPanel($params, $content, &$smarty) 
{
	$punteroPila= count($smarty->_tag_stack)-1;
	$CW = $smarty->_tag_stack[$punteroPila][0];
	//Puntero a la etiqueta Padre (CWPanel) 
	$punteroPilaPadre = $punteroPila - 1;		
	$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];

	if(!isset($content)) // Si se abre la etiqueta {CWBarraSupPanel}...
	{
		$n_comp = "CWBarraSupPanel";
		// Necesitamos saber cuántas instancias de este componente existen ya / para poner el codigo o no
		$num=$smarty->igepPlugin->registrarInstancia($n_comp);
	}
	else
	{
		$igepSmarty = new IgepSmarty();	
		$idPanel = $smarty->_tag_stack[$punteroPilaPadre][1]['id'];

		if ($params['titulo'])
		{
			$titulo = $params['titulo'];
		}
		else
		{ 
			$titulo = "";
		}	
			
		$ini_barra = "";		
		$class = '';
		switch ($idPanel)
		{
			case 'fil':
			case 'vSeleccion':
				$class = 'topBar-panelFil';
			break;
			case 'lis':
			case 'edi':
				$class = 'topBar-panelPrimary';
			break;
			case 'lisDetalle':
			case 'ediDetalle':
				$class = 'topBar-panelDetail';
			break;
		}
		$ini_barra .= "<div class='$class row'>";
	
	
              
    	$ini_barra .= "<div class='col-xs-7 col-sm-6 col-md-6 titlePanel text-left'>".$titulo."</div>\n";
    	   
		$fin_barra .= "</div>\n";      
       
		
		return $ini_barra.$content.$fin_barra;		
	}
}
?>