
$detalles = array (
			<<foreach name=hijos from=$array_classname_detalle item=hijo>>
				array (
				"panelActivo" =>"<<$hijo|capitalize>>",
				"titDetalle" =>"<<$hijo|capitalize>>"
				)
				<<if $smarty.foreach.hijos.last>>);<<else>>,<</if>>
			<</foreach>>
	        
$panelActivo = IgepSession::dameVariable('<<$classname_maestro|capitalize>>','panelDetalleActivo');

$s->assign('smty_detalles',$detalles);
$s->assign('smty_panelActivo',$panelActivo);

$s->display('<<$nombreModulo|capitalize>>/p_<<$classname_maestro|capitalize>>.tpl');
?>

