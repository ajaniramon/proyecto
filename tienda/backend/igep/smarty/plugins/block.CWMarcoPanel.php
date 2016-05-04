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

function smarty_block_CWMarcoPanel($params, $content, &$smarty) 
{
	//Puntero a la pila de etiquetas que contiene a CWMarcoPanel 
	$indicePila = count($smarty->_tag_stack)-1;
	//Puntero a la etiqueta Padre (CWVentana) 
	$punteroPilaPadre = $indicePila - 1;
	$pilaCWVentana = $smarty->_tag_stack[$punteroPilaPadre];

	$igepSmarty = new IgepSmarty();

	$botonFil = '';
	$botonLis = '';
	$botonEdi = '';
	$botonEdiDetalle = '';
	$botonLisDetalle = '';
	
	if(!isset($content)) // Si se abre la etiqueta {CWMarcoPanel}...
	{	
		$n_comp = "CWMarcoPanel";	
		// Necesitamos saber cuántas instancias de este componente existen ya / para poner el codigo o no
		$num=$smarty->igepPlugin->registrarInstancia($n_comp);
	} 
	else 
	{
		$llamadas_js = '';
		// Tabla exterior que engloba todo el contenido (pestañas y contenido)
		$ini_tabla = "<!-- INI: CWMarcoPanel -->\n";
		
		$conPestanyas = 'true';
		if ($params['conPestanyas']) 
		{
			$conPestanyas = $params['conPestanyas'];
		}
		
		if ($conPestanyas == 'true')
		{
			$smarty->igepPlugin->registrarInclusionJS('pestanyas.js');
		
			$nomPestanyero = $smarty->_tag_stack[$indicePila][2]['nomPestanyero'];
			$script = '';	
			$script .= "var ".$nomPestanyero." = new oPestanyas(\"".$nomPestanyero."\");\n";
			
			$igepSmarty->addPreScript($script);
			//Registramos el objeto JS
			$smarty->igepPlugin->registerJSObj($nomPestanyero);
			
			$params = '';
			$paramsM = '';
			$paramsD = '';
			if ($smarty->_tag_stack[$indicePila][2]['paramsBotonera'])
			{
				$params = $smarty->_tag_stack[$indicePila][2]['paramsBotonera'];
				$numBotones = explode("__",$params);
			}
			else 
			{
				$paramsM = $smarty->_tag_stack[$indicePila][2]['paramsBotoneraM'];
				$numBotones = explode("__",$paramsM);
				$paramsD = $smarty->_tag_stack[$indicePila][2]['paramsBotoneraD'];
				$numBotonesD = explode("__",$paramsD);
			}
		
			$botonera = "<div class=\"row button-bar\">";
			$botonera .= "<div class=\"btn-group\">";
			for($i=0;$i<count($numBotones);$i++)
			{
				if (trim($numBotones[$i]) != '')
				{
					$param = explode(",",$numBotones[$i]);
					//$nomPestanyero,$tipo,$ruta_img,$class,$mostrar,$ocultar
					$nomPestanya = 'pest_'.$param[1].'_'.$param[0];
					$tipoPanel = $param[1];
					$rutaImg = $param[2];
					$panel = $param[3];
					$classIcon = '';
					switch($panel)
					{
						case "P_fil":
							$titlePestanya = "Nueva búsqueda";
							$classIcon = "glyphicon glyphicon-search toolTipOn";
							$dataTarget = "data-target='#$panel'";
						break;
						case "P_lis":
						case "P_lisDetalle":
							$titlePestanya = "Listado";
							$classIcon = "glyphicon glyphicon-list toolTipOn";
							$dataTarget = "data-target='#$panel'";
						break;
						case "P_edi":
						case "P_ediDetalle":
							$titlePestanya = "Edición";
							$classIcon = "glyphicon glyphicon-edit toolTipOn";
							$dataTarget = "data-target='#$panel'";
						break;
						default:
							$titlePestanya = "";
						break;
					}
					$class = $param[4];
					$mostrar = $param[5];
					$ocultar = $param[6];
					$funcion = '';
					if (trim($ocultar) != '')
					{
						$funcion .= "ocultarPanel('$ocultar');";
					}
					if (trim($mostrar) != '')
					{
						$funcion .= "mostrarPanel('$mostrar');";
					}
		
					$funcion .= $nomPestanyero.".activarPanel('P_".$tipoPanel."',this);";
					switch($panel)
					{
						case "P_edi":
							$botonEdi .= " <button id='$tipoPanel' class='$class btn-botonera' type='button' $dataTarget>";
							$botonEdi .= "<span class='$classIcon'></span> $titlePestanya";
							$botonEdi .= "</button>";
						break;
						case "P_lis":
							$botonLis .= " <button id='$tipoPanel' class='$class btn-botonera' type='button' $dataTarget>";
							$botonLis .= "<span class='$classIcon'></span> $titlePestanya";
							$botonLis .= "</button>";
						break;
						case "P_fil":
							$botonFil .= " <button id='$tipoPanel' class='$class btn-botonera' type='button' $dataTarget>";
							$botonFil .= "<span class='$classIcon'></span> $titlePestanya";
							$botonFil .= "</button>";
						break;
						
						case "P_lisDetalle":
							$botonLisDetalle .= " <button id='$tipoPanel' class='$class btn-botonera' type='button' $dataTarget>";
							$botonLisDetalle .= "<span class='$classIcon'></span> $titlePestanya";
							$botonLisDetalle .= "</button>";
						break;
						
						case "P_ediDetalle":
							$botonEdiDetalle .= " <button id='$tipoPanel' class='$class btn-botonera' type='button' $dataTarget>";
							$botonEdiDetalle .= "<span class='$classIcon'></span> $titlePestanya";
							$botonEdiDetalle .= "</button>";
						break;
					}
				}
			}
			$botonera .= $botonFil.$botonLis.$botonEdi.$botonLisDetalle.$botonEdiDetalle;
			$botonera .= "</div>";
		}
		$botonera .= "</div>";

		$fin_tabla .= "<!-- FIN: CWMarcoPanel -->\n";
		
		
		return $igepSmarty->getPreScript().$llamadas_js.$botonera.$ini_tabla.$content.$fin_tabla."\n";
	}
}
?>