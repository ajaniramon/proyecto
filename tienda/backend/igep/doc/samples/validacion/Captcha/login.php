<?php
/**
 * Hace uso de la validacion basica
 *
 * @package gvHIDRA
 */

include_once('igep/include_class.php');
include_once 'include/validacion/AuthDg.php';

$msg = AuthDg::autenticate(ConfigFramework::getConfig()->getApplicationName());
if ($msg) {
	echo $msg;
}

?>
