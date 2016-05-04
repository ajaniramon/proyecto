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
 * Fichero IgepPanelArbol.php
 * @package	gvHIDRA
 */

/**
 * Hace uso de la clase IgepTreeMenu para crear el arbol
 */
require_once 'igep/include/IgepTreeMenu.php';

/**
 * Clase IgepPanelArbol
 * @package gvHIDRA
 */
class IgepPanelArbol{

var $v_pestanyas;
var $str_claseManejadora;
var $obj_clase;
var $str_datosPanel; 
var $panelAsociado;

function IgepPanelArbol($claseManejadora, $datosPanel=''){	
	global $s;
			
	//Para el control de los mensajes de Alerta
	$mensaje = IgepSession::dameVariable($claseManejadora,'obj_mensaje');
	if(isset($mensaje)) {
		$tipo =  $mensaje->getTipo();	
		$s->assign("smty_tipoAviso", $tipo);			
		$codError =  $mensaje->getCodigo();
		$s->assign("smty_codError", $codError);
		$descBreve = $mensaje->getDescripcionCorta();	
		$s->assign("smty_descBreve", $descBreve);
		$textoAviso = $mensaje->getDescripcionLarga();								
		$s->assign("smty_textoAviso", $textoAviso);
		IgepSession::borraVariable($claseManejadora,'obj_mensaje');
	}	
	$this->v_pestanyas = array();
	$this->str_claseManejadora = $claseManejadora;
	$this->str_datosPanel = $datosPanel;
}//Fin constructor

function activarModo($tipoPestanya,$nombreTpl){
  	if(($tipoPestanya=='fil'))
  		$this->v_pestanyas['fil']=$nombreTpl;
  	elseif(($tipoPestanya=='lis')or($tipoPestanya=='edi'))
  		$this->v_pestanyas['edi']=$nombreTpl;
  	else
  		die("Error: Se ha incorporado un pestaña que no es de ninguno de los tipos estandar, concretamente $tipoPestanya.\nLos tipos estandar son 'fil', 'lis' y 'edi'.");  	  	
}

function comportamientoPanel($dependiente=false){
	//Vamos a ver cuantas pestañas tiene, y dependiendo de ello llamamos a una función o a otra
	switch(count($this->v_pestanyas)){
	 	case 2:
	 		$this->obj_clase = & $this->comportamientoDosPestanyas();	 			 		
	 		break;
	 	case 1:
	 		$this->obj_clase = & $this->comportamientoUnaPestanya();
	 		break;
	 	default:	 
	 		die('Error: el número de Pestañas indicado en views no es correcto');
	}
	return $this->obj_clase;
}   

function comportamientoUnaPestanya() {
	global $s;
		
	$obj_clase = IgepSession::damePanel($this->str_claseManejadora);
	
	$arbol = IgepSession::dameVariable($this->str_claseManejadora,'obj_arbol');
	$cadXML = $arbol->getXML();
	
	$arbolXML  = new HTML_IgepArbol();
	$arbolXML->arbolXML($cadXML);
	$html_arbol = $arbolXML->generaMenu();
	
	//Asignamos la cadena al arbol
	$s->assign("smty_objArbol", $html_arbol);
	
	//Dependiendo del arbol marcamos una clase como clase manejadora
	$s->assign("smty_panelVisible",$arbol->tipoNodoSeleccionado);
	
	if(isset($arbol->v_defArbol[$arbol->tipoNodoSeleccionado]['claseManejadora'])){		
		$this->panelAsociado = new IgepPanel($arbol->v_defArbol[$arbol->tipoNodoSeleccionado]["claseManejadora"],$this->str_datosPanel);
		$this->panelAsociado->activarModo('edi','estado_edi');		
		$s->assign("smty_tituloPanel",$arbol->str_tituloPanel);
	}
		
	return $obj_clase;
} //Fin comportamientoUnaPestaña



function comportamientoDosPestanyas() {

	global $s;

	if ($_REQUEST['panel'] == 'buscar') {
		$s->assign($this->v_pestanyas['fil'],'on'); 
		$s->assign($this->v_pestanyas['edi'],'inactivo'); 	
	}
	else {
		$obj_clase =$this->comportamientoUnaPestanya();
		$s->assign($this->v_pestanyas['fil'],'off');
		$s->assign($this->v_pestanyas['edi'],'on');
	}
	return $obj_clase;
} //Fin comportamientoDosPestañas


	
}//Fin de clase IgepPanelArbol
?>