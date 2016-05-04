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
 * Clase que extiende DateTime de PHP, y que usaremos para representar las fechas en FW
 * Podria haberse llamado gvHidraDateTime, pero como ya tenemos una clase gvHidraDatetime,
 * hemos preferido llamarla con el sufijo timestamp, que es como se conoce habitualmente en
 * muchos SGBDs.
 * 
 * En la clase definimos varios métodos para facilitar el trabajo con gvHidra, tomando como base
 * la clase definida en 'PHP Object Oriented Solutions', de David Powers (Friendsoft 2008)
 * 
 * @package gvHIDRA
 */
class gvHidraTimestamp extends DateTime {

	protected $_year;
	protected $_month;
	protected $_day;
    private $_date_time;	// solo se usa para serializacion

	/**
	 * METODOS SOBRECARGADOS
	 */

	/**
	 * Sobreescribe el constructor por defecto
	 */
	function __construct($ts='now', $tz=null) {
		if (is_null($tz))
			parent::__construct($ts);
		else
			parent::__construct($ts,$tz);

		$this->init();
	}

	/**
	 * Usado junto con __wakeup para conservar estado de clase base DateTime
	 * 
	 * TODO: Segun explica en http://php.net/manual/en/datetime.wakeup.php (comentario
	 * del 19-Jun-2009 03:14 parece que con la 5.3 se arregla.
	 */
	public function __sleep() {
		$this->_date_time = $this->format('c');
		return array('_date_time','_year','_month','_day');
	}

	/**
	 * Usado junto con __sleep para conservar estado de clase base DateTime
	 */
	public function __wakeup() {
		$this->__construct($this->_date_time);
	}

	/**
	 * Sobrecarga del método setTime, para que no permita horas, minutos y segundos fuera del rango
	 * En caso de necesitar, se puede usar el metodo modify (ej. +300 seconds, ...)
	 */
	public function setTime($hours, $minutes, $seconds = 0)	{
		if (!is_numeric($hours) || !is_numeric($minutes) || !is_numeric($seconds)) {
			throw new Exception('setTime() espera dos o tres números separados por comas en el orden: horas, minutos, segundos');
		}
		$outOfRange = false;
		if ($hours < 0 || $hours > 23) {
			$outOfRange = true;
		}
		if ($minutes < 0 || $minutes > 59) {
			$outOfRange = true;
		}
		if ($seconds < 0 || $seconds > 59) {
			$outOfRange = true;
		}
		if ($outOfRange) {
			throw new Exception('Hora incorrecta');
		}
		parent::setTime($hours, $minutes, $seconds);
	}

	/**
	 * Sobrecarga del método setDate, para que no permita meses y dias fuera de rango
	 * En caso de necesitar, se puede usar el método modify (ej. +1000 days, ...)
	 */
	public function setDate($year, $month, $day) {
		if (!is_numeric($year) || !is_numeric($month) || !is_numeric($day)) {
			throw new Exception('setDate() espera tres números separados por comas en el orden: año, mes, dia');
		}
		if (!checkdate($month, $day, $year)) {
			throw new Exception('Fecha incorrecta');
		}
		parent::setDate($year, $month, $day);
		$this->_year = (int) $year;
		$this->_month = (int) $month;
		$this->_day = (int) $day;
	}

	public function modify($str) {
		parent::modify($str);
		$this->init();
	}

	/**
	 * actualiza las propiedades internas cada vez que cambia la fecha
	 */
	private function init() {
		$this->_year = (int) $this->format('Y');
		$this->_month = (int) $this->format('n');
		$this->_day = (int) $this->format('j');		
	} 

	/**
	 * METODOS PROPIOS DE gvHIDRA
	 */

	/**
	 * Formatea la fecha en el formato User
	 */
	public function formatUser() {
		$fmt = ConfigFramework::getDateMaskUser();
		//$fmt = $this->outputFormat($fmt);
		$fhora = $this->format('His')!='000000'? ' '.ConfigFramework::getTimeMask(): '';
		return $this->format($fmt.$fhora);
	}

	/**
	 * Formatea la fecha en el formato FW
	 */
	public function formatFW() {
		$fmt = ConfigFramework::getDateMaskFW();
		//$fmt = $this->outputFormat($fmt);
		$fhora = $this->format('His')!='000000'? ' '.ConfigFramework::getTimeMask(): '';
		return $this->format($fmt.$fhora);
	}

	/**
	 * Formatea la fecha en el formato usado por SOAP
	 * http://www.w3.org/TR/xmlschema-2/#dateTime
	 */
	public function formatSOAP() {
		return $this->format(DateTime::W3C);
	}
	
	/**
	 * Devuelve el valor de la fecha en timestamp
	 * REVIEW: Hay que revisar este metodo. Cuando utilicemos la version 5.3 de PHP podemos eliminarlo
	 */
	public function getTimestamp() {
		// no puedo usar parent en method_exists, luego obtengo instancia del padre
		if (method_exists(new DateTime(), 'getTimestamp'))
			return parent::getTimestamp();
		$dateFormated = $this->formatFW();
		return strtotime($dateFormated); 
	}

	/**
	 * Devuelve si la fecha actual está entre el rango de fechas introducido
	 * REVIEW: Hay que revisar este metodo. La forma de trabajar con el no se si es comoda.
	 * Ahora que se puede comparar directamente, igual ya tiene poco sentido
	 */
	public function between($date1, $date2) {
		if ($this >= $date1 and $this <= $date2)
			return true;
		return false;
	}

	public function betweenDays($date1, $numDays) {
		if (!is_numeric($numDays))
			throw new Exception('betweenDays() espera un entero');

		$date2 = clone $date1;
		if ($numDays > 0) {
			$date2->addDays($numDays);
			return $this->between($date1,$date2);
		} else {
			$date2->subDays($numDays);
			return $this->between($date2,$date1);
		}
	}

	/**
	 * Compara dos objetos fecha
	 * Devuelve 1 si la primera es menor, -1 si es mayor, y 0 si son iguales
	 * Como la comparación se puede hacer directamente, este metodo ya no tiene mucho sentido
	 */
	public static function cmp($date1, $date2) {
		if ($date1 > $date2)
			return -1;
		if ($date1 < $date2)
			return 1;
		return 0;
	}
	
	/**
	 * Modifica un formato para que éste siempre tenga ceros en meses y dias menores de 10
	 * De momento no se usa, para no alterar el formato definido en cada nivel
	 */
	public static function outputFormat($fmt) {
		$fmt = str_replace('j', 'd', $fmt);
		return str_replace('n', 'm', $fmt);
	}

	/**
	 * METODOS PARA OPERAR
	 */

	public function addDays($numDays) {
		if (!is_numeric($numDays) || $numDays < 1) {
			throw new Exception('addDays() espera un entero positivo');
		}
		$this->modify('+' . intval($numDays) . ' days');
	}

	public function subDays($numDays) {
		if (!is_numeric($numDays)) {
			throw new Exception('subDays() espera un entero');
		}
		$this->modify('-' . abs(intval($numDays)) . ' days');
	}

	public function addWeeks($numWeeks) {
		if (!is_numeric($numWeeks) || $numWeeks < 1) {
			throw new Exception('addWeeks() espera un entero positivo');
		}
		$this->modify('+' . intval($numWeeks) . ' weeks');
	}

	public function subWeeks($numWeeks) {
		if (!is_numeric($numWeeks)) {
			throw new Exception('subWeeks() espera un entero');
		}
		$this->modify('-' . abs(intval($numWeeks)) . ' weeks');
	}

	/**
	 * Este método cambia el funcionamiento por defecto usado en modify:
	 * - si estamos en dia 31 y le sumamos un mes, si no existe el dia obtenemos el 1 del mes siguiente
	 * Con este metodo, en la situación anterior obtenemos el último dia del mes siguiente.
	 */
	public function addMonths($numMonths) {
		if (!is_numeric($numMonths) || $numMonths < 1) {
			throw new Exception('addMonths() espera un entero positivo');
		}
		$numMonths = (int) $numMonths;
		// Add the months to the current month number.
		$newValue = $this->_month + $numMonths;
		// If the new value is less than or equal to 12, the year
		// doesn't change, so just assign the new value to the month.
		if ($newValue <= 12) {
			$this->_month = $newValue;
		} else {
			// A new value greater than 12 means calculating both
			// the month and the year. Calculating the year is
			// different for December, so do modulo division
			// by 12 on the new value. If the remainder is not 0,
			// the new month is not December.
			$notDecember = $newValue % 12;
			if ($notDecember) {
				// The remainder of the modulo division is the new month.
				$this->_month = $notDecember;
				// Divide the new value by 12 and round down to get the
				// number of years to add.
				$this->_year += floor($newValue / 12);
			} else {
				// The new month must be December
				$this->_month = 12;
				$this->_year += ($newValue / 12) - 1;
			}
		}
		$this->checkLastDayOfMonth();
		parent::setDate($this->_year, $this->_month, $this->_day);
	}

	/**
	 * Ver comentarios en addMonths
	 */
	public function subMonths($numMonths) {
		if (!is_numeric($numMonths)) {
			throw new Exception('addMonths() espera un entero');
		}
		$numMonths = abs(intval($numMonths));
		// Subtract the months from the current month number.
		$newValue = $this->_month - $numMonths;
		// If the result is greater than 0, it's still the same year,
		// and you can assign the new value to the month.
		if ($newValue > 0) {
			$this->_month = $newValue;
		} else {
			// Create an array of the months in reverse.
			$months = range(12 , 1);
			// Get the absolute value of $newValue.
			$newValue = abs($newValue);
			// Get the array position of the resulting month.
			$monthPosition = $newValue % 12;
			$this->_month = $months[$monthPosition];
			// Arrays begin at 0, so if $monthPosition is 0,
			// it must be December.
			if ($monthPosition) {
				$this->_year -= ceil($newValue / 12);
			} else {
				$this->_year -= ceil($newValue / 12) + 1;
			}
		}
		$this->checkLastDayOfMonth();
		parent::setDate($this->_year, $this->_month, $this->_day);
	}

	/**
	 * Ver comentarios en addMonths
	 */
	public function addYears($numYears) {
		if (!is_numeric($numYears) || $numYears < 1) {
			throw new Exception('addYears() espera un entero positivo');
		}
		$this->_year += (int) $numYears;
		$this->checkLastDayOfMonth();
		parent::setDate($this->_year, $this->_month, $this->_day);
	}

	/**
	 * Ver comentarios en addMonths
	 */
	public function subYears($numYears) {
		if (!is_numeric($numYears)) {
			throw new Exception('subYears() espera un entero');
		}
		$this->_year -= abs(intval($numYears));
		$this->checkLastDayOfMonth();
		parent::setDate($this->_year, $this->_month, $this->_day);
	}

	/**
	 * Comprueba si la fecha interna calculada en metodos addMonths, subMonths, ... es correcta. 
	 * En estos métodos solo se cambia el mes y año, por lo que sólo hay que comprobar
	 * que el dia siga existiendo en el mes-año en curso, y en caso contrario ajusta el día al último del mes
	 */
	final protected function checkLastDayOfMonth() {
		if (!checkdate($this->_month, $this->_day, $this->_year)) {
			$use30 = array(4 , 6 , 9 , 11);
			if (in_array($this->_month, $use30)) {
				$this->_day = 30;
			} else {
				$this->_day = $this->isLeap() ? 29 : 28;
			}
		}
	}

	/**
	 * Indica si el año es bisiesto
	 */
	public function isLeap() {
		if ($this->_year % 400 == 0 || ($this->_year % 4 == 0 && $this->_year % 100 != 0)) {
			return true;
		} else {
			return false;
		}
	}

}
?>
