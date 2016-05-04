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
 * gvHidraTime contiene información relativa a los campos de tipo fechahora 
 *
 * @version $Id: gvHidraTime.php,
 * 
 * @package gvHIDRA
 */ 
class gvHidraTime extends gvHidraTypeBase
{
    private $time;
    private $type = 'time';
    private $time24H = false;
    
	/**
	* Crear un tipo gvHidraTime (timestamp).
	* @access	public
	* @param	required	boolean		true campo obligatorio, false campo no obligatorio	 
	* @return	none
	*/
    public function __construct($required=false){
    	parent::__construct($required,19);
    	$this->setShowTimer(false);
    }//Fin de constructor

	/**
	* Indica que si se quiere mostrar o no el objeto calendario
	* @access	public
	* @param	boolean	true activa calendario, false desactiva el calendario	 
	* @return	none
	*/    

    final public function setShowTimer($value) //Por defecto no se muestra
    {
    	$this->time = $value;
    }
    
    final public function getShowTimer()
    {
    	return $this->time;
    }
    
    final public function getType()
    {
    	return $this->type;
    }

    final public function setTime24H($value)
    {
    	$this->time24H = $value;
    }

    final public function getTime24H($value)
    {
    	return $this->time24H;
    }
}//Fin clase gvHidraTime
?>