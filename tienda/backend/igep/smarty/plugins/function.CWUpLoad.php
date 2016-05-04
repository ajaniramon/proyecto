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

function smarty_function_CWUpLoad($params, &$smarty) {
	
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
	
	if (($CWPadre == "CWSelector") && ($CWAbuelo == "CWSolapa"))//Si el padre es un CWSelector y el abuelo es solapa, tenemos que movernos dos más arriba, pq pasamos de el
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
	
	if ($CWAbuelo == 'CWContenedor') //Estamos en un panel de búsqueda
	{
		$punteroPilaPanel = $punteroPilaAbuelo - 1;
	}
	else
	{
		$punteroPilaPanel = $punteroPilaAbuelo - 2;
	}
	$idPanel = $smarty->_tag_stack[$punteroPilaPanel][1]['id'];
	
	
	///////////////////////////////////////////////////////////////
	// Necesitamos avisar al panel que existe este componente pq // 
	// se deberá añadir un nuevo atributo al Form                //
	///////////////////////////////////////////////////////////////
	
	$smarty->_tag_stack[$punteroPilaPanel][1]['upLoad'] = 1;
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	// CODIGO NECESARIO PARA CADA COMPONENTE //
	////////////////////////////////////////////////////////////////////////////////////////////////
	// Primero defino el nombre del componente.
	$n_comp="CWUpLoad";	
	// Incrementamos  el número de componentes CWUpLoad
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
	
	$obligatorio = 'false';
	if($params['obligatorio'])
	{
		$obligatorio = $params['obligatorio'];
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
	//Tamaño de la caja Text de HTML
	if(isset($params['size'])) 
	{ 
		$size="size='".$params['size']."'"; 
	} 
	else 
	{ 
		$size="";
	}
	
	$textoAsociado = "";
	if (isset($params['textoAsociado']))
	{
		$textoAsociado = $params['textoAsociado'];
	}
	
	$multiple = '';
	if ((isset($params['multiple'])) &&
			(($params['multiple'] === true) || (strtolower(trim($params['multiple'])) =='true'))
		)
	{
		$multiple = "multiple='true'";
	}
	
	
	// -----------------------------------------------------------------------------------------
	// GENERAMOS LOS JS DE COMPROBACIÓN DE ERRORES
	// CUANDO HAY MAS DE UN ERROR PARA EL MISMO EVENTO
	// SE MUESTRA EL QUE MÃS ABAJO ESTE EN ESTE FICHERO
	// -----------------------------------------------------------------------------------------
		
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	// FIN DE GENERACION JS DE COMPROBACIÓN ERROR	///	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$ini = "";
	$fin = ""; 
	$hiddentxt = "";
	
	//Si el padre es una fila o el abuelo es una fichaEdicion....
	// Comprobamos lo dl abuelo en vez d q el padre sea CWFicha pq una ficha ahora puede estar dentro de otro componente
	// p.ej (CWContenedor) para el panel de búsqueda
	if (($CWPadre == "CWFila") || ($CWAbuelo == "CWFichaEdicion"))
	{	
		
		$iterActual = $smarty->_tag_stack[$punteroPilaPadre][2];
		$numRegTotales = count($smarty->_tag_stack[$punteroPilaAbuelo][1]['datos']);
		$iterActualExtra=0;
		$numRegTotalesExtras=0;		
		
		$idFila = $smarty->_tag_stack[$punteroPilaAbuelo][1]['id']."_".$iterActual;
		
		/*** ESTOY EN UNA TABLA **/
		$estadoFilaJS = '';
		if (($iterActual == 0) && ($CWPadre == "CWFila"))
		{	
			$estadoFilaJS = $idPanel."_tabla.columnaEstado('".$idCampo."','".$editable."');";
			$igepSmarty->addPreScript($estadoFilaJS);
		}
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
		//SI: mi padre es una fila, NO soy oculto y estoy
		//en la primera iteración ENTONCES: tengo que poner título a la columna
		//Y utilizar mi parámetro nombre para añadir una referencia
		if (
				($CWPadre == "CWFila")
				&& ($iterActual == 0)
		)
		{
			$referencia = $params['nombre'];
			$v_titulo = $smarty->_tag_stack[$punteroPilaAbuelo][1]['titulosColumnas'];
			$v_titulo[$referencia] = $textoAsociadoColumna;
			$smarty->_tag_stack[$punteroPilaAbuelo][1]['titulosColumnas'] = $v_titulo;
		}
		/********************************/
		
		if ($iterActual >= $numRegTotales)
		{	
			//$contInsercion = $iterActual - $numRegTotales;
			// Componer el nombre dl campo: params[nombre]_idFila
			// ej: cad_inv_dni__F_tabla1_2
			$idCampo = "ins___".$idCampo."___".$idFila;
			$estado = "disabled";
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
				$campoEstadoFila = "est_".$idFila;
				$llamadaJS = "document.getElementById('".$campoEstadoFila."').value='insertada';";
				$igepSmarty->addAccionEvento("onBlur", $llamadaJS, 1);
			}
			 		
		}
		else
		{		
			$idCampo = "cam___".$idCampo."___".$idFila;
			$estado = 'disabled';
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
				$llamadaJS = "edi_comp.comprobarModificacion('".$idCampo."');";
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
			//Ahora comprobamos si tiene un valor impuesto por parámetro, que tiene preferencia...

			if (isset($valueReg) && ($valueReg!=null) && $valueReg!="") $value=htmlspecialchars($valueReg);		
			// Fin del proceso de asignación del valor			
			
			// Creamos un campo hidden para la concurrencia (valor anterior)
			$idHidden=str_replace("cam","ant",$idCampo); 
			if ($idHidden!="")
			{
				$hiddentxt .= "<input type=\"hidden\" name=\"$idHidden\" id=\"$idHidden\" value=\"$value\" />";
			}
		}
		
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
							$estado = '';
							$classHTML = " class=\"text modify\"";
						}
						else if ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'insertar') {
							$estado = '';	
							$classHTML = " class=\"text edit\"";
						}
						else {
							$classHTML = " class=\"text edit\"";
						}
					break;
					case "false":
					case "no":
						$classHTML = " class=\"text noEdit\"";
					break;
					case "nuevo":
						// Comprobamos si se vuelve a cargar la plantilla con la acción modificar/insertar, activar los campos
						if ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'modificar') {
							$estado = '';
							$classHTML = " class=\"text new\"";
							//Pasamos el tabindex a negativo, ya que no será accesible
							$tabindex=" tabindex='-1' ";															
						}
						else if ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'insertar') {
							$estado = '';	
							$classHTML = " class=\"text edit\"";
						}
						else {
							$classHTML = " class=\"text new\"";
							//Pasamos el tabindex a negativo, ya que no será accesible
							$tabindex=" tabindex='-1' ";
						}
					break;
				}
			break;
			default:
				$classHTML = " class=\"text edit\"";
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
					$classHTML = " class=\"text edit\"";
				break;
				case "false":
				case "no":
					$classHTML = " class=\"text noEdit\"";
				break;
			}
		}
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$compotxt = $hiddentxt;		
	$compotxt.="<div id='IGEPInvisible".$idCampo."' style='display:none; vertical-align:top;'></div>";		
	$compotxt.="<div class='upload-image row' id='IGEPVisible".$idCampo."' style='display:-moz-inline-box; vertical-align:top; padding:1px 1px 2px 1px; margin:0px'>";
	
	//Si hay texto asociado y el CWUpLoad NO ESTA en una fila
	$textoAsociado = '';
	if ($CWPadre != "CWFila")		
	{
		if ($obligatorio == 'true')	$txtAsterisco="*"; 
		else $txtAsterisco="";
		
		if ($textoAsociado != "")				
			$textoAsociado = $txtAsterisco."<label style='font-weight: bold;' id='txt".$idPanel."_".$idCampo."' for='".$idCampo."'>".$txtAsterisco.$textoAsociado.":</label>\n";
		else
			$textoAsociado = "<label id='txt".$idPanel."_".$idCampo."' for='".$idCampo."'></label>\n";
	}
	
	$componente = '';
	//$componente .= $textoAsociado."<input type=\"file\" name=\"$idCampo\" id=\"$idCampo\" $tabindex $classHTML style='padding:0px;' readOnly $size ".$igepSmarty->getAcciones()." />";
	if (!empty($multiple))$idCampo.='[]';
	$componente .= $textoAsociado."<input type=\"file\" data-gvhpanelOn='panel_$idPanel' $estado name=\"$idCampo\" id=\"$idCampo\" $tabindex $classHTML readOnly $size $multiple onBlur='javascript:edi_comp.comprobarModificacion(\"$idCampo\");' />";

	$compotxt .= $componente;
	$compotxt .= "</div>";

	if (($CWSelector) && ($editable != 'false'))
	{		
			$punteroPilaCWSelector = count($smarty->_tag_stack)-1;
			array_push($smarty->_tag_stack[$punteroPilaCWSelector][2],$idCampo);
	}
	
	return ($ini.$igepSmarty->getPreScript().$compotxt.$fin);
}
?>