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

function smarty_function_CWInformation($params, &$smarty)
{
	/**
	 devuelve una cadena para mostrar el componente en html e incluir[linkar] ficheros en su caso

	 parametros
	 @param disabled boolean
	 @param
	 @returns string
	 */

	$igepSmarty = new IgepSmarty();

	$smarty->igepPlugin->registrarInclusionCSS('information.css', 'igep/css/');
	//////////////////////////////////////////////////////////////////////
	// LECTURA DE VALORES DE LA PILA //
	//////////////////////////////////////////////////////////////////////
	//Puntero a la pila de etiquetas que contiene a CWFila
	$punteroPilaPadre = count($smarty->_tag_stack)-1;
	$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];
	//Puntero a la etiqueta Abuelo
	$punteroPilaAbuelo = $punteroPilaPadre - 1;
	$CWAbuelo = $smarty->_tag_stack[$punteroPilaAbuelo][0];

	$CWSelector = false;
	if ($CWPadre == "CWSelector") $CWSelector = true;

	if (($CWPadre == "CWSelector") && ($CWAbuelo == "CWSolapa"))//Si el padre es un CWSolapa, tenemos que movernos uno más arriba, pq pasamos de el
	{
		$punteroPilaPadre = count($smarty->_tag_stack)-3;
		$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];
		$punteroPilaAbuelo = $punteroPilaPadre - 1;
		$CWAbuelo = $smarty->_tag_stack[$punteroPilaAbuelo][0];
	}
	if (($CWPadre == "CWSelector") || ($CWPadre == "CWSolapa"))//Si el padre es un CWSolapa, tenemos que movernos uno más arriba, pq pasamos de el
	{
		$punteroPilaPadre = count($smarty->_tag_stack)-2;
		$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];
		$punteroPilaAbuelo = $punteroPilaPadre - 1;
		$CWAbuelo = $smarty->_tag_stack[$punteroPilaAbuelo][0];
	}


	if ($CWAbuelo == 'CWContenedor')  //stamos en un panel de búsqueda
	{
		$punteroPilaPanel = $punteroPilaAbuelo - 1;
	}
	else
	{
		$punteroPilaPanel = $punteroPilaAbuelo - 2;
	}
	$idPanel = $smarty->_tag_stack[$punteroPilaPanel][1]['id'];


	////////////////////////////////////////////////////////////////////////////////////////////////
	// CODIGO NECESARIO PARA CADA COMPONENTE //
	////////////////////////////////////////////////////////////////////////////////////////////////
	// Primero defino el nombre del componente.
	$n_comp="CWInformation";

	// Incrementamos  el número de componentes CWCampoTexto
	$num = $smarty->igepPlugin->registrarInstancia($n_comp);

	if($params['nombre'])
	{
		$idCampo=$params['nombre'];
	}
	else
	{
		$idCampo=$n_comp.$num;
	}
	// Imagen del botón
	$ruta = '';
	if ($params['imagen'])
		$ruta = IMG_PATH_CUSTOM."botones/".$params['imagen'].".gif";
	$iconCSS = '';
	if ($params['iconCSS'])
		$iconCSS = $params['iconCSS'];
	// texto informativo
	$value = '';	
	if($params['value'])
	{
		if ((!is_array($params['value'])) && ($params['value'] != ''))
			$value = html_entity_decode($params['value'], ENT_COMPAT | ENT_HTML401, 'ISO-8859-1');
		else
			$value = $params['value'];
	}	
		
	//////////////////////////////////////////////////////////////////////////////////////////////////
	// FIN CODIGO NECESARIO DE CADA COMPONENTE //
	/////////////////////////////////////////////////////////////////////////////////////////////////
	
	$ini = "";
	$fin = "";
	$hiddentxt = "";

	//Si el padre es una fila o una ficha....
	if (($CWPadre == "CWFila") || ($CWAbuelo == "CWFichaEdicion"))
	{
		$iterActual = $smarty->_tag_stack[$punteroPilaPadre][2];
//print_r($iterActual);		
		$numRegTotales = count($smarty->_tag_stack[$punteroPilaAbuelo][1]['datos']);
		$iterActualExtra=0;
		$numRegTotalesExtras=0;

		$idFila = $smarty->_tag_stack[$punteroPilaAbuelo][1]['id']."_".$iterActual;
//print_r($idFila);
		// TAMAÑO COLUMNAS
		// Hay que almacenar en el padre (CWFila) el tamaño del campo para poder fijar el ancho de las columnas
		if (($CWPadre == "CWFila"))
		{
			if (($params['size']))
				$smarty->_tag_stack[$punteroPilaAbuelo][6]['sizes'][] = strlen($params['size']);
			else
				// Si no se le pasa tamaño, se toma el tamaño de la imagen para el ancho de la columna
				$smarty->_tag_stack[$punteroPilaAbuelo][6]['sizes'][] = 28;
			if ($iterActual == 0)
			{
				$v_titulo = $smarty->_tag_stack[$punteroPilaAbuelo][1]['titulosColumnas'];				
				$v_titulo['information'] = "";
				$smarty->_tag_stack[$punteroPilaAbuelo][1]['titulosColumnas'] = $v_titulo;
			}
		}
		
		if ($iterActual >= $numRegTotales)
		{
			//$contInsercion = $iterActual - $numRegTotales;
			// Componer el nombre dl campo: params[nombre]_idFila
			// ej: cad_inv_dni__F_tabla1_2
			$idCampo = "ins___".$idCampo."___".$idFila;
			if ($CWPadre == "CWFila")
			{
				$ini = "<td align='center'>\n";
				$fin = "</td>\n";
			}
			$return = $ini."&nbsp;".$fin;
		}
		else
		{
			// Componer el nombre dl campo: params[nombre]_idFila
			// ej: cad_inv_dni__F_tabla1_2
			$idCampo = "cam___".$idCampo."___".$idFila;
				
			//Si el padre es una fila, incluimos los TD
			if ($CWPadre == "CWFila")
			{
				$ini = "<td align='center'>\n";
				$fin = "</td>\n";
				$campoEstadoFila = "est_".$idFila;
			}
			if ($CWPadre == "CWFicha")
			{
				
			}

			//Obtenemos el registro que le corresponde y fijamos el valor			
			$datosReg = $smarty->_tag_stack[$punteroPilaAbuelo][1]['datos'][$iterActual];
		
			//Asignamos el valor de registro, controlando el PEAR:DB
			$valueReg=null;
			if ($value == '')
			{
				//Si es la nueva version de PEAR, da igual que sea Postgres que Oracle (columna en minúsculas)
				if (!isset($valueReg)) $valueReg = $datosReg[strtolower($params['nombre'])];
				//Si aqui aun no tiene valor, puede ser Pear "case sensitive" contra Postgres (columna mayúscula/minúsculas)
				if (!isset($valueReg)) $valueReg = $datosReg[$params['nombre']];
				//Por último, si aquí tampoco tiene valor, puede ser Pear "case sensitive" contra Oracle (columna mayúsculas)
				if (!isset($valueReg)) $valueReg = $datosReg[strtoupper ($params['nombre'])];
				// Fin del proceso de asignación del valor
				if (isset($valueReg) && ($valueReg!="")) $value=html_entity_decode($valueReg, ENT_COMPAT | ENT_HTML401, 'ISO-8859-1');
			}
			if (($valueReg == null) && ($value == '')) $value='';

			//$img = "<div id='$idCampo'>";
			$idBallon = 'infBallon_'.$idFila;
			
			if ($iconCSS != '')
			{
				$img .= "<button type='button' id='infImg_$idCampo' title='$texto' style='display:inline;' class='btnToolTip' onClick='showdiv(event,$idBallon);';>\n";
				$img .= "<span class='".$params['iconCSS']."' aria-hidden='true'></span> ";
				$img .= "</button>";
			}
			else
				$img .= "<img id='infImg_$idCampo' src='$ruta' onClick='showdiv(event,$idBallon);'; style='cursor: pointer;border-style:none;vertical-align:middle;' alt='$texto' title='$texto'/>\n";
			//$img .= "<img src='$ruta' onClick='javascript:document.getElementById(\"txtInformation\").style.display=\"block\"'; style='border-style:none;vertical-align:middle;' alt='$texto' title='$texto'/>\n";
			//$img .= "</div>";
			$inf = "<div id='infBallon_".$idFila."' class='ballon' style='display:none;' onmouseout='this.style.display=\"none\"' onClick='javascript:document.getElementById(\"txtInformation\").style.display=\"none\"'>";
			$inf .= "<div class='info_arrow-before'></div>";
			$inf .= "<div class='info_arrow-after'></div>";	
			$inf .= "<div id='infTxt_".$idFila."' class='info_container'>$value</div>";
			$inf .= "</div>";
			
			$return = $ini.$img.$fin.$inf;
		}

	}
	
	return($return);	
}
?>