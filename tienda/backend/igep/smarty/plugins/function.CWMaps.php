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

function smarty_function_CWMaps($params, &$smarty)
{
	
	$igepSmarty = new IgepSmarty();
	$punteroPilaPadre = count($smarty->_tag_stack)-1;
	$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];
	//Puntero a la etiqueta Abuelo
	$punteroPilaAbuelo = $punteroPilaPadre - 1;
	$CWAbuelo = $smarty->_tag_stack[$punteroPilaAbuelo][0];
	
	$CWSelector = false;
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
	}
	
	if ($CWAbuelo == 'CWContenedor') //Estamos en un panel de búsqueda
	{
		$punteroPilaPanel = $punteroPilaAbuelo - 1;
	}
	else
	{
		$punteroPilaPanel = $punteroPilaAbuelo - 2;
	}
	
	
	$idPanel = $smarty->_tag_stack[$punteroPilaPanel][1]['id'];	

	// **********************
	// RECOGIDA DE PARÁMETROS
	// **********************
	// Primero defino el nombre del componente.
	$n_comp="CWMaps";
	// Incrementamos  el número de componentes CWCampoTexto
	$num = $smarty->igepPlugin->registrarInstancia($n_comp);
	
	// Tiene nombre? no tiene? Le asigno uno en ese caso
	if($params['nombre'])
		$idCampo=$params['nombre'];
	else
		// Por defecto, nombre plugin y número de instancia del componente
		$idCampo=$n_comp.$num;

	//Recuperamos parametros coordX y coordY
	$coordX = 0;
	if ($params['coordX']) {
		$coordX = $params['coordX'];
		$idCoordX = $coordX;
	}
	$coordY = 0;
	if ($params['coordY']) {
		$coordY = $params['coordY'];
		$idCoordY = $coordY;
	}

	$zoom = '10';
	if ($params['zoom']) {
		$zoom = $params['zoom'];
	}
	
	$proyeccion = 'EPSG:4326';
	if ($params['proyeccion']) {
		$proyeccion = $params['proyeccion'];
	}
        
        
    //Parametro para indicar el visor que se quiere utilizar
	if($params['visor'])
            $visor=strtolower($params['visor']);
	else
            // Por defecto el visor del ICV
            $visor='streetmaps';

	$textoAsociado = '';
	//Si hay etiqueta asociada...
	if (
		(isset($params['textoAsociado']))
		&& (trim($params['textoAsociado']) !='')
		)
	{
		$textoAsociado = $params['textoAsociado'];
	}

	//Para el caso de ICVGeo se debe indicar una url
	$url = '';
	if (
		(isset($params['url']))
		&& (trim($params['url']) !='')
		)
	{
		$url = $params['url'];
	}
        
	// Cálculo iteración
	$iterActual = $smarty->_tag_stack[$punteroPilaPadre][2];		
	$idFila = $smarty->_tag_stack[$punteroPilaAbuelo][1]['id']."_".$iterActual;
	$numRegTotales = count($smarty->_tag_stack[$punteroPilaAbuelo][1]['datos']);

	if (($CWPadre == "CWFila") || ($CWAbuelo == "CWFichaEdicion"))
	{
		// MODO INSERCIÓN
		if ($iterActual >= $numRegTotales) {
			$idCampo = "ins___".$idCampo."___".$idFila;
			$idCoordX = "ins___".$coordX."___".$idFila;
			$idCoordY = "ins___".$coordY."___".$idFila;
		}
		else {// MODO EDICIÓN
			$idCampo = "cam___".$idCampo."___".$idFila;
			$idCoordX = "cam___".$coordX."___".$idFila;
			$idCoordY = "cam___".$coordY."___".$idFila;
		}
	}
	
	
	//Cargamos las librerias JQUERY

    $smarty->igepPlugin->registrarInclusionJS('jquery-ui.min.js','igep/smarty/plugins/jquery/jquery-ui-1.10.3/ui/minified/');
    $smarty->igepPlugin->registrarInclusionCSS('jquery-ui.css','igep/smarty/plugins/jquery/jquery-ui-1.10.3/themes/base/');
        

    //Comienza la construcción del componente
    $componente = '';
    if($visor=='icv') {
        $smarty->igepPlugin->registrarInclusionJS('icvgeo.js','igep/smarty/plugins/gvh_maps/');
        $variables="url='".$url."';";
    }
    
    if($visor=='google') {
    	$smarty->igepPlugin->registrarInclusionJS('googlemaps.js','igep/smarty/plugins/gvh_maps/');
    	$variables="url='".$url."';";
    }
    
    if($visor=='streetmaps') {
    	/*$smarty->igepPlugin->registrarInclusionJS('OpenLayers.js','igep/smarty/plugins/jquery/jquery-ui-1.10.3/');
   		$smarty->igepPlugin->registrarInclusionJS('PosicionarMapa.js','igep/smarty/plugins/jquery/jquery-ui-1.10.3/');*/
   		$smarty->igepPlugin->registrarInclusionJS('openstreetmaps.js','igep/smarty/plugins/gvh_maps/');
   		$smarty->igepPlugin->registrarInclusionJS('proj4js-compressed.js','igep/smarty/plugins/gvh_maps/');
    	$variables="url='".$url."';";
    }
 
  
   	// Creamos una capa para poder ocultar y visualizar el botón tooltip de la ventana de selección.
   	// Debería ser un botón como el del calendario.
   	$aperturaCapa = "<span id='IGEPVisibleBtn".$campo."'";
   	$aperturaCapa .=" style='visibility:visible;'>";//Fin style
   	$cierreCapa = '</span>';
   
   	// Comprobación en una tabla para las filas q serán para insertar nuevos registros pondremos el botón ToolTip en transparente
   	if ( ($CWPadre == 'CWFila') && ($iterActual >= $numRegTotales) )
   	{
   		$componente.='<button onclick="visor(\''.$idCoordX.'\',\''.$idCoordY.'\',\''.$zoom.'\',\''.$proyeccion.'\');" data-gvhposition="panel_'.$idPanel.'" class="btnToolTip off" type="button" name="bntmapa" id=\"'.$idCampo.'\" style="display:inline;"><span style="font-size:18px" class="glyphicon glyphicon-globe" aria-hidden="true"></span>&nbsp;'.$textoAsociado.'</button>';
  	}
    elseif (( ($CWPadre == 'CWFila') || ($CWAbuelo == 'CWFichaEdicion') )
    			&& ($smarty->_tag_stack[$punteroPilaPanel][1]['accion'] != 'modificar')
    			&& ($smarty->_tag_stack[$punteroPilaPanel][1]['accion'] != 'insertar')
    	)
    {	// Botón desactivado pq el panel está desactivado
    	$componente.='<button onclick="visor(\''.$idCoordX.'\',\''.$idCoordY.'\',\''.$zoom.'\',\''.$proyeccion.'\');" data-gvhposition="panel_'.$idPanel.'" class="btnToolTip disabled" type="button" name="bntmapa" id=\"'.$idCampo.'\" style="display:inline;"><span style="font-size:18px" class="glyphicon glyphicon-globe" aria-hidden="true"></span>&nbsp;'.$textoAsociado.'</button>';
    }
    else
    {// Botón activado pq el panel está activado
    	$componente.='<button onclick="visor(\''.$idCoordX.'\',\''.$idCoordY.'\',\''.$zoom.'\',\''.$proyeccion.'\');" data-gvhposition="panel_'.$idPanel.'" class="btnToolTip" type="button" name="bntmapa" id=\"'.$idCampo.'\" style="display:inline;"><span style="font-size:18px" class="glyphicon glyphicon-globe" aria-hidden="true"></span>&nbsp;'.$textoAsociado.'</button>';
    }
    $boton = $aperturaCapa.$componente.$cierreCapa;
    
    
    //$componente.='<button onclick="'.$variables.'" onmouseout="this.className=\'button\';" onmouseover="this.className=\'button_on\';" class="button" type="button" name="bntmapa" id="bnedi_guardar" style="display:inline;"><img title="Mapa" alt="Mapa" style="border-style:none;" src="igep/custom/default/images/acciones/mapa.png">'.$textoAsociado.'</button>';
     
   return ($ini.$igepSmarty->getPreScript().$boton.$fin);
}
?>
