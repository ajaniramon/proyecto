{CWVentana tipoAviso=$smty_tipoAviso  codAviso=$smty_codError  descBreve = $smty_descBreve  textoAviso=$smty_textoAviso onLoad=$smty_jsOnLoad}
{CWBarra usuario=$smty_usuario codigo=$smty_codigo customTitle=$smty_customTitle modal=$smty_modal iconOut="glyphicon glyphicon-log-out" iconHome="glyphicon glyphicon-home" iconInfo="glyphicon glyphicon-info-sign"}
	{CWMenuLayer name="$smty_nombre" cadenaMenu="$smty_cadenaMenu"}	
{/CWBarra}
{CWMarcoPanel conPestanyas="true"}

<!--*********** PANEL fil ******************-->
	{CWPanel id="fil" action="buscar" method="post" estado="$estado_fil" claseManejadora="<<$classname|capitalize>>"}
		{CWBarraSupPanel titulo="Mantenimiento de <<$classname|capitalize>>"}
			{CWBotonTooltip imagen="01" iconCSS="glyphicon glyphicon-plus" titulo="Insertar <<$classname|capitalize>>" funcion="insertar" actuaSobre="ficha" action="<<$classname|capitalize>>__nuevo"}
			{CWBotonTooltip imagen="04" iconCSS="glyphicon glyphicon-refresh" titulo="Limpiar campos" funcion="limpiar" actuaSobre="ficha"}
		{/CWBarraSupPanel}
		{CWContenedor}
			{CWFicha}
				<table class="text" cellspacing="4" cellpadding="4" border="0">
<<section name=fil loop=$fields>>
<<assign var='campo' value=$fields[fil]>>
<<assign var='componente' value=$customFields.$campo.componente>>
<<assign var='titVal' value=$customFields.$campo.titVal>>
<<assign var='tamVal' value=$customFields.$campo.tamVal>>
<<assign var='visibleVal' value=$customFields.$campo.visibleVal>>
					<tr>
<<if $componente eq 0 >>
						<td>{CWCampoTexto textoAsociado=<<if $titVal eq "" >>"<<$titles[fil]>>"<<else>>"<<$titVal>>"<</if>> nombre="fil_<<$fields[fil]>>" size="<<if $tamVal eq "" >><<$lengths[fil]|floor>><<else>><<$tamVal>><</if>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname|capitalize>>.<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields[fil]>><</if>> dataType=$dataType_<<$classname|capitalize>>.fil_<<$fields[fil]>>}</td>
<<elseif $componente eq 1 >>
						<td>{CWAreaTexto textoAsociado=<<if $titVal eq "" >>"<<$titles[fil]>>"<<else>>"<<$titVal>>"<</if>> nombre="fil_<<$fields[fil]>>" rows="3" cols="<<$lengths[fil]|floor>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname|capitalize>>.<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields[fil]>><</if>> dataType=$dataType_<<$classname|capitalize>>.fil_<<$fields[fil]>>}</td>
<<elseif $componente eq 2 >>
						<td>{CWCheckBox textoAsociado=<<if $titVal eq "" >>"<<$titles[fil]>>"<<else>>"<<$titVal>>"<</if>> nombre="fil_<<$fields[fil]>>" size="<<$lengths[fil]|floor>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname|capitalize>>.<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields[fil]>><</if>> dataType=$dataType_<<$classname|capitalize>>.fil_<<$fields[fil]>>}</td>
<<elseif $componente eq 3 >>
						<td>{CWLista textoAsociado=<<if $titVal eq "" >>"<<$titles[fil]>>"<<else>>"<<$titVal>>"<</if>> nombre="fil_<<$fields[fil]>>" size="<<$lengths[fil]|floor>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname|capitalize>>.<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields[fil]>><</if>> dataType=$dataType_<<$classname|capitalize>>.fil_<<$fields[fil]>>}</td>
<<else>>
						<td>{CWLista textoAsociado=<<if $titVal eq "" >>"<<$titles[fil]>>"<<else>>"<<$titVal>>"<</if>> nombre="fil_<<$fields[fil]>>" size="<<$lengths[fil]|floor>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname|capitalize>>.<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields[fil]>><</if>> dataType=$dataType_<<$classname|capitalize>>.fil_<<$fields[fil]>>}</td>
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

<!-- ****************** PANEL edi ***********************-->
	{CWPanel id="edi" tipoComprobacion="envio" action="operarBD" method="post" estado="$estado_edi" claseManejadora="<<$classname|capitalize>>"}
		{CWBarraSupPanel titulo="Mantenimiento de <<$classname|capitalize>>"}
			{CWBotonTooltip imagen="01" iconCSS="glyphicon glyphicon-plus" titulo="Insertar registros" funcion="insertar" actuaSobre="ficha" action="<<$classname|capitalize>>__nuevo"}
			{CWBotonTooltip imagen="02" iconCSS="glyphicon glyphicon-edit" titulo="Modificar registros" funcion="modificar" actuaSobre="ficha"}
			{CWBotonTooltip imagen="03" iconCSS="glyphicon glyphicon-minus" titulo="Eliminar registros" funcion="eliminar" actuaSobre="ficha"}
		{/CWBarraSupPanel}
		{CWContenedor}
			{CWFichaEdicion id="FichaEdicion" datos=$smty_datosFicha}
				{CWFicha}
					<table class="text" cellspacing="4" cellpadding="4" border="0">
<<section name=edi loop=$fields>>
<<assign var='campo' value=$fields[edi]>>
<<assign var='componente' value=$customFields.$campo.componente>>
<<assign var='titVal' value=$customFields.$campo.titVal>>
<<assign var='tamVal' value=$customFields.$campo.tamVal>>
<<assign var='visibleVal' value=$customFields.$campo.visibleVal>>
						<tr>
<<if $componente eq 0 >>
						<td>{CWCampoTexto textoAsociado=<<if $titVal eq "" >>"<<$titles[edi]>>"<<else>>"<<$titVal>>"<</if>> nombre="edi_<<$fields[edi]>>" size="<<if $tamVal eq "" >><<$lengths[edi]|floor>><<else>><<$tamVal>><</if>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname|capitalize>>.<<if $primaryKey_maestro[edi] eq "true">><<$fields_maestro[edi]>><<else>>edi_<<$fields[edi]>><</if>> dataType=$dataType_<<$classname|capitalize>>.edi_<<$fields[edi]>>}</td>
<<elseif $componente eq 1 >>
						<td>{CWAreaTexto textoAsociado=<<if $titVal eq "" >>"<<$titles[edi]>>"<<else>>"<<$titVal>>"<</if>> nombre="edi_<<$fields[edi]>>" rows="3" cols="<<$lengths[edi]|floor>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname|capitalize>>.<<if $primaryKey_maestro[edi] eq "true">><<$fields_maestro[edi]>><<else>>edi_<<$fields[edi]>><</if>> dataType=$dataType_<<$classname|capitalize>>.edi_<<$fields[edi]>>}</td>
<<elseif $componente eq 2 >>
						<td>{CWCheckBox textoAsociado=<<if $titVal eq "" >>"<<$titles[edi]>>"<<else>>"<<$titVal>>"<</if>> nombre="edi_<<$fields[edi]>>" size="<<$lengths[edi]|floor>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname|capitalize>>.<<if $primaryKey_maestro[edi] eq "true">><<$fields_maestro[edi]>><<else>>edi_<<$fields[edi]>><</if>> dataType=$dataType_<<$classname|capitalize>>.edi_<<$fields[edi]>>}</td>
<<elseif $componente eq 3 >>
						<td>{CWLista textoAsociado=<<if $titVal eq "" >>"<<$titles[edi]>>"<<else>>"<<$titVal>>"<</if>> nombre="edi_<<$fields[edi]>>" size="<<$lengths[edi]|floor>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname|capitalize>>.<<if $primaryKey_maestro[edi] eq "true">><<$fields_maestro[edi]>><<else>>edi_<<$fields[edi]>><</if>> dataType=$dataType_<<$classname|capitalize>>.edi_<<$fields[edi]>>}</td>
<<else>>
						<td>{CWLista textoAsociado=<<if $titVal eq "" >>"<<$titles[edi]>>"<<else>>"<<$titVal>>"<</if>> nombre="edi_<<$fields[edi]>>" size="<<$lengths[edi]|floor>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname|capitalize>>.<<if $primaryKey_maestro[edi] eq "true">><<$fields_maestro[edi]>><<else>>edi_<<$fields[edi]>><</if>> dataType=$dataType_<<$classname|capitalize>>.edi_<<$fields[edi]>>}</td>
<</if>>
						</tr>
<</section>>
					</table>
<br/>
				{/CWFicha}
				{CWPaginador enlacesVisibles="3" iconCSS="true"}
			{/CWFichaEdicion}
		{/CWContenedor}
		{CWBarraInfPanel}
			{CWBoton imagen="41" iconCSS="glyphicon glyphicon-ok" texto="Guardar" class="button" accion="guardar"}
			{CWBoton imagen="42" iconCSS="glyphicon glyphicon-remove" texto="Cancelar" class="button" accion="cancelar"}
		{/CWBarraInfPanel}						
	{/CWPanel}	

<!-- ****************** PESTANYAS ************************-->
	{CWContenedorPestanyas}
		{CWPestanya tipo="fil" estado=$estado_fil}		
		{CWPestanya tipo="edi" estado=$estado_edi}
	{/CWContenedorPestanyas}
{/CWMarcoPanel}
{/CWVentana}
