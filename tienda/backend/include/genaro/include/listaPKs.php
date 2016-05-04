<?php
require_once 'MDB2.php';
include_once 'htmlOutput.php';
include_once 'config.php';
include_once 'MDB2Functions.php';

$nombreTabla = $_POST['nombreTabla'];
$conexion = $_POST['conexion'];
$control = $_POST['control'];

$conf = new config();

// Se establece la conexión elegida en el formulario
$dsn = $conf->getDsnConfig($conexion, '../../../gvHidraConfig.inc.xml');

$options = array(
		'debug'       => 2,
		'portability' => MDB2_PORTABILITY_ALL);

$mdb =& MDB2::connect($dsn, $options);
$dbname = $mdb->getDatabase();

if($dsn['phptype']=='oracle' OR $dsn['phptype']=='oci8' OR $dsn['phptype']=='thin') {
	//Parche para que funcione con ORACLE
	//Vamos a cargar la consulta que obtiene la clave.
	
	$nombreTabla = strtoupper($nombreTabla);
	$mdb->setFetchMode(MDB2_FETCHMODE_ASSOC);

	$query = "SELECT COLUMN_NAME as \"clave\" FROM USER_CONS_COLUMNS WHERE constraint_name IN (select constraint_name from user_constraints WHERE CONSTRAINT_TYPE='P' AND TABLE_NAME ='$nombreTabla')";
	$result =& $mdb->query($query);
	
	if (PEAR::isError($result)) {
		return null;
	}	
	$pks = $result->fetchAll();
	$listaPKAux = null;
	
	foreach ($pks as $field) {
		$listaPKAux .= $field['clave'].',';					
		$flag = false; // Una vez recupero la información de la Primary Key dejo la flag a false de nuevo
	}
	$listaPK =substr($listaPKAux, 0, -1);
	
        echo "document.getElementById('$control').value='$listaPK'";
}
else {

	$mdb->loadModule('Manager');
	
	// Load the Reverse Module using MDB2's loadModule method
	$mdb->loadModule('Reverse', null, true);
	
	$tableInfo = $mdb->listTableConstraints($nombreTabla);
	
	if ($nombreTabla != ""){
		foreach ($tableInfo as $valorConstraint){
			$constraint[] = $mdb->getTableConstraintDefinition($nombreTabla, $valorConstraint);
		}
	
		$flag = false;
		$listaPKAux = '';
	
		foreach ($constraint as $indice=>$value)
		{
			foreach ($value as $indiceVal=>$campVal){
				if (($indiceVal == "primary") and ($campVal == 1)){
					$flag = true;
				}
				if (($indiceVal == "fields") and ($flag)) 
				{
					foreach ($value[$indiceVal] as $aa=> $ab)
					{
						$listaPKAux .= $aa.',';					
						$flag = false; // Una vez recupero la información de la Primary Key dejo la flag a false de nuevo
					}
					
					$listaPK =substr($listaPKAux, 0, -1);								
					echo "document.getElementById('$control').value='$listaPK'";			
					
				}
			}
		}
	}
}
?>