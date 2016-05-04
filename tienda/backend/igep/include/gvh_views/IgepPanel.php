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
 *
 * @package	gvHIDRA
 */

class IgepPanel{

	var $v_pestanyas;
	var $str_claseManejadora;
	var $str_estadoFil; 
	var $str_estadoEdi; 
	var $str_estadoLis;
	var $obj_clase; 

	public function IgepPanel($claseManejadora,$nombreDatosConsulta,$nombreDatosEdicion="") {	

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
		$this->str_nombreDatosConsulta = $nombreDatosConsulta; 
		$this->str_nombreDatosEdicion = $nombreDatosEdicion;	
	}//Fin constructor

	public function activarModo($tipoPestanya,$nombreTpl) {

	  	if(($tipoPestanya=="fil")or($tipoPestanya=="lis")or($tipoPestanya=="edi"))
	  		$this->v_pestanyas[$tipoPestanya]=$nombreTpl;
	  	else
	  		die("Error: Se ha incorporado un pestaña que no es de ninguno de los tipos estandar, concretamente $tipoPestanya.\nLos tipos estandar son 'fil', 'lis' y 'edi'.");  	  	
	}

	public function comportamientoPanel($dependiente=false) {

		//Vamos a ver cuantas pestañas tiene, y dependiendo de ello llamamos a una función o a otra
		switch(count($this->v_pestanyas)){
			case  3:
		 		//Mantenimiento de panel Ficha
				$this->obj_clase = & $this->comportamientoTresPestanyas($dependiente);
				break;
			case 2:
				if($dependiente)
					$this->obj_clase = & $this->comportamientoDosPestanyasDetalle();
	      		else
					$this->obj_clase = & $this->comportamientoDosPestanyas();	 			 		
		 		break;
			case 1:
		 		$this->obj_clase = & $this->comportamientoUnaPestanya($dependiente);
				break;
			default:	 
				die("Error: el número de Pestañas indicado en views no es correcto");
		}
		return $this->obj_clase;
	}   

	public function comportamientoUnaPestanya($dependiente) {

		global $s;
	
		$obj_clase = IgepSession::damePanel($this->str_claseManejadora);
		//Para el contenido de la tabla
		if(is_object($obj_clase)) {
			$s->assign($this->str_nombreDatosConsulta, $obj_clase->obj_ultimaConsulta);
			//Realizamos los assign comunes a un panel IGEP.
			$this->asignacionesComunes($obj_clase);
		}
			
		return $obj_clase;
	} //Fin comportamientoUnaPestaña



	public function comportamientoDosPestanyas() {
	
		global $s;
	    
		if (IgepSession::existePanel($this->str_claseManejadora)) {     
			//¿Hay datos para visualizar?
			$obj_clase = IgepSession::damePanel($this->str_claseManejadora);
			if(count($obj_clase->obj_ultimaConsulta)>0) {
				//cuando realizas el buscar y la se han obtenido resultados
				$otroPanel = "on";
				$this->str_estadoFil = "off";   
			}
			//cuando la consulta no devuelve valores se deja elegir según el mappings y el parámetro panel.
			else {        
				if (isset($_REQUEST["panel"]) and $_REQUEST["panel"] == "listar") {
					$otroPanel = "on";
					$this->str_estadoFil = "off";             
				}
				elseif (isset($_REQUEST["panel"]) and $_REQUEST["panel"] == "editar") {
					$otroPanel = "on";
					$this->str_estadoFil = "off";             
				}
				else {        
					$otroPanel = "inactivo";
					$this->str_estadoFil = "on";
				}       
			}         
		}
		else {
			//La primerta vez que se entra en la ventana    
		    $otroPanel = "inactivo"; 
		    $this->str_estadoFil = "on";  
		}
		//La visualización de las pestañas
		if(isset($this->v_pestanyas["lis"]))
			$s->assign($this->v_pestanyas["lis"],$otroPanel); 
		else
			$s->assign($this->v_pestanyas["edi"],$otroPanel); 
		$s->assign($this->v_pestanyas["fil"],$this->str_estadoFil);  

		//Para el contenido de la tabla
		if (is_object($obj_clase)) {
			$s->assign($this->str_nombreDatosConsulta, $obj_clase->obj_ultimaConsulta);  
			//Realizamos los assign comunes a un panel IGEP.
			$this->asignacionesComunes($obj_clase);
		}
	    
		return $obj_clase;
	} //Fin comportamientoDosPestañas


	public function comportamientoDosPestanyasDetalle() {	

		global $s;

		if (IgepSession::existePanel($this->str_claseManejadora)) {     
			$obj_clase = IgepSession::damePanel($this->str_claseManejadora);
			if($_REQUEST["panel"]=="listar")
				$obj_clase->obj_ultimaEdicion = null;
			if(($_REQUEST["panel"]=="editar") or (count($obj_clase->obj_ultimaEdicion)>0)) {
				//cuando realizas el edicion y la se han obtenido resultados
				$this->str_estadoEdi = "on";
				$this->str_estadoLis = "off";   
			}
			//cuando la consulta no devuelve valores se deja elegir según el mappings y el parámetro panel.
			else {        
				$this->str_estadoEdi = "inactivo";
				$this->str_estadoLis = "on";
			}         
		}
		else {
			//La primerta vez que se entra en la ventana    
			$this->str_estadoEdi = "inactivo"; 
			$this->str_estadoLis = "on";  
		}  
		$s->assign($this->v_pestanyas["lis"],$this->str_estadoLis);   
		$s->assign($this->v_pestanyas["edi"],$this->str_estadoEdi);
		$s->assign($this->str_nombreDatosEdicion, $obj_clase->obj_ultimaEdicion);         

		//Para el contenido de la tabla
		if(is_object($obj_clase)) { 
			$s->assign($this->str_nombreDatosConsulta, $obj_clase->obj_ultimaConsulta);  
			//Realizamos los assign comunes a un panel IGEP.
			$this->asignacionesComunes($obj_clase);
		}
	    
		return $obj_clase;
	} //Fin comportamientoDosPestañasDetalle



	public function comportamientoTresPestanyas($dependiente) {
		
		global $s;
		
		if (IgepSession::existePanel($this->str_claseManejadora)) {
			$obj_clase = IgepSession::damePanel($this->str_claseManejadora);			
			//Cuando estas editando
			if($_REQUEST["panel"]=="editar") {
				$this->str_estadoEdi = "on";
				$this->str_estadoLis = "off";
				$this->str_estadoFil = "off";					
			}
			else {
				$this->str_estadoEdi = "inactivo";
				if(count($obj_clase->obj_ultimaConsulta)>0) {
					//cuando realizas el buscar y has buscado bien
					$this->str_estadoLis = "on";
					$this->str_estadoFil = "off";					
				}
				else {				
					if ($_REQUEST["panel"] == "listar") {		
						$this->str_estadoLis = "on";
						$this->str_estadoFil = "off";
					}
					else {
						$this->str_estadoLis = "inactivo";				
						$this->str_estadoFil = "on";
					}		
				}		
			}
		}
		else {
			/*La primera vez q entra*/ 		
			$this->str_estadoLis = "inactivo"; 
			$this->str_estadoFil = "on";
			$this->str_estadoEdi = "inactivo";
		}
		
		//La visualización de las pestañas
		$s->assign($this->v_pestanyas["fil"],$this->str_estadoFil); 
		$s->assign($this->v_pestanyas["edi"],$this->str_estadoEdi); 
		$s->assign($this->v_pestanyas["lis"],$this->str_estadoLis);

		//Para el contenido de la tabla y de la ficha
		if(is_object($obj_clase)) { 
			$s->assign($this->str_nombreDatosConsulta, $obj_clase->obj_ultimaConsulta);
			$s->assign($this->str_nombreDatosEdicion, $obj_clase->obj_ultimaEdicion);
			//Realizamos los assign comunes a un panel IGEP.
			$this->asignacionesComunes($obj_clase);
		}

		return $obj_clase; 
	}//Fin de comportamientoTresPestañas
	

	public function asignacionesComunes(& $obj_clase) {

		global $s;
		//En el caso de las fichas, cuando pasamos por Phrame tenemos que cargar los datos preinsertados y la accion, por eso vamos a realizar dos assign	
		if(isset($obj_clase->v_preInsercionDatos)){
			$s->assign('defaultData_'.$this->str_claseManejadora,$obj_clase->v_preInsercionDatos);
		}
		if(isset($obj_clase->v_descCamposPanel)){
			$s->assign('dataType_'.$this->str_claseManejadora,$obj_clase->v_descCamposPanel);
		}
		//Para los datos que vienen de negocio para la presentacion
		if(isset($obj_clase->v_datosPresentacion['accionFicha']))
			$s->assign('smty_operacionFicha'.$this->str_claseManejadora,$obj_clase->v_datosPresentacion['accionFicha']);

		if(is_object($obj_clase)) {
			$activeTab = $obj_clase->getActiveTab();
			$s->assign('smty_activeTab'.$this->str_claseManejadora,$activeTab);
		}
	}

}//Fin de clase IgepPanel
?>