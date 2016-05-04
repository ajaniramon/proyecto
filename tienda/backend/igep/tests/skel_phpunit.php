<?php
/**
 * 
 * Script de ayuda para crear el esquetele de tests phpunit en gvHIDRA
 * Requiere que la ruta donde está ubicado el ejecutable 'phpunit' esté en el PATH
 * 
 */

$cmdname=$argv[0];
$uso ="

Script usado para crear los tests en gvHIDRA para una clase usando PHPUnit.

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
	$p = strpos($ruta,'igep'.DIRECTORY_SEPARATOR);
	if ($p !== false) {
		// clase en gvHIDRA
		$raiz = substr($ruta, 0, $p - 1);
		$tipo = 1;
	} else {
		// clase en proyecto
		$raiz = $ruta;
		do {
			$raiz = substr($raiz, 0, strrpos($raiz, DIRECTORY_SEPARATOR));
		} while (!file_exists($raiz.DIRECTORY_SEPARATOR.'openApp.php') and !empty($raiz));
		$tipo = 2;
	}
	
	return array(
	 'prj'=> substr($raiz,strrpos($raiz, DIRECTORY_SEPARATOR)+1),
	 'raiz'=> $raiz,
	 'tipo'=> $tipo,
	);
}


/**
 * invoca a phpunit
 */
function phpunit($clase) {
	exec("phpunit  --skeleton-test $clase igep/tests/test_incprj.php", $out);
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
    	echo "--- generando clase '$clase' de gvHIDRA ---\n";
    	phpunit($clase);
        break;
        
    case 2:
    	echo "--- generando clase '$clase' de prj '$prj' ---\n";
   	 	phpunit($clase);
   	 	rename("igep/tests/{$clase}Test.php", "tests/{$clase}Test.php");
        break;

}
echo "\nBorrar el include al principio del fichero generado.";

?>
