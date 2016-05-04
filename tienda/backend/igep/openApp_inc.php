<?php
/**
* Este fichero solo se ejecuta una vez al inicio de la aplicacion
* 
* package gvHIDRA
*/

include_once('igep/include_class.php');

if (isset($_GET['as_usuario']))
	include_once('include/comun/modulos/valida.php');
else {
	$opcion = @$_GET['option'];
	$file = urldecode($opcion);
	if (isset($opcion) and file_exists($file))
		include_once($file);
	else {
		// busco ficheros login*.php en raiz
		$logins = glob('login*.php');
		if ($logins[0] and file_exists($logins[0])) {
			header('location: '.$logins[0].'?'.$_SERVER['QUERY_STRING']);
			exit;
		}
		die('Metodo de validacion incorrecto: '.var_export($opcion,true));
	}
	$login = @$_GET['login'];
}

$app = ConfigFramework::getApplicationName();
IgepSession::session_start($app);
IgepSession::clear();
validacion::valida( $app, FALSE );

if (isset($login))
	IgepSession::guardaVariableGlobal('gvhLogin',urldecode($login));

$_REQUEST['action'] = 'abrirAplicacion';
include_once('igep/phrame_inc.php');

?>
