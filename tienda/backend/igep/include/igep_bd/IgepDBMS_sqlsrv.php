<?php
/**
 * Clase que define las características particulares del driver sqlsrv (SQL Server)
 *
 * @package gvHIDRA
 */
include_once('igep/include/igep_bd/IgepDBMS.php');

class IgepDBMS_sqlsrv extends IgepDBMS {

	/**
	 * Indica los carácteres usados para esta conexión (separador decimal y de miles)
	 *
	 * @access public
	 * @static
	 * @param mixed dsn que utiliza pear:mdb2 para la conexión
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
	 * @param mixed dsn que utiliza pear:mdb2 para la conexión
	 * @return string que indica la mascara de fechas utilizada.
	 */
	function mascaraFechas($p_dsn){
		return 'Y-m-d';
	}

	/**
	 * Devuelve la cadena sin acentos. Se podrá utilizar en las comparaciones de cadenas.
	 *
	 * @access public
	 * @static
	 * @param string cadena a la que se le quiere quitar los acentos.
	 * @return string
	 */
	function unDiacritic($param){
		$res= "REPLACE($param,'á','a') ";
		$res= "REPLACE($res,'à','a')";
		$res= "REPLACE($res,'é','e')";
		$res= "REPLACE($res,'è','e')";
		$res= "REPLACE($res,'í','i')";
		$res= "REPLACE($res,'ó','o')";
		$res= "REPLACE($res,'ò','o')";
		$res= "REPLACE($res,'ú','u')";
		$res= "REPLACE($res,'ü','u')";
		return $res;
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
		return "convert(varchar,$param)";
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
		return "$p1+$p2";
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