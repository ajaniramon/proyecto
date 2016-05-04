<?php
/**
 * Clase que define las caracter�sticas particulares del driver mysql
 * Sobre la portabilidad, este driver por defecto no activa nada
 * 
 * @package gvHIDRA
 */
include_once('igep/include/igep_bd/IgepDBMS.php');

class IgepDBMS_mysql extends IgepDBMS {

  /**
   * Acciones realizadas:
   * - fijar la codificacion
   * 
   * @param conexion recibe una conexi�n establecida
   */
  function postConexion($p_conexion){
    $query = 'set names \'LATIN1\'';    
    $result = $p_conexion->exec($query);
    if (PEAR::isError($result)){
		throw new Exception('Error al fijar encoding');
    }
//    parece que funciona a partir de otra version del plugin
//    $result = $p_conexion->exec('set innodb_lock_wait_timeout 5');
//    if (PEAR::isError($result)){
//		throw new Exception('Error al fijar innodb_lock_wait_timeout '.var_export($result,true));
//    }
  }

  /**
   * Indica los car�cteres usados para esta conexi�n (separador decimal y de miles)
   *
   * @access public
   * @static
   * @param mixed dsn que utiliza pear:db para la conexi�n
   * @return mixed array asociativo con entrada 'DECIMAL' y 'GROUP'
   */
  function caracteresNumericos($p_dsn){
    return array('DECIMAL'=>'.','GROUP'=>'');
  }

    /**
   * Indica la m�scara de fechas utilizada para esta conexi�n
   *
   * @access public
   * @static
   * @param mixed dsn que utiliza pear:db para la conexi�n
   * @return string que indica la mascara de fechas utilizada.
   */
  function mascaraFechas($p_dsn){
    return 'Y-m-d';    
  }

  /**
   * Devuelve la cadena sin acentos. Se podr� utilizar en las comparaciones de cadenas.
   *
   * @access public
   * @static
   * @param string cadena que a la que se le quiere quitar los acentos.
   * @return string
   */
  function unDiacritic($param){
    $res= "REPLACE($param,'�','a')";
    $res= "REPLACE($res,'�','a')";
    $res= "REPLACE($res,'�','e')";
    $res= "REPLACE($res,'�','e')";
	$res= "REPLACE($res,'�','i')";
    $res= "REPLACE($res,'�','o')";
	$res= "REPLACE($res,'�','o')";
    $res= "REPLACE($res,'�','u')";
	$res= "REPLACE($res,'�','u')";
	return $res;
  }

	/**
	 * Devuelve la cadena usada en una consulta para bloqueo exclusivo de registros
	 *
	 * @access public
	 * @static
	 * @return string
	 */
	function obtenerBloqueo(){
		IgepDebug::setDebug(WARNING,'mysql no soporta el nowait; se hace el bloqueo con espera y timeout');
		return 'for update';
	}

	/**
	 * Devuelve si el objeto de error es debido a que la(s) fila(s) no se puede(n) bloquear
	 * [Native code: 1205[Native message: Lock wait timeout exceeded; try restarting transaction
	 * 
	 * @access public
   	 * @param result objeto error de IgepError
	 * @return boolean
	 */
	function isLocked($result){
		return (strpos($result,'[Native code: 1205[')!==false);
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
	
	/**
	 * Devuelve la cadena para pasar a texto un campo usado en las ventanas de selecci�n.
	 * Solo hace falta definirlo cuando salgan problemas de conversiones en
	 * ventanas de seleccion y filtros que usan like
	 *
	 * @access public
	 * @static
	 * @return string
	 */
	public function toTextForVS($param) {
		return 'cast('.$param.' as char)';
	}
}  		
?>