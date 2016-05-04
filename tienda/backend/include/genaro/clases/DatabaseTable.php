<?php

# REQUIREMENT: this file is required for the mapping of database field
# types to java field types:
# import 'CrudDatabaseTableFieldTypes.inc';


# TODO might need this syntax for the include statement below:
# require_once(dirname(__FILE__) . "/file.php");

class DatabaseTable
{

        private $claves = array();
	
	# the name of the database table in the database
	private $raw_table_name;

	# the name of the database table as converted to a java classname
	# (camelcase rules for a class)
	private $camelcase_table_name;

	# the names of the database table fields as they appear in the database
	private $raw_field_names = array();

	# the name of the database table fields as they convert to camelcase names
	private $camelcase_field_names = array();

	# the database field types
	private $db_field_types = array();

	# Los tipos de campos se transforman a tipo de datos de gvHidra
	private $field_types = array();

	# Titulo para la tpl a partir del nombre de los campos de la tabla
	private $table_field_tpl_titles = array();

	# Longitud de los campos
	private $db_field_lengths = array();

        function getClaves() {
            return $this->claves;
        }

        function setClaves($claves) {
            $this->claves = $claves;
        }

        function esCampoClave($campo) {

            $claves = explode(',', $this->claves);

            foreach($claves as $indice => $valor) {

                $claves[$indice] = trim($valor);
            }

            foreach($claves as $indice => $valor) {

                if($valor == $campo) {
                    return true;
                }
            }

            return false;
        }


		private function esOracle($mdb) {
			
			$tipoConexion = $mdb->connected_dsn['phptype'];
			
			if($tipoConexion=='oci8' or $tipoConexion=='oracle')			
				return true;
			return false;
		}

		public function getTableFieldDefinitionOracle($mdb, $tabla) {

			$tabla = strtoupper($tabla);
			$mdb->setFetchMode(MDB2_FETCHMODE_ASSOC);
			$query = "SELECT TABLE_NAME,COLUMN_NAME,lower(DATA_TYPE) as \"type\",CASE WHEN (DATA_TYPE='NUMBER' AND DATA_SCALE>0) THEN to_number(DATA_PRECISION||'.'||DATA_SCALE) WHEN (DATA_TYPE='NUMBER' AND DATA_SCALE=0) THEN DATA_PRECISION WHEN DATA_TYPE='DATE' THEN 10 ELSE DATA_LENGTH END as \"length\",DATA_DEFAULT as \"default\",CASE WHEN NULLABLE='Y' THEN 0 ELSE 1 END as \"notnull\" FROM USER_TAB_COLUMNS  WHERE TABLE_NAME='$tabla'  ORDER BY COLUMN_ID";

			$result =& $mdb->query($query);
			if (PEAR::isError($result)) {
				return null;
			}
			$propiedades = $result->fetchAll();
			return $propiedades;
		}


        function getPropiedadesCamposTabla($nombreTabla, $campos, $pk, $mdb)
        {
                $propiedadesCampos = array();

                if (strpos($nombreTabla, ".")) {
                        $tabla = explode( ".", $nombreTabla);
                        $tabla = $tabla[1];
                } else {
                        $tabla = $nombreTabla;
                }
				


                foreach($campos as $indice => $valor)
                {
                		$propiedades = NULL;
                		if($this->esOracle($mdb)) {
                        	$propiedadesTabla = $this->getTableFieldDefinitionOracle($mdb, $tabla);
                        	//Cambiamos el indice a 0 para homogenizar con el resto de SGBDs
                        	$propiedades[0] = $propiedadesTabla[$indice]; 
                        }
                        else
                        	$propiedades = $mdb->getTableFieldDefinition($tabla, $valor);
                        
                        if (PEAR::isError($propiedades)) {
								muestraMensaje("ERROR: Fallo al obtener las propiedades del campo ".$valor." de la tabla ".$tabla, 'error');
                               	$propiedades = null;
                        } 
                        else {
                                //Comprobamos si es tipo fecha y le ponemos longitud 12 por defecto
                                if($propiedades[0]['type']=='date') 
                                	$propiedadesCampos['length'][$indice] = 12;
                                else {
                                	$propiedades[0]['length'] = str_replace('.', ',', $propiedades[0]['length']);
                                	$propiedadesCampos['length'][$indice] = $propiedades[0]['length'];
                                }
								//Comprobamos si es numerico que tenga la longitud
                                if($propiedades[0]['type']=='integer' or $propiedades[0]['type']=='float' or $propiedades[0]['type']=='numeric' or $propiedades[0]['type']=='number' or $propiedades[0]['type']=='decimal') {
                                	if(empty($propiedades[0]['length']))
                                		$propiedadesCampos['length'][$indice] = 6;
                                	//Si es un valor real, que tenga parte flotante definida.
                                	if($propiedades[0]['type']=='float' or $propiedades[0]['type']=='numeric' or $propiedades[0]['type']=='number' or $propiedades[0]['type']=='decimal') 
                                		if(strpos($propiedades[0]['length'],',')===false)
                                			$propiedadesCampos['length'][$indice].=',0';
                                }
                                
                                $propiedadesCampos['default'][$indice] = $propiedades[0]['default'];

                                if ($propiedades[0]['notnull'] == 1)
                                        $propiedadesCampos['notnull'][$indice] = 'true';
                                else
                                        $propiedadesCampos['notnull'][$indice] = 'false';
                                if ($this->esCampoClave($valor))
                                        $propiedadesCampos['pk'][$indice] = 'true';
                                else
                                        $propiedadesCampos['pk'][$indice] = 'false';
                        }
                }
            return $propiedadesCampos;
        }

	function get_raw_table_name()
	{
		return $this->raw_table_name;
	}

	function set_raw_table_name($name)
	{
		$this->raw_table_name = $name;
	}

	# set the raw database table field names array
	function set_raw_field_names($field_names)
	{
		$this->raw_field_names = $field_names;
	}

	# set the raw database table field types
	function set_db_field_types($field_types)
	{
		$this->db_field_types = $field_types;
	}
	
	# Función que transforma el nombre del campo en título para la tpl
	function get_tpl_titles()
	{
		foreach($this->raw_field_names as $indice => $valor)
		{
			$this->table_field_tpl_titles[$indice] = preg_replace('/_/', ' ', ucwords($this->raw_field_names[$indice]));
		}
		return $this->table_field_tpl_titles;
	}

	# returns an array of java field types that corresponds to the database
	# table field types. a database field of 'integer' becomes 'int',
	# a database field of 'text' becomes 'String', etc.
	#
	# this function requires the '$field_types_map' array to be set; it
	# provides the mapping from database field types to java field types.
	function get_gvhidra_field_types()
	{
		include 'CrudDatabaseTableFieldTypes.inc';

		$count = 0;
		foreach ($this->db_field_types as $field_type)
		{
			#echo "type = " . $field_type;
			# this should be a simple map lookup (as long as all the key/value
			# pairs are defined).
			$field_type = $field_types_map[$field_type];
			if (isset($field_type))
			{
				$this->field_types[$count] = $field_type;
			}
			else
			{
				# couldn't find a corresponding value in the map
				$this->field_types[$count] = 'UNKNOWN';
			}
			$count++;
		}
		return $this->field_types;
	}

	# convert a plural name to a singular name, as in turning
	# 'users' into 'user'. useful for converting database table
	# names into java class names.
	# TODO - also need to handle 'es' at the end of a table name.
	private function turn_plural_into_singular($string)
	{
		# get the last character from the string
		$l = strlen($string);
		$start = $l -1;
		$last_char = substr($string, $start, 1);

		# if the last char is not an 's', just return the original string
		if ($last_char != 's') return $string;

		# get the last two characters; if they are 'es', remove those and return
		$es_check = substr($string, $start-1, 2);
		if ($es_check == 'es')
		{
			return substr($string, 0, $l-2);
		}

		# otherwise, remove the last character ('s'), and return
		return substr($string, 0, $l-1);
	}

	# convert a database table field name to a java class name
	# (foo_bar_baz -> FooBarBaz)
	function get_camelcase_table_name()
	{
		//$table_name = $this->turn_plural_into_singular($this->raw_table_name);
		$table_name = $this->raw_table_name;
		$arr = explode('_', $table_name);
		$l = count($arr);
		for ($i=0; $i<$l; $i++)
		{
			$arr[$i] = ucwords($arr[$i]);
		}
		return implode($arr);
	}

	# TODO - this is currently returning "users", and needs to return "user"
	# convert a database table field name to a java object name,
	# i.e., a java instance variable name
	# (foo_bar_baz -> fooBarBaz)
	function get_java_object_name()
	{
		$table_name = $this->turn_plural_into_singular($this->raw_table_name);
		$arr = explode('_', $table_name);
		$l = count($arr);
		for ($i=0; $i<$l; $i++)
		{
			if ($i != 0)
			{
				$arr[$i] = ucwords($arr[$i]);
			}
		}
		return implode($arr);
	}

	function getPrimaryKey($nombre_tabla, $mdb)
	{
		/*//echo "getP";
		$mdb->loadModule('Reverse', null, true);
		$res = $mdb->getTableConstraintDefinition($nombre_tabla,'pk');
		var_dump($res);
		//echo "Clave primaria: ".key($res['fields']);
		return key($res['fields']);*/
		$query =   <<<QUERY
					 SELECT pg_attribute.attname as pk, format_type(pg_attribute.atttypid, pg_attribute.atttypmod)
					 FROM pg_index, pg_class, pg_attribute
					 WHERE pg_class.oid = '"$nombre_tabla"'::regclass 
					 AND indrelid = pg_class.oid 
					 AND pg_attribute.attrelid = pg_class.oid 
					 AND pg_attribute.attnum = any(pg_index.indkey) 
					 AND indisprimary
QUERY;

		$result =& $mdb->query($query);
		if (PEAR::isError($result)) {
			return "";
		}
		//var_dump($result);
		$result = $result->fetchAll();
		$primaryKey = $result[0][0];
		
		return $primaryKey;
	}
	
	function getForeignKey($nombre_tabla_detalle, $nombre_tabla_maestro, $mdb)
	{
		/*//echo "getF";
		$mdb->loadModule('Reverse', null, true);
		$res = $mdb->getTableConstraintDefinition($nombre_tabla,'fk');
		//echo "Clave foranea: ".$res['fields'];
		var_dump($res);
		return key($res['fields']*/
		$query = <<<QUERY
					SELECT distinct(attname)
					FROM pg_constraint, pg_class, pg_attribute 
					WHERE contype = 'f' 
					AND relname = '$nombre_tabla_detalle'
					AND conrelid = attrelid
					AND attnum = any(conkey)
					--AND conrelid = relfilenode
					AND confrelid=(SELECT indrelid 
									 FROM pg_index, pg_class, pg_attribute
									 WHERE pg_class.oid = '$nombre_tabla_maestro'::regclass 
									 AND indrelid = pg_class.oid 
									 AND pg_attribute.attrelid = pg_class.oid 
									 AND pg_attribute.attnum = any(pg_index.indkey) 
									 AND indisprimary)
QUERY;

		$result =& $mdb->query($query);
		$result = $result->fetchAll();
		$foreignKey = $result[0][0];
		
		return $foreignKey;
	}

	function get_camelcase_field_names()
	{
		$count = 0;
		foreach ($this->raw_field_names as $raw_field_name)
		{
			# create an array of words from the raw field name
			$words = $raw_field_name;
			$this->camelcase_field_names[$count] = $words;
			$count++;
		}
		return $this->camelcase_field_names;
	}

	# returns the table fields as a csv list; this is very useful for Dao
	# SQL INSERT statements.
	# example string for four fields: "id,foo,bar,baz"
	function get_fields_as_insert_stmt_csv_list()
	{
		$s = '';
		$count = 0;
		foreach ($this->raw_field_names as $raw_field_name)
		{
			$l = count($this->raw_field_names);
			if ($count < $l-1)
			{
				$s = $s . $raw_field_name . ',';
			}
			else
			{
				$s = $s . $raw_field_name;
			}
			$count++;
		}
		return $s;
	}

	# returns a csv-string of '?' corresponding to the database table fields;
	# very useful for Dao SQL INSERT statements.
	# example string for four fields: "?,?,?,?"
	function get_prep_stmt_insert_csv_string()
	{
		$s = '';
		$count = 0;
		foreach ($this->raw_field_names as $raw_field_name)
		{
			$l = count($this->raw_field_names);
			if ($count < $l-1)
			{
				$s = $s . '?,';
			}
			else
			{
				$s = $s . '?';
			}
			$count++;
		}
		return $s;
	}

	# returns a string like "id=?,foo=?,bar=?,baz=?", which is very useful for
	# SQL UPDATE statements, using the Java PreparedStatement syntax.
	function get_fields_as_update_stmt_csv_list()
	{
		$s = '';
		$count = 0;
		foreach ($this->raw_field_names as $raw_field_name)
		{
			$l = count($this->raw_field_names);
			if ($count < $l-1)
			{
				$s = $s . $raw_field_name . '=?,';
			}
			else
			{
				$s = $s . $raw_field_name . '=?';
			}
			$count++;
		}
		return $s;
	}


}

# TODO - move all of this to some unit tests

#$dt = new DatabaseTable();

# --- TEST THE TABLE NAME ---
# setting the raw table name should create camelcase_table_name
#$dt->set_raw_table_name('foo_bar_baz');
#print $dt->get_raw_table_name() . "\n";
#print $dt->get_camelcase_table_name() . "\n";

# --- TEST THE TABLE FIELD NAMES ---
#$t_fields = array('a_foo', 'b_bar');
#$dt->set_raw_field_names($t_fields);

#$fields = $dt->get_camelcase_field_names();
#foreach ($fields as $f)
#{
#  print "$f\n";
#}

# --- TEST THE TABLE FIELD TYPES ---
# setting the db field types should create the java field types
#$types = array('integer', 'text', 'poop');
#$dt->set_db_field_types($types);
#$j_types = $dt->get_java_field_types();
#foreach ($j_types as $jt)
#{
#  print "$jt\n";
#}

#print_r($dt);

?>
