<?php
/**
 * Excepciones definidas para el framework. Jerarquia:
 * 
 * Exception
 *   gvHidraException
 *     gvHidraSQLException
 *       gvHidraLockException
 *       gvHidraPrepareException
 *       gvHidraExecuteException
 *       gvHidraFetchException
 *       gvHidraNotInTransException
 * 
 * PROPUESTAS!!!  (ver tambien las de SPL en http://www.php.net/~helly/php/ext/spl/classException.html)
 *     gvHidraIOException
 * 
 * @package gvHIDRA
 */

/**
 * clase base de todas las excepciones gvHidra
 */
class gvHidraException extends Exception
{
	function __construct($message='', $code=0, $prev_excep=null)
	{
		// el parametro $prev_excep esta disponible a partir de php 5.3
		if (version_compare(PHP_VERSION, '5.3.0') >= 0)
			parent::__construct($message, $code, $prev_excep);
		else
			parent::__construct($message, $code);
	}
}

/**
 * Excepciones en operaciones SQL
 */
class gvHidraSQLException extends gvHidraException
{
	private $sqlerror;

	/**
	 * Modifico constructor para permitir indicar el error pear
	 */
	function __construct($message='', $code=0, $prev_excep=null, $pear_err=null)
	{
		parent::__construct($message, $code, $prev_excep);
		
		// me guardo el error en atributo
		$this->sqlerror = $pear_err;
	}

	/**
	 * obtener error
	 */
	function getSqlerror()
	{
		return $this->sqlerror;
	}
}

/**
 * Excepciones cuando no se puede bloquear un recurso
 */
class gvHidraLockException extends gvHidraSQLException {}

/**
 * Excepciones cuando no se puede preparar una sentencia
 */
class gvHidraPrepareException extends gvHidraSQLException {}

/**
 * Excepciones cuando no se puede ejecutar una sentencia preparada
 */
class gvHidraExecuteException extends gvHidraSQLException {}

/**
 * Excepciones cuando no se puede recuperar datos de una consulta
 */
class gvHidraFetchException extends gvHidraSQLException {}

/**
 * Excepciones cuando no se puede recuperar datos de una consulta
 */
class gvHidraNotInTransException extends gvHidraSQLException {}

?>
