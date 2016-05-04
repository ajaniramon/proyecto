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
* Incluimos la clase de Igep que nos permite realizar las conexiones a la BD
*/
//include_once "IgepConexion.php";
/**
* Incluimos la clase de Igep que nos permite manejar las tablas de la BD
*/
//include_once "IgepPersistencia.php";

/**
 * gvHidraForm_DB extension gvHidra que permite mantener un FORM mediante un CRUD sobre una base de datos
 * relacional. Debe indicarse el DSN de conexion a la misma y la correlacion campos de pantalla con campos
 * de la bd (matching).
 *
 * @version $Id: gvHidraForm_DB.php,v 1.34 2011-04-18 12:48:44 afelixf Exp $ 
 * @author Toni: <felix_ant@gva.es>
 * @package gvHIDRA
 */ 
class gvHidraForm_DB extends gvHidraForm
{
	
	/**
	* objeto conexion
	* @access private
	* @var object obj_conexion
	*/	
	var $obj_conexion;
	
	/**
	* vector de nombres de tablas
	* @access private
	* @var array v_nombreTablas
	*/		
	var $v_nombreTablas;
			
	/**
	* vector de manejadores de tablas
	* @access private
	* @var array v_tablas
	*/		
	var $v_tablas;	
			
	/**
	* string que contiene la SELECT que define el contenido del panel en su pestaña principal (la que se mostrará tras lanzar el panel de busqueda). Esta select debe ser inicializada por el programador en la clase contenida en actions
	*	  
	* @var string str_select
	*/		
	var $str_select;
	
	/**
	* string que contiene el ORDER BY de la SELECT
	*
	* @var string str_orderBy
	*/			
	var $str_orderBy;

	/**
	* Variable que controla el match-in entre las tpl y la bd
	* @access private
	* @var array  $matching
	*/		
	var $matching;	
				
	/**
	* Esta variable contiene la WHERE que se aplicará a la Select de búsqueda	 
	* @var string $str_where
	*/			
	var $str_where;
	  
	/**
	* Esta variable contiene la SELECT que se aplicará al segundo panel de edición.	 
	* @var string $str_selectEditar
	*/			
	var $str_selectEditar;
	  
	/**
	* Esta variable contiene la WHERE que se aplicará al segundo panel de edición.	 
	* @var string $str_whereEditar
	*/			
	var $str_whereEditar;
	
	/**
	* Esta variable contiene el ORDER BY que se aplicará al segundo panel de edición.	 
	* @var string $str_orderByEditar
	*/				
	var $str_orderByEditar;

	/**
	* String donde se almacena el filtro de la Where de Busqueda. Es importante porque este filtro se mantiene hasta que se vuelva a realizar otra busqueda
	* @access private	 
	* @var string $str_whereFiltro
	*/				
	var $str_whereFiltro;
	
	/**
	* String donde se almacena el filtro de la Where de la selección (3 pestañas en el panel lis). Es importante porque este filtro se mantiene hasta que se vuelva a realizar otra edicion
	* @access private	 
	* @var string $str_whereFiltroEdicion
	*/				
	var $str_whereFiltroEdicion;  
	
	/**
	* Variable donde marcamos el límite de los registros a mostrar
	* @access private
	* @var integer	int_limiteConsulta
	*/	
	var $int_limiteConsulta = 100;
		
	/**
	* Variable que almacena las constantes que se van a incluir en la consulta.
	* @access private
	* @var string str_SelectConstantes
	*/
	var $str_SelectConstantes;	
	
	/**
	* Array que almacena los campos claves de la searchquery.
	* @access private
	* @var array v_pkForSearchQuery
	*/
	private $v_pkForSearchQuery = array();

	/**
	* Array que almacena los campos claves de la editquery.
	* @access private
	* @var array v_pkForEditQuery
	*/
	private $v_pkForEditQuery = array();
	
	/**
	* String en el que se almacenarán condiciones de busqueda especiales. Por ejemplo para añadir un EXISTS a la SELECT si cierto campo del panel de busqueda está a true.
	* @access private
	* @var string str_whereAdicional
	*/	
	var $str_whereAdicional;
		
	/**
	* Variable interna que permite modificar el tipoConsulta general de un panel. Los valores posibles son:
	*	 (0) Se construye la Where igualando los campos a los valores.
	*	 (1) Se construye con like y comodines para cada campo.
	*	 (2) Por defecto, se contruye con like sólo si el usuario ha especificado comodines.
	*	  (3) Se contruye con like, case unsensitive y sin considerar las marcas diacríticas (no distingue acentos, ç,..).
	*	 @access private
	* @var integer $int_tipoConsulta
	*/
	var $int_tipoConsulta;


	private $changeFilterPostInsert = true;

	/**
	* constructor. Generará a partir de los parámetros que se le pasen una conexión a al base de datos y un
	* array de manejadores de  tablas (una por cada una de las que mantenga el panel hijo).
	*/
	function gvHidraForm_DB($dsn='',$nombreTablas=null){	 
		
		//Contiene la coleccion de tablas que mantiene:
		//Generalmente una. También pueden ser varias o ninguna.
		if(is_array($nombreTablas))
			$this->v_nombreTablas= $nombreTablas;
		elseif($nombreTablas!==null)
			$this->v_nombreTablas = array($nombreTablas);			
		//Guardamos la referencia del dsn principal
		$this->_dsnInterno = $dsn;
		//Guardamos la referencia al tipo de consulta
		$this->int_tipoConsulta = ConfigFramework::getConfig()->getQueryMode();
		//Creamos la instancia de IgepSmarty que controla el Js
		$this->obj_IgSmarty = new IgepSmarty();
		//Generamos la instancia completa
		$this->regenerarInstancia($dsn);
	}//Fin de constructor

	function regenerarInstancia($dsn=''){
		//Recuperamos la instancia de la clase Error. Si no existe (caso en el que venimos de Views), lo creamos
		global $g_error;				
		//#NVI#VIEWS#: Cuando quietemos del views las llamadas a Negocio quitamos este if
		if(!isset($g_error)) 
			$g_error = new IgepError(); 
		$this->obj_errorNegocio = & $g_error;
		if($dsn=='')
			$dsn=$this->getDSN();
		//Como es una instancia de una clase hija creamos la conexión.	  
		if($dsn!='')
			$this->obj_conexion = new IgepConexion($dsn);		   
		//Comprobación de errores de la conexion.
		if($this->obj_errorNegocio->hayError()){			
			$v_descError = $this->obj_errorNegocio->getDescErrorDB();					   
			$mensajeError = new IgepMensaje('IGEP-6',$v_descError);
			IgepSession::guardaVariable('principal','obj_mensaje',$mensajeError);
			return;
		}
		//Si es un form de mantenimiento, creamos las clases de persistencia
		if(count($this->v_nombreTablas)>0){
			$i=0;		
			foreach($this->v_nombreTablas as $tabla) {	  
				$this->v_tablas[$i] = new IgepPersistencia($this->obj_conexion->getPEARConnection(),$tabla);
				++$i;			   
			}
		}
		//Creamos la instancia de IgepComunicacion
		$this->comunica = new IgepComunicacion($this->v_descCamposPanel);		
	}

	function getDSN(){
		if(!empty($this->_dsnInterno))
			return $this->_dsnInterno;
		else
			return '';
	}

	/**
	 * Devuelve el objeto conexión al que se está conectado.
	 *
	 * @access	public
	 * @return	object
	 */		
	public function getConnection()
	{
		return $this->obj_conexion;
	}//Fin de getConnection


  /* -------------------------- MÉTODOS DE CONSULTA -------------------------- */

	/**
	* Este método almacenará una cadena que luego se anexará a la WHERE de la consulta a ejecutar. En esa cadena se pueden incluir condiciones especiales como añadir un EXISTS si cierto campo del panel de busqueda está a true. 
	* @param	string str_where	contiene la cadena que se quiere concatenar a la WHERE de la consulta
	* @return integer
	* @abstract
	*/	
	public function setSearchParameters($str_where){

		if(trim($str_where)!='')
			$this->str_whereAdicional = $str_where;
	}
	
	/**
	* Método que construye la SQL que se lanzara posteriormente para obtener los datos 
	* @return none
	*/	
	public final function prepareDataSource() {

		//Si no tiene consulta, ya hemos ejecutado el preBuscar y acabamos
		//Sino, si el retorno es distinto de 0 tambien acabamos
		if((empty($this->str_select))) {
			return 0;
		}
		$m_datosFW = $this->comunica->getAllTuplas();
		
		//Creamos matriz de datos preparados para realizar la Query	  
		$m_datos = array();

		if(is_array($m_datosFW) and count($m_datosFW)>0) {

			$this->obj_conexion->prepararOperacion($m_datosFW,$this->v_descCamposPanel);

			$undiacritic = array();

			foreach($m_datosFW as $index => $row){
				foreach($row as $field => $value){				
					if(isset($this->matching[$field])){
						$campo = $this->matching[$field]['campo'];
						$tabla = $this->matching[$field]['tabla'];
						//Comprobamos que el valor sea distinto de vacio para poder darle valor
						if($value!='') {

							$m_datos[$index][$tabla.'.'.$campo] = $value;

							//Si el tipo es string es anyadimos el undiacritic					
							(empty($this->v_descCamposPanel[$field]) 
							or @$this->v_descCamposPanel[$field]['tipo']==TIPO_CARACTER)? $undiacritic[$tabla.'.'.$campo] = true: $undiacritic[$tabla.'.'.$campo] = false;
						}
					}
				}
			}
		}
		$str_where = '';
		if (isset($m_datos)) {  
			$tipo = $this->getQueryMode();
			foreach($m_datos as $v_datos) {	 
				$str_where = $this->obj_conexion->construirWhereBusqueda($v_datos,$undiacritic,$str_where,$tipo);
			}//Fin de foreach		   
		}
		//Mezclamos las dos partes de la where
		$str_where = $this->obj_conexion->combinarWhere(array($str_where,$this->str_where,$this->str_whereAdicional));
		$this->str_whereAdicional = '';		
		//Almacenamos la última where realizada
		$this->str_whereFiltro = $str_where;
		//Si tenemos constantes se las concatenamos a la SELECT 
		if($this->str_SelectConstantes!=''){
			$posFrom = strpos(strtolower($this->str_select),' from');
			$this->str_select = substr_replace($this->str_select,$this->str_SelectConstantes,$posFrom,0);
		}
	}

	/**
	* Método que lanza la consulta SQL y retorna los datos 
	* @return none
	*/	 
 	public final function recoverData(){
		//Si tiene consulta la lanza
		if(!empty($this->str_select)){
			//Añadimos el Order By
			if (isset($this->str_orderBy))
				$orden = ' ORDER BY '.$this->str_orderBy; 
			$str_where = $this->str_whereFiltro;		
			//Añadimos el límite de la consulta
			$limite = $this->obj_conexion->construirLimite($str_where,$this->int_limiteConsulta); 
			//Realizamos la Consulta
			$consultaActual = $this->str_select.$str_where.$orden.$limite;   
			$res = $this->obj_conexion->consultar($consultaActual);	
			//Antes de transformar los datos comprobamos que no hay error de BD
			if($res!=-1)
				$this->obj_conexion->transform_BD2FW($res,$this->v_descCamposPanel);
			return $res;
		}
		else
			return array();	
 	}

	public final function prepareDataSourceDetails($detail,$masterData){
		if(!empty($detail->str_select)){
			$v_datos = array();
			foreach ($this->v_hijos[$this->panelDetalleActivo] as $padre => $hijo){		 
				$filapadre = array_keys($masterData);
				$tablahijo = $detail->matching[$hijo]['tabla'];
				$campohijo = $detail->matching[$hijo]['campo'];  
				$campoConsultaHijo= $tablahijo.".".$campohijo; 
				//Si es una lista le asignamos el valor del seleccionado
				if(is_array($masterData[$filapadre[0]][$padre])) 
					$value = $masterData[$filapadre[0]][$padre]['seleccionado'];				
				else
					$value = $masterData[$filapadre[0]][$padre];
									
				//Lo guardamos en datos por defecto del detalle
				$detail->addDefaultData($hijo,$value);

				//Fijamos el valor
				$this->obj_conexion->prepararOperacion($value,@$detail->v_descCamposPanel[$hijo]['tipo']);
				$v_datos[$campoConsultaHijo] = $value;
			}
			//Componemos la WHERE de la consulta.
			$str_where = $this->obj_conexion->construirWhere($v_datos,'');	  
			//Cuando no hay datos para el where dara error
			$str_where = $this->obj_conexion->combinarWhere(array($str_where,$detail->str_where));
			$detail->str_whereFiltro = $str_where;
		}
	}

	/**
	* Método que lanza la consulta SQL y retorna los datos del detalle 
	* @return none
	*/	 
 	public final function recoverDataDetail(){
		//Si tiene consulta la lanza
		if(!empty($this->str_select)){
			//dejo el ordeby de momento
			if (isset($this->str_orderBy))
				$orden = ' ORDER BY '.$this->str_orderBy;
			$str_where = $this->str_whereFiltro;					
			//OJO: hay que transformar res a formato FW de formato BD
			$res = $this->obj_conexion->consultar($this->str_select.$str_where.$orden);
			//Antes de transformar los datos comprobamos que no hay error de BD
			if($res!=-1)		
				$this->obj_conexion->transform_BD2FW($res,$this->v_descCamposPanel);
			return $res;
		}
		else
			return array();	
 	}

	public final function prepareDataSourceEdit(){
		if(empty($this->str_selectEditar))
			return 0;
		$m_datosTpl = $this->comunica->getAlltuplas();
		$hayCamposClave = count($this->v_pkForSearchQuery);
		$str_where = '';	
		//OJO: Transformar datos de formato FW a formato BD
		foreach($m_datosTpl as $indice=>$v_datos) {	 
			//Si hay campos clave solo crearemos el Where sobre los campos selecionados
			if($hayCamposClave>0) {
				$v_datosCP = array();				
				foreach($this->v_pkForSearchQuery as $campoTpl){		  
					$campoBD = $this->matching[$campoTpl]['tabla'].'.'.$this->matching[$campoTpl]['campo'];		  
					//Controlamos que el campo que añadimos no sea vacio, en principio no es necesario, pero por si acaso
					if($v_datos[$campoTpl]!=''){
						$this->obj_conexion->prepararOperacion($v_datos[$campoTpl],$this->v_descCamposPanel[$campoTpl]['tipo']);
						$v_datosCP[$campoBD] = $v_datos[$campoTpl];
					}		   
				}
				$str_where = $this->obj_conexion->construirWhere($v_datosCP,$str_where);		
			}
			else{
				//Sino, crearemos el Where sobre todos los campos con matching
				$v_tupla = array();
				foreach($v_datos as $campoTpl=>$value){
					if(isset($this->matching[$campoTpl])){
						$campoBD = $this->matching[$campoTpl]['tabla'].'.'.$this->matching[$campoTpl]['campo'];
						//Controlamos que el campo que añadimos no sea vacio
						if($value!=''){
							$this->obj_conexion->prepararOperacion($v_datos[$campoTpl],$this->v_descCamposPanel[$campoTpl]['tipo']);
							$v_tupla[$campoBD] = $v_datos[$campoTpl];
						}
							
					}
				}
				$str_where = $this->obj_conexion->construirWhere($v_tupla,$str_where);
			}			
		}
		$str_where = $this->obj_conexion->combinarWhere(array("($str_where)",$this->str_whereEditar));
		if($this->str_orderByEditar!='')
			$orderBy = ' ORDER BY '.$this->str_orderByEditar;
		$this->str_whereFiltroEdicion = $str_where.$orderBy;
		return 0;
	}
	
	public final function recoverDataEdit(){
		if(!empty($this->str_selectEditar)){
			$consultaActual = $this->str_selectEditar.$this->str_whereFiltroEdicion;	
			$res = $this->obj_conexion->consultar($consultaActual);
			//Antes de transformar los datos comprobamos que no hay error de BD
			if($res!=-1)
				$this->obj_conexion->transform_BD2FW($res,$this->v_descCamposPanel);
			return $res;
		}
		return array();
	}	

	/* ----------------------- PROPERTIES ---------------------- */
	/**
	 * Permite especificar la cabecera de la query consulta del CRUD de gvHidra que se lanza en la acción buscar.
	 *
	 * @param String $query	Cabecera de sentencia SQL
	 */
	public function setSelectForSearchQuery($query)
	{
		$this->str_select = $query;
	}
	
	/**
	 * Permite especificar la clausula 'WHERE' de la query consulta del CRUD de gvHidra que se lanza en la acción buscar.
	 *
	 * @param String $query	Cláusula 'WHERE' de sentencia SQL
	 */
	public function setWhereForSearchQuery($query)
	{
		$this->str_where = $query;
	}
	
	/**
	 * Permite especificar la clausula 'ORDER BY' de la query consulta del CRUD de gvHidra que se lanza en la acción buscar.
	 *
	 * @param String $query	Cláusula 'ORDER BY' de sentencia SQL
	 */
	public function setOrderByForSearchQuery($query)
	{
		$this->str_orderBy = $query;
	}
	
	/**
	 * Permite especificar la cabecera de la query consulta del CRUD de gvHidra que se lanza en la acción editar (paso de tabular a registro en patrón T-R)
	 *
	 * @param String $query	Cabecera de sentencia SQL
	 */
	public function setSelectForEditQuery($query)
	{
		$this->str_selectEditar = $query;
		//Fijamos esta propiedad a true para que por defecto en las clases manejadoras con Tabular Registro redirija a edición.
		$this->setJumpToEditOnUniqueRecord(true);
	}
	
	/**
	 * Permite especificar la clausula 'WHERE' de la query consulta del CRUD de gvHidra que se lanza en la acción editar (paso de tabular a registro en patrón T-R)
	 *
	 * @param String $query	Cláusula 'WHERE' de sentencia SQL
	 */
	public function setWhereForEditQuery($query)
	{
		$this->str_whereEditar = $query;
	}
	
	/**
	 * Permite especificar la clausula 'ORDER BY' de la query consulta del CRUD de gvHidra que se lanza en la acción editar (paso de tabular a registro en patrón T-R)
	 *
	 * @param String $query	Cláusula 'ORDER BY' de sentencia SQL
	 */
	public function setOrderByForEditQuery($query)
	{
		$this->str_orderByEditar = $query;
	}

	/**
	 * Permite especificar la PK de las dos queries que se pueden definir en el FW. El primer parametro permite
	 * definir la PK para el el searchMode. El segundo corresponde con el editMode.
	 *
	 * @param array $fieldsForSearchQuery	Campos que componen la clave primaria en la query del searchMode
	 * @param array $fieldsForEditQuery	Opcional. Campos que componen la clave primaria en la query del editMode
	 */
	public function setPKForQueries($fieldsForSearchQuery,$fieldsForEditQuery=array()) {

		//pk searchQuery
		foreach($fieldsForSearchQuery as $campoTpl) {
			if(!isset($this->matching[$campoTpl])) {
				
				IgepSession::borraPanel(get_class($this));
				throw new Exception('Error setPKForQueries: Param1 - Todos los campos claves tienen que tener matching');
			}
			array_push($this->v_pkForSearchQuery,$campoTpl);
		}

		//Comprobamos que existe el PK para SearchQuery
		if(count($this->v_pkForSearchQuery)==0) {

			IgepSession::borraPanel(get_class($this));
			throw new Exception('Error setPKForQueries: Debe haber introducido la PK para el SearchMode previamente');
		}

		//pk editQuery		
		if(count($fieldsForEditQuery)>0) {
			foreach($fieldsForEditQuery as $campoTpl) {
				if(!isset($this->matching[$campoTpl])) {
					
					IgepSession::borraPanel(get_class($this));
					throw new Exception('Error setPKForQueries: Param2 - Todos los campos claves tienen que tener matching');
				}
				array_push($this->v_pkForEditQuery,$campoTpl);
			}
		}
	}

	/**
	* Indica si el filtro se debe regenerar tras una insercion. Tras insertar una tupla, para evitar
	* que dicha tupla no aparezca en el filtro previo, se elimina dicho filtro y se crea uno nuevo que
	* apunta a la nueva tupla insertada (a través de su PK o todos sus campos).
	* 
	* Puede haber casos en los que nos interese que este comportamiento no se produzca. Con este método
	* podemos cambiar el comportamiento por defecto. Los valores que admite son:
	* 
	* -Con valor true, despues de insertar solo se vera la nueva tupla insertada. Valor por defecto
	* -Con valor false no modifica el filtro, por lo que se recarga el panel con el filtro previo.
	* 
	* @param boolean value
	* @return none
	*/	
	public function showOnlyNewRecordsAfterInsert($value) {

		$this->changeFilterPostInsert = $value;
	}
	
	private function getChangeFilterPostInsert() {

		return $this->changeFilterPostInsert; 
	}

	/**
	* Permite obtener el filtro actual que se está utilizando sobre la SearchQuery.
	* 
	* Devuelve la construcción que ha realizado el FW tras ejecutarse la accion buscar. Es el WHERE que obtiene los 
	* registros que se muestran en ese momento en el panel SearhQuery 
	*
	* @param none
	* @return string
	*/
	public function getFilterForSearch() {

		return $this->str_whereFiltro;
	}

	/**
	* Permite cambiar el filtro actual que se está utilizando sobre la SearchQuery.
	* 
	* Fija el WHERE que se utilizará para refrescar los datos del panel SearhQuery 
	*
	* @param newFilter string
	* @return none
	*/	
	public function setFilterForSearch($newFilter) {

		$this->str_whereFiltro = $newFilter;
	}

	/**
	* Permite obtener el filtro actual que se está utilizando sobre la EditQuery.
	* 
	* Devuelve la construcción que ha realizado el FW tras ejecutarse la accion buscar. Es el WHERE que obtiene los 
	* registros que se muestran en ese momento en el panel EditQuery 
	*
	* @param none
	* @return string
	*/	
	public function getFilterForEdit() {

		return $this->str_whereFiltroEdicion;
	}

	/**
	* Permite cambiar el filtro actual que se está utilizando sobre la EditQuery.
	* 
	* Fija el WHERE que se utilizará para refrescar los datos del panel EditQuery 
	*
	* @param newFilter string
	* @return none
	*/
	public function setFilterForEdit($newFilter) {

		$this->str_whereFiltroEdicion = $newFilter;
	}

	/* ----------------------- FUNCIONES DE OPERACIONES ---------------------- */


	/**
	* Este método es el método abstracto que ofrece Igep para realizar operaciones una vez realiza la acción de insertar. Todo lo que se realice en este evento está
	* incluido en la TRANSACTION por lo que podrá cancelarse la operación. Su utilización pude ser:
	*<ul>
	*<li>Si se quiere insertar N tuplas en una tabla con relación 1:N con la tabla sobre la que ya hemos insertado. Si no se puede realizar la operación, podemos cancelar toda la transacción.</li>
	*</ul>
	*
	* <b>IMPORTANTE:</b>: Si se quiere interrumpir la ejecución de la Insercion, el programador debe utilizar el método setError para indicar que se ha producido un error. 
	* @return integer
	* @abstract
	*/  
	public function postInsertar($objDatos){ 
		return 0;   
	}

	/**
	* Método encargado de realizar los INSERTs
	* @access private
	*/
	public function processInsert() {
		/*Empezamos la transacción*/
		$this->obj_conexion->empezarTransaccion();						  
		$m_datosTpl = $this->comunica->getAllTuplas();
		//Creamos matriz de datos adaptada para la insercion
		$m_datos = $this->createArrayCRUD($m_datosTpl);
		//Si tiene una tabla de mantenimiento
		if(!empty($this->v_tablas)){
			foreach($this->v_tablas as $tabla) {
				$tabla->insertar($m_datos[$tabla->getTabla()]);
			}
		}
		//Realizamos la operación de Post-Inserción si no hay errores.	  
		$errores = $this->obj_errorNegocio->hayError();
		if(!$errores) {

	   		// Antes del postInsertar cambiamos el filtro de busqueda
	   		// Si el programador quiere, puede volver a poner el mismo en el postInsertar
			$hayInsercion = ! $this->comunica->isEmpty('insertar');
			if($hayInsercion and !isset($this->str_nombrePadre)) { 
				
				$tuplas = $m_datosTpl;
				$nuevasTuplas = '';
				
				//Cambiamos el filtro tras insertar a no ser que el programador haya indicado lo contrario.
				if($this->getChangeFilterPostInsert()==true){
				
					IgepDebug::setDebug(DEBUG_IGEP,'PostInsert: el FW cambia el filtro de búsqueda para que sólo muestre los nuevos registros');

					foreach($tuplas as $tupla){
						//Renombramos la tupla para que añada el nombre de la tabla
						if(count($this->v_pkForEditQuery)) {
							foreach($this->v_pkForEditQuery as $campo) {
								$campoBD = $this->matching[$campo]['tabla'].'.'.$this->matching[$campo]['campo'];
								$this->obj_conexion->prepararOperacion($tupla[$campo],$this->v_descCamposPanel[$campo]['tipo']);								
								$tuplaRenombrada[$campoBD]=$tupla[$campo];
							}
						}
						else{
							foreach($tupla as $campo=>$valor) {
								if (isset($this->matching[$campo])) {

									$campoBD = $this->matching[$campo]['tabla'].'.'.$this->matching[$campo]['campo'];
									$this->obj_conexion->prepararOperacion($valor,@$this->v_descCamposPanel[$campo]['tipo']);
									$tuplaRenombrada[$campoBD]=$valor;
								}
							}
						}				 
						$unaTupla = $this->obj_conexion->construirWhere($tuplaRenombrada,'');
						if ($nuevasTuplas!='')
							$nuevasTuplas.=' OR ';
						$nuevasTuplas.= $unaTupla;	
					}
					$wherePanel = '';
					if($this->str_where!='')
						$wherePanel = $this->str_where.' AND ';
					$this->str_whereFiltro = ' WHERE '.$wherePanel.' ('.$nuevasTuplas.')';
				}
				else {
					if(empty($this->str_whereFiltro) AND !empty($this->str_where))
						$this->str_whereFiltro = ' WHERE '.$this->str_where;
				}
			}

			$this->comunica->reset();
			$comunicaUsuario = new IgepComunicaUsuario($this->comunica,$this->v_preInsercionDatos,$this->v_listas);
			$retorno = $this->postInsertar($comunicaUsuario);
		}
		/*Comprobación de  errores*/
		$errores = $this->obj_errorNegocio->hayError();	 
		//Cancelamos la transaccion si hay errores o el return es -1
		if($errores or $retorno==-1){
			$this->obj_conexion->acabarTransaccion(1);
			return -1;
		}
		else{

	   		$this->obj_conexion->acabarTransaccion(0);
	   		return $retorno;
		}
	}

	/**
	* Este método es el método abstracto que ofrece Igep para realizar operaciones una vez realiza la acción de modificar. Todo lo que se realice en este evento está
	* incluido en la TRANSACTION por lo que podrá cancelarse la operación. Su utilización pude ser:
	*<ul>
	*<li>Si se quiere modificar N tuplas en una tabla con relación 1:N con la tabla sobre la que ya hemos modificado. Si no se puede realizar la operación, podemos cancelar toda la transacción.</li>
	*</ul>
	*
	* <b>IMPORTANTE:</b>: Si se quiere interrumpir la ejecución de la modificación, el programador debe utilizar el método setError para indicar que se ha producido un error. 
	* @return integer
	* @abstract
	*/  
	public function postModificar($m_datos) {
		return 0;
	}
	
	/**
	* proceso de actualizacion de los datos 
	*/	
	public function processUpdate(){
		/*Empezamos transacción*/
		$this->obj_conexion->empezarTransaccion();	  
		//Si tenemos tabla de mantenimiento
		$m_datosTpl = $this->comunica->getAllTuplas();
		$m_datosAntiguosTpl = $this->comunica->getAllTuplasAntiguas();
		//Creamos matriz de datos adaptada para la insercion
		$m_datos = $this->createArrayCRUD($m_datosTpl);
		$m_datosant  = $this->createArrayCRUD($m_datosAntiguosTpl);
		if(isset($this->v_tablas)){
			foreach($this->v_tablas as $tabla) {
				if(is_array($m_datos[$tabla->getTabla()])) {
					foreach($m_datos[$tabla->getTabla()] as $indice => $v_datos) {
						$tabla->actualizar($v_datos, $m_datosant[$tabla->getTabla()][$indice]);
					}
				}
			}
		}
		//Lanzamos el postModificar si no ha habido errores
		$errores = $this->obj_errorNegocio->hayError();
		if(!$errores){
			$this->comunica->reset();
			$comunicaUsuario = new IgepComunicaUsuario($this->comunica,$this->v_preInsercionDatos,$this->v_listas);
			$retorno = $this->postModificar($comunicaUsuario);
		}
		/*Comprobación de errores*/
		$errores = $this->obj_errorNegocio->hayError();	 
		//Cancelamos la transaccion si hay errores o el return es -1
		if($errores or $retorno==-1){
			$this->obj_conexion->acabarTransaccion(1);
			return -1;
		}
		else {
	   		$this->obj_conexion->acabarTransaccion(0);
			return $retorno;
		}
	}	

	/**
	* Este método es el método abstracto que ofrece Igep para realizar operaciones una vez realiza la acción de borrar. Todo lo que se realice en este evento está
	* incluido en la TRANSACTION por lo que podrá cancelarse la operación. Su utilización pude ser:
	*<ul>
	*<li>Si se quiere borrar N tuplas en una tabla con relación 1:N con la tabla sobre la que ya hemos borrado. Si no se puede realizar la operación, podemos cancelar toda la transacción.</li>
	*</ul>
	*
	* <b>IMPORTANTE:</b>: Si se quiere interrumpir la ejecución del borrado, el programador debe utilizar el método setError para indicar que se ha producido un error. 
	* @return integer
	* @abstract
	*/  
	public function postBorrar(){ 
		return 0;   
	}

	public function processDelete(){
		$this->obj_conexion->empezarTransaccion();
		$m_datosTpl = $this->comunica->getAllTuplas();
		$m_datos = $this->createArrayCRUD($m_datosTpl);
		//Si tenemos una tabla de mantenimiento
		if(isset($this->v_tablas)){
			foreach($this->v_tablas as $tabla) {
				if(is_array($m_datos[$tabla->getTabla()])) {
					foreach ($m_datos[$tabla->getTabla()] as $v_datos)											  
						$tabla->borrar($v_datos);
				}								   
			}
		}
		//Lanzamos el postBorrar si no ha habido errores
		$errores = $this->obj_errorNegocio->hayError();
		$retorno = 0;
		if(!$errores){
			$this->comunica->reset();
			$comunicaUsuario = new IgepComunicaUsuario($this->comunica,$this->v_preInsercionDatos,$this->v_listas);
			$retorno = $this->postBorrar($comunicaUsuario);
		}
		//Comprobación de errores
		$errores = $this->obj_errorNegocio->hayError();
		//Cancelamos la transaccion si hay errores o el return es -1
		if($errores or $retorno==-1){
			$this->obj_conexion->acabarTransaccion(1);
			return -1;
		}
		else {
	   		$this->obj_conexion->acabarTransaccion(0);
			return $retorno;
		}
	}

	/*-------------------------- FUNCIONES AUXILIARES -------------------------*/
	/**
	* Método que limpia de variables inncesarias el objeto actual antes de guardarlo en la SESSION 
	* @access private
	*/
	public function limpiarInstancia(){
		//Esta función se encargará de liberar de carga la instancia de la clase antes de ponerla en la SESSION
		parent::limpiarInstancia();
		unset($this->obj_conexion->obj_conexion); 
		unset($this->obj_conexion->obj_errorConexion);  
		unset($this->v_tablas);
		unset($this->str_SelectConstantes);
	}

	/**
	* Este método devuelve el valor de una secuencia programada en la base de datos
	* para la conexión del panel 
	*
	* @param  nombreSecuencia string  nombre de la secuencia en la BD
	* @return integer 
	*/
	public function calcularSecuenciaBD($nombreSecuencia){   
		$res = $this->obj_conexion->calcularSecuenciaBD($nombreSecuencia);
		if($this->obj_errorNegocio->hayError()) {
			$this->showMessage('IGEP-12',$this->obj_errorNegocio->getDescErrorDB());
			$this->obj_errorNegocio->limpiarError();
			return -1;
		}
		else
			return $res;
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
	public function calcularSecuencia($tabla,$campoSecuencia,$camposDependientes, $valorInicial=1){
		$res = $this->obj_conexion->calcularSecuencia($tabla,$campoSecuencia,$camposDependientes, $valorInicial);
		if($this->obj_errorNegocio->hayError()) {
			$this->showMessage('IGEP-12',$this->obj_errorNegocio->getDescErrorDB());				  
			$this->obj_errorNegocio->limpiarError();
			return -1;
		}
		else {
			if (($res=='')||($res==null)||(!isset($res)))
				return $valorInicial;
			else 
				return ($res);
		}
	}//Fin de funcion calcularSecuencia

	/*------------------- FUNCIONES DE AYUDA AL PROGRAMADOR -------------------*/

	/**
	* Función encargada de indicar a Negocio la correspondencia de los campos de la TPL con los campos de la BD.
	* En principio sólo deben de indicarse los campos que se almacenarán en la BD.
	* @internal Rellena el array de matchin. Tenemos que quitar la referencia al mismo en el caso de editar.
	* 
	* @param	campoTpl	corresponde con el nombre del campo en la Tpl
	* @param	campoBD indica el nombre del campo en la tabla de la BD
	* @param	tablaBD indica el nombre de la tabla a la que corresponde.
	* @return none 
	*/
	public function addMatching($campoTpl,$campoBD,$tablaBD){

		//Comprobamos que exista una referencia a ese campo en una SELECT   
		/*
			$cond1 = strpos($this->str_select,$campoTpl);
			$cond2 = strpos($this->str_selectEditar,$campoTpl);
			if (($cond1===false) and ($cond2===false))
				throw new Exception("Error de Programación: Ha incluido el campo $campoTpl que no tiene ninguna referencia en las SELECT de la clase. Compruebe que la definición de tablas está antes que el matching");
		*/
		$this->matching[$campoTpl] = array('campo'=>$campoBD,'tabla'=>$tablaBD);   
	}//Fin de addMatching

	/**
	* Función encargada de indicar a Negocio que existe una definición de una nueva Lista
	* 
	* @param	obj_lista   objetivo de tipo gvHidraList.
	* @return none 
	*/
	public function addList($objLista) {

		$nombreClase = get_class($this);
		if(!is_object($objLista)) {

			IgepSession::borraPanel($nombreClase);
			throw new Exception('Error: Problema al adjuntar la lista '.$objLista->getName());
		}
		if($nombreClase=='') {

			IgepSession::borraPanel($nombreClase);
			throw new Exception('Error: Problema al adjuntar la lista '.$objLista->getName().'. Antes de definir las listas debe llamar al constructor de gvHidraForm_DB.');
		}	  
		//Si no tiene dsn, quiere decir que funciona con el dsn de la clase
		if(!$objLista->hayDSN()) {
			$objLista->setDSN($this->getDSN());
			$objLista->setConnection($this->getConnection());
			$objLista->setConnectionOwn(FALSE);			
		}
		
		//Puede darse el caso que haya añadido un addDefaultData anteriormente
		//Para evitar que se pierda, si no tiene seleccionado, lo cargamos
		$defaultData = $this->getDefaultData();
		$seleccionado = $objLista->getSelected();
		if(empty($seleccionado) and !empty($defaultData[$objLista->getName()]))
			$objLista->setSelected($defaultData[$objLista->getName()]);			
			
		//Almacenamos la lista en la estructura interna y en los datos por defecto.
		$this->v_listas[$objLista->getName()] = $objLista;
		$resultadoLista = $objLista->construyeLista($this->getDefaultData());	   
		$this->addDefaultData($objLista->getName(), $resultadoLista);

		//Guardamos la informacion de la lista en la estructura dataTypes.
		$this->v_descCamposPanel[$objLista->getName()]['multiple'] = $objLista->getMultiple();
		$this->v_descCamposPanel[$objLista->getName()]['radio'] = $objLista->getRadio();
		$this->v_descCamposPanel[$objLista->getName()]['size'] = $objLista->getSize();

		//En el caso de los datalles puede darse el caso de que no pasemos por phrame (refreshDetail), y que no ejecutemos el perform. Por eso lo metemos en la SESSION.
		$datosPreInsertados = IgepSession::dameVariable($nombreClase,'v_preInsercionDatos');
		$datosPreInsertados[$objLista->getName()]=$resultadoLista;
		IgepSession::guardaVariable($nombreClase,'v_preInsercionDatos',$datosPreInsertados);
	}//Fin de addList

	/**
	* Función encargada de almacenar constantes que se añaden al DBResult que se muestra en un panel.
	* Es importante tener en cuenta que no se pueden añadir constantes en consultas con el operador SQL DISTINCT afectando a todo la tupla.
	* 
	* @param	nombre  nombre que se le da a la constante
	* @param	valor   valor que va a tomar la constante
	* @return none 
	*/
	public function addConstant($nombre, $valor) {

		//La cadena que tiene que guardar es del tipo '$valor' as "$nombre" 
		if(isset($nombre)&&isset($valor))	   
			$this->str_SelectConstantes.= ", "." '$valor' as \"$nombre\"";
	}//Fin de addConstant


	/**
	* Función que realiza una consulta a la Base de datos con la conexión actual
	* Es la función que el programador debe gastar para realizar una consulta SQL
	*
	* @param   string que contiene la consulta.
	* @param   array  vector que indica si queremos convertir los datos
	* @return  array devuelve un array si todo ha ido bien o -1 en caso de error.
	*/
	public function consultar($str_consulta,$tipo=NULL){
		if(!isset($this->obj_errorNegocio)){
			global $g_error; 
			$g_error = new IgepError();
			$this->obj_errorNegocio = & $g_error;		   
		}			  
		if (!is_null($tipo)) {
	   		if (!array_key_exists('DATATYPES',$tipo)) {
	   			// se supone que las columnas son las del panel
	   			$tipo['DATATYPES'] = $this->v_descCamposPanel;
	   		}
		}
		$res = $this->obj_conexion->consultar($str_consulta,$tipo);
		if($this->obj_errorNegocio->hayError()){
			//La consulta es erronea.		   
			$this->obj_errorNegocio->setMsjError($this->obj_mensaje =new IgepMensaje());
			return -1;
		}
		return $res;
	}

	/**
	* Función que realiza una operación SQL en la Base de datos con la conexión actual
	* Es la función que el programador debe gastar para realizar una operación SQL
	*
	* @param	string que contiene la operación a realizar.
	* @return integer	devuelve 0 si todo ha ido bien o -1 en caso de error
	*/
	public function operar($str_operacion){
		if(!isset($this->obj_errorNegocio)){
			global $g_error; 
			$g_error = new IgepError();
			$this->obj_errorNegocio = & $g_error;
		}
		$this->obj_conexion->operar($str_operacion);
		if($this->obj_errorNegocio->hayError()){
			//La consulta es erronea.
			$this->obj_errorNegocio->setMsjError($this->obj_mensaje =new IgepMensaje());
			return -1;
		}
		return 0;
	}

	/**
	* Función que debe utilizar el programador para indicar el límite de registros que se pueden recuperar de la base de datos con una consulta.
	* Por defecto IGEP tiene un límite de 100.
	* 
	* @param	integer número que indica el límite. 
	* @return none 
	*/
	public function setLimit($int_limite){
		$this->int_limiteConsulta = $int_limite;
	} 
  
	/**
	* Método que sirve para fijar el tipo de consulta del panel. Siempre se descartan mayúsculas y marcas diacríticas.
	* Las posibilidades son:
	*   (0) Se contruye la Where igualando los campos a los valores.
	*   (1) Se construye con like y comodines para cada campo.
	*   (2) Por defecto, se contruye con like sólo si el usuario ha especificado comodines.
	* @param integer $valorTipoConsulta Entero entre 0 y 2 que indica el tipo deseado.	
	* @return none 
	*/
	public function setQueryMode($valorTipoConsulta) {
		
		if(($valorTipoConsulta>-1) and ($valorTipoConsulta<3))
			$this->int_tipoConsulta = $valorTipoConsulta;
			
		else {
			
			$nombreClaseActual = get_class($this);
			IgepSession::borraPanel($nombreClaseActual);
			throw new Exception('Error en el constructor de la clase '.$nombreClaseActual.'. El valor del tipo de consulta debe encontrarse entre [0-2]');			
		}
	}
	
	/**
	* Método que sirve para obtener el tipo de consulta del panel. Las posibilidades son:
	*   (0) Se contruye la Where igualando los campos a los valores.
	*   (1) Se construye con like y comodines para cada campo.
	*   (2) Por defecto, se contruye con like sólo si el usuario ha especificado comodines.	   
	* @return integer
	*/
	public function getQueryMode() {
		
		return $this->int_tipoConsulta;
	}   

	private function createArrayCRUD($m_datosTpl) {

		//Creamos matriz de datos adaptada para las operaciones
		if(count($m_datosTpl)==0)
			return array();
		$m_datos = array();		
		foreach($m_datosTpl as $index =>$row){
			foreach($row as $field => $value){
				//Comprobamos si el campo tiene matchig				
				if(isset($this->matching[$field])){
					$tipo = (@$this->v_descCamposPanel[$field]['tipo']==''? TIPO_CARACTER: $this->v_descCamposPanel[$field]['tipo']);
					$this->obj_conexion->prepararOperacion($value,$tipo);
					//Construimos el array agrupando por tablas
					$m_datos[$this->matching[$field]['tabla']][$index][$this->matching[$field]['campo']] = $value;
				}
			}
		}
		return $m_datos;		
	}
	
}//Fin clase gvHidraForm_DB
?>