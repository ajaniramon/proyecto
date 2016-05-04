<?php /* Smarty version 2.6.14, created on 2016-04-06 16:58:06
         compiled from Articulos/p_Articulo.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'CWVentana', 'Articulos/p_Articulo.tpl', 1, false),array('block', 'CWBarra', 'Articulos/p_Articulo.tpl', 2, false),array('block', 'CWMarcoPanel', 'Articulos/p_Articulo.tpl', 5, false),array('block', 'CWPanel', 'Articulos/p_Articulo.tpl', 8, false),array('block', 'CWBarraSupPanel', 'Articulos/p_Articulo.tpl', 9, false),array('block', 'CWContenedor', 'Articulos/p_Articulo.tpl', 13, false),array('block', 'CWFicha', 'Articulos/p_Articulo.tpl', 14, false),array('block', 'CWBarraInfPanel', 'Articulos/p_Articulo.tpl', 41, false),array('block', 'CWTabla', 'Articulos/p_Articulo.tpl', 54, false),array('block', 'CWFila', 'Articulos/p_Articulo.tpl', 55, false),array('block', 'CWFichaEdicion', 'Articulos/p_Articulo.tpl', 80, false),array('block', 'CWContenedorPestanyas', 'Articulos/p_Articulo.tpl', 118, false),array('function', 'CWMenuLayer', 'Articulos/p_Articulo.tpl', 3, false),array('function', 'CWBotonTooltip', 'Articulos/p_Articulo.tpl', 10, false),array('function', 'CWCampoTexto', 'Articulos/p_Articulo.tpl', 17, false),array('function', 'CWBoton', 'Articulos/p_Articulo.tpl', 42, false),array('function', 'CWPaginador', 'Articulos/p_Articulo.tpl', 64, false),array('function', 'CWPestanya', 'Articulos/p_Articulo.tpl', 119, false),)), $this); ?>
<?php $this->_tag_stack[] = array('CWVentana', array('tipoAviso' => $this->_tpl_vars['smty_tipoAviso'],'codAviso' => $this->_tpl_vars['smty_codError'],'descBreve' => $this->_tpl_vars['smty_descBreve'],'textoAviso' => $this->_tpl_vars['smty_textoAviso'],'onLoad' => $this->_tpl_vars['smty_jsOnLoad'])); $_block_repeat=true;smarty_block_CWVentana($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start();  $this->_tag_stack[] = array('CWBarra', array('usuario' => $this->_tpl_vars['smty_usuario'],'codigo' => $this->_tpl_vars['smty_codigo'],'customTitle' => $this->_tpl_vars['smty_customTitle'],'modal' => $this->_tpl_vars['smty_modal'],'iconOut' => "glyphicon glyphicon-log-out",'iconHome' => "glyphicon glyphicon-home",'iconInfo' => "glyphicon glyphicon-info-sign")); $_block_repeat=true;smarty_block_CWBarra($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	<?php echo smarty_function_CWMenuLayer(array('name' => ($this->_tpl_vars['smty_nombre']),'cadenaMenu' => ($this->_tpl_vars['smty_cadenaMenu'])), $this);?>
	
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWBarra($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack);  $this->_tag_stack[] = array('CWMarcoPanel', array('conPestanyas' => 'true')); $_block_repeat=true;smarty_block_CWMarcoPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

<!--*********** PANEL fil ******************-->
	<?php $this->_tag_stack[] = array('CWPanel', array('id' => 'fil','action' => 'buscar','method' => 'post','estado' => ($this->_tpl_vars['estado_fil']),'claseManejadora' => 'Articulo')); $_block_repeat=true;smarty_block_CWPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		<?php $this->_tag_stack[] = array('CWBarraSupPanel', array('titulo' => 'Mantenimiento de Articulo')); $_block_repeat=true;smarty_block_CWBarraSupPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php echo smarty_function_CWBotonTooltip(array('imagen' => '01','iconCSS' => "glyphicon glyphicon-plus",'titulo' => 'Insertar Articulo','funcion' => 'insertar','actuaSobre' => 'ficha','action' => 'Articulo__nuevo'), $this);?>

			<?php echo smarty_function_CWBotonTooltip(array('imagen' => '04','iconCSS' => "glyphicon glyphicon-refresh",'titulo' => 'Limpiar campos','funcion' => 'limpiar','actuaSobre' => 'ficha'), $this);?>

		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWBarraSupPanel($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php $this->_tag_stack[] = array('CWContenedor', array()); $_block_repeat=true;smarty_block_CWContenedor($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php $this->_tag_stack[] = array('CWFicha', array()); $_block_repeat=true;smarty_block_CWFicha($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
				<table class="text" cellspacing="4" cellpadding="4" border="0">
					<tr>
						<td><?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Idarticulo','nombre' => 'fil_idarticulo','size' => '4','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Articulo']['fil_idarticulo'],'dataType' => $this->_tpl_vars['dataType_Articulo']['fil_idarticulo']), $this);?>
</td>
					</tr>
					<tr>
						<td><?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Nombre','nombre' => 'fil_nombre','size' => '40','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Articulo']['fil_nombre'],'dataType' => $this->_tpl_vars['dataType_Articulo']['fil_nombre']), $this);?>
</td>
					</tr>
					<tr>
						<td><?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Descripcion','nombre' => 'fil_descripcion','size' => '0','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Articulo']['fil_descripcion'],'dataType' => $this->_tpl_vars['dataType_Articulo']['fil_descripcion']), $this);?>
</td>
					</tr>
					<tr>
						<td><?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Precio','nombre' => 'fil_precio','size' => '7','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Articulo']['fil_precio'],'dataType' => $this->_tpl_vars['dataType_Articulo']['fil_precio']), $this);?>
</td>
					</tr>
					<tr>
						<td><?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Imagen','nombre' => 'fil_imagen','size' => '40','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Articulo']['fil_imagen'],'dataType' => $this->_tpl_vars['dataType_Articulo']['fil_imagen']), $this);?>
</td>
					</tr>
					<tr>
						<td><?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Stock','nombre' => 'fil_stock','size' => '4','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Articulo']['fil_stock'],'dataType' => $this->_tpl_vars['dataType_Articulo']['fil_stock']), $this);?>
</td>
					</tr>
					<tr>
						<td><?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Categoria','nombre' => 'fil_categoria','size' => '4','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Articulo']['fil_categoria'],'dataType' => $this->_tpl_vars['dataType_Articulo']['fil_categoria']), $this);?>
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
	<?php $this->_tag_stack[] = array('CWPanel', array('id' => 'lis','action' => 'borrar','method' => 'post','estado' => ($this->_tpl_vars['estado_lis']),'claseManejadora' => 'Articulo')); $_block_repeat=true;smarty_block_CWPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		<?php $this->_tag_stack[] = array('CWBarraSupPanel', array('titulo' => 'Listado de Articulo')); $_block_repeat=true;smarty_block_CWBarraSupPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php echo smarty_function_CWBotonTooltip(array('imagen' => '01','iconCSS' => "glyphicon glyphicon-plus",'titulo' => 'Insertar Articulo','funcion' => 'insertar','actuaSobre' => 'ficha','action' => 'Articulo__nuevo'), $this);?>

			<?php echo smarty_function_CWBotonTooltip(array('imagen' => '02','iconCSS' => "glyphicon glyphicon-edit",'titulo' => 'Modificar Articulo','funcion' => 'modificar','actuaSobre' => 'ficha','action' => 'Articulo__editar'), $this);?>

			<?php echo smarty_function_CWBotonTooltip(array('imagen' => '03','iconCSS' => "glyphicon glyphicon-minus",'titulo' => 'Eliminar Articulo','funcion' => 'eliminar','actuaSobre' => 'tabla'), $this);?>

		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWBarraSupPanel($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php $this->_tag_stack[] = array('CWContenedor', array()); $_block_repeat=true;smarty_block_CWContenedor($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php $this->_tag_stack[] = array('CWTabla', array('conCheck' => 'true','conCheckTodos' => 'true','id' => 'Tabla1','numFilasPantalla' => '10','datos' => $this->_tpl_vars['smty_datosTabla'])); $_block_repeat=true;smarty_block_CWTabla($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
				<?php $this->_tag_stack[] = array('CWFila', array('tipoListado' => 'false')); $_block_repeat=true;smarty_block_CWFila($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
					<?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Idarticulo','nombre' => 'lis_idarticulo','size' => '4','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Articulo']['lis_idarticulo'],'dataType' => $this->_tpl_vars['dataType_Articulo']['lis_idarticulo']), $this);?>

					<?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Nombre','nombre' => 'lis_nombre','size' => '40','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Articulo']['lis_nombre'],'dataType' => $this->_tpl_vars['dataType_Articulo']['lis_nombre']), $this);?>

					<?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Descripcion','nombre' => 'lis_descripcion','size' => '0','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Articulo']['lis_descripcion'],'dataType' => $this->_tpl_vars['dataType_Articulo']['lis_descripcion']), $this);?>

					<?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Precio','nombre' => 'lis_precio','size' => '7','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Articulo']['lis_precio'],'dataType' => $this->_tpl_vars['dataType_Articulo']['lis_precio']), $this);?>

					<?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Imagen','nombre' => 'lis_imagen','size' => '40','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Articulo']['lis_imagen'],'dataType' => $this->_tpl_vars['dataType_Articulo']['lis_imagen']), $this);?>

					<?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Stock','nombre' => 'lis_stock','size' => '4','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Articulo']['lis_stock'],'dataType' => $this->_tpl_vars['dataType_Articulo']['lis_stock']), $this);?>

					<?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Categoria','nombre' => 'lis_categoria','size' => '4','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Articulo']['lis_categoria'],'dataType' => $this->_tpl_vars['dataType_Articulo']['lis_categoria']), $this);?>

				<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWFila($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>				
				<?php echo smarty_function_CWPaginador(array('enlacesVisibles' => '3','iconCSS' => 'true'), $this);?>

			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWTabla($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>			
		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWContenedor($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php $this->_tag_stack[] = array('CWBarraInfPanel', array()); $_block_repeat=true;smarty_block_CWBarraInfPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php echo smarty_function_CWBoton(array('imagen' => '41','iconCSS' => "glyphicon glyphicon-ok",'texto' => 'Guardar','class' => 'button','accion' => 'guardar'), $this);?>

			<?php echo smarty_function_CWBoton(array('imagen' => '42','iconCSS' => "glyphicon glyphicon-remove",'texto' => 'Cancelar','class' => 'button','accion' => 'cancelar'), $this);?>
			
		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWBarraInfPanel($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>						
	<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWPanel($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>	


<!-- ****************** PANEL edi ***********************-->
	<?php $this->_tag_stack[] = array('CWPanel', array('id' => 'edi','tipoComprobacion' => 'envio','action' => ($this->_tpl_vars['smty_operacionFichaArticulo']),'method' => 'post','estado' => ($this->_tpl_vars['estado_edi']),'claseManejadora' => 'Articulo','accion' => $this->_tpl_vars['smty_operacionFichaArticulo'])); $_block_repeat=true;smarty_block_CWPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		<?php $this->_tag_stack[] = array('CWBarraSupPanel', array('titulo' => 'Mantenimiento de Articulo')); $_block_repeat=true;smarty_block_CWBarraSupPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php echo smarty_function_CWBotonTooltip(array('imagen' => '04','iconCSS' => "glyphicon glyphicon-refresh",'titulo' => 'Limpiar campos','funcion' => 'limpiar','actuaSobre' => 'ficha'), $this);?>
				
		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWBarraSupPanel($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php $this->_tag_stack[] = array('CWContenedor', array()); $_block_repeat=true;smarty_block_CWContenedor($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php $this->_tag_stack[] = array('CWFichaEdicion', array('id' => 'FichaEdicion','datos' => $this->_tpl_vars['smty_datosFicha'])); $_block_repeat=true;smarty_block_CWFichaEdicion($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?> 
				<?php $this->_tag_stack[] = array('CWFicha', array()); $_block_repeat=true;smarty_block_CWFicha($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

					<table class="text" cellspacing="4" cellpadding="4" border="0">
						<tr>
							<td><?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Idarticulo','nombre' => 'edi_idarticulo','size' => '4','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Articulo']['edi_idarticulo'],'dataType' => $this->_tpl_vars['dataType_Articulo']['edi_idarticulo']), $this);?>
</td>
						</tr>
						<tr>
							<td><?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Nombre','nombre' => 'edi_nombre','size' => '40','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Articulo']['edi_nombre'],'dataType' => $this->_tpl_vars['dataType_Articulo']['edi_nombre']), $this);?>
</td>
						</tr>
						<tr>
							<td><?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Descripcion','nombre' => 'edi_descripcion','size' => '0','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Articulo']['edi_descripcion'],'dataType' => $this->_tpl_vars['dataType_Articulo']['edi_descripcion']), $this);?>
</td>
						</tr>
						<tr>
							<td><?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Precio','nombre' => 'edi_precio','size' => '7','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Articulo']['edi_precio'],'dataType' => $this->_tpl_vars['dataType_Articulo']['edi_precio']), $this);?>
</td>
						</tr>
						<tr>
							<td><?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Imagen','nombre' => 'edi_imagen','size' => '40','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Articulo']['edi_imagen'],'dataType' => $this->_tpl_vars['dataType_Articulo']['edi_imagen']), $this);?>
</td>
						</tr>
						<tr>
							<td><?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Stock','nombre' => 'edi_stock','size' => '4','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Articulo']['edi_stock'],'dataType' => $this->_tpl_vars['dataType_Articulo']['edi_stock']), $this);?>
</td>
						</tr>
						<tr>
							<td><?php echo smarty_function_CWCampoTexto(array('textoAsociado' => 'Categoria','nombre' => 'edi_categoria','size' => '4','editable' => 'true','visible' => 'true','value' => $this->_tpl_vars['defaultData_Articulo']['edi_categoria'],'dataType' => $this->_tpl_vars['dataType_Articulo']['edi_categoria']), $this);?>
</td>
						</tr>
					</table>
					<br/>
				<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWFicha($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
				<?php echo smarty_function_CWPaginador(array('enlacesVisibles' => '3','iconCSS' => 'true'), $this);?>

			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWFichaEdicion($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWContenedor($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php $this->_tag_stack[] = array('CWBarraInfPanel', array()); $_block_repeat=true;smarty_block_CWBarraInfPanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php echo smarty_function_CWBoton(array('imagen' => '41','iconCSS' => "glyphicon glyphicon-ok",'texto' => 'Guardar','class' => 'button','accion' => 'guardar'), $this);?>

			<?php echo smarty_function_CWBoton(array('imagen' => '42','iconCSS' => "glyphicon glyphicon-remove",'texto' => 'Cancelar','class' => 'button','accion' => 'cancelar','action' => 'cancelarEdicion'), $this);?>

		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWBarraInfPanel($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>						
	<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWPanel($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>	
		
<!-- ****************** PESTANYAS ************************-->
	<?php $this->_tag_stack[] = array('CWContenedorPestanyas', array()); $_block_repeat=true;smarty_block_CWContenedorPestanyas($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		<?php echo smarty_function_CWPestanya(array('tipo' => 'fil','estado' => $this->_tpl_vars['estado_fil']), $this);?>
		
		<?php echo smarty_function_CWPestanya(array('tipo' => 'lis','estado' => $this->_tpl_vars['estado_lis']), $this);?>

		<?php echo smarty_function_CWPestanya(array('tipo' => 'edi','estado' => $this->_tpl_vars['estado_edi']), $this);?>

	<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWContenedorPestanyas($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack);  $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWMarcoPanel($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack);  $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_CWVentana($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>