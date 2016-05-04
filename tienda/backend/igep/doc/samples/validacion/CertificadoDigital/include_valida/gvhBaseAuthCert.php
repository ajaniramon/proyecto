<?php
/**
 * Validacion de usuarios: configuracion personalizable por el usuario
 * Clase base para crear nuevos metodos de autenticacion para gvHIDRA
 *
 * @package gvHIDRA
 */

require_once 'Auth.php';
include_once 'Auth/Container.php';

abstract class gvhBaseAuth extends Auth_Container 
{

	/**
	 * Metodo para llamar desde aplicaciones, en la autenticacion inicial
	 * 
	 * Devuelve cadena vacia si todo va bien, o texto si error
	 */
	static function autenticate($p_apli) {}

	/**
	 * Metodo para autenticar usuario 
	 */
	function fetchData($username, $password, $isChallengeResponse=false) {}

	/**
	 * Comprueba que la sesión tiene toda la información necesaria para gvHIDRA
	 * Si falta algo se produce una excepcion
	 * 
     * Información usada por gvHidra (se puede modificar la ubicación aunque habria que
     * cambiar el método correspondiente en la clase igep/include/ComunSession.php):
     *
     * $_SESSION['LINT']['daplicacion'] --> nombre de la aplicación
     * 
     * $_SESSION['LINT']['modulos'] --> matriz de módulos asignados al usuario con las siguientes columnas:
     *      $_SESSION['LINT']['modulos']['P_MODIFICA']['valor'] --> valor del módulo
     *      $_SESSION['LINT']['modulos']['P_MODIFICA']['descrip']--> descripción del módulo
     *
     * $_SESSION['LINT']['rolusuar'] --> role del usuario
     * 
     * (siendo, 'LINT' y 'P_MODIFICA' ejemplos de aplicación y módulo, respectivamente)
     * 
	 * @param mixed p_sesion
	 * @param string p_apli 
	 */
	function checkData($p_sesion, $p_apli)
	{		
		
		if (!isset($p_sesion[$p_apli]['usuario']['usuario']))
			throw new Exception('La sesión no tiene el login del usuario conectado');
		if (!isset($p_sesion[$p_apli]['usuario']['nombre']))
			throw new Exception('La sesión no tiene el nombre del usuario conectado');

		if (!isset($p_sesion[$p_apli]['rolusuar']))
			throw new Exception('La sesión no tiene el perfil del usuario conectado');
		if (!isset($p_sesion[$p_apli]['modulos']))
			throw new Exception('La sesión no tiene los módulos del usuario conectado');
		if (!isset($p_sesion[$p_apli]['daplicacion']))
			throw new Exception('La sesión no tiene el titulo de la aplicación');

		if (!isset($p_sesion['validacion']['bd']))
			throw new Exception('La sesión no tiene la fuente de autenticación');
		if (!isset($p_sesion['validacion']['server']))
			throw new Exception('La sesión no tiene el servidor www');
	}

	/**
	 * Hace una redireccion al inicio de la aplicacion
	 * pasando como argumento la direccion del metodo validacion::valida a usar
	 * Tambien se pasa la pagina actual para volver a ella cuando hacemos un logout
	 * Va en consonancia con openApp_inc.php
	 */
	function open($url, $https=null, $auth=null)
	{
		// existe login en auth?
		if (!is_null($auth) and ($log=$auth->getAuthData('login')))
			$login = $log;
		else
			$login = 'http'.(isset($_SERVER['HTTPS'])?'s':'').'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['PHP_SELF'];
		session_write_close();
		
		//Este uso es incorrecto de acuerdo al estandar actual, el cual especifica que la URI retornada debse ser absoluta. http://tools.ietf.org/html/rfc2616#section-14.30
		//Sin embargo, todos los navegadores más populares aceptan ya un URL relativa, lo cual es correcto de acuerdo al borrador de la siguiente revisión  de HTTP/1.1. http://tools.ietf.org/html/draft-ietf-httpbis-p2-semantics-22#section-7.1.2
		//El motivo de esta modificación es facilitar la integración con frontales ya que estos manejan normalmente la parte relativa de la URI.
		header('location: openApp.php?option='.urlencode($url).'&login='.urlencode($login));
	}
}

?>
