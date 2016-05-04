<?php
 $s->assign("smty_path",$_REQUEST['IGEPpath']);
 unset($_GET['IGEPpath']);
 $claseManejadora = $_GET['IGEPclaseManejadora']; 
 unset($_GET['IGEPclaseManejadora']);
 unset($_GET['view']);
 $parametrosGet = ''; 
 foreach($_GET as $indice => $param){
 	$parametrosGet.='&'.$indice.'='.$param;  
 }
 
 $mensajeJs = '';
 //Recuperamos mensaje
 if(IgepSession::existeMensaje($claseManejadora)){ 	
 	$mensaje = IgepSession::dameMensaje($claseManejadora); 	
 	$mensajeJs = IgepSmarty::getJsMensaje($mensaje);
 	IgepSession::borraMensaje($claseManejadora);
 }

 //Recuperamos scripts
 $jsLoadScript = null;
 $objeto = IgepSession::damePanel($claseManejadora);
 if(isset($objeto->obj_IgSmarty) and is_object($objeto->obj_IgSmarty)) { 	
 	$jsLoadScript = $objeto->obj_IgSmarty->getScriptLoad(false);
 }
  
 $s->assign("smty_mensajeJS",$mensajeJs);
 $s->assign("smty_loadScript",$jsLoadScript);
 $s->assign("smty_parametrosGet",$parametrosGet);
 $s->display('igep_regenerarVentana.tpl');
?>
