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
 * gvHidraFloat contiene informaci�n relativa a los campos de tipo num�rico 
 *
 *
 * @version $Id: gvHidraFloat.php,v 1.17 2011-05-06 13:29:33 afelixf Exp $
 * 
 * @author Toni: <felix_ant@gva.es>
 *
 * @package gvHIDRA
 */ 
class gvHidraFloat extends gvHidraTypeBase
{
    private $floatPart=2;
    
	/**
	* Crear un tipo gvHidraFloat (N�mero real).
	* @access	public
	* @param	required	boolean		true campo obligatorio, false campo no obligatorio	 
	* @param	maxlength	integer		longitud m�xima del campo
	* @return	none
	*/
    public function __construct($required=false,$maxLength=null) {
		parent::__construct($required,$maxLength);
    	//Inicializamos atributos de la clase
		$this->setFloatLength(2);
    }//Fin de constructor

	/**
	* Fija la longitud de la parte real. Los n�mero se expresar�n de este modo al igual que en el SGBD (maxLength,floatLength)
	* @access	public
	* @param	integer	longitud de la parte real
	* @return	none
	*/	    
    final public function setFloatLength($value=2) {
    	
    	if(!empty($value))
    		$this->floatPart = $value;
    }

    final public function getFloatLength() {
    	return $this->floatPart;
    }
    
    public function validate($value) {
    	//comprobamos obligatorio
    	if($this->getRequired() && !isset($value)){
    		throw new Exception('Es un campo obligatorio.');
    	}
    	if(empty($value))
    		return 0;
		//Provisionalmente permitimos desactivar las validaciones del servidor. Esto permite seguir funcionando mantenimientos sobre MySQL
		//(aunque realmente en los numeros de mysql no hace falta inhabilitarlo)
		if(!$this->getStatusServerValidation())
			return 0;	    		
    	//Comprobamos si excede el tama�o la parte real
    	$car_n = ConfigFramework::getNumericSeparatorsFW();
		$des = explode($car_n['DECIMAL'],$value);
		$maxLength = $this->getMaxLength();
		$longEntera = $maxLength - $this->getFloatLength();
		if(strlen($des[0])>$longEntera)
			throw new Exception('El n�mero excede la longitud para la parte entera.');					    	
		if(strlen($des[1])>$this->getFloatLength())
			throw new Exception('El n�mero excede la longitud para la parte decimal.');					    	
    	return 0;
    }
    
}//Fin clase gvHidraFloat
?>