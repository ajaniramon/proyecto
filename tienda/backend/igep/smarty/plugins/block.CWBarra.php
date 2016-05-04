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
* Pluggin CWBarra
*
* Componente barra
* @author  David Pascual <pascual_dav@gva.es>
* @author  Keka <bermejo_mjo@gva.es>
* @author  Toni Félix <felix_ant@gva.es> 
* @author  Raquel Borjabad <borjabad_raq@gva.es>
* @author  Verónica Navarro <navarro_ver@gva.es>
* 
*/

function smarty_block_CWBarra($params, $content, &$smarty)
{
	
	if(!isset($content)) // Si se abre la etiqueta {CWBarra}...
	{
		//NADA
	} 
	else 
	{
		///////////////////////////////////
		// LECTURA DE VALORES DE LA PILA //
		///////////////////////////////////
		//Puntero a la pila de etiquetas que contiene a CWBarra
		$punteroPilaPadre = count($smarty->_tag_stack)-2;
		$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];
		
		$smarty->igepPlugin->registrarInclusionJS('pantallaInicio.js');
		
		// Parámetro que nos indicará si es una ventana modal o no.
		/*************** MODAL ****************/
		$modal = false;
		if (($params['modal']) && ($params['modal'] == true) && ($params['modal'] == 'true'))
		{
			$modal = true;
		}
		$smarty->_tag_stack[$punteroPilaPadre][1]['modal'] = $modal;
		/*************** FIN MODAL ****************/
			
		$alt = 'GVA';
		// textAlt parámetro que permitirá modificar el texto alternativo del logo
		if ($params['textAltLogo'])
			$alt = $params['textAltLogo'];

		
		$barTitle = '';
		if ($params['customTitle'])
    	{    		
    		if (trim($params['customTitle']))
    			$barTitle = trim($params['customTitle']);
    	}    	
    	if ($params['barTitle'])
    	{
    		if (trim($params['barTitle']))
    			$barTitle = trim($params['barTitle']);
    	}
		
    		
			$ini_barra .= "<div class='top-bar text-center row'>\n";
		
			$ini_barra .= "<div class='col-xs-12 col-sm-12 col-md-1 text-left'>";
			$ini_barra .= "<div id='logoBar'><img src='".IMG_PATH_CUSTOM."logos/logoMenu.gif' class='logoBar' alt='$alt' title='$alt' /></div>";
			$fin_barra .= "</div>";

			$fin_barra .= "<div class='col-xs-7 col-sm-8 col-md-9 text-center'>".$params['codigo']."</div>\n";
						

			$fin_barra .= "<div class='text-right panel-close col-xs-4 col-md-2 panel-info' id='toolBar'>";	
		
			$fin_barra .= "<script type='text/javascript'>$(document).ready(function(){ $('[data-toggle=\"popover\"]').popover({ html : true });}); </script>";
		
		
    	if ($modal == false)
		{
	    	$fin_barra .= "<form  name='cerrar' id='cerrar' target='oculto' method='get' action='phrame.php'>";
	    	
	    	if ($params['iconInfo'])
	    	{
	    		$fin_barra .= "<button type='button' class='btnToolTip' data-toggle='popover' data-placement='bottom' title=' <p class=\"text-center title-info \">";
	    		$fin_barra .= "<b>INFORMACIÓN</b></p>' ";
	    		$fin_barra .= "data-content='<table class=\"table table-striped\"><tr><td><b>Usuario</b></td><td> ".$params['usuario']."</td></td></tr> <tr><td><b>Nombre Aplicación</b></td><td>".$params['codigo']."</td></tr>   <tr><td><b>Versión</b></td><td> ".$versionApl."</td></tr></table>'>";
	    		$fin_barra .= "<span class='".$params['iconInfo']."' aria-hidden='true'></span></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";	
	    	}
	    	else {
	    		$fin_barra .= "<img src='".IMG_PATH_CUSTOM."botones/33.gif' class='btnClose' data-toggle='popover' data-placement='bottom' style='margin-right:15px;' title=' <p class=\"text-center title-info \" />";
	    		$fin_barra .= "<b>INFORMACIÓN</b></p>' ";
	    		$fin_barra .= "data-content='<table class=\"table table-striped\"><tr><td><b>Usuario</b></td><td> ".$params['usuario']."</td></td></tr> <tr><td><b>Nombre Aplicación</b></td><td>".$params['codigo']."</td></tr>   <tr><td><b>Versión</b></td><td> ".$versionApl."</td></tr></table>'>";
	    		 
	    	}
	    
		   	$fin_barra .= "<input type='hidden' id='permitirCerrarAplicacion' name='permitirCerrarAplicacion' value='si' />";	   				
		   	if ($params['iconHome'])
		   	{
						$capa_js = "onClick=\"javascript:{";
						$capa_js .= "if (document.getElementById('permitirCerrarAplicacion').value!='si') {";
						$capa_js .= "aviso.set('aviso','capaAviso',";
						$capa_js .= "'ALERTA', ";
						$capa_js .= "'IGEP IU', ";
						$capa_js .= "'Cambios pendientes',";
						$capa_js .= "'Existen datos pendientes de salvar. <br/>SALVE o CANCELE los mismos antes de salir.');";				
						$capa_js .= "aviso.mostrarAviso();";
						$capa_js .= "} else {";
						$capa_js .= "parent.location = '?view=igep/views/aplicacion.php';";
						$capa_js .= "}";
						$capa_js .= "}";
				$fin_barra .= "<button type='button' id='home' title='home' style='display:inline;' class='btnToolTip' ".$capa_js."\">\n";
				$fin_barra .= "<span class='".$params['iconHome']."' aria-hidden='true'></span> ";
				$fin_barra .= "</button>";	
		   	}
		   	else 
		   	{
		   		
				if (!$params['volverInicio'])
				{
					$fin_barra .= "<a tabindex='-1' href='#'  ";
						$capa_js = "onClick=\"javascript:{";
						$capa_js .= "if (document.getElementById('permitirCerrarAplicacion').value!='si') {";
						$capa_js .= "aviso.set('aviso','capaAviso',";
						$capa_js .= "'ALERTA', ";
						$capa_js .= "'IGEP IU', ";
						$capa_js .= "'Cambios pendientes',";
						$capa_js .= "'Existen datos pendientes de salvar. <br/>SALVE o CANCELE los mismos antes de salir.');";				
						$capa_js .= "aviso.mostrarAviso();";
						$capa_js .= "} else {";
						$capa_js .= "parent.location = '?view=igep/views/aplicacion.php';";
						$capa_js .= "}";
						$capa_js .= "}";
					$fin_barra.= $capa_js.'">';
					$fin_barra .= "<img src='".IMG_PATH_CUSTOM."botones/cerrarTr.gif' class='btnClose' title='Inicio' alt='Inicio' />";
					$fin_barra .="</a>\n";
				}
				$capa_js="";
		   	}
		   	
	
			//iconOut iconHome
			if ($params['iconOut'])
			{
				$fin_barra .= "<button type='button' id='close' title='Salir' style='display:inline;' class='btnToolTip' onClick=\"javascript:cerrarAplicacion(document.forms['cerrar']);\">\n";
				$fin_barra .= "<span class='".$params['iconOut']."' aria-hidden='true'></span> ";
				$fin_barra .= "</button>";				
			}
			else 
			{
				$fin_barra .= "<a tabindex='-1' href='#' ";
					$capa_js = "onClick=\"javascript:";
					$capa_js .= "cerrarAplicacion(document.forms['cerrar']);";
		   		$fin_barra.= $capa_js."\" >";
		   		$fin_barra .= "<img src='".IMG_PATH_CUSTOM."botones/28tr.gif' class='btnCloseApp' title='Cerrar la aplicación' alt='&lt;-|' />";	   		
		   		$fin_barra .="</a>&nbsp;\n";				
			}

			$fin_barra .= "<input type='hidden' id='action' name='action' value='cerrarAplicacion' />";
	   		$fin_barra .= "</form>\n"; 
		}
		
		$fin_barra .= "</div>\n";
		/*$fin_barra .= "\n<!-- ************* JAVASCRIPT *************** -->\n";		
		$fin_barra .= "<script type='text/javascript'>actualizaFechaHora();</script>\n";
		$fin_barra .= "<!-- **************************************** -->\n\n";*/
		
		$fin_barra .= "</div>\n";
		if ($modal == false)
			return $ini_barra.$content.$fin_barra;
		else 
			return $ini_barra.$fin_barra;	
	}//FIN else isset
}//Fin funcion
?>