<?php
	$objJs = IgepSession::dameVariable(IgepSession::GVHIDRA_JUMP,'obj_jsOculto');
	$s->assign("smty_paramsSaltoModal",$objJs->getPreScript(false).$objJs->getPostScript(false));
	$s->display('igep_saltoModal.tpl');
?>