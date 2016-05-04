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

function smarty_block_CWBarraInfPanel($params, $content, &$smarty) 
{
	//////////////////////////////////////////////////////////////////////
	// LECTURA DE VALORES DE LA PILA //
	//////////////////////////////////////////////////////////////////////
	//Puntero a la pila de etiquetas que contiene a CWFichaEdicion 
	$punteroPila= count($smarty->_tag_stack)-1;
	$CW = $smarty->_tag_stack[$punteroPila][0];
	//Puntero a la etiqueta Padre (CWPanel) 
	$punteroPilaPadre = $punteroPila - 1;		
	$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];
	
	if(!isset($content)) // Si se abre la etiqueta {CWBarraInfPanel}...
	{	
		$n_comp = "CWBarraInfPanel";		
		$num = $smarty->igepPlugin->registrarInstancia($n_comp);
	} 
	else 
	{
		$igepSmarty = new IgepSmarty();	

	$idPanel = $smarty->_tag_stack[$punteroPilaPadre][1]['id'];
	$class = '';
	switch ($idPanel)
	{
		case 'fil':
		case 'vSeleccion':
			$class = 'bottomBar-panelFil';
		break;
		case 'lis':
		case 'edi':
			$class = 'bottomBar-panelPrimary';
		break;
		case 'lisDetalle':
		case 'ediDetalle':
			$class = 'bottomBar-panelDetail';
		break;
	}
	
	$ini_barra .= "<div class='$class row'>";
		
        if ($smarty->_tag_stack[$punteroPilaPadre][1]['id']!='fil')
        {
        	$nombre_paginador = $smarty->_tag_stack[$punteroPilaPadre][1]['id']."_paginacion";
        	$llamadasJS = "";
            $llamadasJS .= "if (eval(".$nombre_paginador.")) \n";
        	$llamadasJS .= "{\n";        	
        	$llamadasJS .=$nombre_paginador.".dibujar_enlaces();";        
        	$llamadasJS .= "}\n";        
        	$igepSmarty->addPostScript($llamadasJS);
			$paginacion = "<!-- PAGINACIÓN EN BARRA INFERIOR -->\n";			
			$paginacion .= "<div id='capa_".$nombre_paginador."' >".$igepSmarty->getPostScript();

			$paginacion .= "</div>\n";				
			$paginacion .= "<span id='".$idPanel."_imgModificado' class='glyphicon glyphicon-alert modif-glyphicon-alert' aria-hidden='true'></span> ";
			$paginacion .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";
			
			/*$paginacion .= "&nbsp; <img class='iconMod' id='";
    	    $paginacion .= $idPanel."_imgModificado' src='".IMG_PATH_CUSTOM."avisos/marcaModificado.gif' alt='*' ";
    	    $paginacion .= "title='Página con modificaciones pendientes.' />";*/
    	    
        	$ini_barra .= $paginacion;
        }
		$fin_barra .= "</div>\n";
		return $ini_barra.$content.$fin_barra;		
	}
}
?>