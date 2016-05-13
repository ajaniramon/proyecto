<?php /* Smarty version 2.6.14, created on 2016-05-10 17:14:56
         compiled from Clientes/p_Cliente.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'CWVentana', 'Clientes/p_Cliente.tpl', 1, false),array('block', 'CWBarra', 'Clientes/p_Cliente.tpl', 2, false),array('block', 'CWMarcoPanel', 'Clientes/p_Cliente.tpl', 5, false),array('block', 'CWPanel', 'Clientes/p_Cliente.tpl', 8, false),array('block', 'CWBarraSupPanel', 'Clientes/p_Cliente.tpl', 9, false),array('block', 'CWContenedor', 'Clientes/p_Cliente.tpl', 13, false),array('block', 'CWFicha', 'Clientes/p_Cliente.tpl', 14, false),array('block', 'CWBarraInfPanel', 'Clientes/p_Cliente.tpl', 36, false),array('block', 'CWTabla', 'Clientes/p_Cliente.tpl', 49, false),array('block', 'CWFila', 'Clientes/p_Cliente.tpl', 50, false),array('block', 'CWContenedorPestanyas', 'Clientes/p_Cliente.tpl', 68, false),array('function', 'CWMenuLayer', 'Clientes/p_Cliente.tpl', 3, false),array('function', 'CWBotonTooltip', 'Clientes/p_Cliente.tpl', 10, false),array('function', 'CWCampoTexto', 'Clientes/p_Cliente.tpl', 18, false),array('function', 'CWBoton', 'Clientes/p_Cliente.tpl', 37, false),array('function', 'CWPaginador', 'Clientes/p_Cliente.tpl', 58, false),array('function', 'CWPestanya', 'Clientes/p_Cliente.tpl', 69, false),)), $this); ?>
<?php $this->_tag_stack[] = array('CWVentana', array('tipoAviso' => $this->_tpl_vars['smty_tipoAviso'],'codAviso' => $this->_tpl_vars['smty_codError'],'descBreve' => $this->_tpl_vars['smty_descBreve'],'textoAviso' => $this->_tpl_vars['smty_textoAviso'],'onLoad' => $this->_tpl_vars['smty_jsOnLoad'])); $_block_repeat=true;smarty_block_CWVentana($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start();  $this->_tag_stack[] = array('CWBarra', array('usuario' => $this->_tpl_vars['smty_usuario'],'codigo' => $this->_tpl_vars['smty_codigo'],'customTitle' => $this->_tpl_vars['smty_customTitle'],'modal' => $this->_tpl_vars['smty_modal'],'iconOut' => "glyphicon glyphicon-log-out",'iconHome' => "glyphicon glyphicon-home",'iconInfo' => "glyphicon glyphicon-info-sign")); $_block_repeat=true;smarty_block_CWBarra($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	<?php echo smarty_function_CWMenuLayer(array('name' => ($this->_tpl_vars['smty_nombre']),'cadenaMenu' => ($this->_tpl_vars['smty_cadenaMenu'])), $this);?>
	
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWBarra($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack);  $this->_tag_stack[] = array('CWMarcoPanel', array('conPestanyas' => 'true')); $_block_repeat=true;smarty_block_CWMarcoPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

<!--*********** PANEL fil ******************-->
	<?php $this->_tag_stack[] = array('CWPanel', array('id' => 'fil','action' => 'buscar','method' => 'post','estado' => ($this->_tpl_vars['estado_fil']),'claseManejadora' => 'Cliente')); $_block_repeat=true;smarty_block_CWPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		<?php $this->_tag_stack[] = array('CWBarraSupPanel', array('titulo' => 'Mantenimiento de Cliente')); $_block_repeat=true;smarty_block_CWBarraSupPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php echo smarty_function_CWBotonTooltip(array('imagen' => '01','iconCSS' => "glyphicon glyphicon-plus",'titulo' => 'Insertar Cliente','funcion' => 'insertar','actuaSobre' => 'ficha','action' => 'Cliente__nuevo'), $this);?>

			<?php echo smarty_function_CWBotonTooltip(array('imagen' => '04','iconCSS' => "glyphicon glyphicon-refresh",'titulo' => 'Limpiar campos','funcion' => 'limpiar','actuaSobre' => 'ficha'), $this);?>

		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWBarraSupPanel($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php $this->_tag_stack[] = array('CWContenedor', array()); $_block_repeat=true;smarty_block_CWContenedor($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php $this->_tag_stack[] = array('CWFicha', array()); $_block_repeat=true;smarty_block_CWFicha($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
				<table class="text" cellspacing="4" cellpadding="4" border="0">
					
					<tr>
						<td><?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Nombre','nombre' => 'fil_nombre','size' => '15','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Cliente']['fil_nombre'],'dataType' => $this->_tpl_vars['dataType_Cliente']['fil_nombre']), $this);?>
</td>
					</tr>
					<tr>
						<td><?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Apellido','nombre' => 'fil_apellido','size' => '25','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Cliente']['fil_apellido'],'dataType' => $this->_tpl_vars['dataType_Cliente']['fil_apellido']), $this);?>
</td>
					</tr>
					<tr>
						<td><?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'DNI','nombre' => 'fil_dni','size' => '9','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Cliente']['fil_dni'],'dataType' => $this->_tpl_vars['dataType_Cliente']['fil_dni']), $this);?>
</td>
					</tr>
					<tr>
						<td><?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Telefono','nombre' => 'fil_telefono','size' => '15','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Cliente']['fil_telefono'],'dataType' => $this->_tpl_vars['dataType_Cliente']['fil_telefono']), $this);?>
</td>
					</tr>
					<tr>
						<td><?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Correo','nombre' => 'fil_correo','size' => '30','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Cliente']['fil_correo'],'dataType' => $this->_tpl_vars['dataType_Cliente']['fil_correo']), $this);?>
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

<!-- ****************** PANEL lis ***********************-->
	<?php $this->_tag_stack[] = array('CWPanel', array('id' => 'lis','tipoComprobacion' => 'envio','action' => 'operarBD','method' => 'post','estado' => ($this->_tpl_vars['estado_lis']),'claseManejadora' => 'Cliente')); $_block_repeat=true;smarty_block_CWPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		<?php $this->_tag_stack[] = array('CWBarraSupPanel', array('titulo' => 'Listado de Cliente')); $_block_repeat=true;smarty_block_CWBarraSupPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php echo smarty_function_CWBotonTooltip(array('imagen' => '01','iconCSS' => "glyphicon glyphicon-plus",'titulo' => 'Insertar Cliente','funcion' => 'insertar','actuaSobre' => 'tabla','action' => 'Cliente__nuevo'), $this);?>

			<?php echo smarty_function_CWBotonTooltip(array('imagen' => '02','iconCSS' => "glyphicon glyphicon-edit",'titulo' => 'Modificar Cliente','funcion' => 'modificar','actuaSobre' => 'tabla'), $this);?>

			<?php echo smarty_function_CWBotonTooltip(array('imagen' => '03','iconCSS' => "glyphicon glyphicon-minus",'titulo' => 'Eliminar Cliente','funcion' => 'eliminar','actuaSobre' => 'tabla'), $this);?>

		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWBarraSupPanel($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php $this->_tag_stack[] = array('CWContenedor', array()); $_block_repeat=true;smarty_block_CWContenedor($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php $this->_tag_stack[] = array('CWTabla', array('conCheck' => 'true','conCheckTodos' => 'true','id' => 'Tabla1','numFilasPantalla' => '10','datos' => $this->_tpl_vars['smty_datosTabla'])); $_block_repeat=true;smarty_block_CWTabla($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
				<?php $this->_tag_stack[] = array('CWFila', array('tipoListado' => 'false')); $_block_repeat=true;smarty_block_CWFila($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
					<?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Nombre','nombre' => 'lis_nombre','size' => '40','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Cliente']['lis_nombre'],'dataType' => $this->_tpl_vars['dataType_Cliente']['lis_nombre']), $this);?>

					<?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Apellido','nombre' => 'lis_apellido','size' => '40','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Cliente']['lis_apellido'],'dataType' => $this->_tpl_vars['dataType_Cliente']['lis_apellido']), $this);?>

					<?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Dni','nombre' => 'lis_dni','size' => '9','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Cliente']['lis_dni'],'dataType' => $this->_tpl_vars['dataType_Cliente']['lis_dni']), $this);?>

					<?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Direccion','nombre' => 'lis_direccion','size' => '40','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Cliente']['lis_direccion'],'dataType' => $this->_tpl_vars['dataType_Cliente']['lis_direccion']), $this);?>

					<?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Telefono','nombre' => 'lis_telefono','size' => '10','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Cliente']['lis_telefono'],'dataType' => $this->_tpl_vars['dataType_Cliente']['lis_telefono']), $this);?>

					<?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Correo','nombre' => 'lis_correo','size' => '40','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Cliente']['lis_correo'],'dataType' => $this->_tpl_vars['dataType_Cliente']['lis_correo']), $this);?>

				<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWFila($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>				
				<?php echo smarty_function_CWPaginador(array('enlacesVisibles' => '3','iconCSS' => 'true'), $this);?>

			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWTabla($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>			
		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWContenedor($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php $this->_tag_stack[] = array('CWBarraInfPanel', array()); $_block_repeat=true;smarty_block_CWBarraInfPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php echo smarty_function_CWBoton(array('imagen' => '41','iconCSS' => "glyphicon glyphicon-ok",'texto' => 'Guardar','class' => 'button','accion' => 'guardar'), $this);?>

			<?php echo smarty_function_CWBoton(array('imagen' => '42','iconCSS' => "glyphicon glyphicon-remove",'texto' => 'Cancelar','class' => 'button','accion' => 'cancelar'), $this);?>
			
		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWBarraInfPanel($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>						
	<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWPanel($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>	

<!-- ****************** PESTANYAS ************************-->
	<?php $this->_tag_stack[] = array('CWContenedorPestanyas', array()); $_block_repeat=true;smarty_block_CWContenedorPestanyas($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		<?php echo smarty_function_CWPestanya(array('tipo' => 'fil','estado' => $this->_tpl_vars['estado_fil']), $this);?>
		
		<?php echo smarty_function_CWPestanya(array('tipo' => 'lis','estado' => $this->_tpl_vars['estado_lis']), $this);?>

	<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWContenedorPestanyas($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack);  $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWMarcoPanel($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack);  $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWVentana($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>