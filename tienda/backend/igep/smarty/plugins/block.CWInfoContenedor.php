<?php 
/* Block: CWInfoContenedor
*
* Copyright (C) 2011 Enlaza Soluciones Informáticas Valencia S.L.
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
*  Enlaza Soluciones Informáticas Valencia
*  C. República Guinea Ecuatorial Nº8, Bajo Izquierda
*  46022 VALENCIA
*  SPAIN
*  +34 96372 41 33
*  enlazasiv@enlazasiv.com
*  www.enlazasiv.com
*
*/
require_once('igep/include/IgepSmarty.php');

function smarty_block_CWInfoContenedor($params, $content, &$smarty) 
{
	$igepSmarty = new IgepSmarty();
	
	///////////////////////////////////
	// LECTURA DE VALORES DE LA PILA //
	///////////////////////////////////
	//Puntero a la pila de etiquetas que contiene a CWTabla/CWFicha
	$punteroPilaPadre = count($smarty->_tag_stack)-1;
	$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];
	//Puntero a la etiqueta Abuelo
	$punteroPilaAbuelo = $punteroPilaPadre - 1;
	$CWAbuelo = $smarty->_tag_stack[$punteroPilaAbuelo][0];
/*	$CWSelector = false;
	if ($CWPadre == "CWSelector") $CWSelector = true;
	
	//Si el padre es un CWSelector y el abuelo es solapa,
	//tenemos que movernos dos más arriba
	if (($CWPadre == "CWSelector") && ($CWAbuelo == "CWSolapa"))
	{
		$punteroPilaPadre = count($smarty->_tag_stack)-3;
		$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];
		$punteroPilaAbuelo = $punteroPilaPadre - 1;
		$CWAbuelo = $smarty->_tag_stack[$punteroPilaAbuelo][0];
	}
	//Si el padre es un CWSolapa, tenemos que movernos uno más arriba
	if (($CWPadre == "CWSelector") || ($CWPadre == "CWSolapa"))
	{
		$punteroPilaPadre = count($smarty->_tag_stack)-2;
		$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];
		$punteroPilaAbuelo = $punteroPilaPadre - 1;
		$CWAbuelo = $smarty->_tag_stack[$punteroPilaAbuelo][0];
	}*/
	
	if ($CWAbuelo == 'CWContenedor') //Estamos en un panel de búsqueda
	{
		$punteroPilaPanel = $punteroPilaAbuelo - 1;
	}
	else
	{
		$punteroPilaPanel = $punteroPilaAbuelo - 2;
	}
	$idPanel = $smarty->_tag_stack[$punteroPilaPanel][1]['id'];

	if(!isset($content)) // Si se abre la etiqueta {CWInfoContenedor}...
	{	
		$n_comp = "CWInfoContenedor";	
		// Necesitamos saber cuántas instancias de este componente existen ya / para poner el codigo o no
		$num = $smarty->igepPlugin->registrarInstancia($n_comp);
	} 
	else 
	{	
		//$ini_contenedor = "<div id='infoMsg'>";
			$ini_contenedor = "<div class='infoIco'>";
				$ini_contenedor .= "<a href='#'  id='info_$idPanel' data-target='#txtMsg_$idPanel'>";
				$ini_contenedor .= "<span class='glyphicon glyphicon-info-sign btnToolTip'></span></a>";
			$ini_contenedor .= "</div>";
			$ini_contenedor .= "<div id='txtMsg_$idPanel' class='help'>";
			$fin_contenedor .= "</div>";		
		//$fin_contenedor .= "</div>";

		return $ini_contenedor . $content . $fin_contenedor;
	}
}
?>