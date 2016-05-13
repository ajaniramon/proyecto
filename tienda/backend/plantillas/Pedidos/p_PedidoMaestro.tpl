{CWVentana tipoAviso=$smty_tipoAviso  codAviso=$smty_codError  descBreve=$smty_descBreve  textoAviso=$smty_textoAviso onLoad=$smty_jsOnLoad}
{CWBarra usuario=$smty_usuario codigo=$smty_codigo customTitle=$smty_customTitle modal=$smty_modal iconOut="glyphicon glyphicon-log-out" iconHome="glyphicon glyphicon-home" iconInfo="glyphicon glyphicon-info-sign"}	
	{CWMenuLayer name="$smty_nombre" cadenaMenu="$smty_cadenaMenu"}	
{/CWBarra}
{CWMarcoPanel conPestanyas="true"}

<!-- ********************************************** MAESTRO **********************************************-->
	<!--*********** PANEL fil ******************-->
	{CWPanel id="fil" action="buscar" method="post" estado=$estado_fil claseManejadora="PedidoMaestro"}
		{CWBarraSupPanel titulo="Filtrar pedido"}
			{CWBotonTooltip imagen="01" iconCSS="glyphicon glyphicon-plus" titulo="Insertar registros" funcion="insertar" actuaSobre="ficha" action="PedidoMaestro__nuevo"}
			{CWBotonTooltip imagen="04" iconCSS="glyphicon glyphicon-refresh" titulo="Restaurar valores" funcion="restaurar" actuaSobre="ficha"}
		{/CWBarraSupPanel}
		{CWContenedor}
			{CWFicha}
				<table class="text" cellspacing="4" cellpadding="4" border="0">
					<tr>
					 	<td>{CWCampoTexto textoAsociado="Fecha" nombre="fil_fecha" size="0" editable="true" visible="true" value=$defaultData_PedidoMaestro.fil_fecha  dataType=$dataType_PedidoMaestro.fil_fecha}</td>
					</tr>
					<tr>
					 	<td>{CWCampoTexto textoAsociado="Importe total" nombre="fil_total" size="7" editable="true" visible="true" value=$defaultData_PedidoMaestro.fil_total  dataType=$dataType_PedidoMaestro.fil_total}</td>
					</tr>
					<tr>
					 	<td>{CWCampoTexto textoAsociado="DNI" nombre="fil_dni" size="9" editable="true" visible="true" value=$defaultData_PedidoMaestro.fil_dni  dataType=$dataType_PedidoMaestro.fil_dni}</td>
					</tr>
				</table>
				<br/>
			{/CWFicha}
		{/CWContenedor}
		{CWBarraInfPanel}
			{CWBoton imagen="50" iconCSS="glyphicon glyphicon-search" texto="Buscar" class="button" accion="buscar" mostrarEspera="true"}
		{/CWBarraInfPanel}						
	{/CWPanel}
	
	<!--*********** PANEL lis ******************-->	
	{CWPanel id="lis" tipoComprobacion="envio" esMaestro="true" itemSeleccionado=$smty_filaSeleccionada action="operarBD" method="post" estado=$estado_lis claseManejadora="PedidoMaestro"}
		{CWBarraSupPanel titulo="Pedido"}
			
		{/CWBarraSupPanel}
		{CWContenedor}
			{CWTabla conCheck="true" conCheckTodos="false" id="Tabla1" numFilasPantalla="6" datos=$smty_datosTablaM}
				{CWFila tipoListado="false"}
					{CWCampoTexto textoAsociado="Idpedido" nombre="lis_idpedido" size="4" editable="true" visible="true" value=$defaultData_PedidoMaestro.idpedido  dataType=$dataType_PedidoMaestro.idpedido}
					{CWCampoTexto textoAsociado="Fecha" nombre="lis_fecha" size="0" editable="true" visible="true" value=$defaultData_PedidoMaestro.lis_fecha  dataType=$dataType_PedidoMaestro.lis_fecha}
					{CWCampoTexto textoAsociado="Total" nombre="lis_total" size="7" editable="true" visible="true" value=$defaultData_PedidoMaestro.lis_total  dataType=$dataType_PedidoMaestro.lis_total}
					{CWCampoTexto textoAsociado="Dni" nombre="lis_dni" size="9" editable="true" visible="true" value=$defaultData_PedidoMaestro.lis_dni  dataType=$dataType_PedidoMaestro.lis_dni}
					{CWCampoTexto textoAsociado="Nombre" nombre="lis_nombre" size="20" editable="true" visible="true" value=$defaultData_PedidoMaestro.lis_nombre  dataType=$dataType_PedidoMaestro.lis_nombre}

				{/CWFila}				
				{CWPaginador enlacesVisibles="3" iconCSS="true"}
			{/CWTabla}
		{/CWContenedor}
		{CWBarraInfPanel}
			{CWBoton imagen="41" iconCSS="glyphicon glyphicon-ok" texto="Guardar" class="button" accion="guardar"}
			{CWBoton imagen="42" iconCSS="glyphicon glyphicon-remove" texto="Cancelar" class="button" accion="cancelar"}
		{/CWBarraInfPanel}						
	{/CWPanel}
	
	<!-- ****************** PESTA�AS MAESTRO ************************-->	
	{CWContenedorPestanyas id="Maestro"}				
		{CWPestanya tipo="fil" panelAsociado="fil" estado=$estado_fil ocultar="Detalle"}
		{CWPestanya tipo="lis" panelAsociado="lis" estado=$estado_lis mostrar="Detalle"}
	{/CWContenedorPestanyas}
</td></tr>
<tr><td>																																									
<!-- ************************************ DETALLE *****************************************-->
	<!--*********** PANEL lis ******************-->
	{if count($smty_datosTablaM) gt 0}
	{CWPanel id="lisDetalle" detalleDe="lis" tipoComprobacion="envio" action="operarBD" method="post" estado="on" claseManejadora="PedidoDetalle"}
		{CWBarraSupPanel titulo="Detalles del pedido"}

		{/CWBarraSupPanel}
		{CWContenedor}			
			{CWTabla conCheck="true" id="TablaDetalle" numFilasPantalla="6" datos=$smty_datosTablaD}
				{CWFila tipoListado="false"}
					{CWCampoTexto textoAsociado="nombre" nombre="lis_nombre" size="40" editable="true" value=$defaultData_PedidoDetalle.nombre dataType=$dataType_PedidoDetalle.nombre}
					{CWCampoTexto textoAsociado="Unidad" nombre="lis_unidad" size="4" editable="true" value=$defaultData_PedidoDetalle.lis_unidad dataType=$dataType_PedidoDetalle.lis_unidad}
					{CWCampoTexto textoAsociado="Precio" nombre="lis_precio" size="7" editable="true" value=$defaultData_PedidoDetalle.lis_precio dataType=$dataType_PedidoDetalle.lis_precio}
					{CWCampoTexto textoAsociado="PrecioTotal" nombre="lis_precioTotal" size="7" editable="true" value=$defaultData_PedidoDetalle.lis_precioTotal dataType=$dataType_PedidoDetalle.lis_precioTotal}
				{/CWFila}				
				{CWPaginador enlacesVisibles="3" iconCSS="true"}
			{/CWTabla}	 		
		{/CWContenedor}
		{CWBarraInfPanel}
			{CWBoton imagen="41" iconCSS="glyphicon glyphicon-ok" texto="Guardar" class="button" accion="guardar"}
			{CWBoton imagen="42" iconCSS="glyphicon glyphicon-remove" texto="Cancelar" class="button" accion="cancelar"}
		{/CWBarraInfPanel}						
	{/CWPanel}	
		
	<!-- ****************** PESTA�AS DETALLE ************************-->	
	{CWContenedorPestanyas id="Detalle"}
		{CWPestanya tipo="lis" panelAsociado="lisDetalle" estado="on"}
	{/CWContenedorPestanyas}
	{/if}
{/CWMarcoPanel}
{/CWVentana}