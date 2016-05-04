<?php


require_once 'include/htmlOutput.php';
$numeroDeDetalles = $_POST['numeroDeDetalles'];

if($numeroDeDetalles <= 0) {
	muestraMensaje('El par&aacute;metro N&uacute;mero Detalles debe ser mayor que cero', 'warning');
} else {
	
	require 'include/config.php';
	$conf = new config();
	
	require_once 'include/MDB2Functions.php';
	require_once 'MDB2.php';
	
	# Obtengo los datos de conexión a BD del gvHidraConfig.xml
	$dsn = $conf->getDsnConfig($_POST['conexion'], '../../gvHidraConfig.inc.xml');
	
	$options = array(
	  'debug'       => 2,
	  'portability' => MDB2_PORTABILITY_ALL);
	
	
	$mdb =& MDB2::connect($dsn, $options);
	if (PEAR::isError($mdb))
	{
		print_r("<b>ERROR:</b> Se ha producido un error al intentar establecer la conexi&oacute;n con el SGBD. Revise el fichero gvHidraConfig.inc.xml de la aplicaci&oacute;n. El texto del error es:\n<br/>");
		die($mdb->getUserInfo());
	}
	
	$dbname = $mdb->getDatabase();
	
	$mdb->loadModule('Manager');
	
	$tablas = arrayTablasBD($dbname, $mdb);
		
	for ($i=1,$n=$numeroDeDetalles; $i<=$n; $i++)
	{
		echo "<fieldset>";
		echo "<legend>Detalle ".$i."</legend>";
		?>
		<table cellspacing='4' cellpadding='4' border='0' align='center'>
			<tr>
				<?php echo "<td><label>Clase Manejadora:</label></td><td><input id=\"nombreClaseDetalle".$i."\" name=\"nombreClaseDetalle".$i."\" type=\"text\"/></td>";
				echo "<td><label>Seleccionar Tabla:</label></td><td><select id=\"nombreTablaDetalle".$i."\" name=\"nombreTablaDetalle".$i."\" size=\"1\"
								onchange=\"listaFKs(".$i.");\">";
					echo "<option value=\"\">Selecciona Tabla Maestro</option>";
					/*foreach ($tablas as $indice => $valor)
					{
						echo "<option value='$valor'>";
						echo $valor;
						echo "</option>";
					}*/
				echo "</select><input id=\"ck_nombreTablaDetalle".$i."\" name=\"ck_nombreTablaDetalle".$i."\" onchange=\"checkNombreTablaDetalle(this);\" type=\"checkbox\" title=\"Mostar todos\" /></td>";
				?>
			</tr>
			<tr>
				<?php
				//echo "<td><label>Clave Ajena:</label></td><td><input id=\"foreignKeyDetalle".$i."\" name=\"foreignKeyDetalle".$i."\" type=\"text\"/></td>";
				echo "<td><label>Clave Ajena:</label></td><td><select id=\"foreignKeyDetalle".$i."\" name=\"foreignKeyDetalle".$i."\" size=\"1\">"; 								
				echo "<option value=\"\">Selecciona Tabla Detalle</option>";
				echo "</select>";
				echo "<input id=\"ck_foreignKeyDetalle".$i."\" name=\"ck_foreignKeyDetalle".$i."\" onchange=\"checkForeignKeyDetalle(this);\" type=\"checkbox\" title=\"Escribir FK\"/></td>";
				echo "<td><label>Seleccionar Patr&oacute;n:</label></td><td><select id=\"patronDetalle".$i."\" name=\"patronDetalle".$i."\" size=\"3\">";
			  	echo "<option value=\"M1LIS\">(LIS)</option>";
			  	echo "<option value=\"M1EDI\">(EDI)</option>";
			  	echo "<option value=\"M1LIS-EDI\">(LIS-EDI)</option>";
			  	echo "</select></td>";
			  	?>
			</tr>
			<tr>
	
		  	</tr>
		  	</table>
		<?php echo "</fieldset>";
	}
}
?>