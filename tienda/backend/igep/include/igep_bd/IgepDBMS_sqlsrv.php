<?php
/**
 * Clase que define las caracter�sticas particulares del driver sqlsrv (SQL Server)
 *
 * @package gvHIDRA
 */
include_once('igep/include/igep_bd/IgepDBMS.php');

class IgepDBMS_sqlsrv extends IgepDBMS {

	/**
	 * Indica los car�cteres usados para esta conexi�n (separador decimal y de miles)
	 *
	 * @access public
	 * @static
	 * @param mixed dsn que utiliza pear:mdb2 para la conexi�n
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
	 * @param mixed dsn que utiliza pear:mdb2 para la conexi�n
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
	 * @param string cadena a la que se le quiere quitar los acentos.
	 * @return string
	 */
	function unDiacritic($param){
		$res= "REPLACE($param,'�','a') ";
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
	 * Devuelve la cadena para pasar a texto un campo usado en las ventanas de selecci�n.
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