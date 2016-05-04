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
 * gvHidraCheckBox es una clase que se encarga de enmascarar la definici�n de un checkbox en gvHidra.
 * A diferencia con un checkbox b�sico de HTML, en gvHidra podemos asociar valores al estado del checkBox,
 * por lo que la clase facilita al programador un mecanismo m�s sencillo y comprensible para
 * establacer y obtener informaci�n.
 *
 * @version	$Id: gvHidraCheckBox.php,v 1.2 2010-11-17 16:36:07 afelixf Exp $
 * @author Toni: <felix_ant@gva.es>
 * @package gvHIDRA
 */
class gvHidraCheckBox
{
	private $_name;
	private $_valueCheck = true;
	private $_valueUncheck = false;
	private $_value = false;
	private $_checked;
	
	
	/**
	* Construye un nuevo checkbox
	* 
	* @acces public
	* @param string $name nombre del checkbox
	* @return none
	*/
	public function gvHidraCheckBox ($name) {

		$this->setName($name);
	}//Fin de constructor
	
		
	//---------------------------------------------
	//M�todos relativos al nombre
	//---------------------------------------------


	/**
	* getName acceso a la propiedad name de la clase
	* 
	* @acces public
	* @return string
	*/
	public function getName() {

		return $this->_name;		
	}


	/**
	* setNombre fija la propiedad name de la clase
	* 
	* @acces public
	* @param string $name nombre del checkbox
	* @return string
	*/
	public function setName($name) {

		$this->_name = $name;
	}
	
	
	//---------------------------------------------
	//FIN:M�todos relativos al nombre
	//---------------------------------------------	


	//---------------------------------------------
	//M�todos relativos al defaultValue
	//---------------------------------------------


	/**
	* getDefaultValue devuelve el valor por defecto del checkbox
	* 
	* @acces public
	* @return string
	*/	
	public function getDefaultValue() {

		if($this->_checked)
			return $this->getValueChecked();
		
		return $this->getValueUnChecked();
	}


	/**
	* setChecked para indicar que el checkbox est� marcado o no como valor por defecto.
	* 
	* <p> El valor por defecto se utiliza cuando el componente al visualizarse no tiene una fuente de datos. Es
	* decir, cuando estamos en una insercion o en un modo filtro. En la tpl se debe utilizar la propiedad adecuada
	* del componente CWCheckBox.</p>
	* 
	* @acces public
	* @param boolean $value booleano que indica si el check esta por defecto marcado o no
	* @return none
	*/
	public function setChecked($value) {
		
		$this->_checked = $value;
	}

	
	//---------------------------------------------
	//FIN:M�todos relativos al defaultValue
	//---------------------------------------------	


	//---------------------------------------------
	//M�todos relativos al ValueChecked
	//---------------------------------------------

	
	/**
	* getValueChecked devuelve el valor cuando el check esta marcado
	* 
	* @acces public
	* @return string
	*/
	public function getValueChecked() {

		return $this->_valueCheck;		
	}


	/**
	* setValueChecked fija el valor deseado cuando el check esta marcado
	* 
	* @acces public
	* @param string $value valor cuando esta marcado
	* @return none
	*/
	public function setValueChecked($value) {

		$this->_valueCheck = $value;
	}

	
	//---------------------------------------------
	//FIN:M�todos relativos al ValueChecked
	//---------------------------------------------	


	//---------------------------------------------
	//M�todos relativos al ValueUnchecked
	//---------------------------------------------


	/**
	* getValueUnchecked devuelve el valor cuando el check esta desmarcado
	* 
	* @acces public
	* @return string
	*/
	public function getValueUnchecked() {

		return $this->_valueUncheck;		
	}


	/**
	* setValueUnchecked fija el valor deseado cuando el check esta desmarcado
	* 
	* @acces public
	* @param string $value valor cuando esta desmarcado
	* @return none
	*/
	public function setValueUnchecked($value) {

		$this->_valueUncheck = $value;
	}
	

	
	//---------------------------------------------
	//FIN:M�todos relativos al ValueUnchecked
	//---------------------------------------------	


}//Fin gvHidraCheckBox
?>