<?php
/**
 * Cerramos la ventana. 
 * @package	gvHIDRA 
 */
//Limpiamos la SESSION
$login = IgepSession::dameVariableGlobal('gvhLogin');
IgepSession::borraAplicacion(ConfigFramework::getApplicationName());
if (isset($login)) {
	// si hay pagina de login vamos a ella
	session_write_close();
	header('location: '.$login.(strpos($login,'?')===false?'?':'&').'logout=1');
} else {
	ob_end_flush();
	$s->display('gvHidraCloseApp.tpl');
}
?>