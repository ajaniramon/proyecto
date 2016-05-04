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
 * gvHidraDatetime contiene información relativa a los campos de tipo fechahora 
 *
 *
 * @version $Id: gvHidraDatetime.php,v 1.16 2009-06-16 13:08:55 gaspar Exp $
 * 
 * @author Toni: <felix_ant@gva.es>
 *
 * @package gvHIDRA
 */ 
class gvHidraDatetime extends gvHidraTypeBase
{
    private $calendar;
    private $dayOfWeek;
    private $dayOfYear;
    
	/**
	* Crear un tipo gvHidraDateTime (timestamp).
	* @access	public
	* @param	required	boolean		true campo obligatorio, false campo no obligatorio	 
	* @return	none
	*/
    public function __construct($required=false){
    	parent::__construct($required,19);
    	//Inicializamos atributos de la clase
    	$this->setCalendar(false);
    	$this->setDayOfWeek('none');
    	$this->setDayOfYear(false);    	
    }//Fin de constructor

	/**
	* Indica que si se quiere mostrar o no el objeto calendario
	* @access	public
	* @param	boolean	true activa calendario, false desactiva el calendario	 
	* @return	none
	*/    
    final public function setCalendar($value){
    	$this->calendar = $value;
    }

    final public function getCalendar(){
    	return $this->calendar;
    }

	/**
	* Indica si se quiere mostrar una etiqueta al lado del campo con el día de la semana.
	* @access	public
	* @param	string	'short' formato corto, 'long' formato extendido, 'none' no se muestra la etiqueta
	* @return	none
	*/
	final public function setDayOfWeek($value){
		if($value!='short' and $value!='long' and $value!='none')
			throw new Exception("Error al definir el type gvHidraDatetime. La propiedad dayOfWeek sólo puede tener valores 'none', 'short' o 'long'.");
		$this->dayOfWeek = $value;
	}
	
	/**
	* Indica si se quiere mostrar una etiqueta con el día del año
	* @access	public
	* @param	bool	true muestra etiqueta, false no muestra la etiqueta
	* @return	none
	*/		
	final public function setDayOfYear($value){
		$this->dayOfYear = $value;
	}

	final public function getDayOfWeek(){
		return $this->dayOfWeek;
	}
	
	final public function getDayOfYear(){
		return $this->dayOfYear;
	}

	/**
	 * Recibe un objeto gvHidraTimestamp, que es una fecha-hora válida
	 * TODO: Si recibe un null puede ser porque no se hayan introducido valores o porque no sean válidos,
	 * por lo que siempre que salga entrada incorrecta tambien dirá campo obligatorio
	 */
    public function validate($value){
		if (empty($value))
			$strValue = '';
		elseif (is_object($value) and method_exists($value, 'formatFW'))
			$strValue = $value->formatFW();
		else {
			IgepDebug::setDebug(ERROR,'Validación en gvHidraDatetime recibe valor desconocido: '.var_export($value,true));
			throw new Exception('No se recibe una fecha-hora válida.');
		}
		parent::validate($strValue);	    		
    	return 0;
    }
	
}//Fin clase gvHidraDatetime
?>