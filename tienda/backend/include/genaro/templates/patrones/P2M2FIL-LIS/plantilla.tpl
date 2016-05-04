{CWVentana tipoAviso=$smty_tipoAviso  codAviso=$smty_codError  descBreve=$smty_descBreve  textoAviso=$smty_textoAviso onLoad=$smty_jsOnLoad}
{CWBarra usuario=$smty_usuario codigo=$smty_codigo customTitle=$smty_customTitle modal=$smty_modal iconOut="glyphicon glyphicon-log-out" iconHome="glyphicon glyphicon-home" iconInfo="glyphicon glyphicon-info-sign"}	
	{CWMenuLayer name="$smty_nombre" cadenaMenu="$smty_cadenaMenu"}	
{/CWBarra}
{CWMarcoPanel conPestanyas="true"}

<!-- ********************************************** MAESTRO **********************************************-->
	<!--*********** PANEL fil ******************-->
	{CWPanel id="fil" action="buscar" method="post" estado=$estado_fil claseManejadora="<<$classname_maestro|capitalize>>"}
		{CWBarraSupPanel titulo="<<$classname_maestro|capitalize>>"}
			{CWBotonTooltip imagen="01" iconCSS="glyphicon glyphicon-plus" iconCSS="glyphicon glyphicon-plus" titulo="Insertar registros" funcion="insertar" actuaSobre="ficha" action="<<$classname_maestro|capitalize>>__nuevo"}
			{CWBotonTooltip imagen="04" iconCSS="glyphicon glyphicon-refresh" titulo="Restaurar valores" funcion="restaurar" actuaSobre="ficha"}
		{/CWBarraSupPanel}
		{CWContenedor}
			{CWFicha}
			
				<table class="text" cellspacing="4" cellpadding="4" border="0">
<<section name=fil loop=$fields_maestro>>
<<assign var='campo' value=$fields_maestro[fil]>>
<<assign var='componente' value=$customFields.$campo.componente>> 
<<assign var='titVal' value=$customFields.$campo.titVal>>
<<assign var='tamVal' value=$customFields.$campo.tamVal>>
<<assign var='visibleVal' value=$customFields.$campo.visibleVal>>
					<tr>				
<<if $componente eq 0 >>
					 	<td>{CWCampoTexto textoAsociado=<<if $titVal eq "" >>"<<$titles_maestro[fil]>>"<<else>>"<<$titVal>>"<</if>> nombre="<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields_maestro[fil]>><</if>>" size="<<if $tamVal eq "" >><<$lengths[fil]|floor>><<else>><<$tamVal>><</if>>" editable="true" visible=<<if $visibleVal eq "1" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields_maestro[fil]>><</if>> dataType=$dataType_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields_maestro[fil]>><</if>>}</td>
<<elseif $componente eq 1 >>
						<td>{CWAreaTexto textoAsociado=<<if $titVal eq "" >>"<<$titles_maestro[fil]>>"<<else>>"<<$titVal>>"<</if>> nombre="<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields_maestro[fil]>><</if>>" rows="3" cols="<<$lengths_maestro[fil]|floor>>" editable="true" visible=<<if $visibleVal eq "1" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields_maestro[fil]>><</if>> dataType=$dataType_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields_maestro[fil]>><</if>>}</td>
<<elseif $componente eq 2 >>
					    <td>{CWCheckBox textoAsociado=<<if $titVal eq "" >>"<<$titles_maestro[fil]>>"<<else>>"<<$titVal>>"<</if>> nombre="<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields_maestro[fil]>><</if>>" size="<<$lengths_maestro[fil]|floor>>" editable="true" visible=<<if $visibleVal eq "1" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields_maestro[fil]>><</if>> dataType=$dataType_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields_maestro[fil]>><</if>>}</td>
<<elseif $componente eq 3 >>
					 	<td>{CWLista textoAsociado=<<if $titVal eq "" >>"<<$titles_maestro[fil]>>"<<else>>"<<$titVal>>"<</if>> nombre="<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields_maestro[fil]>><</if>>" size="<<$lengths_maestro[fil]|floor>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields_maestro[fil]>><</if>> dataType=$dataType_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields_maestro[fil]>><</if>>}</td>
<<else>>
						<td>{CWLista textoAsociado=<<if $titVal eq "" >>"<<$titles_maestro[fil]>>"<<else>>"<<$titVal>>"<</if>> nombre=<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields_maestro[fil]>><</if>> size="<<$lengths_maestro[fil]|floor>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields_maestro[fil]>><</if>> dataType=$dataType_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields_maestro[fil]>><</if>>}</td>
<</if>>
					</tr>
<</section>>
            	</table>
                <br/>
			{/CWFicha}
		{/CWContenedor}
		{CWBarraInfPanel}
			{CWBoton imagen="50" iconCSS="glyphicon glyphicon-search" texto="Buscar" class="button" accion="buscar" mostrarEspera="true"}
		{/CWBarraInfPanel}						
	{/CWPanel}
	
	<!--*********** PANEL lis ******************-->	
	{CWPanel id="lis" tipoComprobacion="envio" esMaestro="true" itemSeleccionado=$smty_filaSeleccionada action="operarBD" method="post" estado=$estado_lis claseManejadora="<<$classname_maestro|capitalize>>"}
		{CWBarraSupPanel titulo="<<$classname_maestro|capitalize>>"}
			{CWBotonTooltip imagen="01" iconCSS="glyphicon glyphicon-plus" titulo="Insertar registro" funcion="insertar" actuaSobre="tabla" action="<<$classname_maestro|capitalize>>__nuevo"}
			{CWBotonTooltip imagen="02" iconCSS="glyphicon glyphicon-edit" titulo="Modificar registro" funcion="modificar" actuaSobre="tabla"}
			{CWBotonTooltip imagen="03" iconCSS="glyphicon glyphicon-minus" titulo="Eliminar registro" funcion="eliminar" actuaSobre="tabla"}
			{CWBotonTooltip imagen="04" iconCSS="glyphicon glyphicon-refresh" titulo="Restaurar valores" funcion="limpiar" actuaSobre="tabla"}
		{/CWBarraSupPanel}
		{CWContenedor}
			{CWTabla conCheck="true" conCheckTodos="false" id="Tabla1" numFilasPantalla="6" datos=$smty_datosTablaM}
				{CWFila tipoListado="false"}
<<section name=lis loop=$fields_maestro>>
<<assign var='campo' value=$fields_maestro[lis]>>
<<assign var='componente' value=$customFields.$campo.componente>>
<<assign var='titVal' value=$customFields.$campo.titVal>>
<<assign var='tamVal' value=$customFields.$campo.tamVal>>
<<assign var='visibleVal' value=$customFields.$campo.visibleVal>>
<<if $componente eq 0 >>
				 	{CWCampoTexto textoAsociado=<<if $titVal eq "" >>"<<$titles_maestro[lis]>>"<<else>>"<<$titVal>>"<</if>> nombre="<<if $primaryKey_maestro[lis] eq "true">><<$fields_maestro[lis]>><<else>>lis_<<$fields_maestro[lis]>><</if>>" size="<<if $tamVal eq "" >><<$lengths[lis]|floor>><<else>><<$tamVal>><</if>>" editable="true" visible=<<if $visibleVal eq "1" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[lis] eq "true">><<$fields_maestro[lis]>><<else>>lis_<<$fields_maestro[lis]>><</if>> dataType=$dataType_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[lis] eq "true">><<$fields_maestro[lis]>><<else>>lis_<<$fields_maestro[lis]>><</if>>}
<<elseif $componente eq 1 >>
					{CWCampoTexto textoAsociado=<<if $titVal eq "" >>"<<$titles_maestro[lis]>>"<<else>>"<<$titVal>>"<</if>> nombre="<<if $primaryKey_maestro[lis] eq "true">><<$fields_maestro[lis]>><<else>>lis_<<$fields_maestro[lis]>><</if>>" size="<<if $tamVal eq "" >><<$lengths[lis]|floor>><<else>><<$tamVal>><</if>>" editable="true" visible=<<if $visibleVal eq "1" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[lis] eq "true">><<$fields_maestro[lis]>><<else>>lis_<<$fields_maestro[lis]>><</if>> dataType=$dataType_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[lis] eq "true">><<$fields_maestro[lis]>><<else>>lis_<<$fields_maestro[lis]>><</if>>}
<<elseif $componente eq 2 >>
				    {CWCheckBox textoAsociado=<<if $titVal eq "" >>"<<$titles_maestro[lis]>>"<<else>>"<<$titVal>>"<</if>> nombre="<<if $primaryKey_maestro[lis] eq "true">><<$fields_maestro[lis]>><<else>>lis_<<$fields_maestro[lis]>><</if>>" size="<<$lengths_maestro[lis]|floor>>" editable="true" visible=<<if $visibleVal eq "1" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[lis] eq "true">><<$fields_maestro[lis]>><<else>>lis_<<$fields_maestro[lis]>><</if>> dataType=$dataType_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[lis] eq "true">><<$fields_maestro[lis]>><<else>>lis_<<$fields_maestro[lis]>><</if>>}
<<elseif $componente eq 3 >>
				 	{CWLista textoAsociado=<<if $titVal eq "" >>"<<$titles_maestro[lis]>>"<<else>>"<<$titVal>>"<</if>> nombre="<<if $primaryKey_maestro[lis] eq "true">><<$fields_maestro[lis]>><<else>>lis_<<$fields_maestro[lis]>><</if>>" size="<<$lengths_maestro[lis]|floor>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[lis] eq "true">><<$fields_maestro[lis]>><<else>>lis_<<$fields_maestro[lis]>><</if>> dataType=$dataType_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[lis] eq "true">><<$fields_maestro[lis]>><<else>>lis_<<$fields_maestro[lis]>><</if>>}
<<else>>
					{CWLista textoAsociado=<<if $titVal eq "" >>"<<$titles_maestro[lis]>>"<<else>>"<<$titVal>>"<</if>> nombre="<<if $primaryKey_maestro[lis] eq "true">><<$fields_maestro[lis]>><<else>>lis_<<$fields_maestro[lis]>><</if>>" size="<<$lengths_maestro[lis]|floor>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[lis] eq "true">><<$fields_maestro[lis]>><<else>>lis_<<$fields_maestro[lis]>><</if>> dataType=$dataType_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[lis] eq "true">><<$fields_maestro[lis]>><<else>>lis_<<$fields_maestro[lis]>><</if>>}
<</if>>								
<</section>>
				{/CWFila}				
				{CWPaginador enlacesVisibles="3" iconCSS="true"}
			{/CWTabla}
		{/CWContenedor}
		{CWBarraInfPanel}
			{CWBoton imagen="41" iconCSS="glyphicon glyphicon-ok" texto="Guardar" class="button" accion="guardar"}
			{CWBoton imagen="42" iconCSS="glyphicon glyphicon-remove" texto="Cancelar" class="button" accion="cancelar"}
		{/CWBarraInfPanel}						
	{/CWPanel}
	
	<!-- ****************** PESTAÑAS MAESTRO ************************-->	
	{CWContenedorPestanyas id="Maestro"}				
		{CWPestanya tipo="fil" panelAsociado="fil" estado=$estado_fil ocultar="Detalle"}
		{CWPestanya tipo="lis" panelAsociado="lis" estado=$estado_lis mostrar="Detalle"}
	{/CWContenedorPestanyas}
</td></tr>
																																					
<!-- ****************** PANELES DETALLES ***********************-->

{if count($smty_datosTablaM ) gt 0 }

	{CWDetalles claseManejadoraPadre="<<$classname_maestro|capitalize>>" detalles=$smty_detalles panelActivo=$smty_panelActivo}

<tr><td>