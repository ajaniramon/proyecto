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
 * IgepComunicaUsuario clase que aisla métodos de la clase IgepComunicacion 
 * simplificando la interfaz con el usuario (el desarrollador).
 *  
 * <p> Se proporcionará una instancia de esta clase en los metodos abstractos que el
 * programador puede utilizar para cambiar el comportamiento de las acciones genericas
 * del framework. También en el metodo correspondiente a las acciones particulares.
 * </p>
 * 
 * <p> Con los metodos proporcionados se podran manejar los datos obtenidos desde la REQUEST
 * de una forma racional (por campo, por tupla o por matriz de datos). Toda esta informacion
 * viene organizada por operacion; de modo que se proporcionara la informacion segun la accion
 * que este ejecutandose. 
 * </p> 
 *
 * @version	$Id: IgepComunicaUsuario.php,v 1.58 2011-01-20 11:48:43 vnavarro Exp $
 * @author David: <pascual_dav@gva.es> 
 * @author Keka: <bermejo_mjo@gva.es>
 * @author Vero: <navarro_ver@gva.es>
 * @author Raquel: <borjabad_raq@gva.es> 
 * @author Toni: <felix_ant@gva.es>
 * @package	gvHIDRA
 */
class IgepComunicaUsuario {
 
	/*
	* Instancia de IgepComunicacion
	* @access private
	* @var object	IgepComunicacion
	*/
	var $comunica;

	/*
	* Datos utilizadaso en las inseciones para las listas
	* @access private
	* @var array	datosPreinsertados
	*/
	var $datosPreinsertados;
 
	/*
	* Coleccion (array) de las listas definidas en el panel
	* @access private
	* @var array	listasPanel
	*/
	var $listasPanel;

	/*
	* Objeto de la clase IgepMensaje que se creará en el caso de que el programador haga uso del método showMessage
	* @access private
	* @var objeto	obj_mensaje
	*/ 
	var $obj_mensaje = null;


	/**
	* Constructor. Recibe como parámetro una instancia viva de la clase
	* IgepComunicacion	 
	*
	* @access	public
	* @param	object	$comunica
	*/	 
	public function __construct(& $comunica, & $datosPreinsertados, & $listasPanel) {

		$this->comunica = & $comunica;
		$this->datosPreinsertados = & $datosPreinsertados;
		$this->listasPanel = & $listasPanel;
	} 
 
 
	/**
	* reset reinicializa el acceso a datos
	*
	* @access	public
	* @param	$parametroOperacion	Origen de datos (datos para insertar, para modificar, para borrar) 
	* @return	none
	*/	
	public function reset($parametroOperacion='') {

		$this->comunica->reset($parametroOperacion);
	}


	/**
	* setOperation Fija la operación que va a ser origen de los datos
	*
	* @access	public
	* @param	$parametroOperacion	Origen de datos (datos para insertar, para modificar, para borrar) 
	* @return	none
	*/	
	public function setOperation($parametroOperacion) {
		
		$this->comunica->setOperation($parametroOperacion);	 	
	}

	/**
	* getOperation Permite cual es el origen de los datos actual
	*
	* @access	public
	* @return	string
	*/	 
	public function getOperation() {

		return $this->comunica->getOperation();
	}

	/**
	* setIndex Cuando se trabaja con un conjunto de fichas múltiple, se utiliza para fijar la fila
	* (registro/tupla del conjunto) sobre la que estamos trabajando, por defecto, es la ficha que
	* se está visializando (esta activa)
	*
	* @access	public
	* @param	integer $indice	Indice de 0..nRegs que indica la tuplas 
	* @return	none
	*/	
	public function setIndex($indice) {	

		$this->comunica->int_filaActual = $indice; 	
	} 
 
	/**
	* getIndex Devuelve el valor del indice del cursor sobre el origen de datos actual 
	*
	* @access	public
	* @param	$parametroOperacion	Origen de datos (datos para insertar, para modificar, para borrar)
	* @return	integer
	*/	 
	public function getIndex($parametroOperacion='') {

		return $this->comunica->getIndex($parametroOperacion);
	}


	/**
	* currentTupla Este método devuelve el registro activo sobre el origen de datos actual (cursor)
	*
	* @access	public
	* @param	$parametroOperacion	el tipo de operación, si no se indica se coge el fijado para la instancia
	* @return	array
	*/	  
	public function currentTupla ($parametroOperacion=''){
	
		return $this->comunica->currentTupla($parametroOperacion);
	}


	/**
	* getValue devuelve el campo indicado como párametro del registro activo sobre el
	* origen de datos actual (cursor)
	*
	* @access	public
	* @param	string	$nombreCampo	Nombre del campo
	* @param	$parametroOperacion	Origen de datos (datos para insertar, para modificar, para borrar) 
	* @return	mixed
	*/ 
	public function getValue($nombreCampo, $parametroOperacion='') {

 		return $this->comunica->getCampo($nombreCampo, $parametroOperacion);
	}


	/**
	* getOldValue devuelve el valor antiguo del campo indicado como párametro del registro activo
	* 
	* @access	public
	* @param	string	$nombreCampo	Nombre del campo 
	* @return	mixed 
	*/
	public function getOldValue($nombreCampo) {
 		
 		return $this->comunica->getOldValue($nombreCampo);
 	}

 
	/**
	* setSelected fija el valor de una lista
	*
	* @access	public
	* @param	string	$nombreCampo	Nombre del campo
	* @param	string	$valorCampo		Valor del campo
	* @param	$parametroOperacion	Origen de datos (datos para insertar, para modificar, para borrar) 
	* @return	mixed
	*/ 
	public function setSelected($nombreCampo, $valorCampo, $parametroOperacion='') {
	
		return $this->comunica->setCampo($nombreCampo, $valorCampo, $parametroOperacion);
	}


	/**
	* setChecked método que permite modificar el valor del un check.
	* @param string name	nombre del check
	* @param boolean check	Checked si o no
	* @param string operacion	Operacion sobre la que se quiere actuar
	* @return none
	*/
	public function setChecked($name, $check, $parametroOperacion='') {

		//Seleccionamos el valor dependiendo si esta seleccionado
		$desc = $this->comunica->descCampoPanel[$name];
		($check)?$value=$desc['valueChecked']:$value=$desc['valueUnchecked'];
		
		return $this->comunica->setCampo($name, $value, $parametroOperacion);	
	}


	/**
	* isChecked método que permite saber si un check esta marcado o no
	* @param string name	nombre del check
	* @param string operacion	Operacion sobre la que se quiere actuar
	* @return boolean
	*/
	public function isChecked($name, $parametroOperacion='') {

		//Devolvemos true o false dependiendo del valor del check
		$value = $this->comunica->getCampo($name, $parametroOperacion);
		$desc = $this->comunica->descCampoPanel[$name];
		($value==$desc['valueChecked'])?$result = true: $result = false;
		
		return $result;	
	}


	/**
	* setValue fija el valor pasado como parámetro sobre el campo indicado como párametro del
	* registro activo sobre el origen de datos actual (cursor)
	*
	* @access	public
	* @param	string	$nombreCampo	Nombre del campo
	* @param	string	$valorCampo		Valor del campo
	* @param	$parametroOperacion	Origen de datos (datos para insertar, para modificar, para borrar) 
	* @return	mixed
	*/ 
	public function setValue($nombreCampo, $valorCampo, $parametroOperacion='') {

		return $this->comunica->setCampo($nombreCampo, $valorCampo, $parametroOperacion);
	}


	/**
	* nextTupla Avanza la posición del cursor sobre el origen de datos
	* actual (cursor) y devuelve el registro/tupla correspondiente
	*
	* @access	public
	* @param	string	$nombreCampo	Nombre del campo
	* @param	string	$valorCampo		Valor del campo
	* @param	$parametroOperacion	Origen de datos (datos para insertar, para modificar, para borrar) 
	* @return	mixed
	*/   
	public function nextTupla($parametroOperacion='') {

		return $this->comunica->nextTupla($parametroOperacion);
	}


	/**
	* fetchTupla devuelve el registro/tupla correspondiente y 
	* avanza la posición del cursor sobre el origen de datos
	*
	* @access	public
	* @param	$parametroOperacion	Origen de datos (datos para insertar, para modificar, para borrar) 
	* @return	mixed
	*/
	public function fetchTupla($parametroOperacion='') {
	
		return $this->comunica->fetchTupla($parametroOperacion);
	}


	/**
	* setTupla sustituye el registro actual de la fuente de datos
	* prestablecida por el registro/tupla (array asociativo) pasado
	* como parámetro
	*
	* @access	public
	* @param	string	$nombreCampo	Nombre del campo
	* @param	string	$valorCampo		Valor del campo
	* @param	$parametroOperacion	el tipo de operación, si no se indica se coge el fijado para la instancia 
	* @return	mixed
	*/
	public function setTupla($tupla, $parametroOperacion='') {

	 	return $this->comunica->setTupla($tupla, $parametroOperacion);
	}


	/**
	* getAllTuplas obtiene la matriz de registros correspondiente 
	* al origen de datos pasado como argumento o prestablecido
	*
	* @access	public
	* @param	$parametroOperacion	Origen de datos (datos para insertar, para modificar, para borrar) 
	* @return	mixed
	*/
	public function getAllTuplas($parametroOperacion='') {
		
		return $this->comunica->getAllTuplas($parametroOperacion);
	}


	/**
	* getAllTuplasAntiguas obtiene la matriz de registros original correspondiente 
	* al origen de datos pasado como argumento o prestablecido
	*
	* @access	public
	* @param	$parametroOperacion	Origen de datos (datos para insertar, para modificar, para borrar) 
	* @return	mixed
	*/
	public function getAllTuplasAntiguas($parametroOperacion='') {

		return $this->comunica->getAllTuplasAntiguas($parametroOperacion);
	}
	public function getAllOldTuplas($parametroOperacion='') {

		return $this->comunica->getAllTuplasAntiguas($parametroOperacion);
	}


	/**
	* setAllTuplas establece obtiene la matriz de registros correspondiente 
	* al origen de datos pasado como argumento o prestablecido
	*
	* @access	public
	* @param	matriz	$vTuplas	Conjunto de tuplas/registros a asignar
	* @param	$parametroOperacion	Origen de datos (datos para insertar, para modificar, para borrar) 
	* @return	mixed
	*/ 
	public function setAllTuplas($vTuplas, $parametroOperacion='') {
	
		return $this->comunica->setAllTuplas($vTuplas, $parametroOperacion);
	}


	/**
	* isEmpty indica si el origen de datos prestablecido esta vacio o no
	* 
	* @access	public
	* @param	$parametroOperacion	Origen de datos (datos para insertar, para modificar, para borrar) 
	* @return	boolean
	*/
	public function isEmpty($parametroOperacion='') {

		return $this->comunica->isEmpty($parametroOperacion);
	}


	/**
	* getList obtiene la estructura de la lista  
	* 
	* @access	public
	* @param	$nombreCampo nombre del campo de la lista.
	* @param	$parametroOperacion	Origen de datos (datos para insertar, para modificar, para borrar). 
	* @return	_IgepEstructuraLista
	*/
	public function getList($nombreCampo, $parametroOperacion=''){

		//Comprobamos si es una lista
		if(get_class($this->listasPanel[$nombreCampo])!='gvHidraList')
			return null;

		$objLista = & $this->listasPanel[$nombreCampo];
		//Recogemos el valor del campo
		$valorSeleccionado = $this->getValue($nombreCampo, $parametroOperacion);

		//Montamos la lista
		//Si no tiene dependencia cogemos el valor de camposPreInsertados
		if(!$objLista->hayDependencia()) {	
			
			$listaResultado = $this->datosPreinsertados[$nombreCampo];
		}
		else {

			//Tenemos que construir la dependencia
			$tupla = $this->comunica->currentTupla($parametroOperacion);
			$listaResultado = $objLista->construyeLista($tupla);
		}

		$listaResultado['seleccionado'] = $valorSeleccionado;
		//Modificar aqui
		$objListaStruc = new _IgepEstructuraLista($listaResultado); 

		return $objListaStruc;  
	}


	/**
	* setList fija el contenido de una lista  
	* 
	* @access	public
	* @param	$nombreCampo nombre del campo de la lista.
	* @param	$objListaStruc estructura de la lista
	* @param	$parametroOperacion	Origen de datos (datos para insertar, para modificar, para borrar). 
	* @return	none
	*/
	public function setList($nombreCampo, $objListaStruc, $parametroOperacion= '') {

		$v_lista = $objListaStruc->getEstructuraListaIgep();
		$this->comunica->setList($nombreCampo, $v_lista, $parametroOperacion); 	
	}
  
  
	/**
	* getFileInfo metodo que devuelve para una tupla dada la información de un campo de tipo 'FILE'
	* que se ha subido al servidor.
	* @param string nombreCampo Nombre del campo FILE del que se quiere obtener la información
	* @param string parametroOperacion Indica la operación sobre la que se quiere la tupla
	* @return array
	*/
	function getFileInfo($nombreCampo, $pametroOperacion =''){

		return $this->comunica->getFileInfo($nombreCampo, $pametroOperacion);
	}


	/**
	* Método que dada una tupla, fija el color para poder ser representado en una tabla de gvHidra
	* 
	* @param array row tupla a la que se le quiere dar color.
	* @param string color color que se le va a dar a la tupla.
	* @return none
	*/  
	public function setRowColor(&$row,$color){
		
		if(!is_array($row))
			die('setColorRow_Error: el parámetro row no es un array');
		$row['__gvHidraRowColor'] = $color;
	}
  
 
  /* --------------------------FUNCIONES AUXILIARES -------------------------- */


    /**
    * @access private
    * Método privado que prepara los datos antes de realizar una operacion en la BD.
    */
    function _prepararOperacion($conexion,$v_desCampos){
        
        $m_datos = $this->getAllTuplas();
        $conexion->prepararOperacion($m_datos,$v_desCampos);
        $this->setAllTuplas($m_datos);
        $this->reset();
        if($this->getOperation()=='actualizar') {
            $m_datosAnt = $this->getAllTuplasAntiguas();
            $conexion->prepararOperacion($m_datosAnt,$v_desCampos);
            $this->comunica->m_datos_antiguosTpl = $m_datosAnt;
            $this->reset();
        }
    }


    /**
    * prepararPresentacion este método prepara los datos que vienen de estado FW y los pasa a estado presentación 
    * Devuelve el resultado por referencia y como valor de retorno.
    *
    * @access   public
    * @param    any $a_parametros
    * @param    any $a_tipo
    * @param    any $a_decimales: usado cuando convertimos valor individual
    * @return   mixed
    */   
    static public function prepararPresentacion(& $a_parametros, $a_tipo, $a_decimales='2') {                

		if($a_decimales==='')
			$a_decimales='2';
		
		if (!is_array($a_parametros) and $a_parametros!=='' and $a_tipo!='') {
			// le doy estructura de vector para no repetir el codigo
			$vector = false;
			$a_parametros = array(array('col'=>$a_parametros,),);
			$a_tipo = array('col'=>array('tipo'=>$a_tipo, 'parteDecimal'=>$a_decimales),);
		} else
			$vector = true;		
		if (is_array($a_tipo)) {
			$transformer = new IgepTransformer();
			$carfw = ConfigFramework::getNumericSeparatorsFW();
			$caruser = ConfigFramework::getNumericSeparatorsUser();
			$transformer->setDecimal($carfw['DECIMAL'],$caruser['DECIMAL'],$carfw['GROUP'],$caruser['GROUP']);
			
			$fechafw = ConfigFramework::getDateMaskFW();
			$fechauser = ConfigFramework::getDateMaskUser();        
			$transformer->setDate($fechafw,$fechauser);

			foreach($a_parametros as $fila => $tupla)
				foreach($a_tipo as $campo => $descTipo)
					if (isset($tupla[$campo])) {
						$tipo_efectivo = (empty($descTipo['tipo'])? TIPO_CARACTER: ($descTipo['tipo']==TIPO_ENTERO? TIPO_DECIMAL: $descTipo['tipo']));
						if ($tipo_efectivo == TIPO_DECIMAL)
							$a_parametros[$fila][$campo] = $transformer->expandExponent($a_parametros[$fila][$campo], $carfw['DECIMAL'], $carfw['GROUP']);
						if (isset($descTipo['tipo']) && ($descTipo['tipo'] == TIPO_DECIMAL))
							$a_parametros[$fila][$campo] = $transformer->decimalPad($a_parametros[$fila][$campo], $descTipo['parteDecimal']);
						if(empty($a_parametros[$fila][$campo]))
							continue;
						elseif (($tipo_efectivo == TIPO_FECHA or $tipo_efectivo == TIPO_FECHAHORA) and is_object($a_parametros[$fila][$campo]))
							$a_parametros[$fila][$campo] = $a_parametros[$fila][$campo]->format($fechauser.($tipo_efectivo==TIPO_FECHAHORA? ' H:i:s':''));
						else
							$a_parametros[$fila][$campo] = $transformer->process($tipo_efectivo, $a_parametros[$fila][$campo]);
					}
		}
        if (!$vector)
	    	$a_parametros = $a_parametros[0]['col'];
        
        return $a_parametros;
    }


    /**
    * getForward este método permite recuperar un actionForward (un destino). Esto permite cambiar el destino de 
    * una accion.
    *
    * @access   public
    * @param string $name identificador del actionForward que se quiere recuperar.
    * @return   actionForward
    */   	
	public function getForward($name) {

		$fordward = $this->comunica->getForward($name);
		if(empty($fordward))
			die('Error: no existe el retorno '.$name.', consulte el fichero de mapeo.');
		
		return $fordward; 
	}	 
}
?>