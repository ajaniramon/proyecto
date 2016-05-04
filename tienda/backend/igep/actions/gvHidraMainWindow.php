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
* gvHidraMainWindow contiene el código de la ventana inicial de la aplicación. Controla la entrada de la aplicación, 
* la salida y el paso por la ventana de inicio.
* 
*
* @version	$Id: gvHidraMainWindow.php,v 1.8 2010-06-04 15:08:42 afelixf Exp $
* 
* @author David: <pascual_dav@gva.es> 
* @author Vero: <navarro_ver@gva.es>
* @author Raquel: <borjabad_raq@gva.es> 
* @author Toni: <felix_ant@gva.es>
*
* @package	gvHIDRA
*/ 
class gvHidraMainWindow extends Action {
	
    /**
    * variable de error
    *
    * @var object obj_errorNegocio
    */		
	var $obj_errorNegocio;
			
	/** 
	* Variable que contendrá el posible mensaje a enviar al panel. Tiene que ser de la clase IgepMensaje
	*
	* @var	object	$obj_mensaje 
	*/  	
	var $obj_mensaje;
	
	/**
	* Objeto que permite manejar/registrar el javascript de esta clase
	* @access private
	* @var object igepArbol	
	*/		
	var $obj_IgSmarty;

	/**
	* constructor. Generará a partir de los parámetros que se le pasen una conexión a al base de datos y un
	* array de manejadores de  tablas (una por cada una de las que mantenga el panel hijo).
	*/
    public function __construct(){

        global $g_error;
        if(!isset($g_error)) 
            $g_error = new IgepError();	
        $this->obj_errorNegocio = & $g_error;
    }

    /**
    * Método que se ejecuta tras el constructor y que permite seleccionar la acción a realizar.
    * En esta clase se encuentran las siguientes acciones genéricas:
    * <ul>
    * <ui>camposDependientes: recalcula listas dependientes y dispara acciones de interfaz.</ui>
    * <ui>abrirVentanaSeleccion: abre la ventana de Selección.</ui>
    * <ui>buscarVentanaSeleccion: realiza la busqueda en la ventana de Selección.</ui>
    * </ul>
    */
    function perform($actionMapping, $actionForm) {						
        //Recogemos la accion y le quitamos el prefijo que nos viene de la ventana		
        $str_accion = $actionForm->get('action');
        //Debug:Indicamos que entramos en Negocio y la accion a ejecutar
        IgepDebug::setDebug(5,'gvHidraMainWindow: ejecutamos acción '.$str_accion);    
        switch ($str_accion) {
            case 'abrirAplicacion':
				$comunica = new IgepComunicacion(null);
				//Cargamos los posibles Forwards
				$comunica->setMapping($actionMapping);
				//Parseamos el REQUEST
				$comunica->setOperation('iniciarVentana');
        		$comunica->construirArrayIniciarVentana();
        		//Parametros no utilizados a null
        		$vacio= null;
        		$comunicaUsuario = new IgepComunicaUsuario($comunica,$vacio,$vacio);

				//llamamos a un método para que los sobreescriban si quieren añadir comportamiento.
				$res = $this->openApp($comunicaUsuario);
				//Permite redirigir la entrada a un actionForward definido por el usuario
				if(is_object($res))
                	$actionForward = $res;
				elseif($res == -1)
					$actionForward = $actionMapping->get('gvHidraCloseApp');
				else
                	$actionForward = $actionMapping->get('gvHidraOpenApp');
                break;
            case 'cerrarAplicacion':
				$res = $this->closeApp();
				if(is_object($res))
                	$actionForward = $res;
				elseif($res == -1){
					$actionForward = new ActionForward('gvHidraNoAction');
				}
				else
                	$actionForward = $actionMapping->get('gvHidraCloseApp');
                break;
            default:
                throw new Exception('Error: La acción '.$str_accion.' no se reconoce.');
                break;
        }//Fin switch
        IgepSession::_guardaPanelIgep('principal',$this);
        return $actionForward;
    }// Fin de perform

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

    /**
    * Método virtual que se sobrescribe en los hijos para poder cambiar el comportamiento en la apertura de la aplicación 
    * @return int 
    */  
	public function openApp($objDatos) {
		return 0;	
	}

    /**
    * Método virtual que se sobrescribe en los hijos para poder cambiar el comportamiento del cierre de la aplicación 
    * @return int 
    */  
	public function closeApp() {
		return 0;	
	}



}//Fin clase gvHidraMainWindow
?>