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
* Incluimos la clase de Igep que nos permite formatear los datos que nos vienen de un form
*/
//include "IgepComunicacion.php";
/**
* Clase para aislar el acceso a datos al usuario/desarrollador
*/
//include "IgepComunicaUsuario.php";
/**
* Clase para aislar el acceso a datos al usuario/desarrollador
*/
//include "IgepComunicaIU.php";
/**
* Incluimos la clase de Igep que nos permite enmascarar la creación de Mensajes en pantalla
*/
//include "IgepMensaje.php";

/**
* Incluimos la clase de Igep que incluye métodos de escpado de comillas combinados con Javscript
*/
//require_once "IgepSmarty.php";



/**
 * gvHidraForm es la clase más importante de todo el entorno igep. Es la clase de la que heredarán todas las clases
 * que manejen paneles igep. Esta clase contiene una serie de métodos y funciones que producen el comportamiento 
 * genérico de cualquier aplicación igep, y es el nexo de unión entre estas clases y phrame.
 *
 * Es importante tener en cuenta que siempre que se instancie desde phrame esta clase, tras lanzar el constructor se llamará
 * al método perform. En este método es donde se escoge la acción a realizar y que nos llega a partir del parámetro action añadido a la URL. 
 * Las acciones genéricas programadas son las siguientes:
 *<ul>
 *<li><b>buscar:</b> Acción genérica de Igep que se lanza cuando le damos desde un panel de busqueda al botón buscar. Se encarga de recoger los datos incluidos en el panel de busqueda y realizar la Select a partir de los parametros $str_select, $str_where y $str_orderBy.</li>
 *<li><b>nuevo:</b> Acción genérica que se lanza al dar al botón de nuevo. Es importante tener en cuenta que esta acción puede llegar a phrame o no; esto dependerá de si el panel lo require o no. Siempre lo requerirá si tiene listas deplegables, ya que si se tienen que cargar debe pasar por esta acción.</li>
 *<li><b>editar:</b> Acción genérica que se lanza al pulsar el botón de editar en un panel de listado. Se encarga de recoger los datos del registro seleccionado y construir la select a partir de los atributos $str_selectEditar, $str_whereEditar y $str_orderByEditar.</li>
 *<li><b>borrar:</b> Acción genérica que se lanza en un panel de listado (que tenga asociado un panel de edición o Ficha) al pulsar el botón de guardar. Se encarga de recoger las tuplas que el usuario a marcado para borrar y lanza el Delete.</li>
 *<li><b>recargar:</b> Acción privada de Igep que se encarga de sincronizar el maestro-detalle.</li>
 *<li><b>operarBD:</b> Acción genérica que se lanza al pulsar el botón guardar en un panel de listado. Se encarga de realizar las tres operaciones básicas de mantenimiento y que se pueden realizar desde este panel: Inserción, Actualización y borrado.</li>
 *<li><b>cancelarTodo:</b> Acción genérica que se lanza al pulsar el botón cancelar de un panel de listado. Se encarga de borrar el contenido de la ultima consulta y volver al panel de busqueda.</li>
 *<li><b>cancelarEdicion:</b> Acción genérica que se lanza al pulsar el botón cancelar de un panel de edición o ficha. Se encarga de borrar el contenido de la ultima edición y volver al panel de listado.</li>
 *<li><b>inicializarBusqueda:</b> Acción genérica que se lanzará en el caso de que se quiera incluir una lista desplegable en el menu de busqueda. En este caso, antes de entrar en la ventana tendrá que realizarse una llamada a phrame con esta acción.</li>
 *<li><b>camposDependientes:</b> Acción privada de Igep que sirve para recalcular las listas dependientes con el nuevo valor seleccionado. Nunca tiene que ser utilizado por los programadores.</li>
 *<li><b>abrirVentanaSeleccion:</b> Acción privada de Igep que sirve para abrir una ventana de Selección. Nunca tiene que ser utilizado por los programadores.</li>
 *<li><b>buscarVentanaSeleccion:</b> Acción privada de Igep que sirve para buscar en una ventana de Selección. Nunca tiene que ser utilizado por los programadores.</li> 
 *</ul>
 *
 * Se recomienda consultar la documentación acerca del método perform.   
 *
 * @version $Id: gvHidraForm.php,v 1.72 2011-05-12 14:36:50 afelixf Exp $
 * 
 * @author David: <pascual_dav@gva.es> 
 * @author Keka: <bermejo_mjo@gva.es>
 * @author Vero: <navarro_ver@gva.es>
 * @author Raquel: <borjabad_raq@gva.es> 
 * @author Toni: <felix_ant@gva.es>
 *
 * @package gvHIDRA
 */ 
class gvHidraForm extends Action
{
    
    /**
    * objeto de resultado de la última consulta
    *
    * @var object obj_ultimaConsulta
    */        
    var $obj_ultimaConsulta;
    
    /**
    * variable de error
    *
    * @var object obj_errorNegocio
    */        
    var $obj_errorNegocio;
            
    /**
    * La instancia de IgepComunicación para comunicarnos con la presentación
    * @access public
    * @var    object  $comunica
    */        
    var $comunica;
                        
    /**
    * Esta variable contiene el resultado de la última Select de Edición. Contiene el DBResult
    * @access private     
    * @var object $obj_ultimaEdicion
    */            
    var $obj_ultimaEdicion;
        
    /** 
    * Variable que contendrá el posible mensaje a enviar al panel. Tiene que ser de la clase IgepMensaje
    *
    * @var    object  $obj_mensaje 
    */    
    var $obj_mensaje;
    
    /**
    * Para guardar la fila actual
    * @access private     
    * @var integer    $int_filaActual
    */                
    var $int_filaActual;
            
    /**
    * Variable interna donde tenemos la descripción de las lista desplegables
    * @access private
    * @var array  $v_lista
    */            
    var $v_listas;
    
    /**
    * Este array tiene la referencia de los paneles hijos(su clase manejadora) y la relación de dependencia existente,
    * es decir los campos de la TPL que correspondan.
    * @var array  $v_hijos
    */    
    var $v_hijos;
      
    /**
    * Esta variable contiene el nombre de la clase padre. Si el panel tiene un panel maestro del que depende, debe inicializar esta variable con el nombre de la clase padre.     
    * @var string $str_nombrePadre
    */            
    var $str_nombrePadre;
       
    /**
    * Esta variable contiene el array de definición de todas las ventanas de selección del panel.
    * @access private     
    * @var array  $v_ventanasSeleccion
    */                
    var $v_ventanasSeleccion;
            
    /**
    * Array que se utiliza para preInsertar los datos cuando se pulsa al botón de nuevo.
    * @access private     
    * @var array  $v_preInsercionDatos
    */                    
    var $v_preInsercionDatos;
    
    /**
    * Array que se gasta para almacenar datos necesarios para la presentacion.
    * Permite interactuar con el panel de presentación. Es decir, ponerlo en 
    * modo inserción o en modo modificacion.
    * @access private     
    * @var array  $v_datosPresentacion
    */                    
    var $v_datosPresentacion;
    
    /**
    * Array que describe las características de los campos del panel.
    * @access private     
    * @var array  $v_descCamposPanel
    */                        
    var $v_descCamposPanel;
        
    /**
    * Array que contiene la estructura necesaria para controlar las validaciones que los usuarios pueden programar a partir de funciones particulares de la clase.
    * @access private
    * @var array  v_validacionesUsuario
    */        
    var $v_validacionesUsuario;
          
    /**
    * Objeto que permite manejar/registrar el javascript de esta clase
    * @access private
    * @var object obj_IgSmarty    
    */        
    var $obj_IgSmarty;
    
    /**
    * Vector que contiene los checks que en un tres modos se han seleccionado al realizar una accion editar.
    * @access private
    * @var array $v_checksMarcados
    */        
    var $v_checksMarcados;
    
    /**
    * Cadena que contiene el nombre de la clase manejadora del panel detalle activo (visible)
    * @access private
    * @var string panelDetalleActivo 
    */    
    var $panelDetalleActivo;

	private $keepFilterValuesAfterSearch = false;
	
    /**
    * Booleano que indica si el panel esta en una ventana modal o no
    * @access private
    * @var bool isModal 
    */
	private $_isModal = false;

	private $activeLazyList = false;

	private $activeTab = 0;
	
	/**
	* Booleano que indica si el panel salta al modo edicion cuando al buscar se recupera un unico registro
	* @access protected
	* @var bool jumpToEditOnUniqueRecord
	*/
	protected $jumpToEditOnUniqueRecord = false;
	private $activeJumpToEditOnUniqueRecord = false;
	
    /**
    * constructor. Generará a partir de los parámetros que se le pasen una conexión a al base de datos y un
    * array de manejadores de  tablas (una por cada una de las que mantenga el panel hijo).
    */
    public function gvHidraForm(){     
        
        //Creamos la instancia de IgepSmarty que controla el Js
        $this->obj_IgSmarty = new IgepSmarty();
        //Generamos la instancia completa
        $this->activeTab = 0;
        $this->regenerarInstancia();
    }//Fin de constructor

    public function regenerarInstancia(){
        //Recuperamos la instancia de la clase Error. Si no existe (caso en el que venimos de Views), lo creamos
        global $g_error;                
        //#NVI#VIEWS#: Cuando quietemos del views las llamadas a Negocio quitamos este if
        if(!isset($g_error)) 
            $g_error = new IgepError(); 
        $this->obj_errorNegocio = & $g_error;
        //Creamos la instancia de IgepComunicacion
        $this->comunica = new IgepComunicacion($this->v_descCamposPanel);        
    }

    /**
     * Utilizado por Negocio. Devuelve una colección campo/valor, campo/valores que contine
     * la información para preasignar valores por defecto en campos de inserción y detalles 
     * (los campos que lo relaciona con el maestro)
     * @acces private
     * @param   string  nombreCampo     Nombre del campo sobre el que se dan valores por defecto
     * @return  array   Vector indexado por el nombre del campo que contiene los valores de los
     * mismos en función del tipo 
     */
    public function getDefaultData($nombreCampo=''){        
        if($nombreCampo=='')
            return ($this->v_preInsercionDatos);
        else
            return ($this->v_preInsercionDatos[$nombreCampo]);
    }
    
    /**
     * Fija valores por defecto para distintos campos de la TPL,
     * por ejemplo, los valores de las listas cuando pulsamos la opción de insertar, o
     * los campos que relacionan una panel detalle con su maestro
     * @access private
     * @param   string  nombreCampo     Nombre del campo sobre el que se dan valores por defecto
     * @param   mixed   $valor          Valor (string) /valores (arraysLista) que asignar
     */ 
    public function addDefaultData($nombreCampo, $valor) {

		$field = @$this->v_descCamposPanel[$nombreCampo];
        IgepComunicaUsuario::prepararPresentacion($valor, @$field['tipo'], @$field['parteDecimal']);
        //Comprobamos si es una lista
        if(isset($this->v_listas[$nombreCampo])) {
        	//Si asigna un valor pasamos a modificar el seleccionado
        	if(!is_array($valor)) {
        		$this->v_preInsercionDatos[$nombreCampo]['seleccionado'] = $valor;
        		return;
        	}
        }
        $this->v_preInsercionDatos[$nombreCampo] = $valor;
    }
    

    /**
    *   Método que SIEMPRE se lanza cuando venimos desde phrame y que es el encargado de realizar la unión entre Igep y el controlador (phrame). 
    * Este método comprueba cual es la acción a ejecutar y lanza las operaciones pertinentes.
    *   Estas acciones pueden ser acciones <i>genéricas</i> en cuyo caso aparecerán en el codigo de este método como entradas del switch principal; o pueden ser acciones
    *  <i>particulares</i> del panel hijo, en cuyo caso deberán incorporarse al sobreescribiendo el método comportamientosParticulares en la clase hija.  
    * La forma que proporciona phrame para que le indiquemos la dirección de destino son los objetos de la clase actionForward. 
    * Por esta razón este método recogerá estos objetos y los devolverá a phrame; quien se encargará de 
    * redirigir al navegador hasta la URL adecuada. Estos objetos actionForward se obtienen a partir del 
    * parámetro $actionMapping (que se encarga de leer el valor del mappings.php de la aplicación).  
    * <br><b>IMPORTANTE:</b> Este método SIEMPRE almacena en la SESSION el objeto panel actual, por ello no es 
    * necesario que nosotros lo almacenemos previamente si venimos de phrame.
    * @access private
    */
    public function perform($actionMapping, $actionForm) {  
        //Recogemos la accion y le quitamos el prefijo que nos viene de la ventana      
        $str_accion = $actionForm->get('action');   
        if(strpos($str_accion,'__')>0) {                
            $auxiliar = explode('__',$str_accion);
            $str_accion = $auxiliar[1];             
        }
        /************ MODAL *********************/
        //Recogemos el parametro openModal para saber si el panel se abre en una ventana modal.
        $openModal = $_REQUEST['openModal'];
        if($openModal=='yes') {
        	$this->_isModal = true;
        }
        unset($_REQUEST['openModal']);  
        /************ FIN MODAL *********************/

        $idSolapaOn = 'solapaActiva';
        if(!empty($_REQUEST[$idSolapaOn]))//Si se ha cambiado la solapa activa, guardamos el nuevo valor
        	$this->setActiveTab($_REQUEST[$idSolapaOn]);
        
        
        $nombreClaseActual = get_class($this);
        //Debug:Indicamos que la accion a ejecutar
        IgepDebug::setDebug(DEBUG_IGEP,'gvHidraForm: ejecutamos acción '.$str_accion.' de la clase Manejadora '.$nombreClaseActual);
        //Cargamos el mapping dentro de comunicación para darle acceso al mismo al usuario
        $this->comunica->setMapping($actionMapping);    
        switch ($str_accion) {
            //Acción genérica de Igep que se lanzará cuando se quiera  incluir en un panel de búsqueda una lista desplegable cargada desde BD
            case 'iniciarVentana':
                $retorno = $this->initWindow();
                if($retorno==0)
        			$actionForward = $actionMapping->get('gvHidraSuccess');        	
                else{
                	if(is_object($retorno))
                		$actionForward = $retorno;
                	else
                		$actionForward = $actionMapping->get('gvHidraError');
                }
                break;
            //Acción que normalmente se dispara desde los paneles de filtro. Comprueba si la busqueda tiene parámetros y lanza la SELECT q se encuentra en el parámetro str_select                                  
            case 'buscar':
                //Como volvemos a buscar, borramos el filtro anterior.
                $this->int_filaActual = 0;
                
                //Si se quiere saltar a la edicion cuando se obtenga un unico registro, se activa
                if($this->getJumpToEditOnUniqueRecord() AND $actionMapping->containsKey('gvHidraSuccessOne'))
                	$this->activeJumpToEditOnUniqueRecord =true;
                
				//Para el caso de los patrones con registro, se utiliza la variable accionFicha para fijar el estado de la ficha. Borramos para que tenga estado nada.
				unset($this->v_datosPresentacion['accionFicha']);
                
                $resultado = $this->buildQuery();
                if(is_numeric($resultado) and $resultado==0){
                    $resultado = $this->refreshSearch();
                    //Si todo ha funcionado correctamente
                    if(is_numeric($resultado) and $resultado===0){
						//Si tiene limite
						if ($this->int_limiteConsulta != -1) {						
	        				//Comprobamos si ha sobrepasado el límite de registros
	        				if(count($this->obj_ultimaConsulta)==$this->int_limiteConsulta) {
	            				if(empty($this->obj_mensaje))
	            					$this->obj_mensaje = new IgepMensaje('IGEP-14',array($this->int_limiteConsulta));
	        				}
						}
	                    //Hay que lanzar el focusChanged si es una ficha
	                    //TODO: Toni Evento FocusChanged.
	                    //$this->obj_IgSmarty->addScriptLoad(IgepSmarty::getJsLanzarFocusChanged(),'IGEP');
	                    $actionForward = $actionMapping->get('gvHidraSuccess');
	                    //Comprobamos si obtiene un unico registro y es tabular registro con retorno gvHidraSuccessOne pasamos al modo edicion
	                    if(count($this->obj_ultimaConsulta)==1 AND ($this->getJumpToEditOnUniqueRecord() AND $actionMapping->containsKey('gvHidraSuccessOne'))) {
	                    	$actionForward = $actionMapping->get('gvHidraSuccessOne');
	                    }
	                    
                    }
                    //Si el programador indica una acción concreta
                    elseif(is_object($resultado))
                    	$actionForward = $resultado;                    
                    //Si la consulta no ha devuelto datos
                    elseif($resultado==-2){
                    	//La acción por defecto es no recargar la ventana siempre que el programador no diga lo contrario
                    	if(!$actionMapping->containsKey('gvHidraNoData')){
							//Si el programador no ha incluido un mensaje indicando que no ha devuelto datos la consulta, lo introducimos nosotros.
							if($this->obj_mensaje==null)
								$this->showMessage('IGEP-10');
                    		$actionForward = new ActionForward('gvHidraNoAction');
                    	}
                    	//Si hay un fordward del usuario, se indica
                    	else
                    		$actionForward = $actionMapping->get('gvHidraNoData');
                    }
                    else
                    	$actionForward = $actionMapping->get('gvHidraError');
                }
                else{
                	if(!is_object($resultado)){
                		$actionForward = new ActionForward('gvHidraNoAction');
                    }
                    else
                    	$actionForward = $resultado;
                    
                }
                break;
            //Acción que se lanza al paginar en un maestro para recargar los detalles
            case 'recargar':
                //Capturamos los datos de la fila Seleccionada  
                $this->comunica->construirArraySeleccionar();
                /*Recogida de los datos de phrame para montar el where de la consulta con los datos del padre*/
                $this->comunica->setOperation('seleccionar');
                $this->int_filaActual = $this->comunica->dameFilaActual();
                $m_datosPadreTpl = $this->comunica->getAllTuplas();                                                             

                //Si tenemos activado el modo lazy, tenemos que recargar las listas 
                if($this->getLazyList()) {
                	$mDatos = & $this->obj_ultimaConsulta;
                	$this->obj_ultimaConsulta = $this->generarListas($mDatos);
					//Si tenemos el lazy podemos perder algun campo. Si este campo interviene en el calculo de la clave primaria produce error.
					//Por ello cargamos la ultima consulta porque aunque hayan cambiado los datos al recargar se perderían.
                	$m_datosPadreTpl[0] = $this->obj_ultimaConsulta[$this->int_filaActual]; 
                }
                
                $resultado = $this->buildQueryDetails($m_datosPadreTpl);
                if($resultado==0)
                    $actionForward = $actionMapping->get('gvHidraSuccess');
                else
                    $actionForward = $actionMapping->get('gvHidraError');                                                                          
                break;
            //Acción que se lanza al pulsar el botón modificar de un panel con dos consultas. En este caso se lanzaría la segunda consulta que se encuentra en el parámetro str_selectEditar
            case 'editar':
            	$this->comunica->construirArraySeleccionar();
                //Fijamos la operación a seleccionar        
                $this->comunica->setOperation('seleccionar');
                $resultado = $this->buildQueryEdit();
                if($resultado==0)
                    $resultado = $this->refreshEdit();
                elseif(!is_object($resultado)){
					$actionForward = new ActionForward('gvHidraNoAction');
                    break;
                }
                if($resultado==0){
                    //TODO: Toni Evento FocusChanged.
                    //$this->obj_IgSmarty->addScriptLoad(IgepSmarty::getJsLanzarFocusChanged(),'IGEP');
                    //Para que aparezcan los botones
                    $actionForward = $actionMapping->get('gvHidraSuccess');
                }
                elseif(is_object($resultado)){
                	$actionForward = $resultado;	
                }                
                elseif($resultado==-2) {
					//La acción por defecto es no recargar la ventana siempre que el programador no diga lo contrario
                    if(!$actionMapping->containsKey('gvHidraNoData')){
						//Si el programador no ha incluido un mensaje indicando que no ha devuelto datos la consulta, lo introducimos nosotros.
						if($this->obj_mensaje==null)
							$this->showMessage('IGEP-10');
                    	$actionForward = new ActionForward('gvHidraNoAction');
					}
                    //Si hay un fordward del usuario, se indica
                    else
                    	$actionForward = $actionMapping->get('gvHidraNoData');
                }
                else
                	$actionForward = $actionMapping->get('gvHidraError');
                break;
            //Acción que realiza las tres operaciones básicas de un mantenimiento: Insertar, Borrar y Modificar.
            case 'operarBD':
                $hayDatos = 0;
                $this->comunica->data2Arrays();
                $resultado = $this->deleteSelected();
                if ($resultado==0) {
                    $resultado = $this->updateSelected();
                    if ($resultado==0) {                                        
                        $resultado = $this->insertData();
                    }
                }
                //Si el resultado es correcto
                if($resultado==0){
                    //Hay que volver a realizar la consulta del panel
                    //Si es un detalle
                    if(isset($this->str_nombrePadre)) { 
                        $this->refreshDetail();
                        $actionForward = $actionMapping->get('gvHidraSuccess');
                    }
                    else{  
                    	//No es un detalle
	                    //Si es un maestro y se han insertado datos cambiamos el filtro:
	                    //Dejamos la tupla insertada como la única activa
                        $this->refreshSearch();
                        $hayDatos = count($this->obj_ultimaConsulta);                       
                        //Si se trata de un maestro (tiene paneles dependientes)                    
//REVIEW: este codigo ya no es necesario al utilizar el setResultadoBusqueda
/*
                        if(isset($this->v_hijos)){
                            $this->int_filaActual = $this->comunica->dameFilaActual();
                            if(empty($this->int_filaActual))
                                $this->int_filaActual = 0;                          
                            $m_datos = $this->getResultForSearch();
                            $filaActiva[0] = $m_datos[$this->int_filaActual];
                            $this->buildQueryDetails($filaActiva);                       
                        }
*/
                        if($hayDatos>0) {
                            $actionForward = $actionMapping->get('gvHidraSuccess');                       
                        }                   
                        else{
                            unset($this->obj_ultimaConsulta);
                            if(!$actionMapping->containsKey('gvHidraNoData')){
                            	if(empty($this->obj_mensaje))
                                	$this->obj_mensaje = new IgepMensaje('IGEP-20');
                            	$actionForward = $actionMapping->get('gvHidraSuccess');
                            }
                            else
                            	$actionForward = $actionMapping->get('gvHidraNoData');
                        }
                    }
                    $this->v_datosPresentacion['accionFicha'] = '';
                }//Fin de resultado == 0
                else{
					if(is_object($resultado))
                    	$actionForward = $resultado; 
					else{
	                    $actionForward = new ActionForward('gvHidraNoAction');
					}
                }
                break;
            //Solo para los forms de tres modos de trabajo
            case 'modificar':
                $this->comunica->data2Arrays(); 
                $resultado = $this->updateSelected();
                if($resultado==0){          
                    if(isset($this->str_nombrePadre)) 
                        $this->refreshDetail();
                    else
                        $this->refreshSearch();                    
                    $this->refreshEdit();
                    $actionForward = $actionMapping->get('gvHidraSuccess');
                }
                else{
					if(is_object($resultado))
                    	$actionForward = $resultado; 
                    else{                    
                    	$actionForward = new ActionForward('gvHidraNoAction');
                    }
                }
                break;
            //Solo para los forms de tres modos de trabajo
            case 'insertar':
                $this->comunica->data2Arrays(); 
                $resultado = $this->insertData();
                if($resultado==0){
                    //Modifica la consulta para que en el panel lis (seleccion tabular) aparezcan SÓLO las tuplas que acabamos de insertar
                    //REVIEW: Toni&David Posibilidad de parametrizar el comportamineto de las busquedas tras acciones (Ej, busqueda anterior + inssertados)
                	//Si no es un detalle
                	$this->setResultForEdit(array());
                	if(!isset($this->str_nombrePadre)) { 
                        $this->refreshSearch();
                    }
                    else{
                        $this->refreshDetail();
                    }
                    $actionForward = $actionMapping->get('gvHidraSuccess');
                }
                else {

					if(is_object($resultado))
                    	$actionForward = $resultado; 
                    else{   
                    	$actionForward = new ActionForward('gvHidraNoAction');
                    }
                }
                break;
            //Acción que se lanza al pulsar el botón borrar de un panel. Borra los registros seleccionados.
            case 'borrar':
                //En las tablas de la ventanas de 3 paneles
                $this->comunica->construirArrayBorrar();
                $resultado = $this->deleteSelected();
                if($resultado==0){
                    if(isset($this->str_nombrePadre)){                         
                        $this->refreshDetail();
                        $actionForward = $actionMapping->get('gvHidraSuccess');
                    }
                    else{
                        $this->refreshSearch();
                        if(count($this->obj_ultimaConsulta)>0) {
                            $actionForward = $actionMapping->get('gvHidraSuccess');                       
                        }                   
                        else{
							if(!$actionMapping->containsKey('gvHidraNoData')){
								//Si el programador no ha incluido un mensaje indicando que no ha devuelto datos la consulta, lo introducimos nosotros.
								if($this->obj_mensaje==null)
									$this->showMessage('IGEP-20');
								$actionForward = $actionMapping->get('gvHidraSuccess');	
							}
							else
								$actionForward = $actionMapping->get('gvHidraNoData');	
                        }
                    }
                }
                else {                    
					if(is_object($resultado))
                    	$actionForward = $resultado; 
                    else{
	                    $actionForward = new ActionForward('gvHidraNoAction');
                    }
                }               
                break;
            //Acción que se lanza al pulsar al botón de nuevo registro en un panel. Se utiliza para cargar listas u otros componentes antes de que el usuario empiece la inserción de datos.
            case 'nuevo':                                               
                $resultado = $this->nuevo();
                if($resultado==0){
                    $actionForward = $actionMapping->get('gvHidraSuccess');
                }
                elseif(is_object($resultado)){
                	$actionForward = $resultado;	
                }
                else {
                    $actionForward = new ActionForward('gvHidraNoAction');
                }
                break;
            //Acción que elimina el contenido de la última consulta y de la última edición
            case 'cancelarTodo':                
                unset($this->str_whereFiltro);
                unset($this->str_whereFiltroEdicion);
                //unset($this->obj_ultimaConsulta);
                $this->setResultForSearch(array());
                $this->setResultForEdit(array());
                unset($this->obj_ultimaEdicion);
                //Si se trata de un maestro (tiene paneles dependientes unset a todos los hijos)
                if(isset($this->v_hijos)){
                    foreach ($this->v_hijos as $claseHija => $val)
                        IgepSession::borraPanel($claseHija);
                }                       
                $actionForward = $actionMapping->get('gvHidraSuccess');
                break;
            //Acción que elimina el contenido de la última edición  
            case 'cancelarEdicion':
                $this->v_datosPresentacion['accionFicha'] = '';
                unset($this->str_whereFiltroEdicion);
                $this->setResultForEdit(array());
                $actionForward = $actionMapping->get('gvHidraSuccess');
                break;
            default:
                $this->comunica->data2Arrays();
                $comunicaUsuario = new IgepComunicaUsuario($this->comunica,$this->v_preInsercionDatos,$this->v_listas);
		        
		        /*Validaciones de tipos de datos de gvHidra*/
		        $validacionIgep = $this->comunica->checkDataTypes();
		        if($validacionIgep!= '0') {
		        	
		            $this->showMessage('IGEP-17',array($validacionIgep));
		            $actionForward = new ActionForward('gvHidraNoAction');

		            return $actionForward;
		        }
                $actionForward = $this->accionesParticulares($str_accion, $comunicaUsuario); 
                break;                  
        }//Fin switch   
        $this->limpiarInstancia();
        //Guardamos el panel en la SESSION
        IgepSession::guardaPanel($nombreClaseActual,$this);
        //Marcamos el nombre del PANEL como panel visitado
        IgepSession::_marcarPanelVisitado($nombreClaseActual);
        return $actionForward;
    }// Fin de perform
    
    /**
    * Método que debemos sobreescribir en el caso de que se quieran incorporar acciones particulares para un panel.
    * @abstract
    */
    public function accionesParticulares($str_accion, $objDatos) {
        
		throw new Exception('Se ha intentado ejecutar la acción '.$str_accion.' y no está programada.');        
    }


  /*------------------------------METODOS DE LAS ACCIONES------------------------------*/
	/**
    * Sobrecargable, en este método implementaremos la lógica que sea necesaria ANTES de saltar hacia la clase destino (un símil de "presaltar").
    * @abstract
    * @access public
    * @param IgepComunicaUsuario $objDatos Objeto datos de acceso a la interfaz
    * @param IgepSalto $objSalto Objeto salto
    */

    public function saltoDeVentana($objDatos, $objSalto) {  
		return -1;
    }

    /**
    * Método abstracto que se debe de sobre escribir si se quiere volver de un salto de una ventana
    * @abstract
    */
    public function regresoAVentana($objDatos, $objSalto ) { 
		return 0;
    }


    /**
    * Método abstracto que se debe de sobre escribir si se quiere parametrizar la acción nuevo antes de que se lance.
    * @abstract
    */
    public function preNuevo($objDatos){   
        return 0;
    }

    /**
    * Método encargado de realizar las operaciones relativas a la acción nuevo
    * @access private
    */
    public function nuevo() {

        if(isset($_REQUEST['menuActv']))
            IgepSession::guardaVariable('global','menuActv',$_REQUEST['menuActv']);  
        $this->comunica->setOperation('postConsultar');
        $this->comunica->setArrayOperacion(array($this->v_preInsercionDatos));          

        //Guardamos los valores por defecto antes de que el usuario cambie los valores
        $antesTransformar = $this->v_preInsercionDatos;                  

        $comunicaUsuario = new IgepComunicaUsuario($this->comunica,$this->v_preInsercionDatos,$this->v_listas);
        $valido = $this->preNuevo($comunicaUsuario);    
        if($valido!= '0') {         
            return $valido;
        }       
        $datosPorDefecto = $comunicaUsuario->getAllTuplas();
        if(is_array($datosPorDefecto[0])){
	        foreach ($datosPorDefecto[0] as $campo => $valor) {
    	        //Si se trata de un nuevo campo o un nuevo valor, se suma a los datos por defecto (se transforma a presentacion)
    	        //Hacemos esta comprobacion para no transformar dos veces
    	        if(!isset($antesTransformar[$campo]) or $antesTransformar[$campo]!=$valor)
    	        	$this->addDefaultData($campo,$valor);
	        }
        }           
        //Para vaciar la última edicion -> no permitimos modificar e insertar en la misma ficha
        unset($this->obj_ultimaEdicion);
        //ATENCION: EN EL CASO DE 3 MODOS TENEMOS QUE INDICAR LA ACCION A REALIZAR Y LOS DATOS PREINSERTADOS.
        //Debemos indicar a la ficha que va a insertar
		
		//A veces la propiedad no esta fijada y provoca un warning
		$actuaSobre = null;
		if(isset($_REQUEST['actuaSobre']))        
        	$actuaSobre = $_REQUEST['actuaSobre']; 
        	
        $this->v_datosPresentacion['accionFicha'] = 'insertar';
		$this->obj_IgSmarty->addScriptLoad(IgepSmarty::getJsNuevo(count($this->v_hijos[0]),$this->str_nombrePadre,$actuaSobre),'IGEP');

        return 0;
    }//Fin de nuevo

    
    public function preIniciarVentana($objDatos){   
        return 0;
    }
    
    /**
    * Este método se utiliza en la acción genérica del mismo nombre y básicamente realiza dos acciones:
    *<ul>
    *<li>Genera todas las listas definidas para el panel y las carga en el objeto v_datosPreinsertar. De modo que si en el panel de búsqueda se quiere incluir una lista,
    * en el atributo datos de la misma, debe hacer referéncia a esta estructura.</li>
    *<li>Almacena el valor del módulo actual por si es la primera vez que se entra en una pantalla de dicho módulo.</li>
    *</ul>
    */
    public function initWindow(){
        $this->comunica->setOperation('iniciarVentana');
        $this->comunica->construirArrayIniciarVentana();
        //Comprobamos si tiene el parámetro str_select. Si no lo tiene ejecutamos refreshEmptySearch
        $comunicaUsuario = new IgepComunicaUsuario($this->comunica,$this->v_preInsercionDatos,$this->v_listas);
        $valido = $this->preIniciarVentana($comunicaUsuario);   
        if($valido!= '0') {
        	return $valido;    
        }
        if(!empty($_REQUEST['menuActv']))
            IgepSession::guardaVariable('global','menuActv',$_REQUEST['menuActv']);        
        return 0;
    }//Fin de IniciarVentana

  /*-------------------------- MÉTODOS DE CONSULTA --------------------------*/


    /**
    * Este método es el método abstracto que ofrece Igep para parametrizar el comportamiento antes lanzar la consulta de busqueda. Su utilización pude ser:
    *<ul>
    *<li>Añadir condiciones de busqueda especiales a la consulta antes de ejecutarse utilizando el método setParametrosBusqueda</li>
    *<li>Añadir constantes a la consulta antes de que se ejecute con el método addConstant</li>
    *<li>Impedir la ejecución de la consulta si se dan ciertas condiciones. Para ello debe retornar -1. Si se desea se puede crear un IgepMensaje para indicar al usuario el problema.</li>
    *<li>Fijar el límite de la consulta dependiendo de ciertos parámetros. Esto se puede hacer con el método setLimit.</li>
    *</ul>
    * <b>IMPORTANTE:</b>: El método debe devolver 0 si todo ha ido bien. En caso contrario -1 (es valido cualquier valor distinto de 0, pero recomendamos -1).
    * @param    array   Vector que contiene los valores de los campos que ha introducido el usuario en el panel de busqueda. 
    * @return integer
    * @abstract
    */  
    public function preBuscar($objDatos){    
        return 0;
    }

    /**
    * Este método es el método abstracto que ofrece Igep para parametrizar el comportamiento de la busqueda una vez se ha realizado la consulta. Su utilización pude ser:
    *<ul>
    *<li>Añadir columnas al DB:Result obtenido en la consulta</li> 
    *<li>Modificar los valores obtenidos en la consulta antes de visualizarse.</li>
    *</ul>
    * <b>IMPORTANTE:</b>: Si se quiere interrumpir la ejecución de la Busqueda, el programador debe utilizar el método setError para indicar que se ha producido un error. 
    * @param    array   Matriz de datos que contiene el resultado de la consulta realizada.
    * @return integer
    * @abstract
    */  
    public function postBuscar($objDatos){
        return 0;
    } 

    /**
    * Método que inicializa las variables para realizar la consulta correspondiente al panel de filtro.
    * Genera la Where a partir de los parámetros introducidos y modifica el filtro activo.
    * 
    * @access private
    */ 
    public function buildQuery() {

        if(isset($_REQUEST['menuActv']))
            IgepSession::guardaVariable('global','menuActv',$_REQUEST['menuActv']);  
        if(empty($this->comunica))
            $this->comunica = new IgepComunicacion($this->v_descCamposPanel);
        
        $this->comunica->setOperation('buscar');
        $this->comunica->construirArrayBuscar();
        //Comprobamos si tiene el parámetro str_select. Si no lo tiene ejecutamos refreshEmptySearch
        $comunicaUsuario = new IgepComunicaUsuario($this->comunica,$this->v_preInsercionDatos,$this->v_listas);

        /*Validaciones de tipos de datos de gvHidra*/
        $validacionIgep = $this->comunica->checkDataTypes();
        if($validacionIgep!= '0') {
            $this->showMessage('IGEP-17',array($validacionIgep));
            return -1;
        }
        
        //Ejecutamos la función preBuscar donde el usuario puede cargar ciertas cosas.    
        $valido = $this->preBuscar($comunicaUsuario);
        if($valido!= '0')
        	return $valido;

		//Si se quiere mantener el valor de los campos del filtro despues de buscar
		if($this->keepFilterValuesAfterSearch) {
						
			$fields = $comunicaUsuario->getAllTuplas();
			if(count($fields[0])>0) {
				foreach($fields[0] as $field => $value) {
					$this->addDefaultData($field,$value);
				}
			}
			
		}
		     
        $this->prepareDataSource();
        return 0;
    }
 
	/**
	* Método que se debe sobreescribir por las clases de extension para dar el
	* comportamiento a la preparación de la obtencion de datos.
	* P.E. en la extension CRUD sirve para crear la consulta SQL.
	* @return none
	* @abstract
	*/	
	public function prepareDataSource(){
		
	} 

	/**
	* Método que lanza la consulta SQL y retorna los datos 
	* @return none
	*/	 
 	public function recoverData(){
        return array();	
 	}
 	
    /**
    * Método encargado de realizar las consultas que se almacenan en el obj_ultimaConsulta
    * @param boolean $deep indica si se recarga en profundidad o no
    * @access private
    */
    public function refreshSearch($deep=true){        
		$this->obj_ultimaConsulta = $this->recoverData();
        $comunicaUsuario = new IgepComunicaUsuario($this->comunica,$this->v_preInsercionDatos,$this->v_listas);
        $res = 0;
        //Pasamos a guardamos los datos q hemos obtenido de la consulta
        //Si no hay ningun error ejecutamos el postBuscar   
        if(!PEAR::isError($this->obj_ultimaConsulta)){        
            $this->comunica->setOperation('postConsultar');
            $this->comunica->setArrayOperacion($this->obj_ultimaConsulta);
            $res = $this->postBuscar($comunicaUsuario);        
            $dataContainer = $comunicaUsuario->getAllTuplas();
        }                       
        if(PEAR::isError($dataContainer) or $this->obj_errorNegocio->hayError()){
            //La consulta es erronea.
            $this->obj_ultimaConsulta = array();
            $mensajeError[0] = 'La consulta SQL ha producido un error. Consulte con el Administrador de la aplicación.';      
            $this->showMessage('IGEP-15',$mensajeError);
            return -1;              
        }
        //Si esta activado el salto a edicion
        if($this->activeJumpToEditOnUniqueRecord and count($dataContainer)==1) {
        	$this->activeJumpToEditOnUniqueRecord = false;
        	$this->buildQueryEditAfterSearch($dataContainer);
			$this->refreshEdit();
        }
        //Fijamos la matriz resultado (transformada, con listas, recargando hijos, ...)
        $this->setResultForSearch($dataContainer,$deep);
        if (count($this->obj_ultimaConsulta)>0) {
            return $res;                                   
        }                       
        else {
            //Si $res=0 devolvemos -2 porque es correcto pero no tiene datos.
            //Sino devolvemos lo que nos haya dicho el usuario            
            if(!is_object($res) and $res==0)
            	return -2;
            else
            	return $res;
        }
    }// Fin de refreshSearch

    /**
    * Método encargado de recargar el panel maestro desde un panel detalle.
    * 
    * Lanza la consulta del panel maestro recargando asi su informacion. Tendra efecto solo si se trata de un panel detalle
    * @access public
    * @return int	devuelve 0 si todo ha ido bien. -1 si no ha podido recargar el panel maestro (no tiene panel maestro o no esta activo).
    */
	public function refreshMaster() {
		
		if(isset($this->str_nombrePadre)&& IgepSession::existePanel($this->str_nombrePadre)){

			//Recuperamos el panel maestro
			$master=IgepSession::damePanel($this->str_nombrePadre);
			
			if(is_object($master)) {
				
				$master->regenerarInstancia();
				//Compartimos conexion. De ese modo todos los campos pendientes se visualizaran en el maestro.
				$master->obj_conexion = $this->obj_conexion;
				//Recargamos el contenido sin recargar los detalles
				$master->refreshSearch(false);
				IgepSession::guardaPanel($this->str_nombrePadre,$master);
		
				return 0;
			}
		}
		
		return -1;
	}

    public function preRecargar($objDatos){ //selección del Padre
        return 0;
    }
  
    public function postRecargar($objDatos){ //obj_ultimaConsulta
        return 0;
    }

	/**
	* Método que se encarga de preparar la fuente de datos de un detalle
	* @param object detail referencia a la CM que se quiere recargar
	* @param array masterData matriz de datos del maestro 
	* @return none
	* @abstract
	*/	 
	public function prepareDataSourceDetails($detail,$masterData){
	}
	
    public function buildQueryDetails($m_datosPadreTpl){
        //buscamos los datos
        if (!empty($m_datosPadreTpl)){  
            //Para cada uno de los detalles

            foreach($this->v_hijos as $nombreClaseHija => $correspondenciaClaseHija) {
                //Solo recargamos si es el panel activo
                if($nombreClaseHija == $this->panelDetalleActivo){          
                    //Creamos las instancias de la clase hija        
                    if(IgepSession::existePanel($nombreClaseHija)){
                        IgepDebug::setDebug(DEBUG_IGEP,'Recuperamos la instancia de la clase '.$nombreClaseHija);
                        $obj_claseHija = IgepSession::damePanel($nombreClaseHija);
                        if(method_exists($obj_claseHija,'regenerarInstancia'))
                            $obj_claseHija->regenerarInstancia();
                        else{
                            IgepSession::borraPanel($nombreClaseHija);
                            IgepDebug::setDebug(PANIC,'Error al recuperar la clase manejadora '.$nombreClaseHija);
                            $obj_claseHija = new $nombreClaseHija;
                        }
                    }
                    else{
                        IgepDebug::setDebug(DEBUG_IGEP,'Creamos una instancia de la clase '.$nombreClaseHija);
                        $obj_claseHija = new $nombreClaseHija;
                        if($this->obj_errorNegocio->hayError()){
                            IgepSession::borraPanel($nombreClaseHija);
                            IgepDebug::setDebug(PANIC,'Error al crear la clase manejadora '.$nombreClaseHija);
                            $this->showMessage('IGEP-21',array($nombreClaseHija));
                            return -1;
                        }
                    }
                    //Para la clase hija vamos a insertar el array de seleccionados del padre
                    $obj_claseHija->comunica = new IgepComunicacion($obj_claseHija->v_descCamposPanel);
                    $obj_claseHija->comunica->setOperation('seleccionarPadre');
                    $obj_claseHija->comunica->setArrayOperacion($m_datosPadreTpl);
                    $comunicaUsuario = new IgepComunicaUsuario($obj_claseHija->comunica,$obj_claseHija->v_preInsercionDatos,$obj_claseHija->v_listas);
                    //Ejecutamos el evento de preRecargar               
                    $valido = $obj_claseHija->preRecargar($comunicaUsuario);
                    if($valido!= '0') {
                        //Si el programador ha insertado un mensaje lo guardamos en el panel maestro.
                        $this->obj_mensaje = $obj_claseHija->obj_mensaje;                    
                        return -1;
                    }
                    //Llamamos al metodo de preparacion de obtencion de datos
                    $this->prepareDataSourceDetails($obj_claseHija,$m_datosPadreTpl);

                    $obj_claseHija->refreshDetail();
                    //Comprobación de Errores
                    $errores = $this->obj_errorNegocio->hayError();     
                    if($errores) {
                        $this->showMessage('IGEP-13', $this->obj_errorNegocio->getDescErrorDB());                   
                        $this->obj_errorNegocio->limpiarError();
                        //Si hay error paramos la recargar devolviendo el Forward de error.
                        return -1; 
                    }
                    else {          
                        $obj_claseHija->limpiarInstancia();
                        IgepSession::guardaPanel($nombreClaseHija,$obj_claseHija);
                        $resultado = 0;
                    }
                    IgepSession::_marcarPanelVisitado($nombreClaseHija);          
                } //Fin del if (si es el panel activo)
            }//Fin del Foreach de para cada hijo
        }//Fin del If    
        return $resultado; 
    }
	
	/**
	*	Metodo que se encarga de obtener los datos del detalle 
	*/
	public function recoverDataDetail(){
		return array();
	}
		
    /**
    * Método encargado de realizar la recarga desde un hijo. Como su nombre indica se ejecuta cuando se ha realizado una operación sobre un detalle. 
    * Entonces este lanza para realizar la busqueda con los valores del padre. Lógicamente este método se suele llamar desde views. 
    * @access private
    */
    public function refreshDetail(){
	    $comunicaUsuario = new IgepComunicaUsuario($this->comunica,$this->v_preInsercionDatos,$this->v_listas);
		$this->obj_ultimaConsulta = $this->recoverDataDetail();
	    if(!PEAR::isError($this->obj_ultimaConsulta)){
	        $this->comunica->setOperation('postConsultar');
	        $this->comunica->setArrayOperacion($this->obj_ultimaConsulta);
	        $this->postRecargar($comunicaUsuario);
	        $dataContainer = $this->comunica->getAllTuplas();
	    }
	    else
	    	$dataContainer = array();
	    //Guardamos los datos       
		$this->setResultForSearch($dataContainer);
	    if (count($this->obj_ultimaConsulta)>0)     
	        return 0;                       
	    else 
	        return -1; 
    } //Fin de refreshDetail

    /**
    * Este método es el método abstracto que ofrece Igep para parametrizar el comportamiento antes lanzar la consulta del panel de Edición. Su utilización pude ser:
    *<ul>
    *<li>Añadir condiciones de busqueda especiales a la consulta de Edición antes de ejecutarse.</li>
    *<li>Añadir constantes a la consulta antes de que se ejecute</li>
    *<li>Impedir la ejecución de la consulta si se dan ciertas condiciones. Para ello debe retornar -1. Si se desea se puede crear un IgepMensaje para indicar al usuario el problema.</li> 
    *</ul>
    * <b>IMPORTANTE:</b>: El método debe devolver 0 si todo ha ido bien. En caso contrario -1 (es valido cualquier valor distinto de 0, pero recomendamos -1). 
    */
    public function preEditar($m_datos){
        return 0;
    }

    /**
    * Este método es el método abstracto que ofrece Igep para parametrizar el comportamiento de la edicion una vez se ha realizado la consulta. Su utilización pude ser:
    *<ul>
    *<li>Añadir columnas al DB:Result obtenido en la consulta de Edición</li> 
    *<li>Modificar los valores obtenidos en la consulta antes de visualizarse.</li>
    *</ul>
    * <b>IMPORTANTE:</b>: Si se quiere interrumpir la ejecución de la Edición, el programador debe utilizar el método setError para indicar que se ha producido un error. 
    * @return integer
    * @abstract
    */
    public function postEditar($m_datos){
        return 0;
    }

	public function prepareDataSourceEdit(){
		return;	
	}

    /**
    * Método que inicializa las variables para realizar la consulta correspondiente al panel de edicion.
    * Calcula la Where que se debe aplicar a la consulta de edición y cambia el filtro de edicion.
    * 
    * @access private
    */ 
    public function buildQueryEdit(){

        //Si no hay datos finalizamos
        if($this->comunica->isEmpty()){

            //Si tiene el forward gvHidraNoData devolvemos dicho forward
            try {
            	$fordward = $this->comunica->getForward('gvHidraNoData');
            }
            catch(Exception $e) {
	            //Si no tiene definido el noData, mostramos mensaje y regeneramos
	            $this->showMessage('IGEP-16');    
	            return -1;            	
            }
            if(is_object($fordward))
            	return $fordward;
            return -1;
        }
        
        $comunicaUsuario = new IgepComunicaUsuario($this->comunica,$this->v_preInsercionDatos,$this->v_listas); 
        $valido = $this->preEditar($comunicaUsuario);
        //Si valido es distinto de 0 volvemos
        if($valido!= '0') {   
            return $valido;
        }
        $this->nombreCheckTabla = $this->comunica->nombreCheckTabla;
        //REVIEW: Hay que revisar si esto funciona
        //Añadimos al vector de filas seleccionadas
        $m_datos = $this->comunica->getAllTuplas();
        $this->v_checksMarcados = array();
        foreach($m_datos as $indice=>$v_datos) {
        	array_push($this->v_checksMarcados,$v_datos);
        }
        $this->prepareDataSourceEdit();
        return 0;  
    }

    protected function buildQueryEditAfterSearch($m_datos){
    
		//Inicializamos comunicacion
    	$this->comunica->reset();
    	$this->comunica->setOperation('seleccionar');
    	$this->comunica->m_datos_seleccionarTpl = $m_datos;
    	$this->comunica->m_auxIndices_seleccionar = array(0=>0);
    	
    	$comunicaUsuario = new IgepComunicaUsuario($this->comunica,$this->v_preInsercionDatos,$this->v_listas);
    	$valido = $this->preEditar($comunicaUsuario);
    	//Si valido es distinto de 0 volvemos
    	if($valido!= '0') {
    		return $valido;
    	}
    	$this->nombreCheckTabla = $this->comunica->nombreCheckTabla;
    	//REVIEW: Hay que revisar si esto funciona
    	//Añadimos al vector de filas seleccionadas
    	$m_datos = $this->comunica->getAllTuplas();
    	$this->v_checksMarcados = array();
   		array_push($this->v_checksMarcados,$m_datos[0]);
    	$this->prepareDataSourceEdit();
    	return 0;
    }    
    
    public function recoverDataEdit(){
        return array();
    }

    /**
    * Método encargado de realizar las consultas de Edición que se almacena en el obj_ultimaEdicion.
    * @access private
    */
    public final function refreshEdit(){    
		$res = $this->recoverDataEdit();
		$comunicaUsuario = new IgepComunicaUsuario($this->comunica,$this->v_preInsercionDatos,$this->v_listas);
        //Si no hay errores entonces ejecutamos la postEdición
        if(!$this->obj_errorNegocio->hayError()){
            $this->comunica->setOperation('postConsultar');
            $this->comunica->setArrayOperacion($res);
            $this->postEditar($comunicaUsuario);
            $dataContainer = $comunicaUsuario->getAllTuplas();
        }
        if(!$this->obj_errorNegocio->hayError()){
            //Guardamos los resultados.
            $this->setResultForEdit($dataContainer);     
            //Para que adapte los datos que vienen de la Base de datos a los requerimientos de la presentación.
            if(count($this->obj_ultimaEdicion)>0){      
	            //Interacción con Pantalla
	            $this->v_datosPresentacion['accionFicha'] = 'modificar';
	            //PARCHE: Hacemos referencia a $this->nombreCheckTabla. Pendiente de cambios en Plugin      
	            //Tenemos que cargar el JS de los checks marcados
	            $filasSeleccionadas = $this->_getFilasSeleccionadas($this->nombreCheckTabla);
	            $this->obj_IgSmarty->addScriptLoad(IgepSmarty::getJsChecksMarcados($filasSeleccionadas),'IGEP');
	            return 0;
            }
            else
            	return -2;
        }
        else{
            //Si hay algún error lo indicamos con el mensaje
            $this->setResultForEdit(array());
            $this->obj_errorNegocio->setMsjError($this->obj_mensaje =new IgepMensaje());
            return -1;
        }
    }//Fin de refreshEdit

      
    /*----------------------- FUNCIONES DE OPERACIONES ----------------------*/

    /**
    * Este método es el método abstracto que ofrece Igep para realizar las validaciones previas antes de la Inserción. Su utilización pude ser:
    *<ul> 
    *<li>Impedir la ejecución de la inserción. Para ello debe retornar -1. Si se desea se puede crear un IgepMensaje para indicar al usuario el problema.</li>
    *<li>Calcular un número de secuencia de un campo de la clave primaria. Ver métodos calcularSecuencia y calcularSecuenciaBD</li>
    *</ul>
    * <b>IMPORTANTE:</b>: El método debe devolver 0 si todo ha ido bien. En caso contrario -1 (es valido cualquier valor distinto de 0, pero recomendamos -1). 
    * @return integer
    * @abstract
    */  
    public function preInsertar($objDatos) {
        return 0;
    }
	
	public function processInsert(){
		return 0;
	}
	
    /**
    * Método encargado de realizar los INSERTs
    * @access private
    */
    public function insertData(){  
        //Fijamos la operación a insertar
        $this->comunica->setOperation('insertar');
        //Comprobamos si hay datos para operar
        if($this->comunica->isEmpty())
            return 0;                       
        //Validaciones del Usuario
        $comunicaUsuario = new IgepComunicaUsuario($this->comunica,$this->v_preInsercionDatos,$this->v_listas);
        /*Validaciones de tipos de datos de gvHidra*/
        $validacionIgep = $this->comunica->checkDataTypes();
        if($validacionIgep!= '0') {
            $this->showMessage('IGEP-17',array($validacionIgep));
            return -1;
        }
        $valido = $this->preInsertar($comunicaUsuario);
        if($valido!= '0') { 
            return $valido;
        }
        /*Generamos el proceso de insercion*/
        $retorno = $this->processInsert();
        $errores = $this->obj_errorNegocio->hayError();     
        if($errores) {
            $this->obj_errorNegocio->setMsjError($this->obj_mensaje =new IgepMensaje());
            return -1;
        }
        else{        
            if(empty($retorno))
            	$retorno = 0;
            return $retorno;
        }
    }// Fin de insertar

    /**
    * Este método es el método abstracto que ofrece Igep para realizar las validaciones previas antes de la Modificación. Su utilización pude ser:
    *<ul> 
    *<li>Impedir la ejecución de la modificación por que no se cumple cierta regla de validación. Para ello debe retornar -1. Si se desea se puede crear un IgepMensaje para indicar al usuario el problema.</li>
    *<li>Modificar los datos antes de que se vayan a actualizar.</li>
    *</ul><b>IMPORTANTE:</b>: El método debe devolver 0 si todo ha ido bien. En caso contrario -1 (es valido cualquier valor distinto de 0, pero recomendamos -1). 
    * @return interger
    * @abstract
    */  
    public function preModificar($datos) {
        return 0;
    }

	public function processUpdate(){
		return 0;
	}

    /**
    * Método encargado de realizar las UPDATEs
    * @access private
    */
    public final function updateSelected(){
        //Fijamos la operación a actualizar
        $this->comunica->setOperation('actualizar');
        /*Comprobamos si hay datos para operar*/    
        if($this->comunica->isEmpty())
            return 0;   
        $comunicaUsuario = new IgepComunicaUsuario($this->comunica,$this->v_preInsercionDatos,$this->v_listas);
        /*Transformamos los datos al formato que utiliza la BD*/
        /*Validaciones de tipos de datos de gvHidra*/
        $validacionIgep = $this->comunica->checkDataTypes();
        if($validacionIgep!= '0') {
            $this->showMessage('IGEP-17',array($validacionIgep));
            return -1;
        }
        $valido = $this->preModificar($comunicaUsuario);
        if($valido!= '0') {
            return $valido; 
        }
        //Procesamos la accion
        $retorno = $this->processUpdate();
        $errores = $this->obj_errorNegocio->hayError();
        if($errores){
            $this->obj_errorNegocio->setMsjError($this->obj_mensaje =new IgepMensaje());
            return -1;
        }
        else{
            $nombreClase = get_class($this);
            $this->obj_IgSmarty->addScriptLoad(IgepSmarty::getJsFijarFichaActiva($this->comunica->damePanelActivo(),$nombreClase,$this->comunica->dameFilaActual()),'IGEP');
			if(empty($retorno))
				$retorno = 0;
            return $retorno;
        }
    }//Fin del método updateSelected

    /**
    * Este método es el método abstracto que ofrece Igep para realizar las validaciones previas antes del borrado. Su utilización pude ser:
    *<ul> 
    *<li>Impedir la ejecución del DELETE porque no se cumple cierta regla de validación. Para ello debe retornar -1. Si se desea se puede crear un IgepMensaje para indicar al usuario el problema.</li> 
    *</ul>
    * <b>IMPORTANTE:</b>: El método debe devolver 0 si todo ha ido bien. En caso contrario -1 (es valido cualquier valor distinto de 0, pero recomendamos -1). 
    * @return integer
    * @abstract
    */  
    public function preBorrar($m_datos) {
        return 0;
    }

	public function processDelete(){
		return 0;
	}

    /**
    * Método  encargado de realizar los DELETEs
    * @access   private
    */
    public final function deleteSelected(){
        //Fijamos la operación a borrar     
        $this->comunica->setOperation('borrar');
        //Comprobamos si hay datos para operar
        if($this->comunica->isEmpty())
            return 0;
        /*Validaciones de los datos*/
        //Recogemos los datos en formato tpl
        $comunicaUsuario = new IgepComunicaUsuario($this->comunica,$this->v_preInsercionDatos,$this->v_listas);
        /*Transformamos los datos al formato que utiliza la BD*/
        $valido = $this->preBorrar($comunicaUsuario);
        if($valido!= '0') {     
            return $valido;
        }
        $retorno = $this->processDelete();
        $errores = $this->obj_errorNegocio->hayError();
        if($errores) {
            $this->obj_errorNegocio->setMsjError($this->obj_mensaje =new IgepMensaje());
            return -1;
        }
        else{
            if(empty($retorno))
            	$retorno = 0;
            return $retorno;
        }   
    }//Fin método deleteSelected


    public function _getFilasSeleccionadas($nombreCheck){
        $arrayFilasSeleccionadasTpl = array();
        $filasMarcadasNegocio = $this->v_checksMarcados;
        if(isset($filasMarcadasNegocio)){
			if(isset($this->obj_ultimaConsulta)) {
	            foreach($this->obj_ultimaConsulta as $indiceFila => $fila){
	                foreach($filasMarcadasNegocio as $indiceSeleccionda => $filaSeleccionada){        
	                    $distinta = false;
	                    foreach($filaSeleccionada as $nomCampo => $valorCampo){
	                        if($fila[$nomCampo]!= $valorCampo){
	                            $distinta = true;
	                            break;
	                        }
	                    }
	                    //Esta tupla es marcable
	                    if($distinta==false){
	                        array_push($arrayFilasSeleccionadasTpl,$nombreCheck.$indiceFila);
	                        unset($filasMarcadasNegocio[$indiceSeleccionda]);
	                        break;
	                    }
	                }
	            }
			}
        }
        return $arrayFilasSeleccionadasTpl;
    }

    public function _recalcularListasDependientes($destinos,$destinosAdaptados){
        $opciones = '';
        foreach($destinos as $indice => $campoRellenar) {
            if(isset($this->v_listas[$campoRellenar])) {
                $listas = $this->v_listas;
                $objLista = & $listas[$campoRellenar];
                if($objLista->hayDependencia()){                
                    $resultado = $objLista->construyeLista($this->comunica->currentTupla());                    
                    //Si tiene el keep, almacenaremos el valor como valor por defecto.
                    if($this->keepFilterValuesAfterSearch AND $this->comunica->damePanelActivo()=='fil') {
                    	$this->addDefaultData($campoRellenar,$resultado);
                    }
                    $opciones.= IgepSmarty::getJsLista($destinosAdaptados[$indice], $resultado);
                }               
            } //Fin de listas
        }//Fin del foreach de todas las listas  
        return $opciones;
    }

    public function _accionesInterfaz($campoDisparador, $campoOrigenHtml) {    
        $jsActualizaCampo = '';

		//RECTIFICACION DE NOMBRE CAMPO EN HTML
        // Para las listas multiples. En las listas multiples el nombre nos viene con []
        // En el fil campoDisparador tiene []. En cam y ins no.
        // En el campoOrigen viene con [] y hay que quitarlo
		$auxiliar = $campoDisparador;
		if(substr($campoDisparador,-2)=='[]')
			$auxiliar = substr($campoDisparador,0,-2);
        if(isset($this->v_listas[$auxiliar]) && $this->v_listas[$auxiliar]->getMultiple()) {
        	$campoDisparador = $auxiliar;
        	$campoOrigenHtml = substr($campoOrigenHtml,0,-2);
        }
        
        // Para los checkbox en el caso de insercion
        if(@$this->v_descCamposPanel[$campoDisparador]['component']=='CheckBox') {
	        if(strpos($campoOrigenHtml,'h')===0)
	        	$campoOrigenHtml = substr($campoOrigenHtml,1);
        }        
        if(isset($this->v_validacionesUsuario[$campoDisparador])) {
            //Buscamos la función a ejecutar
            $funcionValidacion = $this->v_validacionesUsuario[$campoDisparador]['funcion'];
            //Ejecutamos la función de validacion
            $comunicaUsuario = new IgepComunicaIU($this->comunica,$this->v_preInsercionDatos,$this->v_listas,$campoOrigenHtml);            
            $comunicaUsuario->_setCampoDisparador($campoDisparador);

        	$indice = $this->comunica->getIndex();
            $this->comunica->posicionarEnTupla($indice);

            $validacion = $this->$funcionValidacion($comunicaUsuario);
            if(isset($this->obj_mensaje))
                $jsActualizaCampo.=IgepSmarty::getJsMensaje($this->obj_mensaje);
            else {
                //En caso de error
                if($validacion != 0){
                    //Si el programador no introduce un mensaje, mostramos el de por defecto            
                    $jsActualizaCampo.=IgepSmarty::getJsMensaje(new IgepMensaje('IGEP-18'));            
                }
            }
            $jsActualizaCampo.= $comunicaUsuario->getScript();
        }
        return $jsActualizaCampo;
    }

	//REVIEW Toni FocusChanged
    public function preFocusChanged($objDatos){
        return 0;
    }
  
    public function postFocusChanged($objDatos){
        return 0;
    }
  
    public function _focusChanged($composicionCampos,$filaActual, $filaProxima) {
        $jsActualizaCampo='';  
        $comunicaUsuario = new IgepComunicaIU($this->comunica,$this->v_preInsercionDatos,$this->v_listas,$composicionCampos);
        if($filaActual!=-1){
            $comunicaUsuario->posicionarEnFicha($filaActual);      
            $this->preFocusChanged($comunicaUsuario);
        }
        if($filaProxima!=-1){
            $comunicaUsuario->posicionarEnFicha($filaProxima);      
            $this->postFocusChanged($comunicaUsuario);
        }
        if(isset($this->obj_mensaje))
            $jsActualizaCampo.=IgepSmarty::getJsMensaje($this->obj_mensaje);
        $jsActualizaCampo.= $comunicaUsuario->getScript();
        return $jsActualizaCampo;   
    } 
    /*-------------------------- FUNCIONES AUXILIARES -------------------------*/

    /**
    * Método  encargado de realizar la carga de las listas definidas por el programador
    * @access private
    */
    public function generarListas($res) {

        if (count($res)>0) {     
            foreach ($this->v_listas as $campo => $lista ) {
                //Si tenemos la lista calculada previamente... no la volvemos a calcular
                if(!$lista->hayDependencia()) {
                    if (is_array($this->v_preInsercionDatos[$campo]))
                        $res2 = $this->v_preInsercionDatos[$campo];
                    else {
						//Antes de construir, comprobamos si la lista tiene conexión propia o no. Si no es así, le asignamos el objecto conexion
						if($lista->connectionOwn()==false)
							$lista->setConnection($this->getConnection());
                        $res2 = $lista->construyeLista();
                    }
                }
                //Comprobamos que el campo esté en el DB:Result que vamos a modificar
                if(array_key_exists($campo,$res[0])) {
                    //Si no tiene estructura de lista ya
					$indice = empty($this->int_filaActual)?0:$this->int_filaActual;                    
                    if(!is_array($res[$indice][$campo])){                 
                        //Realizamos la actualización del DB:Result para cada una sus filas
                        //LazyList
                        if($this->getLazyList() AND $this->isMaster()) {
	                        //Si hay dependencia hay que ejecutar la SELECT para cada fila.
	                        if ($lista->hayDependencia()) {
								//Antes de construir, comprobamos si la lista tiene conexión propia o no. Si no es así, le asignamos el objecto conexion
								if($lista->connectionOwn()==false)
									$lista->setConnection($this->getConnection());
	                            $res2 = $lista->construyeLista($res[$indice]);         
	                        }//Fin de if de dependencia             
	                        //Tratamiento: cogemos el valor y lo marcamos.
	                        $res2['seleccionado'] = $res[$indice][$campo];
	                        unset($res[$indice][$campo]);
	                        $res[$indice][$campo] = $res2;
                        }
                        else {
                        
	                        foreach(array_keys($res) as $indice){
	                            //Si hay dependencia hay que ejecutar la SELECT para cada fila.
	                            if ($lista->hayDependencia()){
									//Antes de construir, comprobamos si la lista tiene conexión propia o no. Si no es así, le asignamos el objecto conexion
									if($lista->connectionOwn()==false)
										$lista->setConnection($this->getConnection());
	                                $res2 = $lista->construyeLista($res[$indice]);                      
	                            }//Fin de if de dependencia             
	                            //Tratamiento: cogemos el valor y lo marcamos.
	                            $res2['seleccionado'] = $res[$indice][$campo];
	                            unset($res[$indice][$campo]);
	                            $res[$indice][$campo] = $res2;
	                        }//fin del foreach
                        }
                    }
                }//fin del if
            }//fin de foreach
        }//fin del if de count($res)>0  
        return $res;    
    } //Fin de generarListas


    /**
    * Método que limpia de variables inncesarias el objeto actual antes de guardarlo en la SESSION 
    * @access private
    */
    public function limpiarInstancia(){

        //Esta función se encargará de liberar de carga la instancia de la clase antes de ponerla en la SESSION
        unset($this->obj_errorNegocio);
        unset($this->comunica);
    }

    /*------------------- FUNCIONES DE AYUDA AL PROGRAMADOR -------------------*/
  
    /**
    * 
    * Método para introducir el tipo de un campo dentro de un panel gvHidra. Permitirá que el framework conozca el tipo de datos y pueda realizar
    * las siguientes operaciones por el programador:
    * - máscaras de entrada en el cliente (Javascript).
    * - validaciones en el servidor.
    * - limitar la longitud máxima del campo en pantalla (maxLength).
    * - comprobación de required en cliente y en el servidor.
    * - ordenación por tipo en modo tabla.
    * 
    *@access public
    *@param campoTpl	nombre del campo en la tpl
    *@param type		instancia de un tipo gvHidra válido. Acudir a la documentación para ver los tipos gvHidra válidos.
    *@return none 
    */
    public function addFieldType($name,$field){    

		//Comprobamos que field no sea nulo y sea un objeto
		if(is_null($field) or !is_object($field))
			throw new Exception('Error en el constructor: se ha intentado asignar un tipo de datos vacio o invalido al campo '.$name.'.');

		$nombreClaseActual = get_class($this);        
		$tipo = get_class($field);
        //Recogemos el valor obligatorio
        $required = $field->getRequired();
        //Recogemos el valor maxLength
        $maxLength =  $field->getMaxLength();
        $enableInputMask = $field->getStatusInputMask();
        //Inicializamos los valores fijos
        $this->v_descCamposPanel[$name]['required'] = $required;
        $this->v_descCamposPanel[$name]['tipo'] = $tipo;
        $this->v_descCamposPanel[$name]['maxLength'] = $maxLength;
        $this->v_descCamposPanel[$name]['enableInputMask'] = $enableInputMask;
        
        if($tipo==TIPO_CARACTER){
            $this->v_descCamposPanel[$name]['type'] = 'text';
            $regxp = $field->getRegExp();
            $inputMask = $field->getInputMask();
            $this->v_descCamposPanel[$name]['ereg'] = $regxp;
            $this->v_descCamposPanel[$name]['inputMask'] = $inputMask;
			$password=$field->getPasswordType();
			$this->v_descCamposPanel[$name]['password'] = $password;
        }        
        elseif($tipo==TIPO_FECHA OR $tipo==TIPO_FECHAHORA){
            if($tipo==TIPO_FECHA)
            	$this->v_descCamposPanel[$name]['type'] = 'date';
            else
            	$this->v_descCamposPanel[$name]['type'] = 'datetime';
            $calendario = $field->getCalendar();
            $this->v_descCamposPanel[$name]['calendar'] = $calendario;
            $dayOfWeek = $field->getDayOfWeek();
            $this->v_descCamposPanel[$name]['dayOfWeek'] = $dayOfWeek;
            $dayOfYear = $field->getDayOfYear();
            $this->v_descCamposPanel[$name]['dayOfYear'] = $dayOfYear;
        }
        elseif($tipo==TIPO_DECIMAL OR $tipo==TIPO_ENTERO){
            $this->v_descCamposPanel[$name]['type'] = 'numeric';
            $parteDecimal = $field->getFloatLength();
            $this->v_descCamposPanel[$name]['parteDecimal'] = $parteDecimal;
            $this->v_descCamposPanel[$name]['numDec'] = $parteDecimal;
            //decimalSeparator
            //thousandSeparator
        }
        elseif($tipo==TIPO_TIME){
            $this->v_descCamposPanel[$name]['type'] = 'time';
            $this->v_descCamposPanel[$name]['time24H'] = false;
        }
        else{//Si es un tipo no gvHidra	
        	if (method_exists($field,'getType'))
        	{
        		$type = $field->getType();
        		$this->v_descCamposPanel[$name]['type'] = $type;
        	}
        	else {
        		$this->v_descCamposPanel[$name]['type'] = 'text';
        	}
        }
        $object = serialize(clone($field));
        $this->v_descCamposPanel[$name]['instance'] = $object;
    }//Fin de addFieldType

    /**
    * 
    * Método que devuelve el tipo de un campo dentro de un panel gvHidra.
    * 
    *@access public
    *@param name	nombre del campo en la tpl
    *@return object
    */
	public function getFieldType($name) {  

		if(isset($this->v_descCamposPanel[$name]['instance']))
			return unserialize($this->v_descCamposPanel[$name]['instance']);
		return null;
	}

    /**
    * Función encargada de indicar a Negocio que existe una definición de una nueva Ventana Seleccion
    * @param    obj_ventanaSeleccion    objetivo de tipo gvHidraSelectionWindow.
    * @return none  
    */
    public function addSelectionWindow($obj_ventanaSeleccion){

        if(!is_object($obj_ventanaSeleccion)) {

            IgepSession::borraPanel(get_class($this));
			throw new Exception('Error: Problema al adjuntar una ventana de selección en la clase '.get_class($this).'. Debe ser del tipo gvHidraSelectionWindow.');
        }
        $this->v_ventanasSeleccion[$obj_ventanaSeleccion->getName()] = & $obj_ventanaSeleccion;
    }//Fin de addSelectionWindow

    /**
    * Función encargada de indicar a Negocio que existe una definición de una nueva Lista
    * 
    * @param    obj_lista   objetivo de tipo gvHidraList.
    * @return none 
    */
    public function addList($objLista) {

        $nombreClase = get_class($this);
        if(!is_object($objLista)) {
            
            IgepSession::borraPanel($nombreClase);
			throw new Exception('Error: Problema al adjuntar una lista en la clase '.$nombreClase.'. Debe ser del tipo gvHidraList.');
        }
        if($nombreClase=='') {

            IgepSession::borraPanel($nombreClase);
            throw new Exception('Error: Problema al adjuntar la lista '.$objLista->getName().'. Antes de definir las listas debe llamar al constructor de gvHidraForm.');
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
        $this->v_descCamposPanel[$objLista->getName()]['component'] = 'List';
        $this->v_descCamposPanel[$objLista->getName()]['multiple'] = $objLista->getMultiple();
        $this->v_descCamposPanel[$objLista->getName()]['radio'] = $objLista->getRadio();
        $this->v_descCamposPanel[$objLista->getName()]['size'] = $objLista->getSize();
         
        //REVIEW: Toni, esto creo que ya no ocurre y q se puede borrar
        //En el caso de los datalles puede darse el caso de que no pasemos por phrame (refreshDetail), y que no ejecutemos el perform. Por eso lo metemos en la SESSION.
        $datosPreInsertados = IgepSession::dameVariable($nombreClase,'v_preInsercionDatos');
        $datosPreInsertados[$objLista->getName()]=$resultadoLista;
        IgepSession::guardaVariable($nombreClase,'v_preInsercionDatos',$datosPreInsertados);
    }//Fin de addList


    /**
    * Función encargada de indicar a Negocio que existe una definición de un nuevo checkbox
    * 
    * @param    objCheckBox   objetivo de tipo gvHidraCheckBox.
    * @return none 
    */
    public function addCheckBox($objCheckBox){
        $nombreClase = get_class($this);
        
        if(!is_object($objCheckBox)) {

            IgepSession::borraPanel($nombreClase);
            throw new Exception('Error: Problema al adjuntar un CheckBox. Debe ser del tipo gvHidraCheckBox.');
        }
        
        if($nombreClase=='') {

            IgepSession::borraPanel($nombreClase);
    		throw new Exception('Error: Problema al adjuntar el Checkbox '.$objCheckBox->getName().'. Antes de definir el checkbox, debe llamar al constructor de gvHidraForm.');            
        }      

        //Guardamos la informacion del check en la estructura dataTypes.
        $this->v_descCamposPanel[$objCheckBox->getName()]['component'] = 'CheckBox';
        $this->v_descCamposPanel[$objCheckBox->getName()]['valueChecked'] = $objCheckBox->getValueChecked();
        $this->v_descCamposPanel[$objCheckBox->getName()]['valueUnchecked'] = $objCheckBox->getValueUnchecked();
        
        $this->addDefaultData($objCheckBox->getName(), $objCheckBox->getDefaultValue());
    }//Fin de addCheckBox

    
    
    /**
    * Función encargada de almacenar de la creación del array de información de la relación de un panel padre y un panel hijo
    * 
    * @param    nombreClaseManejadora   string  nombre de la clase manejadora del Hijo
    * @param    listaCamposPadre    array   array con la lista de todos los campos que necesitará el detalle para identificarse
    * @param    listaCamposHijo array   array con la lista de todos los campos que en el hijo hacen referencia a los campos del padre (en el mismo orden que el parámetro anterior).
    * @return none 
    */
    public function addSlave($nombreClaseManejadora,$listasCamposPadre,$listasCamposHijo) {     
        if(!class_exists($nombreClaseManejadora)) {

            IgepSession::borraPanel(get_class($this));
    		throw new Exception('Se ha intentado crear un IgepHijo que no corresponde a una clase de Igep. La clase '.$nombreClaseManejadora.' no es una clase válida');
        }                               
        $dependencia = array();     
        $numCamposHijos = count($listasCamposHijo);
        if((!is_array($listasCamposPadre)) or (!is_array($listasCamposHijo)) or (count($listasCamposPadre)!=$numCamposHijos)) {

            IgepSession::borraPanel(get_class($this));            
			throw new Exception('Error en la introducción de la dependencia con el panel hijo '.$nombreClaseManejadora.' . Recuerde que debe introducir dos arrays.');            
        }
        $i=0;
        for($i=0;$i<$numCamposHijos;$i++)
            $dependencia[$listasCamposPadre[$i]] = $listasCamposHijo[$i];       
        $this->v_hijos[$nombreClaseManejadora] = $dependencia;
        //Fijamos el panel como panel activo
        if(empty($this->panelDetalleActivo))
            $this->panelDetalleActivo = $nombreClaseManejadora;                         
    }//Fin de addSlave

    /**
    * Función encargada de almacenar en un panel hijo el nombre del padre (maestro)
    *  
    * @param    nombreClaseManejadora   string  nombre de la clase manejadora del Padre
    * @return none 
    */
    public function addMaster($nombreClaseManejadoraPadre) {
        if(!class_exists($nombreClaseManejadoraPadre)) {

            IgepSession::borraPanel(get_class($this));
			throw new Exception('Se ha intentado crear un IgepHijo que no corresponde a una clase de Igep. La clase '.$nombreClaseManejadoraPadre.' no es una clase válida');            
        }
        $this->str_nombrePadre = $nombreClaseManejadoraPadre;       
    }

    /**
    * Está funcion se utiliza para asociar funciones PHP desarrolladas por
    * el programador a elementos de la interfaz. Dadas las limitaciones
    * de la interfaz Web, estás acciones siempre se disparan al perder el 
    * foco el lelemento de la tpl (interfaz de usuario) elegido como primer
    * 
    * @param    campoTpl    string  indica el nombre del campo de la tpl (interfaz) sobre el que se va a lanzar la validación. Cuando pierda el foco se lanzará la validación.
    * @param    funcion string  Nombre de una función de clase que se ejecutará cuando el elemento de la interfaz pierda el foco.
    * @param    dependencia array   contiene un array de los campos de TPl que son necesarios, a parte del ya indicado en el primer parámetro, para realizar la validación.
    * @access public
    * @return none 
    */
    public function addTriggerEvent($campoTpl, $funcion){        
        
        $this->v_validacionesUsuario[$campoTpl]['funcion'] = $funcion;
    }
        
    /**
    * Función que fija (cambia) el conjunto de datos manejados por IGEP internamente
    * Se destina a uso en comportamientos muy particulares no cubiertos 
    * por la funcionalidad generica, el parámetro es una matiz de datos (dbresult),
    * que pasará a sustirtuir a la matriz que se hubiese obtenido tras realizar una consulta
    * 
    * @param    $mDatos         Array Matriz (cursor/dbResult) de datos (registros)
    * @param    $deep         boolean indica si se quiere recargar tambien los posibles hijos o no
    * @return none 
    */
    public function setResultForSearch($mDatos,$deep=true){         

		//Guardamos los datos del maestro en formato FW para poder asi utilizarlos en el caso de recarga en profundidad        
        $masterDataFW = $mDatos;
        //Para que adapte los datos que vienen de la Base de datos a los requerimientos de la presentación.
        IgepComunicaUsuario::prepararPresentacion($mDatos, $this->v_descCamposPanel);
		//Tratamiento de listas
        if (isset($this->v_listas)) 
        	$mDatos =$this->generarListas($mDatos);
        $this->obj_ultimaConsulta = & $mDatos;
        if($deep) {
	        if(isset($this->v_hijos)){


				//Recogemos el numero de filas del maestro
				$numMasterData = count($masterDataFW);
				
	            if(is_array($masterDataFW) and  $numMasterData>0) {
		            if(empty($this->int_filaActual) or ($this->int_filaActual>(--$numMasterData)))
		            	$this->int_filaActual= 0;
		            
		            $masterSelected[0] = $masterDataFW[$this->int_filaActual];
		            $this->buildQueryDetails($masterSelected);
	            }
	        }
        }
        return  0;      
    } //Fin de setResultForSearch
  
    /**
    * Función que devuelve el conjunto de datos manejados por IGEP internamente
    * Se destina a uso en comportamientos muy particulares no cubiertos 
    * por la funcionalidad generica. El valor devuelto es una matiz de datos (dbresult)
    * que corresponde con el resultado obtenido tras la busqueda.
    * 
    * @return Array Matriz (cursor/dbResult) de datos (registros)
    */
    public function getResultForSearch(){
        $m_datos = null;
        if(empty($this->obj_ultimaConsulta))
        	return array();
        foreach($this->obj_ultimaConsulta as $index => $tupla){
        	foreach($tupla as $name=>$value){
        		if(empty($this->v_descCamposPanel[$name]['tipo']))
        			$tipo = TIPO_CARACTER;
        		else
        			$tipo = $this->v_descCamposPanel[$name]['tipo'];
        		IgepComunicacion::transform_User2FW($value,$tipo);
        		$m_datos[$index][$name] = $value;
        	}
        }
        return $m_datos;
    } //Fin de getResultForSearch
  
    
    /**
    * Función que fija (cambia) el conjunto de datos manejados por IGEP internamente
    * Se destina a uso en comportamientos muy particulares no cubiertos 
    * por la funcionalidad generica, el parámetro es una matiz de datos (dbresult),
    * que pasará a sustirtuir a la matriz que se hubiese obtenido tras realizar una 
    * preseleccion de datos en una panel
    * @param    $mDatos         Array Matriz (cursor/dbResult) de datos (registros)
    * @return none 
    */
    public function setResultForEdit($mDatos){
        //Para que adapte los datos que vienen de la Base de datos a los requerimientos de la presentación.            
        IgepComunicaUsuario::prepararPresentacion($mDatos, $this->v_descCamposPanel);
		//Tratamiento de listas
	    if (isset($this->v_listas)) 
	    	$mDatos =$this->generarListas($mDatos);
		$this->obj_ultimaEdicion = & $mDatos;
        return 0;       
    } //Fin de setResultForEdit
    
    /**
    * Función que devuelve el conjunto de datos manejados por IGEP internamente
    * Se destina a uso en comportamientos muy particulares no cubiertos 
    * por la funcionalidad generica. El valor devuelto es una matiz de datos (dbresult)
    * que corresponde con el resultado obtenido tras la edicion.
    * 
    * @return Array Matriz (cursor/dbResult) de datos (registros)
    */
    public function getResultForEdit(){
        $m_datos = null;
        if(empty($this->obj_ultimaEdicion))
        	return array();        
        foreach($this->obj_ultimaEdicion as $index => $tupla){
        	foreach($tupla as $name=>$value){
        		if(empty($this->v_descCamposPanel[$name]['tipo']))
        			$tipo = TIPO_CARACTER;
        		else
        			$tipo = $this->v_descCamposPanel[$name]['tipo'];
        		IgepComunicacion::transform_User2FW($value,$tipo);
        		$m_datos[$index][$name] = $value;
        	}
        }
        return $m_datos;
    } //Fin de getResultForEdit

     /**
    * Este método crea un IgepMensaje y se lo asigna al panel. El primer parámetro corresponde
    * con el id del mensaje (ver mensaje.php) y el segundo, que es opcional, permite parametrizar
    * el mensaje pasando en un array los campos que se sustituirán en la cadena del mensaje. 
    * @param    $idMensaje  string  Cadena que contiene el id del mensaje. Ver mensaje.php
    * @param    $mDatos     Array   Array que contiene parámetros del mensaje.   
    * @return none 
    */    
	public function showMessage($idMensaje,$params=null) {
        
        $this->obj_mensaje = new IgepMensaje($idMensaje,$params);
    }
    
    public function openWindow($actionFordward){
        $path = $actionFordward->getPath();
        $this->obj_IgSmarty->addScriptLoad(IgepSmarty::getJsOpenWindow($path),'IGEP');
    }
    
    
    /***************** MODAL *********************/
    // Revisar y adaptar para una acción particular, estudiar como pasar el formulario de la ventana origen
    public function openModalWindow($actionFordward,$objSalto=null) {
        
        $path = $actionFordward->getPath();
        //Si viene de un salto almacenamos los datos para la accion de retorno
        if(is_object($objSalto)) {
        	$nomForm = $objSalto->getForm();
        	$retunPath = $objSalto->getDestinoVuelta();
        	$width = $objSalto->getWidthModal();
        	$height = $objSalto->getHeightModal();
        }
		$this->obj_IgSmarty->addScriptLoad(IgepSmarty::getJsOpenModalWindow($path,$retunPath,$nomForm,$width,$height),'IGEP');
    }
    
    public function closeModalWindow()
    {
    	$this->obj_IgSmarty->addScriptLoad('window.close();','IGEP');
    }
    /***************** MODAL *********************/

    /**
    * Este metodo permite activar el "recuerdo" en los filtros tras la búsqueda. Por defecto está desactivada,
    * pero se puede activar con este método pasandole el parametro true.
    * 
    * En el caso de que se active, es importante que se distingan los campos del fil-lis-edi para evitar cambiar
    * los campos por defecto.
    * 
    *  
    * @param    $value     boolean   
    * @return none 
    */      
    public function keepFilterValuesAfterSearch($value) {

    	$this->keepFilterValuesAfterSearch = $value;
    }

    /**
    * Este metodo indica si el panel esta abierto en una ventana modal o no.
    * 
    * @access public
    * @return bool 
    */    
    public function isModal() {
    	
    	return $this->_isModal;
    }
    
    
    /**
    * Este metodo indica si el panel ejerce de maestro en una relacion maestro-detalle
    * 
    * @access public
    * @return bool 
    */    
    public function isMaster() {

    	if(is_array($this->v_hijos) AND (count($this->v_hijos)>0))
    		return true;
    		
   		return false;	
    }

    /**
    * Este metodo indica si el panel ejerce de detalle en una relacion maestro-detalle
    * 
    * @access public
    * @return bool 
    */    
    public function isDetail() {

    	if($this->str_nombrePadre!='')
    		return true;
    		
   		return false;	
    }

    
    /**
    * Activa/desactiva la carga Lazy en los maestros. Esto permite reducir el tiempo de carga de la ventana. En los maestros detalles.
    * 
    * Se recomienda su uso si se tienen listas dependientes en los maestros y estos son en formato registro
    * 
    * @access public
	* @param    value	bool	activa la carga de las listas en modo lazy en los maestros.  
	* @return none  
    */    
    public function setLazyList($value) {
    	
    	$this->activeLazyList = $value;
    }
    
    /**
    * Obtiene el estado de la carga LazyList
    * 
    * @access private
	* @return bool  
    */    
    private function getLazyList() {
    	
    	return $this->activeLazyList;
    }

    public function setActiveTab($tab) {
    	
    	$this->activeTab = $tab;
    }
    
    public function getActiveTab() {

    	return $this->activeTab;
    }
    
    /**
     * Activa/desactiva el salto a edicion en el caso de que la busqueda devuelva un unico registro
     *
     * @access public
     * @param    value	bool	activa el salto a edicion cuando la busqueda devuelve un registro.
     * @return none
     */    
    public function setJumpToEditOnUniqueRecord($value) {
    	
    	($value)?$this->jumpToEditOnUniqueRecord = true:$this->jumpToEditOnUniqueRecord = false;
    }

    /**
     * Devuelve si la clase tiene activado el modo salto a edicion cuando devuelve un unico registro la busqueda.
     *
     * @access public
     * @return bool
     */
    public function getJumpToEditOnUniqueRecord() {

    	return $this->jumpToEditOnUniqueRecord;
    }
    
}//Fin clase gvHidraForm
?>