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

function smarty_function_CWPestanya($params, &$smarty)
 {
 	$igepSmarty = new IgepSmarty();			
 	
	$pestanya = "";

	
	//////////////////////////////////////////////////////////////////////
	// LECTURA DE VALORES DE LA PILA //
	//////////////////////////////////////////////////////////////////////
	//Número de elementos de la pila de Blocks
	$punteroPilaPadre = count($smarty->_tag_stack)-1;
	$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];
	////////////////////////////////////////////////////////////////////////////////////
	///// FIN LECTURA DE VALORES DE LA PILA  /////
	////////////////////////////////////////////////////////////////////////////////////
	
	// tipo = ['fil','edi','lis']
	if ($params['tipo'])
	{
		// Nombre del fichero sin extensión y sin estado
		$img_pestanya = "p".$params['tipo']."_"; 
		$tipo = $params['tipo'];
		// Name d la imagen
		$img = "pest_".$params['tipo'];
	}
	else 
	{ 
		$img_pestanya = '';
		$tipo = '';
		$img = ''; 
		$panel = '';
	}
	
	$estado = '';
 	if ($params['estado'])
	{
		$estado = $params['estado'];	
	}
	
	switch($tipo) 
	{
		case "fil":
		case "vSeleccion":
			$titlePestanya = "Búsqueda";
		break;
		case "lis":
			$titlePestanya = "Listado";
		break;
		case "edi":
			$titlePestanya = "Edición";
		break;
		default:
			$titlePestanya = "";
		break;
	}

	$md = false;
	if ($params['panelAsociado']) {
		$md = true;
		$panel = "P_".$params['panelAsociado'];
	}
	else 
	{
		// Nombre del panel correspondiente
		$panel = "P_".$params['tipo'];
	}
	
	if ($params['estado']) {
		// Nombre del fichero sin extensión y con estado (ej. 'pfil_on')
		if ($params['estado'] != 'inactivo') {
			$img_pestanya = $img_pestanya.$params['estado'];
		}
		else 
		{
			$img_pestanya = "pix_trans";
		}
		// Ruta del fichero
		$ruta_img = "pestanyas/".$img_pestanya.".gif";		
	}
	else 
	{ 
		$img_pestanya = '';
		$ruta_img = ''; 
	}
	$smarty->igepPlugin->registrarInclusionJS('pestanyas.js');
	
	$nomPestanyero = $smarty->_tag_stack[$punteroPilaPadre][1]['id'];
	if(($nomPestanyero=="") || ($nomPestanyero==null))
	{
		$n_comp = "CWContenedorPestanyas";
		$num=$smarty->igepPlugin->getNumeroInstancia("CWContenedorPestanyas");		
		$nomPestanyero = $n_comp.$num; 
	}
	
/*	$funcion = '';
	if ($params['ocultar']) 
	{
		$funcion .= "ocultarPanel('".$params['ocultar']."');";
	}	
	if ($params['mostrar']) 
	{
		$funcion .= "mostrarPanel('".$params['mostrar']."');";
	}	
	
	$script = $nomPestanyero."=eval('".$nomPestanyero."');\n";
	$script .= $nomPestanyero.".addPestanya('".$tipo."','".$panel."');\n";		
	$igepSmarty->addPreScript($script); 
	
	$funcion .= $nomPestanyero.".activarPanel('".$panel."',this)";*/

// REVIEW: Vero. Cambio de tablas por capas
	$class = '';
	switch ($estado)
	{
		case 'off':
			$class = 'tab';
		break;
		case 'on':
			$class = 'tab disabled';
		break;
		case 'inactivo':
			$class = 'hiddenTab';
		break;
	}
	$pestanya = "<div id='".$img."_".$nomPestanyero."' class='".$class."'>";
// FIN REVIEW	
	/*$pestanya.= "<img id='".$img."_".$nomPestanyero."' name='".$img."_".$nomPestanyero."' "; 
	$pestanya.="style='cursor: pointer;' title='$titlePestanya' tabindex='10010' class='imgTab_on text'"; 
	$pestanya.=" alt='$tipo' border='0' src=\"".IMG_PATH_CUSTOM.$ruta_img."\" >";
	$pestanya.=" onClick=\"javascript:".$funcion."\" />\n";*/
	//$pestanya .= "$nomPestanyero";
// REVIEW: Vero. Cambio de tablas por capas	
	$pestanya .= "</div>";
	

	//$datosPestanya = "__nomPestanyero=".$nomPestanyero.",tipoPanel=".$tipo.",rutaImg=".$ruta_img.",class=".$class.",mostrar=".$params['mostrar'].",ocultar=".$params['ocultar'];
	$datosPestanya = "__".$nomPestanyero.",".$tipo.",".$ruta_img.",".$panel.",".$class.",".$params['mostrar'].",".$params['ocultar'];
//print_r(' datosPestanya ');print_r($datosPestanya);
	if ($md == false)
		$smarty->_tag_stack[$punteroPilaPadre][1]['datosPestanya'] = $datosPestanya;
	else
	{
		switch ($params['panelAsociado'])
		{
			case 'fil':
			case 'lis':
			case 'edi':
				$smarty->_tag_stack[$punteroPilaPadre][1]['datosPestanyaM'] = $datosPestanya;
			break;
			case 'lisDetalle':
			case 'ediDetalle':
				$smarty->_tag_stack[$punteroPilaPadre][1]['datosPestanyaD'] = $datosPestanya;
			break;
		}
	}
// FIN REVIEW	
	//return $llamadas_js.$igepSmarty->getPreScript().$pestanya;
	return $datosPestanya;	
}
?>