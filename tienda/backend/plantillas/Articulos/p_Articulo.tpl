{CWVentana tipoAviso=$smty_tipoAviso  codAviso=$smty_codError  descBreve = $smty_descBreve  textoAviso=$smty_textoAviso onLoad=$smty_jsOnLoad}
{CWBarra usuario=$smty_usuario codigo=$smty_codigo customTitle=$smty_customTitle modal=$smty_modal iconOut="glyphicon glyphicon-log-out" iconHome="glyphicon glyphicon-home" iconInfo="glyphicon glyphicon-info-sign"}
	{CWMenuLayer name="$smty_nombre" cadenaMenu="$smty_cadenaMenu"}	
{/CWBarra}
{CWMarcoPanel conPestanyas="true"}

<!--*********** PANEL fil ******************-->
	{CWPanel id="fil" action="buscar" method="post" estado="$estado_fil"  claseManejadora="Articulo"}
		{CWBarraSupPanel titulo="Mantenimiento de Articulo"}
			{CWBotonTooltip imagen="01" iconCSS="glyphicon glyphicon-plus" iconCSS="glyphicon glyphicon-plus" titulo="Insertar Articulo" funcion="insertar" actuaSobre="ficha"  action="Articulo__nuevo"}
			{CWBotonTooltip imagen="04" iconCSS="glyphicon glyphicon-refresh" titulo="Limpiar campos" funcion="limpiar" actuaSobre="ficha"}
		{/CWBarraSupPanel}
		{CWContenedor}
			{CWFicha}
				<table class="text" cellspacing="4" cellpadding="4" border="0">
					<tr>
						<td>{CWCampoTexto textoAsociado="Idarticulo" nombre="fil_idarticulo" size="4" editable="true" visible="true" value=$defaultData_Articulo.fil_idarticulo dataType=$dataType_Articulo.fil_idarticulo}</td>
					</tr>
					<tr>
						<td>{CWCampoTexto textoAsociado="Nombre" nombre="fil_nombre" size="40" editable="true" visible="true" value=$defaultData_Articulo.fil_nombre dataType=$dataType_Articulo.fil_nombre}</td>
					</tr>
					<tr>
						<td>{CWCampoTexto textoAsociado="Descripcion" nombre="fil_descripcion" size="0" editable="true" visible="true" value=$defaultData_Articulo.fil_descripcion dataType=$dataType_Articulo.fil_descripcion}</td>
					</tr>
					<tr>
						<td>{CWCampoTexto textoAsociado="Precio" nombre="fil_precio" size="7" editable="true" visible="true" value=$defaultData_Articulo.fil_precio dataType=$dataType_Articulo.fil_precio}</td>
					</tr>
					<tr>
						<td>{CWCampoTexto textoAsociado="Imagen" nombre="fil_imagen" size="40" editable="true" visible="true" value=$defaultData_Articulo.fil_imagen dataType=$dataType_Articulo.fil_imagen}</td>
					</tr>
					<tr>
						<td>{CWCampoTexto textoAsociado="Stock" nombre="fil_stock" size="4" editable="true" visible="true" value=$defaultData_Articulo.fil_stock dataType=$dataType_Articulo.fil_stock}</td>
					</tr>
					<tr>
						<td>{CWCampoTexto textoAsociado="Categoria" nombre="fil_categoria" size="4" editable="true" visible="true" value=$defaultData_Articulo.fil_categoria dataType=$dataType_Articulo.fil_categoria}</td>
					</tr>
				</table>
				<br/>
			{/CWFicha}
		{/CWContenedor}
		{CWBarraInfPanel}
			{CWBoton imagen="50" iconCSS="glyphicon glyphicon-search" texto="Buscar" class="button" accion="buscar" mostrarEspera="true"}
		{/CWBarraInfPanel}						
	{/CWPanel}

<!-- ****************** PANEL lis ***********************-->
	{CWPanel id="lis" action="borrar" method="post" estado="$estado_lis" claseManejadora="Articulo"}
		{CWBarraSupPanel titulo="Listado de Articulo"}
			{CWBotonTooltip imagen="01" iconCSS="glyphicon glyphicon-plus" titulo="Insertar Articulo" funcion="insertar" actuaSobre="ficha"  action="Articulo__nuevo"}
			{CWBotonTooltip imagen="02" iconCSS="glyphicon glyphicon-edit" titulo="Modificar Articulo" funcion="modificar" actuaSobre="ficha" action="Articulo__editar"}
			{CWBotonTooltip imagen="03" iconCSS="glyphicon glyphicon-minus" titulo="Eliminar Articulo" funcion="eliminar" actuaSobre="tabla"}
		{/CWBarraSupPanel}
		{CWContenedor}
			{CWTabla conCheck="true" conCheckTodos="true" id="Tabla1" numFilasPantalla="10" datos=$smty_datosTabla}
				{CWFila tipoListado="false"}
					{CWCampoTexto textoAsociado="Idarticulo" nombre="lis_idarticulo" size="4" editable="true" visible="true" value=$defaultData_Articulo.lis_idarticulo dataType=$dataType_Articulo.lis_idarticulo}
					{CWCampoTexto textoAsociado="Nombre" nombre="lis_nombre" size="40" editable="true" visible="true" value=$defaultData_Articulo.lis_nombre dataType=$dataType_Articulo.lis_nombre}
					{CWCampoTexto textoAsociado="Descripcion" nombre="lis_descripcion" size="0" editable="true" visible="true" value=$defaultData_Articulo.lis_descripcion dataType=$dataType_Articulo.lis_descripcion}
					{CWCampoTexto textoAsociado="Precio" nombre="lis_precio" size="7" editable="true" visible="true" value=$defaultData_Articulo.lis_precio dataType=$dataType_Articulo.lis_precio}
					{CWCampoTexto textoAsociado="Imagen" nombre="lis_imagen" size="40" editable="true" visible="true" value=$defaultData_Articulo.lis_imagen dataType=$dataType_Articulo.lis_imagen}
					{CWCampoTexto textoAsociado="Stock" nombre="lis_stock" size="4" editable="true" visible="true" value=$defaultData_Articulo.lis_stock dataType=$dataType_Articulo.lis_stock}
					{CWCampoTexto textoAsociado="Categoria" nombre="lis_categoria" size="4" editable="true" visible="true" value=$defaultData_Articulo.lis_categoria dataType=$dataType_Articulo.lis_categoria}
				{/CWFila}				
				{CWPaginador enlacesVisibles="3" iconCSS="true"}
			{/CWTabla}			
		{/CWContenedor}
		{CWBarraInfPanel}
			{CWBoton imagen="41" iconCSS="glyphicon glyphicon-ok" texto="Guardar" class="button" accion="guardar"}
			{CWBoton imagen="42" iconCSS="glyphicon glyphicon-remove" texto="Cancelar" class="button" accion="cancelar"}			
		{/CWBarraInfPanel}						
	{/CWPanel}	


<!-- ****************** PANEL edi ***********************-->
	{CWPanel id="edi" tipoComprobacion="envio" action="$smty_operacionFichaArticulo" method="post" estado="$estado_edi" claseManejadora="Articulo"  accion=$smty_operacionFichaArticulo}
		{CWBarraSupPanel titulo="Mantenimiento de Articulo"}
			{CWBotonTooltip imagen="04" iconCSS="glyphicon glyphicon-refresh" titulo="Limpiar campos" funcion="limpiar" actuaSobre="ficha"}				
		{/CWBarraSupPanel}
		{CWContenedor}
			{CWFichaEdicion id="FichaEdicion" datos=$smty_datosFicha} 
				{CWFicha}

					<table class="text" cellspacing="4" cellpadding="4" border="0">
						<tr>
							<td>{CWCampoTexto textoAsociado="Idarticulo" nombre="edi_idarticulo" size="4" editable="true" visible="true" value=$defaultData_Articulo.edi_idarticulo dataType=$dataType_Articulo.edi_idarticulo}</td>
						</tr>
						<tr>
							<td>{CWCampoTexto textoAsociado="Nombre" nombre="edi_nombre" size="40" editable="true" visible="true" value=$defaultData_Articulo.edi_nombre dataType=$dataType_Articulo.edi_nombre}</td>
						</tr>
						<tr>
							<td>{CWCampoTexto textoAsociado="Descripcion" nombre="edi_descripcion" size="0" editable="true" visible="true" value=$defaultData_Articulo.edi_descripcion dataType=$dataType_Articulo.edi_descripcion}</td>
						</tr>
						<tr>
							<td>{CWCampoTexto textoAsociado="Precio" nombre="edi_precio" size="7" editable="true" visible="true" value=$defaultData_Articulo.edi_precio dataType=$dataType_Articulo.edi_precio}</td>
						</tr>
						<tr>
							<td>{CWCampoTexto textoAsociado="Imagen" nombre="edi_imagen" size="40" editable="true" visible="true" value=$defaultData_Articulo.edi_imagen dataType=$dataType_Articulo.edi_imagen}</td>
						</tr>
						<tr>
							<td>{CWCampoTexto textoAsociado="Stock" nombre="edi_stock" size="4" editable="true" visible="true" value=$defaultData_Articulo.edi_stock dataType=$dataType_Articulo.edi_stock}</td>
						</tr>
						<tr>
							<td>{CWCampoTexto textoAsociado="Categoria" nombre="edi_categoria" size="4" editable="true" visible="true" value=$defaultData_Articulo.edi_categoria dataType=$dataType_Articulo.edi_categoria}</td>
						</tr>
					</table>
					<br/>
				{/CWFicha}
				{CWPaginador enlacesVisibles="3" iconCSS="true"}
			{/CWFichaEdicion}
		{/CWContenedor}
		{CWBarraInfPanel}
			{CWBoton imagen="41" iconCSS="glyphicon glyphicon-ok" texto="Guardar" class="button" accion="guardar"}
			{CWBoton imagen="42" iconCSS="glyphicon glyphicon-remove" texto="Cancelar" class="button" accion="cancelar" action="cancelarEdicion"}
		{/CWBarraInfPanel}						
	{/CWPanel}	
		
<!-- ****************** PESTANYAS ************************-->
	{CWContenedorPestanyas}
		{CWPestanya tipo="fil" estado=$estado_fil}		
		{CWPestanya tipo="lis" estado=$estado_lis}
		{CWPestanya tipo="edi" estado=$estado_edi}
	{/CWContenedorPestanyas}
{/CWMarcoPanel}
{/CWVentana}