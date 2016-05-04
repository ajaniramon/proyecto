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
 * Creado el 25-sep-2006
 *
 * Clase que se encarga de dar el comportamiento genérico de un Servidor WS en PHP 
 * 
 * @author  Toni felix_ant@gva.es
 * @version $Id: IgepWS_Server.php,v 1.13 2010-01-26 13:26:22 gaspar Exp $
 * @package gvHIDRA
 */

////////////////////////////////////////////////////////
// inicializacion hecha como en limpiar_smarty_inc.php
require_once('igep/include_class.php');
IgepSession::session_start();
require_once('igep/include_all.php');
$aux = new AppMainWindow();
////////////////////////////////////////////////////////

// parche obtenido de la web de pear, para que se puedan generar Soap_Fault's desde el servidor
// Obviamente, esto anula informacion de traza, aunque es lo que recomiendan para produccion
$skiptrace = &PEAR::getStaticProperty('PEAR_Error', 'skiptrace');
$skiptrace = true;

 
class IgepWS_Server {
    
    protected $idWS;    
    protected $msgs;    
    static private $username;

	/**
	 * Constructor: incluye el código que genera el WSDL.
	 */
    function __construct($idWS, $msgs=null){
        //Creamos la variable de Error
        global $g_error;
        $g_error = new IgepError();
        //Almacenamos la referencia al identificador WS
        if (is_array($idWS))
	        $this->idWS = $idWS;
		else
			$this->idWS = array($idWS,);
        //Exportamos la estructura credencialCIT para que los consumidores puedan crearla
        $this->__typedef['{http://WS_CIT_Credencial.cit.gva.es/xsd}credencialCIT'] = 
            array(
                'login' => 'string',
                'password' => 'string' 
                );
        // guardar mensajes
        $this->msgs = $msgs;
        self::$username = null;
    }
    
    /**
     * Este método se encarga de realizar la validacion
     * 
     * @param mixed credencial que recibe el metodo del ws
     * @param array credenciales autorizadas, opcional
     * @return boolean indica si la credencial es valida
     */
    protected function checkCredential($credencial, $allowed=array())
    {
		if (!is_object($credencial) or !property_exists($credencial,'login'))
			return FALSE;
		self::$username = $credencial->login;
		if (empty($allowed))
    		$allowed = $this->idWS;
    	elseif (!is_array($allowed))
    		return FALSE;
    	foreach ($allowed as $cred) {
			$valido = $this->getCredential($cred);
			if($credencial->login==$valido['username'] AND $this->hashData($credencial->password)==$valido['password'])
				return TRUE;
	    }
	    return FALSE;
    }

	/**
	 * Metodo usado para ocultar informacion de passwords de WS
	 * La implementación debe coincidir con el formulario en igep/include/igep_utils/protectdata.php
	 */
	static function hashData($str) {
		if (empty($str))
			return '';
		return md5('salto-pre'.$str.'salto-post');
	}

    private function getCredential($idWS){
		return ConfigFramework::getConfig()->getDSN($idWS);
    }

    static function getUsername(){
		return self::$username;
    }
    
    /**
     * Devuelve un mensaje SOAP que indica al cliente que tiene un error de validación
     * 
     * @return Soap_Fault error indicando que la validacion es incorrecta
     */
    protected function getAuthError(){
        return new SOAP_Fault('No esta autorizado para este Web Service',
                              'Cliente',
                              $this->method_namespace,
                              NULL);
    }
    
    /**
     * Devuelve un objeto IgepConexion o false si hay error
     * 
     * @param mixed dsn
     * @return IgepConexion|false
     */
    protected function conectar($dsn){
        $conexion = new IgepConexion($dsn);
        if(PEAR::isError($conexion->getPEARConnection()))
            return FALSE;
        return $conexion;
    }

	/**
	 * Convierte un texto del encoding del fw (latin1) a utf-8
	 * Si el fw no usara latin1 habria que convertir con iconv
	 */
	function encode($text) {
		return utf8_encode($text);
	}

	/**
	 * Convierte un texto en uft8 al encoding del fw (latin1)
	 * Si el fw no usara latin1 habria que convertir con iconv
	 */
	function decode($text) {
		return utf8_decode($text);
	}

	/**
	 * Genera un fallo soap
	 * @param id: clave del error en el array asociativo de mensajes
	 * @param: mgs: texto adicional opcional que saldrá en el debug
	 */
	function gvhFault($id, $msg=null) {
		$txt = $this->encode($this->msgs[$id]);
		if ($msg)
			IgepDebug::setDebug(ERROR, $msg);
		return new SOAP_Fault($txt, $id, $this->method_namespace, NULL);		
	}

}
?>