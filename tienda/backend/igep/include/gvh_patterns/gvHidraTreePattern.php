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
 * gvHidraTreePattern es la clase que contiene la información y el comportamiento para crear un patrón árbol de gvHidra 
 *
 *
 * @version $Id: gvHidraTreePattern.php,v 1.9 2010-06-04 15:09:06 afelixf Exp $
 * 
 * @author Toni: <felix_ant@gva.es>
 *
 * @package gvHIDRA
 */ 
class gvHidraTreePattern extends gvHidraForm_DB
{
    
    /**
    * Objeto que contiene la descripción del arbol gvHIDRA
    * @access private
    * @var object igepArbol   
    */        
    public $obj_arbol;

    /**
    * constructor. Generará a partir de los parámetros que se le pasen una conexión a al base de datos y un
    * array de manejadores de  tablas (una por cada una de las que mantenga el panel hijo).
    */
    public function __construct($dsn=''){     
        //Guardamos la referencia del dsn principal
        $this->_dsnInterno = $dsn;
        //Guardamos la referencia al tipo de consulta
        $this->int_tipoConsulta = ConfigFramework::getConfig()->getQueryMode();
        //Creamos la instancia de IgepSmarty que controla el Js
        $this->obj_IgSmarty = new IgepSmarty();
        //Generamos la instancia completa
        $this->regenerarInstancia($dsn);
    }//Fin de constructor

    public function regenerarInstancia($dsn=''){
        //Recuperamos la instancia de la clase Error. Si no existe (caso en el que venimos de Views), lo creamos
        global $g_error;                
        //#NVI#VIEWS#: Cuando quietemos del views las llamadas a Negocio quitamos este if
        if(!isset($g_error)) 
            $g_error = new IgepError(); 
        $this->obj_errorNegocio = & $g_error;
        if($dsn=='')
            $dsn=$this->getDSN();
        //Como es una instancia de una clase hija creamos la conexión.      
       	$this->obj_conexion = new IgepConexion($dsn);
        	           
        //Comprobación de errores de la conexion.
        if($this->obj_errorNegocio->hayError()){            
            $v_descError = $this->obj_errorNegocio->getDescErrorDB();                       
            $mensajeError = new IgepMensaje('IGEP-6',$v_descError);
            IgepSession::guardaVariable('principal','obj_mensaje',$mensajeError);
            return;
        }
        //Creamos la instancia de IgepComunicacion          
        $this->comunica = new IgepComunicacion($this->v_descCamposPanel);        
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
        $nombreClaseActual = get_class($this);
        //Debug:Indicamos que la accion a ejecutar
        IgepDebug::setDebug(DEBUG_IGEP,'gvHidraForm_DB: ejecutamos acción '.$str_accion.' de la clase Manejadora '.$nombreClaseActual);
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
            case 'abrirRamaArbol':
                $this->obj_arbol->abrirRamaArbol();
                $actionForward = $actionMapping->get('gvHidraSuccess');              
                break;
            case 'cancelarArbol':
                $this->obj_arbol->cancelarArbol();
                unset($this->obj_arbol);
                $actionForward = $actionMapping->get('gvHidraSuccess');
                break;
            //Acción que normalmente se dispara desde los paneles de filtro. Comprueba si la busqueda tiene parámetros y lanza la SELECT q se encuentra en el parámetro str_select                                  
            case 'buscar':
                //Como volvemos a buscar, borramos el filtro anterior.
                $resultado = $this->buildQuery();
                if(is_numeric($resultado) and $resultado==0){
                    $resultado = $this->refreshSearch();
                    //Si todo ha funcionado correctamente
                    if(is_numeric($resultado) and $resultado===0){
	                    //TODO: Toni Evento FocusChanged.
	                    //$this->obj_IgSmarty->addScriptLoad(IgepSmarty::getJsLanzarFocusChanged(),'IGEP');
	                    $actionForward = $actionMapping->get('gvHidraSuccess');
	                    
                    }
                    //Si el programador indica una acción concreta
                    elseif(is_object($resultado))
                    	$actionForward = $resultado;                    
                    //Si la consulta no ha devuelto datos
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
            //Acción que elimina el contenido de la última consulta y de la última edición
            case 'cancelarTodo':                
                unset($this->str_whereFiltro);
                unset($this->str_whereFiltroEdicion);
                unset($this->obj_ultimaConsulta);
                unset($this->obj_ultimaEdicion);
                //Si se trata de un maestro (tiene paneles dependientes unset a todos los hijos)
                $actionForward = $actionMapping->get('gvHidraSuccess');
                break;
        }//Fin switch   
        $this->limpiarInstancia();
        //Guardamos el panel en la SESSION
        IgepSession::guardaPanel($nombreClaseActual,$this);
        //Marcamos el nombre del PANEL como panel visitado
        IgepSession::_marcarPanelVisitado($nombreClaseActual);    
        return $actionForward;
    }// Fin de perform
    

  /*-------------------------- MÉTODOS DE CONSULTA --------------------------*/

    /**
    * Método que inicializa las variables para realizar la consulta correspondiente al panel de filtro.
    * Genera la Where a partir de los parámetros introducidos y modifica el filtro activo.
    * 
    * @access private
    */ 
    public function buildQuery(){
        if(isset($_REQUEST['menuActv']))
            IgepSession::guardaVariable('global','menuActv',$_REQUEST['menuActv']);  
        if(empty($this->comunica))
            $this->comunica = new IgepComunicacion($this->v_descCamposPanel);
        $this->comunica->setOperation('buscar');
        $this->comunica->construirArrayBuscar();
        //Comprobamos si tiene el parámetro str_select. Si no lo tiene ejecutamos refreshEmptySearch
        $comunicaUsuario = new IgepComunicaUsuario($this->comunica,$this->v_preInsercionDatos,$this->v_listas);
        //Ejecutamos la función preBuscar donde el usuario puede cargar ciertas cosas.    
        $valido = $this->preBuscar($comunicaUsuario);
        //Como no tiene consulta, acabamos.
        return $valido;
    }
 
    /**
    * Método encargado de realizar las consultas que se almacenan en el obj_ultimaConsulta
    * @access private
    */
    public function refreshSearch(){        
        //Como no tiene consulta limpiamos la variable resultado 
		$this->obj_ultimaConsulta = array();
        $comunicaUsuario = new IgepComunicaUsuario($this->comunica,$this->v_preInsercionDatos,$this->v_listas);
        $res = 0;
        //Pasamos a guardamos los datos q hemos obtenido de la consulta
        //Si no hay ningun error ejecutamos el postBuscar   
        if(!PEAR::isError($this->obj_ultimaConsulta)){        
            $this->comunica->setOperation('postConsultar');
            $this->comunica->setArrayOperacion($this->obj_ultimaConsulta);
            $res = $this->postBuscar($comunicaUsuario);
        }                       
        if(PEAR::isError($this->obj_ultimaConsulta) or $this->obj_errorNegocio->hayError()){
            //La consulta es erronea.
            $mensajeError[0] = 'La consulta SQL ha producido un error. Consulte con el Administrador de la aplicación.';      
            $this->showMessage('IGEP-15',$mensajeError);
            return -1;              
        }        
       	return $res;
    }// Fin de buscar



    /*------------------- FUNCIONES DE AYUDA AL PROGRAMADOR -------------------*/
      
    /**
    * Método encargado de incorporar un componente IgepArbol a una ventana Igep
    * @param IgepArbol $arbol Instancia de la clase IgepArbol que se quiere insertar en la ventana
    * @access public
    * @return none 
    */
    protected function addArbol($arbol){
        $nombreClase = get_class($this);
        if(!IgepSession::dameVariable($nombreClase,'obj_arbol')){
            $arbol->generaXML($nombreClase);
            $this->obj_arbol = $arbol;
        }
        else
            $this->obj_arbol = IgepSession::dameVariable($nombreClase,'obj_arbol');
    }


    /**
    * Función encargada de indicar a Negocio la correspondencia de los campos de la TPL con los campos de la BD.
    * En principio sólo deben de indicarse los campos que se almacenarán en la BD.
    * @internal Rellena el array de matchin. Tenemos que quitar la referencia al mismo en el caso de editar.
    * 
    * @param    campoTpl    corresponde con el nombre del campo en la Tpl
    * @param    campoBD indica el nombre del campo en la tabla de la BD
    * @param    tablaBD indica el nombre de la tabla a la que corresponde.
    * @return none 
    */
    public function addMatching($campoTpl,$campoBD,$tablaBD){
		IgepSession::borraPanel(get_class($this));
        throw new Exception('Error de Programación: no es posible mantener campos en una clase manejadora del patrón árbol.');        
    }//Fin de addMatching

    
}//Fin clase gvHidraTreePattern
?>