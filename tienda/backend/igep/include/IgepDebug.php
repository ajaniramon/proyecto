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
 * Creado el 25-may-2006
 *
 * Esta clase sirve para poder realizar el Debug de una aplicación Igep.
 * Almacena en una tabla de la BD las ocurrencias reseñables dependiendo
 * del nivel de sensibilidad deseado.
 *  
 * Permite controlar diferentes tipos de eventos:
 * <ul>
 * <li><b>PANIC</b>   0</li>
 * <li><b>ERROR</b>   1</li>
 * <li><b>WARNING</b> 2</li>
 * <li><b>NOTICE</b>  3</li>
 * <li><b>DEBUG_USER</b> 4</li>
 * <li><b>DEBUG_IGEP</b> 5</li>
 * </ul>
 *  
 * @version  $Id: IgepDebug.php,v 1.39 2010-05-26 10:30:54 afelixf Exp $ 
 * @author Toni: <felix_ant@gva.es> 
 * @package gvHIDRA
 */
 
 
class IgepDebug{
  
    static function setDebug($tipo,$mensaje){
		if (!is_numeric($tipo))
			throw new Exception('IgepDebug: el tipo de mensaje no está definido: '.$tipo);
        include_once "IgepConstants.php"; 
        $configuration = ConfigFramework::getConfig();
        $debug = $configuration->getLogStatus();
        //Comprobamos si tenemos que insertar
        if($tipo>=$debug)
            return;
        
        //Obtenemos los datos    
        $aplicacion = IgepSession::dameAplicacion();
        $modulo = '';
        $version = $configuration->getAppVersion();
        if (strlen($version) > 10)
        	$version = substr($version,-10);
        $usuario = IgepSession::dameUsuario();
        if (empty($usuario)) {
        	$usuario = $_SERVER['REMOTE_ADDR'];
        	if (empty($usuario))
        		$usuario = 'UNKNOWN';
        	if (class_exists('IgepWS_Server')) {
	        	$login_cred = IgepWS_Server::getUsername();
	        	if (!is_null($login_cred))
	        		$usuario = strtoupper($login_cred).'@'.$usuario;
        	}
        }
        //reemplazamos caracter octal 0 con string \000 (relacionados con serialización de objetos)
        $mensaje = str_replace("\000",'\\000',$mensaje);

        //Realizamos la insercion
        try {
        	IgepDebug::_setDB($tipo, $mensaje, $aplicacion, $modulo, $version, $usuario);
        } catch (Exception $e) {
        	error_log('Error en debug ('.$e->getMessage().') al intentar registrar: '.$mensaje);
        }
    }
  
    static function _setDB($tipo, $mensaje, $aplicacion, $modulo, $version, $usuario){
    	// variable static para controlar el acceso exclusivo al metodo, y avitar asi bucles 
    	// infinitos provocados por los propios errores dentro del metodo
    	static $excl = false;
    	static $conexion=null;
    	static $ins_prepared=null;
    	static $horabd=null;
        include_once "IgepConexion.php";
        
        $conf = ConfigFramework::getConfig();
        $dsn_log = $conf->getDSNLog(); 
        if(empty($dsn_log))
        	return;
        
        //Conexion persistente para el log 
        $conexion = $conf->getLogConnection();
        
        if(!isset($conexion) or !is_object($conexion->obj_conexion)) {

	        if ($excl === true) {
				$excl = false;
				throw new Exception('Error de conexión al debug, desactivelo (pase a LOG_NONE) o corrija el problema. Posiblemente se trata de un error en los parámetros de conexion. La descripcción del problema es: '.$conexion->obj_conexion->userinfo);
	        }

			$conexion = new IgepConexion($dsn_log,true);
			$conf->setLogConnection($conexion);
        }
        
        $excl = true;
         
		if (PEAR::isError($conexion->obj_conexion))
			throw new Exception('Error de conexión al debug, desactivelo (pase a LOG_NONE) o corrija el problema. Posiblemente se trata de un error en los parámetros de conexion. La descripcción del problema es: '.$conexion->obj_conexion->userinfo);
			
        //Obtenemos la fecha en el formato que admita la BD
        $fechabd = IgepDB::mascaraFechas($dsn_log);
        if (is_null($horabd))
        	$horabd = $conf->getTimeMask();
        $fecha = date($fechabd.' '.$horabd);

        $dbms = IgepDB::obtenerDBMS($dsn_log);
		if ($dbms == 'mysql') {
			$iderror = 'null';
		} elseif ($dbms == 'sqlsrv') {
			// nada
    	} else {
	        $sql = IgepDB::obtenerSecuenciaBD($dsn_log,'scmn_id_errlog');
	        $res = $conexion->obj_conexion->query($sql);
	        if (PEAR::isError($res)) {
	        	$excl = false;
				throw new Exception('Error al obtener secuencia de scmn_id_errlog: '.$res->userinfo);
	        }
			$iderror = $res->fetchOne('nextval');
		}

        $conexion->obj_conexion->beginTransaction();
		if (empty($ins_prepared)) {
			if ($dbms == 'sqlsrv') {
				$ins = 'INSERT INTO tcmn_errlog (aplicacion,modulo,version,usuario,fecha,tipo,mensaje) values(?,null,?,?,?,?,?)';
				$ins_prepared = $conexion->obj_conexion->prepare($ins, array('text','text','text','text','text','text'));
			} else {
				$ins = 'INSERT INTO tcmn_errlog (iderror,aplicacion,modulo,version,usuario,fecha,tipo,mensaje) values(?,?,null,?,?,?,?,?)';
				$ins_prepared = $conexion->obj_conexion->prepare($ins, array('integer','text','text','text','text','text','text'));
			}
	        if (PEAR::isError($ins_prepared)) {
	        	$excl = false;
	        	$prep = $ins_prepared;
	        	$ins_prepared = null;
		        $conexion->obj_conexion->rollback();
	        	throw new Exception('Error preparando inserción en tcmn_errlog: '.$prep->userinfo);
	        }
    	}
    	if ($dbms == 'sqlsrv')
			$res = $ins_prepared->execute(array($aplicacion,$version,$usuario,$fecha, $tipo, $mensaje));
		else
			$res = $ins_prepared->execute(array($iderror,$aplicacion,$version,$usuario,$fecha, $tipo, $mensaje));
		$excl = false;
        if (PEAR::isError($res)) {
	        $conexion->obj_conexion->rollback();
        	throw new Exception('Error al insertar en tcmn_errlog: '.$res->userinfo);
        }
        $conexion->obj_conexion->commit();
    }
}
?>
