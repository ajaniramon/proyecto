<?php

class config
{
	//Parametrizar rutas
	var $ruta_proyecto = "../../";
	var $ruta_smarty = "../../igep/smarty/";
	var $ruta_app_cato = "";

	//Nombre del DSN que se utilizará
	var $dsn = '';

	//Configuracion rutas templates
	var $template_dir = 'templates';
	var $compile_dir = 'compile';

	var $left_delimiter = '<<';
	var $right_delimiter = '>>';

	var $templates = array();

	//Getters
	function getRutaProyecto()
	{
		return $this->ruta_proyecto;
	}

	function getRutaSmarty()
	{
		return $this->ruta_smarty;
	}

	function getRutaAppCato()
	{
		return $this->ruta_app_cato;
	}

	function getDsn()
	{
		return $this->dsn;
	}

	function getPhpType()
	{
		return $this->php_type;
	}

	function getTemplateDir()
	{
		return $this->template_dir;
	}

	function getCompileDir()
	{
		return $this->compile_dir;
	}

	function getLeftDelimiter()
	{
		return $this->left_delimiter;
	}

	function getRightDelimiter()
	{
		return $this->right_delimiter;
	}

	function getConexiones($gvhidraconfig) 
	{
   		$datos = $this->parsearXML($gvhidraconfig);
        foreach($datos['GVHIDRACONFIG']['DSNZONE'] as $indice => $valor) 
        {
                $conexiones[$indice] = $valor;        
        }
        return $conexiones;
    }
	//Obtenemos los datos para la conexion a la base de datos
	function getDsnConfig($conexion, $gvhidraconfig)
	{
		$datos = $this->parsearXML($gvhidraconfig);
		$dsnid = $conexion;
		
		$conexionType = $datos['GVHIDRACONFIG']['DSNZONE'][$dsnid]['PHPTYPE'];
		
		if ($conexionType == "oci8" or $conexionType == "oracle-thin" or $conexionType == "thin") 
		{	
			$dsn = array(
				'phptype'  => $datos['GVHIDRACONFIG']['DSNZONE'][$dsnid]['PHPTYPE'],
				'username' => $datos['GVHIDRACONFIG']['DSNZONE'][$dsnid]['DBUSER'],
				'password' => $datos['GVHIDRACONFIG']['DSNZONE'][$dsnid]['DBPASSWORD'],
				'port' => $datos['GVHIDRACONFIG']['DSNZONE'][$dsnid]['DBPORT'],
				'hostspec' => $datos['GVHIDRACONFIG']['DSNZONE'][$dsnid]['DBHOST'],
				'service' => $datos['GVHIDRACONFIG']['DSNZONE'][$dsnid]['DBDATABASE']);
		}
		else
		{
			$dsn = array(
				'phptype'  => $datos['GVHIDRACONFIG']['DSNZONE'][$dsnid]['PHPTYPE'],
				'username' => $datos['GVHIDRACONFIG']['DSNZONE'][$dsnid]['DBUSER'],
				'password' => $datos['GVHIDRACONFIG']['DSNZONE'][$dsnid]['DBPASSWORD'],
				'port' => $datos['GVHIDRACONFIG']['DSNZONE'][$dsnid]['DBPORT'],
				'hostspec' => $datos['GVHIDRACONFIG']['DSNZONE'][$dsnid]['DBHOST'],
				'database' => $datos['GVHIDRACONFIG']['DSNZONE'][$dsnid]['DBDATABASE']);
		}	
		
		//Comprobamos el PHPTYPE correcto
		switch (strtolower(trim($dsn['phptype']))) {
			case 'postgres':
			case 'pgsql':
				$dsn['phptype'] = 'pgsql';
			break;
			case 'oci8':
			case 'oci':
			case 'oracle':
			case 'thin':
			case 'oracle-thin':
				$dsn['phptype'] = 'oci8';
			break;
			case 'mysqli':
			case 'mysql':
				$dsn['phptype'] = 'mysqli';  		
			break;
    		case 'sqlsrv':
    			$dsn['phptype'] = 'sqlsrv';
    		break;
			default:
				error_log('ERROR: El tipo de PHPTYPE no está soportado: '.$dsn['phptype'], 0);
				die('<b>ERROR:</b> El tipo de PHPTYPE no está soportado: '.$dsn['phptype']);
		}		
		return $dsn;
	}

	//Función que devuelve un array con la ubicación de los templates para el patrón
	function getTemplates($patron)
	{
		foreach (glob($this->getTemplateDir()."/patrones/".$patron."/*.tpl") as $filename)
		{
			$filename = preg_replace("/templates\//", "", $filename);
			array_push($this->templates, $filename);
		}
		
		return $this->templates;
	}

	//Función que devuelve el nombre del fichero y ruta destino a generar dependiendo de la plantilla
	function getDatosDestino($classname, $patron, $template, $nombreModulo)
	{
		$datos_destino = array('nombre_fichero' => '',
							   'nombre_carpeta' => '');

		$classname = ucwords($classname);
		$nombreModulo = $nombreModulo;

		$template = preg_replace("/patrones\/".$patron."\//", "", $template);
		$template = preg_replace("/.tpl/", "", $template);

		switch($template)
		{
			case "action":
				$datos_destino['nombre_fichero'] = $classname.".php";
				$datos_destino['nombre_carpeta'] = "actions/".$nombreModulo;
				break;
			case "actionMaestro":
				$datos_destino['nombre_fichero'] = $classname.".php";
				$datos_destino['nombre_carpeta'] = "actions/".$nombreModulo;
				break;
			case "actionDetalle":
				$datos_destino['nombre_fichero'] = $classname.".php";
				$datos_destino['nombre_carpeta'] = "actions/".$nombreModulo;
				break;
			case "plantilla":
				$datos_destino['nombre_fichero'] = 'p_'.$classname.".tpl";
				$datos_destino['nombre_carpeta'] = "plantillas/".$nombreModulo;
				break;
			case "view":
				$datos_destino['nombre_fichero'] = 'p_'.$classname.".php";
				$datos_destino['nombre_carpeta'] = "views/".$nombreModulo;
				break;
			case "mappings":
				$datos_destino['nombre_fichero'] = "mappings.php";
				$datos_destino['nombre_carpeta'] = "include";
				break;
			case "include":
				$datos_destino['nombre_fichero'] = "include.php";
				$datos_destino['nombre_carpeta'] = "include";
				break;
		}
		return $datos_destino;
	}
	/**
	* Función que genera el archivo final
	* @param	nombreClase	nombre
	* @param	patron	patron de interfaz
	* @param	template	plantilla del fichero a generar
	* @param	nombreModulo	modulo al que pertecene
	* @param	salida	¿?
	* 
	* @return int	0 Correcto.
	*				-1 Error.
	*				-2 Error ficheero ya existe.
	*/
	
	function generaArchivo($nombreClase, $patron, $template, $nombreModulo, $salida)
	{
		//Me traigo los datos necesarios para ubicar el archivo que voy a generar
		$datosDestino = $this->getDatosDestino($nombreClase, $patron, $template, $nombreModulo);
	
		//Devuelvo un nombre de fichero dependiendo de la plantilla
		$nombreFichero = $datosDestino['nombre_fichero'];
		//$nombre_fichero[0] = strtoupper($nombre_fichero[0]);
	
		//Devuelvo una carpeta destino dependiendo de la plantilla
		$nombreCarpeta = $datosDestino['nombre_carpeta']."/";
	
		$archivo = $this->getRutaProyecto().$nombreCarpeta.$nombreFichero;

		//Los ficheros mappings e include tienen un tratamiento especial
		if (strpos(strtoupper($template), "MAPPINGS")) {

	        $file = file_get_contents($archivo);
	        $file = explode("}", $file);
	        $salida = $file[0].$salida."}}?>";
	        echo $file[1];

        } elseif (strpos(strtoupper($template), "INCLUDE")) {

			$file = file_get_contents($archivo);
            $file = explode("?>", $file);
            $salida = $file[0].$salida."?>";
            echo $file[1];
		}
        else {
        	//Comprobamos que no existe el fichero
        	if (file_exists($archivo))
        		return -2;    	
		}
	
		//Creamos el archivo y escribimos la salida en el
		$fp = fopen($archivo, "w");
	
		if(!$fp)
		{
			mkdir($this->getRutaProyecto().$nombreCarpeta, 0777);
			$fp = fopen($archivo, "w");
		}
	
		$write = fputs($fp, $salida);
		fclose($fp);
		
		if($write)
		{
			chmod($archivo, 0777);
			chmod($this->getRutaProyecto().$nombreCarpeta, 0777);
			return 0;
		}
		else
			return -1;
	}

	//XML Parser
	function parsearXML($file) {

		$xml_parser = xml_parser_create();

		if (!($fp = fopen($file, "r")))
		{
			die("could not open XML input");
		}

		$data = fread($fp, filesize($file));
		fclose($fp);
		xml_parse_into_struct($xml_parser, $data, $vals, $index);
		xml_parser_free($xml_parser);

		$params = array();
		$level = array();

		foreach ($vals as $xml_elem)
		{
			if ($xml_elem['type'] == 'open')
			{
				if (array_key_exists('attributes',$xml_elem))
				{
					list($level[$xml_elem['level']],$extra) = array_values($xml_elem['attributes']);
					if($xml_elem['tag']=='DBDSN') {
					
						$phptype[$xml_elem['attributes']['ID']] = $xml_elem['attributes']['SGBD'];
					}
				}
				else
				{
					$level[$xml_elem['level']] = $xml_elem['tag'];
				}
			}

			if ($xml_elem['type'] == 'complete')
			{
				$start_level = 1;
				$php_stmt = '$params';
					
				while($start_level < $xml_elem['level'])
				{
					$php_stmt .= '[$level['.$start_level.']]';
					$start_level++;
				}
//				$php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
				if (array_key_exists('value', $xml_elem))
					$php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
				else
					$php_stmt .= '[$xml_elem[\'tag\']] = null;';
				eval($php_stmt);
			}
		}
		//parche para que se cargue el phptype
		foreach($params['GVHIDRACONFIG']['DSNZONE'] as $id => &$value) {
			//Parche provisional para que cuando tengamos un wsDSN no deje de funcionar		
			if(empty($phptype[$id]))
				unset($params['GVHIDRACONFIG']['DSNZONE'][$id]);
			else
				$value['PHPTYPE'] = $phptype[$id];
		}
		return $params;
	}
	public function generaMenuAdministracion($nombreModulo,$nombreClase) 
	{		
		//Generamos el codigo del MenuModulos.xml
		//Con DOM
		$dom = new DomDocument();
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->load('../../include/menuAdministracion.xml');
	
		$xpath = new DomXPath($dom);
		$result = $xpath->query('//menu/modulo[@titulo=\''.$nombreModulo."']");
		$modulo = $result->item(0);
		//Si no tiene el modulo lo creamos
		if(!isset($modulo)) {
			$modulo = $dom->createElement("modulo");
			$modulo->setAttribute('titulo',$nombreModulo);
			$modulo->setAttribute('imagen','menu/43.gif');
			$modulo->setAttribute('descripcion',$nombreModulo);
			$menu = $xpath->query('//menu');
			$menu->item(0)->appendChild($modulo);
		}
		//Si existe, comprobamos que la opcion no exista ya
		else {
			$result = $xpath->query('//menu/modulo[@titulo=\''.$nombreModulo."']/opcion[@titulo='".$nombreClase."']");
			$opcionExiste = $result->item(0);
			if(isset($opcionExiste)){
				return -1;
			}
				
		}
	
		//creamos opcion
		$opcion = $dom->createElement("opcion");
		$opcion->setAttribute('titulo',$nombreClase);
		$opcion->setAttribute('descripcion',$nombreClase);
		$opcion->setAttribute('url','include/genaro/doc/manual/html/index.html');
		$opcion->setAttribute('abrirVentana','true');
		
		$modulo->appendChild($opcion);
		//$dom->formatOutput = true;
		$dom->save('../../include/menuAdministracion.xml');
	
		return 0;
	}
		
	public function generaMenuModulos($nombreModulo,$nombreClase) {
		

		//Generamos el codigo del MenuModulos.xml
		//Con DOM
		$dom = new DomDocument();
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->load('../../include/menuModulos.xml');
	
		$xpath = new DomXPath($dom);
		$result = $xpath->query('//menu/modulo[@titulo=\''.$nombreModulo."']");
		$modulo = $result->item(0);
		//Si no tiene el modulo lo creamos
		if(!isset($modulo)) {
			$modulo = $dom->createElement("modulo");
			$modulo->setAttribute('titulo',$nombreModulo);
			$modulo->setAttribute('imagen','menu/43.gif');
			$modulo->setAttribute('descripcion',$nombreModulo);
			$menu = $xpath->query('//menu');
			$menu->item(0)->appendChild($modulo);
		}
		//Si existe, comprobamos que la opcion no exista ya
		else {
			$result = $xpath->query('//menu/modulo[@titulo=\''.$nombreModulo."']/opcion[@titulo='".$nombreClase."']");
			$opcionExiste = $result->item(0);
			if(isset($opcionExiste)){
				return -1;
			}
					
		}
				
		//creamos opcion
		$opcion = $dom->createElement("opcion");
		$opcion->setAttribute('titulo',$nombreClase);
		$opcion->setAttribute('descripcion','Mantenimiento de '.$nombreClase);
		$opcion->setAttribute('url','phrame.php?action='.$nombreClase.'__iniciarVentana');
		$modulo->appendChild($opcion);
		//$dom->formatOutput = true;
		$dom->save('../../include/menuModulos.xml');

		return 0;
	}
	
	//Recupeara la version para mostrarla por pantalla
	public function getVersion() {
       
        $importFile = file_get_contents('import.txt');
        if($importFile == false)
            return -1;
        $version = explode('genaro-', $importFile);
        $version = str_replace('_', '.', $version[1]);
       
        return $version;
    }

}
?>