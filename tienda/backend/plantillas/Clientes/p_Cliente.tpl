{CWVentana tipoAviso=$smty_tipoAviso  codAviso=$smty_codError  descBreve = $smty_descBreve  textoAviso=$smty_textoAviso onLoad=$smty_jsOnLoad}
{CWBarra usuario=$smty_usuario codigo=$smty_codigo customTitle=$smty_customTitle modal=$smty_modal iconOut="glyphicon glyphicon-log-out" iconHome="glyphicon glyphicon-home" iconInfo="glyphicon glyphicon-info-sign"}
	{CWMenuLayer name="$smty_nombre" cadenaMenu="$smty_cadenaMenu"}	
{/CWBarra}
{CWMarcoPanel conPestanyas="true"}

<!--*********** PANEL fil ******************-->
	{CWPanel id="fil" action="buscar" method="post" estado="$estado_fil" claseManejadora="Cliente"}
		{CWBarraSupPanel titulo="Mantenimiento de Cliente"}
			{CWBotonTooltip imagen="01" iconCSS="glyphicon glyphicon-plus" iconCSS="glyphicon glyphicon-plus" titulo="Insertar Cliente" funcion="insertar" actuaSobre="ficha" action="Cliente__nuevo"}
			{CWBotonTooltip imagen="04" iconCSS="glyphicon glyphicon-refresh" titulo="Limpiar campos" funcion="limpiar" actuaSobre="ficha"}
		{/CWBarraSupPanel}
		{CWContenedor}
			{CWFicha}
				<table class="text" cellspacing="4" cellpadding="4" border="0">
					
					<tr>
						<td>{CWCampoTexto textoAsociado="Nombre" nombre="fil_nombre" size="15" editable="true" visible="true" value=$defaultData_Cliente.fil_nombre dataType=$dataType_Cliente.fil_nombre}</td>
					</tr>
					<tr>
						<td>{CWCampoTexto textoAsociado="Apellido" nombre="fil_apellido" size="25" editable="true" visible="true" value=$defaultData_Cliente.fil_apellido dataType=$dataType_Cliente.fil_apellido}</td>
					</tr>
					<tr>
						<td>{CWCampoTexto textoAsociado="DNI" nombre="fil_dni" size="9" editable="true" visible="true" value=$defaultData_Cliente.fil_dni dataType=$dataType_Cliente.fil_dni}</td>
					</tr>
					<tr>
						<td>{CWCampoTexto textoAsociado="Telefono" nombre="fil_telefono" size="15" editable="true" visible="true" value=$defaultData_Cliente.fil_telefono dataType=$dataType_Cliente.fil_telefono}</td>
					</tr>
					<tr>
						<td>{CWCampoTexto textoAsociado="Correo" nombre="fil_correo" size="30" editable="true" visible="true" value=$defaultData_Cliente.fil_correo dataType=$dataType_Cliente.fil_correo}</td>
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
	{CWPanel id="lis" tipoComprobacion="envio" action="operarBD" method="post" estado="$estado_lis" claseManejadora="Cliente"}
		{CWBarraSupPanel titulo="Listado de Cliente"}
			{CWBotonTooltip imagen="01" iconCSS="glyphicon glyphicon-plus" titulo="Insertar Cliente" funcion="insertar" actuaSobre="tabla"  action="Cliente__nuevo"}
			{CWBotonTooltip imagen="02" iconCSS="glyphicon glyphicon-edit" titulo="Modificar Cliente" funcion="modificar" actuaSobre="tabla"}
			{CWBotonTooltip imagen="03" iconCSS="glyphicon glyphicon-minus" titulo="Eliminar Cliente" funcion="eliminar" actuaSobre="tabla"}
		{/CWBarraSupPanel}
		{CWContenedor}
			{CWTabla conCheck="true" conCheckTodos="true" id="Tabla1" numFilasPantalla="10" datos=$smty_datosTabla}
				{CWFila tipoListado="false"}
					{CWCampoTexto textoAsociado="Nombre" nombre="lis_nombre" size="40" editable="true" visible="true" value=$defaultData_Cliente.lis_nombre dataType=$dataType_Cliente.lis_nombre}
					{CWCampoTexto textoAsociado="Apellido" nombre="lis_apellido" size="40" editable="true" visible="true" value=$defaultData_Cliente.lis_apellido dataType=$dataType_Cliente.lis_apellido}
					{CWCampoTexto textoAsociado="Dni" nombre="lis_dni" size="9" editable="true" visible="true" value=$defaultData_Cliente.lis_dni dataType=$dataType_Cliente.lis_dni}
					{CWCampoTexto textoAsociado="Direccion" nombre="lis_direccion" size="40" editable="true" visible="true" value=$defaultData_Cliente.lis_direccion dataType=$dataType_Cliente.lis_direccion}
					{CWCampoTexto textoAsociado="Telefono" nombre="lis_telefono" size="10" editable="true" visible="true" value=$defaultData_Cliente.lis_telefono dataType=$dataType_Cliente.lis_telefono}
					{CWCampoTexto textoAsociado="Correo" nombre="lis_correo" size="40" editable="true" visible="true" value=$defaultData_Cliente.lis_correo dataType=$dataType_Cliente.lis_correo}
				{/CWFila}				
				{CWPaginador enlacesVisibles="3" iconCSS="true"}
			{/CWTabla}			
		{/CWContenedor}
		{CWBarraInfPanel}
			{CWBoton imagen="41" iconCSS="glyphicon glyphicon-ok" texto="Guardar" class="button" accion="guardar"}
			{CWBoton imagen="42" iconCSS="glyphicon glyphicon-remove" texto="Cancelar" class="button" accion="cancelar"}			
		{/CWBarraInfPanel}						
	{/CWPanel}	

<!-- ****************** PESTANYAS ************************-->
	{CWContenedorPestanyas}
		{CWPestanya tipo="fil" estado=$estado_fil}		
		{CWPestanya tipo="lis" estado=$estado_lis}
	{/CWContenedorPestanyas}
{/CWMarcoPanel}
{/CWVentana}