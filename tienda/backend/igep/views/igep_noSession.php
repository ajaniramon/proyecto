<?php
include_once('igep/include_class.php');
 
$s = new Smarty_Phrame();

$login = IgepSession::dameVariableGlobal('gvhLogin');
IgepSession::borraAplicacion(ConfigFramework::getApplicationName());
if (isset($login)) {
	// si hay pagina de login vamos a ella
	$s->assign("smty_path",$login.(strpos($login,'?')===false?'?':'&').'logout=1');
	;
}
else {	
	if(file_exists ('login.php'))
		$s->assign("smty_path",'login.php?logout=1');
	else
		$s->assign("smty_path",'index.php?view=igep/views/aplicacion.php');		
}
session_write_close();
$s->display('igep_noSession.tpl');
?>