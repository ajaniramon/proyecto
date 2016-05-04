<?php
/**
 * Es una clase static, que centralizará la mayor parte de aspectos relacionados
 * La mayor parte de veces invocará a la correspondiente clase derivada de IgepDBMS.
 *
 * No se han tratado las cuestiones de portabilidad que pueden activarse mediante
 * las funciones 'connect' y 'setOption'. En el momento que se puedan establecer
 * conexiones "fuera de igep" habrá que controlarlo.
 *
 * @package gvHIDRA
 */
class IgepDB {

  /**
   * Modifica, si procede, los parámetros de la conexión.
   * 
   * @access public
   * @static
   * @param mixed dsn que utiliza pear:db para la conexión
   * @return mixed devuelve el dsn modificado
   */
  static function preConexion($p_dsn){
    $obj = IgepDB::creaDBMS($p_dsn);
    return $obj->preConexion($p_dsn);
  }		

  /**
   * Modifica, si procede, la conexión establecida.
   * 
   * @access public
   * @static
   * @param mixed dsn que utiliza pear:db para la conexión
   * @param conexion recibe una conexión establecida
   */
  static function postConexion($p_dsn,$p_conexion){
    $obj = IgepDB::creaDBMS($p_dsn);
    $obj->postConexion($p_conexion);
  }		

  /**
   * Indica los carácteres usados para esta conexión (separador decimal y de miles)
   *
   * @access public
   * @static
   * @param mixed dsn que utiliza pear:db para la conexión
   * @return mixed array asociativo con entrada 'DECIMAL' y 'GROUP'
   */
  static function caracteresNumericos($p_dsn){
    $obj = IgepDB::creaDBMS($p_dsn);
    return $obj->caracteresNumericos($p_dsn);
  }

  /**
   * Indica la máscara de fechas utilizada para esta conexión
   *
   * @access public
   * @static
   * @param mixed dsn que utiliza pear:db para la conexión
   * @return mixed array asociativo con entrada 'DECIMAL' y 'GROUP'
   */
  static function mascaraFechas($p_dsn){
    $obj = IgepDB::creaDBMS($p_dsn);
    return $obj->mascaraFechas($p_dsn);
  }

  /**
   * Devuelve los tipos de drives soportados.
   * Las cadenas contienen el valor soportado por los DSN de pear:mdb2 en campo 'phptype'
   * 
   * @access public
   * @return mixed devuelve el resultado
   */
  static function supportedDBMS(){
    return array('pgsql','oci8','mysql','sqlsrv');
  }

  /**
   * Inicia la transacción.
   * 
   * @access public
   * @static
   * @param mixed dsn que utiliza pear:db para la conexión
   * @param conexion recibe una conexión establecida
   * @return mixed devuelve el resultado
   */
  function empezarTransaccion($p_dsn,$p_conexion){
    $obj = IgepDB::creaDBMS($p_dsn);
    return $obj->empezarTransaccion($p_conexion);
  }		

  /**
   * Acaba la transacción en función del parámetro
   * 
   * @access public
   * @static
   * @param mixed dsn que utiliza pear:db para la conexión
   * @param conexion recibe una conexión establecida
   * @param boolean indica si hay que confirmar o deshacer
   * @return mixed devuelve el resultado
   */
  function acabarTransaccion($p_dsn, $p_conexion, $p_error){
    $obj = IgepDB::creaDBMS($p_dsn);
    return $obj->acabarTransaccion($p_conexion, $p_error);
  }		

  /**
   * Obtiene el tipo de dbms usado en una conexión
   * 
   * @access private
   * @static
   * @param mixed dsn usado en pear::db
   * @return string dbms
   */
  static function obtenerDBMS($p_dsn){
  	$resul = $p_dsn['phptype'];
  	if ($resul=='mysqli')
  		$resul = 'mysql';
  	if (!in_array($resul, self::supportedDBMS())) {
		throw new Exception("El tipo de dsn no está soportado: '$resul'");
  	}
  	return $resul;
  }

  /**
   * Crea una instancia del tipo IgepDBMS_* que corresponde a $p_dsn
   * 
   * @access private
   * @static
   * @param mixed dsn usado en pear::db
   * @return IgepDBMS instancia de IgepDBMS_*
   */
  static function creaDBMS($p_dsn){
    $tipo = IgepDB::obtenerDBMS($p_dsn);
    $nombreclase = 'IgepDBMS_'.$tipo;
	//TODO: por algun motivo hay una llamada con getcwd() a '/' y da un warning en el include
	if (!class_exists($nombreclase))
		include_once("igep/include/igep_bd/${nombreclase}.php");
    if (!class_exists($nombreclase)) {
    	throw new Exception('No existe la clase '.$nombreclase);
    }
    @$obj = new $nombreclase;
    if (!isset($obj)) {
    	throw new Exception('No se ha podido instanciar la clase '.$nombreclase);
    }
  	return $obj;
  }

  /**
   * Crea la cadena del LIMIT adecuada al tipo de DBMS.
   * 
   * @access private
   * @static
   * @param string str_where cadena que contiene la where. Se utiliza en el caso de Oracle, ya que limitamos con el ROWNUM
   * @param array p_dsn DSN de conexion a la BD.
   * @param integer int_limit límite que se le quiere incorporar a la consulta
   * @return string cadena resultado del límite
   */
  static function obtenerLimit(& $str_where, $p_dsn,$int_limit){  
    switch($p_dsn['phptype']){
      case 'pgsql':
      case 'mysql':
      case 'mysqli':
        $limite = ' LIMIT '.$int_limit;
        break;
      case 'oci8':
        if($str_where!='')
          $str_where.= ' AND';
        else
          $str_where.= ' WHERE';
        $str_where.= ' ROWNUM <= '.$int_limit;
        $limite = '';
        break;
      default:
        $limite ='';
    }
    return $limite;
  }
  
  static function obtenerSecuenciaBD($p_dsn,$sequence){
    $obj = IgepDB::creaDBMS($p_dsn);
    return $obj->obtenerSecuenciaBD($sequence);
  }


  /**
   * Devuelve la cadena sin acentos teniendo en cuenta el tipo de DBMS. (En MySQL se debe utilizar la función de translate anidada).
   *
   * @access public
   * @static
   * @param string cadena que a la que se le quiere quitar los acentos.
   * @return string
   */  
   static function unDiacritic($p_dsn,$param){
    $obj = IgepDB::creaDBMS($p_dsn);
    return $obj->unDiacritic($param);
  }

	/**
	 * Devuelve la cadena para pasar a texto un campo usado en las ventanas de selección.
	 * Solo hace falta definirlo cuando salgan problemas de conversiones en
	 * ventanas de seleccion y filtros que usan like
	 *
	 * @access public
	 * @static
	 * @return string
	 */
	static function toTextForVS($p_dsn, $param){
		$obj = IgepDB::creaDBMS($p_dsn);
		return $obj->toTextForVS($param);
	}

	/**
	 * Devuelve la cadena para concatenar dos campos
	 * Si alguno es nulo lo reemplaza por cadena vacia
	 *
	 * @access public
	 * @static
	 * @return string
	 */
	static function concat($p_dsn, $p1, $p2){
		$obj = IgepDB::creaDBMS($p_dsn);
		return $obj->concat($p1, $p2);
	}

	/**
	 * Devuelve la cadena usada en una consulta para bloqueo exclusivo de registros
	 *
	 * @access public
	 * @static
	 * @return string
	 */  
	static function obtenerBloqueo($p_dsn){
		$obj = IgepDB::creaDBMS($p_dsn);
		return $obj->obtenerBloqueo();
	}

	/**
	 * Devuelve si el objeto de error es debido a que la(s) fila(s) no se puede(n) bloquear
	 * 
	 * @access public
	 * @static
   	 * @param result objeto error de IgepError
	 * @return boolean
	 */
	function isLocked($p_dsn, $result){
		$obj = IgepDB::creaDBMS($p_dsn);
		return $obj->isLocked($result);
	}

	/**
	 * Devuelve la cadena que se debe utilizar para escapar la contrabarra
	 * 
	 * En Oracle no utilizamos dicha cadena, en Postgres y MySql si 
	 * 
	 * @access public
	 * @return string
	 */
	public function backSlashScape($p_dsn){
		$obj = IgepDB::creaDBMS($p_dsn);
		return $obj->backSlashScape();		
	}
}

?>