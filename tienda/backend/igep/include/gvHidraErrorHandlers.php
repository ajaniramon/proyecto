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
* Control de errores PHP
* El objetivo es mostrar aquellos mensajes importantes en el debug
* Los errores de tipo E_STRICT no se pueden enviar al debug, por lo que sólo salen en el log del apache.
* 
* package gvHIDRA
*/


/**
 * Poner a true para ver más información (en debug y log apache)
 */
define('SHOW_DEPRECATED',false);

/**
 * Cuando SHOW_DEPRECATED, poner a true para ver más información de plantillas compiladas
 */
define('SHOW_COMPILED',false);

/**
 * Cuando SHOW_DEPRECATED, poner a true para ver más información de debug de gvHIDRA
 */
define('SHOW_gvHIDRA',false);

/**
 * Clase con métodos estáticos
 */
class gvHidraErrorHandlers {

	/**
	 * Lista de tipos de errores posibles
	 */
	static private $error_list = array(
		E_ERROR => 'E_ERROR',
		E_WARNING => 'E_WARNING',
		E_PARSE => 'E_PARSE',
		E_NOTICE => 'E_NOTICE',
		E_CORE_ERROR => 'E_CORE_ERROR',
		E_CORE_WARNING => 'E_CORE_WARNING',
		E_COMPILE_ERROR => 'E_COMPILE_ERROR',
		E_COMPILE_WARNING => 'E_COMPILE_WARNING',
		E_USER_ERROR => 'E_USER_ERROR',
		E_USER_WARNING => 'E_USER_WARNING',
		E_USER_NOTICE => 'E_USER_NOTICE',
		E_STRICT => 'E_STRICT',
		E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
		// nuevas en PHP 5.3, comento constantes para evitar warning en 5.2
		/*E_DEPRECATED*/ 8192 => 'E_DEPRECATED',
		/*E_USER_DEPRECATED*/ 16384 => 'E_USER_DEPRECATED',
		E_ALL => 'E_ALL',
		);

	/**
	 * Formato usado para mostrar errores
	 */
	static private $str_fmt = 'PHP %s:  %s in %s on line %d';

	/**
	 * Manejador de errores, usado en set_error_handler
	 * Si el error es uno que para la ejecución, lo convierte en Excepcion
	 * El resto los envia al debug o al log del apache
	 */
	static function error_handler($number, $string, $file, $line, $context)
	{
	    // Determine if this error is one of the enabled ones in php config (php.ini, .htaccess, etc)
	    $error_is_enabled = (bool)($number & ini_get('error_reporting') );
	   
	    // -- FATAL ERROR
	    // throw an Error Exception, to be handled by whatever Exception handling logic is available in this context
	    if( in_array($number, array(E_USER_ERROR, E_RECOVERABLE_ERROR)) && $error_is_enabled ) {
	        throw new ErrorException($string, 0, $number, $file, $line);
	    }
	   
	    // -- NON-FATAL ERROR/WARNING/NOTICE
	    // Log the error if it's enabled, otherwise just ignore it
	    elseif( $error_is_enabled ) {
			self::log(WARNING, sprintf(self::$str_fmt, self::errorText($number), $string, $file, $line), ($number!=E_STRICT));
	        return false; // Make sure this ends up in $php_errormsg, if appropriate

		// otros
	    } else {
			if (!in_array($number, array(E_STRICT, E_NOTICE, E_WARNING)) or 
			    (SHOW_DEPRECATED and 
			     (SHOW_COMPILED or strpos($file,'/templates_c/')===false) and
			     (SHOW_gvHIDRA  or strpos($file,'/igep/')===false))
			   )
	    		self::log(NOTICE, sprintf(self::$str_fmt, self::errorText($number), $string, $file, $line), ($number!=E_STRICT));
		    return false;
	    }
	}

	/**
	 * Método invocado al final de cada peticion para detectar si se ha acabado con error
	 */
	static function error_alert()
	{
		$e = error_get_last();
		if (!is_null($e))
			if ($e['type'] != E_STRICT and $e['type'] != E_NOTICE)
				self::log(ERROR, sprintf(self::$str_fmt, self::errorText($e['type']), $e['message'], $e['file'], $e['line']));
	}

	/**
	 * Traduce el codigo de error al nombre de la constante en texto
	 * Devuelve UNKNOWN si no existe
	 */
	static function errorText($code)
	{
		if (array_key_exists($code, self::$error_list))
			return self::$error_list[$code];
		return 'UNKNOWN ('.$code.')';
	}

	/**
	 * Metodo para enviar mensaje a IgepDebug o a log de apache, segun el punto donde ocurra
	 * Para que funcione IgepDebug, ha de hacerse incluido ya la clase ComponentesMap, la ultima de include_class.php
	 * Además el codigo del propio IgepDebug tiene errores E_STRICT y E_NOTICE (dificiles de solucionar porque son del PEAR),
	 * por lo que no podemos registrarlos en él.
	 */
	static private function log($type, $msg, $debug=true)
	{
		if ($debug and class_exists('ComponentesMap') and class_exists('MDB2') and class_exists('IgepBD'))
			IgepDebug::setDebug($type, $msg);
		else
			error_log($msg, 0);
	}
}

/**
 * cambiar manejador de errores
 */
$gvh_old_error_handler = set_error_handler(array('gvHidraErrorHandlers', 'error_handler'));

/**
 * añadir metodo al final de peticion para detectar si se ha acabado con error
 */
register_shutdown_function(array('gvHidraErrorHandlers', 'error_alert'));

?>
