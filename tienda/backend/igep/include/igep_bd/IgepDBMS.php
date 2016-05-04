<?php
/**
* Es una clase virtual, que contiene los metodos que deben implementar cada
* clase hija. Habr� una clase hija por cada SGBD empleado.
* Aqui estar�n todas las caracter�sticas particulares de cada gestor de BD. 
*
* @package gvHIDRA
*/
class IgepDBMS {

  /**
   * Modifica, si procede, los par�metros de la conexi�n.
   * Por ejemplo, los par�metros usados, que no se usen ciertas caracter�sticas
   * de compatibilidad, ...
   * @param mixed dsn que utiliza pear:db para la conexi�n
   * @return mixed devuelve el dsn modificado
   */
  function preConexion($p_dsn){
  	 return $p_dsn;
  }		

  /**
   * Modifica, si procede, la conexi�n establecida.
   * Por ejemplo, formato de fechas, n�meros, idioma, ...
   * @param conexion recibe una conexi�n establecida
   */
  function postConexion($p_conexion){
  }		

  /**
   * Indica los car�cteres usados para esta conexi�n (separador decimal y de miles).
   * Es necesario definir para cada conexi�n.
   *
   * @access public
   * @static
   * @param mixed dsn que utiliza pear:db para la conexi�n
   * @return mixed array asociativo con entrada 'DECIMAL' y 'GROUP'
   */
  function caracteresNumericos($p_dsn){
	throw new Exception('No est�n definidos los car�cteres num�ricos para: '.$p_dsn['phptype']);
  }

  /**
   * Indica la m�scara de fechas utilizada para la conexi�n
   *
   * @access public
   * @static
   * @param mixed dsn que utiliza pear:db para la conexi�n
   * @return string que indica la mascara de fechas utilizada.
   */
  function mascaraFechas($p_dsn){
	throw new Exception('No est� definido el formato de fechas para: '.$p_dsn['phptype']);    
  }

	/**
	 * Inicia la transacci�n (begin, begin work, ...)
	 * @param conexion recibe una conexi�n establecida
	 * @return mixed devuelve el resultado
	 */
	function empezarTransaccion($p_conexion){
		return $p_conexion->beginTransaction();
	}

	/**
	 * Finaliza la transacci�n con commit o rollback
	 * @param conexion recibe una conexi�n establecida
	 * @param boolean indica si hay que confirmar o deshacer
	 * @return mixed devuelve el resultado
	 */
	function acabarTransaccion($p_conexion, $p_error){
		if ($p_error)	
			return $p_conexion->rollback();
		else
			return $p_conexion->commit();		
	}

  /**
   * Devuelve la cadena SQL que permite obtener el valor de una secuencia de BD
   *
   * @access public
   * @static
   * @param string sequence cadena que contiene el nombre de la secuencia.
   * @return string
   */
  function obtenerSecuenciaBD($sequence){
	throw new Exception('No est� definida la obtenci�n de secuencias de BD para el tipo de BD indicado.');
  }

  /**
   * Devuelve la cadena sin marcas diacr�ticas. Se podr� utilizar en las comparaciones de cadenas.
   *
   * @access public
   * @static
   * @param string cadena que a la que se le quiere quitar los acentos.
   * @return string
   */
  function unDiacritic($param){
    return "translate($param,'����������','aaeeioouuc')";
  }

	/**
	 * Devuelve la cadena para pasar a texto un campo usado en las ventanas de selecci�n.
	 * Solo hace falta definirlo cuando salgan problemas de conversiones en
	 * ventanas de seleccion y filtros que usan like
	 *
	 * @access public
	 * @return string
	 */
	function toTextForVS($param) {
		return $param;
	}

	/**
	 * Devuelve la cadena para concatenar dos campos
	 * Si alguno es nulo lo reemplaza por cadena vacia
	 *
	 * @access public
	 * @static
	 * @return string
	 */
	function concat($p1, $p2) {
		return "concat($p1,$p2)";
	}

	/**
	 * Devuelve la cadena usada en una consulta para bloqueo exclusivo de registros
	 *
	 * @access public
	 * @static
	 * @return string
	 */
	function obtenerBloqueo(){
		return 'for update nowait';
	}

	/**
	 * Devuelve si el objeto de error es debido a que la(s) fila(s) no se puede(n) bloquear
	 *
	 * @access public
	 * @static
   	 * @param result objeto error de IgepError
	 * @return boolean
	 */
	function isLocked($result){
		throw new Exception('No est� definida la detecci�n de bloqueos.');
	}

	/**
	 * Devuelve la cadena que se debe utilizar para escapar la contrabarra
	 * 
	 * En Oracle no utilizamos dicha cadena, en Postgres y MySql si 
	 * 
	 * @access public
	 * @return string
	 */
	public function backSlashScape(){
		
		throw new Exception('No est� definido el escape de la contrabarra.');
	}
}

?>