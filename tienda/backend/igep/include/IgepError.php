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
 * IgepError es una clase que contiene el manejador de errores de la capa de Negocio y
 * Persistencia de una aplicación.
 * 
 * Consta de las siguientes propiedades:
 * <ul>
 * <li><b>int_error</b> - Se trata de un flag de error.
 * 		<ul>
 * 			<li>0. No hay errores.</li>
 * 			<li>1. Hay un error.</li>
 * 		</ul>
 * </li>
 * <li><b>str_descError</b> - Contiene la descripción del error a mostrar</li>
 * </ul>
 * 
 * @version	$Id: IgepError.php,v 1.19 2011-03-14 11:34:26 afelixf Exp $
 * @author David: <pascual_dav@gva.es> 
 * @author Keka: <bermejo_mjo@gva.es>
 * @author Vero: <navarro_ver@gva.es>
 * @author Raquel: <borjabad_raq@gva.es> 
 * @author Toni: <felix_ant@gva.es>
 * @package	gvHIDRA
 */ 

class IgepError{
	
	 /**
     * flag de error
     *
     * @var integer int_flag
     */	
	var $int_flag;
	
	 /**
     * código de error
     *
     * @var string str_codigoError
     */	
	var $str_codigoError;
	
	 /**
     * Descripción del error
     *
     * @var string str_fichero
     */		
	var $str_fichero;	

	 /**
     * Descripción del error
     *
     * @var string str_funcion
     */		
	var $str_funcion;	
	
	/**
     * DB error
     *
     * @var objeto obj_dbError
     */		
	var $obj_dbError;	


	/**
	 * Constructor
	 *
	 * @access	public
	 */
	function IgepError(){	
		$this->limpiarError();
	}

	/**
	 * Para indicar la existencia de un error. Activa el flag y
	 * almacena el valor del texto del error. En 
	 *
	 * @access	public
	 * @param	string	$descError
	 */
	function setError($codigoError,$fichero,$funcion,$dbError="",$codigoSQL="") {					
		$this->int_flag = 1;
		$this->str_codigoError = $codigoError;		
		$this->str_fichero = $fichero;	
		$this->str_funcion = $funcion;				
		$this->obj_dbError=$dbError;
		//Marcamos el error en el debug
		$descripcion = $this->getDescErrorDB();
		if($codigoSQL!='')    
			IgepDebug::setDebug(ERROR,'ERROR al ejecutar : '.$codigoSQL);
		if(isset($descripcion[0]))
			IgepDebug::setDebug(ERROR,$descripcion[0]);
	}

	/**
	 * Desactiva el flag de error.
	 *
	 * @access	public	
	 */		
	function limpiarError() {		
		$this->int_flag = 0;
		$this->str_codigoError = "";
		unset($this->str_funcion);		
		unset($this->str_fichero);
		unset($this->obj_dbError);		
	}

	/**
	 * Devuelve el codigo de error del error almacenado.
	 *
	 * @access	public	
	 * @return	integer
	 */		
	function getError() {		
		return $this->str_codigoError;		
	}
	
	/**
	 * Devuelve el string de descripción del error de la base de datos.
	 *
	 * @access	public	
	 * @return	array
	 */		
	public function getDescErrorDB()  {		
		$descripcion = array();
    
		if(isset($this->obj_dbError->userinfo)){    
			
			//Mensaje al usuario
			$descripcion[0]=$this->obj_dbError->message;
			
			//Mensaje al debugger
			$posInicial = strpos($this->obj_dbError->userinfo,"[");									
			$mensaje = substr($this->obj_dbError->userinfo,$posInicial +12);															
			if($mensaje!="")
				$debug = $mensaje;
			else
				$debug = $this->obj_dbError->message; 
			//Quitamos las comillas, los corchetes y los \n.
			$debug = str_replace("'","",$debug);		 		
			$debug = str_replace("\n","",$debug);		
			$debug = str_replace("]","",$debug);
			$debug = trim($debug);
			IgepDebug::setDebug(ERROR,$debug);
		}
		return $descripcion;		
	}
	
	
	/**
	 * Devuelve el flag de error.
	 *
	 * @access	public	
	 * @return	integer
	 */			
	function hayError() {
		return $this->int_flag; 	
	}
	
	function setMsjError(& $mensaje) {
		$mensaje->setMensaje($this->getError(),$this->getDescErrorDB());		
		$this->limpiarError();		
	}	
}//Fin de IgepError
?>