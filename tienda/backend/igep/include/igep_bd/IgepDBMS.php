<?php
/**
* Es una clase virtual, que contiene los metodos que deben implementar cada
* clase hija. Habrá una clase hija por cada SGBD empleado.
* Aqui estarán todas las características particulares de cada gestor de BD. 
*
* @package gvHIDRA
*/
class IgepDBMS {

  /**
   * Modifica, si procede, los parámetros de la conexión.
   * Por ejemplo, los parámetros usados, que no se usen ciertas características
   * de compatibilidad, ...
   * @param mixed dsn que utiliza pear:db para la conexión
   * @return mixed devuelve el dsn modificado
   */
  function preConexion($p_dsn){
  	 return $p_dsn;
  }		

  /**
   * Modifica, si procede, la conexión establecida.
   * Por ejemplo, formato de fechas, números, idioma, ...
   * @param conexion recibe una conexión establecida
   */
  function postConexion($p_conexion){
  }		

  /**
   * Indica los carácteres usados para esta conexión (separador decimal y de miles).
   * Es necesario definir para cada conexión.
   *
   * @access public
   * @static
   * @param mixed dsn que utiliza pear:db para la conexión
   * @return mixed array asociativo con entrada 'DECIMAL' y 'GROUP'
   */
  function caracteresNumericos($p_dsn){
	throw new Exception('No están definidos los carácteres numéricos para: '.$p_dsn['phptype']);
  }

  /**
   * Indica la máscara de fechas utilizada para la conexión
   *
   * @access public
   * @static
   * @param mixed dsn que utiliza pear:db para la conexión
   * @return string que indica la mascara de fechas utilizada.
   */
  function mascaraFechas($p_dsn){
	throw new Exception('No está definido el formato de fechas para: '.$p_dsn['phptype']);    
  }

	/**
	 * Inicia la transacción (begin, begin work, ...)
	 * @param conexion recibe una conexión establecida
	 * @return mixed devuelve el resultado
	 */
	function empezarTransaccion($p_conexion){
		return $p_conexion->beginTransaction();
	}

	/**
	 * Finaliza la transacción con commit o rollback
	 * @param conexion recibe una conexión establecida
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
	throw new Exception('No está definida la obtención de secuencias de BD para el tipo de BD indicado.');
  }

  /**
   * Devuelve la cadena sin marcas diacríticas. Se podrá utilizar en las comparaciones de cadenas.
   *
   * @access public
   * @static
   * @param string cadena que a la que se le quiere quitar los acentos.
   * @return string
   */
  function unDiacritic($param){
    return "translate($param,'áàéèíóòúüç','aaeeioouuc')";
  }

	/**
	 * Devuelve la cadena para pasar a texto un campo usado en las ventanas de selección.
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
		throw new Exception('No está definida la detección de bloqueos.');
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
		
		throw new Exception('No está definido el escape de la contrabarra.');
	}
}

?>