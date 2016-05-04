<?php
	$s->assign("smty_formulario",IgepSession::dameVariable('camposDependientes','formulario')); 	
	$s->assign("smty_origen",IgepSession::dameVariable('camposDependientes','origen'));
	$objJs = IgepSession::dameVariable('camposDependientes','obj_jsOculto');
	$s->assign("smty_insertarOpciones",$objJs->getPreScript(false).$objJs->getPostScript(false));
	$s->display('igep_actualizar.tpl');
?>