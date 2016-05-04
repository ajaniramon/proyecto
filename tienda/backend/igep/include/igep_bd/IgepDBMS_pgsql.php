<?php
/**
 * Clase que define las características particulares del driver pgsql
 * Sobre la portabilidad, este driver por defecto no activa nada
 * @package gvHIDRA
 */
include_once('igep/include/igep_bd/IgepDBMS.php');

class IgepDBMS_pgsql extends IgepDBMS {

  /**
   * Acciones realizadas:
   * - 
   * @param conexion recibe una conexión establecida
   */
  function postConexion($p_conexion){
    $query = 'set session datestyle = \'sql, european\'';    
    $result = $p_conexion->exec($query);
    if (PEAR::isError($result)){
		throw new Exception('Error al fijar variables de sesión datestyle');
    }
    $query = 'set client_encoding=\'LATIN1\'';    
    $result = $p_conexion->exec($query);
    if (PEAR::isError($result)){
		throw new Exception('Error al fijar encoding');
    }
  }

  /**
   * Indica los carácteres usados para esta conexión (separador decimal y de miles)
   *
   * @access public
   * @static
   * @param mixed dsn que utiliza pear:db para la conexión
   * @return mixed array asociativo con entrada 'DECIMAL' y 'GROUP'
   */
  function caracteresNumericos($p_dsn){
  	return array('DECIMAL'=>'.','GROUP'=>'');
  }

  /**
   * Indica la máscara de fechas utilizada para esta conexión
   *
   * @access public
   * @static
   * @param mixed dsn que utiliza pear:db para la conexión
   * @return string que indica la mascara de fechas utilizada.
   */
  function mascaraFechas($p_dsn){

    //Utilizamos esta mascara porque es la que devuelve el metodo text() que aplicamos en las busquedas
    return 'd/m/Y';    
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
		return "SELECT nextval('$sequence') as \"nextval\"";
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
		return 'text('.$param.')';
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
		if ($p2 != "' '")
			$p2 = "coalesce($p2,'')";
		return "coalesce($p1,'')||".$p2;
	}

	/**
	 * Devuelve si el objeto de error es debido a que la(s) fila(s) no se puede(n) bloquear
	 * [Native message: ERROR: could not obtain lock on row in relation ...
	 * 
	 * @access public
   	 * @param result objeto error de IgepError
	 * @return boolean
	 */
	function isLocked($result){
		return (strpos($result,'could not obtain lock on row')!==false);
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
		
		return "\\\\";
	}
}
?>