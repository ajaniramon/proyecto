<?php
/* gvHIDRA. Herramienta Integral de Desarrollo R�pido de Aplicaciones de la Generalitat Valenciana
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
*  Av. Blasco Ib��ez, 50
*  46010 VALENCIA
*  SPAIN
*  +34 96386 24 83
*  gvhidra@gva.es
*  www.gvpontis.gva.es
*
*/
/**
 * gvHidraList es una clase que se encarga de enmascarar la definici�n de las listas (combos) en Igep.
 * Facilita al programador un mecanismo m�s sencillo y comprensible para rellenar los diferentes arrays
 * que contienen la definici�n de una lista.
 *
 * Consta de las siguientes propiedades:
  * <ul>
 * <li><b>$v_defLista</b> - Vector donde se almacena la estructura de la lista que se est� creando. Hay que tener en cuenta que
 * la creaci�n de esta puede darse en dos pasos seg�n si tiene dependencia o no la lista</li>
 * <li><b>$nombre</b> - Es un string donde se almancena el nombre del campo de la tpl que contiene la lista y que es el nombre
 * que identificar� a la lista en la estructura de listas de igep.</li>
 * </ul> 
 * @version	$Id: gvHidraList.php,v 1.4 2010-11-18 11:47:27 afelixf Exp $
 * @author David: <pascual_dav@gva.es> 
 * @author Keka: <bermejo_mjo@gva.es>
 * @author Vero: <navarro_ver@gva.es>
 * @author Raquel: <borjabad_raq@gva.es> 
 * @author Toni: <felix_ant@gva.es>
 * @package gvHIDRA
 */


class gvHidraList {

	var $_nombre;
	var $_consulta;
	var $_datosDin;
	var $_datosEst;
	var $_dependencia;
	var $_orden;
	var $_seleccionado;
	var $_dsn;	
	var $v_defLista;
	
	var $_seleccionadoDefecto; //NO SE SABE
	//error_reporting(E_STRICT | E_ALL | E_NOTICE);	
	
	/**
	* @access private 
	* @var boolean indica si la lista es multiple o no. 
	*/
	private $_multiple = false;	

	/**
	* @access private 
	* @var boolean indica si la lista se representa mediante radios 
	*/
	private $_radio = false;
		
	/**
	* @access private 
	* @var int muestra los valores visibles de la lista 
	*/
	private $_size = 1;
	
	private $_classSource;
	
	/**
	* @access private 
	* @var object conexion preestablecida 
	*/
	private $_conn = null;	

	/**
	* @access private 
	* @var object indica si la conexi�n es propia o coincide con la del panel 
	*/
	private $_connown = true;
	
	/**
	* Construye una nueva lista
	* @param string $nombreListaTpl Nombre del campo de la TPl a cincular con la lista
	* @param string $source Nombre de la definici�n de lista (AppMainWindow)
	* @param string $conexionAlternativa DSN alternativo (null si se coge el de la CManejadora)
	*
	* @return void
	*/
	public function __construct ($nombreListaTpl, $source='', $conexionAlternativa='') {
		
		$this->_datosEst = array();
		$this->_seleccionado = '';
		
		$this->setName($nombreListaTpl);
			
		if(!empty($source)) {
			//Cargamos los datos de configuracion de la lista
			$descSource = $this->_procesarDefinicion($source);
			//Si la fuente es de DB
			if(isset($descSource['query'])) {

				$consulta = $descSource['query'];
				//Como la consulta nos puede venir con Order By, se lo quitamos.
				$posOrder = strpos(strtolower($consulta),"order by");										
				if($posOrder===false){
					$this->setConsulta($consulta);
					$this->setOrden(" ORDER BY 2");
				}
				else {
					$this->setConsulta(substr($consulta,0,$posOrder));
					$this->setOrden(substr ($consulta,$posOrder));
				}
			}
			//Si la fuente es una clase
			elseif(isset($descSource['class'])) {
				
				$this->setClass($descSource['class']);					
			}
		
		}
		//Ahora no se le puede pasar un array a esta funci�n, pero por compatibilidad lo dejamos
		else {
			$this->setConsulta ('');
			$this->setClass('');
		}
		$this->setDSN($conexionAlternativa);
	}//Fin de constructor
		
		
	//---------------------------------------------
	//M�todos relativos al nombre
	//---------------------------------------------
	public function getName(){
		return $this->_nombre;		
	}//Fin de getname

	public function setName($nombre){
		$this->_nombre = $nombre;
	}//Fin de setname
	
	//---------------------------------------------
	//FIN:M�todos relativos al nombre
	//---------------------------------------------	
	
	//---------------------------------------------
	//M�todos relativos a la dependencia
	//---------------------------------------------
	function hayDependencia() {
		return (!empty($this->_dependencia));
	}

	private function getArrayDependencia() {
		return ($this->_dependencia);
	}

	private function getTipoDependencia() {
		return ($this->_tipoDependencia);
	}
			

	/**
	* M�todo que permite asigar dependencia en una List. Es decir, indicamos que los elementos de
	* la lista dependen del valor de otros campos de la TPL.
	*  
	* @access	public
	* @param array $listaCamposTpl	Array con los campos de la tpl de cuyos vvalores depende el conjunto de valores que muestra la lista
	* @param array $listaCamposBd	Array (indexado en el mismo orden que el anterior) con los campos (o alias) de la BD (de la consulta) que guardan correspondencia con la lista anterior
	* @param int $tipoDependencia	Entero que indica si es una dependencia fuerte (0, valor por defecto) o d�bil (1). 
	*/
	public function setDependence($listasCamposTpl, $listasCamposBD,$tipoDependencia=0){

		$dependencia = array();
		if((!is_array($listasCamposTpl)) or (!is_array($listasCamposBD)) or (count($listasCamposTpl)!=count($listasCamposBD)))
			throw new gvHidraException('Error en la introducci�n de la dependencia de la lista que actua sobre el campo TPL '.$this->getName().' . Recuerde que debe introducir dos arrays.');
		$i=0;
		for($i=0;$i<count($listasCamposBD);$i++)
			$dependencia[$listasCamposTpl[$i]] = $listasCamposBD[$i];  		
		$this->_dependencia = $dependencia;
		$this->_tipoDependencia = $tipoDependencia;				
	}//Fin de setDependence
	//---------------------------------------------
	//FIN:M�todos relativos a la dependencia
	//---------------------------------------------
	
	
	//---------------------------------------------
	//M�todos relativos a la fuente de datos
	//---------------------------------------------
	public function getConsulta() {
		return ($this->_consulta);
	}
	
	public function setConsulta($cadena){
		$this->_consulta = $cadena; 
	}	
	
	public function getOrden() {
		return (' '.$this->_orden);
	}
	
	public function setOrden($cadena){
		$this->_orden = $cadena; 
	}

	public function getClass() {
		return ($this->_classSource);
	}
	
	public function setClass($class){
		$this->_classSource = $class; 
	}	

	//---------------------------------------------
	//FIN:M�todos relativos a la fuente de datos
	//---------------------------------------------


	//---------------------------------------------
	//M�todos relativos a la conexi�n
	//---------------------------------------------

	/**
	* M�todo que permite validar si existe DSN
	*  
	* @access	public
	*/
	public function hayDSN() {
		return (!empty($this->_dsn));
	}

	/**
	* M�todo que permite obtener el DSN de conexi�n de la lista
	* 
	* @access	public
	* @return	array
	*/	
	public function getDSN() {
		return ($this->_dsn);
	}

	/**
	* M�todo que permite fijar el DSN de conexi�n de la lista
	* 
	* @access	public
	* @param	array
	*/	

	public function setDSN($dsn) {
		$this->_dsn = $dsn;
	}

	/**
	* M�todo que permite validar si existe conexi�n viva asignada
	* 
	* Tener una conexi�n viva asignada al objeto optimiza el rendimiento evitando sobrecarga por creaci�n de nuevas conexiones. 
	* @access	public
	*/
	public function connectionAlive() {
		return (is_object($this->_conn->obj_conexion));
	}

	/**
	* M�todo que permite obtener el objecto conexi�n interno de la lista.
	* 
	* Tener una conexi�n viva asignada al objeto optimiza el rendimiento evitando sobrecarga por creaci�n de nuevas conexiones. 
	* @access	public
	* @return	object
	*/	
	public function getConnection() {
		return ($this->_conn);
	}

	/**
	* M�todo que permite fijar una conexi�n para la lista
	* 
	* Tener una conexi�n viva asignada al objeto optimiza el rendimiento evitando sobrecarga por creaci�n de nuevas conexiones. 
	* @access	public
	* @param object conexion
	*/
	public function setConnection($con) {
		$this->_conn = $con;
	}

	/**
	* M�todo que permite comprobar si la conexi�n es propia o no
	* 
	* Para optimizar, si la conexi�n es la misma que la del panel, no reconectaremos. 
	* @access	public
	*/
	public function connectionOwn() {
		return $this->_connown;
	}

	/**
	* M�todo que permite fijar si la conexi�n es propia o no
	* 
	* Para optimizar, si la conexi�n es la misma que la del panel, no reconectaremos. 
	* @access	public
	* @param bool val
	*/
	public function setConnectionOwn($val) {
		$this->_connown = $val;
	}

	//---------------------------------------------
	//FIN:M�todos relativos a la conexi�n
	//---------------------------------------------


	//---------------------------------------------
	//M�todos relativos al item seleccionado
	//---------------------------------------------				
	public function getSeleccionado() {
		return $this->_seleccionado; 
	}

	public function getSelected() {
		return $this->_seleccionado; 
	}
	
	public function setSelected($valor) {
		$this->_seleccionado = $valor;		
	}
	
	//---------------------------------------------
	//FIN:M�todos relativos al item seleccionado
	//---------------------------------------------

	/**
	* Fija la propiedad multiple a un valor. Esta propiedad indica si la lista es multiple o no.
	*
	* @param	$multiple	boolean	
	* @return none 
	*/	
	public function setMultiple($multiple){
		
		$this->_multiple = $multiple;	
		//Si no ha fijado size, le damos valor por defecto 5
		if($this->_size==1)
			$this->setSize(5);
	}

	/**
	* Devuelve el valor de la propiedad multiple. Esta propiedad indica si la lista es multiple o no.
	*	
	* @return boolean 
	*/		
	public function getMultiple(){
		
		return $this->_multiple;
	}	

	/**
	* Fija la propiedad radion a un valor. Esta propiedad indica si la lista se representa como radios o no.
	*
	* @param	$radio	boolean	
	* @return none 
	*/	
	public function setRadio($radio){
		
		$this->_radio = $radio;
	}

	/**
	* Devuelve el valor de la propiedad radio. Esta propiedad indica si la lista se representa como radio.
	*	
	* @return boolean 
	*/		
	public function getRadio(){
		
		return $this->_radio;
	}	

	/**
	* Fija la propiedad size. Esta propiedad indica los elementos visibles de la lista.
	*
	* @param	$size	integer	
	* @return none 
	*/	
	public function setSize($size){
		
		$this->_size = $size;
	}

	/**
	* Devuelve el valor de la propiedad size. Esta propiedad indica los elementos visibles de la lista.
	*	
	* @return integer 
	*/		
	public function getSize(){
		
		return $this->_size;
	}	
	
	/**
	* M�todo que a�ade opciones est�ticas a una lista.
	*
	* @param	$valor	indica el valor del option
	* @param	$descripcion	indica la descripcion del option
	* @param	$posicion		indica la posicion donde se a�ade la opci�n	
	* @return array 
	*/	
	public function addOption($valor, $descripcion) {

		$nuevaOpcion = array("valor"=>$valor, "descripcion"=>$descripcion);
		array_push($this->_datosEst, $nuevaOpcion);
	}		

	function _procesarDefinicion($nombre) {	

        $conf = ConfigFramework::getConfig();
        $res = $conf->getDefList($nombre);
        if($res == -1){
            throw new Exception("gvHidraList Error: La consulta '$nombre' no est� definida en el fichero AppMainWindow.php.");
        }
        //Tenemos que indicar de que tipo es y el valor del parametro
        return $res;
	}//Fin de _procesarDefinicion
	
	
    public function construyeLista ($regFuenteDependencia = '') {
        //Debug:Indicamos que ejecutamos la consulta
        IgepDebug::setDebug(DEBUG_IGEP,'gvHidraList: Construyendo la lista '.$this->getName());
            
        $res2 = array();
        if ($this->getConsulta()!='') {

			$res2 = $this->buildByQuery($regFuenteDependencia);
        }
		elseif ($this->getClass()!='') {

			$res2 = $this->buildByClass($regFuenteDependencia);
        }

        $res2 = array_merge($this->_datosEst, $res2);
        
        if (count($res2)==0)
            $lista_resultanteNuevo = array('seleccionado'=>'', 'lista'=>array());
        else
            $lista_resultanteNuevo = array('seleccionado'=>$this->getSeleccionado(),'lista'=>$res2);			
        return $lista_resultanteNuevo;
    }
    

	private function buildByQuery($regFuenteDependencia) {
		
        //Si la lista es est�tica la a�adimos directamente a datosPreInsertados		
        //Conectamos a la base de datos. Si ya est� previamente conectado, ahorramos dicha conexion
        if($this->connectionAlive())
        	$conexionParticular = $this->getConnection();
        else {
        	$conexionParticular = new IgepConexion($this->getDSN());
        	$this->setConnection($conexionParticular);
        }
        //Vamos a comprobar si tiene dependencia
        $strDependencia = '';		
        if ($this->hayDependencia()) {

            //Tenemos que ver si el campos del que depende tiene valor
            $v_Dependencia = $this->getArrayDependencia();
            $tipoDependencia = $this->getTipoDependencia(); 
            $valorSeleccionado = '';

            foreach($v_Dependencia as $campoDependiente => $campoDependienteBd) {					
                //Si el campo dependiente tiene valor										
                if(is_array($regFuenteDependencia[$campoDependiente])) {
                    $valorSeleccionado = $regFuenteDependencia[$campoDependiente]['seleccionado']; 
                }
                else {
					$valorSeleccionado = $regFuenteDependencia[$campoDependiente];
                }
                if(($tipoDependencia==0) OR (!empty($valorSeleccionado))) {
                    if($strDependencia != '')
                        $strDependencia.=' AND ';
                    if(!empty($valorSeleccionado))
                    	$strDependencia.=  $campoDependienteBd." = '".$valorSeleccionado."'";
                    else
                    	$strDependencia.=  $campoDependienteBd." is null";
                }
                $valorSeleccionado = '';
            }
            if(!empty($strDependencia)) {
                if((strpos(strtolower($this->getConsulta()),'where')===false))
                    $strDependencia = ' WHERE '.$strDependencia;
                else
                    $strDependencia = ' AND '.$strDependencia;			
                $consulta = $this->getConsulta().$strDependencia.$this->getOrden();
                $res2 = $conexionParticular->consultar($consulta);
            }
            else {
				if($tipoDependencia==1) {
					$consulta = $this->getConsulta().$this->getOrden();;
					$res2 = $conexionParticular->consultar($consulta);
				}
				else
                	$res2 = array();
            }
        }
        else {
            $consulta = $this->getConsulta().$this->getOrden();
            $res2 = $conexionParticular->consultar($consulta);
        }		
        if (PEAR::isError($res2))
            $res2 = array();

		return $res2;
	}    
    
    
	private function buildByClass($regFuenteDependencia) {

		//Si la lista es est�tica la a�adimos directamente a datosPreInsertados			
		//Vamos a comprobar si tiene dependencia
		
		//Creamos la instancia de la clase
		$class = $this->getClass();
		if(!class_exists($class))
			throw new gvHidraException('Error-List Component:'.$this->getName().': la clase definida como fuente de datos no existe. Revise su defini�n y su inclusi�n. Concretamente la clase '.$class);
		  
						
		$dependence = array();		
		if ($this->hayDependencia()) {
		
		    //Tenemos que ver si el campos del que depende tiene valor
		    $v_Dependencia = $this->getArrayDependencia();
		    $tipoDependencia = $this->getTipoDependencia();
		    $valorSeleccionado = '';
		
		    foreach($v_Dependencia as $campoDependiente => $campoDependienteBd) {					
		        //Si el campo dependiente tiene valor
		        if(is_array($regFuenteDependencia[$campoDependiente])) {
		            $valorSeleccionado = $regFuenteDependencia[$campoDependiente]['seleccionado']; 
		        }
		        else {		            														
		            $valorSeleccionado = $regFuenteDependencia[$campoDependiente];
		        }					
				if(($tipoDependencia==0) OR (!empty($valorSeleccionado))) {
					$dependence[$campoDependienteBd] = $valorSeleccionado;
		        }
		        $valorSeleccionado = '';
		    }
		    try { 
			    $instance = new $class;
			    if(count($dependence)>0) {
					
			    	$res2= $instance->build($dependence,$tipoDependencia);
			    }
			    else {
			    	if($tipoDependencia==1) {
			    		
			    		$res2= $instance->build($dependence,$tipoDependencia);
			    	}
			    	else {
			    		
			    		$res2 = array();
			    	}
			    }
		    }
			catch(Exception $e){
				IgepDebug::setDebug('Error en gvHidraList '.$this->getName().' Mensaje: '.$e->getMessage());
			}		    
		        
		}
		else {
			try {
				$instance = new $class;
				$res2= $instance->build(null,null);
			}
			catch(Exception $e){
				IgepDebug::setDebug('Error en gvHidraList '.$this->getName().' Mensaje: '.$e->getMessage());
			}
		}		
		if (PEAR::isError($res2))
		    $res2 = array();
		
		return $res2;
	}
	
}//Fin gvHidraList

/**
 * Classe de uso interno que encapsula la estructura de Arrays que se utiliza en
 * IGEP para el manejo de las listas. Se utilizara en IgepComunicaIU
 * @package	gvHIDRA
 */
 
class _IgepEstructuraLista {
	
	var $_listaIGEP;
	 
	function _IgepEstructuraLista(& $lista)
	{
		$this->_listaIGEP = $lista;
	}
		
	
	private function setSelectedItem($valor)
	{
		$this->_listaIGEP['seleccionado'] = $valor;
	}

	/**
	 * Fija el valor seleccionado
	 */	
	function setSelected($valor)
	{
		$this->setSelectedItem($valor);
	}
	
	function addItem($valor, $descripcion)
	{
		$this->_listaIGEP['lista'][]= array('valor'=>$valor,'descripcion'=>$descripcion);
	}	
	function addOption($valor, $descripcion)
	{
		$this->addItem($valor, $descripcion);
	}
	
	
	function deleteItem($valor)
	{	
		$numElem = count($this->_listaIGEP['lista']);
		$i = 0;
		$nuevaLista = array();
		while ($i<$numElem)
		{
			if ($this->_listaIGEP['lista'][$i]['valor'] != $valor)
				array_push($nuevaLista,$this->_listaIGEP['lista'][$i]);        
			++$i;
		}
    	$this->_listaIGEP['lista'] = $nuevaLista;
	}	
	function deleteOption($valor)
	{
		$this->deleteItem($valor);
	}
	
	function toArray()
	{
		return($this->_listaIGEP);
	}	
	function getEstructuraListaIgep()
	{
		return($this->_listaIGEP);
	}
	
	function arrayToObject($v_lista)
	{
		$this->_listaIGEP=$v_lista;
	}
	
    function clean(){
        $this->_listaIGEP['seleccionado'] = null;
        $this->_listaIGEP['lista'] = array();
    }
  
    function limpiar(){
        $this->clean();
    }


	/**
	 * Devuelve el valor seleccionado
	 */	
	public function getSelected() {
		
		return $this->_listaIGEP['seleccionado'];
	}

	/**
	* Devuelve la descripccion del valor seleccionado
	*/
	public function getDescription() {

        $value = $this->_listaIGEP['seleccionado'];
       
        foreach($this->_listaIGEP['lista'] as $row) {
                if($row['valor']==$value)
                    return $row['descripcion'];
        }
        return null;
    }    
}//Fin _IgepEstructuraLista

?>