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
 * IgepComunicaIU clase que controla el acceso a la interfaz a través 
 * del frame oculto. El programador puede activar/desactivar un componente, 
 * hacerlo visible/invisible o cambiar su contenido.
 * 
 * <p>
 * El framework proporcionará una instancia de esta clase en todas las acciones de interfaz.
 * Mediante los métodos que proporciona el programador podrá hacer operaciones sobre la IU
 * y el FW se encargará de traducirlas en el Javascript pertinente.
 * </p>  
 *
 * @version	$Id: IgepComunicaIU.php,v 1.32 2010-02-03 11:22:49 afelixf Exp $
 * @author David: <pascual_dav@gva.es> 
 * @author Keka: <bermejo_mjo@gva.es>
 * @author Vero: <navarro_ver@gva.es>
 * @author Raquel: <borjabad_raq@gva.es> 
 * @author Toni: <felix_ant@gva.es>
 * @package	gvHIDRA
 */

class IgepComunicaIU extends IgepComunicaUsuario {
 
	/*
	* Contiene el código JS que se va a ejecutar
	* @access private
	* @var string
	*/
	var $script;

 
	/*
	* Prefijo para obtener los campos adaptados
	* en pantalla
	* @access private
	* @var string
	*/
	var $prefijoAdaptacion;


	/*
	* Prefijo para obtener los campos adaptados
	* en pantalla
	* @access private
	* @var string
	*/
	var $sufijoAdaptacion;
  
  
	/*
	* Variable que contiene el nombre del campo que dispara la acción en el caso de las acciones de interfaz
	* @access private
	* @var string
	*/  
	var $_campoDisparador;


	/**
	* Constructor. Recibe como parámetro una instancia viva de la clase
	* IgepComunicacion
	*
	* @access	public
	* @param	object	$comunica
	*/  
 	public function __construct(& $comunica, & $datosPreinsertados, & $listasPanel, $campoOrigen) {

	 	parent::__construct($comunica, $datosPreinsertados, $listasPanel);
	 	$this->script = '';

	 	//Almacenamos los destinosAdaptados en un array indexado por el nombre del campo	 	 	
	 	$this->prefijoAdaptacion ='';
	 	$this->sufijoAdaptacion ='';
	 	
	 	$auxDestino = $campoOrigen;
		$v_auxDestino = explode('___',$auxDestino,3);
		if (count($v_auxDestino)>2) {
			$this->prefijoAdaptacion = $v_auxDestino[0].'___';
			$this->sufijoAdaptacion = '___'.$v_auxDestino[2];
		}
 	}

 
	/**
	* destinosAdaptados. Devuelve el nombre del campo en formato HTML
	* 
	* @access	private
	* @param	string	$nombreCampo
	* @return string
	*/    
	private function destinosAdaptados($nombreCampo) {

 		return ($this->prefijoAdaptacion.$nombreCampo.$this->sufijoAdaptacion);
	}

 
 	/**
	* Método que devuelve el nombre de un check en HTML
	* 
	* @access private
	* @param string $nombreCampo Nombre del campo
	* @return string devuelve el id del check en formato HTML
	*/
	private function getCheckName($nombreCampo) {

	 	//Panel fil
	 	if($this->prefijoAdaptacion=='')
	 		return 'chkbfil_'.$nombreCampo;
	 	//Panel modo edi
	 	elseif($this->prefijoAdaptacion == 'cam___')
	 		return 'ccam___'.$nombreCampo.$this->sufijoAdaptacion;
	 	//Panel modo ins
	 	else 	
	 		return 'cins___'.$nombreCampo.$this->sufijoAdaptacion;
	}


 	/**
	* Método que devuelve el nombre del hidden de un check
	* 
	* @access private
	* @param string $nombreCampo Nombre del campo
	* @return string devuelve el campo en formato HTML
	*/
	private function getCheckHiddenName($nombreCampo) {

	 	//Panel fil
	 	if($this->prefijoAdaptacion=='')
	 		return $nombreCampo;
	 	//Panel modo edi
	 	elseif($this->prefijoAdaptacion == 'cam___')
	 		return 'cam___'.$nombreCampo.$this->sufijoAdaptacion;
	 	//Panel modo ins
	 	else 	
	 		return 'hins___'.$nombreCampo.$this->sufijoAdaptacion;
	}


 	/**
	* setValue método que fija el valor de un campo de texto en una accion de interfaz.
	* 
	* @access public
	* @param string $nombreCampo Nombre del campo en la TPL
	* @param string $valor nuevo valor del campo
	* @return none
	*/
	public function setValue($campo, $valor, $parametroOperacion='')
	{
		//Recogemos el tipo
		$tipo = $this->comunica->descCampoPanel[$campo]['tipo'];
		//Recogemos la parte decimal (si exite) para adaptar la presentación
		$parteDecimal = $this->comunica->descCampoPanel[$campo]['parteDecimal'];
		$valor = IgepComunicaUsuario::prepararPresentacion($valor, $tipo, $parteDecimal);	

		if ($parametroOperacion == 'external')
			$campoDestino = $campo;
		else
			$campoDestino = $this->destinosAdaptados($campo);	
		$this->script.= IgepSmarty::getJsSetCampoTexto($campoDestino, $valor);
		$this->script .= IgepSmarty::getJsSetEstadoModificado($campoDestino);
	}

 
	/**
	* setSelected fija el valor de una lista en una acción de interfaz.
	* 
	* <p>Este metodo se debe utilizar si se quiere cambiar el valor seleccionado de una lista.</p>
	*
	* @access	public
	* @param	string	$campo	Nombre del campo
	* @param	string	$valor	Valor del campo 
	* @return	none
	*/ 
	public function setSelected($campo, $valor, $parametroOperacion='') {

		$campoDestino = $this->destinosAdaptados($campo);
	 	$this->script.= IgepSmarty::getJsSetSelected($campoDestino, $valor);
	  	$this->script .= IgepSmarty::getJsSetEstadoModificado($campoDestino);
	}

 
	/**
	* setList cambia el contenido de una lista en una accion de interfaz.
	* 
	* <p>Este metodo se debe utilizar si se quiere cambiar el contenido entero de una lista.
	* Suele venir combinado con la obtencion de la lista con el metodo getList.
	* </p>
	*
	* @access	public
	* @param	string	$campo	nombre del componente lista
	* @param	string	$objListaStruc		estructura de la lista 
	* @return	none
	*/ 
	public function setList($campo, $objListaStruc, $parametroOperacion= '') {

	 	$v_lista = $objListaStruc->getEstructuraListaIgep();
	 	$campoDestino = $this->destinosAdaptados($campo); 	
	 	$this->script.= IgepSmarty::getJsLista($campoDestino, $v_lista);  
		$this->script .= IgepSmarty::getJsSetEstadoModificado($campoDestino);
	}

 
	/**
	* setChecked metodo que permite modificar el valor del un check en una accion de interfaz.
	* 
	* @access	public
	* @param string name	nombre del check
	* @param boolean check	Checked si o no
	* @return none
	*/
	public function setChecked($name, $check, $parametroOperacion='') {

		$idCheck = $this->getCheckName($name);
		$hiddenName = $this->getCheckHiddenName($name);
		
		//Seleccionamos el valor dependiendo si esta seleccionado
		$desc = $this->comunica->descCampoPanel[$name];
		($check)?$value=$desc['valueChecked']:$value=$desc['valueUnchecked'];
		
	 	$this->script.= IgepSmarty::getJsSetCheck($idCheck, $check, $hiddenName, $value);
	}

 
	/**
	* setVisible metodo que permite modificar la visibilidad de un componente en una accion de interfaz.
	* 
	* @access	public
	* @param string $name nombre del componente
	* @param boolean $valor booleano que indica si se quiere ver o no.
	* @return none
	*/ 
	public function setVisible($campo, $valor)
	{
		//Obtenemos el tipo de widget
		$desc = $this->comunica->descCampoPanel[$campo];		
		if($desc['component'] == 'CheckBox')
			$campoDestino = $this->getCheckName($campo);
		else 		
			$campoDestino = $this->destinosAdaptados($campo);
			
		$this->script.= IgepSmarty::getJsSetVisible($campoDestino, $valor);
	}


	/**
	* setEneble metodo que permite modificar la accesibilidad de un componente en una accion de interfaz.
	* 
	* @access	public
	* @param string $name nombre del componente
	* @param boolean $valor booleano que indica si es editable o no
	* @return none
	*/
	public function setEnable($campo, $valor)
	{
		//Obtenemos el tipo de widget
		$desc = $this->comunica->descCampoPanel[$campo];
		$tipoComponente = null;
		$campoDestino = $this->destinosAdaptados($campo);
		
		if ($desc['component'] == 'CheckBox')
		{
			$tipoComponente = 'CheckBox';
			$campoDestino = $this->getCheckName($campo);
		}
		else if ($desc['radio']==true)
		{
			$tipoComponente = 'Radio';
		}
		$this->script.= IgepSmarty::getJsSetEnable($campoDestino, $valor, $tipoComponente);
	}
 
 
 	/**
	* setTabIndex metodo que permite modificar el tabindex de un componente de pantalla de forma dinamica.
	* 
	* @access	public
	* @param string $name nombre del componente
	* @param boolean $value valor asignado
	* @return none
	*/
	public function setTabIndex($name, $value)
	{
		$campoDestino = $this->destinosAdaptados($name);	
		$this->script.= IgepSmarty::getJsSetOrder($campoDestino, $value);
	}
 
 
	/**
	* setBttlState Establece el estado del boton Tooltip correspondiente a activado (true) o desactivado (false)
	* 
	* @access	public
	* @param	String	$panel	Indica el panel sobre el que se sitúa el botonTooltip |||
	* @param	String	$nameBttl	Nombre del boton tooltip
	* @param	Boolean	$on	True para activar, false para desahabilitar
	* @return	void 
	*/		
	public function setBttlState($idPanel, $nameBttl, $on) {

		$this->script.=IgepSmarty::getJsSetBttlState($idPanel, $nameBttl, $on);	
	}

 
	/**
	* _setCampoDisparador método interno para fijar el nombre del campo que dispara la acción en las acciones de interfaz
	* 
	* @access	private
	* @param	string	$nombreCampo
	* @return none
	*/
	function _setCampoDisparador($nombreCampo) { 	

		$this->_campoDisparador = $nombreCampo;
	}

	 
	/**
	* getTriggerField método para obtener el nombre del campo que dispara la acción en las acciones de interfaz
	* 
	* @access public
	* @return string
	*/
	public function getTriggerField(){

		return $this->_campoDisparador;
	}

 
	/**
	* posicionarEnFicha permite cambiar la ficha activa
	* 
	* @access private
	* @return none
	*/
	function posicionarEnFicha($indiceFila) {        

		if($this->sufijoAdaptacion!=''){      
			$v_auxUno = explode('___',$this->sufijoAdaptacion);
			$v_auxDos = explode('_',$v_auxUno[1]);      
			$this->sufijoAdaptacion = '___'.$v_auxDos[0].'_'.$indiceFila; 
		}    
		$this->comunica->posicionarEnTupla($indiceFila);    
	}

	
	/**
	* getActiveMode obtenemos el modo activo para una acción de interfaz dada.
	* 
	* <p>Los modos posibles son</p>
	* <ul>
	* <li>fil : Filtro o modo de búsqueda.</li>
	* <li>lis : Listado o modo de tabla.</li>
	* <li>edi : Edición o modo de ficha.</li>
	* </ul>
	* @access public
	* @return string
	*/
	public function getActiveMode() {
    
    	return $this->comunica->damePanelActivo();
	}

   
	//PROVISIONAL
	function getScript() { 
 		return $this->script; 
 	}


	//REVIEW: Toni Para poder conseguir que las acciones de negocio tengan acceso a mapeos diferentes a los ya especificados por gvHidra
	public function getForward($name){
		
		die('Error: no se puede hacer uso de esta funcionalidad desde una acción de interfaz.');
	}
}
?>