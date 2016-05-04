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
 * gvHidraTreePattern es la clase que contiene la informaci�n y el comportamiento para crear un patr�n �rbol de gvHidra 
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
    * Objeto que contiene la descripci�n del arbol gvHIDRA
    * @access private
    * @var object igepArbol   
    */        
    public $obj_arbol;

    /**
    * constructor. Generar� a partir de los par�metros que se le pasen una conexi�n a al base de datos y un
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
        //Como es una instancia de una clase hija creamos la conexi�n.      
       	$this->obj_conexion = new IgepConexion($dsn);
        	           
        //Comprobaci�n de errores de la conexion.
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
    *   M�todo que SIEMPRE se lanza cuando venimos desde phrame y que es el encargado de realizar la uni�n entre Igep y el controlador (phrame). 
    * Este m�todo comprueba cual es la acci�n a ejecutar y lanza las operaciones pertinentes.
    *   Estas acciones pueden ser acciones <i>gen�ricas</i> en cuyo caso aparecer�n en el codigo de este m�todo como entradas del switch principal; o pueden ser acciones
    *  <i>particulares</i> del panel hijo, en cuyo caso deber�n incorporarse al sobreescribiendo el m�todo comportamientosParticulares en la clase hija.  
    * La forma que proporciona phrame para que le indiquemos la direcci�n de destino son los objetos de la clase actionForward. 
    * Por esta raz�n este m�todo recoger� estos objetos y los devolver� a phrame; quien se encargar� de 
    * redirigir al navegador hasta la URL adecuada. Estos objetos actionForward se obtienen a partir del 
    * par�metro $actionMapping (que se encarga de leer el valor del mappings.php de la aplicaci�n).  
    * <br><b>IMPORTANTE:</b> Este m�todo SIEMPRE almacena en la SESSION el objeto panel actual, por ello no es 
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
        IgepDebug::setDebug(DEBUG_IGEP,'gvHidraForm_DB: ejecutamos acci�n '.$str_accion.' de la clase Manejadora '.$nombreClaseActual);
        //Cargamos el mapping dentro de comunicaci�n para darle acceso al mismo al usuario
        $this->comunica->setMapping($actionMapping);    
        switch ($str_accion) {
            //Acci�n gen�rica de Igep que se lanzar� cuando se quiera  incluir en un panel de b�squeda una lista desplegable cargada desde BD
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
            //Acci�n que normalmente se dispara desde los paneles de filtro. Comprueba si la busqueda tiene par�metros y lanza la SELECT q se encuentra en el par�metro str_select                                  
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
                    //Si el programador indica una acci�n concreta
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
            //Acci�n que elimina el contenido de la �ltima consulta y de la �ltima edici�n
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
    

  /*-------------------------- M�TODOS DE CONSULTA --------------------------*/

    /**
    * M�todo que inicializa las variables para realizar la consulta correspondiente al panel de filtro.
    * Genera la Where a partir de los par�metros introducidos y modifica el filtro activo.
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
        //Comprobamos si tiene el par�metro str_select. Si no lo tiene ejecutamos refreshEmptySearch
        $comunicaUsuario = new IgepComunicaUsuario($this->comunica,$this->v_preInsercionDatos,$this->v_listas);
        //Ejecutamos la funci�n preBuscar donde el usuario puede cargar ciertas cosas.    
        $valido = $this->preBuscar($comunicaUsuario);
        //Como no tiene consulta, acabamos.
        return $valido;
    }
 
    /**
    * M�todo encargado de realizar las consultas que se almacenan en el obj_ultimaConsulta
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
            $mensajeError[0] = 'La consulta SQL ha producido un error. Consulte con el Administrador de la aplicaci�n.';      
            $this->showMessage('IGEP-15',$mensajeError);
            return -1;              
        }        
       	return $res;
    }// Fin de buscar



    /*------------------- FUNCIONES DE AYUDA AL PROGRAMADOR -------------------*/
      
    /**
    * M�todo encargado de incorporar un componente IgepArbol a una ventana Igep
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
    * Funci�n encargada de indicar a Negocio la correspondencia de los campos de la TPL con los campos de la BD.
    * En principio s�lo deben de indicarse los campos que se almacenar�n en la BD.
    * @internal Rellena el array de matchin. Tenemos que quitar la referencia al mismo en el caso de editar.
    * 
    * @param    campoTpl    corresponde con el nombre del campo en la Tpl
    * @param    campoBD indica el nombre del campo en la tabla de la BD
    * @param    tablaBD indica el nombre de la tabla a la que corresponde.
    * @return none 
    */
    public function addMatching($campoTpl,$campoBD,$tablaBD){
		IgepSession::borraPanel(get_class($this));
        throw new Exception('Error de Programaci�n: no es posible mantener campos en una clase manejadora del patr�n �rbol.');        
    }//Fin de addMatching

    
}//Fin clase gvHidraTreePattern
?>