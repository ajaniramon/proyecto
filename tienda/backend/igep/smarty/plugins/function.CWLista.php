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
	 Aclaración de prefijos utilizados para el id de los campos
			ins -> id para modo inserción
			cam -> lista visible en modo edición
			ant -> lista oculta para poder recuperarla
			lcam -> campo de texto con el valor seleccionada, anterior
 */

require_once('igep/include/IgepSmarty.php');

function smarty_function_CWLista ($params, &$smarty) 
{
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
	
	//Si el padre es un CWSolapa, tenemos que movernos uno más arriba para ignorarlo
	if (($CWPadre == "CWSelector") && ($CWAbuelo == "CWSolapa"))
	{		
		$punteroPilaPadre = count($smarty->_tag_stack)-3;
		$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];
		$punteroPilaAbuelo = $punteroPilaPadre - 1;
		$CWAbuelo = $smarty->_tag_stack[$punteroPilaAbuelo][0];
	}
	//Si el padre es un CWSolapa, tenemos que movernos uno más arriba para ignorarlo
	if (($CWPadre == "CWSelector") || ($CWPadre == "CWSolapa"))
	{		
		$punteroPilaPadre = count($smarty->_tag_stack)-2;
		$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];
		$punteroPilaAbuelo = $punteroPilaPadre - 1;
		$CWAbuelo = $smarty->_tag_stack[$punteroPilaAbuelo][0];
	}		

	if ($CWAbuelo == 'CWContenedor')  //Estamos en un panel de búsqueda
	{
		$punteroPilaPanel = $punteroPilaAbuelo - 1;
	}
	else
	{
		$punteroPilaPanel = $punteroPilaAbuelo - 2;
	}
	$idPanel = $smarty->_tag_stack[$punteroPilaPanel][1]['id'];
	$dataPanelOn = 'data-gvhPanel = "'.$idPanel.'"';
	
	////////////////////////////////////////////////////////////////////////////////
	// CODIGO NECESARIO PARA CADA COMPONENTE //
	////////////////////////////////////////////////////////////////////////////////
	// Primero defino el nombre del componente.
	$n_comp="CWLista";	
	
	// Incrementamos  el número de componentes CWCampoTexto
	$num = $smarty->igepPlugin->registrarInstancia($n_comp);
	
	
	//autocomplete
	$autocomplete = '';
	if($params['autocomplete']) 
	{
		$autocomplete=$params['autocomplete'];
	}

	// Tiene nombre? no tiene? Le asigno uno en ese caso 
	if($params['nombre']) 
	{
		$idCampo=$params['nombre'];
		$nameCampo = $params['nombre'];
		$idRadio = $idCampo;
		$nameRadio = $nameCampo;
	} 
	else // Por defecto, nombre plugin y número de instancia del componente 
	{		
		$idCampo=$n_comp.$num;
		$nameCampo=$n_comp.$num;
		$idRadio = $idCampo;  
		$nameRadio = $nameCampo;
	}	
		
	//Editable: 'true' 'false' y 'nuevo'
	$editable = 'true';	
	if (isset($params['editable']))
	{
		$editable = strtolower(trim($params['editable']));		
		if (
			($editable == 'false') 
			|| ($editable == 'falso')
			|| ($editable == 'no')			
			|| ($editable === false)
		)
		{
			$editable = 'false';
		}		
		else if (
			($editable == 'true') 
			|| ($editable == 'si')
			|| ($editable == 'verdad')			
			|| ($editable === true)
		)
		{
			$editable = 'true';
		}
		else if (
			($editable == 'nuevo') 
			|| ($editable == 'insertar')
		)
		{
			$editable = 'nuevo';
		}
		else 
			$editable = 'true';
	}//Fin editable	
		
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
	
	//Número máximo de carácteres a mostrar en la descripción de la lista
	//$numCaracteres=50;
	$html_numCaracteres = '';
	if($params['numCaracteres'])
	{
		$html_numCaracteres = "style='width:".$params['numCaracteres']."em;'";		
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
	$textoAsociado = '';
	$textoAsociadoColumna = '';
	$mostrarTextoAsociado = true;
	//Si hay etiqueta asociada...
	if (
		(isset($params['textoAsociado'])) 
		&& (trim($params['textoAsociado']) !='')
		)
	{	
		$textoAsociado = $params['textoAsociado'].':';
		$textoAsociadoColumna = $params['textoAsociado'];
		$mostrarTextoAsociado = true;
	}
	else
	{
		$textoAsociado = ucfirst($idCampo).":";
//		$textoAsociadoColumna = ucfirst($idCampo);
		$mostrarTextoAsociado = false;
	}
	
	if (isset($params['mostrarTextoAsociado']))
	{
		switch (trim(strtolower($params['mostrarTextoAsociado'])))
		{
			case 'no':
			case 'false':
				$mostrarTextoAsociado = false;	
			break;
			case 'si':
			case 'true':
				$mostrarTextoAsociado = true;
			break;
		}//Fin switch
	}//Fin params 
	
	if ($mostrarTextoAsociado == true)
		$disposicionTextoAsociado = 'display:inline; ';
	else
		$disposicionTextoAsociado = 'display:none; ';
		
	//El vector de datos será un array asociativo de tres columna (valor, descripción, seleccionado)
	$parametroValue = array();
	$v_lista[0] = array();	
	$v_lista[0]['valor'] = '';
	$v_lista[0]['descripcion'] = '';
	$parametroValue[0]['seleccionado'] = '';	
	$parametroValue[0]['lista'] = $v_lista;	
	
	//Recepción del valor desde views: se mantiene la compatibilidad con el parametro datos. 
	if($params['datos'])
	{
		$parametroValue = $params['datos'];
	}
	
	if($params['value'])
	{
		$parametroValue = $params['value'];
	}
	$v_datos = $parametroValue; 
	

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
	
	//Número de líneas a mostrar en el desplegable
	$size = '';
	$radio = 0;
	$multiple = '';

	//Si existe el parámetro radio, en lugar de una lista SELECT generamos un RadioButtom
		
	if (
		(isset($params['dataType']))
		&& (is_array($params['dataType']))
		)
	{
		$dataType = $params['dataType'];
		
		//Size
		if (isset($dataType['size']))
		{ 
			$size = " size='".$dataType['size']."'";
		}
		
		//Radio
		if (!empty($dataType['radio']))
		{
			$radio = 1;	
		}

		//Multiple
		if(isset($dataType['multiple']) and $dataType['multiple']) 
		{ 			

			$multiple = ' multiple ';
			//Número de líneas a mostrar en el desplegable
			if ($size != '') 
				$multiple = $multiple." ".$size;
			else
				$multiple = $multiple." size='5' ";
			
			$idCampo = $idCampo;
		}
		
		//Required - obligatorio
		if (isset($dataType['required'])) {
			
			if (is_bool($dataType['required']))
				$obligatorio = $dataType['required']; 
		}		
	}
	
	// REVIEW: Vero, repasar el primer parámetro de actualizarElemento
	if($params['actualizaA'])
	{
		$actuoSobrePlugin = $params['actualizaA'];
	   	$llamadaJS .= $idPanel."_comp.actualizarElemento(this,'".$params['actualizaA']."');";
		$igepSmarty->addAccionEvento("onChange", $llamadaJS);
	}
		
	// -----------------------------------------------------------------------------------------
	// GENERAMOS LOS JS DE COMPROBACIÓN DE ERRORES
	// CUANDO HAY MAS DE UN ERROR PARA EL MISMO EVENTO
	// SE MUESTRA EL QUE MÁS ABAJO ESTE EN ESTE FICHERO
	// -----------------------------------------------------------------------------------------
		
	//inicializacion variables	
	$ini = '';
	$fin = ''; 
	$hiddentxt = '';
	$idFila='';
	$cadHTML='';
	
//////////////////////////////////////////////////////////////////////
// PANEL EDICIÓN (Tabla o Registro)
//////////////////////////////////////////////////////////////////////
	if (($CWPadre == 'CWFila') || ($CWAbuelo == 'CWFichaEdicion'))
	{
		$iterActual = $smarty->_tag_stack[$punteroPilaPadre][2];
		// Tratamiento del tabindex en una tabla, teniendo en cuenta las filas			
		if ($CWPadre == 'CWFila')
		{
			$valorIndex = $iterActual.$valorIndex;	
			$tabindex = "tabindex='".$valorIndex."'";
			
			// TAMAÑO COLUMNAS
			if ( (isset($params['numCaracteres'])) && ($iterActual == 0) )
			{
				// Hay que almacenar el tamaño del campo para poder fijar el ancho de las columnas
				$smarty->_tag_stack[$punteroPilaAbuelo][6]['sizes'][] = $params['numCaracteres'];
				$smarty->_tag_stack[$punteroPilaAbuelo][6]['sizes']['total'] += intval($params['numCaracteres']);
			}
			elseif (($CWPadre == "CWFila") && ($iterActual == 0)) {
				$smarty->_tag_stack[$punteroPilaAbuelo][6]['sizes'][] = "10";
				$smarty->_tag_stack[$punteroPilaAbuelo][6]['sizes']['total'] += intval(10);
			}
						
		}		
		$numRegTotales = count($smarty->_tag_stack[$punteroPilaAbuelo][1]['datos']);
		$funcionJS='';
		$idFila = $smarty->_tag_stack[$punteroPilaAbuelo][1]['id']."_".$iterActual;		

		$estadoFilaJS = '';
		if (($iterActual == 0) && ($CWPadre == "CWFila")) 
		{
			$estadoFilaJS = $idPanel."_tabla.columnaEstado('".$idCampo."','".$editable."');";
			$igepSmarty->addPreScript($estadoFilaJS);		
		}
 
		$ini = '';
		$fin = '';
		// Estamos en una TABLA
		if ($CWPadre == 'CWFila')
		{				
			$ini = "<td align='center'>\n";
			$fin = "</td>\n";
		}
		
// --------------				
// Modo INSERCIÓN
// --------------
		if ($iterActual >= $numRegTotales)
		{
			$idCampo = 'ins___'.$idCampo.'___'.$idFila;
			$nameCampo = 'ins___'.$nameCampo.'___'.$idFila;				
			$idRadio = 'ins___'.$idRadio;			
			$nameRadio = 'ins___'.$nameRadio;			
			// id para el campo oculto donde guardaremos el valor anterior
			$idRadioAnt = str_replace('ins___','ant___',$idRadio); // ant___nomCampo___Form_fila			
			$idCampoAnt = str_replace('ins___','lcam___',$idCampo); // lcam___nomCampo___Form_fila
			$nameCampoAnt = str_replace('ins___','ant___',$nameCampo); // ant___nomCampo___Form_fila
			$idListaAnt = str_replace('ins___','ant___',$idCampo); // ant___nomCampo___Form_fila
			//-----------------------------------------	-------------------------------------------------------------------
			
			// Se añade el evento onChange para marcar como insertado el registro
			// TABLA			
			if ($CWPadre == 'CWFila')
			{
				//SI: mi padre es una fila, NO soy oculto y estoy
				//en la primera iteración ENTONCES: tengo que poner título a la columna
				//Y utilizar mi parámetro nombre para añadir una referencia								
				if (($params['oculto'] != 'true') && ($iterActual == 0))
				{	
					$referencia = $params['nombre'];							
					$v_titulo = $smarty->_tag_stack[$punteroPilaAbuelo][1]['titulosColumnas'];				
					$v_titulo[$referencia] = $textoAsociadoColumna;
					$smarty->_tag_stack[$punteroPilaAbuelo][1]['titulosColumnas'] = $v_titulo;
				}	
				$campoEstadoFila = 'est_'.$idFila;
				$llamadaJS = $idPanel."_tabla.cambiarEstado('insertada','this','".$campoEstadoFila."');";
				$igepSmarty->addAccionEvento("onChange", $llamadaJS, 1);
			}
			// REGISTRO			
			if ($CWPadre == 'CWFicha')
			{
				$campoEstadoFila = 'est_'.$idFila;
				$llamadaJS = "document.getElementById('".$campoEstadoFila."').value='insertada';";
				$igepSmarty->addAccionEvento("onChange", $llamadaJS, 1);
			}
			
		}
// --------------------				
// FIN: Modo INSERCIÓN
// --------------------		
		else 
		{		
// --------------				
// Modo EDICIÓN
// --------------
			$idCampo = 'cam___'.$idCampo.'___'.$idFila;
			$nameCampo = 'cam___'.$nameCampo.'___'.$idFila;
			$idRadio = 'cam___'.$idRadio;
			$nameRadio = 'cam___'.$nameRadio;			
			// id para el campo oculto donde guardaremos el valor anterior
			$idRadioAnt = str_replace('cam___','ant___',$idRadio); // ant___nomCampo___Form_fila
			$idCampoAnt = str_replace('cam___','lcam___',$idCampo); // lcam___nomCampo___Form_fila
			$nameCampoAnt = str_replace('cam___','ant___',$nameCampo); // ant___nomCampo___Form_fila
			$idListaAnt = str_replace('cam___','ant___',$idCampo); // ant___nomCampo___Form_fila
			//-----------------------------------------	-------------------------------------------------------------------
			
			// TABLA
			// Se añade el evento onChange para marcar como modificado el registro
			if ($CWPadre == 'CWFila')
			{
				//SI: mi padre es una fila, NO soy oculto y estoy
				//en la primera iteración ENTONCES: tengo que poner título a la columna
				//Y utilizar mi parámetro nombre para añadir una referencia								
				if (($params['oculto'] != 'true') && ($iterActual == 0))
				{	
					$referencia = $params['nombre'];							
					$v_titulo = $smarty->_tag_stack[$punteroPilaAbuelo][1]['titulosColumnas'];				
					$v_titulo[$referencia] = $textoAsociadoColumna;
					$smarty->_tag_stack[$punteroPilaAbuelo][1]['titulosColumnas'] = $v_titulo;
				}				
				
				$campoEstadoFila = "est_".$idFila;
				if (($editable != "no") && ($editable != "false"))
        		{
					$llamadaJS = $idPanel."_tabla.cambiarEstado('modificada',this,'".$campoEstadoFila."');";
					$igepSmarty->addAccionEvento("onChange", $llamadaJS, 1);
        		}
			}
// --------------------				
// FIN: Modo EDICIÓN
// --------------------
		}		
		// --------------------------------------------------------------------------------------
		// CARGAMOS LOS DATOS DE LA LISTA
		$datosReg = $smarty->_tag_stack[$punteroPilaAbuelo][1]['datos'][$iterActual];		
		
		//Si todo ha ido bien, tendremos un array registro con uno de los atributos multivaluado
		//Asignamos el valor de registro, controlando el PEAR:DB			
		//Si es la nueva version de PEAR, da igual que sea Postgres que Oracle (columna en minúsculas)
		//$v_datos = $datosReg[$params['nombre']];
		
		if ($iterActual >= $numRegTotales)
			$v_datos = $parametroValue;
		else
		{
			$v_datos = null;
			if (!isset($v_datos)) 
			{
				$v_datos = $datosReg[strtolower($params['nombre'])];				
			}
			//Si aqui aun no tiene valor, puede ser Pear "case sensitive" contra Postgres (columna mayúscula/minúsculas)
			if (!isset($v_datos)) 
			{
				$v_datos = $datosReg[$params['nombre']];
			}
			//Por último, si aquí tampoco tiene valor, puede ser Pear "case sensitive" contra Oracle (columna mayúsculas)
			if (!isset($v_datos)) 
			{
				$v_datos = $datosReg[strtoupper($params['nombre'])];				
			}			
			if (!isset($v_datos)) 
			{
				$v_datos = array();
				$v_lista[0]=array();	
				$v_lista[0]['valor']='';
				$v_lista[0]['descripcion']='';
				$v_datos[0]['seleccionado']='';	
				$v_datos[0]['lista']=$v_lista;				
			}
		}				
		// Fin del proceso de asignación del valor				
		// -----------------------------------------------------------------------------------				
		// -----------------------------------------------------------------------------------
		// ESTILO Y ESTADO DEL CAMPO
		$fondos = '';
		if(isset($datosReg['__gvHidraRowColor']))
			$fondos = $datosReg['__gvHidraRowColor'];		

		$codAutocomplete = "
					$(function() {
				    			$('#".$idCampo."').combobox();
								$('#".$idCampo."').combobox('id','combo_$idCampo');
										$('#".$idCampo."').combobox('idDown','comboDown_$idCampo');
										$('#".$idCampo."').combobox('gvhPanel','$idPanel');
										$('#".$idCampo."').combobox('estado','disabled');
				    			$('#toggle').click(function() {
				      			$('".$idCampo."').toggle();
				    			})
				      	});";
		$combobox = '';
	
		if (($autocomplete == true) || ($autocomplete == 'true'))
		{ 
			$combobox = "<script>";
			if (($editable == 'true') && ($CWPadre == 'CWFila'))
				$combobox .= $codAutocomplete;
			if (($editable == 'true') && ($CWPadre == 'CWFicha'))
			{
				$combobox .= "$('#".$idCampo."').combobox('estado','')";
				$combobox .= $codAutocomplete; 
			}
			if (($editable == 'true') && ($CWPadre == 'CWFicha') && ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'insertar'))
			{
				$combobox .= "$('#".$idCampo."').combobox('estado','')";
				$combobox .= $codAutocomplete;
			}
			if (($editable == 'nuevo') && ($CWPadre == 'CWFicha') && ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'insertar'))
			{
				$combobox .= "$('#".$idCampo."').combobox('estado','')";
				$combobox .= $codAutocomplete;
			}
				  			
			$combobox .= "</script>";
		}
		switch($CWPadre) 
		{
			case 'CWFila':
				switch($editable)
				{
					case 'true':
					case 'si':
						$classHTML = " class=\"text tableEdit ".$fondos."\"";
						$estadoHTML = " disabled ";
					break;
					case 'false':
					case 'no':
						$classHTML = " class=\"text tableNoEdit ".$fondos."\"";
						$estadoHTML = " disabled ";
					break;
					case 'nuevo':
						$classHTML = " class=\"text tableNew ".$fondos."\"";
						$estadoHTML = " disabled";
					break;
				}			
			break;
			
			case 'CWFicha':
				switch($editable)
				{
					case 'true':
					case 'si':
							if ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'modificar') {
								$classHTML = " class=\"text modify\"";
								$estadoHTML = '';
							}
							else if ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'insertar') {	
								$classHTML = " class=\"text edit\"";
								$estadoHTML = ''; 
							}
							else {
								$classHTML = " class=\"text edit\"";
								$estadoHTML = " disabled ";
							}
					break;
					case 'false':
					case 'no':
						$classHTML = " class=\"text noEdit\"";
						$estadoHTML = " disabled ";
					break;
					case 'nuevo':
							// Comprobamos si se vuelve a cargar la plantilla con la acción modificar/insertar, activar los campos
							if ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'modificar') {
								$classHTML = " class=\"text new\"";
								$estadoHTML = " disabled ";
								//Pasamos el tabindex a negativo, ya que no será accesible
								$tabindex=" tabindex='-1' ";
							}
							else if ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'insertar') {
								$classHTML = " class=\"text edit\"";
								$estadoHTML = '';
							}
							else {
								$classHTML = " class=\"text new\"";
								$estadoHTML = " disabled ";
								//Pasamos el tabindex a negativo, ya que no será accesible
								$tabindex=" tabindex='-1' ";								
							}
					break;
				}
			break;
			default:
				$classHTML = " class=\"text edit\"";
				$estadoHTML = '';
			break;
		}//Fin switch
		// -----------------------------------------------------------------------------------
		
//////////////////////////////////////////////////////////////////////
// FIN: PANEL EDICIÓN (Tabla o Registro)
//////////////////////////////////////////////////////////////////////		
	}
	
//////////////////////////////////////////////////////////////////////
// PANEL BÚSQUEDA
//////////////////////////////////////////////////////////////////////	
	elseif (($CWPadre == 'CWFicha') && ($CWAbuelo == 'CWContenedor'))
	{		
		if (($editable == 'true') || ($editable == 'si'))
		{
			$classHTML = " class=\"text edit\"";
			if (($autocomplete == true) || ($autocomplete == 'true'))
			{ 
				$combobox = "<script>";
				$codAutocomplete = "
					$(function() {
				    			$('#".$idCampo."').combobox();
								$('#".$idCampo."').combobox('id','combo_$idCampo');
												$('#".$idCampo."').combobox('idDown','comboDown_$idCampo');
												$('#".$idCampo."').combobox('gvhPanel','$idPanel');
												$('#".$idCampo."').combobox('estado','');
				    			$('#toggle').click(function() {
				      			$('".$idCampo."').toggle();
				    			})
				      	});";
				$combobox .= $codAutocomplete;
				$combobox .= "</script>";
			}
		}
		else
		{
			$classHTML = " class=\"text noEdit\"";
			$estadoHTML = " disabled ";
		}		
	}
//////////////////////////////////////////////////////////////////////
// FIN: PANEL BÚSQUEDA
//////////////////////////////////////////////////////////////////////
		
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ZONA DIBUJO 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////		
	$aperturaCapa ='';
	$cierreCapa = '';
	// VISIBLE/INVISIBLE
	$aperturaCapa = "<span id='IGEPVisible".$idCampo."' style='";
	if ($radio == 1)
	{
		if ($CWAbuelo != 'CWContenedor')
			$aperturaCapa = "<span id='IGEPVisible".$nameRadio.'___'.$idFila."' style='";
		else
			$aperturaCapa = "<span id='IGEPVisible".$nameRadio."' style='";
	}

	//Si estamos insertando, las listas/radios no deben verse en las lineas sin activar
	if (($CWAbuelo == 'CWTabla') && ($iterActual >= $numRegTotales))		
	{	
		$aperturaCapa.="visibility:hidden; '>";
	}
	else 
	{
		if ($visible == false)
		{
			$estiloVisibilidad = 'visibility:hidden;';
		}
		else
		{
			$estiloVisibilidad = 'visibility:visible;';
		}
		
		$aperturaCapa.="$estiloVisibilidad; '>";	
	}
	$cierreCapa = '</span>';
	
	// DIBUJAMOS
	$cadHTML = $aperturaCapa;

	// DIBUJAMOS
	$cadHTML = $aperturaCapa;
	
	if ($obligatorio == true) $txtAsterisco='*'; 
	else $txtAsterisco='';
	
	if ($CWPadre == 'CWFila')
		$textoAsociado = '';		
	else
		$textoAsociado = "<label style='font-weight: bold; $disposicionTextoAsociado' id='txt".$idPanel."_".$idCampo."' for='".$idCampo."'>".$txtAsterisco.$textoAsociado."</label>\n";	
	
	if ($mostrarTextoAsociado == true)
		$cadHTML .= $textoAsociado;
	
	$numValores = count($v_datos['lista']);
	$cadOpcion = '';
	if ($radio == 1) 
	{
		$idAux = $idRadio;
		$nameAux = $nameRadio;
		$idAntAux = $idRadioAnt;
		$numValores = count($v_datos['lista']);
	
		// REGISTRO		
		// 08/02/2010 Vero: Para comprobar la modificación pasamos el total de valores y así comprobar si alguno de ellos ha cambiado.	
		if ($CWPadre == 'CWFicha')
		{
			$campoEstadoFila = 'est_'.$idFila;
			$llamadaJS .= $idPanel."_comp.comprobarModificacion('".$idAux."___".$idFila."',".$numValores.");";
			$igepSmarty->addAccionEvento("onChange", $llamadaJS, 1);				
		}
		
		for($i=0; $i < $numValores; $i++)
		{		
			$value = trim($v_datos['lista'][$i]['valor']);
// Diferente nombre en el 'id' que en el 'name' para seguir manteniendo la funcionalidad de ser excluyentes 
// pero a la vez desde javascript poder acceder a ellos como elementos diferentes del formulario
			if ($CWAbuelo != 'CWContenedor')
			{
				$idCampoAnt = $idAntAux.$i.'___'.$idFila;
				$idCampo = $idAux.$i.'___'.$idFila;
				$nameCampo = $nameAux.'___'.$idFila;
			}
			else
			{
				$idCampoAnt = $idAntAux.$i;
				$idCampo = $idAux.$i;
			}
		
			// REGISTRO			
//			if ($CWPadre == 'CWFicha')
//			{
//				$campoEstadoFila = 'est_'.$idFila;	
//				$llamadaJS .= $idPanel."_comp.comprobarModificacion('".$idCampo."');";
//				$igepSmarty->addAccionEvento("onChange", $llamadaJS, 1);				
//			}
			
//			$cadHTML .= "<label class='text' >";
			$cadHTML .= "<label class='radio'>";
			$cadHTML .= "<input type='radio' name=\"$nameCampo\" id=\"$idCampo\" ".$tabindex;
			if ($obligatorio == true)
				$cadHTML .= " required ";
			$cadHTML .= $classHTML." ".$estadoHTML;
			$cadHTML .= " value=\"".htmlentities($value,ENT_QUOTES,'ISO-8859-1')."\" ";
			//Si ese es el item seleccionado lo marcamos			
			if (trim($v_datos['seleccionado']) == $value) 
			{
				$cadHTML.=" checked ";
				$hidden = "<input type='hidden' name='l".$nameCampo."' id='l".$idCampo."' ".$classHTML." value='".htmlentities($v_datos['seleccionado'],ENT_COMPAT | ENT_HTML401, 'ISO-8859-1')."' />";		
			}
			if (empty($hidden))	
			{
				$hidden.= "<input type='hidden' name='l".$nameCampo."' id='l".$idCampo."' ".$classHTML." value='".htmlentities(trim($v_datos['lista'][0]['valor']), ENT_QUOTES, 'ISO-8859-1')."' />";
			}
			$cadHTML .= $igepSmarty->getAcciones()." />";
			$cadHTML .= trim($v_datos['lista'][$i]['descripcion']);
			$cadHTML .= "</label>";
		}
	}
	else
	{
		if ($multiple != '')
			$nameCampo .= '[]';		

		// REGISTRO			
		if ($CWPadre == 'CWFicha')
		{
			$campoEstadoFila = 'est_'.$idFila;	
			$llamadaJS .= $idPanel."_comp.comprobarModificacion('".$idCampo."');";
			$igepSmarty->addAccionEvento("onChange", $llamadaJS, 1);				
		}
			
		if (($CWAbuelo == 'CWContenedor') || // Panel búsqueda
		// Ficha o tabla en modo inserción
		((($CWAbuelo == 'CWTabla') || ($CWAbuelo == 'CWFichaEdicion')) && ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'insertar'))
		) 
		{
			// Copiamos el valor seleccionado al campo oculto por si nos encontramos con la lista en disabled, llegue el valor a negocio
			$llamadaJS .= "document.getElementById('l".$nameCampo."').value = this.options[this.selectedIndex].value;";
			$igepSmarty->addAccionEvento("onChange", $llamadaJS, 1);
		}
		
		$cadHTML .= $combobox;
		$cadHTML .= "<select name=\"$nameCampo\" id=\"$idCampo\" ".$html_numCaracteres." ".$dataPanelOn." ".$size." ".$multiple." ".$classHTML." ".$tabindex." ".$estadoHTML." ".$igepSmarty->getAcciones();

		if ($obligatorio == true)
			$cadHTML .= " required ";
		$cadHTML .= " >\n";
		
			if(is_array($v_datos['lista'])) {

			$numValores = count($v_datos['lista']);			
			for($i=0; $i < $numValores; $i++)
			{
				$cadOpcion.="<option ".$classHTML." ";
				//Si ese es el item seleccionado lo marcamos	
				if (is_array($v_datos['seleccionado']))
				{
					for ($item=0;$item<count($v_datos['seleccionado']);$item++)
					{
						if (trim($v_datos['seleccionado'][$item]) == trim($v_datos['lista'][$i]['valor']))
							$cadOpcion.=" selected ";
					}		
				}	
				elseif (trim($v_datos['seleccionado']) == trim($v_datos['lista'][$i]['valor']))
						$cadOpcion.=" selected ";
				$cadOpcion.="value='".htmlentities($v_datos['lista'][$i]['valor'], ENT_QUOTES, 'ISO-8859-1')."'>".trim($v_datos['lista'][$i]['descripcion'])."</option>";
			}
			$cadHTML .= $cadOpcion;
		}
		$cadHTML .= "</select>"; 
	}
	$cadHTML .= $cierreCapa;
	
	// -----------------------------------------------------------------------------------
	// CAMPO OCULTO
	// El campo oculto tendrá el valor seleccionado de la lista visible 
	//$hidden = '';
	if ($idCampoAnt != '')
		$valueAnt = $v_datos['seleccionado'];
	
	// RADIO: en el campo oculto me guardo el valor seleccionado
	if ($radio == 1)
	{ 
		$hidden = "<input type='hidden' name='".$nameCampoAnt."' ".$classHTML." id='".$nameCampoAnt."' value='".htmlentities($valueAnt, ENT_COMPAT | ENT_HTML401, 'ISO-8859-1')."' />";
	}
	// LISTA: en el oculto me guardo la lista completa con el valor seleccionado
	else 
	{
		// Guardamos el valor anterior y la colección para restaurarla
		$hidden = "<div style='display:none'>";
		if ($multiple != '') // Soy una lista múltiple
			$hidden.= "<select name='".$idListaAnt."[]' id=\"$idListaAnt\" ".$multiple." ".$size.">";
		else
			$hidden.= "<select name='".$idListaAnt."' id=\"$idListaAnt\">";
			
		$cadOpcion='';
		$campoHidden = '';

		if(is_array($v_datos['lista'])) 
		{
			$numValores = count($v_datos['lista']);	
			$campoHidden.= "<input type='text' name='l".$nameCampo."' id='l".$idCampo."' ".$classHTML." value='";
			$valorHidden = '';		
			for($i=0; $i < $numValores; $i++)
			{
				$cadOpcion.="<option ".$classHTML." ";
				
				if (is_array($v_datos['seleccionado']))
				{
					for ($item=0;$item<count($v_datos['seleccionado']);$item++)
					{
						if (trim($v_datos['seleccionado'][$item]) == trim($v_datos['lista'][$i]['valor']))
							$cadOpcion.=" selected ";
					}
					}
					elseif (trim($v_datos['seleccionado']) == trim($v_datos['lista'][$i]['valor']))
					{
						$cadOpcion.=" selected ";
						$valorHidden = htmlentities($v_datos['lista'][$i]['valor'], ENT_QUOTES, 'ISO-8859-1');
					}
					
				//Si ese es el item seleccionado lo marcamos			
				/*if (trim($v_datos['seleccionado']) == trim($v_datos['lista'][$i]['valor'])) 
				{	
					$cadOpcion.=" selected ";
					//$campoHidden = "OCULTO NAME: l$nameCampo ID: l$idCampo ";
					$valorHidden = htmlentities($v_datos['lista'][$i]['valor'], ENT_QUOTES, 'ISO-8859-1');					
				}
				elseif ($multiple != '')  // Tarea Redmine #20565 - #20600
					// En las listas múltiples si no marcamos ningún elemento como seleccionado enviamos valor blanco
						$valorHidden = '';*/
				$cadOpcion.="value='".htmlentities($v_datos['lista'][$i]['valor'], ENT_QUOTES, 'ISO-8859-1')."'>".trim($v_datos['lista'][$i]['descripcion'])."</option>";
			}
		}
		$campoHidden .= $valorHidden."' />";
		$hidden .= $cadOpcion."</select>".$campoHidden."</div>";
	}
	// -----------------------------------------------------------------------------------
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
// FIN: Zona dibujo 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ZONA COMÚN: Paneles, lista, radios
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// COMPROBACIONES
	// Para la primera iteración en un listado o ficha, o cdo la variable no esté fijada para la búsqueda
	$tipoComprobacionPanel = $smarty->_tag_stack[$punteroPilaPanel][1]['tipoComprobacion'];
	if ( 
		( ($iterActual == 0) || (!isset($iterActual)) )
		&& ( ($obligatorio == true ) && ($tipoComprobacionPanel != 'cambioFoco') )
	) 
	{
		$script = $idPanel."_comp.addCampo('".$idCampo."');";
		$igepSmarty->addPreScript($script);
	}
	
	// Obligatorio
	if ( (($obligatorio == true) ) && ($tipoComprobacionPanel != 'envio'))
	{
		$llamadaJS = $idPanel."_comp.informaAvisoJS('ESVACIO',this);";
		$igepSmarty->addAccionEvento("onBlur", $llamadaJS, 50);
	}
	if ($obligatorio == true) $txtAsterisco='*'; 
	else $txtAsterisco='';	
	
	if ($CWPadre == "CWFila")
		$textoAsociado = '';		
	else
		$textoAsociado = "<label style='font-weight: bold; $mostrarTextoAsociado' id='txt".$idPanel."_".$idCampo."' for='".$idCampo."'>".$txtAsterisco.$textoAsociado."</label>\n";

	if (($CWPadre == 'CWFicha') && ($iterActual < $numRegTotales))
	{
		$campoEstadoFila = "est_".$idFila;
		// id para el campo oculto donde guardaremos el valor anterior
		if ($radio == 0)
		{
			$llamadaJS = "if (this.options[this.selectedIndex].value != getElementById('".$idCampoAnt."').value) ";
		}
		else
		{
			$llamadaJS = "numCampos = document.F_$idPanel.$idCampo.length;";
			$llamadaJS .= "for (i=0; i<numCampos; i++){";
       		$llamadaJS .= "if (document.F_".$idPanel.".".$idCampo."[i].checked)";
        	$llamadaJS .= "break;}";
    		$llamadaJS .= "if (document.F_".$idPanel.".".$idCampo."[i].value != getElementById('".$idCampoAnt."').value)"; 
		}
		$llamadaJS .="{\n ";
		if (($editable != "no") && ($editable != "false"))
        {
			$llamadaJS .="getElementById('".$campoEstadoFila."').value='modificada';";
        }
		$llamadaJS .=$idPanel."_imgModificado.style.display='inline';";
		$llamadaJS .="getElementById('capa_menuFalso').style.display='inline';";
		$llamadaJS .="getElementById('capa_menuReal').style.display='none';";				
		$llamadaJS .="getElementById('permitirCerrarAplicacion').value='no';";				
		$llamadaJS .="\n} ";				
		$igepSmarty->addAccionEvento("onChange", $llamadaJS, 1);
	}//Fin REGISTRO		

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
// FIN: ZONA COMÚN (Paneles, listas, radios)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	

	if (($CWSelector) && ($editable != 'false'))
	{		
		$punteroPilaCWSelector = count($smarty->_tag_stack)-1;
		array_push($smarty->_tag_stack[$punteroPilaCWSelector][2],$idCampo);
	}
	
	
	$tresModos = $smarty->_tag_stack[$punteroPilaPanel][1]['tresModos'];
	// Estamos en un tres modos y dentro de una tabla, los campos 'external' no entran
	// No se activen los campos de inserción en la tabla en un tres modos
	if (
		($tresModos == 1) //Estamos en un tabular-registro
		&& ( (($idPanel == 'lis') || ($idPanel == 'lisDetalle') ) //Estamos en el panel tabular 
		&& ($CWAbuelo == 'CWTabla'))		
		&& ($iterActual >= $numRegTotales)
	)
	{
		$hidden = '';
		$cadHTML = '';
	}
	
	return ($ini.$igepSmarty->getPreScript().$hidden.$cadHTML.$fin);
}
?>