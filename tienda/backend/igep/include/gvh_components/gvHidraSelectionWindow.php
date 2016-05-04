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
 * gvHidraSelectionWindow: Definición y majejo de una Ventana de Selección (V. Sel) 
 * 
 * La clase abstrae el manejo y definición de una ventana de selección gvHidra,
 * de forma que se facilita al programador un mecanismo sencillo y comprensible
 * para utilizarla, mediante el uso de arrays 
 * También contiene los métodos que necesita negocio para el manejo de las
 * acciones de una ventana Selección (abrirVentanaSeleccion y buscarVentanaSeleccion).
 *
 * @version	$Id: gvHidraSelectionWindow.php 5502 2014-08-19 15:30:37Z pascual_dav $
 * @author Verónica Navarro Porter: <navarro_ver@externos.gva.es>
 * @author Toni Felix: <felix_ant@externos.gva.es> 
 * @author David Pascual: <pascual_dav@externos.gva.es>
 * @package	gvHIDRA
 */


class gvHidraSelectionWindow
{

	/**
	* Array que contiene la definición de la ventana de selección  
	* @var array
	*/	
	private $v_defVentana;
	
	/**
	 * Nombre de la ventana de selección
	 * @var string
	*/
	private $nombreVentana;
	
	/**
	 * Número máximo de registro a mostrar (si se excede se informa)
	 * @var integer
	 */
	private $limitQuery = 50;
	
	
	
	/**
	 * Constructor.
	 * 
	 * Recibe como parámetros el campo de la TPL asociado a la invocación de la
	 * ventana de selección (normalmente es el campo que se informa la invocación)
	 * y una referencia única para ser utilizada en distintos paneles ($constanteConstruccion)
	 * como fuente de datos 
	 * 
	 * @param string $campoTpl Nombre del campo de la TPL asociado 
	 * @param string $constanteConstruccion Identificador de la V. Sel.
	*/
	public function __construct($campoTpl, $constanteConstruccion) 
	{

		$this->nombreVentana = $campoTpl;
		$datosVentana = $this->_procesarDefinicion($constanteConstruccion);
		
		//Distinguimos entre fuente de datos DB y Class
		if(!isset($datosVentana['consulta'])) 
		{
			//Modo Class
			$this->v_defVentana['class'] = $datosVentana['class'];  
		}
		else 
		{
			//Modo DB
			if(isset($datosVentana['camposBusqueda']))								
				$this->v_defVentana['camposBusqueda'] = $datosVentana['camposBusqueda'];
	
			$posOrder = strpos(strtolower($datosVentana['consulta']),'order by');
			if($posOrder===false)
			{								
				$this->v_defVentana['consulta'] = $datosVentana['consulta'];			 	
				//Si no hay order by cogemos el primer campo de la select
				$this->v_defVentana['orden'] = ' ORDER BY 1'; 			 	
			}
			else
			{
				$this->v_defVentana['consulta'] = substr ($datosVentana['consulta'], 0, $posOrder);			 	
				$this->v_defVentana['orden'] = substr ($datosVentana['consulta'], $posOrder);			
			}		
				
			$this->v_defVentana['queryMode'] = 1;
		}
		
		//Fijamos el matching a array vacio
		$this->v_defVentana['matching']['fieldsTPL'] = array();
		$this->v_defVentana['matching']['fieldsSource'] = array();
		$this->v_defVentana['templateSource'] = '';
		$this->v_defVentana['rowsNumber'] = 8;		
		$this->v_defVentana['size'] = null;
	}//Fin de constructor

	/**
	 * Método que permite asociar los campos de la BD con los campos de la TPL. Es el encargado de asociar los
	 * resultados de la ventana seleccion con los campos de pantalla.   
	 * @access	public
	 * @param array $fieldsTPL campos que se rellenaran en la pantalla  
	 * @param array $fieldsSource  correspondencia en el resultado de la ventana seleccion
	 * 
	*/	
	public function addMatching($fieldTPL, $fieldSource) 
	{		
		$this->v_defVentana['matching']['fieldsTPL'][] = $fieldTPL;
		$this->v_defVentana['matching']['fieldsSource'][] = $fieldSource;
	}

	/**
	 * Metodo que permite fijar una tpl como patron de visualizacion del componente 
	 * @access	public
	 * @param string $templateSource 
	 * 
	*/	
	public function setTemplate($templateSource) 
	{	
		$this->v_defVentana['templateSource'] = $templateSource;
	}
	
	/**
	 * Metodo que fijar el tamaño de la ventana 
	 * @access	public
	 * @param string $height alto
	 * @param string $width ancho
	 * 
	*/	
	public function setSize($height, $width)
	{		
		$this->v_defVentana['size'] = array('height'=>$height, 'width'=>$width);
	}

	/**
	 * Metodo que fija el limite de registros mostrados en la V.Sel 
	 * @param integer $limit limite
	 * 
	*/		
	public function setLimit($limit)
	{
		$this->limitQuery = $limit;
	}
	
	/**
	 * Devuelve el límite máximo
	 * @return integer
	 */
	private function getLimit()
	{		
		return $this->limitQuery; 
	} 

	/**
	 * Establece el número de filas por página a visualizar en la V.Sel
	 * @param integer $number número de filas a visualizar
	 * 
	*/			
	public function setRowsNumber($number)
	{		
		$this->v_defVentana['rowsNumber'] = $number;
	}
	
	/**
	 * Devuelve el tamaño de la ventana 
	 * @return	tamaño de ventana
	*/	
	public function getWindowSize()
	{		
		return $this->v_defVentana['size'];
	}	

	/**
	 * Permite fijar un dsn alternativo al del panel donde se invoca la V. Sel.
	 * @access	public
	 * @param string $dsn 
	 * 
	*/	
	public function setDSN($dsn='')
	{		
		$this->v_defVentana['conexion'] = $dsn;
	}
	
	/**
	* Establece la dependencia entre campos de la TPL y de la V.Sel.
	* 
	* Si en una V.Sel el resultado de la búsqueda depende del valor de otros
	* campos en la TPL, es decir, se tiene en cuenta el valor de ese campo del
	* panel/tpl origen para condicionar la búsqueda de la V. Sel, lo indicamos
	* con este método.
	* El metoido recibe como parámetro dos arrays correlativos (el orden en cada
	* uno de ellos indica la correpsondencia) de forma que 
	* La dependencia puede ser "fuerte" ($tipoDependencia = 0, valor por defecto)
	* o "debil" (1). La dependencia fuerte implica que siempre se incluya el
	* valor del campo dependiente (valor del campo en la TPL origen) como parte
	* de las condiciones de búsqueda. En el caso de dependencia débil, si el
	* campo dependiente no tiene valor no se incluye la condición (se ignora).
	*  
	* @param array $listaCamposTpl	array lista de campos de la tpl
	* @param array $listaCamposBd	array lista de campos BD
	* @param int $tipoDependencia	integer	[0 o vacío] dependencia fuerte, [1] fuerte
	*/		
	public function setDependence($listasCamposTpl, $listasCamposBD, $tipoDependencia=0)
	{
		$dependencia = array();		
		if (
				(!is_array($listasCamposTpl)) or (!is_array($listasCamposBD))
				|| (count($listasCamposTpl)!=count($listasCamposBD))
		)
		{
			throw new gvHidraException(
					'Error en la introducción de la dependencia de la ventana de
					Selección que actua sobre el campo TPL '
					.$this->nombreVentana.
					' . Recuerde que debe introducir dos listas (arrays).'
			);
		}
		$i=0;
		if ($tipoDependencia<0 || $tipoDependencia>1)
		{
			throw new gvHidraException(
					'Error en la introducción de la dependencia de la ventana de
					 Selección que actua sobre el campo TPL '
					.$this->nombreVentana.
					' . Recuerde el tipo de dependencia puede ser 0 o 1.');
		}
		for ($i=0;$i<count($listasCamposBD);$i++)
			$dependencia[$listasCamposTpl[$i]] = $listasCamposBD[$i];
		  		
		$this->v_defVentana['dependencia'] = $dependencia;
		$this->v_defVentana['tipoDependencia'] = $tipoDependencia;
	}//Fin de setDependence

	/**
	* Cambia el modo de queryMode
	* @param int $modo integer	[Valores 0, 1 y 2]
	*/		
	public function setQueryMode($modo)
	{
		if (!is_numeric($modo) or intval($modo)!=$modo or $modo < 0 or $modo > 2)
			throw new gvHidraException('Error en la introducción del queryMode de la ventana de Selección que actua sobre el campo TPL '.$this->nombreVentana.' . Recuerde que debe introducir un entero entre 0 y 2.');
		$this->v_defVentana['queryMode'] = $modo;
	}

	/**
	* Devuelve el nombre de la ventana Selección. 
	* Sólo para uso interno de Negocio.	
	* @return string nombre de la V.Sel
	*/	
	public function getName()
	{
		return $this->nombreVentana;		
	}//Fin de getName

	
	/**
	* Método que devuelve el array de definición de una ventana de selección
	* Sólo para uso interno de Negocio.
	* @return array
	*/	
	public function getDescripcionVentana()
	{
		return $this->v_defVentana;
	}//Fin de getDescripcionVentana
	
	/**
	* Obtiene la fuente origen de los datos de la V. Sel. 
	* ventana de selección.
	*/	
	public function _procesarDefinicion($constanteConstruccion)
	{
		$conf = ConfigFramework::getConfig();
		$datosVentana = $conf->getDefVS($constanteConstruccion);
		if($datosVentana==-1)
		    throw new gvHidraException('Error: La consulta especificada para la ventana de selección no es valida. Se trata de la ventana de la Tpl '.$this->nombreVentana);
		
		return $datosVentana;
	}//Fin de _procesarDefinicion


	/**
	 * USado en gvHidraForm_DB para atender a la acción que corresponde con el inicio de una ventana selección.
	 * Esta acción es abrirVentanaSeleccion.	
	 * @param array $datosVentanaSeleccion 	array que contiene los datos de interfaz (nombre del campo, claseManejadora, ...) de dicha ventana selección.
	*/
	public function abrirVentanaSeleccion(& $datosVentanaSeleccion) 
	{
		//Debug:Indicamos que ejecutamos la consulta
		IgepDebug::setDebug(DEBUG_IGEP,'gvHidraSelectionWindow: Abriendo Ventana Selección '.$this->getName());

		//Esta función se encargará de pasar los parámetros necesarios a la ventana de selección	
		//Vaciamos la anterior búsqueda
		IgepSession::borraPanel('ventanaSeleccion');

		if(IgepSession::existePanel($datosVentanaSeleccion['claseManejadora']))
		{
			//Indicamos que se deber recalcular la dependencia en caso de existir
			$this->v_defVentana['activeDependence'] = null;
			
			//Guardamos en la SESSION los datos	
			$panelVentanaSeleccion['nomForm'] = $datosVentanaSeleccion['nomForm'];
			$panelVentanaSeleccion['filaActual'] = $datosVentanaSeleccion['filaActual'];
			$panelVentanaSeleccion['panelActua'] = $datosVentanaSeleccion['panelActua'];		
			$panelVentanaSeleccion['nomCampo'] = $datosVentanaSeleccion['nomCampo'];
			$panelVentanaSeleccion['claseManejadora'] = $datosVentanaSeleccion['claseManejadora'];				
			$panelVentanaSeleccion['actionOrigen'] = $datosVentanaSeleccion['actionOrigen'];
			IgepSession::_guardaPanelIgep('ventanaSeleccion',$panelVentanaSeleccion);			
		}		
		return 0;		
	}//function abrirVentanaSeleccion

	/**
	* Método que se utiliza desde gvHidraForm_DB para atender a la acción que corresponde con la búsqueda en una Ventana Selección.
	* Esta acción es buscarVentanaSeleccion.
	* @acces private
	* @param array $datosVentanaSeleccion Es un array que contiene los datos de interfaz de la ventana de selección.
	* @param IgepError $obj_errorNegocio Referencia a la variable de error de Negocio para poder notificar cualquier tipo de error.
	*/	 
	public function buscarVentanaSeleccion(& $datosVentanaSeleccion)
	{
		//Debug:Indicamos que ejecutamos la consulta
		IgepDebug::setDebug(DEBUG_IGEP,'gvHidraSelectionWindow: Buscando en Ventana Selección '.$this->getName());    

		if (IgepSession::existePanel($datosVentanaSeleccion['claseManejadora'])){							

			//Creamos la Select con los parámetros correspondientes.
			$defVentanaSeleccion = $this->getDescripcionVentana();

		    //Para evitar la inyección de SQL
		    $valor = strtolower($datosVentanaSeleccion['valor']); 
		    $valor = str_replace('insert into ','',$valor);
		    $valor = str_replace('delete ','',$valor);
		    $valor = str_replace('update ','',$valor);
		    $valor = str_replace('create ','',$valor);
		    $valor = str_replace('alter ','',$valor);
		    $valor = str_replace('drop ','',$valor);
		    $valor = str_replace('grant ','',$valor);
			$datosVentanaSeleccion['valor'] = $valor;
			
			//Comprobamos si se trata de un componente fuente de BBDD o a través de Clase
			$panelVentanaSeleccion = null;
			
			if(empty($defVentanaSeleccion['consulta']))
			{				
				$resultado = $this->searchClassSource($datosVentanaSeleccion, $panelVentanaSeleccion);
			}
			else
			{
				$resultado = $this->searchDBSource($datosVentanaSeleccion, $panelVentanaSeleccion);
			}
			//Devolvemos valores
			$panelVentanaSeleccion['nomForm'] = $datosVentanaSeleccion['nomForm'];
			$panelVentanaSeleccion['filaActual'] = $datosVentanaSeleccion['filaActual'];
			$panelVentanaSeleccion['panelActua'] = $datosVentanaSeleccion['panelActua'];		
			$panelVentanaSeleccion['nomCampo'] = $datosVentanaSeleccion['nomCampo'];
			$panelVentanaSeleccion['resultado'] = $resultado;
			$panelVentanaSeleccion['claseManejadora'] = $datosVentanaSeleccion['claseManejadora'];
			$panelVentanaSeleccion['matching'] = $defVentanaSeleccion['matching'];
			$panelVentanaSeleccion['templateSource'] = $defVentanaSeleccion['templateSource'];
			$panelVentanaSeleccion['rowsNumber'] = $defVentanaSeleccion['rowsNumber'];
			$panelVentanaSeleccion['actionOrigen'] = $datosVentanaSeleccion['actionOrigen'];
			if (count($resultado) >= $this->limitQuery)
				$panelVentanaSeleccion['showInfoRowsExceeded'] = 1;
			else
				$panelVentanaSeleccion['showInfoRowsExceeded'] = 0;
			IgepSession::_guardaPanelIgep('ventanaSeleccion',$panelVentanaSeleccion);
		}
		return 0;
	} //Fin de buscarVentanaSeleccion
	
	private function searchClassSource($datosVentanaSeleccion, & $panelVentanaSeleccion)
	{		
		$defVentanaSeleccion = $this->getDescripcionVentana();		
		//Creamos la instancia de la clase y lanzamos el método search.
		
		$class = $defVentanaSeleccion['class'];
		if(!class_exists($class))
		{
			throw new gvHidraException (
					'Error VS '.$this->getName().
					': la clase definida como fuente de datos no existe. Revise
					su definión y su inclusión. Concretamente la clase '.$class
			);
		}
		//Recogemos el valor
		$valor = $datosVentanaSeleccion['valor'];
		
		//Recogemos la dependencia		
		$dependence = null;
		$dependenceType = $defVentanaSeleccion['tipoDependencia'];

		if($defVentanaSeleccion['dependencia']!=''){
		    
	    	if (!isset($defVentanaSeleccion['activeDependence'])) {
				$dependence = $this->createClassDependence($datosVentanaSeleccion);
				$this->v_defVentana['activeDependence'] = $dependence;
			}
			else {
				$dependence = $defVentanaSeleccion['activeDependence'];
			}
		}
		
		//Creamos la instancia de la clase y llamamos al metodo
		try {
			$object = new $class;
			$resultado = $object->search($valor,$dependence,$dependenceType);
		}
		catch(Exception $e){
			IgepDebug::setDebug('Error en gvHidraSelectionWindow '.$this->getName().' Mensaje: '.$e->getMessage());
		}
		
		//Si el resultado es vacio, mostramos mensaje en pantalla
		if(count($resultado)==0) {

			$obj_mensaje = new IgepMensaje('IGEP-10');
			$panelVentanaSeleccion['mensaje'] = $obj_mensaje; 
		}
		
		$fieldsNeeded = $defVentanaSeleccion['matching']['fieldsSource'];
    
		//Comprobamos que en el array tenemos los valores del matching
		foreach ($fieldsNeeded as $field)
			if(count($resultado)>0 AND !array_key_exists($field,$resultado[0]))
				throw new gvHidraException('Error WindowSelecion '.$this->getName().': el resultado obtenido no contiene referencia al campo '.$field.' que está en el matching');
		
		return $resultado; 
	}
	

	/**
	 * Obtiene el DSN de conexión correspondiente (el del panel o el propio de la VS), parsea la SQL y la ejecuta
	 * @param $datosVentanaSeleccion	array	Vector asociativo con información de contexto de  la invocación de la V.Sel.
	 * @param $panelVentanaSeleccion 	array	
	 */
	private function searchDBSource($datosVentanaSeleccion, &$panelVentanaSeleccion)
	{		
		$defVentanaSeleccion = $this->getDescripcionVentana();
		
		//Obtenemos el DSN sobre el que se quiere trabajar.
		//Puede ser particular o puede la que tiene el panel por defecto				
		if($defVentanaSeleccion['conexion']=='')
		{
		    $claseManejadora = IgepSession::damePanel($datosVentanaSeleccion['claseManejadora']);
		    $dsn = $claseManejadora->getDSN();
		}
		else
		{
		    $dsn = $defVentanaSeleccion['conexion'];
		}

		//Hacemos la nueva conexión y lanzamos la consulta.
		$nuevaConexion = new IgepConexion($dsn);
		$valor = $datosVentanaSeleccion['valor'];
		
		//Creamos la consulta		
		//Construimos la parte de la búsqueda.
		$whereBusqueda = array();
		if ($valor != '')
		{
			//escapamos el criterio de búsqueda
			$nuevaConexion->prepararOperacion($valor, TIPO_CARACTER);				
			$qm = $defVentanaSeleccion['queryMode'];
			if(isset($defVentanaSeleccion['camposBusqueda']))
			{
				foreach ($defVentanaSeleccion['camposBusqueda'] as $campo)
				{
					$whereBusqueda[] = $nuevaConexion->unDiacriticCondition($campo, $valor, $qm, false);
				}
			}
			$concatenacion = $this->_concatenarCamposSelect($dsn, $defVentanaSeleccion['consulta']);
			if ($concatenacion != '')
			{
				$whereBusqueda[] = $nuevaConexion->unDiacriticCondition($concatenacion, $valor, $qm, false);
			}
		}
		$str_where_a = array();

		//Comprobamos si tenemos que crear la dependencia o no
		$datosInstanciaActual = $this->getDescripcionVentana();
		if($datosInstanciaActual['dependencia']!='')
		{		
		    if (!isset($datosInstanciaActual['activeDependence']))
		    {
				$dependencia = $this->createDBDependence($datosVentanaSeleccion,$nuevaConexion);					
				if(!empty($dependencia))
					$str_where_a[] = $dependencia;
				$this->v_defVentana['activeDependence'] = $dependencia;						
		    }
		    else
		    {			    	
		    	$dependencia = $datosInstanciaActual['activeDependence'];
		    	if(!empty($dependencia))
		    		$str_where_a[] = $dependencia;
		    }
		}
		
		//Componemos la Where y lanzamos la consulta (Importante: el límite se tiene que calcular depues de la conexion)
		if (count($whereBusqueda) > 0)
			$str_where_a[] = "(".implode(' OR ', $whereBusqueda).")";
		$str_where = implode(' and ', $str_where_a);
		$posWhere = strpos(strtolower($defVentanaSeleccion['consulta']),'where');			
		if ($posWhere===false)
		    $consulta = $str_where!=''? ' WHERE '.$str_where : '';
    	else
		    $consulta = $str_where!=''? ' AND '  .$str_where : ' ';
		    // el limite no se pone correctamente para oracle cuando la select inicial tiene where
		    // por lo que se pone un espacio al final para que no ponga where ya que la consulta
		    // inicial si tiene where
		$limite = $nuevaConexion->construirLimite($consulta,$this->getLimit());
		$consulta = $defVentanaSeleccion['consulta'].$consulta.' '.$defVentanaSeleccion['orden'].' '.$limite;
		$resultado = $nuevaConexion->consultar($consulta);
		
		//TODO: Cambiar gestión de errores
		global $g_error;
    	if(!isset($g_error)) 
			$g_error = new IgepError();	
		$obj_errorNegocio = & $g_error;		
		
		if($obj_errorNegocio->hayError()) //Si hay error...
		{
			$obj_mensaje = new IgepMensaje('IGEP-15',array('La consulta pertenece al objeto gvHidraSelectionWindow '.$datosVentanaSeleccion['nomCampo']));				
			$panelVentanaSeleccion['mensaje'] = $obj_mensaje;
			$resultado = array();
			return $resultado;
		}
		
		$numRows = count($resultado);
		if($numRows==0) //Si no hay registros devueltos
		{
			$obj_mensaje = new IgepMensaje('IGEP-10');
			$panelVentanaSeleccion['mensaje'] = $obj_mensaje;
		}
		elseif ($numRow >= $this->getLimit()) //Si hay más tuplas que el limite de resultado
		{	
			$panelVentanaSeleccion['showInfoRowsExceeded'] = 1;
		}		
		return $resultado;
	}// Fin 


	/**
	* Método que obtiene la lista de campos en la consulta concatenados y separados por un espacio.
	* Devuelve ese string preparado para añadir una condicion de filtro al where, o cadena vacia si no es
	* capaz de descifrar los elementos de la consulta.
	* @param $dsn
	* @param $consulta string SQL 
	*/	
	public function _concatenarCamposSelect($dsn, $consulta)
	{
		$patron = <<<xxx
		 /\bselect\b
		  ((?U)(.+)   # para que encuentre el primer from, y no el ultimo
		  \bfrom\b)
		  .+		  # cualquier cosa despues del from, incluidos retornos
		 /isx
xxx;
		if (preg_match($patron, $consulta, $out) == 0) {
			IgepDebug::setDebug(ERROR,'gvHidraSelectionWindow: No encontrada estructura de select');    
			return '';
		}
		$patron2 = <<<xxxx
			/(\s*\b\w+\b\(				  # funciones
			      ((?1)(,(?1))*)?		   # parametros de forma recursiva
					    \)\s* 
			  |\s*'[^']*'\s*		      # constantes texto (pongo ' para que coloree bien el eclipse)
			  |\s*\b\w+\b(\.\b\w+\b)?\s*    # tabla.columna o columna o constantes numericas
			  |\s*\(
					(?1)([-\*\+\/](?1))*
				  \)\s*				     # expresiones n-arias deben ir entre parentesis
			 )
			 \s*(\bas\b\s*("[^"]+"|\w+))?			# alias (pongo " para que coloree bien el eclipse)
			 \s*(,\s*)?
			/isx
xxxx;
		if (preg_match_all($patron2, $out[2],$out2) == 0) {
			IgepDebug::setDebug(ERROR,'gvHidraSelectionWindow: No encontrada estructura de lista de campos en la select');    
			return '';
		}
		$condicion = '';
		$dbms = IgepDB::creaDBMS($dsn);
		foreach ($out2[1] as $val)
			if (isset($val)) {
				$val = $dbms->toTextForVS($val);
				if ($condicion == '')
					$condicion = $val;
				else
					$condicion = ' '.$dbms->concat($dbms->concat($condicion, "' '"), $val).' ';
			}
		return $condicion;    
	}//Fin de _concatenarCamposSelect
	
	
	private function createDBDependence($datosVentana, $conexion) 
	{
		$ventanasSeleccionActivas = $this->getDescripcionVentana();			
		$dependencia = $ventanasSeleccionActivas['dependencia'];
		$tipoDependencia = $ventanasSeleccionActivas['tipoDependencia'];
		$str_dependencia = '';					
		//Obtenemos la descripcion de los campos para posibles rectificaciones
		$fieldsDescription = IgepSession::dameVariable($datosVentana['claseManejadora'],'v_descCamposPanel');
		foreach($dependencia as $campoTpl => $campoBD) 
		{			
			//Obtenemos el nombre del campo en la TPL
			$nombreCampo = $this->getRealName($datosVentana['nomCampo'],$campoTpl,$datosVentana['nombreCompleto'],$fieldsDescription[$campoTpl]['component']);
			
			if(($tipoDependencia==0)OR(!empty($_REQUEST[$nombreCampo])))
			{
				if ($str_dependencia !='')
					$str_dependencia.= ' AND ';
				if(!empty($_REQUEST[$nombreCampo])) 
				{
					//Tenemos que transformar la informacion de pantalla dependiendo del tipo que sea
					$valor = $_REQUEST[$nombreCampo];
					IgepComunicacion::transform_User2FW($valor,$fieldsDescription[$campoTpl]['tipo']);
					$conexion->prepararOperacion($valor,$fieldsDescription[$campoTpl]['tipo']);
					$str_dependencia.= $campoBD."= '".$valor."' ";
				}
				else
					$str_dependencia.= $campoBD." is null ";
			}
		}
		return $str_dependencia;			
	}

	private function createClassDependence($datosVentana)
	{
		$ventanasSeleccionActivas = $this->getDescripcionVentana();			
		$dependencia = $ventanasSeleccionActivas['dependencia'];
		
		$dependence = array();					
		//Obtenemos la descripcion de los campos para posibles rectificaciones
		$fieldsDescription = IgepSession::dameVariable($datosVentana['claseManejadora'],'v_descCamposPanel');
		foreach($dependencia as $campoTpl => $campoSource)
		{	
			//Obtenemos el nombre del campo en la TPL
			$nombreCampo = $this->getRealName($datosVentana['nomCampo'],$campoTpl,$datosVentana['nombreCompleto'],$fieldsDescription[$campoTpl]['component']);
			if($_REQUEST[$nombreCampo]!==null)
			{
				//Tenemos que transformar la informacion de pantalla dependiendo del tipo que sea
				$valor = $_REQUEST[$nombreCampo];
				IgepComunicacion::transform_User2FW($valor,$fieldsDescription[$campoTpl]['tipo']);
				$dependence[$campoSource] = $valor;					
			}
		}
		return $dependence;			
	}


	
	/**
	* Metodo que obtiene el nombre de un campo en tpl a partir de un nombre de campo->nomCampo y su homologo en la tpl->nombreCampoCompleto
	*/
	private function getRealName($nomCampo,$campoTpl,$nombreCompleto,$component)
	{
		$nombreCampo = str_replace($nomCampo,$campoTpl,$nombreCompleto);
		//Rectificacion sobre campos dependientes
		if($component=='CheckBox')
		{
			//Si es un checkbox, en la insercion es hins.
			if(strpos($nombreCampo,'ins')!==false && substr($nombreCampo,0,1)!='h')
				$nombreCampo='h'.$nombreCampo;
		}
		return $nombreCampo;
	}
	
}//Fin gvHidraSelectionWindow


?>