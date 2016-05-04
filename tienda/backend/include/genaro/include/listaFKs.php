<?php

require_once 'MDB2.php';
include_once 'htmlOutput.php';
include_once 'config.php';
include_once 'MDB2Functions.php';

if (isset($_POST['nombreTabla']))
	$nombreTabla = $_POST['nombreTabla'];
else
	$nombreTabla="";

$conexion = $_POST['conexion'];

$conf = new config();

// Se establece la conexión elegida en el formulario
$dsn = $conf->getDsnConfig($conexion, '../../../gvHidraConfig.inc.xml');

$options = array(
		'debug'       => 2,
		'portability' => MDB2_PORTABILITY_ALL);

$mdb =& MDB2::connect($dsn, $options);
$dbname = $mdb->getDatabase();
$mdb->loadModule('Manager');

// Load the Reverse Module using MDB2's loadModule method
$mdb->loadModule('Reverse', null, true);

$tableInfo = $mdb->listTableConstraints($nombreTabla);



if ($nombreTabla != "")
{	

	echo '<option value="">Seleccionar...</option>';
	
	foreach ($tableInfo as $valorConstraint){
		$constraint[] = $mdb->getTableConstraintDefinition($nombreTabla, $valorConstraint);
	}
	
	$flag = false;
	
	foreach ($constraint as $indice=>$value)
	{
		foreach ($value as $indiceVal=>$campVal){
			if (($indiceVal == "foreign") and ($campVal == 1)){
				$flag = true;
			}
			if (($indiceVal == "fields") and ($flag)) {
				foreach ($value[$indiceVal] as $aa=> $ab){
					echo "<option value='$aa'>";
					echo $aa;
					echo "</option>";
					$flag = false; // Una vez recupero la información de la Foreing Key dejo la flag a false de nuevo
				}
			}
		}
	}	
}
else
	echo '<option value="">Selecciona Tabla Detalle</option>';

?>