<?php
/* gvHIDRA. Herramienta Integral de Desarrollo R�pido de Aplicaciones de la Generalitat Valenciana
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
*  Av. Blasco Ib��ez, 50
*  46010 VALENCIA
*  SPAIN
*  +34 96386 24 83
*  gvhidra@gva.es
*  www.gvpontis.gva.es
*
*/ 


/**
 * gvHidraString contiene informaci�n relativa a los campos de tipo cadena 
 *
 *
 * @version $Id: gvHidraString.php,v 1.13 2011-04-27 17:55:36 afelixf Exp $
 * 
 * @author Toni: <felix_ant@gva.es>
 *
 * @package gvHIDRA
 */ 
class gvHidraString extends gvHidraTypeBase
{
    
    private $regExp;
    private $inputMask;
    private $password;


	/**
	* Crear un tipo gvHidraString (String).
	* @access	public
	* @param	required	boolean		true campo obligatorio, false campo no obligatorio	 
	* @param	maxlength	integer		longitud m�xima del campo
	* @return	none
	*/    
    public function __construct($required=false,$maxLength=null){
    	parent::__construct($required,$maxLength);
	   	//Inicializamos atributos de la clase
    	$this->setRegExp(null);
    	$this->setInputMask(null);
    }//Fin de constructor

	/**
	* Fija una expresi�n regular de validaci�n de la cadena.
	* @access	public
	* @param	string	expresi�n regular
	* @return	none
	*/	
	final public function setRegExp($value){
		$this->regExp = $value;
	}
	
	final public function getRegExp(){
		return $this->regExp;
	}

	/**
	* Fija una m�scara de entrada de datos seg�n la codificaci�n de gvHidra.
	* @access	public
	* @param	string	m�scara de entrada
	* @return	none
	*/	
	final public function setInputMask($value){
		$this->inputMask = $value;
	}
	
	final public function getInputMask(){
		return $this->inputMask; 
	}

	/**
	* Fija el tipo como password. Con ello no podra verse lo que se teclea
	* @access	public
	* @param	string	m�scara de entrada
	* @return	none
	*/	
	final public function setPasswordType($enable) {
		
		$this->password = $enable;
	}
	
	final public function getPasswordType() {

		return $this->password;
	}
	
	public function validate($value){
		if(parent::validate($value)==0){
			$regExpr = $this->getRegExp();
			if(!empty($regExpr) and !empty($value)){
				if (ereg($this->getRegExp(),$value))
					return 0;
				else
					throw new Exception('No es del formato correcto.');
			}
			return 0;
		}
	}
		
}//Fin clase gvHidraString
?>