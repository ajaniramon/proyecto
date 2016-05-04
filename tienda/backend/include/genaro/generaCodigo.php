<?php
error_reporting(E_ALL);
ini_set("display_errors", 'Off');

include_once 'include/htmlOutput.php';
include_once 'include/config.php';
include_once 'clases/DatabaseTable.php';

$conf = new config();

//Variable que contendra todas las ocurrencias no criticas que se han producido en el proceso de generacion
$warnings = array();

require($conf->getRutaSmarty().'Smarty.class.php');

$smarty = new Smarty();

// Configuración de los templates Smarty
$smarty->template_dir = $conf->getTemplateDir();
$smarty->compile_dir  = $conf->getCompileDir();

// delimitadores de codigo smarty
$smarty->left_delimiter = $conf->getLeftDelimiter();
$smarty->right_delimiter = $conf->getRightDelimiter();

// Captura de los argumentos que llegan del formulario
$argumentos = $_POST;
$nombreModulo = $argumentos['nombreModulo'];
$generacion = $argumentos['tipo'];
$conexion = $argumentos['conexion'];

// Se establece la conexión elegida en el formulario
$dsn = $conf->getDsnConfig($conexion, '../../gvHidraConfig.inc.xml');

$options = array(
  'debug'       => 2,
  'portability' => MDB2_PORTABILITY_ALL);

require_once 'MDB2.php';
include_once('include/MDB2Functions.php');
include_once('include/htmlOutput.php');


$mdb =& MDB2::connect($dsn, $options);
if (PEAR::isError($mdb))
{
	print_r("<b>ERROR:</b> Se ha producido un error al intentar establecer la conexi&oacute;n con el SGBD. Revise el fichero gvHidraConfig.inc.xml de la aplicaci&oacute;n. El texto del error es:\n<br/>");
	error_log($mdb->getDebugInfo(), 0);
	die($mdb->getUserInfo());
}

$dbname = $mdb->getDatabase();

$mdb->loadModule('Manager');

//Comprobamos que tenemos permisos de escritura en el compile dir
if(!is_writable($conf->getCompileDir())) {
	
	muestraMensaje('Debe tener permisos de escritura en el directorio de compilaci&oacute;n: '.$conf->getCompileDir(), 'error');
	$mdb->disconnect();
	return;
}

//Comprobamos que tenemos permisos de escritura en el directorio actions
if(!is_writable('../../actions')) {
	
	muestraMensaje('Debe tener permisos de escritura en el directorio de actions de la aplicaci&oacute;n.', 'error');
	$mdb->disconnect();
	return;
}

//Comprobamos que tenemos permisos de escritura en el directorio views
if(!is_writable('../../views')) {
	
	muestraMensaje('Debe tener permisos de escritura en el directorio de views de la aplicaci&oacute;n.', 'error');
	$mdb->disconnect();
	return;
}

//Comprobamos que tenemos permisos de escritura en el directorio plantillas
if(!is_writable('../../plantillas')) {
	
	muestraMensaje('Debe tener permisos de escritura en el directorio de plantillas de la aplicaci&oacute;n.', 'error');
	$mdb->disconnect();
	return;
}

//Comprobamos que tenemos permisos de escritura en el fichero include
if(!is_writable('../../include/include.php')) {
	
	muestraMensaje('Debe tener permisos de escritura en el fichero include/include.php de la aplicaci&oacute;n.', 'error');
	$mdb->disconnect();
	return;
}

//Comprobamos que tenemos permisos de escritura en el fichero mappings
if(!is_writable('../../include/mappings.php')) {
	
	muestraMensaje('Debe tener permisos de escritura en el fichero include/mappings.php de la aplicaci&oacute;n.', 'error');
	$mdb->disconnect();
	return;
}

//Comprobamos que tenemos permisos de escritura en el fichero mappings
if(!is_writable('../../include/menuModulos.xml')) {
	
	muestraMensaje('Debe tener permisos de escritura en el fichero include/menuModulos.xml de la aplicaci&oacute;n.', 'error');
	$mdb->disconnect();
	return;
}

// Para poder añadir opciones en el menú Administración de la pantalla principal
//$conf->generaMenuModulos('Administracion','Manual de Genaro');
switch($generacion) {

    case "TABULAR_REGISTRO":

	//Capturamos patron_gvhidra, nombre_clase y nombre_tabla
	$patron = strtoupper($argumentos['patron']);
	//Siempre sera con la primera letra en mayusculas
	$nombreClase = ucwords($argumentos['nombreClase']);
	$nombreTabla = $argumentos['nombreTabla'];
	// Valores personalizados de los campos de la tabla  
	$valoresCampos = obtenerValoresCampos($argumentos['valoresCampos']);
	
	/*Control de errores en entrada de datos*/
	
	//Modulo
	if(empty($nombreModulo)) {
		
		muestraMensaje('Debe rellenar el par&aacute;metro Nombre M&oacute;dulo', 'error');
		break;
	}
		
	if(!validarModulo($nombreModulo)) {
	
		muestraMensaje('El par&aacute;metro Nombre M&oacute;dulo no tiene un formato correcto', 'error');
		break;
	}
		
	//Clasemanejadora
	if(empty($nombreClase)) {
		
		muestraMensaje('Debe rellenar el par&aacute;metro Clase Manejadora', 'error');
		break;
	}
	
	if(!validarModulo($nombreClase)) {
	
		muestraMensaje('El par&aacute;metro Clase Manejadora no tiene un formato correcto', 'error');
		break;
	}

	//Tabla
	if(empty($nombreTabla)) {
		
		muestraMensaje('Debe seleccionar una tabla', 'error');
		break;
	}

	//Patron
	//Tabla
	if(empty($patron)) {
		
		muestraMensaje('Debe seleccionar un patr&oacute;n', 'error');
		break;
	}

	//Generamos el codigo del MenuModulos.xml
	$resMenu = $conf->generaMenuModulos($nombreModulo,$nombreClase);
	if($resMenu<0) {
		$warnings[] = "WARNING: No se ha generado la entrada de menu porque ya existe.";
	}
	

	// Obtenemos los templates predefinidos para el patrón
	$templates = $conf->getTemplates($patron);

	// Obtenemos los nombres de los campos de la tabla
	$nombresCamposTabla = $mdb->listTableFields($nombreTabla);

	if(PEAR::isError($nombresCamposTabla)) {
		$msj = '<b>ERROR:</b> Se ha producido un error al acceder a los campos de la tabla. Compruebe la conexi&oacute;n seleccionada y/o si tiene acceso a la tabla con los datos de conexi&oacute;n suministrados. ';
		if($dsn['phptype'] == 'pgsql') {
			$msj.= 'Compruebe que el search_path del dbUser '.$dsn['username'].' contiene el esquema al que pertenece la tabla '.$nombreTabla;
		}
		muestraMensaje($msj, 'error');
		error_log(utf8_decode(strip_tags(html_entity_decode($msj))." ".$nombresCamposTabla->getMessage()), 0);
		break;
	}
	
	
	$numeroCamposTabla = count($nombresCamposTabla);

	// Select necesaria para obtener los metadatos de los campos
	$query = <<<QUERY
				SELECT * 
				FROM $nombreTabla
QUERY;

	# Objeto DatabaseTable
	$dt = new DatabaseTable();
	$dt->set_raw_table_name($nombreTabla);
	$dt->set_raw_field_names($nombresCamposTabla);
	
	if($dsn['phptype']=='oci8' or $dsn['phptype']=='oracle') {
		
		$tableInfo = $dt->getTableFieldDefinitionOracle($mdb, $nombreTabla);
        $tipoCampoTabla->types = array();
		foreach($tableInfo as $field)
			$tipoCampoTabla->types[] = $field['type'];            
		$dt->set_db_field_types($tipoCampoTabla->types);
	}	
	else {
		$result =& $mdb->query($query, true, true);
		$dt->set_db_field_types($result->types);
	}

	// Obtengo las propiedades de los campos de la tabla que podemos aprovechar en la generaciï¿½n de tipo de datos gvHidra
	$propiedadesCampos = $dt->getPropiedadesCamposTabla($nombreTabla, $nombresCamposTabla, $primaryKey, $mdb);

        # Pasamos los datos a variables Smarty
	$smarty->assign('classname', $nombreClase);
	$smarty->assign('dsn', $conexion);
	$smarty->assign('tablename', $nombreTabla);
	$smarty->assign('fields', $nombresCamposTabla);
	$smarty->assign('customFields',$valoresCampos);
	$smarty->assign('types', $dt->get_gvhidra_field_types());
	$smarty->assign('lengths', $propiedadesCampos['length']);
	$smarty->assign('defaults', $propiedadesCampos['default']);
	$smarty->assign('notnulls', $propiedadesCampos['notnull']);
	$smarty->assign('primaryKey', $propiedadesCampos['pk']);
	$smarty->assign('titles', $dt->get_tpl_titles());
	$smarty->assign('nombreModulo', $nombreModulo);
	$smarty->assign('dt', $dt);
	
	/**/	
	/*
	echo "<pre>";
	print_r($nombresCamposTabla);
	print_r($dt->get_gvhidra_field_types());
	echo "</pre>";
	*/
	
	foreach($templates as $indice => $valor)
        {
                // Salida que genera el template
                $out = $smarty->fetch($templates[$indice]);

                // Generación del archivo
                $resultado = $conf->generaArchivo($nombreClase, $patron,  $templates[$indice], $nombreModulo, $out);
				
                if($resultado != 0)
                {                  	
	            	//TODO: tenemos que llamar de nuevo a la funcion porque no tenemos el nombre del archivo REFACTORIZAR
	            	$datosDestino = $conf->getDatosDestino($nombreClase, $patron,  $templates[$indice], $nombreModulo);	            	
	            	$archivo = $datosDestino['nombre_carpeta']."/".$datosDestino['nombre_fichero'];

                    //Se trata del warning de fichero existente
                    if($resultado==-2) {
                    	$warnings[] = "WARNING: El fichero ".$archivo." ya existe, por lo que no se ha generado.";
                    }
                    else {
                    	muestraMensaje("ERROR: Se ha intentado generar ".$archivo." a partir de ".$templates[$indice], 'error');
                    	die();
                    }
                }
        }

		if(count($warnings)==0) {

        	muestraMensaje("Patr&oacute;n simple ".$patron." generado con &eacute;xito en el m&oacute;dulo ".$nombreModulo, 'ok');
		}
		else {
			$textoMensaje = "Patr&oacute;n simple generado en el m&oacute;dulo ".$nombreModulo." con los siguientes problemas: <br/>";
			foreach($warnings as $m)
				$textoMensaje.=" -".$m."<br/>"; 	
			muestraMensaje($textoMensaje, 'ok');
		}
        muestraMensaje('Recuerde que debe volver a entrar en la aplicaci&oacute;n para ver la nueva ventana generada.<br/>', 'info');

        break;

case "MAESTRO_DETALLE":

	# Atrapamos patrón_gvhidra, nombre_clase_maestro, nombre_tabla_maestro, nombre_clase_detalle y nombre_tabla_detalle
	$patron = strtoupper($argumentos['patronMaestro']).strtoupper($argumentos['patronDetalle1']);
	//Siempre sera con la primera letra en mayusculas
	$nombreClaseMaestro = ucwords($argumentos['nombreClaseMaestro']);
	$nombreTablaMaestro = $argumentos['nombreTablaMaestro'];
	//Siempre sera con la primera letra en mayusculas
	$nombreClaseDetalle = ucwords($argumentos['nombreClaseDetalle1']);
	$nombreTablaDetalle = $argumentos['nombreTablaDetalle1'];
	// Valores personalizados de los campos de la tabla
	$valoresCampos = obtenerValoresCampos($argumentos['valoresCampos']);	

        /*Control de errores en entrada de datos*/

	//Modulo
	if(empty($nombreModulo)) {

		muestraMensaje('Debe rellenar el par&aacute;metro Nombre M&oacute;dulo', 'error');
		break;
	}

	//Clasemanejadora Maestro
	if(empty($nombreClaseMaestro)) {

		muestraMensaje('Debe rellenar el par&aacute;metro Clase Manejadora del Maestro', 'error');
		break;
	}

	//Tabla Maestro
	if(empty($nombreTablaMaestro)) {

		muestraMensaje('Debe seleccionar una tabla para el Maestro', 'error');
		break;
	}

	//Patron Maestro
	if(empty($argumentos['patronMaestro'])) {

		muestraMensaje('Debe seleccionar un patr&oacute;n para el Maestro', 'error');
		break;
	}

	//Claves Primarias Maestro
	if(empty($argumentos['primaryKeyMaestro'])) {

		muestraMensaje('Debe rellenar las claves primarias del Maestro', 'error');
		break;
	}

	//Clase Manejadora Detalle
	if(empty($nombreClaseDetalle)) {

		muestraMensaje('Debe rellenar el par&aacute;metro Clase Manejadora del Detalle', 'error');
		break;
	}

	//Tabla Detalle
	if(empty($nombreTablaDetalle)) {

		muestraMensaje('Debe seleccionar una tabla para el Detalle', 'error');
		break;
	}

        //Patron Detalle
        if(empty($argumentos['patronDetalle1'])) {

		muestraMensaje('Debe seleccionar un patr&oacute;n para el Detalle', 'error');
		break;
	}

	//Claves Ajenas Detalle
	if(empty($argumentos['foreignKeyDetalle1'])) {

		muestraMensaje('Debe rellenar las claves ajenas del Detalle', 'error');
		break;
	}

	//Generamos el codigo del MenuModulos.xml
	$resMenu = $conf->generaMenuModulos($nombreModulo,$nombreClaseMaestro);
	if($resMenu<0) {
		$warnings[] = "WARNING: No se ha generado la entrada de menu porque ya existe.";
	}

	// Obtenemos los templates predefinidos para el patrï¿½n
	$templates = $conf->getTemplates($patron);

	// Campos de la tabla maestro
	$nombresCamposTablaMaestro = $mdb->listTableFields($nombreTablaMaestro);

	if(PEAR::isError($nombresCamposTablaMaestro)) {
		$msj = '<b>ERROR:</b> Se ha producido un error al acceder a los campos de la tabla. Compruebe la conexi&oacute;n seleccionada y/o si tiene acceso a la tabla con los datos de conexi&oacute;n suministrados. ';
		if($dsn['phptype'] == 'pgsql') {
			$msj.= 'Compruebe que el search_path del dbUser '.$dsn['username'].' contiene el esquema al que pertenece la tabla '.$nombreTablaMaestro;
		}
		muestraMensaje($msj, 'error');
		break;
	}

	$numeroCamposTablaMaestro = count($nombresCamposTablaMaestro);

	// Campos de la tabla hijo
	$nombresCamposTablaDetalle = $mdb->listTableFields($nombreTablaDetalle);

	if(PEAR::isError($nombresCamposTablaDetalle)) {
		$msj = '<b>ERROR:</b> Se ha producido un error al acceder a los campos de la tabla. Compruebe la conexi&oacute;n seleccionada y/o si tiene acceso a la tabla con los datos de conexi&oacute;n suministrados. ';
		if($dsn['phptype'] == 'pgsql') {
			$msj.= 'Compruebe que el search_path del dbUser '.$dsn['username'].' contiene el esquema al que pertenece la tabla '.$nombreTablaDetalle;
		}
		muestraMensaje($msj, 'error');
		break;
	}
	$numeroCamposTablaDetalle = count($nombresCamposTablaDetalle);

	// Select necesaria para obtener los metadatos de la tabla Maestro
	$queryMaestro = <<<QUERY
				SELECT * 
				FROM $nombreTablaMaestro
QUERY;

	// Select necesaria para obtener los metadatos de la tabla Detalle
	$querDetalle = <<<QUERY
				SELECT * 
				FROM $nombreTablaDetalle
QUERY;

	

	

    // Objeto DatabaseTable para el Maestro
	$dt_maestro = new DatabaseTable();
	$dt_maestro->set_raw_table_name($nombreTablaMaestro);
	$dt_maestro->set_raw_field_names($nombresCamposTablaMaestro);
	if($dsn['phptype']=='oci8' or $dsn['phptype']=='oracle') {
		$tableInfo = $dt_maestro->getTableFieldDefinitionOracle($mdb, $nombreTablaMaestro);
		$tipoCampoTabla->types = array();
		foreach($tableInfo as $field)
			$tipoCampoTabla->types[] = $field['type'];
		$dt_maestro->set_db_field_types($tipoCampoTabla->types);		
	}
	else {
		$resultMaestro =& $mdb->query($queryMaestro, true, true);
		$dt_maestro->set_db_field_types($resultMaestro->types);
	}
	
	$primaryKeyMaestro = $argumentos['primaryKeyMaestro'];
    $dt_maestro->setClaves($primaryKeyMaestro);

    // Objeto DatabaseTable para el Detalle
	$dt_detalle = new DatabaseTable();
	$dt_detalle->set_raw_table_name($nombreTablaDetalle);
	$dt_detalle->set_raw_field_names($nombresCamposTablaDetalle);
	if($dsn['phptype']=='oci8' or $dsn['phptype']=='oracle') {
		$tableInfo = $dt_detalle->getTableFieldDefinitionOracle($mdb, $nombreTablaDetalle);
		$tipoCampoTabla->types = array();
		foreach($tableInfo as $field)
			$tipoCampoTabla->types[] = $field['type'];
		$dt_detalle->set_db_field_types($tipoCampoTabla->types);
	}
	else {
		$resultDetalle =& $mdb->query($querDetalle, true, true);
		$dt_detalle->set_db_field_types($resultDetalle->types);
	}
	
	$primaryKeyDetalle = "";
	$foreignKeyDetalle = $argumentos['foreignKeyDetalle1'];
    $dt_detalle->setClaves($foreignKeyDetalle);
	
	// Obtengo las propiedades de los campos de la tabla maestro que podemos aprovechar en la generación de tipo de datos gvHidra
	$propiedadesCamposMaestro = $dt_maestro->getPropiedadesCamposTabla($nombreTablaMaestro, $nombresCamposTablaMaestro, $primaryKeyMaestro, $mdb);

	// Obtengo las propiedades de los campos de la tabla detalle que podemos aprovechar en la generación de tipo de datos gvHidra
	$propiedadesCamposDetalle = $dt_detalle->getPropiedadesCamposTabla($nombreTablaDetalle, $nombresCamposTablaDetalle, $primaryKeyDetalle, $mdb);
	
	// Pasamos los datos a variables Smarty
	$smarty->assign('classname_maestro', $nombreClaseMaestro);
	$smarty->assign('classname_detalle', $nombreClaseDetalle);
	$smarty->assign('dsn', $conexion);
	$smarty->assign('tablename_maestro', $nombreTablaMaestro);
	$smarty->assign('tablename_detalle', $nombreTablaDetalle);
	$smarty->assign('fields_maestro', $nombresCamposTablaMaestro);
	$smarty->assign('fields_detalle', $nombresCamposTablaDetalle);
	$smarty->assign('customFields',$valoresCampos);	
	$smarty->assign('types_maestro', $dt_maestro->get_gvhidra_field_types());
	$smarty->assign('types_detalle', $dt_detalle->get_gvhidra_field_types());
	$smarty->assign('lengths_maestro', $propiedadesCamposMaestro['length']);
	$smarty->assign('lengths_detalle', $propiedadesCamposDetalle['length']);
	$smarty->assign('defaults_maestro', $propiedadesCamposMaestro['default']);
	$smarty->assign('defaults_detalle', $propiedadesCamposDetalle['default']);
	$smarty->assign('notnulls_maestro', $propiedadesCamposMaestro['notnull']);
	$smarty->assign('notnulls_detalle', $propiedadesCamposDetalle['notnull']);

	//BUG: de momento lo controlamos aqui.
	//Las claves multiples deben aparecer como array('campo1','campo2');
	$primaryKeyMaestroConComillas = $primaryKeyMaestro;
	$aux = explode(',',$primaryKeyMaestro);
    if(count($aux)>1) {
    	$primaryKeyMaestroConComillas = implode("','",$aux);
    }
	$smarty->assign('primaryKeyMaestro', $primaryKeyMaestroConComillas);
	$smarty->assign('primaryKeyMaestroArray', $aux);
	
	$smarty->assign('primaryKeyDetalle', $primaryKeyDetalle);
	$smarty->assign('primaryKey_maestro', $propiedadesCamposMaestro['pk']);
	$smarty->assign('foreignKey_detalle', $propiedadesCamposDetalle['pk']);

	//BUG: de momento lo controlamos aqui.
	//Las claves multiples deben aparecer como array('campo1','campo2');
	$primaryKeyDetalleConComillas = $foreignKeyDetalle;
	$aux = explode(',',$foreignKeyDetalle);
    if(count($aux)>1) {
    	$primaryKeyDetalleConComillas = implode("','",$aux);
    }
	$smarty->assign('foreignKeyDetalle', $primaryKeyDetalleConComillas);
	$smarty->assign('foreignKeyDetalleArray', $aux);
	$smarty->assign('titles_maestro', $dt_maestro->get_tpl_titles());
	$smarty->assign('titles_detalle', $dt_detalle->get_tpl_titles());
	$smarty->assign('nombreModulo', $nombreModulo);
	$smarty->assign('dt_maestro', $dt_maestro);
	$smarty->assign('dt_detalle', $dt_detalle);

	foreach($templates as $indice => $valor)
        {
                //Salida que genera el template
                $out = $smarty->fetch($templates[$indice]);

                $nombreClase = $nombreClaseMaestro;
                $esDetalle = strpos($valor, 'Detalle');

                if($esDetalle > 0)
                {
                        $nombreClase = $nombreClaseDetalle;
                }

                // Generación del archivo
                $resultado = $conf->generaArchivo($nombreClase, $patron,  $templates[$indice], $nombreModulo, $out);

                if($resultado != 0)
                {                  	
                    //TODO: tenemos que llamar de nuevo a la funcion porque no tenemos el nombre del archivo REFACTORIZAR
	            	$datosDestino = $conf->getDatosDestino($nombreClase, $patron,  $templates[$indice], $nombreModulo);	            	
	            	$archivo = $datosDestino['nombre_carpeta']."/".$datosDestino['nombre_fichero'];
                    
                    //Se trata del warning de fichero existente                    
                    if($resultado=-2) {
                    	$warnings[] = "WARNING: El fichero ".$archivo." ya existe, por lo que no se ha generado.";
                    }
                    else {
                    	muestraMensaje("ERROR: Se ha intentado generar ".$archivo." a partir de ".$templates[$indice], 'error');
                    	die();
                    }
                }
        }

		if(count($warnings)==0) {

   	        muestraMensaje("Maestro-Detalle ".$patron." generado con &eacute;xito en el m&oacute;dulo ".$nombreModulo, 'ok');        	
		}
		else {
			
			$textoMensaje = "Maestro-Detalle generado en el m&oacute;dulo ".$nombreModulo." con los siguientes problemas: <br/>";
			foreach($warnings as $m)
				$textoMensaje.=" -".$m."<br/>"; 	
			muestraMensaje($textoMensaje, 'ok');
		}
        muestraMensaje('Recuerde que debe volver a entrar en la aplicaci&oacute;n para ver la nueva ventana generada.<br/>', 'info');
        break;

case "MAESTRO_N_DETALLES":

	include('clases/maestroNDetalles.php');
	$maestroNDetalles = new maestroNDetalles;
	$maestroNDetalles->setNumeroDetalles($argumentos['numeroDeDetalles']);
        $smarty->assign('numeroDeDetalles', $maestroNDetalles->getNumeroDetalles());
	
	$patronMaestro = $argumentos['patronMaestro'];
	$nombreClaseMaestro = ucwords($argumentos['nombreClaseMaestro']);
	$nombreTablaMaestro = $argumentos['nombreTablaMaestro'];
	// Valores personalizados de los campos de la tabla
	$valoresCampos = obtenerValoresCampos($argumentos['valoresCampos']);	

        /*Control de errores en entrada de datos*/
        
	//Modulo
	if(empty($nombreModulo)) {

		muestraMensaje('Debe rellenar el par&aacute;metro Nombre M&oacute;dulo', 'error');
		break;
	}

	//Clasemanejadora Maestro
	if(empty($nombreClaseMaestro)) {

		muestraMensaje('Debe rellenar el par&aacute;metro Clase Manejadora del Maestro', 'error');
		break;
	}

	//Tabla Maestro
	if(empty($nombreTablaMaestro)) {

		muestraMensaje('Debe seleccionar una tabla para el Maestro', 'error');
		break;
	}

	//Patron Maestro
	if(empty($argumentos['patronMaestro'])) {

		muestraMensaje('Debe seleccionar un patr&oacute;n para el Maestro', 'error');
		break;
	}

	//Claves Primarias Maestro
	if(empty($argumentos['primaryKeyMaestro'])) {

		muestraMensaje('Debe rellenar las claves primarias del Maestro', 'error');
		break;
	}

        for($i=1; $i<=$maestroNDetalles->getNumeroDetalles(); $i++) {

            //Clase Manejadora Detalle
            if(empty($argumentos['nombreClaseDetalle'.$i])) {

                    muestraMensaje('Debe rellenar el par&aacute;metro Clase Manejadora del Detalle'.$i, 'error');
                    return;
            }

            //Tabla Detalle
            if(empty($argumentos['nombreTablaDetalle'.$i])) {

                    muestraMensaje('Debe seleccionar una tabla para el Detalle'.$i, 'error');
                    return;
            }

            //Patron Detalle
            if(empty($argumentos['patronDetalle'.$i])) {

                    muestraMensaje('Debe seleccionar un patr&oacute;n para el Detalle'.$i, 'error');
                    return;
            }

            //Claves Ajenas Detalle
            if(empty($argumentos['foreignKeyDetalle'.$i])) {

                    muestraMensaje('Debe rellenar las claves ajenas del Detalle'.$i, 'error');
                    return;
            }

        }

	//Generamos el codigo del MenuModulos.xml
	$resMenu = $conf->generaMenuModulos($nombreModulo,$nombreClaseMaestro);        
	if($resMenu<0) {
		$warnings[] = "WARNING: No se ha generado la entrada de menu porque ya existe.";
	}
        
    // Obtengo los templates para la parte de la generación del Maestro
	$templates['maestro'] = $conf->getTemplates($patronMaestro);
	
	// Campos de la tabla maestro
	$nombresCamposTablaMaestro = $mdb->listTableFields($nombreTablaMaestro);

        if(PEAR::isError($nombresCamposTablaMaestro)) {
		$msj = '<b>ERROR:</b> Se ha producido un error al acceder a los campos de la tabla. Compruebe la conexi&oacute;n seleccionada y/o si tiene acceso a la tabla con los datos de conexi&oacute;n suministrados. ';
		if($dsn['phptype'] == 'pgsql') {
			$msj.= 'Compruebe que el search_path del dbUser '.$dsn['username'].' contiene el esquema al que pertenece la tabla '.$nombreTablaMaestro;
		}
		muestraMensaje($msj, 'error');
		break;
	}
        
	$numeroCamposTablaMaestro = count($nombresCamposTablaMaestro);

        // Select necesaria para obtener los metadatos de los campos
	$queryMaestro = <<<QUERY
				SELECT * 
				FROM $nombreTablaMaestro
QUERY;

        // Objeto DatabaseTable para el Maestro
	$dt_maestro = new DatabaseTable();
	$dt_maestro->set_raw_table_name($nombreTablaMaestro);
	$dt_maestro->set_raw_field_names($nombresCamposTablaMaestro);
	if($dsn['phptype']=='oci8' or $dsn['phptype']=='oracle') {

		$tableInfo = $dt_maestro->getTableFieldDefinitionOracle($mdb, $nombreTablaMaestro);
		$tipoCampoTabla->types = array();
		foreach($tableInfo as $field)
			$tipoCampoTabla->types[] = $field['type'];
		$dt_maestro->set_db_field_types($tipoCampoTabla->types);	
	}
	else {
		$resultMaestro =& $mdb->query($queryMaestro, true, true);
		$dt_maestro->set_db_field_types($resultMaestro->types);
	}
	
	$primaryKeyMaestro = $argumentos['primaryKeyMaestro'];
    $dt_maestro->setClaves($primaryKeyMaestro);

    // Obtengo las propiedades de los campos de la tabla del maestro
	$propiedadesCamposMaestro = $dt_maestro->getPropiedadesCamposTabla($nombreTablaMaestro, $nombresCamposTablaMaestro, $primaryKeyMaestro, $mdb);

        //BUG: de momento lo controlamos aqui.
	//Las claves multiples deben aparecer como array('campo1','campo2');
	$primaryKeyMaestroConComillas = $primaryKeyMaestro;
	$aux = explode(',',$primaryKeyMaestro);
        if(count($aux)>1) {
            $primaryKeyMaestroConComillas = implode("','",$aux);
        }

        // Asigno los datos a variables Smarty
	$smarty->assign('dsn', $conexion);
	$smarty->assign('classname_maestro', $nombreClaseMaestro);
	$smarty->assign('tablename_maestro', $nombreTablaMaestro);
	$smarty->assign('fields_maestro', $nombresCamposTablaMaestro);
	$smarty->assign('customFields',$valoresCampos);	
	$smarty->assign('types_maestro', $dt_maestro->get_gvhidra_field_types());
	$smarty->assign('lengths_maestro', $propiedadesCamposMaestro['length']);
	$smarty->assign('defaults_maestro', $propiedadesCamposMaestro['default']);
	$smarty->assign('notnulls_maestro', $propiedadesCamposMaestro['notnull']);
	$smarty->assign('primaryKeyMaestro', $primaryKeyMaestroConComillas);
	$smarty->assign('primaryKey_maestro', $propiedadesCamposMaestro['pk']);
	$smarty->assign('titles_maestro', $dt_maestro->get_tpl_titles());
	$smarty->assign('nombreModulo', $nombreModulo);
	$smarty->assign('dt_maestro', $dt_maestro);
	
        // Obtengo el tipo de maestro para aplicarlo mas tarde en la generación de la plantilla
        $tipoMaestro = strpos(strtoupper($patronMaestro), 'LIS') ? 'lis' : 'edi';

        // Asigno el tipo de maestro a la variable Smarty
	$smarty->assign('tipoMaestro', $tipoMaestro);

        // Bucle que obtiene los datos de los detalles y las propiedades de sus campos
	for($i=1, $n=$maestroNDetalles->getNumeroDetalles(); $i<=$n; $i++)
	{
		$detalles['patron'][$i] = $argumentos['patronDetalle'.$i];
		$detalles['nombreClase'][$i] = ucwords($argumentos['nombreClaseDetalle'.$i]);
		$detalles['nombreTabla'][$i] = $argumentos['nombreTablaDetalle'.$i];
		$conf->templates = array();
		$templates['detalle'][$i] = $conf->getTemplates($detalles['patron'][$i]);
		$detalles['campos'][$i] = $mdb->listTableFields($detalles['nombreTabla'][$i]);

                if(PEAR::isError($detalles['campos'][$i])) {
			$msj = '<b>ERROR:</b> Se ha producido un error al acceder a los campos de la tabla. Compruebe la conexi&oacute;n seleccionada y/o si tiene acceso a la tabla con los datos de conexi&oacute;n suministrados. ';
			if($dsn['phptype'] == 'pgsql') {
				$msj.= 'Compruebe que el search_path del dbUser '.$dsn['username'].' contiene el esquema al que pertenece la tabla '.$detalles['nombreTabla'][$i];
			}
			muestraMensaje($msj, 'error');
			break;
		}
                
		$nombreTabla = $detalles['nombreTabla'][$i];
		$queryDetalle = <<<QUERY
				SELECT * 
				FROM $nombreTabla
QUERY;
		
		$dt_detalle[$i] = new DatabaseTable();
		$dt_detalle[$i]->set_raw_table_name($detalles['nombreTabla'][$i]);
		$dt_detalle[$i]->set_raw_field_names($detalles['campos'][$i]);
		
		if($dsn['phptype']=='oci8' or $dsn['phptype']=='oracle') {
			$tableInfo = $dt_detalle[$i]->getTableFieldDefinitionOracle($mdb, $detalles['nombreTabla'][$i]);
			$tipoCampoTabla->types = array();
			foreach($tableInfo as $field)
				$tipoCampoTabla->types[] = $field['type'];
			$dt_detalle[$i]->set_db_field_types($tipoCampoTabla->types);			
		}
		else {
			$detalles['result'][$i] =& $mdb->query($queryDetalle, true, true);
			$dt_detalle[$i]->set_db_field_types($detalles['result'][$i]->types);
		}
		
		$detalles['primaryKey'][$i] = "";
		$detalles['foreignKey'][$i] = $argumentos['foreignKeyDetalle'.$i];
                $dt_detalle[$i]->setClaves($detalles['foreignKey'][$i]);

                //BUG: de momento lo controlamos aqui.
                //Las claves multiples deben aparecer como array('campo1','campo2');
                $primaryKeyDetalleConComillas = $detalles['foreignKey'][$i];
                $aux = explode(',', $detalles['foreignKey'][$i]);
                if(count($aux)>1) {
                    $primaryKeyDetalleConComillas = implode("','",$aux);
                }
                $detalles['foreignKey'][$i] = $primaryKeyDetalleConComillas;
		
		$propiedades = $dt_detalle[$i]->getPropiedadesCamposTabla($detalles['nombreTabla'][$i], $detalles['campos'][$i], $detalles['primaryKey'][$i], $mdb);
		$detalles['propiedades']['length'][$i] = $propiedades['length'];
		$detalles['propiedades']['default'][$i] = $propiedades['default'];
		$detalles['propiedades']['notnull'][$i] = $propiedades['notnull'];
		$detalles['propiedades']['pk'][$i] = $propiedades['pk'];
		
		$detalles['tipos'][$i] = $dt_detalle[$i]->get_gvhidra_field_types();
		$detalles['titulos'][$i] = $dt_detalle[$i]->get_tpl_titles();

                $claves[$detalles['nombreClase'][$i]] = $detalles['foreignKey'][$i];
	}

	// Asignación de las claves de los detalles a la variable Smarty, sirve para relacionar las claves del Maestro con la de los Detalles en la Clase Manejadora Maestro
    $smarty->assign('array_claves_detalles', $claves);

	//BUG Maestro-nDetalles en el views
	$smarty->assign('array_classname_detalle', $detalles['nombreClase']);

	// Bucle de generación de la Clase Manejadora Maestro, y relleno de la plantilla, mappings, include y view de la parte Maestro
	foreach($templates['maestro'] as $indice => $template)
	{
		if(strpos($template, 'action'))
		{
                    $salida = $smarty->fetch($template);
			$resultado = $conf->generaArchivo($nombreClaseMaestro, $patronMaestro, $template, $nombreModulo, $salida);
			if($resultado != 0)
            {	

                //TODO: tenemos que llamar de nuevo a la funcion porque no tenemos el nombre del archivo REFACTORIZAR
            	$datosDestino = $conf->getDatosDestino($nombreClaseMaestro, $patronMaestro, $template, $nombreModulo);	            	
            	$archivo = $datosDestino['nombre_carpeta']."/".$datosDestino['nombre_fichero'];

            	//Se trata del warning de fichero existente
                if($resultado=-2) {
                	$warnings[] = "WARNING: El fichero ".$archivo." ya existe, por lo que no se ha generado.";
				}
                else {
                	muestraMensaje("ERROR: Se ha intentado generar ".$archivo." a partir de ".$templates[$indice], 'error');
                    die();
				}
            }
		}
		elseif(strpos($template, 'plantilla'))
		{
			$maestroNDetalles->setPlantilla($smarty->fetch($template));
		}
		elseif(strpos($template, 'mappings'))
		{
			$maestroNDetalles->setMappings($smarty->fetch($template));
		}
		elseif(strpos($template, 'include'))
		{
			$maestroNDetalles->setInclude($smarty->fetch($template));
		}
		elseif(strpos($template, 'view'))
		{
			$maestroNDetalles->setView($smarty->fetch($template));
		}
	}

        // Bucle de asignación de los datos de los detalles a variables Smarty
	$i = 1;
	while($maestroNDetalles->getNumeroDetalles() > 0)
	{
		$smarty->assign('classname_detalle', $detalles['nombreClase'][$i]);
		$smarty->assign('tablename_detalle', $detalles['nombreTabla'][$i]);
		$smarty->assign('fields_detalle', $detalles['campos'][$i]);
		$smarty->assign('types_detalle', $detalles['tipos'][$i]);
		$smarty->assign('lengths_detalle', $detalles['propiedades']['length'][$i]);
		$smarty->assign('defaults_detalle', $detalles['propiedades']['default'][$i]);
		$smarty->assign('notnulls_detalle', $detalles['propiedades']['notnull'][$i]);
		$smarty->assign('primaryKeyDetalle', $detalles['primaryKey'][$i]);
		$smarty->assign('foreignKey_detalle', $detalles['propiedades']['pk'][$i]);
		$smarty->assign('foreignKeyDetalle', $detalles['foreignKey'][$i]);
		$smarty->assign('titles_detalle', $detalles['titulos'][$i]);
		$smarty->assign('dt_detalle', $dt_detalle[$i]);
	
		$maestroNDetalles->setNumeroDetalles($maestroNDetalles->getNumeroDetalles()-1);

                // Bucle de generación de las Clases Manejadoras Detalles, relleno y generación de la plantilla, mappings, include y view finales
		foreach($templates['detalle'][$i] as $indice => $template)
		{

			if(strpos($template, 'action'))
			{
				$resultado = $conf->generaArchivo($detalles['nombreClase'][$i], $detalles['patron'][$i], $template, $nombreModulo, $smarty->fetch($template));

				if($resultado != 0)
				{	
	            	//TODO: tenemos que llamar de nuevo a la funcion porque no tenemos el nombre del archivo REFACTORIZAR
	            	$datosDestino = $conf->getDatosDestino($detalles['nombreClase'][$i], $detalles['patron'][$i], $template, $nombreModulo);	            	
	            	$archivo = $datosDestino['nombre_carpeta']."/".$datosDestino['nombre_fichero'];
	            	
	            	//Se trata del warning de fichero existente
	                if($resultado=-2) {
	                	$warnings[] = "WARNING: El fichero ".$archivo." ya existe, por lo que no se ha generado.";
					}
	                else {
	                	muestraMensaje("ERROR: Se ha intentado generar ".$archivo." a partir de ".$templates[$indice], 'error');
	                    die();
					}
				}
			}
			elseif(strpos($template, 'plantilla'))
			{
			
				$maestroNDetalles->rellenaPlantilla($smarty->fetch($template));
				
				if($maestroNDetalles->getNumeroDetalles() == 0)
				{
					$maestroNDetalles->rellenaPlantilla('{/if}
														 {/CWMarcoPanel}
														 {/CWVentana}');
					$resultado = $conf->generaArchivo($nombreClaseMaestro, $detalles['patron'][$i], $template, $nombreModulo, $maestroNDetalles->getPlantilla());

					if($resultado != 0)
		            {	

		                //TODO: tenemos que llamar de nuevo a la funcion porque no tenemos el nombre del archivo REFACTORIZAR
		            	$datosDestino = $conf->getDatosDestino($nombreClaseMaestro, $detalles['patron'][$i], $template, $nombreModulo);	            	
		            	$archivo = $datosDestino['nombre_carpeta']."/".$datosDestino['nombre_fichero'];

		            	//Se trata del warning de fichero existente
		                if($resultado=-2) {
		                	$warnings[] = "WARNING: El fichero ".$archivo." ya existe, por lo que no se ha generado.";
						}
		                else {
		                	muestraMensaje("ERROR: Se ha intentado generar ".$archivo." a partir de ".$templates[$indice], 'error');
		                    die();
						}
		            }
				}
			}
			elseif(strpos($template, 'mappings'))
			{
			
				$maestroNDetalles->rellenaMappings($smarty->fetch($template));
				
				if($maestroNDetalles->getNumeroDetalles() == 0)
				{
					
					$resultado = $conf->generaArchivo($nombreClaseMaestro, $detalles['patron'][$i], $template, $nombreModulo, $maestroNDetalles->getMappings());

					if($resultado != 0)
					{	

		                //TODO: tenemos que llamar de nuevo a la funcion porque no tenemos el nombre del archivo REFACTORIZAR
		            	$datosDestino = $conf->getDatosDestino($nombreClaseMaestro, $detalles['patron'][$i], $template, $nombreModulo);	            	
		            	$archivo = $datosDestino['nombre_carpeta']."/".$datosDestino['nombre_fichero'];
		
		            	//Se trata del warning de fichero existente
		                if($resultado=-2) {
		                	$warnings[] = "WARNING: El fichero ".$archivo." ya existe, por lo que no se ha generado.";
						}
		                else {
		                	muestraMensaje("ERROR: Se ha intentado generar ".$archivo." a partir de ".$templates[$indice], 'error');
		                    die();
						}
					}
				}
			}
			elseif(strpos($template, 'include'))
			{
				
				$maestroNDetalles->rellenaInclude($smarty->fetch($template));
				
				if($maestroNDetalles->getNumeroDetalles() == 0)
				{
					
					$resultado = $conf->generaArchivo($nombreClaseMaestro, $detalles['patron'][$i], $template, $nombreModulo, $maestroNDetalles->getInclude());

					if($resultado != 0)
					{	
		                //TODO: tenemos que llamar de nuevo a la funcion porque no tenemos el nombre del archivo REFACTORIZAR
		            	$datosDestino = $conf->getDatosDestino($nombreClaseMaestro, $detalles['patron'][$i], $template, $nombreModulo);	            	
		            	$archivo = $datosDestino['nombre_carpeta']."/".$datosDestino['nombre_fichero'];
		
		            	//Se trata del warning de fichero existente
		                if($resultado=-2) {
		                	$warnings[] = "WARNING: El fichero ".$archivo." ya existe, por lo que no se ha generado.";
						}
		                else {
		                	muestraMensaje("ERROR: Se ha intentado generar ".$archivo." a partir de ".$templates[$indice], 'error');
		                    die();
						}
					}
				}
			}
			elseif(strpos($template, 'view'))
			{
			
				$maestroNDetalles->rellenaView($smarty->fetch($template));
				
				if($maestroNDetalles->getNumeroDetalles() == 0)
				{
					$maestroNDetalles->rellenaView($smarty->fetch('patrones/'.$patronMaestro.'/endView.tpl'));
					$resultado = $conf->generaArchivo($nombreClaseMaestro, $detalles['patron'][$i], $template, $nombreModulo, $maestroNDetalles->getView());

					if($resultado != 0)
					{	
		                //TODO: tenemos que llamar de nuevo a la funcion porque no tenemos el nombre del archivo REFACTORIZAR
		            	$datosDestino = $conf->getDatosDestino($nombreClaseMaestro, $detalles['patron'][$i], $template, $nombreModulo);	            	
		            	$archivo = $datosDestino['nombre_carpeta']."/".$datosDestino['nombre_fichero'];
		
		            	//Se trata del warning de fichero existente
		                if($resultado=-2) {
		                	$warnings[] = "WARNING: El fichero ".$archivo." ya existe, por lo que no se ha generado.";
						}
		                else {
		                	muestraMensaje("ERROR: Se ha intentado generar ".$archivo." a partir de ".$templates[$indice], 'error');
		                    die();
						}
					}
				}
			}
		}
		$i++;
	}

		if(count($warnings)==0) {

        	muestraMensaje("Maestro-N-Detalles generado con &eacute;xito en el m&oacute;dulo ".$nombreModulo, 'ok');
		}
		else {
			$textoMensaje = "Maestro-N-Detalles generado en el m&oacute;dulo ".$nombreModulo." con los siguientes problemas: <br/>";
			foreach($warnings as $m)
				$textoMensaje.=" -".$m."<br/>"; 	
			muestraMensaje($textoMensaje, 'ok');
		}
        muestraMensaje('Recuerde que debe volver a entrar en la aplicaci&oacute;n para ver la nueva ventana generada.<br/>', 'info');

        break;
}

/**
 * metodo obtenerValoresCampos
 *
 * @access public
 * @param Array $datos
 *
 * Método que formatea el array con los valores personalizados de los campos
 * y los convierte a un nuevo array asociativo con los valores apropiados para las plantillas
 * 
 */
function obtenerValoresCampos($datos){
	
	
	$datos = explode("{",$datos);		
                
    $arrGen = array();
           
    foreach($datos as $campo){
        $arrAux = explode("==>",$campo);                       
        $arrGen[$arrAux[0]] =  campoValor($arrAux[1]);            
    }
    
    return $arrGen;
	
}

/**
 * metodo campoValor
 *
 * @access public
 * @param Array $arrCampo
 *
 * Obtiene los valores de los campos y los arregla en un array Asociativo
 *
 */
function campoValor($arrCampo){
	
	$aa = substr($arrCampo,1);

	$ab = explode(',', $aa);

	$ab1 = explode('=>',$ab[0]); //titVal
	$ab2 = explode('=>',$ab[1]); //tamVal
	$ab3 = explode('=>',$ab[2]); //componente
	$ab4 = explode('=>',$ab[3]); //reqVal
	$ab5 = explode('=>',$ab[4]); //calVal
	$ab6 = explode('=>',$ab[5]); //visibleVal	
	$ab7 = explode('=>',$ab[6]); //defVal

	//Pasamos a la codificacion de las plantillas.
	$titVal = utf8_decode($ab1[1]);

	$ac = array(
			$ab1[0]=>$titVal,
			$ab2[0]=>$ab2[1],
			$ab3[0]=>$ab3[1],
			$ab4[0]=>$ab4[1],
			$ab5[0]=>$ab5[1],
			$ab6[0]=>$ab6[1],
			$ab7[0]=>$ab7[1]			
			);

	/*echo '<pre><hr/>';
	print_r($ac);
	echo '</pre><hr/>';*/
	
	return($ac);

}

/**
 * metodo validarModulo
 *
 * @access public
 * @param string $datos
 *
 * Valida que el valor del modulo/clase tenga caracteres validos
 *
 */
function validarModulo($datos){

	$patron = "/^[a-zA-Z0-9ñÑ_]+$/";
	return preg_match($patron, $datos);
}

$mdb->disconnect();

?>