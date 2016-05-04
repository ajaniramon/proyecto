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
 * Created on 21-mar-2005 
 *
 * @version	$Id: CWBotonTooltip.php
 * @author David: <pascual_dav@gva.es> 
 * @author Keka: <bermejo_mjo@gva.es>
 * @author Vero: <navarro_ver@gva.es>
 * @author Raquel: <borjabad_raq@gva.es> 
 * @author Toni: <felix_ant@gva.es>
 * @package	gvHIDRA
 **/
require_once('igep/include/IgepSmarty.php');

function smarty_function_CWBotonTooltip($params, &$smarty) 
{

	$igepSmarty = new IgepSmarty();		
	
	$smarty->igepPlugin->registrarInclusionJS('objBotonToolTip.js');
	
	//////////////////////////////////////////////////////////////////////
	// LECTURA DE VALORES DE LA PILA //
	//////////////////////////////////////////////////////////////////////
	//Puntero a la pila de etiquetas que contiene a CWFila 
	$punteroPilaPadre = count($smarty->_tag_stack)-1;
	$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];
	//Puntero a la etiqueta Abuelo
	$punteroPilaAbuelo = $punteroPilaPadre - 1;
	$CWAbuelo = $smarty->_tag_stack[$punteroPilaAbuelo][0];
	
	if (($CWPadre == 'CWSelector') && ($CWAbuelo == 'CWSolapa'))//Si el padre es un CWSolapa, tenemos que movernos uno más arriba, pq pasamos de el
	{		
		$punteroPilaPadre = count($smarty->_tag_stack)-3;
		$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];
		$punteroPilaAbuelo = $punteroPilaPadre - 1;
		$CWAbuelo = $smarty->_tag_stack[$punteroPilaAbuelo][0];
	}

	if (($CWPadre == 'CWSelector') || ($CWPadre == 'CWSolapa'))//Si el padre es un CWSolapa, tenemos que movernos uno más arriba, pq pasamos de el
	{		
		$punteroPilaPadre = count($smarty->_tag_stack)-2;
		$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];
		$punteroPilaAbuelo = $punteroPilaPadre - 1;
		$CWAbuelo = $smarty->_tag_stack[$punteroPilaAbuelo][0];
	}
			
	$punteroPilaPanel = $punteroPilaAbuelo - 2; // CWPanel - CWContenedor - CWFichaEdicion - CWFicha - (CWSolapa) - (CWSelector)
	$CWPanel = $smarty->_tag_stack[$punteroPilaPanel][0];
	$idPanel = $smarty->_tag_stack[$punteroPilaPanel][1]['id'];
	$idAbuelo = $smarty->_tag_stack[$punteroPilaAbuelo][1]['id'];
	$iterActual = $smarty->_tag_stack[$punteroPilaPadre][2];
	//////////////////////////////////////////////////////////////
	
	//inicialización de variables
	$nomForm = '';
	$formVS = '';
	if  ($CWAbuelo == 'CWPanel')
	{
		$nomForm = 'F_'.$idAbuelo;  // Nombre del formulario
		$formVS = $idAbuelo;
		$idPanel = $idAbuelo;
	}
	// Ampliación para poder tener botones tooltip con 'action' dentro de las fichas
	elseif (($CWAbuelo == 'CWFichaEdicion') || ($CWAbuelo == 'CWTabla'))
	{
		$punteroPilaBisAbuelo = $punteroPilaAbuelo - 1;
		$punteroPilaTaAbuelo = $punteroPilaBisAbuelo - 1;		
		$idTaAbuelo = $smarty->_tag_stack[$punteroPilaTaAbuelo][1]['id'];
		$nomForm = 'F_'.$idTaAbuelo;  // Nombre del formulario
		$formVS = $idTaAbuelo;
		$idPanel = $idTaAbuelo;	
	}
	elseif ($CWAbuelo == 'CWContenedor')
	{
		$punteroPilaBisAbuelo = $punteroPilaAbuelo - 1;		
		$idBisAbuelo = $smarty->_tag_stack[$punteroPilaBisAbuelo][1]['id'];
		$nomForm = 'F_'.$idBisAbuelo;  // Nombre del formulario
		$formVS = $idBisAbuelo;
		$idPanel = $idBisAbuelo;	
	}
	
	/*******************************************************/	
	// PARÁMETROS VENTANA DE SELECCIÓN
	// En la definición de un botón tooltip de una ventana selección en la tpl era obligatorio definir los parámetros:
	// formActua y panelActua, con los que la ventana selección sabrá el formulario y panel origen donde devolver los
	// datos. 
	// Para evitar al programador tener que introducirlos cada vez, se calculan navegando por la pila de los
	// plugins.
	
	$filaActual = '';
	if (isset($params['filaActual'])) 
	{
		$filaActual = $params['filaActual'];
	}

	// Este if corresponderá al botón tooltip 'Buscar' de la ventana selección dónde sí necesitamos
	// el parámetro que vendrá asignado de negocio. 
	if ($params['formActua'])
	{
		$formVS = $params['formActua'];
	}

	// Encapsulación parámetros "formActua" y "panelActua" de los botones tooltip para ventana de selección
	// Panel filtro
	if ($CWAbuelo == 'CWContenedor')
		$panelActua = $idPanel;
	else 
	{
		$panelActua = $smarty->_tag_stack[$punteroPilaAbuelo][1]['id'];
	}
	
	if ($panelActua == 'vSeleccion' OR empty($panelActua))
	{
		if ($params['panelActua']) 
		{
			$panelActua = $params['panelActua'];
		}
	}

	$editable = 'true';
	if($params['editable'])
	{
		$editable = $params['editable'];
	}
	
	$mostrarEspera = false;
	if (
			(!empty($params['mostrarEspera']))
	)
	{
		$mostrarEspera = true;
		if(
				(strtolower(trim($params['mostrarEspera'])) == 'true')
				|| (strtolower(trim($params['mostrarEspera'])) == 'cierto')
				|| (strtolower(trim($params['mostrarEspera'])) == 'si')
				|| ($params['mostrarEspera'] === true)
		)
			$textoMostrarEspera = 'Cargando';
		else
			$textoMostrarEspera = $params['mostrarEspera'];
	}
	/*******************************************************/	
	 
	$claseManejadora = '';
	if ($params['claseManejadora'])
	{
		$claseManejadora = $params['claseManejadora'];
	}
	else
	{
		$claseManejadora = $smarty->_tag_stack[$punteroPilaAbuelo][1]['claseManejadora'];
		if (($CWAbuelo == "CWFichaEdicion") || ($CWAbuelo == "CWTabla"))
		{
			$puntero = $punteroPilaAbuelo-2;
			$claseManejadora = $smarty->_tag_stack[$puntero][1]['claseManejadora'];
		}
		else if (($CWAbuelo == "CWContenedor"))
		{
			$puntero = $punteroPilaAbuelo-1;
			$claseManejadora = $smarty->_tag_stack[$puntero][1]['claseManejadora'];
		}
	}
	
	
	$llamadaJavascript='';
	$script = '';
	//Variable para identificar el objTooltip que generamos
	$nomObjeto ="";	
	
	// PARÁMETROS PROPIOS DEL BOTÓN
	// titulo
	$titulo = '';
	if ($params['titulo']) 
	{
		$titulo = $params['titulo'];
	}

	// funcion		
	$funcionBtn = '';
	if ($params['funcion']) 
	{
		$funcionBtn = $params['funcion'];
	}
	// Acción indica el tipo de botón ['guardar','cancelar'....]
	
	// id
	$idBtn = null;
	if ($params['id']) {
		$idBtn = $params['id'];
	}	

	
	// actuaSobre - Panel sobre el q se realizará la acción
	$actuaSobre = '';
	$tresModos = 0;
	if ($params['actuaSobre']) 
	{
		$actuaSobre = $params['actuaSobre'];
		if ((($idPanel == 'lis') || ($idPanel == 'lisDetalle')) && ($actuaSobre == 'ficha'))
		{
			$smarty->_tag_stack[$punteroPilaAbuelo][1]['tresModos'] = true;
			$tresModos = 1;
		}
	}	
	
	// action - Será el action del formulario
	$action = '';
	if ($params['action']) 
	{
		$action = $params['action'];
		if (!stristr($action,'__'))
		{
			$action=$claseManejadora.'__'.$params['action'];		
			if ($action == 'nuevo')
				$action = $action."&actuaSobre=".$actuaSobre;
		}
	}
	
	// No se utiliza... pq??? para q era???
	$acumularValor = false;
	if ($params['acumularValor'])
	{
		$acumularValor = $params['acumularValor'];
	}
	
	// rutaManual - url a una página q nos mostrará en una ventana
	$rutaManual = '';
	if ($params['rutaManual'])
	{
		$rutaManual = $params['rutaManual'];
	}
		
	if ($actuaSobre != '')
	{
		///////////////////////////////////////////////////////////////////////////
		$esDetalle = 'false';
		$esMaestro = 'false';
		if (isset($smarty->_tag_stack[$punteroPilaAbuelo][1]['detalleDe']))
		{
			$esDetalle = 'true';
		}
		if (isset($smarty->_tag_stack[$punteroPilaAbuelo][1]['esMaestro']))
		{
			$esMaestro = $smarty->_tag_stack[$punteroPilaAbuelo][1]['esMaestro'];
		}
	
		///////////////////////////////////////////////////////////////////////////
		$llamadas_js = '';		
		$hidden = '';
		switch($funcionBtn)
		{
			case 'insertar':
				$nomObjeto = "bttlInsertar_".$idAbuelo;
				$llamadas_js .= $nomObjeto." = new objBTTInsertar('".$nomObjeto."','".$idAbuelo."','".$esMaestro."','".$esDetalle."','".$tresModos."','".$params['iconCSS']."');";
				//Es un detalle, tenemos q copiar los valores dl maestro
				if ($esDetalle == 'true')
				{
					$formMaestro = 'F_'.$smarty->_tag_stack[$punteroPilaAbuelo][1]['detalleDe'];
					$formDetalle = 'F_'.$smarty->_tag_stack[$punteroPilaAbuelo][1]['id'];
					$llamadaJavascript .= $nomObjeto.".obtenerValoresMaestro('".$formMaestro."','".$formDetalle."');";
				}
				// No tenemos parámetro action, no va a hacer un submit el formulario x lo tanto activamos las filas a insertar y los botones
				if ($action == '') 
					$llamadaJavascript .= $nomObjeto.".insertar();";
			break;
		    case 'saltar':
				/************ MODAL *********************/
				//Pasamos el formulario al salto para que sepa desde que formulario lanzar la accion en el retorno

		    	
					//Tenemos que distinguir si es una insercion(ins) o una modificacion(cam)
			    	$numRegTotales = count($smarty->_tag_stack[$punteroPilaAbuelo][1]['datos']);
			    	if ($CWAbuelo != 'CWContenedor')
			    	{
			    		if (($numRegTotales == 0) or ($iterActual >= $numRegTotales))
			    		//$nombre = 'ins___'.$titulo.'___'.$panelActua."_".$iterActual;
			    		$nombre = 'ins___'.$idBtn.'___'.$panelActua."_".$iterActual;
			    		else
			    		//$nombre = 'cam___'.$titulo.'___'.$panelActua."_".$iterActual;
			    		$nombre = 'cam___'.$idBtn.'___'.$panelActua."_".$iterActual;
			    	}
			    	else
			    	//$nombre = $titulo;
			    	$nombre = $idBtn;
			    	$llamadaJavascript = '';
					if ($mostrarEspera == true) {
						$llamadaJavascript .= "aviso.mostrarMensajeCargando('".$textoMostrarEspera."');";
					}
			    	$llamadaJavascript .= "document.forms['".$nomForm."'].target='oculto';";
					$llamadaJavascript .= "document.forms['".$nomForm."'].action='phrame.php?action=IgepSaltoVentana&idBotonSalto=".$idBtn."&formActua=".$nomForm."&idBtn=".$nombre."';";
					$llamadaJavascript .= "document.forms['".$nomForm."'].submit();";
					
					 
					$id = $idBtn;
					if ($CWAbuelo == "CWContenedor")
					{
						$campo = $actuaSobre;
						$id = 'jump_'.$campo;
					}
					if (($CWAbuelo == "CWTabla") || ($CWAbuelo == "CWFichaEdicion"))
					{
						$campo = $actuaSobre."___".$panelActua."_".$iterActual;
						$id = 'jump_'.$campo;
					}
					/************ MODAL *********************/
		    break;
      		case 'modificar':
				$nomObjeto = "bttlModificar_".$idAbuelo;
				$llamadas_js .= $nomObjeto." = new objBTTModificar('".$nomObjeto."','".$idAbuelo."','".$esMaestro."','".$esDetalle."','".$tresModos."','".$params['iconCSS']."');";		
			    
			    // No tenemos parámetro action, no va a hacer un submit el formulario x lo tanto activamos las filas a insertar y los botones
				if ($action == '')
				{
				    if ($actuaSobre == "ficha") 
				    {
				    	// Modificamos en la misma ficha
				    	$llamadaJavascript .= $nomObjeto.".modificarFicha();";
					}
					else 
					{
						// Modificamos en la misma tabla
						$llamadaJavascript .= "if (".$idPanel."_tabla.hayFilaSeleccionada()) ";
				    	$llamadaJavascript .= $nomObjeto.".modificarTabla();";
					}
				}									    
			break;
			case 'eliminar':
				$nomObjeto = "bttlEliminar_".$idAbuelo;
				$llamadas_js .= $nomObjeto." = new objBTTEliminar('".$nomObjeto."','".$idAbuelo."','".$esMaestro."','".$esDetalle."','".$params['iconCSS']."');";				
			    
			    // No tenemos parámetro action, no va a hacer un submit el formulario x lo tanto activamos las filas a insertar y los botones
				if ($action == '')
				{
				    if ($actuaSobre == "ficha") 
				    {
				    	// Eliminamos en la misma ficha
				    	$llamadaJavascript .= $nomObjeto.'.eliminarFicha();';
					}
					else 
					{
						// Eliminamos en la misma tabla
						$llamadaJavascript .= "if (".$idPanel."_tabla.hayFilaSeleccionada())";
				    	$llamadaJavascript .= $nomObjeto.'.eliminarTabla();';
					}
				}
			break;
			
			case 'restaurar':
			case 'limpiar':
				$nomObjeto = "bttlLimpiar_".$idAbuelo;
				$llamadas_js .= $nomObjeto." = new objBTTLimpiar('".$nomObjeto."','".$idAbuelo."','".$esMaestro."','".$esDetalle."','".$params['iconCSS']."');";

				if ($mostrarEspera == true) {
					$llamadaJavascript .= "aviso.mostrarMensajeCargando('".$textoMostrarEspera."');";
				}
				$llamadaJavascript .= $nomObjeto.".limpiarCampos();";
				//$llamadaJavascript .= $idPanel."_comp.bloquearSalida(false);";
			break;
			case 'buscarVS':
			case 'buscarvs':
				$campo = $actuaSobre;
				if (($CWPadre == 'CWFila') || ($CWAbuelo == 'CWFichaEdicion')) 
				{
					$campo = $actuaSobre."___".$panelActua."_".$iterActual;
				}
				$llamadaJavascript .= "buscar('".$claseManejadora."','".$formVS."','".$campo."','".$panelActua."','".$filaActual."');";		
			break;
			
			case 'abrirVS':
			case 'abrirvs':			
				$campo = $actuaSobre;
				if (($CWPadre == 'CWFila') || ($CWAbuelo == 'CWFichaEdicion')) 
				{
					//Mandamos el campo sin prefijo pq en la función javascript se detecta si viene d inserción o modificación
					$campo = $campo."___".$panelActua."_".$iterActual;					
				}	

				$smarty->igepPlugin->registrarInclusionJS('window.js');
				$smarty->igepPlugin->registrarInclusionJS('ventanaSeleccion.js');

				$formVS = 'F_'.$formVS;		

				if ($mostrarEspera == true) {
					$dblClick .= "aviso.mostrarMensajeCargando('".$textoMostrarEspera."');";
				}		
				$dblClick .= "openWS('".$claseManejadora."','".$actuaSobre."','".$formVS."','".$campo."','".$panelActua."')";
				$id = "vs_".$campo;
			break;
			case 'openDoc':
				$campo = $idBtn;
				if (($CWPadre == 'CWFila') || ($CWAbuelo == 'CWFichaEdicion'))
				{
					$campo = $campo."___".$panelActua."_".$iterActual;					}
						
					//Obtenemos el registro que le corresponde y fijamos el valor
					$datosReg = $smarty->_tag_stack[$punteroPilaAbuelo][1]['datos'][$iterActual];
					//Asignamos el valor de registro, controlando el PEAR:DB
					$valueReg=null;
					if (!isset($valueReg)) $valueReg = $datosReg[strtolower($params['id'])];
					if (!isset($valueReg)) $valueReg = $datosReg[$params['id']];
					if (!isset($valueReg)) $valueReg = $datosReg[strtoupper ($params['id'])];
					if (isset($valueReg) && ($valueReg!="")) $value = htmlentities($valueReg, ENT_COMPAT | ENT_HTML401, 'ISO-8859-1');
					else $value='';
					$dblClick .= "Open_Vtna('".$value."','Documento','700','500','no','no','no','no','yes','yes')";
					$id = "vs_".$campo;
			break;
			case 'ayuda':
				$smarty->igepPlugin->registrarInclusionJS('window.js');
				if ($rutaManual != '')
				{
					$llamadaJavascript .= "Open_Vtna('doc/".$rutaManual."','urlAbs',700,500,'no','no','no','no','yes','yes');";
				}
				else 
				{
					$llamadaJavascript .= "alert('No hay manual de la aplicación');";
				}
			break;
			
			case 'actualizaCampos':
				//parametros necesarios para utilizar esta opción:
				//titulo:contendrá el nombre del campo de texto 
				//formActua: para saber quien es el panel y poder componer la llamada a la función dl objComprobación
				//panelActua: para componer el nombre del campo, q luego será el origen
				//actuaSobre: array con los elementos que se quieren actualizar
				//funcion="actualizaCampos";
				//$nomObjeto = "bttlActualizar_".$idAbuelo;
					
//				//Tenemos que distinguir si es una insercion(ins) o una modificacion(cam)
				$numRegTotales = count($smarty->_tag_stack[$punteroPilaAbuelo][1]['datos']); 
				if ($CWAbuelo != 'CWContenedor')
				{
					if (($numRegTotales == 0) or ($iterActual >= $numRegTotales))
						$nombre = 'ins___'.$idBtn.'___'.$panelActua."_".$iterActual;
					else
						$nombre = 'cam___'.$idBtn.'___'.$panelActua."_".$iterActual;
				}
				else
					$nombre = $idBtn;			
				$hidden .= "<input type='hidden' name='".$nombre."' id='".$nombre."' value='' />";
				$llamadaJavascript .= "objCampo = document.getElementById('".$nombre."');objCampo.value = 'S';".$idPanel."_comp.actualizarElemento(objCampo,'".$params['actuaSobre']."');";
		
				$id = $idBtn;
				if ($CWAbuelo == "CWContenedor")
				{
					$campo = $actuaSobre;
					$id = 'func_'.$campo;
				}
				if (($CWAbuelo == "CWTabla") || ($CWAbuelo == "CWFichaEdicion"))
				{
					$campo = $actuaSobre."___".$panelActua."_".$iterActual;
					$id = 'func_'.$campo;
				}
			break;
			case 'exportCSV':
				$llamadaJavascript = '';
				if ($mostrarEspera == true) {
					$llamadaJavascript .= "aviso.mostrarMensajeCargando('".$textoMostrarEspera."');";
				}
				$llamadaJavascript .= "document.forms['".$nomForm."'].target='oculto';";
				$llamadaJavascript .= "document.forms['".$nomForm."'].action='phrame.php?action=exportCSV&actuaSobre=".$actuaSobre."&claseManejadora=".$claseManejadora."';";
				$llamadaJavascript .= "document.forms['".$nomForm."'].submit();";
								
			break;

			case 'print':
				$titulo = $smarty->_tag_stack[$punteroPilaPadre][1]['titulo'];
				$rutaPrint = "phrame.php?action=defaultPrint&actuaSobre=".$actuaSobre."&claseManejadora=".$claseManejadora."&titulo=".$titulo;	
				if ($mostrarEspera == true) {
					$llamadaJavascript .= "aviso.mostrarMensajeCargando('".$textoMostrarEspera."');";
				}			
				$llamadaJavascript .= "Open_Vtna('".$rutaPrint."','urlAbs',700,500,'no','no','no','no','yes','yes');";
			break;

		}//Fin switch
	}//Fin if actua sobre
	
	// Si el botón lleva asociado un action se realiza un submit
	if ($action != '')
	{	
		if ($mostrarEspera == true) {
			$llamadaJavascript .= "aviso.mostrarMensajeCargando('".$textoMostrarEspera."');";
		}
		$llamadaJavascript .= "document.forms['".$nomForm."'].target='oculto';";
		$llamadaJavascript .= "document.forms['".$nomForm."'].action='phrame.php?action=".$action."';";
		$llamadaJavascript .= "document.forms['".$nomForm."'].submit();";
	}
	
	$boton = $titulo; // Aparecera solamente el titulo si no se ha puesto la imagen
	$icono = '';
	$ruta = '';
	if ($params['iconCSS'])
	{
		$icono = $params['iconCSS'];
		$ruta = '';
		$rutaOff = '';
		$rutaTrans = '';
	}
	else 
	{
		$ruta = $params['imagen'];
		$ruta = IMG_PATH_CUSTOM."botones/".$params['imagen'].".gif";
		$rutaOff = IMG_PATH_CUSTOM."botones/".$params['imagen']."off.gif";
		$rutaTrans = IMG_PATH_CUSTOM."pestanyas/pix_trans.gif";
	}
	if (($ruta != '') || ($icono != '')) 
	{		
		//Identificador del boton en el form, ej. "btnfil_buscar" ej "btncalculo_particular"
		if ($idBtn == null)
			$idBtn = 'btn'.$smarty->_tag_stack[$punteroPilaAbuelo][1]['id'].'_'.$funcionBtn;
		else
		// Cuando el botón no pertenece al panel sino dentro de la ventana
			$idBtn = 'btn'.$idBtn.'_'.$funcionBtn.$smarty->_tag_stack[$punteroPilaAbuelo][1]['id'];
		
		// Establecemos un class para poder conocer el estado desde javascript
		switch($editable)
		{
			case "true":
			case "si":
				$class = "class='tableEdit'";
				$dataState = "data-gvhstate = 'edit'";	
			break;
			case "false":
			case "no":
				$class = "class='tableNoEdit'";
				$dataState = "data-gvhstate = 'noEdit'";
			break;
			case "nuevo":
				$class = "class='tableNew'";
				$dataState = "data-gvhstate = 'new'";
			break;
			default:
				$class = "class='tableEdit'";
				$dataState = "data-gvhstate = 'edit'";
			break;
		}
		
		if (($CWPadre != 'CWFicha') || ($CWPadre != 'CWFila'))
			$class = '';
				

		$boton = '';
		if ($funcionBtn == 'openDoc') // Botón que abrirá un documento
		{
			if ($value != '') // Si el registro tiene asociado documento se genera el botón, sino no se genera botón
			{
				$numRegTotales = count($smarty->_tag_stack[$punteroPilaAbuelo][1]['datos']);
				$iterActual = $smarty->_tag_stack[$punteroPilaPadre][2];
		
				$aperturaCapa = "<span id='IGEPVisibleBtn".$campo."'";
				$aperturaCapa .=" style='visibility:visible;'>";
				$cierreCapa = '</span>';
		
				if ($icono != '')
				{
					$boton = "<button type='button' id='".$id."' title=\"".$titulo."\" style='display:inline;' class='btnToolTip' onClick=\"javascript:".$dblClick."\">\n";
					$boton .= "<span id='icon_".$id."' class='".$icono.$ruta."' aria-hidden='true'></span> ";
					$boton .= "</button>";
				}
				else 
				{
					$boton = "<img id=\"".$id."\" src=\"".$ruta."\" border='0' title=\"".$titulo."\" alt=\"Btn_".$funcionBtn."\" onClick = \"".$dblClick."\" />";
				}
				$boton = $aperturaCapa.$boton.$cierreCapa;
			}
		}
		else
		{
			// El parámetro editable solamente tiene que afectar a los botones que estén en una ficha o tabla
			/////////////////////////////////////////////////////////
			// Si no es un botón de ventana de selección y 
			if ( (($funcionBtn != 'abrirVS') && ($funcionBtn != 'abrirvs')))
			{	
				if ((($funcionBtn == 'actualizaCampos') || ($funcionBtn == 'saltar')) && (($CWPadre == 'CWFicha') || ($CWPadre == 'CWFila')))
				{				
					$aperturaCapa = "<span id='IGEPVisibleBtn".$campo."'";
					$aperturaCapa .= " style='visibility:visible;'>";//Fin style

					if ($icono == '')
					{
						if (// 	NO estoy en un panel de búsqueda. 		
							( ($CWPadre == 'CWFila') || ($CWAbuelo == 'CWFichaEdicion') )   
							&& ($smarty->_tag_stack[$punteroPilaPanel][1]['accion'] != 'modificar')
							&& ($smarty->_tag_stack[$punteroPilaPanel][1]['accion'] != 'insertar')
							)
								$boton = "<img id='$id' src='$rutaOff' style='cursor:pointer;' border='0'";
						else 
								$boton = "<img id='$id' src='$ruta' style='cursor:pointer;' border='0'";
						
						$boton .= " title='$titulo' alt='Btn_$funcionBtn' ";
						// Comprobamos si la imagen está activa o no (off) para que ejecute, o no, la apertura de la ventana modal o el salto.
						$boton .= "onClick=\"javascript:";
						$boton .= "bt = eval(document.getElementById('".$id."'));";
					    $boton .= "expr = /off/;";
						$boton .= "if (!expr.test(bt.src)) { $llamadaJavascript }\" />";
					}
					else 
					{
						if (// 	NO estoy en un panel de búsqueda. 		
							( ($CWPadre == 'CWFila') || ($CWAbuelo == 'CWFichaEdicion') )   
							&& ($smarty->_tag_stack[$punteroPilaPanel][1]['accion'] != 'modificar')
							&& ($smarty->_tag_stack[$punteroPilaPanel][1]['accion'] != 'insertar')
							)
						{
							$boton = "<button type='button' title=\"".$titulo."\" id='".$id."' data-gvhposition='panel_$idPanel' style='display:inline;' class='btnToolTip disabled' onClick=\"javascript:".$llamadaJavascript."\">\n";
							$boton .= "<span id='icon_".$id."' class='".$icono.$ruta."' aria-hidden='true'></span> ";
						}
						else
						{
							$boton = "<button type='button' title=\"".$titulo."\" id='".$id."' data-gvhposition='panel_$idPanel' style='display:inline;' class='btnToolTip' onClick=\"javascript:".$llamadaJavascript."\">\n";
							$boton .= "<span id='icon_".$id."' class='".$icono.$ruta."' aria-hidden='true'></span> ";
						}
						$boton .= "</button>";
					}
					
					$cierreCapa = "</span>";
					$boton = $aperturaCapa.$boton.$cierreCapa;
				}
				else 
				{	
					if ($icono == '')
					{
						if ($nomObjeto != '')
							$boton ="<img id='img_$nomObjeto' src='$ruta' $class style='cursor:pointer;' border='0'";
						else
							$boton ="<img id='img_$nombre' src='$ruta' $class style='cursor:pointer;' border='0'";
						$boton.=" title='$titulo' alt='Btn_$funcionBtn' onClick=\"javascript:$llamadaJavascript\" />";
					}
					else {
						$idBoton = 'img_'.$nombre;
						if ($nomObjeto != '')
							$idBoton = 'img_'.$nomObjeto;
						$boton = "<button type='button' id='".$idBoton."' title=\"".$titulo."\" style='display:inline;' class='btnToolTip' data-gvhposition='botonera' data-gvhpanelOn='panel_$idPanel' onClick=\"javascript:".$llamadaJavascript."\">\n";
						$boton .= "<span id='icon_".$idBoton."' class='".$icono.$ruta."' aria-hidden='true'></span> ";
						$boton .= "</button>";
					}
				}
			}
			else
			{ 
				$numRegTotales = count($smarty->_tag_stack[$punteroPilaAbuelo][1]['datos']);
				$iterActual = $smarty->_tag_stack[$punteroPilaPadre][2];
				
				// Creamos una capa para poder ocultar y visualizar el botón tooltip de la ventana de selección.
				// Debería ser un botón como el del calendario.
				$aperturaCapa = "<span id='IGEPVisibleBtn".$campo."'";
				$aperturaCapa .=" style='visibility:visible;'>";//Fin style	
				$cierreCapa = '</span>';
				
				// Comprobación en una tabla para las filas q serán para insertar nuevos registros pondremos el botón ToolTip en transparente
				if ( ($CWPadre == 'CWFila') && ($iterActual >= $numRegTotales) )
				{
					if ($icono != '')
					{
						$boton = "<button type='button' id='".$id."' title=\"".$titulo."\" data-gvhModo='insert' style='display:inline;' class='btnToolTip off' onClick=\"javascript:".$dblClick."\">\n";
						$boton .= "<span id='icon_".$id."' class='".$icono.$ruta."' aria-hidden='true'></span> ";
						$boton .= "</button>";
					}
					else 
					{
						$boton = "<img id=\"".$id."\" src=\"".$rutaTrans."\" border='0' title=\"".$titulo."\" alt=\"ffBtn_".$funcionBtn."\" onClick = \"".$dblClick."\" />";
					}
				}
	
				elseif (( ($CWPadre == 'CWFila') || ($CWAbuelo == 'CWFichaEdicion') )   
					&& ($smarty->_tag_stack[$punteroPilaPanel][1]['accion'] != 'modificar')
					&& ($smarty->_tag_stack[$punteroPilaPanel][1]['accion'] != 'insertar')
					
				)
				{	// Botón desactivado pq el panel está desactivado
					if ($icono != '')
					{
						$boton = "<button type='button' id='".$id."' title=\"".$titulo."\" data-gvhposition='panel_$idPanel' style='display:inline;' class='btnToolTip disabled' onClick = \"".$dblClick."\" />";
						$boton .= "<span id='icon_".$id."' class='".$icono.$rutaOff."' aria-hidden='true'></span> ";
						$boton .= "</button>";
					}
					else 
					{	
						$boton = "<img id=\"".$id."\" src=\"".$rutaOff."\" border='0' title=\"".$titulo."\" alt=\"ffBtn_".$funcionBtn."\" onClick = \"".$dblClick."\" />";
					}
				}
				else
				{// Botón activado pq el panel está activado
					if ($icono != '')
					{
						$boton = "<button type='button' id='".$id."' title=\"".$titulo."\" data-gvhposition='panel_$idPanel' style='display:inline;' class='btnToolTip' onClick=\"javascript:".$dblClick."\">\n";
						$boton .= "<span id='icon_".$id."' class='".$icono.$ruta."' aria-hidden='true'></span> ";
						$boton .= "</button>";
					}
					else
					{
						$boton = "<img id=\"".$id."\" src=\"".$ruta."\" border='0' title=\"".$titulo."\" alt=\"Btn_".$funcionBtn."\" onClick = \"".$dblClick."\" />";
					}
				}
				$boton = $aperturaCapa.$boton.$cierreCapa;
			}
		}
		/////////////////////////////////////////////////////////
	}
	if ( $nomObjeto != '' ) //Registramos el objeto JS
		$smarty->igepPlugin->registerJSObj($nomObjeto);
		
	$igepSmarty->addPreScript($llamadas_js);
	if ($CWPadre == "CWFila") // Si el botón está en una fila de una tabla
	{
		$referencia = $titulo;
		$v_titulo = $smarty->_tag_stack[$punteroPilaAbuelo][1]['titulosColumnas'];
		$v_titulo[$referencia] = '';			
		$smarty->_tag_stack[$punteroPilaAbuelo][1]['titulosColumnas'] = $v_titulo;
		$smarty->_tag_stack[$punteroPilaAbuelo][6]['sizes'][] = '2';
		$smarty->_tag_stack[$punteroPilaAbuelo][6]['sizes']['total'] += intval('2');
		return("<td>".$igepSmarty->getPreScript().$hidden.$boton."</td>\n");
	}
	else
		return($igepSmarty->getPreScript().$hidden.$boton."\n");
}
?>