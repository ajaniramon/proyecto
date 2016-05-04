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

function smarty_function_CWImagen($params, &$smarty) {
	
	$igepSmarty = new IgepSmarty();
	
	////////////////////////////////////////////////////////////////
	// LECTURA DE VALORES DE LA PILA //
	////////////////////////////////////////////////////////////////
	//Puntero a la pila de etiquetas que contiene a CWFila

	$punteroPilaPadre = count($smarty->_tag_stack)-1;
	$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];
	//Puntero a la etiqueta Abuelo
	$punteroPilaAbuelo = $punteroPilaPadre - 1;
	$CWAbuelo = $smarty->_tag_stack[$punteroPilaAbuelo][0];
	
	//Si el padre es un CWSolapa, tenemos que movernos uno más arriba, pq pasamos de el
	if (($CWPadre == "CWSelector") || ($CWPadre == "CWSolapa"))
	{
		$punteroPilaPadre = count($smarty->_tag_stack)-2;
		$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];
		$punteroPilaAbuelo = $punteroPilaPadre - 1;
		$CWAbuelo = $smarty->_tag_stack[$punteroPilaAbuelo][0];
	}
	
	$punteroPilaPanel = $punteroPilaAbuelo - 2;
	$idPanel = $smarty->_tag_stack[$punteroPilaPanel][1]['id'];
	
	
	////////////////////////////////////////////////////////////////
	// CODIGO NECESARIO PARA CADA COMPONENTE //
	////////////////////////////////////////////////////////////////
	// Primero defino el nombre del componente.
	$n_comp="CWImagen"; 
	// Incrementamos	el número de componentes CWImagen
	$num = $smarty->igepPlugin->registrarInstancia($n_comp);


	// Tiene nombre? no tiene? Le asigno uno en ese caso
	if($params['nombre'])
	{
		$id=$params['nombre'];
	}
	else 
	{
		// Por defecto, nombre plugin y número de instancia del componente
		$id=$n_comp.$num; 
	} 
	 
	$src = '';
	if(isset($params['src'])) 
	{
		$src=$params['src'];		
	}
	
	// Bumpbox 
	$bumpbox = false;
	if (isset($params['bumpbox']))
	{
		$bumpbox = true;	
	}

	$width = '';
	$wBump = 1;
	if(isset($params['width']))
	{
		$wBump = $params['width'];
		$width=" width='".$params['width']."'";	 
	}
	
	$height = '';
	$hBump = 1;
	if(isset($params['height'])) 
	{
		$hBump = $params['height'];
		$height=" height='".$params['height']."'";	 
	}

	//Alternativa
	$alt=" alt='imagen' ";
	if(isset($params['alt']))
	{
		$alt=" alt='".$params['alt']."'"; 
	}
	

	//Visibilidad inicial
	$visible = true;
	if (
		(isset($params['visible']))		
		&&
		(
			(strtolower(trim($params['visible'])) == 'false') 
			|| (strtolower(trim($params['visible'])) == 'falso')
			|| (strtolower(trim($params['visible'])) == 'no')
			|| (strtolower(trim($params['visible'])) == 'oculto')
			|| (strtolower(trim($params['visible'])) == 'invisible')
			|| ($params['visible'] === false)
		)
	)
	{
		$visible = false;
	}
	
	// --- Tratamiento de etiquetas asociadas
	$textoAsociado = "";
	$mostrarTextoAsociado = "false";
	//Si hay etiqueta asociada...
	if (isset($params['textoAsociado']))
	{
		$textoAsociado = $params['textoAsociado'];
		$mostrarTextoAsociado = "true";
	}
	else
	{
		$textoAsociado = ucfirst($idCampo);
		$mostrarTextoAsociado = "false";
	}
	if (isset($params['mostrarTextoAsociado']))
	{
		$mostrarTextoAsociado = trim(strtolower($params['mostrarTextoAsociado']));
	}
	switch ($mostrarTextoAsociado)
	{
		case 'no':
		case 'false':
			$mostrarTextoAsociado = "display:none; ";
		break;
		case 'si':
		case 'true':
			$mostrarTextoAsociado = "display:inline; ";
		break;
		default:
			$mostrarTextoAsociado = "display:inline; ";
	}//Fin switch
	// --- tratamiento de etiquetas
	
	$ini = "";
	$fin = "";
	$hiddentxt = "";
	
	//Si el padre es una fila o el abuelo es una fichaEdicion....
	// Comprobamos lo dl abuelo en vez d q el padre sea CWFicha pq una ficha ahora puede estar dentro de otro componente
	// p.ej (CWContenedor) para el panel de búsqueda
	if (($CWPadre == "CWFila") || $CWAbuelo == "CWFichaEdicion")
	{
		$iterActual = $smarty->_tag_stack[$punteroPilaPadre][2];
		$numRegTotales = count($smarty->_tag_stack[$punteroPilaAbuelo][1]['datos']);
		$iterActualExtra=0;
		$numRegTotalesExtras=0;

		$idFila = $smarty->_tag_stack[$punteroPilaAbuelo][1]['id']."_".$iterActual;
	
		// 	TAMAÑO COLUMNAS
		if ( ($CWPadre == "CWFila") && (isset($params['width'])) && ($iterActual == 0) )
		{
			// Hay que almacenar el tamaño del campo para poder fijar el ancho de las columnas
			$smarty->_tag_stack[$punteroPilaAbuelo][6]['sizes'][] = $params['width'];
			$smarty->_tag_stack[$punteroPilaAbuelo][6]['sizes']['total'] += intval($params['width']);
		}
		
		if ($iterActual >= $numRegTotales)
		{
			//$contInsercion = $iterActual - $numRegTotales;
			// Componer el nombre dl campo: params[nombre]_idFila
			// ej: cad_inv_dni__F_tabla1_2
			$id = "ins___".$id."___".$idFila;
			if ($CWPadre == "CWFila")
			{
				$ini = "<td align='center'>\n";
				$fin = "</td>\n";
				$campoEstadoFila = "est_".$idFila;
				//$llamadaFuncion = $idPanel."_tabla.cambiarEstado";
				$llamadaJS = $idPanel."_tabla.cambiarEstado('insertada',this,'".$campoEstadoFila."');";
				$igepSmarty->addAccionEvento("onBlur", $llamadaJS, 1);
				//Si es la primera iteración añadimos el titulo
				if($iterActual == 0)
				{
					$referencia = $params['nombre'];
					if (empty($textoAsociado)) $textoAsociado = $referencia;
					$v_titulo = $smarty->_tag_stack[$punteroPilaAbuelo][1]['titulosColumnas'];
					$v_titulo[$referencia] = $textoAsociado;
					$smarty->_tag_stack[$punteroPilaAbuelo][1]['titulosColumnas'] = $v_titulo;
				}
			}
			if ($CWPadre == "CWFicha")
			{
				$campoEstadoFila = "est_".$idFila;
				$llamadaJS = "document.getElementById('".$campoEstadoFila."').value='insertada';";
			}
		}
		else
		{
			// Componer el nombre dl campo: params[nombre]_idFila
			// ej: cad_inv_dni__F_tabla1_2
			$id = "cam___".$id."___".$idFila;
			
			if ($CWPadre == "CWFila")
			{
				$ini = "<td align='center'>\n";			 
				$fin = "</td>\n";
				//Si es la primera iteración añadimos el titulo
				if ($iterActual == 0)
				{
					$referencia = $params['nombre'];
					if (empty($textoAsociado)) $textoAsociado = $referencia;
					$v_titulo = $smarty->_tag_stack[$punteroPilaAbuelo][1]['titulosColumnas'];				
					$v_titulo[$referencia] = $textoAsociado;
					$smarty->_tag_stack[$punteroPilaAbuelo][1]['titulosColumnas'] = $v_titulo;
				}
			}
			
			//Obtenemos el registro que le corresponde y fijamos el valor
			$datosReg = $smarty->_tag_stack[$punteroPilaAbuelo][1]['datos'][$iterActual];		 
			
			// REVIEW: Vero 4/11/2010 Modificación para la nueva ventana de selección.
			// La ruta a la imagen (src) puede venir dada por el programador como parámetro en la tpl
			// o puede ser el resultado de una consulta de BD, por lo tanto la sacamos de $datosReg
//			if (empty($src))
//				$src = $datosReg['ruta'];
				
			//Asignamos el valor de registro, controlando el PEAR:DB
			$valueReg=null;			
//			Si es la nueva version de PEAR, da igual que sea Postgres que Oracle (columna en minúsculas)			
			if (!isset($valueReg)) $valueReg = $datosReg[strtolower($params['nombre'])];
			//Si aqui aun no tiene valor, puede ser Pear "case sensitive" contra Postgres (columna mayúscula/minúsculas)
			if (!isset($valueReg)) $valueReg = $datosReg[$params['nombre']];
			//Por último, si aquí tampoco tiene valor, puede ser Pear "case sensitive" contra Oracle (columna mayúsculas)
			if (!isset($valueReg)) $valueReg = $datosReg[strtoupper ($params['nombre'])];			
			//Ahora comprobamos si tiene un valor impuesto por parámetro, que tiene preferencia...	
			if (isset($valueReg) && ($valueReg!=null) && $valueReg!="") $src=htmlspecialchars($valueReg);
//print_r($datosReg);
//die;				
			// Fin del proceso de asignación del valor
		}//Fin if-else iteraciones
	} //Fin if (($CWAbuelo == "CWFichaEdicion")

	$aperturaCapa = '';
	$cierreCapa = '';	
	if ($visible == false)
	{
		$estiloVisibilidad = 'visibility:hidden;';
	}
	else
	{
		$estiloVisibilidad = 'visibility:visible;';
	}
	if (($iterActual >= $numRegTotales) && ($CWAbuelo != "CWContenedor") && ($CWAbuelo != "CWFichaEdicion"))
	{
		$estiloVisibilidad = 'visibility:hidden;';
	}
	
	$aperturaCapa = "<div id='IGEPVisible".$idCampo."' style='".$estiloVisibilidad;
	$aperturaCapa.= " display:inline; ' >";
	$cierreCapa = '</div>';	
	$compotxt = '';	 
	$compotxt.=$aperturaCapa;	
	if ($CWPadre != "CWFila")	 
	{
		if ($obligatorio == 'true')	$txtAsterisco="*"; 
		else $txtAsterisco="";
		$compotxt.= "<label style='font-weight: bold; $mostrarTextoAsociado' id='txt".$idPanel."_".$idCampo."' for='".$idCampo."'>$txtAsterisco$textoAsociado:</label>\n";
	}
	
	if(($params['rutaAbs']=='yes' OR $params['rutaAbs']=='si' OR $params['rutaAbs']=='true') and ($src!=''))	
		$src = 'igep/_visualizarImagen.php?fichero='.$src;
	
	$img.= "<img id='$id' src='$src' style='border:none; display:inline;' $width $height $alt />";
	if($bumpbox)
	{	
		// Cálculo dimensión rel
		$diff = $wBump - $hBump;		
		
		if ($idPanel == 'vSeleccion')
		{
			$height = 300;
			$width = 400;
		}
		else {
			$height = 600;
			$width = 800;
		}
		if ($diff <= 0)
		{		
			// Más alto q ancho
			$diffH = $height - $hBump;
			$mult = $diffH / $hBump;
		}
		elseif ($diff > 0)
		{
			// Más ancho q alto
			$diffW = $width - $wBump;
			$mult = $diffW / $wBump;
		}		
		
		$wBump = $wBump+($wBump*$mult);
		$hBump = $hBump+($hBump*$mult);
		


		$smarty->igepPlugin->registrarInclusionCSS('jquery.lighter.css','igep/smarty/plugins/lightbox/stylesheets/');
		$smarty->igepPlugin->registrarInclusionCSS('sample.css','igep/smarty/plugins/lightbox/stylesheets/');
                
		$smarty->igepPlugin->registrarInclusionJS('jquery.lighter.js','igep/smarty/plugins/lightbox/javascripts/');
		$smarty->igepPlugin->registrarInclusionJS('rainbow.js','igep/smarty/plugins/lightbox/javascripts/');

		$compotxt .= "<a href='$src' data-lighter='$src' data-height='".$hBump."' data-width='".$wBump."'>".$img."</a>";
	}
	else {
		$compotxt.= $img;
	}
		
	$compotxt.=$cierreCapa;
	return ($ini.$compotxt.$fin);
}
?>
