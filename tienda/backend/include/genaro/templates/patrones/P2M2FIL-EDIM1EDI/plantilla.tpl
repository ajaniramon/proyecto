{CWVentana tipoAviso=$smty_tipoAviso  codAviso=$smty_codError  descBreve=$smty_descBreve  textoAviso=$smty_textoAviso onLoad=$smty_jsOnLoad}
{CWBarra usuario=$smty_usuario codigo=$smty_codigo customTitle=$smty_customTitle modal=$smty_modal iconOut="glyphicon glyphicon-log-out" iconHome="glyphicon glyphicon-home" iconInfo="glyphicon glyphicon-info-sign"}	
	{CWMenuLayer name="$smty_nombre" cadenaMenu="$smty_cadenaMenu"}	
{/CWBarra}
{CWMarcoPanel conPestanyas="true"}

<!-- ********************************************** MAESTRO **********************************************-->
	<!--*********** PANEL fil ******************-->
	{CWPanel id="fil" action="buscar" method="post" estado=$estado_fil claseManejadora="<<$classname_maestro|capitalize>>"}
		{CWBarraSupPanel titulo="<<$classname_maestro|capitalize>>"}
			{CWBotonTooltip imagen="01" iconCSS="glyphicon glyphicon-plus" titulo="Insertar registros" funcion="insertar" actuaSobre="ficha" action="<<$classname_maestro|capitalize>>__nuevo"}
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
					 	<td>{CWCampoTexto textoAsociado=<<if $titVal eq "" >>"<<$titles_maestro[fil]>>"<<else>>"<<$titVal>>"<</if>> nombre="fil_<<$fields_maestro[fil]>>" size="<<if $tamVal eq "" >><<$lengths_maestro[fil]|floor>><<else>><<$tamVal>><</if>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname_maestro|capitalize>>.fil_<<$fields_maestro[fil]>>  dataType=$dataType_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields_maestro[fil]>><</if>>}</td>
<<elseif $componente eq 1 >>
						<td>{CWAreaTexto textoAsociado=<<if $titVal eq "" >>"<<$titles_maestro[fil]>>"<<else>>"<<$titVal>>"<</if>> nombre="fil_<<$fields_maestro[fil]>>" rows="3" cols="<<$lengths_maestro[fil]|floor>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname_maestro|capitalize>>.fil_<<$fields_maestro[fil]>>  dataType=$dataType_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields_maestro[fil]>><</if>>}</td>
<<elseif $componente eq 2 >>
					    <td>{CWCheckBox textoAsociado=<<if $titVal eq "" >>"<<$titles_maestro[fil]>>"<<else>>"<<$titVal>>"<</if>> nombre="fil_<<$fields_maestro[fil]>>" size="<<$lengths_maestro[fil]|floor>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname_maestro|capitalize>>.fil_<<$fields_maestro[fil]>>  dataType=$dataType_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields_maestro[fil]>><</if>>}</td>
<<elseif $componente eq 3 >>
					 	<td>{CWLista textoAsociado=<<if $titVal eq "" >>"<<$titles_maestro[fil]>>"<<else>>"<<$titVal>>"<</if>> nombre="fil_<<$fields_maestro[fil]>>" size="<<$lengths_maestro[fil]|floor>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname_maestro|capitalize>>.fil_<<$fields_maestro[fil]>>  dataType=$dataType_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields_maestro[fil]>><</if>>}</td>
<<else>>
						<td>{CWLista textoAsociado=<<if $titVal eq "" >>"<<$titles_maestro[fil]>>"<<else>>"<<$titVal>>"<</if>> nombre="fil_<<$fields_maestro[fil]>>" size="<<$lengths_maestro[fil]|floor>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname_maestro|capitalize>>.fil_<<$fields_maestro[fil]>>  dataType=$dataType_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[fil] eq "true">><<$fields_maestro[fil]>><<else>>fil_<<$fields_maestro[fil]>><</if>>}</td>
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
	
	<!--*********** PANEL edi ******************-->	
		{CWPanel id="edi" esMaestro="true" itemSeleccionado=$smty_filaSeleccionada action="operarBD" method="post" estado=$estado_edi claseManejadora="<<$classname_maestro|capitalize>>" accion=$smty_operacionFicha<<$classname_maestro|capitalize>>}	
		{CWBarraSupPanel titulo="<<$classname_maestro|capitalize>>"}
			{CWBotonTooltip imagen="01" iconCSS="glyphicon glyphicon-plus" titulo="Insertar registro" funcion="insertar" actuaSobre="ficha" action="<<$classname_maestro|capitalize>>__nuevo"}
			{CWBotonTooltip imagen="02" iconCSS="glyphicon glyphicon-edit" titulo="Modificar registro" funcion="modificar" actuaSobre="ficha"}
			{CWBotonTooltip imagen="03" iconCSS="glyphicon glyphicon-minus" titulo="Eliminar registro" funcion="eliminar" actuaSobre="ficha"}
			{CWBotonTooltip imagen="04" iconCSS="glyphicon glyphicon-refresh" titulo="Restaurar valores" funcion="limpiar" actuaSobre="ficha"}	
		{/CWBarraSupPanel}
		{CWContenedor}
			{CWFichaEdicion id="FichaEdicion" datos=$smty_datosFichaM}	
				{CWFicha}

					<table class="text" cellspacing="4" cellpadding="4" border="0">
<<section name=edi loop=$fields_maestro>>
<<assign var='campo' value=$fields_maestro[edi]>>
<<assign var='componente' value=$customFields.$campo.componente>>
<<assign var='titVal' value=$customFields.$campo.titVal>>
<<assign var='tamVal' value=$customFields.$campo.tamVal>>
<<assign var='visibleVal' value=$customFields.$campo.visibleVal>>
						<tr>
<<if $componente eq 0 >>
						 	<td>{CWCampoTexto textoAsociado=<<if $titVal eq "" >>"<<$titles_maestro[edi]>>"<<else>>"<<$titVal>>"<</if>> nombre="edi_<<$fields_maestro[edi]>>" size="<<if $tamVal eq "" >><<$lengths_maestro[edi]|floor>><<else>><<$tamVal>><</if>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname_maestro|capitalize>>.edi_<<$fields_maestro[edi]>>  dataType=$dataType_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[edi] eq "true">><<$fields_maestro[edi]>><<else>>edi_<<$fields_maestro[edi]>><</if>>}</td>
<<elseif $componente eq 1 >>
							<td>{CWAreaTexto textoAsociado=<<if $titVal eq "" >>"<<$titles_maestro[edi]>>"<<else>>"<<$titVal>>"<</if>> nombre="edi_<<$fields_maestro[edi]>>" rows="3" cols=<<if $tamVal eq "" >><<$lengths_maestro[edi]|floor>><<else>><<$tamVal>><</if>> editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname_maestro|capitalize>>.edi_<<$fields_maestro[edi]>>  dataType=$dataType_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[edi] eq "true">><<$fields_maestro[edi]>><<else>>edi_<<$fields_maestro[edi]>><</if>>}</td>
<<elseif $componente eq 2 >>
						    <td>{CWCheckBox textoAsociado=<<if $titVal eq "" >>"<<$titles_maestro[edi]>>"<<else>>"<<$titVal>>"<</if>> nombre="edi_<<$fields_maestro[edi]>>" size="<<$lengths_maestro[edi]|floor>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname_maestro|capitalize>>.edi_<<$fields_maestro[edi]>>  dataType=$dataType_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[edi] eq "true">><<$fields_maestro[edi]>><<else>>edi_<<$fields_maestro[edi]>><</if>>}</td>
<<elseif $componente eq 3 >>
						 	<td>{CWLista textoAsociado=<<if $titVal eq "" >>"<<$titles_maestro[edi]>>"<<else>>"<<$titVal>>"<</if>> nombre="edi_<<$fields_maestro[edi]>>" size="<<$lengths_maestro[edi]|floor>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname_maestro|capitalize>>.edi_<<$fields_maestro[edi]>>  dataType=$dataType_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[edi] eq "true">><<$fields_maestro[edi]>><<else>>edi_<<$fields_maestro[edi]>><</if>>}</td>
<<else>>
							<td>{CWLista textoAsociado=<<if $titVal eq "" >>"<<$titles_maestro[edi]>>"<<else>>"<<$titVal>>"<</if>> nombre="edi_<<$fields_maestro[edi]>>" size="<<$lengths_maestro[edi]|floor>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=$defaultData_<<$classname_maestro|capitalize>>.edi_<<$fields_maestro[edi]>>  dataType=$dataType_<<$classname_maestro|capitalize>>.<<if $primaryKey_maestro[edi] eq "true">><<$fields_maestro[edi]>><<else>>edi_<<$fields_maestro[edi]>><</if>>}</td>
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
	
	<!-- ****************** PESTAÑAS MAESTRO ************************-->
	{CWContenedorPestanyas id="Maestro"}				
		{CWPestanya tipo="fil" panelAsociado="fil" estado=$estado_fil ocultar="Detalle"}
		{CWPestanya tipo="edi" panelAsociado="edi" estado=$estado_edi mostrar="Detalle"}
	{/CWContenedorPestanyas}
</td></tr>
<tr><td>
																																									
<!-- ****************** PANEL DETALLE ***********************-->
	{if count($smty_datosFichaM) gt 0 }
	{CWPanel id="ediDetalle" detalleDe="edi" tipoComprobacion="envio" action="operarBD" method="post" estado="on" claseManejadora="<<$classname_detalle|capitalize>>"}
		{CWBarraSupPanel titulo="<<$classname_detalle|capitalize>>"}
			{CWBotonTooltip imagen="01" iconCSS="glyphicon glyphicon-plus" titulo="Insertar registro" funcion="insertar" actuaSobre="ficha" action="<<$classname_detalle|capitalize>>__nuevo"}			
			{CWBotonTooltip imagen="02" iconCSS="glyphicon glyphicon-edit" titulo="Modificar registro" funcion="modificar" actuaSobre="ficha"}
			{CWBotonTooltip imagen="03" iconCSS="glyphicon glyphicon-minus" titulo="Eliminar registro" funcion="eliminar" actuaSobre="ficha"}										
			{CWBotonTooltip imagen="04" iconCSS="glyphicon glyphicon-refresh" titulo="Restaurar valores" funcion="limpiar" actuaSobre="ficha"}
		{/CWBarraSupPanel}
		{CWContenedor}			 		
			{CWFichaEdicion id="Ficha2" datos=$smty_datosFichaD}	
				{CWFicha}	

					<table class="text" cellspacing="4" cellpadding="4" border="0">
<<section name=edi loop=$fields_detalle>>
<<assign var='campo' value=$fields_detalle[edi]>>
<<assign var='componente' value=$customFields.$campo.componente>>
<<assign var='titVal' value=$customFields.$campo.titVal>>
<<assign var='tamVal' value=$customFields.$campo.tamVal>>
<<assign var='visibleVal' value=$customFields.$campo.visibleVal>>
						<tr>
							<td>{CWCampoTexto textoAsociado=<<if $titVal eq "" >>"<<$titles_detalle[edi]>>"<<else>>"<<$titVal>>"<</if>> nombre="edi_<<$fields_detalle[edi]>>" size="<<if $tamVal eq "" >><<$lengths_detalle[edi]|floor>><<else>><<$tamVal>><</if>>" editable="true" oculto="<<$primaryKey_detalle[edi]>>" <<if $fields_detalle[edi] eq "$foreignKeyDetalle">>value=<<if $defVal neq ''>>"<<$defVal>>"<<else>>$defaultData_<<$classname_detalle|capitalize>>.edi_id_maestro<</if>><<else>>value=<<if $defVal neq ''>>"<<$defVal>>"<<else>>"<<$defaults_detalle[edi]>>"<</if>><</if>><<if $fields_detalle[edi] ne "$foreignKeyDetalle">> dataType=$dataType_<<$classname_detalle|capitalize>>.edi_<<if $primaryKey_detalle[edi] eq "true">>id_detalle<<else>><<$fields_detalle[edi]>><</if>><</if>>}</td>
						</tr>
<</section>>
					</table>
                    <br/><br/>
				{/CWFicha}
				{CWPaginador enlacesVisibles="3" iconCSS="true"}
			{/CWFichaEdicion}	
		{/CWContenedor}
		{CWBarraInfPanel}
			{CWBoton imagen="41" iconCSS="glyphicon glyphicon-ok" texto="Guardar" class="button" accion="guardar"}
			{CWBoton imagen="42" iconCSS="glyphicon glyphicon-remove" texto="Cancelar" class="button" accion="cancelar"}
		{/CWBarraInfPanel}						
	{/CWPanel}		
	
	<!-- ****************** PESTAÑAS DETALLE ************************-->
	{CWContenedorPestanyas id="Detalle"}
	{/CWContenedorPestanyas}
	{/if}
{/CWMarcoPanel}
{/CWVentana}