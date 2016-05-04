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
* Igep Pantalla es una clase que utilizamos para definir el comportamiento general de las pantallas.
* Definimos el comportamiento de las pestañas asi como la definición de los mensajes de aviso. En principio hemos hemos hecho una distincion 
* entre la pantalla que puede ser o bien de tipo ficha o bien de tipo tabla
* @package gvHIDRA
*/
class IgepPantalla {

/**
 * Paneles de la pantalla
 * @access private
 * @var vector
 */
var $v_paneles;

/**
 * Constructor de la clase. Introduce la referencia del menu y la de las Barras de
 */
function IgepPantalla(){ 	 	
	global $s;

 	/*Parte genérica de la ventana*/
 	/*Menu*/
	//Asignamos el nombre del menu
	$s->assign("smty_nombre","menu");
	//Recogemos los menus de la Session
	$menuActv = IgepSession::dameVariable("global","menuActv");
	$v_menus = IgepSession::dameVariable("global",$menuActv);
	
	//Seleccionamos el perteneciente al módulo activo
	$menu = $v_menus[IgepSession::dameVariable("global","modActv")]; 
	//Asignamos el menu
	$s->assign("smty_cadenaMenu",$menu);
	
	//Datos de la Barra	      
	$usuario = strtoupper(IgepSession::dameUsuario())."@".strtoupper(IgepSession::dameBaseDatos());
	$s->assign("smty_usuario",$usuario);
	$datosAplicacion = IgepSession::dameDatosAplicacion();
	$codigo = $datosAplicacion["daplicacion"];
	//Obtenemos clase configuracion
	$conf = ConfigFramework::getConfig();
	//Obtenemos la version
	$version = $conf->getAppVersion();
	if (!empty($version))
	    $codigo.=' &nbsp;v.'.$version;
	$s->assign("smty_codigo",$codigo);
	
	//Obtenemos el valor del customTitle
	$customTitle = $conf->getCustomTitle();
	if (!empty($customTitle))
	{
		$customTitle =' &nbsp;'.$customTitle.'&nbsp;';
	}
	$s->assign("smty_customTitle", $customTitle);
	
	//Obtenemos el valor del barTitle
	$barTitle = $conf->getBarTitle();
	if (!empty($barTitle))
	{
		$barTitle =' &nbsp;'.$barTitle.'&nbsp;';
		$s->assign("smty_customTitle", $barTitle);//TODO: Versión 4.1 cambio de variable pendiente
	}
	$s->assign("smty_barTitle",$barTitle);
	
	//Obtenemos el applicationName
	$applicationName = $conf->getApplicationName();
	if (!empty($applicationName))
		$s->assign("smty_tituloApl",$applicationName);
		
	$this->v_paneles =array();
	}

/**
 * Añade un panel a la pantalla
 * @param mixed ???
 * @return mixed ???
 */
function agregarPanel($obj_panel){		
	global $s;
	//Lanzamos el método que marcara el estado del panel
	$obj_panel->comportamientoPanel();
	//En el caso de que tenga javascript para el onLoad	
	$obj_IgepSmarty = & $obj_panel->obj_clase->obj_IgSmarty;

	if(is_object($obj_IgepSmarty)) {
		$jsLoad = $obj_IgepSmarty->getScriptLoad(false);
		if($jsLoad!='')
			$s->assign('smty_jsOnLoad',$jsLoad);
		IgepSession::guardaVariable($obj_panel->str_claseManejadora,'obj_IgSmarty',$obj_IgepSmarty);	
	}
	else 
		IgepDebug::setDebug(WARNING,'VIEWS: problemas al cargar la clase desde SESSION');

	
	//Almacenamos el panel	
	$this->v_paneles[$obj_panel->str_claseManejadora] = & $obj_panel;
	
	/*************** MODAL ****************/
	//Comprobamos si el panel esta abierto como modal
	if (!empty($obj_panel->obj_clase)) {
		if(method_exists($obj_panel->obj_clase,'isModal')){
			if($obj_panel->obj_clase->isModal())
				$s->assign("smty_modal",true);
		}
		else 
			IgepDebug::setDebug(WARNING,'VIEWS: problemas al cargar la clase desde SESSION');
	}
	/*************** FIN MODAL ****************/
	
	
	return $obj_panel;
}


function agregarPanelArbol($obj_panel){
	global $s;
	$obj_panel->comportamientoPanel();	
	$this->v_paneles[$obj_panel->str_claseManejadora] = & $obj_panel;
	if(isset($obj_panel->panelAsociado)){
		$this->agregarPanel($obj_panel->panelAsociado);
		//En el caso de que tenga javascript para el onLoad
		if(isset($obj_panel->panelAsociado->obj_clase)){
			$obj_igepSmarty = $obj_panel->panelAsociado->obj_clase->obj_IgSmarty;	
			
			if(is_object($obj_igepSmarty))
				$jsLoad = $obj_igepSmarty->getScriptLoad(false);
			else 
				IgepDebug::setDebug(WARNING,'VIEWS: problemas al cargar la clase desde SESSION');
			
			if($jsLoad!='')
				$s->assign('smty_jsOnLoad',$jsLoad);
			IgepSession::guardaVariable($obj_panel->panelAsociado->str_claseManejadora,'obj_IgSmarty',$obj_igepSmarty);
		}	
	}
	return $obj_panel;
}

/**
 * Añade un panel dependiente
 * @param mixed ???
 * @param mixed ???
 * @return mixed ???
 */
function agregarPanelDependiente($obj_panel,$panelPadre){
	global $s;
					
  if (($this->v_paneles[$panelPadre]->obj_clase->panelDetalleActivo == $obj_panel->str_claseManejadora) 
       && isset($this->v_paneles[$panelPadre]->obj_clase->obj_ultimaConsulta
     )){
		//Comprobamos si el panel del que depende tiene alguna fila seleccionada.	
		if (!isset($this->v_paneles[$panelPadre]->obj_clase->int_filaActual)){				
			$this->v_paneles[$panelPadre]->obj_clase->int_filaActual = 0;
			IgepSession::borraPanel($obj_panel->str_claseManejadora);
			IgepSession::guardaVariable($panelPadre,"int_filaActual",0);
		}						
		if ($this->v_paneles[$panelPadre]->obj_clase->int_filaActual >= count($this->v_paneles[$panelPadre]->obj_clase->obj_ultimaConsulta)){			
			$this->v_paneles[$panelPadre]->obj_clase->int_filaActual = 0;
			IgepSession::borraVariable($obj_panel->str_claseManejadora,'obj_ultimaConsulta');
			IgepSession::guardaVariable($panelPadre,"int_filaActual",0);
		}		
		$s->assign("smty_filaSeleccionada",$this->v_paneles[$panelPadre]->obj_clase->int_filaActual);						
		//Comprobamos si existe en la Session el Panel padre... sino existe lo guardamos para que si se ejecuta RecargarDesdeHijo funcione
		if(IgepSession::existePanel($panelPadre)){
			IgepSession::guardaPanel($panelPadre,$this->v_paneles[$panelPadre]->obj_clase);
		}
		$obj_panel->comportamientoPanel(true);
		//En el caso de que tenga javascript para el onLoad
		if(isset($obj_panel->obj_clase)){
			$obj_igepSmarty = $obj_panel->obj_clase->obj_IgSmarty;
			
			if(is_object($obj_igepSmarty))
				$jsLoad = $obj_igepSmarty->getScriptLoad(false);
			else 
				IgepDebug::setDebug(WARNING,'VIEWS: problemas al cargar la clase desde SESSION');
			
			if($jsLoad!='')
				$s->assign('smty_jsOnLoad',$jsLoad);
			IgepSession::guardaVariable($obj_panel->str_claseManejadora,'obj_IgSmarty',$obj_igepSmarty);
		}
	}
	else {
		$s->assign($obj_panel->str_nombreDatosConsulta,"");
		$s->assign($obj_panel->str_nombreDatosEdicion,"");
	}
		
	$this->v_paneles[$obj_panel->str_claseManejadora] = & $obj_panel;
	return $obj_panel;			
}

}//Fin de clase IgepPantalla
?>