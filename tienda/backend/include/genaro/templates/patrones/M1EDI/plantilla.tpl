<!--*********** <<$classname_detalle|capitalize>> ******************-->	
{if $smty_panelActivo eq "<<$classname_detalle|capitalize>>" }
<!--*********** PANEL edi ******************-->	
	{CWPanel id="ediDetalle" detalleDe="<<$tipoMaestro>>" tipoComprobacion="envio" action="operarBD" method="post" estado="on" claseManejadora="<<$classname_detalle|capitalize>>"}
		{CWBarraSupPanel titulo="<<$classname_detalle|capitalize>>"}
			{CWBotonTooltip imagen="01" iconCSS="glyphicon glyphicon-plus" titulo="Insertar registros" funcion="insertar" actuaSobre="ficha"}
			{CWBotonTooltip imagen="02" iconCSS="glyphicon glyphicon-edit" titulo="Modificar registros" funcion="modificar" actuaSobre="ficha"}
			{CWBotonTooltip imagen="03" iconCSS="glyphicon glyphicon-minus" titulo="Eliminar registros" funcion="eliminar" actuaSobre="ficha"}
		{/CWBarraSupPanel}
		{CWContenedor}	
			{CWFichaEdicion  id="FichaDetalle" datos="$smty_datos<<$classname_detalle|capitalize>>"}
				{CWFicha}
				<table class="text" cellspacing="4" cellpadding="4" border="0">
<<section name=edi loop=$fields_detalle>>
					<tr>
<<assign var='campo' value=$fields_detalle[edi]>>
<<assign var='defVal' value=$customFields.$campo.defVal>>
<<assign var='componente' value=$customFields.$campo.componente>>
<<assign var='titVal' value=$customFields.$campo.titVal>>
<<assign var='tamVal' value=$customFields.$campo.tamVal>>
<<assign var='visibleVal' value=$customFields.$campo.visibleVal>>
<<if $componente eq 0 >>
				 	<td>{CWCampoTexto textoAsociado=<<if $titVal eq "" >>"<<$titles_detalle[edi]>>"<<else>>"<<$titVal>>"<</if>> nombre="<<if $foreignKey_detalle[edi] eq "true">><<$fields_detalle[edi]>><<else>>edi_<<$fields_detalle[edi]>><</if>>" size="<<if $tamVal eq "" >><<$lengths_detalle[edi]|floor>><<else>><<$tamVal>><</if>>" editable="true" visible=<<if $visibleVal eq "1" >>"false"<<else>>"true"<</if>> value=<<if $defVal neq ''>>"<<$defVal>>"<<else>>$defaultData_<<$classname_detalle|capitalize>>.<<if $foreignKey_detalle[edi] eq "true">><<$fields_detalle[edi]>><<else>>edi_<<$fields_detalle[edi]>><</if>><</if>> dataType=$dataType_<<$classname_detalle|capitalize>>.<<if $foreignKey_detalle[edi] eq "true">><<$fields_detalle[edi]>><<else>>edi_<<$fields_detalle[edi]>><</if>>}</td>
<<elseif $componente eq 1 >>
					<td>{CWAreaTexto textoAsociado=<<if $titVal eq "" >>"<<$titles_detalle[edi]>>"<<else>>"<<$titVal>>"<</if>> nombre="<<if $foreignKey_detalle[edi] eq "true">><<$fields_detalle[edi]>><<else>>edi_<<$fields_detalle[edi]>><</if>>" rows="3" cols="<<$lengths_detalle[edi]|floor>>" editable="true" visible=<<if $visibleVal eq "1" >>"false"<<else>>"true"<</if>> value=<<if $defVal neq ''>>"<<$defVal>>"<<else>>$defaultData_<<$classname_detalle|capitalize>>.<<if $foreignKey_detalle[edi] eq "true">><<$fields_detalle[edi]>><<else>>edi_<<$fields_detalle[edi]>><</if>><</if>> dataType=$dataType_<<$classname_detalle|capitalize>>.<<if $foreignKey_detalle[edi] eq "true">><<$fields_detalle[edi]>><<else>>edi_<<$fields_detalle[edi]>><</if>>}</td>
<<elseif $componente eq 2 >>
				    <td>{CWCheckBox textoAsociado=<<if $titVal eq "" >>"<<$titles_detalle[edi]>>"<<else>>"<<$titVal>>"<</if>> nombre="<<if $foreignKey_detalle[edi] eq "true">><<$fields_detalle[edi]>><<else>>edi_<<$fields_detalle[edi]>><</if>>" size="<<$lengths_detalle[edi]|ceil>>" editable="true" visible=<<if $visibleVal eq "1" >>"false"<<else>>"true"<</if>> value=<<if $defVal neq ''>>"<<$defVal>>"<<else>>$defaultData_<<$classname_detalle|capitalize>>.<<if $foreignKey_detalle[edi] eq "true">><<$fields_detalle[edi]>><<else>>edi_<<$fields_detalle[edi]>><</if>><</if>> dataType=$dataType_<<$classname_detalle|capitalize>>.<<if $foreignKey_detalle[edi] eq "true">><<$fields_detalle[edi]>><<else>>edi_<<$fields_detalle[edi]>><</if>>}</td>
<<elseif $componente eq 3 >>
				 	<td>{CWLista textoAsociado=<<if $titVal eq "" >>"<<$titles_detalle[edi]>>"<<else>>"<<$titVal>>"<</if>> nombre="<<if $foreignKey_detalle[edi] eq "true">><<$fields_detalle[edi]>><<else>>edi_<<$fields_detalle[edi]>><</if>>" size="<<$lengths_detalle[edi]|ceil>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=<<if $defVal neq ''>>"<<$defVal>>"<<else>>$defaultData_<<$classname_detalle|capitalize>>.<<if $foreignKey_detalle[edi] eq "true">><<$fields_detalle[edi]>><<else>>edi_<<$fields_detalle[edi]>><</if>><</if>> dataType=$dataType_<<$classname_detalle|capitalize>>.<<if $foreignKey_detalle[edi] eq "true">><<$fields_detalle[edi]>><<else>>edi_<<$fields_detalle[edi]>><</if>>}</td>
<<else>>
					<td>{CWLista textoAsociado=<<if $titVal eq "" >>"<<$titles_detalle[edi]>>"<<else>>"<<$titVal>>"<</if>> nombre="<<if $foreignKey_detalle[edi] eq "true">><<$fields_detalle[edi]>><<else>>edi_<<$fields_detalle[edi]>><</if>>" size="<<$lengths_detalle[edi]|ceil>>" editable="true" visible=<<if $visibleVal eq "0" >>"false"<<else>>"true"<</if>> value=<<if $defVal neq ''>>"<<$defVal>>"<<else>>$defaultData_<<$classname_detalle|capitalize>>.<<if $foreignKey_detalle[edi] eq "true">><<$fields_detalle[edi]>><<else>>edi_<<$fields_detalle[edi]>><</if>><</if>> dataType=$dataType_<<$classname_detalle|capitalize>>.<<if $foreignKey_detalle[edi] eq "true">><<$fields_detalle[edi]>><<else>>edi_<<$fields_detalle[edi]>><</if>>}</td>
<</if>>
					</tr>
<</section>>
				</table>
				{/CWFicha}
				{CWPaginador enlacesVisibles="3" iconCSS="true"}
			{/CWFichaEdicion}	 		
		{/CWContenedor}
		{CWBarraInfPanel}
			{CWBoton imagen="41" iconCSS="glyphicon glyphicon-ok" texto="Guardar" class="button" accion="guardar"}
			{CWBoton imagen="42" iconCSS="glyphicon glyphicon-remove" texto="Cancelar" class="button" accion="cancelar"}
		{/CWBarraInfPanel}						
	{/CWPanel}
	
	<!-- ****************** PESTANYAS DETALLE ************************-->	
	{CWContenedorPestanyas id="Detalle"}
		{CWPestanya tipo="edi" panelAsociado="ediDetalle" estado="on"}
	{/CWContenedorPestanyas}
	{/if}
	