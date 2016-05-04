<?php

require_once 'MDB2.php';
include_once 'htmlOutput.php';
include_once 'config.php';
include_once 'MDB2Functions.php';

$nombreTabla = $_POST['nombreTabla'];
$conexion = $_POST['conexion'];

/* parametro nuevo, check todas las tablas */
$allTables = $_POST['allTables'];

$conf = new config();

// Se establece la conexión elegida en el formulario
$dsn = $conf->getDsnConfig($conexion, '../../../gvHidraConfig.inc.xml');

$conexionType = $dsn['phptype'];

if ($conexionType == "oci8" or $conexionType == "oracle-thin" or $conexionType == "thin" or $allTables == 1)
{
	$mdb =& MDB2::connect($dsn, $options);
	$dbname = $mdb->getDatabase();
	$mdb->loadModule('Manager');
	
	$tablasBase = arrayTablasBD($dbname, $mdb);

	echo "<option value=''>Seleccionar...</option>";
	
	foreach ($tablasBase as $indice => $valor)
	{
		echo "<option value='$valor'>";
		echo $valor;
		echo "</option>";
	}
}
else
{  
	$options = array(
			'debug'       => 2,
			'portability' => MDB2_PORTABILITY_ALL);
	
	$mdb =& MDB2::connect($dsn, $options);
	$dbname = $mdb->getDatabase();
	$mdb->loadModule('Manager');
	
	$tablas = arrayTablasBD($dbname, $mdb);
	
	if ($nombreTabla != "")	
	{
		echo '<option value="">Seleccionar...</option>';
		
		foreach ($tablas as $nombreTablaFK)
		{
			$flag = false;
		
			if ($nombreTabla != $nombreTablaFK)
			{
				// Load the Reverse Module using MDB2's loadModule method
				$mdb->loadModule('Reverse', null, true);
				
				//$tableInfo = $mdb->listTableConstraints($nombreTabla);
				$tableInfo = $mdb->listTableConstraints($nombreTablaFK);
		
				foreach ($tableInfo as $valorConstraint)
					$constraint[] = $mdb->getTableConstraintDefinition($nombreTablaFK, $valorConstraint);
							
				foreach ($constraint as $indice=>$value)
				{
					foreach ($value as $indiceVal=>$campVal)
					{
						if (($indiceVal == "foreign") and ($campVal == 1))
						{
							$flag = true;
						}
			
						if (($indiceVal == "references") and ($flag))
						{
							foreach ($campVal as $indCamp=> $valCamp)
							{
								if ($indCamp == "table")
								{
									//echo "La tabla \"$nombreTablaFK\" tiene una FK sobre la tabla \"$valCamp\" <br/>";								
									if ($valCamp == $nombreTabla){
										$arrAuxFK[] = $nombreTablaFK;
									}									  
								}
							}
						}	
					}
				}
				$constraint = array();
			}
		}
	}
	else
		echo '<option value="">Selecciona Tabla Maestro</option>';
	 
	foreach ($arrAuxFK as $nomTablaFK)
	{
		echo "<option value=\"$nomTablaFK\">$nomTablaFK</option>";
	}
}

$arrAuxFK[] = array();

?>