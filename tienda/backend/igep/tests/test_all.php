<?php
/**
 * Ficheros auxiliares para la ejecucion de los tests
 * En este se ejecutan todos los tests de gvHIDRA y del proyecto
 *
 * package gvHIDRA
 */

//include_once ('igep/tests/test_incprj.php');
include_once ('igep/tests/test_prj.php');
include_once ('igep/tests/test_gvhidra.php');

class test_all {

	public static function suite() {
	    $suite = new PHPUnit_Framework_TestSuite('prj+gvHIDRA');
		$suite->addTest(test_gvhidra::suite());
		$suite->addTest(test_prj::suite());
		return $suite;
	}

}

?>
