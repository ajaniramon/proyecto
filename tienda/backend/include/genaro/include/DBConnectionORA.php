<?php
//set_include_path(get_include_path() . PATH_SEPARATOR . '.');
require 'config.php';
$conf = new config();

if(!isset($_POST['conexion'])) {
    $gvhidraconfig = '../../gvHidraConfig.inc.xml';
} else {
    $gvhidraconfig = '../../../gvHidraConfig.inc.xml';
}

$conexiones = $conf->getConexiones($gvhidraconfig);

require_once 'MDB2Functions.php';
require_once 'MDB2.php';

if(!isset($_POST['conexion'])) {
    $conexion = key($conexiones);
} else {
    $conexion = $_POST['conexion'];
}
# Obtengo los datos de conexión a BD del gvHidraConfig.xml
$dsn = $conf->getDsnConfig($conexion, $gvhidraconfig);

if($dsn['phptype']=='oci8'  ||
	$dsn['phptype']=='thin' ||	
	$dsn['phptype']=='oracle-thin'){
	echo 'errorSGBD()';
}
else{
	echo 'okSGBD()';
}

?>