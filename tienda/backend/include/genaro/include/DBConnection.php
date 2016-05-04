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

$options = array(
  'debug'       => 2,
  'portability' => MDB2_PORTABILITY_ALL);


$mdb =& MDB2::connect($dsn, $options);
if (PEAR::isError($mdb))
{
	print_r("<b>ERROR:</b> Se ha producido un error al intentar establecer la conexi&oacute;n con el SGBD. Revise el fichero gvHidraConfig.inc.xml de la aplicaci&oacute;n. El texto del error es:\n<br/>");
	die(utf8_decode($mdb->getUserInfo()));
}

$dbname = $mdb->getDatabase();

$mdb->loadModule('Manager');

$tablas = arrayTablasBD($dbname, $mdb);

if(isset($_POST['conexion'])) {
    include('../panelGenaro.php');
}

?>