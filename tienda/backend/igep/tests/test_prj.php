<?php
/**
 * Ficheros auxiliares para la ejecucion de los tests
 * En este se ejecutan todos los tests del proyecto
 *
 * package gvHIDRA
 */

include_once ('igep/tests/test_incprj.php');

class test_prj {

	public static function suite() {
		$clases = (file_exists('tests/')? includeDir('tests/'): array());
	    $suite = new PHPUnit_Framework_TestSuite('prj');
	    foreach ($clases as $cl)
			$suite->addTestSuite($cl);
		return $suite;
	}

}

?>
