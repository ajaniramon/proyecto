<?php /* Smarty version 2.6.14, created on 2016-05-12 16:02:25
         compiled from Pedidos/p_PedidoMaestro.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'CWVentana', 'Pedidos/p_PedidoMaestro.tpl', 1, false),array('block', 'CWBarra', 'Pedidos/p_PedidoMaestro.tpl', 2, false),array('block', 'CWMarcoPanel', 'Pedidos/p_PedidoMaestro.tpl', 5, false),array('block', 'CWPanel', 'Pedidos/p_PedidoMaestro.tpl', 9, false),array('block', 'CWBarraSupPanel', 'Pedidos/p_PedidoMaestro.tpl', 10, false),array('block', 'CWContenedor', 'Pedidos/p_PedidoMaestro.tpl', 14, false),array('block', 'CWFicha', 'Pedidos/p_PedidoMaestro.tpl', 15, false),array('block', 'CWBarraInfPanel', 'Pedidos/p_PedidoMaestro.tpl', 30, false),array('block', 'CWTabla', 'Pedidos/p_PedidoMaestro.tpl', 41, false),array('block', 'CWFila', 'Pedidos/p_PedidoMaestro.tpl', 42, false),array('block', 'CWContenedorPestanyas', 'Pedidos/p_PedidoMaestro.tpl', 60, false),array('function', 'CWMenuLayer', 'Pedidos/p_PedidoMaestro.tpl', 3, false),array('function', 'CWBotonTooltip', 'Pedidos/p_PedidoMaestro.tpl', 11, false),array('function', 'CWCampoTexto', 'Pedidos/p_PedidoMaestro.tpl', 18, false),array('function', 'CWBoton', 'Pedidos/p_PedidoMaestro.tpl', 31, false),array('function', 'CWPaginador', 'Pedidos/p_PedidoMaestro.tpl', 50, false),array('function', 'CWPestanya', 'Pedidos/p_PedidoMaestro.tpl', 61, false),)), $this); ?>
<?php $this->_tag_stack[] = array('CWVentana', array('tipoAviso' => $this->_tpl_vars['smty_tipoAviso'],'codAviso' => $this->_tpl_vars['smty_codError'],'descBreve' => $this->_tpl_vars['smty_descBreve'],'textoAviso' => $this->_tpl_vars['smty_textoAviso'],'onLoad' => $this->_tpl_vars['smty_jsOnLoad'])); $_block_repeat=true;smarty_block_CWVentana($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start();  $this->_tag_stack[] = array('CWBarra', array('usuario' => $this->_tpl_vars['smty_usuario'],'codigo' => $this->_tpl_vars['smty_codigo'],'customTitle' => $this->_tpl_vars['smty_customTitle'],'modal' => $this->_tpl_vars['smty_modal'],'iconOut' => "glyphicon glyphicon-log-out",'iconHome' => "glyphicon glyphicon-home",'iconInfo' => "glyphicon glyphicon-info-sign")); $_block_repeat=true;smarty_block_CWBarra($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>	
	<?php echo smarty_function_CWMenuLayer(array('name' => ($this->_tpl_vars['smty_nombre']),'cadenaMenu' => ($this->_tpl_vars['smty_cadenaMenu'])), $this);?>
	
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWBarra($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack);  $this->_tag_stack[] = array('CWMarcoPanel', array('conPestanyas' => 'true')); $_block_repeat=true;smarty_block_CWMarcoPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

<!-- ********************************************** MAESTRO **********************************************-->
	<!--*********** PANEL fil ******************-->
	<?php $this->_tag_stack[] = array('CWPanel', array('id' => 'fil','action' => 'buscar','method' => 'post','estado' => $this->_tpl_vars['estado_fil'],'claseManejadora' => 'PedidoMaestro')); $_block_repeat=true;smarty_block_CWPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		<?php $this->_tag_stack[] = array('CWBarraSupPanel', array('titulo' => 'Filtrar pedido')); $_block_repeat=true;smarty_block_CWBarraSupPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php echo smarty_function_CWBotonTooltip(array('imagen' => '01','iconCSS' => "glyphicon glyphicon-plus",'titulo' => 'Insertar registros','funcion' => 'insertar','actuaSobre' => 'ficha','action' => 'PedidoMaestro__nuevo'), $this);?>

			<?php echo smarty_function_CWBotonTooltip(array('imagen' => '04','iconCSS' => "glyphicon glyphicon-refresh",'titulo' => 'Restaurar valores','funcion' => 'restaurar','actuaSobre' => 'ficha'), $this);?>

		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWBarraSupPanel($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php $this->_tag_stack[] = array('CWContenedor', array()); $_block_repeat=true;smarty_block_CWContenedor($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php $this->_tag_stack[] = array('CWFicha', array()); $_block_repeat=true;smarty_block_CWFicha($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
				<table class="text" cellspacing="4" cellpadding="4" border="0">
					<tr>
					 	<td><?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Fecha','nombre' => 'fil_fecha','size' => '0','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_PedidoMaestro']['fil_fecha'],'dataType' => $this->_tpl_vars['dataType_PedidoMaestro']['fil_fecha']), $this);?>
</td>
					</tr>
					<tr>
					 	<td><?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Importe total','nombre' => 'fil_total','size' => '7','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_PedidoMaestro']['fil_total'],'dataType' => $this->_tpl_vars['dataType_PedidoMaestro']['fil_total']), $this);?>
</td>
					</tr>
					<tr>
					 	<td><?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'DNI','nombre' => 'fil_dni','size' => '9','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_PedidoMaestro']['fil_dni'],'dataType' => $this->_tpl_vars['dataType_PedidoMaestro']['fil_dni']), $this);?>
</td>
					</tr>
				</table>
				<br/>
			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWFicha($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWContenedor($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php $this->_tag_stack[] = array('CWBarraInfPanel', array()); $_block_repeat=true;smarty_block_CWBarraInfPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php echo smarty_function_CWBoton(array('imagen' => '50','iconCSS' => "glyphicon glyphicon-search",'texto' => 'Buscar','class' => 'button','accion' => 'buscar','mostrarEspera' => 'true'), $this);?>

		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWBarraInfPanel($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>						
	<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWPanel($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
	
	<!--*********** PANEL lis ******************-->	
	<?php $this->_tag_stack[] = array('CWPanel', array('id' => 'lis','tipoComprobacion' => 'envio','esMaestro' => 'true','itemSeleccionado' => $this->_tpl_vars['smty_filaSeleccionada'],'action' => 'operarBD','method' => 'post','estado' => $this->_tpl_vars['estado_lis'],'claseManejadora' => 'PedidoMaestro')); $_block_repeat=true;smarty_block_CWPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		<?php $this->_tag_stack[] = array('CWBarraSupPanel', array('titulo' => 'Pedido')); $_block_repeat=true;smarty_block_CWBarraSupPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			
		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWBarraSupPanel($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php $this->_tag_stack[] = array('CWContenedor', array()); $_block_repeat=true;smarty_block_CWContenedor($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php $this->_tag_stack[] = array('CWTabla', array('conCheck' => 'true','conCheckTodos' => 'false','id' => 'Tabla1','numFilasPantalla' => '6','datos' => $this->_tpl_vars['smty_datosTablaM'])); $_block_repeat=true;smarty_block_CWTabla($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
				<?php $this->_tag_stack[] = array('CWFila', array('tipoListado' => 'false')); $_block_repeat=true;smarty_block_CWFila($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
					<?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Idpedido','nombre' => 'lis_idpedido','size' => '4','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_PedidoMaestro']['idpedido'],'dataType' => $this->_tpl_vars['dataType_PedidoMaestro']['idpedido']), $this);?>

					<?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Fecha','nombre' => 'lis_fecha','size' => '0','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_PedidoMaestro']['lis_fecha'],'dataType' => $this->_tpl_vars['dataType_PedidoMaestro']['lis_fecha']), $this);?>

					<?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Total','nombre' => 'lis_total','size' => '7','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_PedidoMaestro']['lis_total'],'dataType' => $this->_tpl_vars['dataType_PedidoMaestro']['lis_total']), $this);?>

					<?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Dni','nombre' => 'lis_dni','size' => '9','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_PedidoMaestro']['lis_dni'],'dataType' => $this->_tpl_vars['dataType_PedidoMaestro']['lis_dni']), $this);?>

					<?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Nombre','nombre' => 'lis_nombre','size' => '20','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_PedidoMaestro']['lis_nombre'],'dataType' => $this->_tpl_vars['dataType_PedidoMaestro']['lis_nombre']), $this);?>


				<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWFila($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>				
				<?php echo smarty_function_CWPaginador(array('enlacesVisibles' => '3','iconCSS' => 'true'), $this);?>

			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWTabla($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWContenedor($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php $this->_tag_stack[] = array('CWBarraInfPanel', array()); $_block_repeat=true;smarty_block_CWBarraInfPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php echo smarty_function_CWBoton(array('imagen' => '41','iconCSS' => "glyphicon glyphicon-ok",'texto' => 'Guardar','class' => 'button','accion' => 'guardar'), $this);?>

			<?php echo smarty_function_CWBoton(array('imagen' => '42','iconCSS' => "glyphicon glyphicon-remove",'texto' => 'Cancelar','class' => 'button','accion' => 'cancelar'), $this);?>

		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWBarraInfPanel($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>						
	<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWPanel($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
	
	<!-- ****************** PESTA�AS MAESTRO ************************-->	
	<?php $this->_tag_stack[] = array('CWContenedorPestanyas', array('id' => 'Maestro')); $_block_repeat=true;smarty_block_CWContenedorPestanyas($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>				
		<?php echo smarty_function_CWPestanya(array('tipo' => 'fil','panelAsociado' => 'fil','estado' => $this->_tpl_vars['estado_fil'],'ocultar' => 'Detalle'), $this);?>

		<?php echo smarty_function_CWPestanya(array('tipo' => 'lis','panelAsociado' => 'lis','estado' => $this->_tpl_vars['estado_lis'],'mostrar' => 'Detalle'), $this);?>

	<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWContenedorPestanyas($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</td></tr>
<tr><td>																																									
<!-- ************************************ DETALLE *****************************************-->
	<!--*********** PANEL lis ******************-->
	<?php if (count ( $this->_tpl_vars['smty_datosTablaM'] ) > 0): ?>
	<?php $this->_tag_stack[] = array('CWPanel', array('id' => 'lisDetalle','detalleDe' => 'lis','tipoComprobacion' => 'envio','action' => 'operarBD','method' => 'post','estado' => 'on','claseManejadora' => 'PedidoDetalle')); $_block_repeat=true;smarty_block_CWPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		<?php $this->_tag_stack[] = array('CWBarraSupPanel', array('titulo' => 'Detalles del pedido')); $_block_repeat=true;smarty_block_CWBarraSupPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWBarraSupPanel($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php $this->_tag_stack[] = array('CWContenedor', array()); $_block_repeat=true;smarty_block_CWContenedor($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>			
			<?php $this->_tag_stack[] = array('CWTabla', array('conCheck' => 'true','id' => 'TablaDetalle','numFilasPantalla' => '6','datos' => $this->_tpl_vars['smty_datosTablaD'])); $_block_repeat=true;smarty_block_CWTabla($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
				<?php $this->_tag_stack[] = array('CWFila', array('tipoListado' => 'false')); $_block_repeat=true;smarty_block_CWFila($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
					<?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'nombre','nombre' => 'lis_nombre','size' => '40','editable' => 'true','value' => $this->_tpl_vars['defaultData_PedidoDetalle']['nombre'],'dataType' => $this->_tpl_vars['dataType_PedidoDetalle']['nombre']), $this);?>

					<?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Unidad','nombre' => 'lis_unidad','size' => '4','editable' => 'true','value' => $this->_tpl_vars['defaultData_PedidoDetalle']['lis_unidad'],'dataType' => $this->_tpl_vars['dataType_PedidoDetalle']['lis_unidad']), $this);?>

					<?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Precio','nombre' => 'lis_precio','size' => '7','editable' => 'true','value' => $this->_tpl_vars['defaultData_PedidoDetalle']['lis_precio'],'dataType' => $this->_tpl_vars['dataType_PedidoDetalle']['lis_precio']), $this);?>

					<?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'PrecioTotal','nombre' => 'lis_precioTotal','size' => '7','editable' => 'true','value' => $this->_tpl_vars['defaultData_PedidoDetalle']['lis_precioTotal'],'dataType' => $this->_tpl_vars['dataType_PedidoDetalle']['lis_precioTotal']), $this);?>

				<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWFila($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>				
				<?php echo smarty_function_CWPaginador(array('enlacesVisibles' => '3','iconCSS' => 'true'), $this);?>

			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWTabla($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>	 		
		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWContenedor($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php $this->_tag_stack[] = array('CWBarraInfPanel', array()); $_block_repeat=true;smarty_block_CWBarraInfPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php echo smarty_function_CWBoton(array('imagen' => '41','iconCSS' => "glyphicon glyphicon-ok",'texto' => 'Guardar','class' => 'button','accion' => 'guardar'), $this);?>

			<?php echo smarty_function_CWBoton(array('imagen' => '42','iconCSS' => "glyphicon glyphicon-remove",'texto' => 'Cancelar','class' => 'button','accion' => 'cancelar'), $this);?>

		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWBarraInfPanel($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>						
	<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWPanel($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>	
		
	<!-- ****************** PESTA�AS DETALLE ************************-->	
	<?php $this->_tag_stack[] = array('CWContenedorPestanyas', array('id' => 'Detalle')); $_block_repeat=true;smarty_block_CWContenedorPestanyas($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		<?php echo smarty_function_CWPestanya(array('tipo' => 'lis','panelAsociado' => 'lisDetalle','estado' => 'on'), $this);?>

	<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWContenedorPestanyas($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
	<?php endif;  $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWMarcoPanel($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack);  $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWVentana($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>