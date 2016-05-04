<?php
/**
* @version
* @author Toni: <felix_ant@gva.es>
* @package	gvHIDRA
* Contiene todos los tipos gvHidra
**/

$al = GVHAutoLoad::singleton();
include_once "gvHidraTypeBase.php";
$al->registerClass('gvHidraDate', 'igep/include/gvh_types/gvHidraDate.php');
$al->registerClass('gvHidraDatetime', 'igep/include/gvh_types/gvHidraDatetime.php');
$al->registerClass('gvHidraFloat', 'igep/include/gvh_types/gvHidraFloat.php');
$al->registerClass('gvHidraInteger', 'igep/include/gvh_types/gvHidraInteger.php');
$al->registerClass('gvHidraString', 'igep/include/gvh_types/gvHidraString.php');
$al->registerClass('gvHidraTime', 'igep/include/gvh_types/gvHidraTime.php');

// interfaces
include_once "gvHidraType.php";
?>