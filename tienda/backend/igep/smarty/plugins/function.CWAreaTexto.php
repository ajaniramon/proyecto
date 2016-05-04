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

function smarty_function_CWAreaTexto($params, &$smarty) 
{
	/**	
	devuelve una cadena para mostrar el componente en html e incluir[linkar] ficheros en su caso
	
	parametros
	@param disabled boolean
	@param 
	@returns string
	*/
	
	$igepSmarty = new IgepSmarty();
	
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
	$n_comp="CWAreaTexto";	
	
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
	
	//////////////////////////////////////////////////////////////////////////////////////////////////
	// FIN CODIGO NECESARIO DE CADA COMPONENTE //
	/////////////////////////////////////////////////////////////////////////////////////////////////

	$obligatorio = 'false';
	if($params['obligatorio'])
	{
		$obligatorio = $params['obligatorio'];
	}
	
	// Ahora generamos el codigo del componente
	if (
		(isset($params['dataType']))
		&& (is_array($params['dataType']))
		)
	{
		$dataType = $params['dataType'];
		
		//Comprobamos los atributos comunes
		if (!empty($dataType['maxLength']))
		{
			$maxLength = intval($dataType['maxLength']);
		}
		if (!empty($dataType['required']))
		{
			$obligatorio = $dataType['required'];
		}
		//Comprobamos atributos particulares según el tipo
		switch (strtolower(trim($dataType['type'])))
		{
			case 'string':
			case 'text':
				if (!empty($dataType['ereg']))
					$strRegExp = $dataType['ereg'];
				if (!empty($dataType['inputMask']))
					$strInputMask = $dataType['inputMask'];
				if (!empty($dataType['password']) && $dataType['password']===true)
					$strType = "password";
			break;
			
			case 'integer':
			break;
			
			case 'numeric':
			break;
		}
	}
	// Longitud mínima 
	if ($params['longitudMinima']) 
	{
		$llamadaJS = $idPanel."_comp.informaAvisoJS('LONGITUDMINIMA',this,'".$params['longitudMinima']."');";
		$igepSmarty->addAccionEvento("onBlur", $llamadaJS, 190);
	}
	// Longitud maxima
	if ($params['longitudMaxima']) 
	{
		$llamadaJS = $idPanel."_comp.comprobarMaximo(this.type, this, '".$params['longitudMaxima']."');";
		$igepSmarty->addAccionEvento("onKeyUp", $llamadaJS, 180);
		$igepSmarty->addAccionEvento("onBlur", $llamadaJS, 170);
	}
	
	// valor por defecto ? 
	if($params['value']) 
	{
		if ((!is_array($params['value'])) && ($params['value'] != ''))
			$value = htmlentities($params['value'], ENT_COMPAT | ENT_HTML401, 'ISO-8859-1');
		else		
			$value = $params['value'];
	} 
	else 
	{
		$value="";
	}

	$editable = 'true';
	if($params['editable'])
	{
		$editable = $params['editable'];
	}	

	if(isset($params['tabindex'])) 
	{
		$valorIndex = $params['tabindex'];
		if ($editable == 'false')
			$valorIndex = abs($params['tabindex'])*(-1);
		$tabindex="tabindex='".$valorIndex."'";
	} 
	else 
	{
		if ($editable == 'false')
			$tabindex="tabindex='-1'";
		else
			$tabindex="";
	}
		
	// cols 
	if($params['cols']) 
	{
		$cols="cols=\"".$params['cols']."\"";
	}
	else
	{
		$elementArea = "width:100%;";
	}

	// rows 
	if($params['rows']) 
	{
		$rows="rows=\"".$params['rows']."\"";
	}
	
	if (isset($params['actualizaA']))
	{
		$actuoSobrePlugin = $params['actualizaA'];
	    $llamadaJS = $idPanel."_comp.actualizarElemento(this,'".$params['actualizaA']."');";
		$igepSmarty->addAccionEvento("onBlur", $llamadaJS);
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

	//Tratamiento de etiquetas asociadas
	$textoAsociado = "";
	$textoAsociadoColumna = "";
	$mostrarTextoAsociado = "false";
	//Si hay etiqueta asociada...
	if (
		(isset($params['textoAsociado'])) 
		&& (trim($params['textoAsociado']) !='')
		)
	{	
		$textoAsociado = $params['textoAsociado'].':';
		$textoAsociadoColumna = $params['textoAsociado'];
		$mostrarTextoAsociado = "true";
	}
	else
	{
		$textoAsociado = ucfirst($idCampo).":";
		//$textoAsociadoColumna = ucfirst($idCampo);
		$mostrarTextoAsociado = "false";
	}

	//Decidimos si se muestra o no el texto asociado
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
	//Fin tratamiento de etiquetas

	$ini = "";
	$fin = "";
	$hiddentxt = "";

	//Si el padre es una fila o una ficha....
	if (($CWPadre == "CWFila") || ($CWAbuelo == "CWFichaEdicion"))
	{	
		$iterActual = $smarty->_tag_stack[$punteroPilaPadre][2];
		$numRegTotales = count($smarty->_tag_stack[$punteroPilaAbuelo][1]['datos']);
		$iterActualExtra=0;
		$numRegTotalesExtras=0;

		$idFila = $smarty->_tag_stack[$punteroPilaAbuelo][1]['id']."_".$iterActual;
	
// TAMAÑO COLUMNAS
		if (($CWPadre == "CWFila") && (isset($params['cols'])) && ($iterActual == 0))
		{
			// Hay que almacenar el tamaño del campo para poder fijar el ancho de las columnas
			$smarty->_tag_stack[$punteroPilaAbuelo][6]['sizes'][] = $params['cols'];
			$smarty->_tag_stack[$punteroPilaAbuelo][6]['sizes']['total'] += intval($params['cols']);
		}
		
		if ($iterActual >= $numRegTotales)
		{
			//$contInsercion = $iterActual - $numRegTotales;
			// Componer el nombre dl campo: params[nombre]_idFila
			// ej: cad_inv_dni__F_tabla1_2
			$idCampo = "ins___".$idCampo."___".$idFila;

			//Si el padre es una fila, incluimos los TD
			if ($CWPadre == "CWFila")
			{
				$ini = "<td align='center'>\n";
				$fin = "</td>\n";
				$campoEstadoFila = "est_".$idFila;
				$llamadaJS = $idPanel."_tabla.cambiarEstado('insertada',this,'".$campoEstadoFila."');";
				$igepSmarty->addAccionEvento("onBlur", $llamadaJS, 1);
			}
			if ($CWPadre == "CWFicha")
			{
				$campoEstadoFila = "est_".$idFila;
				$llamadaJS = "document.getElementById('".$campoEstadoFila."').value='insertada';";
				$igepSmarty->addAccionEvento("onBlur", $llamadaJS, 1);
			}
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
				if (($editable != "no") && ($editable != "false"))
        		{
					$llamadaJS = $idPanel."_tabla.cambiarEstado('modificada',this,'".$campoEstadoFila."');";
					$igepSmarty->addAccionEvento("onBlur", $llamadaJS, 1);
        		}
			}
			if ($CWPadre == "CWFicha")
			{
				$llamadaJS = $idPanel."_comp.comprobarModificacion('".$idCampo."');";
				$igepSmarty->addAccionEvento("onBlur", $llamadaJS, 1);
			}

			//Obtenemos el registro que le corresponde y fijamos el valor
			$datosReg = $smarty->_tag_stack[$punteroPilaAbuelo][1]['datos'][$iterActual];
			
			//Asignamos el valor de registro, controlando el PEAR:DB
			$valueReg=null;
			//Si es la nueva version de PEAR, da igual que sea Postgres que Oracle (columna en minúsculas) 			
			if (!isset($valueReg)) $valueReg = $datosReg[strtolower($params['nombre'])];
			//Si aqui aun no tiene valor, puede ser Pear "case sensitive" contra Postgres (columna mayúscula/minúsculas)
			if (!isset($valueReg)) $valueReg = $datosReg[$params['nombre']];
			//Por último, si aquí tampoco tiene valor, puede ser Pear "case sensitive" contra Oracle (columna mayúsculas)
			if (!isset($valueReg)) $valueReg = $datosReg[strtoupper ($params['nombre'])];			
			// Fin del proceso de asignación del valor
			if (isset($valueReg) && ($valueReg!="")) $value=htmlentities($valueReg, ENT_COMPAT | ENT_HTML401, 'ISO-8859-1');
			if (($valueReg == null) && ($value == '')) $value='';
		}
				
		$fondos = '';
		if(isset($datosReg['__gvHidraRowColor']))
			$fondos = $datosReg['__gvHidraRowColor'];
		// editable = true,false,nuevo | si/no/nuevo
		switch($CWPadre) 
		{			
			case "CWFila":
				switch($editable)
				{
					case "true":
					case "si":
						$classHTML = " class=\"text tableEdit form-control".$fondos."\"";
						$estadoHTML = "readOnly";
					break;
					case "false":
					case "no":
						$classHTML = " class=\"text tableNoEdit form-control".$fondos."\"";
						$estadoHTML = "readOnly";
					break;
					case "nuevo":
						$classHTML = " class=\"text tableNew form-control".$fondos."\"";
						$estadoHTML = "readOnly";
					break;
					default:
						$classHTML = "";
						$estadoHTML = "";
					break;
				}//Fin switch
			break;
			case "CWFicha":
						switch($editable)
					{
						case "true":
						case "si":
							// Comprobamos si se vuelve a cargar la plantilla con la acción modificar/insertar, activar los campos
							if ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'modificar') {
								$classHTML = " class=\"text modify form-control\"";
								$estadoHTML = "";
							}
							else if ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'insertar') {	
								$classHTML = " class=\"text edit form-control\"";
								$estadoHTML = ""; 
							}
							else {
								$classHTML = " class=\"text edit form-control\"";
								$estadoHTML = "readOnly";
							}
						break;
						case "false":
						case "no":
							$classHTML = " class=\"text noEdit form-control\"";
							$estadoHTML = "readOnly";
						break;
						case "nuevo":
							// Comprobamos si se vuelve a cargar la plantilla con la acción modificar/insertar, activar los campos
							if ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'modificar') {
								$classHTML = " class=\"text new form-control\"";
								$estadoHTML = "readOnly";
								//Pasamos el tabindex a negativo, ya que no será accesible
								$tabindex=" tabindex='-1' ";								
							}
							else if ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'insertar') {	
								$classHTML = " class=\"text edit form-control\"";
								$estadoHTML = ""; 
							}
							else {
								$classHTML = " class=\"text new form-control\"";
								$estadoHTML = "readOnly";
								//Pasamos el tabindex a negativo, ya que no será accesible
								$tabindex=" tabindex='-1' ";
							}
						break;
					}
				break;
			default:
				$classHTML = " class=\"text edit\"";
				$estadoHTML = "";
		}
		
		// Creamos un campo hidden para la concurrencia (valor anterior)
		//Comprobamos si hay que crear el hidden o no
		if(strpos($idCampo,'cam')!==false)
			$idHidden=str_replace("cam___","ant___",$idCampo);
		if ($idHidden!="")
		{
			if (substr($value, 0, 2)==SALTO_LINEA)
			{
				$value=SALTO_LINEA.$value;
			}
			$hiddentxt="<div id='capa_".$idHidden."' style='display:none' >";
			$hiddentxt.="<textarea name='".$idHidden."' id='".$idHidden."' ".$classHTML." ";
			$hiddentxt.=$tabindex." ".$cols." ".$rows." >";
			$hiddentxt.=$value;
			$hiddentxt.="</textarea></div>";
		}	
		
		//SI: mi padre es una fila, NO soy oculto y estoy
		//en la primera iteración ENTONCES: tengo que poner título a la columna
		//Y utilizar mi parámetro nombre para añadir una referencia
		if (
			($CWPadre == "CWFila")
			&& ($oculto == false)
			&& ($iterActual == 0)
		)
		{
			$referencia = $params['nombre'];
			$v_titulo = $smarty->_tag_stack[$punteroPilaAbuelo][1]['titulosColumnas'];
			$v_titulo[$referencia] = $textoAsociadoColumna;
			$smarty->_tag_stack[$punteroPilaAbuelo][1]['titulosColumnas'] = $v_titulo;
		}
	}
	else 
	{
		if ($CWPadre == "CWFicha") //Panel de búsqueda
		{
			switch($editable)
			{
				case "true":
				case "si":	
					$classHTML = " class=\"text edit\"";
					$estadoHTML = "";
				break;
				case "false":
				case "no":
					$classHTML = " class=\"text noEdit form-control\"";
					$estadoHTML = "readOnly";
				break;
			}
		}
	}

///////////////////////////////////////////////////////////////////////////////////	
	/* Fin del proceso*/
	
	// Para la primera iteración en un listado o ficha, o cdo la variable no esté fijada para la búsqueda
	$tipoComprobacionPanel = $smarty->_tag_stack[$punteroPilaPanel][1]['tipoComprobacion'];
	if ( 
		(($iterActual == 0) || (!isset($iterActual)) ) && 
		( ($obligatorio == "true") || ($obligatorio == "si")) &&
		($tipoComprobacionPanel != 'cambioFoco') ) 
	{
		$script = $idPanel."_comp.addCampo('".$idCampo."');";
		$igepSmarty->addPreScript($script);
	}
	
	//Generará el evento onBlur cuando sea obligatorio y en el panel no indique q solo es comprobación d "envio"
	if ( ( ($obligatorio == 'true') || ($obligatorio == 'si') ) && ($tipoComprobacionPanel != 'envio') )
	{
		$llamadaJS = $idPanel."_comp.informaAvisoJS('ESVACIO',this);";
		$igepSmarty->addAccionEvento("onBlur", $llamadaJS, 50);
	}
	///No vacío
///////////////////////////////////////////////////////////////////////////////////
	
	if (($CWSelector) && ($editable != 'false'))
	{		
		$punteroPilaCWSelector = count($smarty->_tag_stack)-1;
		array_push($smarty->_tag_stack[$punteroPilaCWSelector][2],$idCampo);
	}	
	
	//Visibilidad e invisibilidad
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
	
	$aperturaCapa = "<div id='IGEPVisible".$idCampo."'";
	$aperturaCapa.=" style='";
		$aperturaCapa.="$estiloVisibilidad; ";
		$aperturaCapa.="display:block; ";
	$aperturaCapa.= "' >";//Fin style
	
	$cierreCapa = '</div>';
	
	$compotxt = $aperturaCapa;
	$compotxt .= $hiddentxt;	
	if ($obligatorio == 'true')	$txtAsterisco="*"; 
	else $txtAsterisco="";
	
	//Texto asociado
	$etiquetaTextoAsociado ='';
	if ( // Si el CWCampoTexto NO ESTÁ en una fila Y NO es un campo oculto
		($CWPadre != "CWFila") &&
		($oculto == false)
	)
	{
		if ($obligatorio == true) $txtAsterisco="*";
		else $txtAsterisco="";
		$etiquetaTextoAsociado = "<label style='font-weight: bold; $mostrarTextoAsociado' ";
		$etiquetaTextoAsociado.= "id='txt".$idPanel."_".$idCampo."' for='".$idCampo."'>";
		$etiquetaTextoAsociado.= "$txtAsterisco$textoAsociado</label>\n";
	}
	
	if ($maxLength > 0)
		$html_maxLength = "maxLength='".$maxLength."'";
	else
		$html_maxLength ='';
	
	$compotxt.=$etiquetaTextoAsociado;	
	$compotxt .= "<textarea style=\"$elementArea\" name=\"$idCampo\" id=\"$idCampo\" $tabindex $classHTML $html_maxLength $estadoHTML $cols $rows ".$igepSmarty->getAcciones();
	if ($obligatorio == true)
		$compotxt .= " required ";
	$compotxt .= ">".$value."</textarea>";
	$compotxt .= $cierreCapa;
	
	$tresModos = $smarty->_tag_stack[$punteroPilaPanel][1]['tresModos'];
	// Estamos en un tres modos y dentro de una tabla, los campos 'external' no entran
	// No se activen los campos de inserción en la tabla en un tres modos
	if ( ( (($idPanel == 'lis') || ($idPanel == 'lisDetalle')) && ($CWAbuelo == 'CWTabla')) && ($tresModos == 1) && ($iterActual >= $numRegTotales))
	{
		$compotxt = '';
	}
	
	return($igepSmarty->getPreScript().$ini.$compotxt.$fin);
}
?>