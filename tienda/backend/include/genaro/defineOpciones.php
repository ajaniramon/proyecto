	<?php
	
	require_once 'MDB2.php';
	include_once 'include/htmlOutput.php';
	include_once 'include/config.php';		
	include_once 'include/MDB2Functions.php';
			
	// Recogemos las variables pasadas por POST		
	$nombreTabla = $_POST['nombreTabla'];
	$conexion = $_POST['conexion'];		

	//inserta_log($nombresCamposTabla.' -- '.$conexion);

	$conf = new config();
	
	// Se establece la conexión elegida en el formulario
	$dsn = $conf->getDsnConfig($conexion, '../../gvHidraConfig.inc.xml');
	
	$options = array(
			'debug'       => 2,
			'portability' => MDB2_PORTABILITY_ALL);
		
	$mdb =& MDB2::connect($dsn, $options);
	

	
	$dbname = $mdb->getDatabase();
	$mdb->loadModule('Manager');
		
	// Obtenemos los nombres de los campos de la tabla
	$nombresCamposTabla = $mdb->listTableFields($nombreTabla);
	
	//Pasamos los nombres de los campos a mayusculas
	if($dsn['phptype']=='oci8' or $dsn['phptype']=='oracle') {
		$aux = array();
		foreach($nombresCamposTabla as $field)
			$aux[] = strtoupper($field);
		$nombresCamposTabla = $aux;
	}
	
	// Load the Reverse Module using MDB2's loadModule method
	$mdb->loadModule('Reverse', null, true);
	
	if($dsn['phptype']=='oci8' or $dsn['phptype']=='oracle') {
		include_once 'clases/DatabaseTable.php';
		$dt = new DatabaseTable();
		$nombreTabla = strtoupper($nombreTabla);
		$tableInfo = $dt->getTableFieldDefinitionOracle($mdb, $nombreTabla);
	}
	else 
		$tableInfo = $mdb->tableInfo($nombreTabla, NULL);
	
        $columnInfo = array();
        $columnLength = array();
        
	foreach($tableInfo as $column)
	{
		$columnInfo[] = $column['notnull'];
		$columnLength[] = $column['length']; // recuperamos el tamaño de cada campo
	}
		
	// Contador de elementos en la tabla
	$numeroCamposTabla = count($nombresCamposTabla);
	
	// Select necesaria para obtener los metadatos de los campos
	if($dsn['phptype']=='oci8' or $dsn['phptype']=='oracle') {
		$tipoCampoTabla->types = array();
		foreach($tableInfo as $field)
			$tipoCampoTabla->types[] = $field['type'];
	}
	else {
		$query = "SELECT * FROM $nombreTabla";
		$tipoCampoTabla =& $mdb->query($query, true, true);	
	}	

	if (PEAR::isError($tipoCampoTabla))
	{
		echo "<pre>";
		
		
		echo "<h2 style=\"text-align:center;\">Las tabla del SGBD seleccionado no se pueden parametrizar.</h2><br/>
			  <input type=\"button\" name=\"btAceptar\" value=\"VOLVER\" onmouseup=\"btnAceptarDiv()\"/>	";
		echo "</pre>";
		die();
	}	
	
	// Unimos los dos Arrays en uno
	$valoresCampoTabla = array($nombresCamposTabla, $tipoCampoTabla->types, $columnInfo, $columnLength);
			
	echo "<h2>Parametrizaci&oacute;n de los campos de la tabla ".strtoupper($nombreTabla)."</h2>";
	?>
	<script>document.cambiosEnParametrizar=false;</script>
	<form id="frmPersonalizaCampos" onchange="document.cambiosEnParametrizar=true;">		
	<table cellspacing="4" cellpadding="4" border="0" align="center">
	<tbody>
	<tr>	
		<th class="cabezaTabla">Campo</th>
		<th class="cabezaTabla">Tipo</th>
		<th class="cabezaTabla">Titulo</th>
		<th class="cabezaTabla">Size</th>				
		<!-- <th class="cabezaTabla">Mascara</th> -->
		<th class="cabezaTabla">Requer.</th>
		<th class="cabezaTabla">Calend.</th>
		<th class="cabezaTabla">Visible</th>
		<th class="cabezaTabla">Componente</th>
		<!-- <th class="cabezaTabla">Valor defecto</th> -->
	</tr>
	
	<?php 		
	/*
	echo "<pre>";
	print_r($valoresCampoTabla);
	echo "</pre>";
	*/
	$i=0;		
	while ($i < $numeroCamposTabla)
	{	
		echo '<tr>';
		echo '<td><label>'.$valoresCampoTabla[0][$i].'</label><input id="valorCampo'.$i.'" type="hidden" value ="'.$valoresCampoTabla[0][$i].'"/></td>';		
		echo '<td><label>'.$valoresCampoTabla[1][$i].'</label><input id="tipoCampo'.$i.'" type="hidden" value ="'.$valoresCampoTabla[1][$i].'"/></td>';
		echo '<td><input id="tituloCampo'.$i.'"  name="tituloCampo" type="text" size="10"/></td>';
		
		if (($valoresCampoTabla[1][$i] == 'date') || ($valoresCampoTabla[1][$i] == 'timestamp'))
		{
			echo '<td><input id="tamCampo'.$i.'"  name="tamCampo" type="text" size="3" disabled="true"/></td>';
		}
		else
		{
			echo '<td><input id="tamCampo'.$i.'"  name="tamCampo" type="text" size="3" value="'.$valoresCampoTabla[3][$i].'"/></td>';
		}
		echo '</td>';
		
		/* Quitamos la mascara 
		
		if ($valoresCampoTabla[1][$i] != 'text')
			echo '<td align="center"><select id="mascara'.$i.'" name="mascara" size="1" disabled="true">';
		else
			echo '<td align="center"><select id="mascara'.$i.'" name="mascara" size="1">';
			
		echo'<option value="" SELECTED>Mascara...</option>
			<option value="#### #### ## ##########">Cuenta Corriente</option>
			<option value="(+##)-#########">Telefono</option></td>';		
		*/
		if ($valoresCampoTabla[2][$i] == 1){		
			echo '<td align="center"><select id="requerido'.$i.'" name="requerido" size="1" disabled="true">';
			echo'<option value="1" SELECTED>Si</option>';
		}
		else{
			echo '<td align="center"><select id="requerido'.$i.'" name="requerido" size="1">';
			echo'<option value="0" SELECTED>No</option>
				 <option value="1">Si</option></td>';
		}				
	
		if (($valoresCampoTabla[1][$i] == 'date') || ($valoresCampoTabla[1][$i] == 'timestamp'))
			echo '<td align="center"><select id="calendario'.$i.'" name="calendario" size="1">';
		else			
			echo '<td align="center"><select id="calendario'.$i.'" name="calendario" size="1" disabled="true">';
		
		echo'<option value="0" SELECTED>No</option>
			 <option value="1">Si</option></td>';		
		
		echo '<td align="center"><select id="visible'.$i.'" name="visible" size="1">';
		echo'<option value="0">No</option>
		     <option value="1" SELECTED>Si</option></td>';
		
		if (($valoresCampoTabla[1][$i] == 'date') || ($valoresCampoTabla[1][$i] == 'timestamp') || ($valoresCampoTabla[2][$i] == 1))
		{
			echo '<td align="center"><select id="componente'.$i.'" name="componente" size="1" disabled="true">';
			echo'<option value="0" SELECTED>Campo Texto</option></td>';
		}
		else
		{
			echo '<td align="center"><select id="componente'.$i.'" name="componente" size="1">';
			echo'<option value="0" SELECTED>Campo Texto</option>
				 <option value="1">Area Texto</option>
				 <option value="2">CheckBox</option>
				 <option value="3">Radio</option>
				 <option value="4">Lista</option>
				</td>';
		}		

		if (($valoresCampoTabla[1][$i] == 'date') || ($valoresCampoTabla[1][$i] == 'timestamp'))
		{
			echo '<td align="center"><input type="hidden" id="valordefecto'.$i.'"  name="valordefecto" size="10" disabled="true"/>';
		}
		else
		{
			echo '<td align="center"><input type="hidden" id="valordefecto'.$i.'"  name="valordefecto" size="10"/>';
		}
		echo '</td>';		
		
		echo '</tr>';		
						
		$i++;		
	}										
	?>	
	
	</tbody>
	</table>
	<br/><br/>
	<input type="button" name="btAceptar" value="Aceptar" onmouseup="btnAceptarDiv()"/>		 	 	 
	<input type="button" name="btCancelar" value="Cancelar" onmouseup="btnCancelarDiv()"/>
	<input type="button" name="btLimpiar" value="Limpiar" onmouseup="btnLimpiarDiv()"/>
	</form>