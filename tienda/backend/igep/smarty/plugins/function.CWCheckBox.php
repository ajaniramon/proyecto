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
	En el caso del checkbox, extendemos el estandar de HTML, proporcionando	valores asociados
	al checkbox según su estado. Para ello, creamos un campo hidden asociaciado al mismo, que
	contiene dicho valor. De cara al REQUEST que gestiona gvHidra, interesa que el 'name' de
	dicho hidden se corresponda con el campo de la TPL, por lo que debemos dejar claro los
	prefijos/sufijos que vamos a utilizar. Siendo "ntpl" el nombre del elemento en la tpl:
							 ________________________________________________________
							|		Búsqueda	|		Edición		|	Inserción	 |
····························|--------------------------------------------------------|
	   Elemento checkbox ID:|	chkbfil_ntpl	|	ccam___ntpl		|	cins___ntpl	 |
							|--------------------------------------------------------|							
	 Elemento checkbox NAME:|	chkbfil_ntpl	|	ccam___ntpl		|	cins___ntpl	 |
····························|--------------------------------------------------------|
		 Elemento hidden ID:|		ntpl		|	cam___ntpl		|	hins___ntpl	 |
							|--------------------------------------------------------|
	   Elemento hidden NAME:|		ntpl		|	cam___ntpl		|	hins___ntpl	 |
····························|--------------------------------------------------------|
  Element hidden (conc.) ID:|	NO EXISTE		|	ant___ntpl		|	NO EXISTE	 |
							|--------------------------------------------------------|
Element hidden (conc.) NAME:|	NO EXISTE		|	ant___ntpl		|	NO EXISTE	 |
····························^-------------------^-------------------^----------------^

	Aclaración de prefijos utilizados para el id de los campos
		cins -> id para modo inserción
		hins -> campo de texto oculto en inserción con el valor dl checkbox seleccionado
		ccam -> id del checkbox en modo edición
		cam -> campo de texto oculto con el valor del checkbox seleccionado, anterior
		chkbfil_ -> campo de texto oculto con el valor del checkbox en el filtro
*/
require_once('igep/include/IgepSmarty.php');

function smarty_function_CWCheckBox($params, &$smarty)
{
	
	$igepSmarty = new IgepSmarty();
	
	///////////////////////////////////////////
	// CODIGO NECESARIO PARA CADA COMPONENTE //
	///////////////////////////////////////////
	// Primero defino el nombre del componente.
	$n_comp="CWCheckBox";	
	// Incrementamos  el número de componentes CWCampoTexto
	$num = $smarty->igepPlugin->registrarInstancia($n_comp);
	
	if($params['nombre'])
	{
		$idCampo=$params['nombre'];
	} 
	else 
	{
		// Por defecto, nombre plugin y número de instancia del componente
		$idCampo=$n_comp.$num; 
	}
	$nameCampo = $idCampo;//Name para el checkbox
	$idCampoOculto = '';//Id para el campoOculto
	$nameCampoOculto = '';//Name para el hidden
	

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
			$tabindex = ""; 		
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

	//Obtenemos el valorSi y valorNo
	if (
		(isset($params['dataType']))
		&& (is_array($params['dataType']))
		)
	{
		$dataType = $params['dataType'];
		
		//Valor si
		if (isset($dataType['valueChecked']))
		{ 
			$valorSi = 	$dataType['valueChecked'];
		}
		else {
			$valorSi = true;
		}
		
		//Valor no
		if (isset($dataType['valueUnchecked']))
		{
			$valorNo = 	$dataType['valueUnchecked'];
		}
		else {
			$valorNo = false;
		}
		
		//Required - obligatorio
		if (isset($dataType['required'])) {
			
			if (is_bool($dataType['required']))
				$obligatorio = $dataType['required']; 
		}
	}
	
	//valor se pasara al php cuando este en un panel de busqueda
	$chequeado = '';
	
	//Recepción del valor desde views
	if($params['value'])
	{
		$value = $params['value'];
		if ($value == $valorSi)
			$chequeado =  "checked='checked'";
	}
	//Se manteniene la compatibilidad con el parametro valor
	elseif($params['valor'])
	{
		$value = $params['valor'];
		if ($value == $valorSi)
			$chequeado =  "checked='checked'";
	}
	else
	{
		$value = $valorNo;
	}


	//#20304 Problema con el 0. Cuando el unchecked es 0 daba problemas. PHP pasa el "0" a bool(false) y no a int(0). Por ello hacemos esta asignación.
	if($value==="0")
		$value = 0;
	
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
	
	//inicializacion de variables
	$script = "";
	$textoHtml = "";
	$ini = "";
	$fin = "";
	$hiddentxt = "";
	$hiddentxtActual = "";
	$estado = "";

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
	
		
	//Si el padre es una fila o el abuelo es una fichaEdicion....
	// Comprobamos lo dl abuelo en vez d q el padre sea CWFicha pq una ficha ahora puede estar dentro de otro componente
	// p.ej (CWContenedor) para el panel de búsqueda 
	// Estamos en una tabla o en un panel de edición
	if (($CWPadre == "CWFila") || ($CWAbuelo == "CWFichaEdicion"))
	{
		$iterActual = $smarty->_tag_stack[$punteroPilaPadre][2];		
		
		// Tratamiento del tabindex en una tabla, teniendo en cuenta las filas
		if ($CWPadre == 'CWFila')
		{
			$valorIndex = $iterActual.$valorIndex;	
			$tabindex = "tabindex='".$valorIndex."'";

			// TAMAÑO COLUMNAS
			if (($CWPadre == "CWFila"))
			// Hay que almacenar en el padre (CWFila) el tamaño del campo para poder fijar el ancho de las columnas
				if (isset($params['size']))
					$smarty->_tag_stack[$punteroPilaAbuelo][6]['sizes'][] = strlen($params['size']);
				else
					$smarty->_tag_stack[$punteroPilaAbuelo][6]['sizes'][] = strlen($params['textoAsociado']);
		}
		
		$numRegTotales = count($smarty->_tag_stack[$punteroPilaAbuelo][1]['datos']);
		
		$idFila = $smarty->_tag_stack[$punteroPilaAbuelo][1]['id']."_".$iterActual;

/* --------------- */
/* --- ESTILOS --- */
/* --------------- */
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
						$estadoHTML = 'disabled="disabled"';
					break;
					case "false":
					case "no":
						$classHTML = " class=\"text tableNoEdit ".$fondos."\"";
						$estadoHTML = 'disabled="disabled"';
					break;
					case "nuevo":
						$classHTML = " class=\"text tableNew ".$fondos."\"";
						//$estadoHTML = 'disabled="disabled"';
					break;
				}	
			break;
			case "CWFicha":
				switch($editable)
				{
					case "true":
					case "si":
							// Comprobamos si se vuelve a cargar la plantilla con la acción modificar/insertar, activar los campos
							if ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'modificar') {
								$classHTML = ' class="text modify"';
								$estadoHTML = "";
							}
							else if ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'insertar') {	
								$classHTML = ' class="text edit"';
								$estadoHTML = ''; 
							}
							else {
								$classHTML = ' class="text edit"';
								$estadoHTML = 'disabled="disabled"';
							}
					break;
					case "false":
					case "no":
						$classHTML = ' class="text noEdit"';
						$estadoHTML = 'disabled="disabled"';
					break;
					case "nuevo":
							// Comprobamos si se vuelve a cargar la plantilla con la acción modificar/insertar, activar los campos
							if ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'modificar') {
								$classHTML = ' class="text new"';
								$estadoHTML = 'disabled="disabled"';
								//Pasamos el tabindex a negativo, ya que no será accesible
								$tabindex=" tabindex='-1' ";
							}
							else if ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'insertar') {	
								$classHTML = ' class="text edit"';
								$estadoHTML = ''; 
							}
							else {
								$classHTML = ' class="text new"';
								$estadoHTML = 'disabled="disabled"';
								//Pasamos el tabindex a negativo, ya que no será accesible
								$tabindex=" tabindex='-1' ";
							}
					break;
				}			
			break;
			default:
				$classHTML = ' class="text edit"';
				$estadoHTML = '';
		}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		$estadoFilaJS = '';
		if (($iterActual == 0) && ($CWPadre == "CWFila")) 
		{
			$estadoFilaJS = $idPanel."_tabla.columnaEstado('".$idCampo."','".$editable."');";
			$igepSmarty->addPreScript($estadoFilaJS);
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		//campos de la insercion
		if ($iterActual >= $numRegTotales)
		{	
			// Componer el nombre del campo: params[nombre]_idFila
			// ej: cad_inv_dni__F_tabla1_2
			$baseIdCampo = $idCampo;//Núcleo del identificador del campo 
			$idCampo = "cins___".$idCampo."___".$idFila;				
			
			if ($CWPadre == "CWFila")
			{		
				$ini = "<td align='center'>\n";
				$fin = "</td>\n";						
				$campoEstadoFila = "est_".$idFila;
				$llamadaJS = $idPanel."_tabla.cambiarEstado('insertada','this','".$campoEstadoFila."');";
				$igepSmarty->addAccionEvento("onBlur", $llamadaJS, 1);
			}
			
			if ($CWPadre == "CWFicha")
			{

				// Para hacer las comprobaciones de modificación necesitamos el campo oculto 'hins___' que es el q contiene
				// el valor a comparar con el anterior
/*
				$id = str_replace("cins___","hins___",$idCampo);
				$llamadaJS = $idPanel."_comp.comprobarModificacion('".$id."');";
				$igepSmarty->addAccionEvento("onBlur", $llamadaJS, 1);
*/			
				

				$campoEstadoFila = "est_".$idFila;
				$llamadaJS = "document.getElementById('".$campoEstadoFila."').value='insertada';";
				$igepSmarty->addAccionEvento("onClick", $llamadaJS, 1);

			}
			
			$idCampoOculto = str_replace("cins___","hins___",$idCampo); 	
			//Quitamos esto porque creemos que no se gasta
			/*
			if($params['funcion']) 
			{
				$funcion .= $idPanel."_comp.establecerBooleano(this,'".$idCampoOculto."','".$valorSi."','".$valorNo."');";
			} 
			else
			{
				$funcion = "onClick=\"javascript:".$idPanel."_comp.establecerBooleano(this,'".$idCampoOculto."','".$valorSi."','".$valorNo."');";
			}
			*/
			$funcion = "onClick=\"javascript:$llamadaJS".$idPanel."_comp.establecerBooleano(this,'".$idCampoOculto."','".$valorSi."','".$valorNo."');";
			
			// Creamos un campo hidden para la concurrencia (valor actual)
			if ($idCampoOculto!="") //hins___
			{
				$hiddentxtActual .= "<input type='hidden' id='$idCampoOculto' name='$idCampoOculto' value='$value' />";
			}
		}
		else //Ya no estamos en inserción
		{
			// Componer el nombre del campo: params[nombre]_idFila
			// ej: cad_inv_dni__F_tabla1_2
			// se utiliza 'ccam' en vez d 'cam' pq creamos un campo d texto oculto para guardar el valor y comparar con el anterior
			$idCampo = "ccam___".$idCampo."___".$idFila;

			//Si el padre es una fila, incluimos los TD
			if ($CWPadre == "CWFila")
			{
				$ini = "<td align='center'>\n";
				$fin = "</td>\n";
				$campoEstadoFila = "est_".$idFila;
				if (($editable != "no") && ($editable != "false"))
        		{
					$llamadaJS = $idPanel."_tabla.cambiarEstado('modificada','this','".$campoEstadoFila."');";
					$igepSmarty->addAccionEvento("onBlur", $llamadaJS, 1);
        		}
			}
			if ($CWPadre == "CWFicha")
			{
				// Para hacer las comprobaciones de modificación necesitamos el campo oculto 'cam___' que es el q contiene
				// el valor a comparar con el anterior
				$id = str_replace("ccam___","cam___",$idCampo);
				$llamadaJS = $idPanel."_comp.comprobarModificacion('".$id."');";
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
			
			if (isset($valueReg) && ($valueReg!="")) $value=htmlspecialchars($valueReg);
			else $value='';
			// Fin del proceso de asignación del valor		
			
			if ($value == $valorSi)
			{
				$chequeado = "checked='checked'";
			}
			else
			{
				$chequeado = '';
			}	
	
			$estado = " disabled='disabled' ";	
			
			$idCampoOculto = str_replace("ccam___","cam___",$idCampo); 	
			if($params['funcion']) 
			{
				$funcion .= $idPanel."_comp.establecerBooleano(this,'".$idCampoOculto."','".$valorSi."','".$valorNo."');";
			} 
			else
			{
				$funcion = "onClick=\"javascript:".$idPanel."_comp.establecerBooleano(this,'".$idCampoOculto."','".$valorSi."','".$valorNo."');";
			}
			
			// Creamos un campo hidden para la concurrencia (valor actual)
			if ($idCampoOculto!="") // ccam___
			{
				$hiddentxtActual .= " <input type=\"hidden\" id=\"$idCampoOculto\" name=\"$idCampoOculto\" value=\"$value\" $classHTML/>";
			}
			
			// Creamos un campo hidden para la concurrencia (valor anterior)
			$idHidden = str_replace("ccam___","ant___",$idCampo); 	
			if ($idHidden!="")
			{
				$hiddentxt .= "<input type=\"hidden\" id=\"$idHidden\" name=\"$idHidden\" value=\"$value\" $classHTML/>";
			}			
		}		
		
		//SI: mi padre es una fila, NO soy oculto y estoy
		//en la primera iteración ENTONCES: tengo que poner título a la columna
		//Y utilizar mi parámetro nombre para añadir una referencia								
		if (
			($CWPadre == "CWFila") &&								
			($params['oculto'] != 'true') &&
			($iterActual == 0)
		)
		{	
			$referencia = $params['nombre'];				
			if (empty($textoAsociado)) $textoAsociado = $referencia;				
			$v_titulo = $smarty->_tag_stack[$punteroPilaAbuelo][1]['titulosColumnas'];				
			$v_titulo[$referencia] = $textoAsociadoColumna;
			$smarty->_tag_stack[$punteroPilaAbuelo][1]['titulosColumnas'] = $v_titulo;
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	}//if ($CWPadre)
	else //Estamos en un panel de búsqueda
	{		
		$idCampoOculto = $idCampo;
		$idCampo = 'chkbfil_'.$idCampo;
		if ($editable == 'false')
			$estadoHTML = 'disabled="disabled"';
		$hiddentxtActual = " <input type=\"hidden\" id=\"$idCampoOculto\" name=\"$idCampoOculto\" value=\"$value\" />";
		$funcion = 'onClick="javascript:'.$idPanel."_comp.establecerBooleano(this,'$idCampoOculto','".$valorSi."','".$valorNo."');";
	}//Fin if-else	
	
	if($params['actualizaA'])
	{
	   	$funcion .= $idPanel."_comp.actualizarElemento(document.getElementById('$idCampoOculto'),'".$params['actualizaA']."');";
	}
			
	if (($CWSelector) && ($editable != 'false'))
	{		
		$punteroPilaCWSelector = count($smarty->_tag_stack)-1;
		array_push($smarty->_tag_stack[$punteroPilaCWSelector][2],$idCampo);
	}
	
	$funcion .= '"';
		 
	if ($CWPadre != "CWFila")
	{
		$textoAsociado = "<label style='font-weight: bold; $mostrarTextoAsociado' id='txt".$idPanel."_".$idCampo."' for='".$idCampo."'>".$txtAsterisco.$textoAsociado."</label>\n"; 
	}
	else
		$textoAsociado = '';
		
	//Visibilidad e invisibilidad
	$aperturaCapa = '';
	$cierreCapa = '';
	//$substr_replace ($var, '', 10, -1)
	//Si el campo INICIALMENTE NO debe ser visible
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

	$tresModos = $smarty->_tag_stack[$punteroPilaPanel][1]['tresModos'];
	// Estamos en un tres modos y dentro de una tabla, los campos 'external' no entran
	// No se activen los campos de inserción en la tabla en un tres modos
	if ( ( (($idPanel == 'lis') || ($idPanel == 'lisDetalle')) && ($CWAbuelo == 'CWTabla')) && ($tresModos == 1) && ($iterActual >= $numRegTotales))
		$textoHtml = '';
	else
	{
		$textoHtml = $aperturaCapa;
		$required = '';
		$txtAsterisco = '';
		if ($obligatorio == true) 
		{
			$txtAsterisco = "*";
			$required = 'required';
		}
		$textoHtml.=$hiddentxt.$hiddentxtActual.$txtAsterisco.$textoAsociado;
			$textoHtml.="<input type='checkbox' id='$idCampo' $estadoHTML";
			$textoHtml.=" $classHTML $tabindex name='$idCampo' $required ";
			$textoHtml.=' value="'.$value.'" '."$chequeado $funcion ";
		$textoHtml.=$igepSmarty->getAcciones()." />\n";
		$textoHtml .=$cierreCapa;
	}

	return ($ini.$textoHtml.$fin);
}
?>