<ul id="menu">
	<li><a id="item1" class="item" onclick="this.className='itemActual';document.getElementById('item2').className='item';document.getElementById('item4').className='item';limpiarResultado();getElementById('formPatronTabularRegistro').style.display='block';
								getElementById('formPatronMaestroNDetalles').style.display='none';getElementById('formEliminaModulo').style.display='none';">Patr&oacute;n Simple</a></li>

	<li><a id="item2" class="item" onclick="this.className='itemActual';document.getElementById('item1').className='item';document.getElementById('item4').className='item';limpiarResultado();getElementById('formPatronTabularRegistro').style.display='none';
								getElementById('formPatronMaestroNDetalles').style.display='block';getElementById('formEliminaModulo').style.display='none';seleccionDetalles(1);">Patr&oacute;n Maestro-Detalle</a></li>
	
	<li><a id="item4" class="item" onclick="this.className='itemActual';document.getElementById('item1').className='item';document.getElementById('item2').className='item';document.getElementById('item2').className='item';limpiarResultado();getElementById('formPatronTabularRegistro').style.display='none';
								getElementById('formPatronMaestroNDetalles').style.display='none';getElementById('formEliminaModulo').style.display='block';">Eliminar Clase</a></li>                                                                                    

	<li><a id="item3">Conexi&oacute;n&nbsp;<select onchange="seleccionarConexion();" id="conexion" name="conexion" size="1">

                                                                            <?php
                                                                                    foreach ($conexiones as $indice => $valor)
                                                                                    {
                                                                                            if($conexion == $indice) {
                                                                                                echo "<option value='$indice' SELECTED>";
                                                                                                echo $indice;
                                                                                                echo "</option>";
                                                                                            } else {
                                                                                                echo "<option value='$indice'>";
                                                                                                echo $indice;
                                                                                                echo "</option>";
                                                                                            }

                                                                                    }
                                                                                    ?></select></a></li>
	</ul>

	<br>

	<div id="panel">

	<!-- TABULAR-REGISTRO -->
	<form id="formPatronTabularRegistro" name="formPatronTabularRegistro" action="generaCodigo.php" method="POST">
			<input type="hidden" id="flagCambios" name="flagCambios" value="0">	
			<input type="hidden" id="tipoDePatron" name="tipo" value="TABULAR_REGISTRO"/>
			<input type="hidden" id="ERROR_SGBD" name="ERROR_SGDB" value="0"/>
			<table cellspacing="4" cellpadding="4" border="0" align="center">
				<tr>
					<td><label>Nombre M&oacute;dulo:</label></td><td><input id="nombreModulo" name="nombreModulo" type="text"/></td>
					<td><label>Clase Manejadora:</label></td><td><input id="nombreClase" name="nombreClase" type="text"/></td>
				</tr>
				<tr>

					<td><label>Seleccionar Tabla:</label></td><td>	<select id="nombreTabla" name="nombreTabla" size="1" onchange="parametrosCargados(false);">
															<option value="">Seleccion Tabla</option>
																<?php
																	foreach ($tablas as $indice => $valor)
																	{
																		echo "<option value='$valor'>";
																		echo $valor;
																		echo "</option>";
																	}
																	?></select></td>
                                        <td><label>Seleccionar Patr&oacute;n:</label></td><td>	<select id="patronSeleccionado" name="patron" size="3">
															<option value="P1M2FIL-LIS">(FIL-LIS)</option>
															<option value="P1M2FIL-EDI">(FIL-EDI)</option>
															<option value="P1M3FIL-LIS-EDI">(FIL-LIS-EDI)</option>
														</select></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="button" name="btPersonaliza" value="Parametrizar Campos" onmouseup="btnParametrizarCampos('nombreTabla')"/>
					<img id="ParametrizarSimple" class="sinParametrizar" src="img/warning.png" title="Sin Parametrizar" />
					</td>
				</tr>
				<tr>
					<td colspan="4" style="text-align:center"><input type="button" onclick="enviarFormulario('TABULAR_REGISTRO');" value="Generar"/></td>
				</tr>
			</table>
	</form>

	<!-- MAESTRO-N-DETALLES -->
	<form id="formPatronMaestroNDetalles" name="formPatronMaestroNDetalles" action="salida.php" method="POST">
			<input id="tipoDePatronMD" type="hidden" name="tipo" value="MAESTRO_DETALLE"/>
                        <?php include('include/htmlOutput.php');
                              muestraMensaje('Las claves primarias del Maestro y claves For&aacute;neas de los Detalles deben separarse por comas', 'info'); ?>
			<br/>
                        <table cellspacing="4" cellpadding="4" border="0" align="center">
				<tr>
					<td><label>Nombre M&oacute;dulo:</label></td><td><input id="nombreModuloMD" name="nombreModulo" type="text"/></td>
				</tr>
			</table>
			<fieldset>
			<legend>Maestro</legend>
			<table cellspacing="4" cellpadding="4" border="0" align="center">
				<tr>
					<td><label>Clase Manejadora:</label></td><td><input id="nombreClaseMaestroN" name="nombreClaseMaestro" type="text"/></td>
					<td><label>Seleccionar Tabla:</label></td><td><select id="nombreTablaMaestro" name="nombreTablaMaestro" size="1" onchange="listaPKs(); parametrosCargados(false);">
																	<option value="">Seleccionar...</option>
																	<?php
																		foreach ($tablas as $indice => $valor)
																		{
																			echo "<option value='$valor'>";
																			echo $valor;
																			echo "</option>";
																		}
																		?>
																</select>
					<input type="button" name="btPersonaliza" value="Parametrizar Campos" onmouseup="btnParametrizarCampos('nombreTablaMaestro')"/>
					<img id="ParametrizarMD" class="sinParametrizar" src="img/warning.png" title="Sin Parametrizar" />
					</td>
				</tr>
				<tr>
					<td><label>Clave Primaria:</label></td>
					<!--  <td style="text-align:left;"><input id="primaryKeyMaestro" name="primaryKeyMaestro" type="text"/></td>
					<td><select id="primaryKeyMaestro" name="primaryKeyMaestro" size="1"><option value="">Seleccionar...</option></select></td>	-->
					<td><input id="primaryKeyMaestro" name="primaryKeyMaestro" value=""/></td>									
					<td><label>Seleccionar Patr&oacute;n:</label></td><td><select id="patronMaestro" name="patronMaestro" size="2">
																						<option value="P2M2FIL-EDI">(FIL-EDI)</option>
																						<option value="P2M2FIL-LIS">(FIL-LIS)</option>
																					</select></td>
				</tr>
				<tr>
					<td><label>Numero detalles:&nbsp;</label></td><td>
					<input id="numeroDeDetalles" name="numeroDeDetalles" type="text" size="2" value="1"
					onkeyup="seleccionDetalles(document.getElementById('numeroDeDetalles').value);"
					/></td>
					<!-- onfocus="seleccionDetalles(document.getElementById('numeroDeDetalles').value);"  -->
				</tr>
				</table>

				<div id="definicionDeDetalles"></div>

				<input type="button" onclick="if(document.getElementById('numeroDeDetalles').value > 1) { enviarFormulario('MAESTRO_N_DETALLES'); } else { enviarFormulario('MAESTRO_DETALLE'); }" value="Generar"/>

		</fieldset>
	</form>

	<!-- ELIMINA MODULO -->
	<form id="formEliminaModulo" name="formEliminaModulo" action="generaCodigo.php" method="POST">			
					<?php 
    						muestraMensaje('Funci&oacute;n no disponible por el momento...', 'info'); ?></td>					
	</form>	
	
	</div>
	<div id="resultado"></div>

	<!-- Añadimos un nuevo DIV para la gestión de la personalización por parte del usuario de los campos y sus caracteristicas --> 

	<div id ="personalizaCamposAJAX" style="display:none;"></div>	 