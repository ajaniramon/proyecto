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
 * Clase que se encarga de crear un Servidor WS en IGEP 
 * 
 * @author  Toni felix_ant@gva.es
 * @version $Id: IgepWS_ServerBase.php,v 1.7 2009-10-01 12:38:23 gaspar Exp $
 * @package gvHIDRA
 */
  
require_once 'SOAP/Server.php';
require_once 'igep/include/igep_ws/IgepWS_Server.php';

 class IgepWS_ServerBase {
    function registrar($nombreWS){
        //Comprobamos que la clase que se ha definido existe
        global $HTTP_RAW_POST_DATA;
        if(!class_exists($nombreWS))
            throw new Exception('La clase '.$nombreWS.' no existe. Compruebe que esta clase tiene acceso a ella.');
        $server = new SOAP_Server;
        $server->_auto_translation = true;
        $soapclass = new $nombreWS;
        $server->addObjectMap($soapclass,'urn:'.$nombreWS);
        //Limpiamos el bufer para evitar problemas
        ob_clean();
        //Este código sirve para generar el WSDL
        if(isset($_REQUEST['wsdl'])){ 
            require_once 'SOAP/Disco.php';
            $disco = new SOAP_DISCO_Server($server,'Server'.$nombreWS);
            header("Content-type: text/xml");
            echo $disco->getWSDL();
            exit;
        }
		try {
			$server->service($HTTP_RAW_POST_DATA);
		} catch (Exception $e) {
			IgepDebug::setDebug(PANIC,'Ha ocurrido una excepción no capturada (web service):<pre>'.$e.'</pre>');
			// se genera mensaje ocultando el error al llamante
			$e2 = new Soap_Fault($soapclass->encode('Error interno en servidor'));
			echo $e2->message();
		}
    }    
 }
 
?>