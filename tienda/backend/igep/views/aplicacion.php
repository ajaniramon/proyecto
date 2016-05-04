<?php
/**
 * Inicializa los valores de la pantalla inicial. 
 * @package	gvHIDRA 
 */
$datosAplicacion = IgepSession::dameDatosAplicacion();
$datosUsuario = IgepSession::dameDatosUsuario();
//Obtenemos los datos de  configuración
$conf = ConfigFramework::getConfig();

$s->assign("smty_usuario",$datosUsuario["nombre"]);
$s->assign("smty_aplicacion",$datosAplicacion["daplicacion"]);
$s->assign("smty_tituloApl",$conf->getApplicationName());
$s->assign("smty_version",$conf->getAppVersion());
$s->assign("smty_gvHidraVersion",$conf->getgvHidraVersion());
$s->assign("smty_ubicacion", $conf->isEnableBreadCrumb());


//TODO: parametro no usado, comento 
$s->assign("smty_codaplic",strtoupper($conf->getApplicationName()));

//Para que se pueda añadir JS en la ventana principal
if(IgepSession::existeVariable('principal','obj_IgSmarty')){
	$obj_IgepSmarty = IgepSession::dameVariable('principal','obj_IgSmarty');
	$jsLoad = $obj_IgepSmarty->getScriptLoad(false);
	$s->assign('smty_jsOnLoad',$jsLoad);
	IgepSession::borraVariable('principal','obj_IgSmarty');
}

//Para que saque el mensaje de la pantalla de error.
$mensaje = IgepSession::dameVariable('principal','obj_mensaje');
if(isset($mensaje)) {
	$tipo =  $mensaje->getTipo();
	$s->assign("smty_tipoAviso", $tipo);
	$codError =  $mensaje->getCodigo();
	$s->assign("smty_codError", $codError);
	$descBreve = $mensaje->getDescripcionCorta();
	$s->assign("smty_descBreve", $descBreve);
	$textoAviso = $mensaje->getDescripcionLarga();
	$s->assign("smty_textoAviso", $textoAviso);
	IgepSession::borraVariable('principal','obj_mensaje');
}	
//CAMBIO PARA MANTENER LIMPIA LA SESSION
//Comprobamos si hemos visitado paneles
if(IgepSession::existeVariable('global','panelesVisitados')) {
	//Borramos los paneles visitados
	IgepSession::_borrarPanelesVisitados();
	IgepSession::borraSalto();
}
//Limpiamos la variable de paneles Visitados
IgepSession::guardaVariable('global','panelesVisitados',array());

$s->display('igep_aplicacion.tpl');
?>