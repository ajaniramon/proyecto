<?php
/**
 * Clase que define las características particulares del driver oci8
 * Sobre la portabilidad, este driver por defecto activa DB_PORTABILITY_LOWERCASE
 * y DB_PORTABILITY_DELETE_COUNT.
 *
 * @package gvHIDRA
 */
include_once('igep/include/igep_bd/IgepDBMS.php');

class IgepDBMS_oci8 extends IgepDBMS
{
	
	/**
	 * Modifica, si procede, los parámetros de la conexión. 
	 * @param mixed dsn que utiliza pear:db para la conexión
	 * @return mixed devuelve el dsn modificado
	*/
	function preConexion($p_dsn)
	{
		//Necesitamos que estas variables esten fijadas para comunicarnos con el SGBD
		//putenv("LC_ALL=es_ES.ISO8859-1");
		putenv("NLS_LANG=SPANISH_SPAIN.WE8ISO8859P15");
		//setlocale(LC_ALL, 'es_ES@euro', 'es_ES', 'es', 'es_ES.ISO8859-1');
		//putenv("LANG=".'es_ES.ISO8859-1');
		
		if (@$p_dsn['database'] != '')
		{
			throw new Exception('El parámetro "database" no se usa para oracle');
		}
		return $p_dsn;
	}

  /**
   * Acciones realizadas:
   * - Fija el formato por defecto de las fechas
   * - Fijar el idioma español (no porque de momento ya se ha configurado el servidor en español)
   * - Registrar la sesión en oracle
   * @param conexion recibe una conexión establecida
   */
  function postConexion($p_conexion){
    $query = 'alter session set nls_date_format=\'dd/mm/yyyy hh24:mi:ss\'';
    $result = $p_conexion->exec($query);
    if (PEAR::isError($result)){
		throw new Exception('Error al fijar variables de sesión nls_date_format');
    }
    $query = 'alter session set nls_numeric_characters=\'.,\'';
    $result = $p_conexion->exec($query);
    if (PEAR::isError($result)){
		throw new Exception('Error al fijar variables de sesión nls_numeric_characters');
    }
    // Registrar sesion
    $aplicacion=IgepSession::dameAplicacion();
    // Lo ideal seria coger el modulo URL que estoy ejecutando, en vez del script_name
    $mod = strtoupper(IgepSession::dameUsuario()).'@'.$_SERVER['SCRIPT_NAME'];
	$query = "begin dbms_application_info.set_module( '$aplicacion', '$mod' ); end;";
    $result = $p_conexion->exec($query);
    if (PEAR::isError($result)){
            echo 'Error al registrar la sesión: '.$query;
            return;
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
    return 'j/n/Y';
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
    return 'SELECT '.$sequence.'.NextVal as "nextval" from DUAL';
  }

  /**
  * Devuelve si el objeto de error es debido a que la(s) fila(s) no se puede(n) bloquear
  * [Native code: 54[Native message: ORA-00054: resource busy and acquire with NOWAIT specified
  * 
  * @access public
  * @param result objeto error de IgepError
  * @return boolean
  */
  function isLocked($result){
    return (strpos($result,'[Native code: 54[')!==false);
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

    return "\\";
  }
}

?>