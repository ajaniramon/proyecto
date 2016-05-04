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
require_once ('igep/include/IgepSmarty.php');

function smarty_function_CWBoton($params, & $smarty) {

	$igepSmarty = new IgepSmarty();
	
	/* VALORES DE LA PILA */
	$punteroPilaPadre = count($smarty->_tag_stack) - 1;
	$punteroPilaAbuelo = $punteroPilaPadre -1;
	$CWAbuelo = $smarty->_tag_stack[$punteroPilaAbuelo][0];
	$tipoComprobacion = $smarty->_tag_stack[$punteroPilaAbuelo][1]['tipoComprobacion'];
	$idPanel = $smarty->_tag_stack[$punteroPilaAbuelo][1]['id'];
	$claseManejadora = $smarty->_tag_stack[$punteroPilaAbuelo][1]['claseManejadora'];
	$nomForm = "F_".$idPanel;
	/* Parámetros */
	//Que estemos en un panel tres modos en el modo edi.
	//Este último caso sólo se da cuando en el Panel el parametro accion esta activo.
	// ['insertar','modificar','borrar'] --> tendrá valor cuando nos encontremos en tres modos, en el panel edi
	//resumen, si accionActiva es != de '' es que estamos en un tres modos
	$accionActiva = $smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'];
	
	//Se establece un estilo por defecto para este componente especificamente en caso de no tener ninguna especificada
	if ($params['class']) 
	{
		$class = $params['class'];
	}
	else
	{
		$class = "button";
	}
	
	// Imagen del botón
	$icono = '';
	$ruta = '';
	if ($params['iconCSS'])
	{
		$icono = $params['iconCSS'];
	}
	else
	{
		if ($params['imagen'])
			$ruta = IMG_PATH_CUSTOM."acciones/".$params['imagen'].".gif";
	}	
	
	// Texto alternativo de la imagen
	if ($params['texto']) {
		$texto = $params['texto'];
	} else {
		$texto = '';
	}

	// Función añadida a la de por defecto que tenga el botón
	$funcion = '';
	if (isset ($params['funcion'])) {
		$funcion = $params['funcion'];
	}

	// Acción indica el tipo de botón ['guardar','cancelar'....]
	if ($params['accion']) {
		$accion = $params['accion'];
	} else {
		$accion = '';
	}

	// Id del Botón para referenciarlo
	$id = null;
	if ($params['id']) {
		$id = $params['id'];
	}	

	
	//Opciones para establecer la visibilidad "inicial"
	//Si el parámetro visible está fijado, debe prevalecer
	//sobre el comportamiento del patrón. En otro caso (no fijado)
	//será la lógica de l patrón la que decida al repecto
	$forzarVisibilidad = false; //Por defecto manda el Framework
	$visibilidad = 'none'; //Por defecto el botón  es invisible
	if (isset($params['visible']))
	{
		$forzarVisibilidad = true;
		if (
			(strtolower(trim($params['visible'])) == 'false')
			|| (strtolower(trim($params['visible'])) == 'falso')
			|| (strtolower(trim($params['visible'])) == 'no')
			|| (strtolower(trim($params['visible'])) == 'oculto')
			|| (strtolower(trim($params['visible'])) == 'invisible')
			|| ($params['visible'] === false)
		)
		{
			$visibilidad = 'none';
		}
		else
		{
			$visibilidad = 'inline';
		}
	}//Fin visible
	
	$confirm = '';
	$codigo = $params['confirm'];
	if (isset($codigo) && (trim($codigo)) != '')
	{
		$mensaje = new IgepMensaje($codigo);
		$tipo =  $mensaje->getTipo();	
		$codError =  $mensaje->getCodigo();
		$descBreve = $mensaje->getDescripcionCorta();	
		$textoAviso = $mensaje->getDescripcionLarga();
				 
		$confirm .= "confirm.set('confirm','capaAviso',";
		$confirm .= "'".$tipo."',";
		$confirm .= "'".$codError."',";
		$confirm .= "desescapeIGEPjs('".$descBreve."'),";
		$confirm .= "desescapeIGEPjs('".$textoAviso."'),'No','Si',this.form.name);";
		$confirm .= "confirm.mostrarAviso();\n";		
	}
	
	//  ESTABLECEMOS EL action QUE HA DE EJECUTAR EL FORMULARIO
	$actionForm = '';
	$actionFormPanel = $smarty->_tag_stack[$punteroPilaAbuelo][1]['action'];
	if (isset($params['action'])) 
	{
		if (strpos($params['action'], '__')) //Se Incluye la clase Manejadora?
		{
			$actionForm = $params['action'];
		} 
		else 
		{
			$actionForm = $claseManejadora.'__'.$params['action'];
			// Existe el parámetro action en la tpl por lo q hay q cambiarlo para el formulario			
		}
	} 
	elseif ($actionFormPanel != '') 
	{	//Si tiene action el panel
		$actionForm = $claseManejadora.'__'.$actionFormPanel;
	}
	else
	{	
		//Sino la accion activa
		if ($accion != 'cancelarModal')
			$actionForm = $claseManejadora.'__'.$smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'];
	}//Fin parametro action
	
	// Parámetros necesarios para el botón Cancelar de 
	// una ventana y para una ventana de selección
	if ($params['formActua']) 
		$formActua = $params['formActua'];
	
	// Parámetros solo para la ventana de selección 
	if (isset ($params['filaActual']))
		$filaActual = $params['filaActual'];
		
	if ($params['panelActua'])
		$panelActua = $params['panelActua'];
		
	if ($params['actuaSobre'])
		$actuaSobre = $params['actuaSobre'];

	//PRUEBA INESTABLE BORRAME
	//Mensajito de cargando para búsquedas largas... se plantea como
	//parámetro para el CWBoton, aunque podríamos usar un "prebuscar"
	//Y usar un aviso normal...
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
	
	//mostrarEspera	
		
	//////////////////////////////////////////////////////////////////////////////////////
	// CODIGO NECESARIO PARA CADA COMPONENTE //
	//////////////////////////////////////////////////////////////////////////////////////
	// Primero defino el nombre del componente.
	$n_comp = "CWBoton";
	// Incrementamos  el número de componentes CWBoton
	$num = $smarty->igepPlugin->registrarInstancia($n_comp);

	if ($params['name'])
	{
		$nombre = $params['name'];
	} else {
		$nombre = $n_comp.$num;
	}
	//////////////////////////////////////////////////////////////////////////////////////
	// FIN CODIGO NECESARIO DE CADA COMPONENTE //
	//////////////////////////////////////////////////////////////////////////////////////
	
	// Vector los campos a comprobar (obligatorio, tipo ['numérico','caracter','nif','fecha'])

	$script = '';
	$accion = strtolower($accion);
	$ejecutarForm = '';
	$finFuncion = "";

	//////////////////////////////////////////////////////////////////////////////////////
    // Comprobación de campos cuando es un botón diferente de "Cancelar"
	if (
		($tipoComprobacion != '')
		&& ($accion != 'cancelar')
		&& ($accion != 'cancelarmodal')
		&& ($tipoComprobacion != 'cambioFoco')
		&& (($tipoComprobacion == 'envio') || ($tipoComprobacion == 'todo'))
	)
	{
		$finFuncion .= "}";
		$funcion .= "if (".$idPanel."_comp.comprobarObligatorios(this.form) == false) {";
		$funcion .= "campos = ".$idPanel."_comp.getCamposErroneos();";
		$funcion .= "error = 'Debe introducir un valor en los campos: '+campos;";
		$funcion .= "aviso.set('aviso','capaAviso','aviso','IGEP-901','Faltan campos por rellenar',error);";
		$funcion .= "aviso.mostrarAviso();";
		$funcion .= "}";
		$funcion .= "else {";
		$funcion .= $confirm;
	}
	//////////////////////////////////////////////////////////////////////////////////////
		
	//////////////////////////////////////////////////////////////////////////////////////
	// RESULTADO EN UNA VENTANA DIFERENTE
	//////////////////////////////////////////////////////////////////////////////////////	
	$openWindow = false;
	if (($params['openWindow'] == true) || ($params['openWindow'] == 'true') || ($accion == 'listar')) 
	{
		$openWindow = true;
		$smarty->igepPlugin->registrarInclusionJS('window.js');
		$funcion .= "Open_Vtna('igep/blanco.htm','ventana',700,500,'no','no','no','no','yes','yes');\n";
		$funcion .= "this.form.target = 'ventana';\n";
	}
	//////////////////////////////////////////////////////////////////////////////////////	
	
	$funcion .= "this.form.action='phrame.php?action=".$actionForm."';";	
	if ($mostrarEspera == true) {
		$funcion .= "aviso.mostrarMensajeCargando('".$textoMostrarEspera."');";
	}
	//////////////////////////////////////////
	// ACCIÓN QUE REALIZARÁ EL BOTÓN        //
	// la acción irá siempre en minusculas  //
    //////////////////////////////////////////
	
	$accion = strtolower($accion);	
	switch ($accion) 
	{
		////////////////
		///  BUSCAR  ///
		////////////////
		case 'buscar' :	
			if ($confirm == '')
			{
				$funcion .= "this.form.target='oculto';";
				$funcion .= "this.form.submit();";
			}
			$funcion .= $finFuncion;
			if (!$forzarVisibilidad) $visibilidad = 'inline'; //Accion por defecto según patrón
		break;
		////////////////////////
		///  GUARDAR/SUBMIT  ///
		////////////////////////
		case 'guardar' :
		case 'submit' :
			if ($openWindow == false) // Si no se abre ventana se ejecuta por el oculto
				$funcion .= "this.form.target='oculto';";
			if ($confirm == '')
				$funcion .= "this.form.submit();";
			$funcion .= $finFuncion;
			//Si estamos en tres modos y NO se fuerza la visibilidad, el boton debe ser visible
			if ((trim($accionActiva) != '') && !($forzarVisibilidad) )//si tabular-registro (3 modos)...
			{
				$visibilidad = 'inline';
			}
			else if (!$forzarVisibilidad) $visibilidad = 'none'; //Si no estamos en tres modos y la visibilidad NO está forzada
		break;			
		///////////////////////
		///  SALTAR/VOLVER  ///
		///////////////////////
		case 'saltar' :
		case 'volver' :
			if ($accion == 'saltar')
				$action = 'IgepSaltoVentana';
			else
				$action = 'IgepRegresoVentana';
			$espera = '';
			if ($mostrarEspera == true) 
				$espera = "aviso.mostrarMensajeCargando('".$textoMostrarEspera."');";
			$funcion = $espera;
			if ($confirm == '')
			{
				$funcion .= "this.form.action='phrame.php?action=".$action."&idBotonSalto=".$id."&formActua=".$nomForm."&idBtn=".$id."';";
				$funcion .= "this.form.target='oculto';";
				$funcion .= "this.form.submit();";
			}
			else
			{
				$action = "phrame.php?action=".$action."&idBotonSalto=".$id."&formActua=".$nomForm."&idBtn=".$id;
				$confirm = $espera."confirm.set('confirm','capaAviso',";
				$confirm .= "'".$tipo."',";
				$confirm .= "'".$codError."',";
				$confirm .= "desescapeIGEPjs('".$descBreve."'),";
				// Necesitamos pasar el action del destino
				$confirm .= "desescapeIGEPjs('".$textoAviso."'),'No','Si',this.form.name,'".$action."');";
				$confirm .= "confirm.mostrarAviso();\n";
			}
			if (!$forzarVisibilidad) $visibilidad = 'inline'; //Accion por defecto según patrón
		break;
		///////////////////
		///   LISTAR    ///
		///////////////////			
		case 'listar' :
			if ($confirm == '')
				$funcion .= "this.form.submit();";
			$funcion .= $finFuncion;
			if (!$forzarVisibilidad) $visibilidad = 'inline'; //Accion por defecto según patrón
		break;
		///////////////////////////////////
		///  PARTICULARIZAR/PARTICULAR  ///
		///////////////////////////////////
		case 'particularizar' :
		case 'particular' :
			$funcion .= "this.form.target='oculto';";
			if(!isset($params['confirm']))
				$funcion .= "this.form.submit();";
			$funcion .= $finFuncion;
			$oculto = '';
			if (!$forzarVisibilidad) $visibilidad = 'inline'; //Accion por defecto según patrón
		break;
		//////////////////
		///  CANCELAR  ///
		//////////////////
		case 'cancelar':
			// El botón cancelar lleva su propio action
			if ($params['action'])
			{
				// Si ese action es "refrescar" se realizará un reload de la pantalla
				if ($params['action'] == 'refrescar')
				{
					$funcion = $idPanel."_comp.bloquearSalida(false);";
					$funcion .= "this.form.reset();";
					$funcion .="window.location.reload();";					
				}
				else
				{
					// Ejecuta el action que ha indicado el programador en la tpl
					$funcion .= $idPanel."_comp.bloquearSalida(false);";
					$funcion .= "this.form.target='oculto';this.form.submit();";
				}
			}
			else
			{
				if (trim($accionActiva) != '')
				{
					// Estamos en un caso de 3 modos
					$funcion = "this.form.action='phrame.php?action=".$claseManejadora."__cancelarEdicion';";
					$funcion .= "this.form.target='oculto';this.form.submit();";
				}
				else
				{
					// Recargar para el caso del maestro/detalle, tabla o ficha
					$funcion = $idPanel."_comp.bloquearSalida(false);";
					$funcion .= "fo = document.getElementById('oculto');";
					$funcion .= "if (fo.src.indexOf('?') == -1)";
					$funcion .= "fo.src = fo.src+'?cancelado'; ";
					$funcion .= "else fo.src = fo.src+'&amp;cancelado'; ";
					$funcion .= "this.form.reset();";
					$funcion .= "setTimeout('window.top.location.reload()', 220);";
				}
			}
			$funcion .= $finFuncion;
			if ((trim($accionActiva) != '') && !($forzarVisibilidad) )//si tabular-registro (3 modos)...
			{
				$visibilidad = 'inline';
			}
			else if (!$forzarVisibilidad) $visibilidad = 'none'; //Si no estamos en tres modos y la visibilidad NO está forzada
		break;
//////////////////////////////////////////////////////////////////////////		
// REVIEW: Vero - VENTANA MODAL				
		/////////////////////////////
		///  GUARDAR/SUBMIT MODAL ///
		/////////////////////////////
		
/************ MODAL *********************/
		case 'guardarmodal' :
		case 'submitmodal' :
			// Guardamos el trabajo hecho en la ventana modal
			$funcion .= "this.form.target='oculto';";
			$funcion .= "this.form.submit();";
			
			$funcion .= "returnModal('submit');";
			$funcion .= $finFuncion;
			$visibilidad = 'inline';
		break;		
		
		///////////////////////
		///  ACCIÓN MODAL ///
		///////////////////////
		case 'accionmodal':
			$funcion .= "returnModal('accion');";
			$funcion .= $finFuncion;
			$visibilidad = 'inline';
		break;
		
		///////////////////////
		///  CANCELAR MODAL ///
		///////////////////////
		case 'cancelarmodal':			
			$funcion = "returnModal('cancel');";
			
					$funcion .= "fo = document.getElementById('oculto');";
					$funcion .= "if (fo.src.indexOf('?') == -1)";
					$funcion .= "fo.src = fo.src+'?cancelado'; ";
					$funcion .= "else fo.src = fo.src+'&amp;cancelado'; ";
					$funcion .= "this.form.reset();";
					$funcion .= "setTimeout('window.top.location.reload()', 220);";
			$funcion .= $finFuncion;
			$visibilidad = 'inline';
		break;		
		
/************ MODAL *********************/
		
		////////////////////
		///  CANCELARVS  ///
		////////////////////
		case 'cancelarvs' :
			$funcion .= "aceptarCancelarSeleccion('cancelar',this.form.actionOrigen.value,this.form,'','','".$formActua."','')";
			$funcion .= $finFuncion;
			$visibilidad = 'inline'; //Si el botón debe o no debe visualizarse
		break;
		///////////////////
		///  ACEPTARVS  ///
		///////////////////
		case 'aceptarvs' :
			if (isset ($filaActual) && isset ($panelActua) && isset ($formActua) && (count($actuaSobre) > 0)) 
			{
				$funcion .= "aceptarCancelarSeleccion('aceptar',this.form.actionOrigen.value,this.form,'".$filaActual."','".$panelActua."','".$formActua."'";
				$fieldsTPL = $actuaSobre['fieldsTPL'];
				if (count($fieldsTPL) > 0)
					$funcion .= ",[";
				for ($i = 0; $i < count($fieldsTPL); $i ++) 
				{
					$funcion .= "'".$fieldsTPL[$i]."'";
					if ($i == (count($fieldsTPL) - 1)) 
					{
						$funcion .= "],";
					} //if
					else 
					{
						$funcion .= ",";
					} //else					
				} //for
				$fieldsSource = $actuaSobre['fieldsSource'];
				if (count($fieldsSource) > 0)
					$funcion .= "[";
				for ($i = 0; $i < count($fieldsSource); $i ++) 
				{
					$funcion .= "'".$fieldsSource[$i]."'";
					if ($i == (count($fieldsSource) - 1)) 
					{
						$funcion .= "]";
					} //if
					else 
					{
						$funcion .= ",";
					} //else					
				} //for				
				$funcion .= ");\n";
			} //if
			else 
			{
				//REVIEW: incluir capa de aviso en ventana selección
				//$funcion .= "aviso.set('error','capaAviso','aviso','E/S IU','Tienes que realizar una búsqueda','La accion a realizar es necesariamente una búsqueda');";
				//$funcion .= "aviso.mostrarAviso();";					
				$funcion .= "alert('Tienes que realizar una búsqueda.')\n";
			}
			$funcion .= $finFuncion;
			$visibilidad = 'inline'; //Si el boton debe o no debe visualizarse
		break;
	}//Fin Switch

	//Identificador del boton en el form, ej. "bnfil_buscar" ej "bncalculo_particular"
	if ($id == null)
		$id = 'bn'.$smarty->_tag_stack[$punteroPilaAbuelo][1]['id'].'_'.$accion;
	else
		$id = 'bn'.$id.'_'.$accion.$smarty->_tag_stack[$punteroPilaAbuelo][1]['id'];


	if ($icono == '')
	{
		$boton .= "\n<button style='display:".$visibilidad."; ' id='".$id."' name='".$id."' type='button' class=".$class." onmouseover=\"this.className='".$class."_on';\" onmouseout=\"this.className='".$class."';\" onClick=\"javascript:".$funcion."\">\n";
	    if($ruta!='')
    	    $boton .= "<img src='$ruta' style='border-style:none;vertical-align:middle;' alt='$texto' title='$texto' />$texto \n";
    	else
        	$boton .= "<img src='$ruta' style='border-style:none;vertical-align:middle;' alt='$texto' title='$texto' /> \n";
		$boton .= "</button>\n";
	}
	else {
		switch ($idPanel){
			case 'fil':
			case 'vSeleccion':
				$classBtn = 'btn_fil';				
			break;
			case 'lis':
			case 'edi':
				$classBtn = 'btn_primary';
			break;
			case 'lisDetalle':
			case 'ediDetalle':
				$classBtn = 'btn_detail';
			break;
		}
		
		$boton = "<button type='button' id='".$id."' style='display:".$visibilidad."' class='".$classBtn."' onClick=\"javascript:".$funcion."\">\n";
		$boton .= "<span class='".$icono."' aria-hidden='true'></span> ".$texto;
		$boton .= "</button>";
	}
	return ($script.$boton);
}
?>