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

function smarty_function_CWCampoTexto($params, &$smarty)
{
	
	$igepSmarty = new IgepSmarty();
	
	///////////////////////////////////
	// LECTURA DE VALORES DE LA PILA //
	///////////////////////////////////
	//Puntero a la pila de etiquetas que contiene a CWFila
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
	
// REVIEW: Nueva ventana de selección
	$punteroPilaCWPanel = $punteroPilaAbuelo - 2;
	$CWPanel = $smarty->_tag_stack[$punteroPilaCWPanel][0];
	$idCWPanel = $smarty->_tag_stack[$punteroPilaCWPanel][1]['id'];
	if ($idCWPanel == 'vSeleccion')
		$smarty->_tag_stack[$punteroPilaPadre][1]['vsTPL'] = 1;
//////////////////////////////
		
	// Referencia al contenido de la función "inicializar_ventana"
	// Este código se ejecuta en el onLoad de la Ventana (BODY)
	$onLoadParams = & $smarty->_tag_stack[0][1]['onLoad'];
	$onLoadParams.="";
	
	$listado = $smarty->_tag_stack[$punteroPilaPadre][1]['tipoListado'];
	////////////////////////////////////////////////////////////////////////////////////////////////
	// CODIGO NECESARIO PARA CADA COMPONENTE //
	////////////////////////////////////////////////////////////////////////////////////////////////
	// Primero defino el nombre del componente.
	$n_comp="CWCampoTexto";
	// Incrementamos  el número de componentes CWCampoTexto
	$num = $smarty->igepPlugin->registrarInstancia($n_comp);
	
	
	// Tiene nombre? no tiene? Le asigno uno en ese caso
	if($params['nombre'])
	{
		$idCampo=$params['nombre'];
	}
	else
	{
		// Por defecto, nombre plugin y número de instancia del componente
		$idCampo=$n_comp.$num;
	}
	
	
	$editable = 'true';
	if($params['editable'])
	{
		$editable = $params['editable'];
	}

	$placeholder = '';
	if($params['placeholder'])
	{
		$placeholder = $params['placeholder'];
	}
	
	$obligatorio = false;
	if (
		(isset($params['obligatorio']))
		&&
		(
			($params['obligatorio'] === true) || (strtolower(trim($params['obligatorio'])) =='true')
		)
	)
	{
		$obligatorio = true;
	}
	
	
	//valor por defecto
	if (isset($params['value']))
	{
		if ((!is_array($params['value'])) && ($params['value'] != ''))
			$value = htmlentities($params['value'], ENT_COMPAT | ENT_HTML401, 'ISO-8859-1');
		else
			$value = $params['value'];
	}
	else
	{
		$value = "";
	}
	
	if (isset($params['tabindex']))
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
	
	//maxLength: Atributo del INPUT HTML que limita los caracteres máximos a introducir
	$html_maxLength='';
	$maxLength = -1;
	if(isset($params['maxlength']))
	{
		$maxLength = intval($params['maxlength']);
	}
	
	
	//Tamaño de la caja Text de HTML
	$size = -1;
	if(isset($params['size']))
	{
		$size = intval($params['size']);
		$html_size="size='".$params['size']."'";
	}
	else
	{
		$html_size="";
	}

	$padding = "padding-right:0px;";
	
	$dataType = array('type'=>'text');
	$conCalendario = false;
	$showTime = 'false';
	$strRegExp = null;
	$strInputMask = null;
	$strType="text";
	$smarty->igepPlugin->registrarInclusionJS('masks.js');
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
		$tipo = strtolower(trim($dataType['type']));
		switch ($tipo)
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
								
			case 'time':
				$conCalendario = false;
				$showTime = 'true';
			break;
			case 'date':
				if ($dataType['calendar'] === true)
				{
					$conCalendario = true;
					$showTime = 'false';
				}
			break;
			
			case 'datetime':
				if ($dataType['calendar'] === true)
				{
					$conCalendario = true;
					$showTime = 'true';
				}
			break;
		}
	}
	
	
	//REVIEW: David y Vero ¿Cuales de estos campos deben incluirse?
	//Si el campo debe estar oculto (hidden)
	$oculto = false;
	if (
		(isset($params['oculto']))
		&&
		(
			($params['oculto'] === true)
			|| (strtolower(trim($params['oculto'])) == 'true')
		)
	)
	{
		$oculto = true;
		$editable = '';
	}
	else
	{
		$llamadaJS = "this.select();";
		$igepSmarty->addAccionEvento("onFocus", $llamadaJS);
		$igepSmarty->addAccionEvento("onDblClick", $llamadaJS);
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
	$textoAsociado = '';
	$textoAsociadoColumna = '';
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
		$textoAsociado = ucfirst($idCampo).':';
//		$textoAsociadoColumna = ucfirst($idCampo);
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
	
	if(isset($params['mascara']))
	{
		// le añadimos el javascript para controlar la mascara...
		$smarty->igepPlugin->registrarInclusionJS('objMascara.js');
		$llamadaJS ="return aplicaMascara(this, event, '".$params['mascara']."'); ";
		$igepSmarty->addAccionEvento("onKeyPress", $llamadaJS, 190);
	}//FIN mascaras
	
	// Longitud mínima
	if (isset($params['longitudMinima']))
	{
		$llamadaJS = $idPanel."_comp.informaAvisoJS('LONGITUDMINIMA',this,'".$params['longitudMinima']."');";
		$igepSmarty->addAccionEvento("onBlur", $llamadaJS, 180);
	}
	//Fin longitudMinima

	// Longitud máxima
	if (isset($params['longitudMaxima']))
	{
		$llamadaJS = $idPanel."_comp.informaAvisoJS('LONGITUDMAXIMA',this,'".$params['longitudMaxima']."');";
		$igepSmarty->addAccionEvento("onBlur", $llamadaJS, 170);
	}
	//Fin longitudMaxima
	
	//No vacío
	if (isset($params['actualizaA']))
	{
		$actuoSobrePlugin = $params['actualizaA'];
		//$llamadaJS = $idPanel."_comp.actualizarElemento('campo',this,'".$params['actualizaA']."');";
	    $llamadaJS = $idPanel."_comp.actualizarElemento(this,'".$params['actualizaA']."');";
		$igepSmarty->addAccionEvento("onBlur", $llamadaJS);
	}
	///No vacío
		
	//autocomplete
	if (isset($params['autocomplete']))
	{
		$autocomplete = intval($params['autocomplete']);
		if($autocomplete==0)
			$autocomplete = 3;
	}

	///////////////////////////////////////////////////////////////////////////////////////////////////
	// FIN DE GENERACION JS DE COMPROBACIÓN ERROR	///
	///////////////////////////////////////////////////////////////////////////////////////////////////
	
	$ini = "";
	$fin = "";
	$hiddentxt = "";
	
	//Si el padre es una fila o el abuelo es una fichaEdicion....
	//Comprobamos lo del abuelo en vez de que el padre sea CWFicha
	//porque una ficha ahora puede estar dentro de otro componente
	// p.ej (CWContenedor) para el panel de búsqueda
	if (($CWPadre == "CWFila") || ($CWAbuelo == "CWFichaEdicion"))
	{
		$padding = "padding-right:5px;";
		$iterActual = $smarty->_tag_stack[$punteroPilaPadre][2];
		// Tratamiento del tabindex en una tabla, teniendo en cuenta las filas
		if ($CWPadre == 'CWFila')
		{
			$valorIndex = $iterActual.$valorIndex;
			$tabindex = "tabindex='".$valorIndex."'";
		}
		$numRegTotales = count($smarty->_tag_stack[$punteroPilaAbuelo][1]['datos']);
		$iterActualExtra=0;
		$numRegTotalesExtras=0;
		
		$idFila = $smarty->_tag_stack[$punteroPilaAbuelo][1]['id']."_".$iterActual;
		
		$estadoFilaJS = '';
		if (($iterActual == 0) && ($CWPadre == "CWFila"))
		{
			$estadoFilaJS = $idPanel."_tabla.columnaEstado('".$idCampo."','".$editable."');";
			$igepSmarty->addPreScript($estadoFilaJS);
		}

// REVIEW: Nueva ventana de selección
		if (($CWPadre == "CWFila") && (isset($params['size'])) && ($iterActual == 0))
		{
			// Hay que almacenar el tamaño del campo para poder fijar el ancho de las columnas
			$smarty->_tag_stack[$punteroPilaAbuelo][6]['sizes'][] = $params['size'];
			$smarty->_tag_stack[$punteroPilaAbuelo][6]['sizes']['total'] += intval($params['size']);
		}
		elseif (($CWPadre == "CWFila") && ($iterActual == 0)) {
			$smarty->_tag_stack[$punteroPilaAbuelo][6]['sizes'][] = "10";
			$smarty->_tag_stack[$punteroPilaAbuelo][6]['sizes']['total'] += intval(10);
		}
		if ($iterActual >= $numRegTotales) //Zona de inserción
		{
			// Componer el nombre dl campo: params[nombre]_idFila
			// ej: cad_inv_dni__F_tabla1_2
			$idCampo = "ins___".$idCampo."___".$idFila;
			//Si el padre es una fila Y NO soy un campo oculto, incluimos los TD
			if (
				($CWPadre == "CWFila") &&
				($oculto == false)
			)
			{
				$ini = "<td style='text-align:center;'>\n";
				$fin = "</td>\n";
			}
			
			// REVIEW: Vero 20/11/2012 #20065
			// Antes se encapsulaba el evento onChange dentro del evento onBlur para solucionar el problema en versiones de 
			// firefox inferiores a la 3. Se deja solamente el onChange.
			$campoEstadoFila = "est_".$idFila;
			$funcionJS = $idPanel."_tabla.cambiarEstado('insertada',this.id,'".$campoEstadoFila."');";
			$igepSmarty->addAccionEvento("onChange", $funcionJS, 1);
		}
		else //Zona de datos
		{
			// Componer el nombre dl campo: params[nombre]_idFila
			// ej: cad_inv_dni__F_tabla1_2
			$idCampo = "cam___".$idCampo."___".$idFila;
			
			//Si el padre es una fila y NO soy oculto, => incluimos los TD
			if (
				($CWPadre == "CWFila") &&
				($oculto == false)
			)
			{
				$ini = "<td style='text-align:center;'>\n";
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
			if (isset($valueReg) && ($valueReg!="")) $value = htmlentities($valueReg, ENT_COMPAT | ENT_HTML401, 'ISO-8859-1');
			//DEBUG: Cambio "inseguro" 18/09/07 Toni & David. Ver versión 1.85 y hablar con vero. Efecto Guadiana.
			else $value='';
			// Quitado: Los valores nulos en los datos se convierten a cadena vacía, pero se respetan.
			// Fin del proceso de asignación del valor
			// Creamos un campo hidden para la concurrencia (valor anterior)
			$idHidden = str_replace("cam___","ant___",$idCampo);
		}//Fin if-else entre zona inserción y zona datos

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
						$classHTML = " class=\"text tableEdit ".$fondos."\"";
						$estadoHTML = "readOnly";
					break;
					case "false":
					case "no":
						$classHTML = " class=\"text tableNoEdit ".$fondos."\"";
						$estadoHTML = "readOnly";
					break;
					case "nuevo":
						$classHTML = " class=\"text tableNew ".$fondos."\"";
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
							$classHTML = " class=\"text modify\"";
							$estadoHTML = "";
						}
						else if ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'insertar') {
							$classHTML = " class=\"text edit\"";
							$estadoHTML = "";
						}
						else
						{
							$classHTML = " class=\"text edit\"";
							$estadoHTML = "readOnly";
						}
						break;
					case "false":
					case "no":
						$classHTML = " class=\"text noEdit\"";
						$estadoHTML = "readOnly";
					break;
					case "nuevo":
						// Comprobamos si se vuelve a cargar la plantilla con la acción modificar/insertar, activar los campos
						if ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'modificar') {
							$classHTML = " class=\"text new\"";
							$estadoHTML = "readOnly";
							//Pasamos el tabindex a negativo, ya que no será accesible
							$tabindex=" tabindex='-1' ";
						}
						else if ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'insertar')
						{
							$classHTML = " class=\"text edit\"";
							$estadoHTML = "";
						}
						else
						{
							$classHTML = " class=\"text new\"";
							$estadoHTML = "readOnly";
							//Pasamos el tabindex a negativo, ya que no será accesible
							$tabindex=" tabindex='-1' ";
						}
						break;
						default:
							$classHTML = "";
							$estadoHTML = "";
						break;
				}//Fin switch
			break;
			default:
				$classHTML = " class=\"text edit\"";
				$estadoHTML = "";
			break;
		}//Fin switch
		
		if ($idHidden!="")
		{
			$hiddentxt .= "<input type=\"hidden\" name=\"$idHidden\" id=\"$idHidden\" value=\"$value\" $classHTML/>";
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
	} //Fin if (($CWPadre == "CWFila") || ($CWAbuelo == "CWFichaEdicion"))
	else
	{
		if ($CWPadre == "CWFicha") //Panel de búsqueda
		{
			switch($editable)
			{
				case "true":
				case "si":
					$classHTML = " class='text edit '";
					$estadoHTML = "";
				break;
				case "false":
				case "no":
					$classHTML = " class='text noEdit'";
					$estadoHTML = "readOnly";
				break;
			}
		}
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	// Para la primera iteración en un listado o ficha, o cdo la variable no esté fijada para la búsqueda
	$tipoComprobacionPanel = $smarty->_tag_stack[$punteroPilaPanel][1]['tipoComprobacion'];
	if (
		(($iterActual == 0) || (!isset($iterActual)) )
		&& ($obligatorio == true)
		&& ($tipoComprobacionPanel != 'cambioFoco')
	)
	{
		$script = $idPanel."_comp.addCampo('".$idCampo."');";
		$igepSmarty->addPreScript($script);
	}

	
	
	/*
	 -----------------------------------------------------------------
	 -----------------------------------------------------------------
	                   Manejo del parámetro dataType
	 -----------------------------------------------------------------
	 -----------------------------------------------------------------
	*/
	$calendario = '';
	$scriptCalendario = '';
	$textoInfoFecha = '';
	$estiloNumerico ='';//Opciones de alineado si el tipo es numérico
	$html_espaciadoDerecho='';//Espacio adicional si es numero para evitar que la alineación resulte en mala visibilidad
	$dataType = $params['dataType'];

	$separadorItemFecha='';
	if ($tipo =='date')
	{
		if ($dataType['enableInputMask'])
		{
			//REVIEW: Vero y David Leemos el parámetro, pero NO lo tratamos
			if (empty($dataType['dateMask']))
			{
				$dataType['dateMask'] = 'j/n/Y';
			}
			$dateMask = $dataType['dateMask'];

			$dateSeparator = '/';
			$jsDateMask = 'dd'.$dateSeparator.'mm'.$dateSeparator.'yyyy';

			$onLoadParams.= " if (document.getElementById('".$idCampo."'))\n{\t";
			$onLoadParams.= "oMask_$idCampo = new Mask('$jsDateMask', 'date');\n\t";
			$onLoadParams.= "oMask_$idCampo.attach(document.getElementById('$idCampo'));\n}\n";
		}
		
		$textoInfoFecha = _manageDateInfo($dataType, $value, $dayOfWeek, $dayOfYear, $weekOfYear);
		
		$funcionJS = $idPanel."_comp.mostrarInfoFechaJS(this.value,'$idCampo','$dayOfWeek',$dayOfYear,$weekOfYear);";
		$llamadaJS = "campo = document.getElementById(this.id);campo.onChange = $funcionJS";
		$igepSmarty->addAccionEvento("onChange", $llamadaJS, 201);
		
		// ------ Creamos la capa con la información sobre las fechas ------
		$idInfoFecha = "infoFecha".$idCampo;
		$textoInfoFecha = "<span id='$idInfoFecha' class='dateFormat'>&nbsp;$textoInfoFecha&nbsp;</span>";
		
		// -------- Tratamiento parámetro 'calendar' -----------------
		if (
			(strtolower(trim($dataType['calendar']))==true) //Si hay botón calendario
		)
		{
			$smarty->igepPlugin->registrarInclusionCSS('calendar.css', 'igep/css/');
			$smarty->igepPlugin->registrarInclusionJS('calendar.js');
			$smarty->igepPlugin->registrarInclusionJS('calendar-es.js');
			$smarty->igepPlugin->registrarInclusionJS('calendar-setup.js');
			
			// Ahora generamos el codigo del calendario
			$imgCal = "botones/17off.gif"; // El icono aparece transparente hasta q se pase a modo edición o inserción
			$disabled = 'disabled';
			$funcionCalendario = "";
			if ( // Estamos en un panel de búsqueda, se necesita q aparezca el calendario
					($CWAbuelo == "CWContenedor") ||
					($smarty->_tag_stack[$punteroPilaPanel][1]['accion'] == 'modificar' && $editable != 'nuevo' ) ||
					($smarty->_tag_stack[$punteroPilaPanel][1]['accion'] == 'insertar') &&
					(($editable != 'false')&& ($editable != 'no'))
				)
			{
				$imgCal = "botones/17.gif";
				$disabled = '';
			}
			
			$src= '';
			$src = IMG_PATH_CUSTOM.$imgCal;
			
		/*	$funcionCalendario = "onClick = \"expr = /off/; ";
			$funcionCalendario.="if (!expr.test(this.src)) {";
			$funcionCalendario.="ini_calendario('$idCampo','cal_$idCampo'";
			$funcionCalendario.= ", '$dayOfWeek', $dayOfYear, $weekOfYear";
			$funcionCalendario.=", $showTime";
			$funcionCalendario.=");}\" ";
			
			$imgCalendario  = "<img src='".$src."' name='cal_".$idCampo."' id='cal_".$idCampo."' data-gvhposition='panel_$idPanel'";
			$imgCalendario .= " alt='C' title='Calendario' class='btnCalendar' ";
			$imgCalendario .= $funcionCalendario;
			$imgCalendario .= "/>\n";
			$calendario = $style1.$scriptCalendario.$imgCalendario;*/
			
			
			
			$funcionCalendario.="ini_calendario('$idCampo','cal_$idCampo'";
			$funcionCalendario.= ", '$dayOfWeek', $dayOfYear, $weekOfYear";
			$funcionCalendario.=", $showTime);";
				
			$imgCalendario = "<button type='button' id='cal_".$idCampo."' title='Calendario' data-gvhposition='panel_$idPanel' style='display:inline;' class='btnToolTip $disabled' onClick = \"".$funcionCalendario."\" />";
			$imgCalendario .= "<span id='cal_".$idCampo."' class='glyphicon glyphicon-calendar' aria-hidden='true'></span> ";
			$imgCalendario .= "</button>";
			$calendario = $scriptCalendario.$imgCalendario;
			
			$calendario = $imgCalendario;
		}//Fin tratamineto calendar (antes conCalendario)
		
		
	}
	/* --- --- --- --- TRATAMIENTOS DATETIME --- ---*/
	else if ($tipo=='datetime')
	{
		if ($dataType['enableInputMask'])
		{
			//REVIEW: Vero y David Leemos el parámetro, pero NO lo tratamos del todo bien
			if (empty($dataType['datetime']))
				$dataType['datetime'] = 'j/n/Y hh:mm:ss';

			$datetimeMask = $dataType['datetimeMask'];
			$timeSeparator = ':';
			$dateSeparator = '/';
			$jsDatetimeMask = "##$dateSeparator##$dateSeparator#### ##$timeSeparator##$timeSeparator##";
			
			$onLoadParams.= " if (document.getElementById('".$idCampo."'))\n{\t";
			$onLoadParams.= "oMask_$idCampo = new Mask('$jsDatetimeMask');\n\t";
			$onLoadParams.= "oMask_$idCampo.attach(document.getElementById('$idCampo'));\n}\n";
		}
		
		$textoInfoFecha = _manageDateInfo($dataType, $value, $dayOfWeek, $dayOfYear, $weekOfYear);
		
		$funcionJS = $idPanel."_comp.mostrarInfoFechaJS(this.value,'$idCampo','$dayOfWeek',$dayOfYear,$weekOfYear);";
		$llamadaJS = "campo = document.getElementById(this.id);campo.onChange = $funcionJS";
		$igepSmarty->addAccionEvento("onChange", $funcionJS, 201);
		$igepSmarty->addAccionEvento("onBlur", $llamadaJS, 201);
		// ------ Creamos la capa con la información sobre las fechas ------
		$idInfoFecha = "infoFecha".$idCampo;
		$textoInfoFecha = "<span id='$idInfoFecha' class='dateFormat'>&nbsp;$textoInfoFecha&nbsp;</span>";
		
		// -------- Tratamiento parámetro 'calendar' -----------------
		if (
			(strtolower(trim($dataType['calendar']))==true) //Si hay botón calendario
		)
		{
			$smarty->igepPlugin->registrarInclusionCSS('calendar.css', 'igep/css/');
			$smarty->igepPlugin->registrarInclusionJS('calendar.js');
			$smarty->igepPlugin->registrarInclusionJS('calendar-es.js');
			$smarty->igepPlugin->registrarInclusionJS('calendar-setup.js');
			
			// Ahora generamos el codigo del calendario
			$imgCal = "botones/17off.gif"; // El icono aparece transparente hasta q se pase a modo edición o inserción
			$disabled = "disabled";
			$funcionCalendario = "";
			if ( // Estamos en un panel de búsqueda, se necesita q aparezca el calendario
					($CWAbuelo == "CWContenedor") ||
					($smarty->_tag_stack[$punteroPilaPanel][1]['accion'] == 'modificar' && $editable != 'nuevo' ) ||
					($smarty->_tag_stack[$punteroPilaPanel][1]['accion'] == 'insertar') &&
					(($editable != 'false')&& ($editable != 'no'))
				)
			{
				$imgCal = "botones/17.gif";
				$disabled = "";
			}
			
			$src= '';
			$src = IMG_PATH_CUSTOM.$imgCal;
			
			//$funcionCalendario = "onClick = \"expr = /off/; ";
				//$funcionCalendario.="if (!expr.test(this.src)) {";
				$funcionCalendario="ini_calendario('$idCampo','cal_$idCampo'";
				$funcionCalendario.= ", '$dayOfWeek', $dayOfYear, $weekOfYear";
				$funcionCalendario.=", $showTime)";
				//$funcionCalendario.=");}\" ";

$imgCalendario = "<button type='button' id='cal_".$idCampo."' title='Calendario' data-position='panel_$idPanel' style='display:inline;' class='btnToolTip $disabled' onClick = \"".$funcionCalendario."\" />";
$imgCalendario .= "<span id='cal_".$idCampo."' class='glyphicon glyphicon-calendar' aria-hidden='true'></span> ";
$imgCalendario .= "</button>";
				
			/*$imgCalendario  = "<img src='".$src."' name='cal_".$idCampo."' id='cal_".$idCampo."'";
			$imgCalendario .= " alt='C' title='Calendario' class='btnCalendar' ";
			$imgCalendario .= $funcionCalendario;
			$imgCalendario .= "/>\n";*/
			$calendario = $style1.$scriptCalendario.$imgCalendario;
		}//Fin tratamiento calendar (antes conCalendario)
		
		
	}
	/* --- --- --- --- TRATAMIENTOS TIME (HORAS)  --- --- --- --- */
	else if ($tipo=='time')
	{
		$loadTime = '';
			$timeSeparator = ':';
			$jsTimeMask = '##'.$timeSeparator.'##';
			
			$onLoadParams.= " if (document.getElementById('".$idCampo."'))\n{\t";
			$onLoadParams.= "oMask_$idCampo = new Mask('$jsTimeMask');\n\t";
			$onLoadParams.= "oMask_$idCampo.attach(document.getElementById('$idCampo'));\n}\n";

		$smarty->igepPlugin->registrarInclusionJS('jquery-ui.min.js','igep/smarty/plugins/jquery/jquery-ui-1.10.3/ui/minified/');
		$smarty->igepPlugin->registrarInclusionJS('timepicki.js','igep/smarty/plugins/TimePicki-master/js/');
		$smarty->igepPlugin->registrarInclusionCSS('timepicki.css', 'igep/smarty/plugins/TimePicki-master/css/');
		$classTimer = " time_element";
		
		$loadTime = "<script>
			$('input[id=$idCampo]').click(function() {
			 ";

		if (($CWPadre == "CWFila") || ($CWAbuelo == 'CWFichaEdicion')) // NO Panel de búsqueda
		{
			$loadTime .= "
			   if (($(this).attr('class').indexOf('modify') > -1) || ($(this).attr('class').indexOf('tableModify') > -1))
			   {
					$(document).ready(function(){
						$('input[id=$idCampo]').timepicki();
					});
				}	";
		}
		else 
		{
			$loadTime .= "
				$(document).ready(function(){
					$('input[id=$idCampo]').timepicki();
				});";
		}
		$loadTime .= " });
			</script>";
	}
	/* --- --- --- --- TRATAMIENTOS PARA LAS CADENAS --- --- --- --- */
	else if (strtolower(trim($dataType['type']))=='text')
	{
		if ($strRegExp != null)
		{
			$funcionRegExpJS = "";
			$funcionRegExpJS.=$idPanel."_comp.informaAvisoJS('REGEXP',this,/$strRegExp/);";
			$igepSmarty->addAccionEvento("onBlur", $funcionRegExpJS, 150);
		}

		if ($strInputMask != null)
		{
			$onLoadParams.= " if (document.getElementById('".$idCampo."'))\n{\t";
			$onLoadParams.= "oMask_$idCampo = new Mask('$strInputMask');\n\t";
			$onLoadParams.= "oMask_$idCampo.attach(document.getElementById('$idCampo'));\n}\n";
		}
	}
	/* --- --- --- --- TRATAMIENTOS NUMÉRICOS --- --- --- --- */
	else if ($tipo=='numeric')
	{
		$html_espaciadoDerecho ='&nbsp;';
		//Si no hay definido un número de decimales...
		if (empty($dataType['numDec']))
			$numDec = 0;
		else
			$numDec = intval($dataType['numDec']);

		//Modificamos maxLength para contemplar los separadores de miles
		if ($maxLength>0)
		{
			if ((($maxLength - $numDec) % 3) == 0)
				$maxLength += intval(($maxLength - $numDec) / 3) - 1;
			else
				$maxLength += intval(($maxLength - $numDec) / 3);
				
			if ($numDec > 0) $maxLength++;
		}
			
		if ($dataType['enableInputMask'])
		{
			//Concretamos el símbolo separador decimal...
			if (empty($dataType['decimalSeparator']))
				$decimalSeparator = ',';
			else
				$decimalSeparator = $dataType['decimalSeparator'];
				
			//Concretamos el símbolo separador de miles...
			if (empty($dataType['thousandSeparator']))
				$thousandSeparator = '.';
			else
				$thousandSeparator =$dataType['thousandSeparator'];
			
			$jsNumberMask = '-#'.$thousandSeparator.'###';
			if ($numDec>0)
			{
				$jsNumberMask.= $decimalSeparator;
				for($i=0; $i<$numDec; $i++) $jsNumberMask.='0';
			}
			$onLoadParams.= " if (document.getElementById('".$idCampo."'))\n{\t";
			$onLoadParams.= "\t oMask_$idCampo = new Mask('$jsNumberMask', 'number', '$decimalSeparator', '$thousandSeparator');\n";
			$onLoadParams.= "\t oMask_$idCampo.attach(document.getElementById('$idCampo'));\n}\n";
		}
		$estiloNumerico = 'text-align:right;';
		$padding = "padding-right:5px;";
	}//Fin DataType
	/*
	 -----------------------------------------------------------------
	 -----------------------------------------------------------------
	                   Fin manejo del parámetro dataType
	 -----------------------------------------------------------------
	 -----------------------------------------------------------------
	*/
	
	
	//Generará el evento onBlur cuando sea obligatorio y en el panel no indique que sólo es comprobación de "envio"
	if (
		($obligatorio == true)
		&& ($tipoComprobacionPanel != 'envio')
	)
	{
		$llamadaJS = $idPanel."_comp.informaAvisoJS('ESVACIO',this);";
		$igepSmarty->addAccionEvento("onBlur", $llamadaJS, 50);
	}
	///No vacío
	//////////////////////////////////////////////////////////////////////////////////////////////////////
		
	
	$imgUrl = '';
	if (
		($params['conUrl'])
		&&
		(
			($params['conUrl'] === true)
			|| ($params['conUrl'] == 'true')
		)
	)
	{
		$classHTML = "class='text link'";
		$smarty->igepPlugin->registrarInclusionJS('window.js');
		$imgUrl = "<img alt='lk' src=\"".IMG_PATH_CUSTOM."botones/51.gif\" name=\"url_".$idCampo."\" id=\"url_".$idCampo."\" onClick=\"Open_Vtna('".$value."','plantilla',800,600,'no','no','no','no','yes','yes')\" />\n";
	}

	//Comienza la construcción del componente
	$campoTxt =''; //Inicializamos el componente

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
	if (($iterActual >= $numRegTotales) && ($CWAbuelo != "CWContenedor") && ($CWAbuelo != "CWFichaEdicion"))
	{
		$estiloVisibilidad = 'visibility:hidden;';
	}

	$aperturaCapa = "<span id='IGEPVisible".$idCampo."'";
	$aperturaCapa.=" style='";
	$aperturaCapa.="$estiloVisibilidad; ";
	$aperturaCapa.= "' >";//Fin style
	$cierreCapa = '</span>';


	if (isset($autocomplete)) {

		//Cargamos las librerias JQUERY
	    $smarty->igepPlugin->registrarInclusionJS('jquery-ui.min.js','igep/smarty/plugins/jquery/jquery-ui-1.10.3/ui/minified/');
	    $smarty->igepPlugin->registrarInclusionCSS('jquery-ui.css','igep/smarty/plugins/jquery/jquery-ui-1.10.3/themes/base/');
	    
		//Obtenemos la clase manejadora
		$puntero = $punteroPilaAbuelo-1;
		$claseManejadora = $smarty->_tag_stack[$puntero][1]['claseManejadora'];
	    
	    $onLoadParams.= '
	     $(function() {
			$( "#'.$idCampo.'" ).autocomplete({
				source: "phrame.php?action=gvHautocomplete&field='.$idCampo.'&claseManejadora='.$claseManejadora.'",
				minLength: '.$autocomplete.'
			});
		});';
	}	    

	
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
	
	if ($oculto == true)
	{
		$campoTxt = "<input type=\"hidden\" name=\"$idCampo\" id=\"$idCampo\" value=\"$value\" $classHTML/>";
		$campoTxt.= $hiddentxt; //Elemento de valor anterior
	}
	else
	{
		if ($maxLength > 0)
			$html_maxLength = "maxLength='".$maxLength."'";
		else
			$html_maxLength ='';
		
		//Creamos el elemento
		$campoTxt.=$aperturaCapa;
		$campoTxt.=$etiquetaTextoAsociado;
		$campoTxt.= $hiddentxt; //Elemento de valor anterior
		//$campoTxt.="<input type='$strType' name='$idCampo' id='$idCampo' value=\"$value\" title=\"$value\" ";
		$campoTxt.="<input type='$strType' name='$idCampo' id='$idCampo' value=\"$value\"";

		if ($placeholder != '')
			$campoTxt .= " placeholder = '".$params['placeholder']."'";
		
		if ($obligatorio == true)
			$campoTxt .= "required ";
		else
			$campoTxt .= "title='$value'";
		$campoTxt.="$tabindex $classHTML style='padding:0px; $padding $estiloNumerico' $estadoHTML $html_size $html_maxLength ";
		$campoTxt.=$igepSmarty->getAcciones();
		$campoTxt.=" />";
		$campoTxt .= $loadTime;
		$campoTxt.= $textoInfoFecha.$calendario.$imgUrl;
		$campoTxt.= $cierreCapa;
	}

	if (($CWSelector) && ($editable != 'false'))
	{
		$punteroPilaCWSelector = count($smarty->_tag_stack)-1;
		array_push($smarty->_tag_stack[$punteroPilaCWSelector][2],$idCampo);
	}
	
	$tresModos = $smarty->_tag_stack[$punteroPilaPanel][1]['tresModos'];
	// Estamos en un tres modos y dentro de una tabla, los campos 'external' no entran
	// No se activen los campos de inserción en la tabla en un tres modos
	if ( ( (($idPanel == 'lis') || ($idPanel == 'lisDetalle')) && ($CWAbuelo == 'CWTabla')) && ($tresModos == 1) && ($iterActual >= $numRegTotales))
	{
		$campoTxt = '';
	}
	
	if (($CWPadre == 'CWFila') && ($listado == 'true'))
	{
		if ($oculto != 1)
		{
			$campo = "<input type='text' class=\"text tableNoEdit\" readOnly id=\"".$idCampo."\" name=\"".$idCampo."\" value=\"".$value."\" />\n";
			return($ini.$campo.$fin);
		}
	}
	elseif ($listado != 'true')
		return ($ini.$igepSmarty->getPreScript().$campoTxt.$fin);
}//Fin CWCampoTexto



/*
 * _manageDateInfo: Construye la información adicional a las fechas (día de la semana, día del año, y semana del año)
 */
function _manageDateInfo($dataType, $value, &$dayOfWeek, &$dayOfYear, &$weekOfYear)
{
	$textoInfoFecha ='';
	$dayOfWeek = 'none';
	// ----------------- Tratamiento parámetro 'dayOfWeek' -----------------
	if (strtolower(trim($dataType['dayOfWeek']))!='none') //Si queremos mostrar el día de la semana
	{
		$dayOfWeek = strtolower(trim($dataType['dayOfWeek']));
			
		if ($value!='') //Si el valor viene de BD
		{
			//REVIEW: Leer el carácter separador de fechas de config Framework
			$vFecha = explode('/', $value);
			$dt = new DateTime();
			$dt->setDate(intval($vFecha[2]), intval($vFecha[1]), intval($vFecha[0]));
			switch ($dt->format('N'))
			{
				case 1:
					if ((strtolower(trim($dataType['dayOfWeek']))=='short'))
						$textoInfoFecha.='L';
					else
						$textoInfoFecha.='Lunes';
				break;
				case 2:
					if ((strtolower(trim($dataType['dayOfWeek']))=='short'))
						$textoInfoFecha.='M';
					else
						$textoInfoFecha.='Martes';
				break;
				case 3:
					if ((strtolower(trim($dataType['dayOfWeek']))=='short'))
						$textoInfoFecha.='X';
					else
						$textoInfoFecha.='Miércoles';
				break;
				case 4:
					if ((strtolower(trim($dataType['dayOfWeek']))=='short'))
						$textoInfoFecha.='J';
					else
						$textoInfoFecha.='Jueves';
				break;
				case 5:
					if ((strtolower(trim($dataType['dayOfWeek']))=='short'))
						$textoInfoFecha.='V';
					else
						$textoInfoFecha.='Viernes';
				break;
				case 6:
					if ((strtolower(trim($dataType['dayOfWeek']))=='short'))
						$textoInfoFecha.='S';
					else
						$textoInfoFecha.='Sábado';
				break;
				case 7:
					if ((strtolower(trim($dataType['dayOfWeek']))=='short'))
						$textoInfoFecha.='D';
					else
						$textoInfoFecha.='Domingo';
				break;
			};
		}//No hay valor
		$separadorItemFecha='&nbsp;';
	}//Fin tratamineto dayOfWeek
			
		
	$dayOfYear = 'false';
	// -------- Tratamiento parámetro 'dayOfYear' -----------------
	if (strtolower(trim($dataType['dayOfYear'])) != false) //Si queremos mostrar el día del año
	{
		$dayOfYear = 'true';
		if ($value!='') //Si el valor viene de BD
		{
			$vFecha = explode('/', $value);
			$dt = new DateTime();
			$dt->setDate(intval($vFecha[2]), intval($vFecha[1]), intval($vFecha[0]));
			$diaAnyo = intval($dt->format('z')) + 1;
			$diaAnyo = sprintf ("D%03s", $diaAnyo);
			$textoInfoFecha.=$separadorItemFecha.$diaAnyo;
			$separadorItemFecha='&nbsp;';
		}
		else
		{
			$diaAnyo = 'D---';
			$textoInfoFecha.=$separadorItemFecha.$diaAnyo;
			$separadorItemFecha='&nbsp;';
		}
	}//Fin tratamineto dayOfYear
	
	$weekOfYear = 'false';
	// -------- Tratamiento parámetro 'weekOfYear' -----------------
	if (strtolower(trim($dataType['weekOfYear'])) != false) //Si queremos mostrar la semana del año
	{
		$weekOfYear = 'true';
		if ($value!='') //Si el valor viene de BD
		{
			$vFecha = explode('/', $value);
			$dt = new DateTime();
			$dt->setDate(intval($vFecha[2]), intval($vFecha[1]), intval($vFecha[0]));
			$semanaAnyo = intval($dt->format('W'));
			$semanaAnyo = sprintf ("S%02s", $semanaAnyo);
			$textoInfoFecha.=$separadorItemFecha.$semanaAnyo;
			$separadorItemFecha='&nbsp;';
		}
		else
		{
			$diaAnyo = 'S--';
			$textoInfoFecha.=$separadorItemFecha.$diaAnyo;
			$separadorItemFecha='&nbsp;';
		}
	}//Fin tratamineto weekOfYear
	return($textoInfoFecha);
}//Fin _manageDateInfo

?>