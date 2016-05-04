<?php

$datosVentana = IgepSession::damePanel('ventanaSeleccion');

//Datos propios de la ventana de Seleccion
$s->assign('smty_panelActua', $datosVentana['panelActua']);
$s->assign('smty_formActua', $datosVentana['nomForm']);
$s->assign('smty_campoActua', $datosVentana['nomCampo']);
$s->assign('smty_matching', $datosVentana['matching']);
$s->assign('smty_claseManejadora', $datosVentana['claseManejadora']);
$s->assign('smty_datosTabla', $datosVentana['resultado']);
$s->assign('smty_filaActual', $datosVentana['filaActual']);
$s->assign('smty_actionOrigen', $datosVentana['actionOrigen']);
$s->assign('stmy_numFilasPantalla', $datosVentana['rowsNumber']);
$s->assign('stmy_showInfoRowsExceeded', $datosVentana['showInfoRowsExceeded']);
$s->assign('stmy_numRegistros', count($datosVentana['resultado']));

//Cargamos la fuente de datos por template
$s->assign('stmy_templateSource', $datosVentana['templateSource']);

$unLoad = "cerrar('".$datosVentana['nomForm']."','".$datosVentana['actionOrigen']."');";
$s->assign('smty_unLoad',$unLoad);

//Datos del mensaje
if (isset($datosVentana['mensaje']))
{
	$mensaje = $datosVentana['mensaje'];
	if(isset($mensaje)) 
	{				
			$tipo =  $mensaje->getTipo();	
			$s->assign("smty_tipoAviso", $tipo);
			$codError =  $mensaje->getCodigo();
			$s->assign("smty_codError", $codError);
			$descBreve = $mensaje->getDescripcionCorta();	
			$s->assign("smty_descBreve", $descBreve);
			$textoAviso = $mensaje->getDescripcionLarga();
			$s->assign("smty_textoAviso", $textoAviso);
	}
}
IgepSession::borraPanel('ventanaSeleccion');
//Realizamos el display
$s->display('igep_ventanaSeleccion.tpl');
?>