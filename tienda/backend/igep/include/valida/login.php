<?php
/**
 * Hace uso de la validacion basica
 *
 * @package gvHIDRA
 */

include_once('igep/include_class.php');
include_once 'igep/include/valida/AuthBasic.php';

$msg = AuthBasic::autenticate(ConfigFramework::getApplicationName());
if ($msg) {
	echo $msg;
}

?>
