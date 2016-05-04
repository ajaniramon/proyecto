<?php
/**
 * Validacion de usuarios: comprueba en cada acceso si el usuario puede entrar
 * En caso de no validado, muestra enlace a un php de igep para validarse.
 * 
 * Es una clase estática. 
 *
 * @package	gvHIDRA
 */

require_once 'AuthDg.php';

class validacion 
{	
	
   /**
    * Validación normal, con todas las comprobaciones
    * Se invoca al inicio de la aplicacion y carga la informacion del usuario en la sesion
    *
    * @access public
    */
	static function valida($apli, $sesion=TRUE) 
	{	
		$auth_container = new AuthDg();
		$aut = new Auth($auth_container,'','',false);
		
		$aut->setSessionName($apli);
		$aut->start();
		if (!$aut->checkAuth())
			exit('No estás validado; Vuelve a la pantalla de conexión.');

		if (!isset($_SESSION[$apli]['usuario'])){
			if (!isset($_SESSION[$apli]))
				$_SESSION[$apli] = array();
			$_SESSION[$apli]['usuario']['usuario'] = $aut->getUsername();
			$_SESSION[$apli] = $auth_container->postLogin($_SESSION[$apli],$aut);
			$_SESSION['validacion'] = array(
				'bd'=>'bd-prod',
				'server'=>'host-login',
			);			

			$auth_container->checkData($_SESSION, $apli);
		}
	}

}

?>