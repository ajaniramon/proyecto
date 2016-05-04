<?php
/* gvHIDRA. Herramienta Integral de Desarrollo Rápido de Aplicaciones de la Generalitat Valenciana
*
* Copyright (C) 2006 Generalitat Valenciana.
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,USA.
*
* For more information, contact:
*
*  Generalitat Valenciana
*  Conselleria d'Infraestructures i Transport
*  Av. Blasco Ibáñez, 50
*  46010 VALENCIA
*  SPAIN
*  +34 96386 24 83
*  gvhidra@gva.es
*  www.gvpontis.gva.es
*
*/

/**
 * IgepConexion es una clase que corresponde a un manejador de una conexion a una 
 * bases de datos determinada. Controla las operaciones correspondientes a la conexión
 * y a las transacciones.
 * 
 * Consta de las siguientes propiedades:
 * <ul>
 * <li><b>$obj_conexion</b> - El objeto de conexión con el que trabaja.</li>
 * <li><b>$obj_errorConexion</b> - Referencia al objeto de error global</li>
 * </ul>
 *
 * @version	$Id: IgepConexion.php,v 1.90 2011-04-18 12:48:44 afelixf Exp $
 * @author David: <pascual_dav@gva.es> 
 * @author Keka: <bermejo_mjo@gva.es>
 * @author Vero: <navarro_ver@gva.es>
 * @author Raquel: <borjabad_raq@gva.es> 
 * @author Toni: <felix_ant@gva.es>
 * @package gvHIDRA
 */


 class IgepConexion {
 	
 	/**
     * objeto conexion
     *
     * @var object obj_conexion
     */	     
	var $obj_conexion;
	
	 /**
     * variable de error
     *
     * @var object obj_errorConexion
     */		
	var $obj_errorConexion;

	 /**
     * variable de que contiene la descripción del dsn al que se conecta
     *
     * @var array v_dsn
     */		
	var $v_dsn;

	 /**
     * indica si se quiere utilizar conexion permanente. Solo disponible para SGBD Postgresql
     *
     * @var bool permanent
     */		
	private $persistent;
	/**
	 * Esta variable indica el driver de conexión a utilizar. Por defecto es mdb2
	 *
	 * @var string contiene la información del driver a utilizar. Por defecto es mdb2.
	 */
	private $driver = 'mdb2';
	
	/**
	 * Constructor. Recibe como parámetro un array con la definición
	 * del dsn.
	 *
	 * @access	public
	 * @param	array	$dsn
	 * @param	bool	$persistent
	 */	
	public function __construct($dsn, $persistent=false) {

		//Cogemos la referencia de la variable de error global
		global $g_error;
		$this->obj_errorConexion = & $g_error;
		//Realizamos la conexión
		$this->v_dsn = $dsn;
		$this->persistent = $persistent;		
		$this->obj_conexion = $this->conectar($dsn);
	}

	 
	//destructor
	public function __destruct() {
		$this->desconectar();
	}

	
	/**
	 * Realiza la conexión a la base de datos especificada
	 *
	 * @access	public
	 * @return	object
	 */	
	private function conectar($dsn) {						

		// truco para que el autoload cargue la clase, y asi esten definidas las constantes
		if (!class_exists('MDB2'))
			throw new gvHidraException('No existe la clase MDB2');
		IgepDB::preConexion($dsn);
		$options = array(
    		'portability' => MDB2_PORTABILITY_NONE,
		);

		//Para evitar problemas con el funcionamiento del MDB2, forzamos a que se utilice una 
		//nueva conexion siempre. La informacion esta en el bug Pear::MDB2 (Bug #17198)
		//Forzamos a que siempre utilice una nueva conexion
		if(!$this->isPersistent())
			$dsn['new_link'] = true;
		
		$res = MDB2::connect($dsn,$options);
		if (PEAR::isError($res))   {
			//Comprobamos que el Log esta activo para evitar entrar en un bucle
        	$conexion = ConfigFramework::getConfig()->getLogConnection();        
        	if(is_object($conexion) and is_object($conexion->obj_conexion)) {
				$this->obj_errorConexion->setError("IGEP-6",'IgepConexion.php',"conectar",$res);
        	} else error_log('Error en conexion: '.$res->userinfo);
		} else {
			IgepDB::postConexion($dsn,$res);
			//Finalmente marcamos el fetchMode por defecto
			$res->setFetchMode(MDB2_FETCHMODE_ASSOC);
		}		
    	return $res;
	}// Fin de conectar


	/**
	 * Realiza la desconexión a la base de datos a la que actualmente
	 * se está conectado.
	 *
	 * @access	public
	 */	
	private function desconectar() {	

		if (isset($this->obj_conexion) and is_object($this->obj_conexion) and method_exists($this->obj_conexion, 'rollback')) {
			$this->obj_conexion->rollback();
			//Si no es persistente desconectamos
			if(!$this->isPersistent()) {			
				$res = $this->obj_conexion->disconnect();
				if (PEAR::isError($res)) 
					$this->obj_errorConexion->setError("IGEP-7",'IgepConexion.php',"desconectar",$res);
			}
		}    	
	}// Fin de desconectar


	/**
	 * Devuelve el objeto conexión al que se está conectado.
	 *
	 * @access	public
	 * @return	object
	 */		
	public function getPEARConnection() {
		
		return $this->obj_conexion;
	}//Fin de getPEARConnection


	/**
	 * Devuelve el objeto conexión al que se está conectado.
	 *
	 * @access	public
	 * @return	object
	 * @deprecated 3.2 - 21/01/2010
	 */		
	public function getConexion() {

		// DEPRECATED: se fija en v. 3.2
		IgepDebug::setDebug(WARNING, 'DEPRECATED IgepConexion::getConexion. Usar IgepConexion::getPEARConnection '.
                                 '<br>En version 3.2 se borrará.');
		return $this->getPEARConnection();
	}//Fin de getConexion


	/**
	 * Devuelve el dsn de la conexión.
	 *
	 * @access	public
	 * @return	array
	 */		
	public function getDSN() {

		return $this->v_dsn;
	}//Fin de getDSN


	/**
	 * Indica si una conexion se ha establecido con el atributo persistente(reusable)
	 *
	 * @access	public
	 * @return	array
	 */		
	public function isPersistent() {

		return $this->persistent;
	}//Fin de isPersistent


	/**
	 * Empieza una transacción (BEGIN) en la conexión a la que 
	 * está apuntando.
	 *
	 * @access	public
	 */		
	public function empezarTransaccion() {
		//Debug:Indicamos que ejecutamos la consulta
		IgepDebug::setDebug(DEBUG_IGEP,'Empezamos transacción.');
		$res = IgepDB::empezarTransaccion($this->v_dsn,$this->obj_conexion);
		if (PEAR::isError($res)) 
    			$this->obj_errorConexion->setError("IGEP-8",'IgepConexion.php',"empezarTransaccion",$res);    	
	}//Fin de empezarTransaccion


	/**
	 * Finaliza una transacción (COMMIT o ROLLBACK) en la conexión a la que 
	 * está apuntando. Recibe un parámetro que indica si el procesado de las
	 * diferentes operaciones que se han realizado ha concluido satisfactoriamente
	 * o no. Dependiendo de ello se realizará el COMMIT o el ROLLBACK. Dicho 
	 * parámetro es $error
	 * <ul>
	 * <li>0. No ha habido ningún error en el proceso. Realizamos COMMIT</li>
	 * <li>1. Ha habido algún error durante el proceso. Realizamos ROLLBACK</li>
	 * </ul>
	 *
	 * @access	public
	 * @param	integer	$error
	 */		
	public function acabarTransaccion($error) {
		
		//Debug:Indicamos que ejecutamos la consulta
		if ($error)	
      		IgepDebug::setDebug(DEBUG_IGEP,'Acabamos transacción con ROLLBACK');
    	else
      		IgepDebug::setDebug(DEBUG_IGEP,'Acabamos transacción con COMMIT');	
		$res = IgepDB::acabarTransaccion($this->v_dsn,$this->obj_conexion,$error);
		if (PEAR::isError($res))
    			$this->obj_errorConexion->setError("IGEP-9",'IgepConexion.php',"acabarTransaccion",$res);    	
	}//Fin de acabarTransaccion
	
	
	/**
	* Método  encargado de construir las WHERE de las consultas. 
	* @access	private
	*/
	public function construirWhere($v_datos,$str_where) {		
		
		//Esta función construye una WHERE igualando los valores con los nombres de los campos.
		if (isset($v_datos)){	
			if($str_where!='')
				$inicio_condicion=' OR (';
			else
				$inicio_condicion=' (';							
			$str_condicion='';
			foreach($v_datos as $prop => $val) {					
				if ($str_condicion!='') { 
					$str_condicion.=' AND '; 
				}			
				$str_condicion.=$prop;
				if (gettype($val)=='string') {
					if($val!='') 
						$str_condicion.="='".$val."'";
					else
						$str_condicion.=' is null';
				}					
		  		else {
		  		 	if($val!='')
		  				$str_condicion.='='.$val;
		  			else
						$str_condicion.=' is null';		  		
		  		}					
			}//FIN foreach
			if(trim($str_condicion)!='')
				$str_where.=$inicio_condicion.$str_condicion.')';											
		}
		return $str_where;		
	} //Fin de construirWhere

	
	/**
	* Método  encargado de construir las WHERE de las consultas incluyendo las condiciones de busqueda que ha seleccionado
	* el programador a partir del parámetro tipoConsulta.
	* 
	* Este es un metodo alternativo a construirWhereBusqueda, donde solo se descartan caracteres especiales y mayusculas en tipo 3
	* Llamado desde gvHidraForm_DB->prepareDataSource (actualmente no se usa)
	* 
	* Dependiendo del valor de la variable realiza una construcción de la Where u otra. Los tipos son:
	*  	- 0. Se contruye igualando los campos a los valores.
    *  	- 1. Se construye con like y comodines para cada campo.
    *  	- 2. Por defecto, se contruye con like sólo si el usuario ha especificado comodines.      		
    *  	- 3. Se construye con like, sin distinguir mayúsculas ni caracteres especiales
	*/
	function construirWhereConLike($v_datos,$str_where,$tipoConsulta) {

		if($tipoConsulta==0)		
			return  $this->construirWhere($v_datos,$str_where);
		else
			//Construimos la Where
			if (isset($v_datos)){	
				// hay where previa cuando la consulta se hace sobre varios registros: se concatenan los criterios
				// en el mismo registro con and, y los de cada registro con or
				if($str_where!='')				
					$inicio_condicion =' OR ('; 
				else
					$inicio_condicion =' (';
				$str_condicion='';
				$dsn = $this->getDsn();
				foreach($v_datos as $prop => $val){
					if($val!=''){					
						if ($str_condicion!='')
							$str_condicion.=' AND '; 
						if($tipoConsulta==3)
							$expr = "lower(".IgepDB::toTextForVS($dsn, $prop).")";
						else
							$expr = $prop;
						if($tipoConsulta==2)
							//Son 3 iguales porque si colocamos 2 no distinguimos entre algo que esté en la posición 0 y el false								
							if((strpos($val,'%')===false) and (strpos($val,'_')===false))
								if (gettype($val)=='string') 					 
									$expr .= "='".$val."'";
								else
									$expr .= '='.$val;																	
							else
								$expr = IgepDB::toTextForVS($dsn, $expr)." LIKE '".$val."'";
						elseif ($tipoConsulta==3)
							$expr .= " LIKE ".IgepDB::unDiacritic($dsn,"lower('%".$val."%')");
						else // tipo 1
							$expr = IgepDB::toTextForVS($dsn, $expr)." LIKE '%".$val."%'";
						$str_condicion .= $expr;
					}
				}//FIN foreach
				if(trim($str_condicion)!='')
					$str_where.=$inicio_condicion.$str_condicion.')';				
			}				
		return $str_where;
	}//Fin de construirWhereConLike
	
	
	/**
	 * Para formar condiciones de busqueda.
	 * Actua como en las ventanas de selección (donde no se tienen en cuenta mayusculas
	 * ni caracteres especiales)
	 * 
	 * @param $col nombre columna
	 * @param $val valor a filtrar en la columna
	 * @param $tipo 0..2, igual que el queryMode
	 * @param $prepare boolean: si hay que transformar el valor a bd
	 */
	public function unDiacriticCondition($col, $val, $tipo=1, $prepare=true) {

		$dsn = $this->getDSN();
		$val = strtolower($val);
		if ($prepare)
			$this->prepararOperacion($val, TIPO_CARACTER);
		$expr1 = 'lower('.IgepDB::toTextForVS($dsn, $col).')';
		$expr1 = IgepDB::unDiacritic($dsn, $expr1);
		$val_nolike = IgepDB::unDiacritic($dsn,"'$val'");
		switch ($tipo) {
			case 0:
				$expr2 = ' = '.$val_nolike;					
				break;

			case 1:
				$expr2 = ' like '.IgepDB::unDiacritic($dsn,"'%$val%'");
				break;

			case 2:
				if (strpos($val,'%')===false and strpos($val,'_')===false)
					$expr2 = ' = '.$val_nolike;					
				else
					$expr2 = ' like '.$val_nolike;
				break;

			default:
				throw new gvHidraException('IgepConexion::unDiacriticCondition -> valor de tipo no soportado: '.$tipo);
		}
		return $expr1.$expr2;
	}


	/**
	 * Para formar condiciones de busqueda con tipos de datos diferentes a string
	 * 
	 * @param $col nombre columna
	 * @param $val valor a filtrar en la columna
	 * @param $tipo 0..2, igual que el queryMode
	 */
	public function normalCondition($col, $val, $tipo=1) {

		$dsn = $this->getDSN();
		$expr1 = IgepDB::toTextForVS($dsn, $col);
		$val_nolike = "'$val'";
		switch ($tipo) {
			case 0:
				$expr2 = ' = '.$val_nolike;					
				break;

			case 1:
				$expr2 = ' like '."'%$val%'";
				break;

			case 2:
				if (strpos($val,'%')===false and strpos($val,'_')===false)
					$expr2 = ' = '.$val_nolike;					
				else
					$expr2 = ' like '.$val_nolike;
				break;

			default:
				throw new gvHidraException('IgepConexion::normalCondition -> valor de tipo no soportado: '.$tipo);
		}
		return $expr1.$expr2;
	}


	/**
	* Método  encargado de construir las WHERE de las consultas incluyendo las condiciones de busqueda que ha seleccionado
	* el programador a partir del parámetro tipoConsulta.
	* 
	* Este es un metodo alternativo a construirWhereConLike, donde siempre se descartan caracteres especiales y mayusculas
	* Llamado desde gvHidraForm_DB->prepareDataSource
	* 
	* Dependiendo del valor de la variable realiza una construcción de la Where u otra. Los tipos son:
	*  	- 0. Se contruye igualando los campos a los valores.
    *  	- 1. Se construye con like y comodines para cada campo.
    *  	- 2. Por defecto, se contruye con like sólo si el usuario ha especificado comodines.      		
    *  	- 3. Coincide con tipo 1
	*/
	public function construirWhereBusqueda($v_datos,$undiacritic,$str_where,$tipoConsulta) {

		//Construimos la Where
		if (isset($v_datos)){	
			$str_condicion='';
			foreach ($v_datos as $prop => $val){
				if ($val!=''){					
					if ($str_condicion!='')
						$str_condicion.=' AND ';
					if($undiacritic[$prop]) 
						$str_condicion .= $this->unDiacriticCondition($prop, $val, $tipoConsulta, false);
					else
						$str_condicion .= $this->normalCondition($prop, $val, $tipoConsulta);
				}
			}//FIN foreach
			if (trim($str_condicion)!='') {
				// hay where previa cuando la consulta se hace sobre varios registros: se concatenan los criterios
				// en el mismo registro con and, y los de cada registro con or
				if ($str_where!='')				
					$inicio_condicion =' OR ('; 
				else
					$inicio_condicion =' (';
				$str_where.=$inicio_condicion.$str_condicion.')';
			}
		}
		return $str_where;
	}//Fin de construirWhereBusqueda


	/**
	 * Método encargado de, dadas una serie de cadenas, componerlas para crear una única
	 * cadena para la where de una SQL.
	 * @acces	private
	 * @param array $v_cadenas Array que contiene las diferentes cadenas que componen la WHERE  
	 * @return	string
	 */
	public function combinarWhere($v_cadenas) {
		$str_where = '';
		foreach($v_cadenas as $cadena){
			if(trim($cadena)!=''){
				if ($str_where!='')
					$str_where.= ') AND ';
				$str_where.='('.$cadena;
			}
		}
		if($str_where!='')
			$str_where=' WHERE '.$str_where.')';
		return $str_where;
	}
	
	
	/**
	* Método encargado de construir el limit para las consultas 
	* @access	private
	*/	
	public function construirLimite(& $str_where,$int_limiteConsulta=100) {

		$limite = '';
		if ($int_limiteConsulta != -1) {
			if(is_int($int_limiteConsulta))
	      		$limite = IgepDB::obtenerLimit($str_where, $this->v_dsn, $int_limiteConsulta);
		}
		return $limite;
	}//Fin de construirLimite
		

  /**
   * Dada una una consulta realiza la SELECT correspondiente.
   * Si se indica el parametro typeDesc cambiará los datos de origen al formato FW
   *
   * @access  public
   * @param string str_select cadena con la query SQL
   * @param array typeDesc descripcion de los campos para posibles transformaciones
   * @return  object
   */         
	public function consultar($str_select,$typeDesc=NULL) {

    	//Debug:Indicamos que ejecutamos la consulta
		IgepDebug::setDebug(DEBUG_IGEP,'IgepConexion: Ejecutamos consulta: '.$str_select);
		if(!$this->obj_errorConexion->hayError()){
			$resc = $this->obj_conexion->query($str_select);
			if (PEAR::isError($resc)){
				$this->obj_errorConexion->setError("IGEP-5",'IgepConexion.php',"consultar",$resc,$str_select);
				return -1;
			}
			$res = $resc->fetchAll();
			$this->transformResultSet($res, $typeDesc);
			return $res;
		}    
	}

	/**
	 * Dada una consulta realiza la SELECT correspondiente pero bloqueando las filas para modificacion
	 *
	 * @access  public
	 * @param string  $str_select
	 * @return  object
	 */         
	public function consultarForUpdate($str_select, $tipo=NULL) {

		IgepDebug::setDebug(DEBUG_IGEP,'IgepConexion: Ejecutamos consultaForUpdate: '.$str_select);
		$tran = $this->obj_conexion->inTransaction();
		// devuelve null si no hemos abierto la transaccion con mdb2
		if (!is_null($tran) and !$tran)
			throw new gvHidraNotInTransException('Para llamar a consultarForUpdate hay que estar en una transaccion');
		$str_select .= ' '.IgepDB::obtenerBloqueo($this->getDSN());
		$res = $this->consultar($str_select, $tipo);
		if ($res == -1) {
			// ver si error de bloqueado o timeout
			$cod = $this->obj_errorConexion->getDescErrorDB();
			if (IgepDB::isLocked($this->getDSN(), $cod[0])) {
				$msg = 'IgepConexion: no se puede bloquear los registros solicitados';
				IgepDebug::setDebug(DEBUG_IGEP, $msg);
				throw new gvHidraLockException($msg, 4, null, $this->obj_errorConexion->obj_dbError);
			}
		}
		return $res;
	}


	/**
	 * Ejecución de consulta preparada
	 * Si se usan placeholders con nombre, en los parametros pasaremos array asociativo.
	 * 
	 * @param boolean $dml_ddl: false para select, true para el resto
	 * @param array tipo: tipos para usar en transformaciones
	 * @return array varios: 
	 * 	si dml_ddl, devuelve numero de registros afectados
	 *  sino devuelve vector
	 * 	excepciones:
	 * 		1: error en prepare
	 * 		2: error en execute
	 * 		3: error en fetchAll
	 */
	public function preparedQuery($str_select, $dml_ddl, $params, $tipo=null, & $cur=null) {

		if (empty($cur)) {
			IgepDebug::setDebug(DEBUG_IGEP,"Preparando sql: $str_select con dml_ddl: ".($dml_ddl? 'true': 'false'));
			$db_pear = $this->obj_conexion;
			$in = null; // por defecto todo text
			if ($dml_ddl)
				$out = MDB2_PREPARE_MANIP;
			else
				$out = null;
			$cur = $db_pear->prepare($str_select, $in, $out);
			if (PEAR::isError($cur)) {
				$this->obj_errorConexion->setError("IGEP-5",'IgepConexion.php',"preparedQuery",$cur,$str_select);
				throw new gvHidraPrepareException('Error: '.$cur->getMessage()." - Preparando sql: $str_select", 1, null, $cur);
			}
		}
		IgepDebug::setDebug(DEBUG_IGEP,"Ejecutando sql: $str_select<br>* parametros: ".var_export($params,true));
		$res = $cur->execute($params);
		if (PEAR::isError($res)) {
			$this->obj_errorConexion->setError("IGEP-5",'IgepConexion.php',"preparedQuery",$res,$str_select.' y parámetros de entrada '.var_export($params,true));
			throw new gvHidraExecuteException('Error: '.$res->getMessage()." - Ejecutando sql ($str_select) con parámetros: ".var_export($params,true), 2, null, $res);
		}
		if ($dml_ddl)
			return $res;
		$vec = $res->fetchAll(MDB2_FETCHMODE_ASSOC);
		if (PEAR::isError($vec)) {
			$this->obj_errorConexion->setError("IGEP-5",'IgepConexion.php',"preparedQuery",$vec,$str_select);
			throw new gvHidraFetchException('Error: '.$vec->getMessage()." - Ejecutando recuperación de registros", 3, null, $vec);
		}
		$this->transformResultSet($vec, $tipo);
		return $vec;
	}


	/**
	 * Dada una  consulta realiza la SELECT correspondiente pero bloqueando las filas para modificacion
	 * y usando sentencias preparadas
	 * Devuelve vector
	 * 		excepciones:
	 * 		4: hay bloqueo
	 * 		resto: las propias de preparedQuery
	 */
	public function preparedQueryForUpdate($str_select, $params, $tipo=null, & $cur=null) {

		IgepDebug::setDebug(DEBUG_IGEP,'IgepConexion: Ejecutamos preparedQueryForUpdate: '.$str_select);
		$tran = $this->obj_conexion->inTransaction();
		// devuelve null si no hemos abierto la transaccion con mdb2
		if (!is_null($tran) and !$tran)
			throw new gvHidraNotInTransException('Para llamar a preparedQueryForUpdate hay que estar en una transaccion');
		$str_select .= ' '.IgepDB::obtenerBloqueo($this->getDSN());
		try {
			$res = $this->preparedQuery($str_select, false, $params, $tipo, $cur);
		} catch (gvHidraExecuteException $e) {
			// ver si error de bloqueado o timeout
			$cod = $this->obj_errorConexion->getDescErrorDB();
			if (IgepDB::isLocked($this->getDSN(), $cod[0]))
				throw new gvHidraLockException('IgepConexion: no se puede bloquear los registros solicitados', 4, null, $e->getSqlerror());
			throw $e;
		}
		return $res;
	}


	/**
	 * Transforma los datos obtenidos por consultar y preparedQuery
	 */
	function transformResultSet(& $res, $typeDesc) {
		if (empty($typeDesc))
			return;
   		if (array_key_exists('DATATYPES',$typeDesc)) {
			// convertimos los tipos a la estructura usada en this->v_descCamposPanel si es necesario
			$datatypes = array();
			foreach($typeDesc['DATATYPES'] as $clave=>$valor)
				if (is_array($valor) and array_key_exists('tipo',$valor))
					$datatypes[$clave] = $valor;
				else
					$datatypes[$clave] = array('tipo'=>$valor);
        } else
        	$datatypes = null;

		$this->transform_BD2FW($res,$datatypes);		
	}


	/**
	* Dada una una consulta realiza la SELECT correspondiente.
	*
	* @access  public
	* @param string  $str_select
	* @return  object
	*/         
	public function operar($str_operar) {
		//Debug:Indicamos que ejecutamos la operacion
		IgepDebug::setDebug(DEBUG_IGEP,'IgepConexion: Ejecutamos operación: '.$str_operar);
		if(!$this->obj_errorConexion->hayError()){                   
			$res = $this->obj_conexion->exec($str_operar);
			if (PEAR::isError($res)){                  
				$this->obj_errorConexion->setError("IGEP-11",'IgepConexion.php',"operar",$res,$str_operar);
				return -1;
			}                                 
			return $res;
		}    
	}

	
    /**
    * Convierte de FW a DB
    * Este método debe ser invocado por el usuario antes de realizar cualquier consulta/operacion a la BD
    * para garantizar la portabilidad.
    * 
    * @access   public
    * @param    any $a_parametros
    * @param    any $a_tipo
    * @return   none
    */   
    public function prepararOperacion(& $a_parametros, $a_tipo=TIPO_CARACTER) {

        $transformer = new IgepTransformer();
        $carfw = ConfigFramework::getNumericSeparatorsFW();
        $backslash = IgepDB::backSlashScape($this->getDsn());
        
        $carbd = IgepDB::caracteresNumericos($this->getDsn());
        $transformer->setDecimal($carfw['DECIMAL'],$carbd['DECIMAL'],$carfw['GROUP'],$carbd['GROUP']);
        //Cogemos la mascara a la que tenemos que transformar el timestamp
        $fechafw = ConfigFramework::getDateMaskFW();        
        $fechabd = IgepDB::mascaraFechas($this->getDsn());
        $transformer->setDate($fechafw, $fechabd);
       	$transformer->setCharacter("'","''");
       	$transformer->setCharacter("\\",$backslash);

		if (!is_array($a_parametros) and $a_parametros!='') {

			//si el tipo es nulo le ponemos TIPO_CARACTER 
			if(empty($a_tipo))
				$a_tipo=TIPO_CARACTER;

			// le doy estructura de vector para no repetir el codigo
			$vector = false;
			$a_parametros = array(array('col'=>$a_parametros,),);
			$a_tipo = array('col'=>array('tipo'=>$a_tipo,),);
		} else
			$vector = true;
		if (is_array($a_tipo))
            foreach ($a_parametros as $fila => $tupla)
                foreach ($tupla as $campo => $valor){
                    if(empty($a_parametros[$fila][$campo]))
                    	continue;
                	$tipo_efectivo = (empty($a_tipo[$campo]['tipo'])? TIPO_CARACTER: ($a_tipo[$campo]['tipo']==TIPO_ENTERO? TIPO_DECIMAL: $a_tipo[$campo]['tipo']));
					if ($tipo_efectivo == TIPO_DECIMAL)
						$tupla[$campo] = $transformer->expandExponent($tupla[$campo], $carfw['DECIMAL'], $carfw['GROUP']);
					if (($tipo_efectivo == TIPO_FECHA or $tipo_efectivo == TIPO_FECHAHORA) and is_object($tupla[$campo]))
						$a_parametros[$fila][$campo] = $tupla[$campo]->format($fechabd.($tipo_efectivo==TIPO_FECHAHORA? ' H:i:s':''));
					else
						$a_parametros[$fila][$campo] = $transformer->process($tipo_efectivo, $tupla[$campo]);
                }
        if (!$vector)
	    	$a_parametros = $a_parametros[0]['col'];
    }//Fin de prepararOperacion


    /**
    * Convierte datos de DB a FW
    * No valida los datos en origen, por lo que de momento si en origen tenemos una fecha con hora,
    * en el destino estaría sin hora.
    *
    * @access   public
    * @param    any $a_parametros
    * @param    any $a_tipo
    */
	public function transform_BD2FW(& $a_parametros, $a_tipo=TIPO_CARACTER) {
        $transformer = new IgepTransformer();
        $carbd = IgepDB::caracteresNumericos($this->getDsn());
        $carfw = ConfigFramework::getNumericSeparatorsFW();
        $transformer->setDecimal($carbd['DECIMAL'],$carfw['DECIMAL'],$carbd['GROUP'],$carfw['GROUP']);
        $fechabd = IgepDB::mascaraFechas($this->getDsn());
        $fechafw = ConfigFramework::getDateMaskFW();
        $transformer->setDate($fechabd,$fechafw);
		if (!is_array($a_parametros)) {
			// le doy estructura de vector para no repetir el codigo
			$vector = false;
			$a_parametros = array(array('col'=>$a_parametros,),);
			$a_tipo = array('col'=>array('tipo'=>$a_tipo,),);
		} else
			$vector = true;
		if (is_array($a_tipo))
            foreach ($a_parametros as $fila => $tupla)
                foreach ($tupla as $campo => $valor){
                	$tipo_efectivo = (empty($a_tipo[$campo]['tipo'])? TIPO_CARACTER: ($a_tipo[$campo]['tipo']==TIPO_ENTERO? TIPO_DECIMAL: $a_tipo[$campo]['tipo']));
					if (empty($a_parametros[$fila][$campo])) {
	                    if ($tipo_efectivo==TIPO_FECHA or $tipo_efectivo==TIPO_FECHAHORA)
							$a_parametros[$fila][$campo] = null;
						continue;
					}
                    $a_parametros[$fila][$campo] = $transformer->process($tipo_efectivo, $valor);
                    if ($tipo_efectivo==TIPO_FECHA or $tipo_efectivo==TIPO_FECHAHORA)
                    	$a_parametros[$fila][$campo] = new gvHidraTimestamp($a_parametros[$fila][$campo]);	
                }
        if (!$vector)
	    	$a_parametros = $a_parametros[0]['col'];
    }


    /**
    * Transforma un numero de capa negocio a capa datos
    * 
    * @access    public
    * @param    any $a_num
    * @return   string
    */   
    public function prepararNumero($a_num) {

    	$this->prepararOperacion($a_num, TIPO_DECIMAL);
    	return $a_num;
    }


    /**
    * Transforma una fecha de capa negocio a capa datos
    * 
    * @access    public
    * @param    any $a_fecha
    * @return   string
    */   
    public function prepararFecha($a_fecha) {

    	$this->prepararOperacion($a_fecha, TIPO_FECHAHORA);
    	return $a_fecha;
    }


    /**
    * Convierte de DB a User
    * Si estamos convirtiendo un numero a decimal podemos indicar el numero de decimales.
    * 
    * @access   public
    * @param    any $a_parametros
    * @param    any $a_tipo
    * @param    mixed $a_dsn
    * @param    number $a_decimales
    * @return   mixed
    */   
    static function transform_BD2User( $a_parametros, $a_tipo, $a_dsn, $a_decimales=2) {                
		if (!is_array($a_parametros) and $a_parametros!='' and $a_tipo!='') {
			// le doy estructura de vector para no repetir el codigo
			$vector = false;
			$a_parametros = array(array('col'=>$a_parametros,),);
			$a_tipo = array('col'=>array('tipo'=>$a_tipo, 'parteDecimal'=>$a_decimales),);
		} else
			$vector = true;		
		if (is_array($a_tipo)) {
			$transformer = new IgepTransformer();
			
			$carbd = IgepDB::caracteresNumericos($a_dsn);
			$carconf = ConfigFramework::getNumericSeparatorsUser();        
			$transformer->setDecimal($carbd['DECIMAL'],$carconf['DECIMAL'],$carbd['GROUP'],$carconf['GROUP']);
			
			$fechabd = IgepDB::mascaraFechas($a_dsn);
			$fechaconf = ConfigFramework::getDateMaskUser();        
			$transformer->setDate($fechabd,$fechaconf);        

			foreach($a_parametros as $fila => $tupla)
				foreach($a_tipo as $campo => $descTipo){
					if(isset($tupla[$campo])) {
						$tipo_efectivo = (empty($descTipo['tipo'])? TIPO_CARACTER: ($descTipo['tipo']==TIPO_ENTERO? TIPO_DECIMAL: $descTipo['tipo']));
						if ($tipo_efectivo == TIPO_DECIMAL)
							$tupla[$campo] = $transformer->expandExponent($tupla[$campo], $carbd['DECIMAL'], $carbd['GROUP']);
						if ($descTipo['tipo'] == TIPO_DECIMAL)
							$tupla[$campo] = $transformer->decimalPadDatos($tupla[$campo], $descTipo['parteDecimal'], $a_dsn);
						$a_parametros[$fila][$campo] = $transformer->process($tipo_efectivo, $tupla[$campo]);
					}
				}
		}
        if ($vector)
	        return $a_parametros;
	    else
	    	return $a_parametros[0]['col'];
    } // Fin de prepararPresentacion


	/**
	* Este método devuelve el valor de una secuencia programada en la base de datos 
	* para la conexion actual 
	*
	* @param  nombreSecuencia string  nombre de la secuencia en la BD
	* @return integer 
	*/
	public function calcularSecuenciaBD($nombreSecuencia) {
		$sql = IgepDB::obtenerSecuenciaBD($this->getDsn(),$nombreSecuencia);
		IgepDebug::setDebug(DEBUG_IGEP,'IgepConexion: Calculamos secuencia BD: '.$sql);
		$resc = $this->obj_conexion->query($sql);
		if (PEAR::isError($resc)){                  
			$this->obj_errorConexion->setError('IGEP-12','IgepConexion.php',"calcularSecuenciaBD",$resc);
			return -1;
		}
		else{
			$res = $resc->fetchAll();
			return $res[0]['nextval'];
		}    
	}
  
  
	/**
	* Este método calcula una secuencia compuesta por varios campos de la misma tabla.
	*
	* @param tabla string nombre de la tabla de la BD
	* @param campoSecuencia  string campo del que se quiere obtener la secuencia
	* @param camposDependientes array contiene el nombre de los campos de los cuales va a depender la secuencia y sus valores. Estructura [nombreBD] = valor 
	* @param valorInicial  integer Fija el valor inicial que devuelve calcularSecuencia en el caso de que no exístan tuplas en la tabla el valor por defecto es 1
	* @return integer
	*/
	public function calcularSecuencia($tabla,$campoSecuencia,$camposDependientes, $valorInicial=1) {
		$i=0;
		$where='';
		foreach($camposDependientes as $campo => $valor){
			if($where!='')
				$where.=' AND ';
			else
				$where = 'WHERE ';
			$where.=$campo."='".$valor."' ";
			++$i;
		}
		$sql = "SELECT max($campoSecuencia) as \"secuencia\" FROM $tabla $where";
		IgepDebug::setDebug(DEBUG_IGEP,'IgepConexion: Calculamos secuencia: '.$sql);   
		$resc = $this->obj_conexion->query($sql);
		if(PEAR::isError($resc)) {
			$this->obj_errorConexion->setError('IGEP-12','IgepConexion.php','calcularSecuencia',$resc);
			return -1;
		}
		else {
			$res = $resc->fetchAll(); 
			if (($res[0]['secuencia']=='')||($res[0]['secuencia']==null)||(!isset($res[0]['secuencia'])))
				return $valorInicial;
			else 
				return ($res[0]['secuencia'] +1);
		}
	}//Fin de funcion calcularSecuencia
	

	public function getDriver() {
	
		return $this->driver;
	}

}//Fin clase IgepConexion
?>