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
 * gvHidraTypeBase 
 *
 *
 * @version $Id: gvHidraTypeBase.php,v 1.18 2010-11-19 17:22:25 afelixf Exp $
 * 
 * @author Toni: <felix_ant@gva.es>
 *
 * @package gvHIDRA
 */ 

class gvHidraTypeBase
{
    private $required=false;
    private $maxLength=null;
    private $enableInputMask; 
    private $enableServerValidation;
    private $label;
    
    /**
    * constructor
    */
    public function __construct($required=false,$maxLength=null){
    	$this->setRequired($required);
    	$this->setMaxLength($maxLength);
    	$this->enableInputMask(TRUE);
    	$this->enableServerValidation = TRUE;
    }//Fin de constructor

	/**
	* Indica que un campo es obligatorio
	* @access	public
	* @param	bool	true campo obligatorio, false campo no obligatorio
	* @return	none
	*/
    final public function setRequired($value){
    	if($value)
    		$this->required = true;
    	else
    		$this->required = false;
    	return 0;
    }
    
    final public function getRequired(){
    	return $this->required;
    }

	/**
	* Indica la longitud máxima del campo
	* @access	public
	* @param	integer	longitud máxima del campo
	* @return	none
	*/
    final public function setMaxLength($value){
    	if(!empty($value))
    		$this->maxLength = $value;
    }

    final public function getMaxLength(){
		return $this->maxLength;
    }

	/**
	* Indica el nombre del campo para mostrarlo por pantalla. Cuando el sistema detecta un error de tipo, muestra un mensaje y con este label identificará al campo
	* @access	public
	* @param	string	nombre del campo
	* @return	none
	*/
    final public function setLabel($value) {
    	if(!empty($value))
    		$this->label = $value;
    }

    final public function getLabel() {
		return $this->label;
    }

	/**
	* Activa o desactiva las máscaras de entrada de gvHidra (javascript)
	* @access	public
	* @param	bool	true activa máscaras de entrada, false desactiva máscaras de entrada
	* @return	none
	*/    
    final public function enableInputMask($value){
    	$this->enableInputMask = $value;
    }

    final public function getStatusInputMask(){
    	return $this->enableInputMask;
    }

	/**
	* Activa o desactiva las validaciones del servidor. Para validaciones en SGBD Oracle y MySQL que provisionalmente no funcionarán.
	* @access	public
	* @param	bool	true activa validaciones en el servidor, false desactiva validaciones en el servidor
	* @return	none
	*/    
	final public function enableServerValidation($value){

		// DEPRECATED: se fija en v. 3.2
		IgepDebug::setDebug(WARNING, 'DEPRECATED gvHidraTypeBase::enableServerValidation. Las validaciones en el servidor serán obligatorias en las próximas versiones.'.
                                 '<br>En version 3.2 se borrará.');		
		$this->enableServerValidation = $value;	
	}
	
	final public function getStatusServerValidation(){

		return $this->enableServerValidation;
	}

	/**
	 * funcion validate
	 */            
    public function validate($value){
    	if ($this->required && (!isset($value) or $value==='')){
    		throw new Exception('Es un campo obligatorio.');
    	}
    	if(!empty($this->maxLength) and strlen($value)>$this->maxLength)
    		throw new Exception('Excede su longitud máxima que es '.$this->maxLength);
    	return 0;
    }
}//Fin clase gvHidraTypeBase
?>