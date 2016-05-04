<?php
/**
 * 
 * Script de ayuda para ejecutar los tests de phpunit en gvHIDRA
 * Requiere que la ruta donde está ubicado el ejecutable 'phpunit' esté en el PATH
 * 
 */

$cmdname=$argv[0];
$uso ="

Script usado para lanzar los tests de gvHIDRA y/o una aplicacion usando PHPUnit.

	Uso $cmdname carpeta_o_fichero

	NOTA: el script esta pensado para invocar desde eclipse, recibiendo el
parametro \${resource_loc}.
	
";

/**
 * 
 * detecta tipo de test a ejecutar
 * @param string $ruta
 */
function info_ruta($ruta) {
	$p = strpos($ruta,'igep'.DIRECTORY_SEPARATOR.'tests'.DIRECTORY_SEPARATOR);
	$p4 = strpos($ruta,'igep'.DIRECTORY_SEPARATOR.'tests'.DIRECTORY_SEPARATOR.'test_all.php');
	$p2 = strpos($ruta,DIRECTORY_SEPARATOR.'igep');
	$p3 = strpos($ruta,DIRECTORY_SEPARATOR.'tests'.DIRECTORY_SEPARATOR);
	if ($p4 !== false) {
		// projecto + gvHIDRA
		$raiz = substr($ruta, 0, $p4 - 1);
		$tipo = 5;
	} elseif ($p !== false) {
		// clase en gvHIDRA
		$raiz = substr($ruta, 0, $p - 1);
		$tipo = 4;
	} elseif ($p2 !== false) {
		// gvHIDRA
		$raiz = substr($ruta, 0, $p2);
		$tipo = 2;
	} elseif ($p2 === false and $p3 !== false) {
		// clase en proyecto
		$raiz = substr($ruta, 0, $p3);
		$tipo = 1;
	} elseif ($p2 === false and $p3 === false) {
		// proyecto
		$raiz = $ruta;
		$tipo = 3;
	} else
		throw new Exception("Tipo de test desconocido");
	
	return array(
	 'prj'=> substr($raiz,strrpos($raiz, DIRECTORY_SEPARATOR)+1),
	 'raiz'=> $raiz,
	 'tipo'=> $tipo,
	);
}

/**
 * invoca a phpunit
 */
function phpunit($clase, $inc, $ruta) {
	// opciones utiles de phpunit: --verbose, --debug
	
	// tipos de ejecución
	// 1: funciona cambiando algo
	// 2: probar funcionamiento como iba en cit
	$tipo = 1;
	if ($tipo == 1)
		exec("phpunit --bootstrap $inc $clase $ruta", $out);
	elseif ($tipo == 2)
		exec("phpunit $clase $inc", $out);
	else
		throw new Exception("Tipo de ejecución desconocida");
	
	print implode("\n",$out);
}

/**
 * Programa principal
 * 
 */

if ($argc != 2) {
	die($uso);
}

$resource_name=$argv[1];
$resource_parts = pathinfo($resource_name);
$clase = $resource_parts['filename'];
$info = info_ruta($resource_name);
$prj = $info['prj'];

chdir($info['raiz']);

switch ($info['tipo']) {
    case 1:
    	echo "--- test de la clase '$clase' de prj '$prj' --- ";
    	phpunit($clase, 'igep/tests/test_incprj.php', "tests/$clase.php");
        break;
        
    case 2:
    	echo '--- test de gvHIDRA --- ';
    	phpunit('test_gvhidra', 'igep/tests/test_gvhidra.php','igep/tests/test_gvhidra.php');
        break;

    case 4:
    	echo "--- test de la clase '$clase' de gvHIDRA --- ";
    	phpunit($clase, 'igep/tests/test_inc.php',"igep/tests/$clase.php");
        break;

    case 3:
		echo "--- test de prj '$prj' --- ";
    	phpunit('test_prj', 'igep/tests/test_prj.php','igep/tests/test_prj.php');
        break;

    case 5:
    	echo "--- test de prj '$prj' + gvHIDRA --- ";
    	phpunit('test_all', 'igep/tests/test_all.php','igep/tests/test_all.php');
        break;
}

?>
