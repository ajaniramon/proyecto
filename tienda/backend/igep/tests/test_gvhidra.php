<?php
/**
 * Ficheros auxiliares para la ejecucion de los tests
 * En este se ejecutan todos las tests de gvHIDRA.
 *
 * package gvHIDRA
 */

require_once ('igep/tests/test_inc.php');

class test_gvhidra {

	public static function suite() {
		global $clases;  // se inicializa en test_inc.php
	    $suite = new PHPUnit_Framework_TestSuite();
	    foreach ($clases as $cl)
			$suite->addTestSuite($cl);
		return $suite;
	}

}

?>
