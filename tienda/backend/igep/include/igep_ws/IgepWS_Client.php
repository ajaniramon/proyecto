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
 * Clase que se encarga de dar el comportamiento genérico de un consumidor de WS desde Igep 
 * 
 * @author  Toni felix_ant@gva.es
 * @version $Id: IgepWS_Client.php,v 1.8 2009-08-06 11:29:18 gaspar Exp $
 * @package gvHIDRA
 */
 
class IgepWS_Client {

    /**
     * Dado un WSDL devuelve un cliente para el mismo
     * @param string ficheroWSDL Fichero o ubicación del WSDL que define el WS
     * @param mixed opciones que se pasan a SoapClient
     * @return object Objeto WS
     */    
    function getClient($ficheroWSDL, $options=null)
    {
    	if (is_null($options))
	        $options = array();
	    if (!isset($options['encoding']))
	    	$options['encoding'] = 'latin1';
        $client = new SoapClient($ficheroWSDL, $options);
        return $client;
    }

    /**
     * Este método se encarga de devolver la credencial de usuario
     * @param string idWS Identificador del WS del que se requieren las credenciales
     * @return array array que contiene las credenciales
     */
    function getCredential($idWS)
    {
        $conf = ConfigFramework::getConfig();
        $wsDef = $conf->getDSN($idWS);   
        return $wsDef;
    }

    /**
     * Este método elimina la cache del cliente con lo que podemos hacer cambios en el wsdl
     * Hay que llamarlo antes de 'getClient'
     */
    static function disableCache()
    {
		ini_set('soap.wsdl_cache_ttl', '0');
    }

}
?>