/**
* PAQUETE JS con las clases que manejan los botones Tooltips
* Clase objBotonToolTip
* Clase objBTTLimpiar
* Clase objBTTModificar
* Clase objBTTInsertar
* Clase objBTTEliminar
* Clase objBTTAbrirVS
* Clase objBTTBuscarVS
* Clase objBTTRestaurar
**/

Function.prototype.method = function (name, func) 
{
    this.prototype[name] = func;
    return this;
};


Function.method('inherits', function (parent) 
	{
		var d = 0, p = (this.prototype = new parent());
		this.method('uber', function uber(name) 
		{
			var f, r, t = d, v = parent.prototype;
	        if (t) {
	            while (t) {
	                v = v.constructor.prototype;
	                t -= 1;
	            }
	            f = v[name];
	        } else {
	            f = p[name];
	            if (f == this[name]) {
	                f = v[name];
	            }
	        }
	        d += 1;
	        r = f.apply(this, Array.prototype.slice.apply(arguments, [1]));
	        d -= 1;
	        return r;
	    });
	    return this;
	}
)

/**
* Clase que maneja los botones tooltips, se incluye la funcionalidad
* global y de ella heredar�n las distintas clases de botones posteriormente 
*/
function objBotonToolTip(nomObjeto,idPanel,esMaestro,esDetalle)
{
	this.constructor (nomObjeto,idPanel,esMaestro,esDetalle);
}

//Devuelve el obj HTML Form, formulario donde se encuentra el bot�n

objBotonToolTip.method('constructor', function (nomObjeto,idPanel,esMaestro,esDetalle)
	{
		this.nomObj = nomObjeto;
		this.idPanel = idPanel;
		
		this.idForm = 'F_'+idPanel;
		this.formulario = eval('document.getElementById("'+this.idForm+'")');
		
		/* S�lo se utilizan en las pantallas con Maestro Detalle */
		this.idPanelMD = null;
		
		
		this.imagen = null;
	
		if (this.formulario === null) {
	    	this.formulario = null;
	    	this.idForm = '';
		}
		this.nomClase = 'objBotonToolTip';
		this.esMaestro = esMaestro;
		this.esDetalle = esDetalle;	
		this.habilitado = true;
		
		return this;  
	}
);

//////////////////////////////////////////////////////////
//// M�TODOS BOTON TOOLTIP

//// ----------
//// getForm
//// ----------
objBotonToolTip.method('getForm', function ()
	{
		return (this.formulario);
	}
);

//// -------------
//// getFormId
//// -------------
//Devuelve una cadena que ser� el nombre del formulario donde se encuentra el bot�n
objBotonToolTip.method('getFormId', function ()
{
	return (this.idForm);
});

//// ------------
//// getPanel
//// ------------
//Devuelve el nombre del panel donde se encuentra el bot�n
objBotonToolTip.method('getPanel', function ()
{
	return (this.idPanel);
});

//// -------------------
//// getNombreObj
//// -------------------
//Devuelve una cadena que ser� el nombre de la instancia creada
objBotonToolTip.method('getNombreObj', function ()
{
	return (this.nomObj);
});

//// -----------
//// getClase
//// -----------
//Devuelve el nombre de la clase de la q es instancia
objBotonToolTip.method('getClase', function ()
{
	return (this.nomClase);
});

//// --------------------
//// estoyEnMaestro
//// --------------------
//Devuelve un true o false seg�n este o no en un panel maestro
objBotonToolTip.method('estoyEnMaestro', function ()
{
	return (this.esMaestro);
});

//// --------------------
//// estoyEnDetalle
//// --------------------
//Devuelve un true o false seg�n este o no en un panel detalle
objBotonToolTip.method('estoyEnDetalle', function ()
{
	return (this.esDetalle);
});

//// --------------
//// esPanelMD
//// --------------
objBotonToolTip.method('esPanelMD', function ()
	{
		panelMD = false;
		// Existe detalle??
		if (
			(document.getElementById('P_ediDetalle')) ||
			(document.getElementById('P_lisDetalle'))
		)
		{
			// Soy panel dl detalle
			if ( 
				(this.idPanel == 'ediDetalle') ||
				(this.idPanel == 'lisDetalle')
			)
			{
				// Oculto panel maestro
				if (document.getElementById('P_edi'))
					this.idPanelMD = 'edi'
				else
					this.idPanelMD = 'lis'			
				panelMD = true;
			}
			else
			{
			// No soy el panel detalle
				// El panel detalle es de tipo 'edi' o 'lis'????
				if (document.getElementById('P_lisDetalle'))
					this.idPanelMD = 'lisDetalle'
				else
					this.idPanelMD = 'ediDetalle'

				panelMD = true;
			}
		}
		return(panelMD);
	}
);

//// --------------
//// activarGC (Activar los botones Guardar/Cancelar)
//// --------------
objBotonToolTip.method('activarGC', function (panelActivar)
	{
		btnActivar = 'bn'+panelActivar+'_guardar';
	
		if (document.getElementById(btnActivar)) 
		{
			bton = document.getElementById(btnActivar);
			bton.disabled = false;
			bton.style.display = 'inline';
		}		

		btnActivar = 'bn'+panelActivar+'_cancelar';
		if (document.getElementById(btnActivar)) 
		{
			bton = document.getElementById(btnActivar);
			bton.disabled = false;
			bton.style.display = 'inline';
		}
		
		
		var button = document.getElementsByTagName('button');
		for(i=0;i<button.length;i++)
		{
			idButton = button[i].id.split('_');
			clave1 = 'particular'+panelActivar;
			clave2 = 'saltar'+panelActivar;
			if ( (idButton[1] == clave1) || (idButton[1] == clave2) ) 
			{
				bton = document.getElementById(button[i].id);
				bton.disabled = false;
				bton.style.display = 'inline';				
			}
		}
	}
);

//// ----------------------
//// deshabilitarBoton
//// ----------------------
objBotonToolTip.method('deshabilitarBoton', function ()
	{
		//Si el boton ya est� deshabilitado SALIMOS 
		if (!this.habilitado) return;		
		// Accede a la imagen, y deshabilita el boton
		this.imagen = document.getElementById('img_'+this.nomObj);
		ruta = this.imagen.src;
		// Modifica el nombre de la imagen
		var pos = ruta.lastIndexOf('.');
		this.imagen.src = ruta.substring(0,pos)+'off.'+ruta.substring(pos+1);
		this.habilitado = false;
	}
);

//// ----------------------
//// habilitarBoton
//// ----------------------
objBotonToolTip.method('habilitarBoton', function ()
	{
		//Si el boton ya est� habilitado SALIMOS 
		if (this.habilitado) return;
		ruta = this.imagen.src;
		v_rutas = ruta.split('off');
		nuevaRuta = v_rutas[0]+v_rutas[1];
		this.imagen.src=nuevaRuta;
		this.habilitado = true;
	}
);


//// ---------------------------
//// deshabilitarHermanos
//// ---------------------------
objBotonToolTip.method('deshabilitarHermanos', function (cadenaBotones, separador)
	{
		if ((separador=='') || (separador==null))
		{
			separador=',';
		}
		v_botones = cadenaBotones.split(separador);
				
		panelMD = this.esPanelMD();
		
		for (i=0; i<v_botones.length; i++)
		{
			nombreBoton = v_botones[i];
			// Nombres: bttlInsertar_edi | bttlModificar_edi | bttlEliminar_edi
			// Nombres: bttlInsertar_ediDetalle | bttlModificar_ediDetalle | bttlEliminar_ediDetalle
			switch(nombreBoton)
			{
				case 'ins':
				case 'insertar':
					btnDesactivar = 'bttlInsertar_'+this.idPanel;					
					
					if (document.getElementById('img_'+btnDesactivar)) //Si existe el boton...
					{
						bton=eval(btnDesactivar);
						bton.deshabilitarBoton();
					}
				break;
				
				case 'mod':
				case 'modificar':
					btnDesactivar = 'bttlModificar_'+this.idPanel;
					
					if (document.getElementById('img_'+btnDesactivar)) //Si existe el boton...
					{
						bton=eval(btnDesactivar);
						bton.deshabilitarBoton();
					}
				break;
				
				case 'borrar':
				case 'eliminar':
					btnDesactivar = 'bttlEliminar_'+this.idPanel;
					if (document.getElementById('img_'+btnDesactivar)) 
					{
						bton=eval(btnDesactivar);
						bton.deshabilitarBoton();
					}
				break;
				
				case 'restaurar':
				case 'limpiar':
					btnDesactivar = 'bttlLimpiar_'+this.idPanelMD;
					if (document.getElementById('img_'+btnDesactivar)) 
					{
						bton=eval(btnDesactivar);
						bton.deshabilitarBoton();
					}
				break;
			}
		}
	}
);

//// ----------------------
//// deshabilitarMD (Deshabilitar los botones d su maestro o detalle)
//// ----------------------
objBotonToolTip.method('deshabilitarMD', function ()
	{
		tipoPanel = this.idPanel.substring(0,3);
		if (tipoPanel != 'lis')
			this.deshabilitarHermanos('insertar,eliminar,modificar',',');		
		v_botones = ['insertar','eliminar','modificar','restaurar'];
				
		panelMD = this.esPanelMD();
		for (i=0; i<v_botones.length; i++)
		{
			nombreBoton = v_botones[i];
			// Nombres: bttlInsertar_edi | bttlModificar_edi | bttlEliminar_edi
			// Nombres: bttlInsertar_ediDetalle | bttlModificar_ediDetalle | bttlEliminar_ediDetalle
			
			if (this.esMaestro == true)
			{
				// Si hay nDetalles deshabilitar las pesta�as
				if (document.getElementById('detalles') && (operacion != 'modificar')) 
				{
					eval('document.getElementById("detalles").style.display = "none"');
				}
			}
			else
			{
				// Si hay nDetalles deshabilitar las pesta�as
				if (document.getElementById('detalles')) 
				{
					eval('document.getElementById("detalles").style.display = "block"');
				}
			}
			switch(nombreBoton)
			{
				case 'ins':
				case 'insertar':
					btnDesactivar = 'bttlInsertar_'+this.idPanelMD;
					
					if (document.getElementById('img_'+btnDesactivar)) //Si existe el boton...
					{
						bton=eval(btnDesactivar);
						bton.deshabilitarBoton();						
					}
				break;
				
				case 'mod':
				case 'modificar':
					btnDesactivar = 'bttlModificar_'+this.idPanelMD;
					if (document.getElementById('img_'+btnDesactivar)) //Si existe el boton...
					{
						bton=eval(btnDesactivar);
						bton.deshabilitarBoton();
					}
				break;
				
				case 'borrar':
				case 'eliminar':
					btnDesactivar = 'bttlEliminar_'+this.idPanelMD;
					if (document.getElementById('img_'+btnDesactivar)) 
					{
						bton=eval(btnDesactivar);
						bton.deshabilitarBoton();
					}
				break;
				
				case 'restaurar':
				case 'limpiar':		
						btnDesactivar = 'bttlLimpiar_'+this.idPanelMD;
						if (document.getElementById('img_'+btnDesactivar)) 
						{
							bton=eval(btnDesactivar);
							bton.deshabilitarBoton();
						}
				break;
			}
		}
	}
);

//// --------------------
//// getPaginaActiva
//// --------------------
//Devuelve un entero que indica la p�gina activa
objBotonToolTip.method('getPaginaActiva', function ()
{

	objPaginador = null;
	if (this.idPanel != 'fil') {
		objPaginador = eval(this.idPanel+'_paginacion');
	
		if ( (objPaginador==null) || (objPaginador===null) )
		{	
			alert('ObjBTT: No existe un paginador, necesitas definirlo');
			return (-1);		
		}
		else 
		{
			return (objPaginador.getPaginaActiva());
		}
	}
});


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////// 	CLASE PARA EL bot�n LIMPIAR.- objBTTLimpiar 							
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function objBTTLimpiar (nomObjeto,idPanel,esMaestro,esDetalle,iconoCSS)
{
	//llamamos al constructor del padre
	this.uber('constructor',nomObjeto,idPanel,esMaestro,esDetalle);
	this.nomClase = 'objBTTLimpiar';
	this.iconoCSS = iconoCSS;
}

//DECLARACION DE HERENCIA
objBTTLimpiar.inherits(objBotonToolTip);

objBTTLimpiar.method('limpiarCampos', function ()
{
	pagActiva = this.getPaginaActiva();
	if ( (pagActiva==null) || (pagActiva===null) )
		pagActiva = null;
	else
		pagActiva = pagActiva.toString();
	
	formulario = this.getForm();
	
	operacion = arguments[0];
	accionActiva = '';
	
	//Determinamos el estado (inserci�n o modificaci�n)
	if (eval('formulario.accionActiva')) accionActiva = eval('formulario.accionActiva.value');
	if ((accionActiva == "insertada") || (eval('formulario.accionActivaP_F_'+this.idPanel+'.value == "insertar"')))
		operacion = "reset"; //Si es una inserci�n, hacemos reset
	
	switch (this.idPanel)
	{
		case 'fil':
			formulario.reset();
		break;
		case 'edi':
		case 'ediDetalle':
		case 'ediMaestro':
			for(i=0; i<formulario.length; i++)
			{
				elemento = eval('formulario.elements[i]'); //(cam___nomCampo___FichaEdicion_pag)
				idElemento = elemento.id;
				vIdElemento = idElemento.split('___');
				if (vIdElemento.length > 2) 
					prefijo = vIdElemento[0]; // prefijo del campo 'cam' o 'ins'
				else 
					prefijo =''; //Estamos tratando el panel de b�squeda �dificil no?
									
				// s�lo queremos actuar sobre los campos de usuario, es decir, eliminamos:
				// - Campos hidden de control de concurrencia
				// - Campos con el prefijo ant
				if (
					(elemento.type != "hidden") &&
					(prefijo != 'ant') && 
					( (prefijo =='cam') || (prefijo =='ins') )
					)
				{
					sufijo = vIdElemento[2]; // sufijo del campo = FichaEdicion_pag  (cam___nomCampo___FichaEdicion_pag)

					pagCampo = sufijo.split('_')[1];
					// Encontramos un campo de la p�gina activa
					if (pagCampo == pagActiva)
					{
						if (prefijo=='ins') 
						{
							//Si es una inserci�n no hay valor anterior
							if ((elemento.type != 'select-one') && 	(elemento.type != 'select-multiple'))
								elemento.value='';
							else
							{
								listaActual = elemento;
								nombreSinPrefijo = idElemento.substr(3,idElemento.length);
								listaAnterior = eval('formulario.ant'+nombreSinPrefijo);								
								listaActual.options.length = 0;
								for(j=0; j<listaAnterior.options.length; j++)
								{						
									listaActual.options[j] = new Option(listaAnterior.options[j].text, listaAnterior.options[j].value);
									if (listaAnterior.options[j].selected)
									{
										listaActual.options[j].selected = true;
									}//if valor
								}//for j								
							}// if select-one
						}
						else //estamos modificando
						{
							////////////////////////////////////////
							// opci�n volver a los valores anteriores
								nombreSinPrefijo = idElemento.substr(3,idElemento.length);
								if ((elemento.type != 'select-one') && 	(elemento.type != 'select-multiple'))
								{
									if (elemento.type != 'radio')
										elemento.value = eval('formulario.ant'+nombreSinPrefijo+'.value');
								}
							else //
							{
								listaActual = elemento;
								listaAnterior = eval('formulario.ant'+nombreSinPrefijo);								
								listaActual.options.length = 0;
								for(j=0; j<listaAnterior.options.length; j++)
								{
									listaActual.options[j] = new Option(listaAnterior.options[j].text, listaAnterior.options[j].value);
									if (listaAnterior.options[j].selected)
									{
										listaActual.options[j].selected = true;
									}//if valor
								}//for j
							}// if select-one
						}//if-else 'reset'
					}					
				}
			}//for
		break;
		case 'lis':
		case 'lisDetalle':
		case 'lisMaestro':
				if (operacion=='reset')
				{ // ESTAMOS EN MODO INSERCI�N, POR LO TANTO SOLAMENTE LIMPIAREMOS LOS CAMPOS DE INSERCI�N	
					for(i=0;i<formulario.length;i++)
					{				
						elemento = eval('formulario.elements[i]');
						idElemento = elemento.id;
						vIdElemento = idElemento.split('___');
						tipo = elemento.type;
						if ( ( (tipo != 'hidden') && (elemento.readOnly != true) &&  
							((tipo == 'text') || (tipo == 'checkbox') || (tipo == 'radio') || (tipo == 'select-one')) ) &&
							(idElemento.split('___')[0] == 'ins') )
						{
							switch (elemento.type)
							{
								case 'text':
									elemento.value = '';
								break;
								case 'checkbox':
									elemento.checked = false;
								break;
								case 'select-one':
									elemento.options[0].selected = true;
								break;
								case 'radio':
									elemento.checked = false;
								break;
							}
						} // if campos
					} //for
				} //operacion reset
				else
				{ // ESTAMOS EN MODO EDICI�N, NECESITAMOS EL idFila
					idFila = '';
					for(i=0;i<formulario.length;i++)
					{				
						elemento = eval('formulario.elements[i]');
						idElemento = elemento.id;
						vIdElemento = idElemento.split('___');
						tipo = elemento.type;	
						if ( (tipo == 'checkbox') && (idElemento.split('_')[0] == 'check') && (elemento.checked) )
						{
							idFila = idElemento.split('check_')[1];
							elemento.checked = false;
						} // if checkbox
						if (idFila != '')
						{
							// Inicializamos el estado de la fila
							idEstadoFila = 'est_'+idFila;
							estadoFila = document.getElementById(idEstadoFila);
							estadoFila.value = "nada";
							vIdElemento = idElemento.split('___');
							// S�lo queremos los posibles campos q puede haber en una tabla
							// los campos d la fila seleccionada y s�lo los visibles				
							if ( ( (tipo != 'hidden') && (elemento.readOnly != true) && ((tipo == 'text') || (tipo == 'checkbox') || (tipo == 'radio') || (tipo == 'select-one')) ) &&
								((vIdElemento.length > 1) && (vIdElemento[2] == idFila) && (vIdElemento[0] != 'ant')) )
							{
								idSinPrefijo = idElemento.substr(3,idElemento.length);
								switch (elemento.type)
								{
									case 'text':
										valorAnt = eval('formulario.ant'+idSinPrefijo+'.value');
										elemento.value = valorAnt;
										//elemento.readOnly = true;
									break;
									case 'checkbox':
										idSinPrefijo = idElemento.substr(4,id.length); //ccam (checkbox) lcam (listas disabled)
										valorAnt = eval('formulario.ant'+idSinPrefijo+'.value');
										valorActual = eval('formulario.cam'+idSinPrefijo+'.value');
										if (valorAnt != valorActual)
											if (eval('document.getElementById("'+idElemento+'").checked'))
												eval('document.getElementById("'+idElemento+'").checked = "false"');
											else
												eval('document.getElementById("'+idElemento+'").checked = "true"');							
										eval('document.getElementById("'+idElemento+'").disabled = "true"');									
									break;
									case 'select-one':
										valorAnt = eval('formulario.ant'+idSinPrefijo+'.value');
										for(k=0;k<elemento.options.length;k++)
										{
											valor = elemento.options[k].value;
											if (valor == valorAnt)
											{
												elemento.options[k].selected = "true";
												break;
											}
										}
										//elemento.disabled = true;
									break;
									case 'radio':
										valorAnt = eval('formulario.ant'+idSinPrefijo+'.value');
										elemento.checked = false;
										//elemento.disabled = true;
									break;
								} // switch			
							} // if campos				
						} // if idFila
					} // for
				} // else
			
		break;
		default:
			//no se sabe aun	
		break;
	};//Fin Switch	
	marcaModificado = document.getElementById(this.idPanel+'_imgModificado');
	if (marcaModificado != null)
	{
		marcaModificado.style.display="none";
	}
}
);


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////// 	CLASE PARA EL bot�n INSERTAR.- objBTTInsertar						
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function objBTTInsertar (nomObjeto,idPanel,esMaestro,esDetalle, tresModos,iconoCSS)
{
	//llamamos al constructor del padre
	this.uber('constructor',nomObjeto,idPanel,esMaestro,esDetalle);
	this.nomClase = 'objBTTInsertar';
	this.tresModos = tresModos;
	this.iconoCSS = iconoCSS;
}

//DECLARACION DE HERENCIA
objBTTInsertar.inherits(objBotonToolTip);
// activarCampos para el bot�n insertar
objBTTInsertar.method('activarCampos', function (prefijoCampo)
	{
	// prefijoCampo: ins - cins (insertar) / cam (modificar) / ant (valor anterior)
	// Nombre campo: 'prefijoCampo___'
		formulario = this.getForm();
		for(i=0;i<formulario.length;i++)
		{
			nombre = formulario.elements[i].id;
			idCampo = nombre.split('___'); 
			// prefijo del campo
			
			if ((idCampo[0] == 'cins') || (idCampo[0] == 'ins'))
			{
				//////////////////////////////////////////////
				/// CHECKBOX			
				if ( (this.idPanel == 'lis') || (this.idPanel == 'lisDetalle'))
				{
					if (formulario.elements[i].type == 'radio')
					{
						// En el caso de los radio, como es diferente el id dl name, el nombre de la capa que los hace ocultos
						// es en base al name
						nameRadio = formulario.elements[i].name;
						eval('document.getElementById("IGEPVisible'+nameRadio+'").style.visibility = "visible"');
					}
					else
					{
						if ( (formulario.elements[i].type != 'select-multiple') && (formulario.elements[i].type != 'hidden'))
						{			
							eval('document.getElementById("IGEPVisible'+nombre+'").style.visibility = "visible"');
						}
					}
				}

				// Comprobamos las im�genes si no se trabaja con iconos
				if (this.iconoCSS == '')
				{
				////////////////////////////////
				/// CALENDARIO
					if (eval('document.getElementById("cal_'+nombre+'")'))
					{
						classCampo = eval('document.getElementById("'+nombre+'").className');		
						vClassCampo = classCampo.split(" ");
						vClassCampo[1] = (vClassCampo[1] != undefined)? vClassCampo[1] : '';
						vClassCampo[2] = (vClassCampo[2] != undefined)? vClassCampo[2] : '';					
						if ( (vClassCampo[1] != "noEdit") && (vClassCampo[1] != "tableNoEdit"))
						{
							src = eval('document.getElementById("cal_'+nombre+'").src');
							src = src.replace("botones/17off.gif","botones/17.gif");
							eval('document.getElementById("cal_'+nombre+'").src = "'+src+'"');			
						}
					};
					///////////////////////////////////////////////////
					/// VENTANA DE SELECCI�N
	  				if (eval('document.getElementById("vs_'+idCampo[1]+"___"+idCampo[2]+'")'))
					{
	  					if (eval('document.getElementById("vs_'+idCampo[1]+"___"+idCampo[2]+'").className'))
	  					{
	  						if (eval('document.getElementById("vs_'+idCampo[1]+"___"+idCampo[2]+'").className') == 'tableNew')
	  						{
								src = eval('document.getElementById("vs_'+idCampo[1]+"___"+idCampo[2]+'").src');
								if (src.indexOf("off.gif") != -1)
									src = src.replace("botones/13off.gif","botones/13.gif");
								else
									src = src.replace("pestanyas/pix_trans.gif","botones/13.gif");
								eval('document.getElementById("vs_'+idCampo[1]+"___"+idCampo[2]+'").src = "'+src+'"');
	  						}
	  					}
	  					else // Para que siga funcionando como antes de tener el par�metro "editable"
	  					{
							src = eval('document.getElementById("vs_'+idCampo[1]+"___"+idCampo[2]+'").src');
							if (src.indexOf("off.gif") != -1)
								src = src.replace("botones/13off.gif","botones/13.gif");
							else
								src = src.replace("pestanyas/pix_trans.gif","botones/13.gif");
							eval('document.getElementById("vs_'+idCampo[1]+"___"+idCampo[2]+'").src = "'+src+'"');
	  					}
					}
  				
	  				///////////////////////////////////////////////////
					/// BOT�N DE SALTO			
	  				if (eval('document.getElementById("jump_'+idCampo[1]+"___"+idCampo[2]+'")'))
					{
	  					if (eval('document.getElementById("jump_'+idCampo[1]+"___"+idCampo[2]+'").className'))
	  					{
	  						if (eval('document.getElementById("jump_'+idCampo[1]+"___"+idCampo[2]+'").className') == 'tableNew')
	  						{
		  						src = eval('document.getElementById("jump_'+idCampo[1]+"___"+idCampo[2]+'").src');
		  						if (src.indexOf("off.gif") != -1)
		  							src = src.replace("off.gif",".gif");
		  						eval('document.getElementById("jump_'+idCampo[1]+"___"+idCampo[2]+'").src = "'+src+'"');
	  						}
	  					}
	  					else // Para que siga funcionando como antes de tener el par�metro "editable"
	  					{
							src = eval('document.getElementById("jump_'+idCampo[1]+"___"+idCampo[2]+'").src');
							if (src.indexOf("off.gif") != -1)
								src = src.replace("off.gif",".gif");
							eval('document.getElementById("jump_'+idCampo[1]+"___"+idCampo[2]+'").src = "'+src+'"');	
	  					}
					}
	  				///////////////////////////////////////////////////
					/// BOT�N DE actualizaCampos			
	  				if (eval('document.getElementById("func_'+idCampo[1]+"___"+idCampo[2]+'")'))
					{
	  					if (eval('document.getElementById("func_'+idCampo[1]+"___"+idCampo[2]+'").className'))
	  					{
	  						if (eval('document.getElementById("func_'+idCampo[1]+"___"+idCampo[2]+'").className') == 'tableNew')
	  						{
								src = eval('document.getElementById("func_'+idCampo[1]+"___"+idCampo[2]+'").src');
								if (src.indexOf("off.gif") != -1)
									src = src.replace("off.gif",".gif");
								eval('document.getElementById("func_'+idCampo[1]+"___"+idCampo[2]+'").src = "'+src+'"');
	  						}  						
	  					}
	  					else // Para que siga funcionando como antes de tener el par�metro "editable"
	  					{
							src = eval('document.getElementById("func_'+idCampo[1]+"___"+idCampo[2]+'").src');
							if (src.indexOf("off.gif") != -1)
								src = src.replace("off.gif",".gif");
							eval('document.getElementById("func_'+idCampo[1]+"___"+idCampo[2]+'").src = "'+src+'"');
	  					}
					}
				
					selectMultiple = idCampo[2].substr(idCampo[2].length-2,idCampo[2].length);
			  		if (selectMultiple == '[]')
			  		{
				  		selectMultiple = idCampo[2].substr(0,idCampo[2].length-2);
				  		idCampo[2] = selectMultiple;
				  		if (eval('document.getElementById("selCopiar'+idCampo[1]+"___"+idCampo[2]+'")'))
						{
							src = eval('document.getElementById("selCopiar'+idCampo[1]+"___"+idCampo[2]+'").src');
							src = src.replace("igep/images/pestanyas/pix_trans.gif","igep/images/botones/flechaIzq.gif");
							eval('document.getElementById("selCopiar'+idCampo[1]+"___"+idCampo[2]+'").src = "'+src+'"');	
						}
						if (eval('document.getElementById("selModificar'+idCampo[1]+"___"+idCampo[2]+'")'))
						{
							src = eval('document.getElementById("selModificar'+idCampo[1]+"___"+idCampo[2]+'").src');
							src = src.replace("igep/images/pestanyas/pix_trans.gif","igep/images/botones/flechaDcha.gif");
							eval('document.getElementById("selModificar'+idCampo[1]+"___"+idCampo[2]+'").src = "'+src+'"');	
						}
						if (eval('document.getElementById("selLimpiar'+idCampo[1]+"___"+idCampo[2]+'")'))
						{
							src = eval('document.getElementById("selLimpiar'+idCampo[1]+"___"+idCampo[2]+'").src');
							src = src.replace("igep/images/pestanyas/pix_trans.gif","igep/images/botones/42.gif");
							eval('document.getElementById("selLimpiar'+idCampo[1]+"___"+idCampo[2]+'").src = "'+src+'"');	
						}
				  	}
				}	
				else
				{
					if (eval('document.getElementById("cal_'+nombre+'")'))
					{
						classCampo = eval('document.getElementById("cal_'+nombre+'").className');
  						vClassCampo = classCampo.split(" ");
  						vClassCampo[0] = (vClassCampo[0] != undefined)? vClassCampo[0] : '';
  						vClassCampo[1] = (vClassCampo[1] != undefined)? vClassCampo[1] : '';
  						if (vClassCampo[1] == "disabled")
  							eval('document.getElementById("cal_'+nombre+'").className = "'+vClassCampo[0]+'"');
					}

	  				if (eval('document.getElementById("vs_'+idCampo[1]+"___"+idCampo[2]+'")'))
	  				{
						classCampo = eval('document.getElementById("vs_'+idCampo[1]+"___"+idCampo[2]+'").className');
  						vClassCampo = classCampo.split(" ");
  						vClassCampo[0] = (vClassCampo[0] != undefined)? vClassCampo[0] : '';
  						vClassCampo[1] = (vClassCampo[1] != undefined)? vClassCampo[1] : '';
  						if (vClassCampo[1] == "disabled")
  							eval('document.getElementById("vs_'+idCampo[1]+"___"+idCampo[2]+'").className = "'+vClassCampo[0]+'"');
	  				}

	  				if (eval('document.getElementById("jump_'+idCampo[1]+"___"+idCampo[2]+'")'))
	  				{
						classCampo = eval('document.getElementById("jump_'+idCampo[1]+"___"+idCampo[2]+'").className');
  						vClassCampo = classCampo.split(" ");
  						vClassCampo[0] = (vClassCampo[0] != undefined)? vClassCampo[0] : '';
  						vClassCampo[1] = (vClassCampo[1] != undefined)? vClassCampo[1] : '';
  						if (vClassCampo[1] == "disabled")
  							eval('document.getElementById("jump_'+idCampo[1]+"___"+idCampo[2]+'").className = "'+vClassCampo[0]+'"');
	  				}

	  				if (eval('document.getElementById("func_'+idCampo[1]+"___"+idCampo[2]+'")'))
	  				{
						classCampo = eval('document.getElementById("func_'+idCampo[1]+"___"+idCampo[2]+'").className');
  						vClassCampo = classCampo.split(" ");
  						vClassCampo[0] = (vClassCampo[0] != undefined)? vClassCampo[0] : '';
  						vClassCampo[1] = (vClassCampo[1] != undefined)? vClassCampo[1] : '';
  						if (vClassCampo[1] == "disabled")
  							eval('document.getElementById("func_'+idCampo[1]+"___"+idCampo[2]+'").className = "'+vClassCampo[0]+'"');
	  				}

					if (formulario.elements[i].type == 'file')
					{
						idFile = formulario.elements[i].id;
						eval('document.getElementById("'+idFile+'").disabled = "false"');
					}
				}
				
				//seg�n el valor del CLASS inicial fijamos el nuevo valor
				nuevoClass = '';
				classCampo = eval('formulario.elements['+i+'].className');
				vClassCampo = classCampo.split(" ");
				vClassCampo[1] = (vClassCampo[1] != undefined)? vClassCampo[1] : '';
				vClassCampo[2] = (vClassCampo[2] != undefined)? vClassCampo[2] : '';		
				switch(vClassCampo[1])
				{
				// Valores contenidos en una TABLA
					case "tableEdit":
						nuevoClass = classCampo.replace(vClassCampo[1],"tableInsert");
						formulario.elements[i].className = nuevoClass;
						if ((formulario.elements[i].type=="text")||(formulario.elements[i].type=="textarea"))
						{
							formulario.elements[i].readOnly = false;  
						}
						else
							formulario.elements[i].disabled = false;
					break;
					case "tableNoEdit":
						nuevoClass = classCampo.replace(vClassCampo[1],"tableNoEdit");
						formulario.elements[i].className = nuevoClass;
						if ((formulario.elements[i].type=="text")||(formulario.elements[i].type=="textarea"))
	     				{
	     					formulario.elements[i].readOnly = true;
	     				}
						else
							formulario.elements[i].disabled = false;
					break;
					case "tableNew": // Caso de claves primarias
						nuevoClass = classCampo.replace(vClassCampo[1],"tableInsert");
						formulario.elements[i].className = nuevoClass;
						if ((formulario.elements[i].type=="text")||(formulario.elements[i].type=="textarea"))
						{
							formulario.elements[i].readOnly = false;
	 					}
	 					else
							formulario.elements[i].disabled = false;
					break;
					// Valores NO contenidos en una tabla
					case "edit":
						nuevoClass = classCampo.replace(vClassCampo[1],"modify");
						formulario.elements[i].className = nuevoClass;
						if ((formulario.elements[i].type=="text")||(formulario.elements[i].type=="textarea"))
						{
							formulario.elements[i].readOnly = false;
						}
						else
							formulario.elements[i].disabled = false;
					break;
					case "noEdit":
						nuevoClass = classCampo.replace(vClassCampo[1],"noEdit");
						formulario.elements[i].className = nuevoClass;
						if ((formulario.elements[i].type=="text")||(formulario.elements[i].type=="textarea"))
						{
							 formulario.elements[i].readOnly = true;
						}
						else
							formulario.elements[i].disabled = true;
					break;		
					case "new":		
						nuevoClass = classCampo.replace(vClassCampo[1],"modify");
						formulario.elements[i].className = nuevoClass;	
						if ((formulario.elements[i].type=="text")||(formulario.elements[i].type=="textarea"))
						{
							formulario.elements[i].readOnly = false;
	 					}
	 					else
							formulario.elements[i].disabled = false;
					break;
				}//Fin Switch
			}//Fin if prefijo
		}//Fin for		
	}//Fin cuerpo metodo
);

objBTTInsertar.method('insertar', function ()
	{
		if (this.habilitado ==  false) return;
		
		//Activamos los botones inferiores!!
		if (this.tresModos != 1)
			this.activarGC(this.idPanel);
		
		nomForm = this.getFormId();
		formulario = this.getForm();
	 	accionActivaP = 'accionActivaP_'+nomForm;
		// Campo oculto para indicar a la parte de negocio la acci�n q se va a realizar
		eval('formulario.'+accionActivaP+'.value="insertar"');
		//Apuntamos al paginador 
		objPaginador = null;
		if (this.idPanel != 'fil') // El panel filtro no tiene paginador
		{
		
			if (this.esPanelMD()) // Maestro/Detalle
			{
					if ((this.idPanel=='edi') || (this.idPanel == 'lis')) //Soy el panel maestro
					{
						//Oculto el detalle
						nomObjPanelAux = this.idPanel+'_panel';
						objPanelAux = eval(nomObjPanelAux);
						objPanelAux.ocultarPanel("Detalle");
						// Deshabilitar mis hermanos
						//this.deshabilitarHermanos('insertar,eliminar,modificar',',');
					}
	
				    if ((this.idPanel=='ediDetalle') || (this.idPanel == 'lisDetalle')) //Soy el panel detalle			    
					{
						// Oculto los botones dl panel complementario
						//this.deshabilitarMD('insertar');

						// Si en el detalle hay solapas (varios detalles) ocultamos las solapas
						// si estamos trabajando sobre uno de ellos
						if (document.getElementById("detalles"))
							document.getElementById("detalles").style.display = 'none';
					}
			}
			objPaginador = eval(this.idPanel+'_paginacion');
			if ( (objPaginador==null) || (objPaginador===null) )
			{	
				alert('ObjBTTInsertar: No existe un paginador, necesitas definirlo');
				return (-1);		
			}		
			objPaginador.activarPaginasInsercion();
			// Activamos los campos para insertar
			this.activarCampos('ins');
			marcaModificado = document.getElementById(this.idPanel+'_imgModificado');
			if (marcaModificado != null)
			{
				if (eval('document.getElementById("capa_menuFalso")'))
				{
					capaMenuFalso = document.getElementById('capa_menuFalso');
					capaMenuFalso.style.display="inline";
				}
				if (eval('capaMenuReal = document.getElementById("capa_menuReal")'))
				{
					capaMenuReal = document.getElementById('capa_menuReal');	
					capaMenuReal.style.display="none";
				}
				if (eval('document.getElementById("permitirCerrarAplicacion")'))
				{
					ocultoPerCerrarApli=document.getElementById('permitirCerrarAplicacion');
					ocultoPerCerrarApli.value='no';
				}	
			}
		}
		this.habilitado = false;
	}
);

//************************************************************************//
//*** FUNCI�N: obtenerValoresMaestro(maestro, detalle) 			 ****/
//*** Copia los valores de la clave primaria del maestro al detalle ****/
//************************************************************************//
objBTTInsertar.method('obtenerValoresMaestro', function (maestro, detalle)
{
	maestro = eval('document.forms["'+maestro+'"]');
	detalle = eval('document.forms["'+detalle+'"]');
	vCamposMaestro = new Array();
	for (i=0;i<maestro.length;i++)
	{
		idFila = '';
		// EL MAESTRO ES UNA TABLA
		if ((maestro.elements[i].type == 'checkbox') && (maestro.elements[i].checked) ) 
		{
			vNombre = maestro.elements[i].id.split('_'); 
			if (vNombre[0] == 'check') // Es un check de seleccionar fila
			{
				idFila = vNombre[2]; // El n�mero d fila chequeada
				sufijoCheck = vNombre[1]+'_'+vNombre[2]; 
				x = i++;
				do
				{
					if  (maestro.elements[x].id.split('___')[0] == 'cam') 
					{
						//vCamposMaestro.push(maestro.elements[x].name);
						vCamposMaestro.push(maestro.elements[x].id);
					}
					x++;						
				} while (maestro.elements[x].id.split('___')[2] == sufijoCheck);
			}
		}//if checkbox
		// EL MAESTRO ES UNA FICHA
		else if (maestro.elements[i].id.split('___')[0] == 'pagActual') 
		{
			idFila = maestro.elements[i].value; //El n?mero de ficha seleccionada
			// cam___nombre___ficha_0
			
			for (x=0;x<maestro.length;x++) {
				//vMaestro = maestro.elements[x].name.split('___');
				vMaestro = maestro.elements[x].id.split('___');
				if (vMaestro) {
					if  ((vMaestro[0] == 'cam') && (vMaestro[2].split('_')[1] == idFila)) 
					{
						//vCamposMaestro.push(maestro.elements[x].name);
						vCamposMaestro.push(maestro.elements[x].id);
					}
				
				}
			}
		}
	}//for maestro
	for (i=0;i<vCamposMaestro.length;i++)
	{
		for(j=0;j<detalle.length;j++) 
		{
				nomCampoMaestro = vCamposMaestro[i].split('___')[1]; // Dividimos el nombre x '___'
				nomCampoDetalle = detalle.elements[j].id.split('___'); // Dividimos el nombre x '___'
				if ( (nomCampoDetalle[0] == 'ins') && (nomCampoDetalle[1] == nomCampoMaestro) )
				{
					detalle.elements[j].value = eval('maestro.'+vCamposMaestro[i]+'.value');
				}//if
		}//for j
	}//for i
}
);

//////////////////////////////////////////////////////
// Funci�n para el plugin CWSelector
// Par�metros: 
//			- destino: id de la lista m�ltiple q ser� donde se vayan recogiendo todos los valores de los campos
//			- separador: car�cter que se utilizar� de separador cuando haya m�s de un campo a acumular por cada l�nea de la lista m�ltiple destino
//			- resto de argumentos, no aparecen indicados literalmente, pero se le pasar�n los id de todos los campos origen de los que se copiar?n
//				los valores a la lista m�ltiple destino
//////////////////////////////////////////////////////
objBTTInsertar.method('copiarToLista', function (destino, separador)
	{
	    valueFinal = '';
	    textFinal = '';
	    // Caso en el que el campo origen es un select-multiple
	    if ( (arguments.length == 3) && (eval('document.getElementById("'+arguments[2]+'").type') == 'select-multiple') )
	    {
	    	select = eval('document.getElementById("'+arguments[2]+'")');
			for (var j=0;j<select.options.length;j++)
			{
				if (select.options[j].selected)
				{
					// A�adimos el valor de la concatenaci�n de todos los campos al select m�ltiple destino
					posicion = eval('document.getElementById("'+destino+'").length');
					campoDestino = eval('document.getElementById("'+destino+'")');
					campoDestino.options[posicion] = new Option(select.options[j].text,select.options[j].value);
					campoDestino.options[posicion].selected = true;
				}
			}
	    }
	    else 
	    {
		    for(i=2;i<arguments.length;i++)  // Bucle q comienza en 2 pq solo queremos recorrer los campos que act�an de origen
			{
				origen = eval('document.getElementById("'+arguments[i]+'")');
				switch(origen.type)
				{
					case "text":
						valueFinal += eval('document.getElementById("'+arguments[i]+'").value');
						textFinal += eval('document.getElementById("'+arguments[i]+'").value');
					break;
					case "select-one":
						textFinal += eval('document.getElementById("'+arguments[i]+'").options[document.getElementById("'+arguments[i]+'").selectedIndex].text');
						valueFinal += eval('document.getElementById("'+arguments[i]+'").options[document.getElementById("'+arguments[i]+'").selectedIndex].value');
					break;
				}				
				// Hay m�s de un campo origen y a�n no estamos en el �ltimo por lo tanto necesitamos separador
				if ( (i <= arguments.length - 2) && (arguments.length > 3) ) 
				{
					valueFinal += separador;
					textFinal += separador;
				}
			}
			// A�adimos el valor de la concatenaci�n de todos los campos al select m�ltiple destino
			posicion = eval('document.getElementById("'+destino+'").length');
			campoDestino = eval('document.getElementById("'+destino+'")');
			campoDestino.options[posicion] = new Option(textFinal,valueFinal);
			campoDestino.options[posicion].selected = true;			
	    }

    	//Marcamos la modificacion en el panel (ficha/fila)
   		cadenaAux = 'document.'+this.idPanel+'_comp';
		objComprobacion = eval(cadenaAux);//Instanciar el objeto
		objComprobacion.marcarModificacionCampo(destino);
	  }
);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////// 	CLASE PARA EL bot�n MODIFICAR.- objBTTModificar						
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function objBTTModificar (nomObjeto,idPanel,esMaestro,esDetalle,tresModos,iconoCSS)
{
	//llamamos al constructor del padre
	this.uber('constructor',nomObjeto,idPanel,esMaestro,esDetalle);
	this.nomClase = 'objBTTModificar';	
	this.tresModos = tresModos;
	this.iconoCSS = iconoCSS;
}

//DECLARACI�N DE HERENCIA
objBTTModificar.inherits(objBotonToolTip);

objBTTModificar.method('modificarTabla', function ()
	{
		var idFila = "";
		var hayFoco = 0;
		
		//Activamos los botones inferiores!!
		if (this.tresModos != 1)	
			this.activarGC(this.idPanel);
		
		formulario = this.getForm();
		nomForm = this.getFormId();
		// Tenemos un campo oculto q contendr� la acci�n realizada con los botones ToolTip
		accionActivaP = 'accionActivaP_'+nomForm;
		
		if (this.esPanelMD()) // Si es maestro o Detalle
		{
		/*	this.deshabilitarMD('modificar'); //Deshabilito botones del panel complementario
			if (this.idPanel == 'lis') //Soy el panel maestro
				this.deshabilitarHermanos('insertar,eliminar,modificar',',');*/

			// Si en el detalle hay solapas (varios detalles) ocultamos las solapas
			// si estamos trabajando sobre uno de ellos
			if (document.getElementById("detalles"))
				document.getElementById("detalles").style.display = 'none';
		}
		
		idFila = '';
		for(i=0;i<formulario.length;i++) 
		{
			/// BUSCAMOS LA FILA SELECCIONADA PARA CAMBIARLA DE COLOR Y ESTADO
			// check_idFila -> idFila = "F_".$idTabla."_".$iterActual;		
			tipoCampo = formulario.elements[i].type;
			nombreCampo = formulario.elements[i].id;
			prefijo = nombreCampo.split('_')[0];
			idFilaCampo = nombreCampo.split('___')[2];
		
			// PARA EL CASO DE UNA TABLA PQ TIENE UN CHECK Q LA IDENTIFICA
			if ( (prefijo== 'check') && (tipoCampo == 'checkbox') && (formulario.elements[i].checked) && 
				(nombreCampo != 'seleccionarTodo') )
			{
						// Las filas chequeadas se marcar�n como modificadas
						idFila = nombreCampo.split('check_')[1];
						fila = eval('document.getElementById("'+idFila+'")');
						// Campo oculto con el estado d la fila
		 				eval('formulario.est_'+idFila+'.value="modificada"');
						// Oculto con la acci�n
					 	eval('formulario.'+accionActivaP+'.value="modificar"');
			} // if checkbox

			if ((idFila != '') && (idFilaCampo == idFila) // solo queremos los campos correspondientes a la fila a modificar
					&& (nombreCampo.split('___')[0] != 'ant'))  
			{
				if ( ( (tipoCampo != 'hidden')  ||  // Todos los campos q no sean ocultos
					 ( (prefijo != 'check') && (tipoCampo == 'checkbox') && (nombreCampo != 'seleccionarTodo') )  // Es un checkbox d una fila
					) )
				{
					classCampo = formulario.elements[i].className;					
					vClassCampo = classCampo.split(' '); // ej. text tableEdit alerta
					vClassCampo[1] = (vClassCampo[1] != undefined)? vClassCampo[1] : '';
					vClassCampo[2] = (vClassCampo[2] != undefined)? vClassCampo[2] : '';	
					
					if (hayFoco == 0) campoFoco = formulario.elements[i];
					else hayFoco = 1;					
	
					if (this.iconoCSS == '')
					{
						if (eval('document["cal_'+nombreCampo+'"]'))
						{										
							classCampo = eval('document.getElementById("'+nombreCampo+'").className');
							vClassCampo = classCampo.split(' '); // ej. text tableEdit alerta
							vClassCampo[1] = (vClassCampo[1] != undefined)? vClassCampo[1] : '';
							vClassCampo[2] = (vClassCampo[2] != undefined)? vClassCampo[2] : '';	
							src = eval('document.getElementById("cal_'+nombreCampo+'").src');
							src = src.replace("botones/17off.gif","botones/17.gif");
							eval('document["cal_'+nombreCampo+'"].src = "'+src+'"');					
						}
												
						// bot�n ventana selecci�n
						nombreSinPrefijo = nombreCampo.substr(6,nombreCampo.length);
						if (eval('document["vs_'+nombreSinPrefijo+'"]'))
						{
		  					if (eval('document.getElementById("vs_'+nombreSinPrefijo+'").className'))
		  					{
		  						if (eval('document.getElementById("vs_'+nombreSinPrefijo+'").className') == 'tableEdit')
		  						{
									src = eval('document["vs_'+nombreSinPrefijo+'"].src');
									src = src.replace("botones/13off.gif","botones/13.gif");
									eval('document["vs_'+nombreSinPrefijo+'"].src = "'+src+'"');
		  						}
		  					}
		  					else // Para que siga funcionando como antes de tener el par�metro "editable"
		  					{
		  						src = eval('document["vs_'+nombreSinPrefijo+'"].src');
								src = src.replace("botones/13off.gif","botones/13.gif");
								eval('document["vs_'+nombreSinPrefijo+'"].src = "'+src+'"');
	  						}
						}
	
			  			///////////////////////////////////////////////////
						/// BOT�N DE SALTO
						if (eval('document["jump_'+nombreSinPrefijo+'"]'))
						{
		  					if (eval('document.getElementById("jump_'+nombreSinPrefijo+'").className'))
		  					{
		  						if (eval('document.getElementById("jump_'+nombreSinPrefijo+'").className') == 'tableEdit')
		  						{
									src = eval('document["jump_'+nombreSinPrefijo+'"].src');
									src = src.replace("off.gif",".gif");
									eval('document["jump_'+nombreSinPrefijo+'"].src = "'+src+'"');
		  						}
		  					}
		  					else // Para que siga funcionando como antes de tener el par�metro "editable"
		  					{
								src = eval('document["jump_'+nombreSinPrefijo+'"].src');
								src = src.replace("off.gif",".gif");
								eval('document["jump_'+nombreSinPrefijo+'"].src = "'+src+'"');
		  					}
						}
					}

					if (vClassCampo[1] == "tableEdit")				
					{
						if ((tipoCampo == 'radio') || (tipoCampo == 'checkbox') || (tipoCampo == 'select-one') || (tipoCampo == 'select-multiple') ){
							formulario.elements[i].disabled = false;
						}
						else
							formulario.elements[i].readOnly = false;
						nuevoClass = classCampo.replace(vClassCampo[1],"tableModify");
						nuevoClass = nuevoClass.replace(vClassCampo[2],"");
						formulario.elements[i].className = nuevoClass;						
					}
				}
			}// if
		} // for i
		if (hayFoco)
			eval('campoFoco.focus()');
		
		marcaModificado = document.getElementById(this.idPanel+'_imgModificado');		
		if (marcaModificado != null)
		{
			capaMenuFalso = document.getElementById('capa_menuFalso');
			capaMenuReal = document.getElementById('capa_menuReal');
			capaMenuFalso.style.display="inline";	
			capaMenuReal.style.display="none";
			marcaModificado.style.display="inline";
			ocultoPerCerrarApli=document.getElementById('permitirCerrarAplicacion');
			ocultoPerCerrarApli.value='no';			
		}
		
		if (eval(this.idPanel+'_paginacion'))
		{
			paginador = eval(this.idPanel+'_paginacion');
			//Se marca la p�gina como modificada...
			paginador.paginasModificadas[parseInt(this.getPaginaActiva(),10)] = true;			
		}
		
	}
);

objBTTModificar.method('modificarFicha', function ()
	{
	
		if (this.habilitado ==  false) return;
		
		//Activamos los botones inferiores!!
		this.activarGC(this.idPanel);
	
		formulario = this.getForm();
		var capas = formulario.getElementsByTagName("DIV");
		var pagina = null;
		var soloLectura = true;
		var numCapas = capas.length;
		accionActivaP = 'accionActivaP_'+formulario.id;
	
	/*	if (this.esPanelMD()) // Si es maestro o Detalle
		{
			if (this.idPanel == 'edi') //Soy el panel edi y soy un maestro
				this.deshabilitarHermanos('insertar,eliminar,modificar',',');		
			this.deshabilitarMD('modificar'); //Deshabilito botones del panel complementario
		}*/
		
		for(i=0;i<numCapas;i++) 
		{
			idCapa = String(capas.item(i).getAttribute("id"));
			// id de la capa = pag_NombreTabla_0
			prefijo = idCapa.substr(0, 3);
			capa = eval('document.getElementById("'+idCapa+'")');
			
			if ( (prefijo == 'pag' ) && (eval('capa.style.display') == 'block') )
			{
				pagina = idCapa.substr(4,idCapa.length);
				eval('formulario.est_'+pagina+'.value="modificada"');
			 	eval('formulario.'+accionActivaP+'.value="modificar"');	 	
				//classCampo = "modify";
				soloLectura = false;
				break;
			}//if
		}//for

//A�adido para controlar error JS
		//classCampo = "modify";
//Fin a�adido
		for (i=0;i<formulario.length;i++) {
  			//nombreCampo = formulario.elements[i].name;
  			nombreCampo = formulario.elements[i].id;
	  		campo = nombreCampo.split('___'); 
	  		// ccam (campo oculto del checkbox) lcam (campo oculto de listas disabled)
	  		if ( (campo[0] == 'cam') || (campo[0] == 'ccam') || (campo[0] == 'lcam'))
	  		{
/// Necesario para el caso de q el campo sea una lista de selecci�n m�ltiple
		  		selectMultiple = campo[2].substr(campo[2].length-2,campo[2].length);
		  		if (this.iconoCSS == '')
		  		{
			  		if (selectMultiple == '[]')
			  		{
				  		selectMultiple = campo[2].substr(0,campo[2].length-2);
				  		campo[2] = selectMultiple;
				  		
				  		if (eval('document.getElementById("selCopiar'+campo[1]+"___"+campo[2]+'")'))
						{
							src = eval('document.getElementById("selCopiar'+campo[1]+"___"+campo[2]+'").src');
							src = src.replace("igep/images/pestanyas/pix_trans.gif","igep/images/botones/flechaIzq.gif");
							eval('document.getElementById("selCopiar'+campo[1]+"___"+campo[2]+'").src = "'+src+'"');	
						}
						if (eval('document.getElementById("selModificar'+campo[1]+"___"+campo[2]+'")'))
						{
							src = eval('document.getElementById("selModificar'+campo[1]+"___"+campo[2]+'").src');
							src = src.replace("igep/images/pestanyas/pix_trans.gif","igep/images/botones/flechaDcha.gif");
							eval('document.getElementById("selModificar'+campo[1]+"___"+campo[2]+'").src = "'+src+'"');	
						}
						if (eval('document.getElementById("selLimpiar'+campo[1]+"___"+campo[2]+'")'))
						{
							src = eval('document.getElementById("selLimpiar'+campo[1]+"___"+campo[2]+'").src');
							src = src.replace("igep/images/pestanyas/pix_trans.gif","igep/images/botones/42.gif");
							eval('document.getElementById("selLimpiar'+campo[1]+"___"+campo[2]+'").src = "'+src+'"');	
						}
				  	}
		  		}
				  	
				classCampo = eval('document.getElementById("'+nombreCampo+'").className');
				vClassCampo = classCampo.split(' '); // ej. text tableEdit alerta
				vClassCampo[1] = (vClassCampo[1] != undefined)? vClassCampo[1] : '';
				vClassCampo[2] = (vClassCampo[2] != undefined)? vClassCampo[2] : '';	
		 		
				if (this.iconoCSS == '')
				{
					if (campo[2] == pagina) 
		 			{
						////////////////////////////////	
						/// CALENDARIO
		  				if (eval('document.getElementById("cal_'+nombreCampo+'")'))
						{
							if (vClassCampo[1] != "noEdit")
							{
								src = eval('document.getElementById("cal_'+nombreCampo+'").src');
								src = src.replace("botones/17off.gif","botones/17.gif");
								eval('document.getElementById("cal_'+nombreCampo+'").src = "'+src+'"');					
							}
						}
						///////////////////////////////////////////////////
						/// VENTANA DE SELECCI�N
		  				if (eval('document.getElementById("vs_'+campo[1]+"___"+campo[2]+'")'))
						{
		  					if (eval('document.getElementById("vs_'+campo[1]+"___"+campo[2]+'").className'))
		  					{
		  						if (eval('document.getElementById("vs_'+campo[1]+"___"+campo[2]+'").className') == 'tableEdit')
		  						{
		  							src = eval('document.getElementById("vs_'+campo[1]+"___"+campo[2]+'").src');
		  							src = src.replace("botones/13off.gif","botones/13.gif");
		  							eval('document.getElementById("vs_'+campo[1]+"___"+campo[2]+'").src = "'+src+'"');
		  						}
		  					}
		  					else // Para que siga funcionando como antes de tener el par�metro "editable" 
		  					{
		  						src = eval('document.getElementById("vs_'+campo[1]+"___"+campo[2]+'").src');
		  						src = src.replace("botones/13off.gif","botones/13.gif");
		  						eval('document.getElementById("vs_'+campo[1]+"___"+campo[2]+'").src = "'+src+'"');
		  					}
						}
	
		  				///////////////////////////////////////////////////
						/// BOT�N DE SALTO			
		  				if (eval('document.getElementById("jump_'+campo[1]+"___"+campo[2]+'")'))
						{
		  					if (eval('document.getElementById("jump_'+campo[1]+"___"+campo[2]+'").className'))
		  					{
		  						if (eval('document.getElementById("jump_'+campo[1]+"___"+campo[2]+'").className') == 'tableEdit')
		  						{
									src = eval('document.getElementById("jump_'+campo[1]+"___"+campo[2]+'").src');
									if (src.indexOf("off.gif") != -1)
										src = src.replace("off.gif",".gif");
									eval('document.getElementById("jump_'+campo[1]+"___"+campo[2]+'").src = "'+src+'"');
		  						}
		  					}
		  					else // Para que siga funcionando como antes de tener el par�metro "editable"
	  						{
								src = eval('document.getElementById("jump_'+campo[1]+"___"+campo[2]+'").src');
								if (src.indexOf("off.gif") != -1)
									src = src.replace("off.gif",".gif");
								eval('document.getElementById("jump_'+campo[1]+"___"+campo[2]+'").src = "'+src+'"');	  						
	  						}
						}
	
		  				///////////////////////////////////////////////////
						/// BOT�N DE actualizaCampos
		  				if (eval('document.getElementById("func_'+campo[1]+"___"+campo[2]+'")'))
						{	
		  					if (eval('document.getElementById("func_'+campo[1]+"___"+campo[2]+'").className'))
		  					{
	  					  		if (eval('document.getElementById("func_'+campo[1]+"___"+campo[2]+'").className') == 'tableEdit')
	  							{
	  					  			src = eval('document.getElementById("func_'+campo[1]+"___"+campo[2]+'").src');
									if (src.indexOf("off.gif") != -1) // Est� en off
										src = src.replace("off.gif",".gif"); // Est� en on
									eval('document.getElementById("func_'+campo[1]+"___"+campo[2]+'").src = "'+src+'"');
	  							}
		  					}
		  					else // Para que siga funcionando como antes de tener el par�metro "editable"
	  						{
					  			src = eval('document.getElementById("func_'+campo[1]+"___"+campo[2]+'").src');
								if (src.indexOf("off.gif") != -1) // Est� en off
									src = src.replace("off.gif",".gif"); // Est� en on
								eval('document.getElementById("func_'+campo[1]+"___"+campo[2]+'").src = "'+src+'"');
							}
						}
		 			}
				}

				if (campo[2] == pagina) 
	 			{
					//Caso especial para los File_UpLoad
					if (campo[1].substr(0,3) == 'FUP')
					{
						campoUp = document.getElementById(nombreCampo);
						campoUp.type = 'file';
					}
					//////////////////////////////////////////
					
		  			if ( (vClassCampo[1] == "edit") || (vClassCampo[1] == "enlace") )
		  			{  // Activamos los campos para que sean modificables
			  			nuevoClass = classCampo.replace(vClassCampo[1],"modify");
		  				eval('formulario.elements[i].className = "'+nuevoClass+'"');
		  				eval('formulario.elements[i].readOnly = '+soloLectura);
		  				if ( (formulario.elements[i].type == "checkbox") || (formulario.elements[i].type == "radio") || (formulario.elements[i].type == "select-one") || (formulario.elements[i].type == "select-multiple") )
		  				{
			  				eval('formulario.elements[i].disabled = false');
		  				}
		  			}
	  		 	}
	  		}//if
	  	} //for
	  	marcaModificado = document.getElementById(this.idPanel+'_imgModificado');		
		if (marcaModificado != null)
		{
			marcaModificado.style.display="inline";
			capaMenuFalso = document.getElementById('capa_menuFalso');
			capaMenuReal = document.getElementById('capa_menuReal');
			capaMenuFalso.style.display="inline";	
			capaMenuReal.style.display="none";
			ocultoPerCerrarApli=document.getElementById('permitirCerrarAplicacion');
			ocultoPerCerrarApli.value='no';
		}
		
		if (eval(this.idPanel+'_paginacion'))
		{
			paginador = eval(this.idPanel+'_paginacion');
			//Se marca la p�gina como modificada...
			paginador.paginasModificadas[parseInt(this.getPaginaActiva(),10)] = true;			
		}
	}
);

objBTTModificar.method('modificarLista', function (destino, separador)
	{
		campoDestino = eval('document.getElementById("'+destino+'")');
		seleccionado = eval('campoDestino.options[campoDestino.options.selectedIndex].value');
		vSeleccionado = eval('seleccionado.split("'+separador+'")');
		for (i=0;i<vSeleccionado.length;i++) 
		{
			campoOrigen = eval('document.getElementById("'+arguments[i+2]+'")');
			campoOrigen.value = vSeleccionado[i];
		}
    	if (campoDestino.options.length > 0)
			campoDestino.options[campoDestino.options.selectedIndex] = null;
	}
);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////// 	CLASE PARA EL bot�n ELIMINAR.- objBTTEliminar						
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function objBTTEliminar(nomObjeto,idPanel,esMaestro,esDetalle,iconoCSS)
{
	//llamamos al constructor del padre
	this.uber('constructor',nomObjeto,idPanel,esMaestro,esDetalle);
	this.nomClase = 'objBTTEliminar';
	this.iconoCSS = iconoCSS;
}

//DECLARACION DE HERENCIA
objBTTEliminar.inherits(objBotonToolTip);

objBTTEliminar.method('eliminarTabla', function ()
	{
		var idFila = "";
		var hayFoco = 0;
		
		//Activamos los botones inferiores!!
		this.activarGC(this.idPanel);
		
		formulario = this.getForm();
		nomForm = this.getFormId();
		// Tenemos un campo oculto q contendr� la acci�n realizada con los botones ToolTip
		accionActivaP = 'accionActivaP_'+nomForm;
		
		if (this.esPanelMD()) // Si es maestro o Detalle
		{			
			/*this.deshabilitarMD('eliminar'); //Deshabilito botones del panel complementario
			if (this.idPanel == 'lis') //Soy el panel maestro
				this.deshabilitarHermanos('insertar,eliminar,modificar',',');*/

			// Si en el detalle hay solapas (varios detalles) ocultamos las solapas
			// si estamos trabajando sobre uno de ellos
			if (document.getElementById("detalles"))
				document.getElementById("detalles").style.display = 'none';
		}
		
		for(i=0;i<formulario.length;i++) 
		{
			/// BUSCAMOS LA FILA SELECCIONADA PARA CAMBIARLA DE COLOR Y ESTADO
			// check_idFila -> idFila = "F_".$idTabla."_".$iterActual;
			valorId = String(formulario.elements[i].id);
			prefijo = valorId.split('_')[0];
			tipoCampo = formulario.elements[i].type;
			nombreCampo = formulario.elements[i].id;
			
			// PARA EL CASO DE UNA TABLA PQ TIENE UN CHECK Q LA IDENTIFICA
			if ( (prefijo== 'check') && 
				 (tipoCampo == 'checkbox') && 
				 (formulario.elements[i].checked) && 
				 (nombreCampo != 'seleccionarTodo') )
			{
				// Las filas chequeadas se marcar�n como modificadas
				idFila = valorId.split('check_')[1];
				fila = eval('document.getElementById("'+idFila+'")');
				// Campo oculto con el estado d la fila q miraremos que no sea un modificar
				estado = eval('formulario.est_'+idFila+'.value');
				if (estado == "nada")
				{
	 				eval('formulario.est_'+idFila+'.value="borrada"');
					//Se deschequea y y se desactiva el campo checked							
					formulario.elements[i].checked = false;
					formulario.elements[i].disabled = true;
					// Estilo de la fila
					classFila = eval('document.getElementById("'+idFila+'").className');
					classFila = classFila.replace("rowOn","rowDeleted");
					fila.className = classFila;
					// Oculto con la acci�n
			 		eval('formulario.'+accionActivaP+'.value="borrar"');
			 	}
			} // if checkbox
			// REVIEW Vero 02/03/2009 Para buscar el estado del radio hay q utilizar el name y no el id
			if (tipoCampo == 'radio')
				nombreCampo = formulario.elements[i].name;
			
			idFilaCampo = nombreCampo.split('___')[2];
			// Para el resto de campos que pueden estar dentro d una tabla
			estado = '';
			if (idFilaCampo)
				estado = eval('formulario.est_'+idFilaCampo+'.value');
	
			if ( ( (tipoCampo != 'hidden') && (estado == 'borrada') && (prefijo != 'check') && (nombreCampo != 'seleccionarTodo') &&
				((tipoCampo == 'textarea') || (tipoCampo == 'text') ||	(tipoCampo == 'checkbox') || (tipoCampo == 'radio') || (tipoCampo == 'select-one')) ) &&
				((idFilaCampo == idFila) && (valorId.split('___')[0] != 'ant')) )
			{
				classCampo = eval('document.getElementById("'+valorId+'").className');
				// Primero quitamos el color de fila activa
				classCampo = classCampo.replace("rowOn","rowDeleted");
				vClassCampo = classCampo.split(' '); // ej. text alerta rowOn
				vClassCampo[1] = (vClassCampo[1] != undefined)? vClassCampo[1] : '';
				vClassCampo[2] = (vClassCampo[2] != undefined)? vClassCampo[2] : '';
				if (vClassCampo[2] != '')			
					nuevoClass = classCampo.replace(vClassCampo[2],"tableDelete");
				else
					nuevoClass = classCampo.replace(vClassCampo[1],"tableDelete");
				formulario.elements[i].className = nuevoClass;
				if (tipoCampo == 'radio')
				{
					idLabelRadio = 'l'+formulario.elements[i].id;
					labelRadio = document.getElementById(idLabelRadio);
					if (labelRadio) labelRadio.className = formulario.elements[i].className;
				}				
			}// if
		} // for i
		marcaModificado = document.getElementById(this.idPanel+'_imgModificado');		
		if (marcaModificado != null)
		{
			marcaModificado.style.display="inline";
			capaMenuFalso = document.getElementById('capa_menuFalso');
			capaMenuReal = document.getElementById('capa_menuReal');
			capaMenuFalso.style.display="inline";	
			capaMenuReal.style.display="none";
			ocultoPerCerrarApli=document.getElementById('permitirCerrarAplicacion');
			ocultoPerCerrarApli.value='no';			
		}
		
		if (eval(this.idPanel+'_paginacion'))
		{
			paginador = eval(this.idPanel+'_paginacion');
			//Se marca la p�gina como modificada...
			paginador.paginasModificadas[parseInt(this.getPaginaActiva(),10)] = true;			
		}
	}
);

objBTTEliminar.method('eliminarFicha', function ()
	{		
		if (this.habilitado ==  false) return;
		
		//Activamos los botones inferiores!!
		this.activarGC(this.idPanel);
		
		formulario = this.getForm();
		var capas = formulario.getElementsByTagName("DIV");
		var pagina = null;
		var soloLectura = true;
		var numCapas = capas.length;
		accionActivaP = 'accionActivaP_'+formulario.id;
		
		if (this.esPanelMD()) // Si es maestro o Detalle
		{
			// Deshabilitar mis hermanos
			/*if (this.idPanel == 'edi') //Soy el panel edi y soy un maestro
				this.deshabilitarHermanos('insertar,eliminar,modificar',',');		
			this.deshabilitarMD('eliminar'); //Deshabilito botones del palnel complementario*/
		}
			
		for(i=0;i<numCapas;i++) 
		{
			idCapa = String(capas.item(i).getAttribute("id"));
			// id de la capa = pag_NombreTabla_0
			prefijo = idCapa.substr(0, 3);
			capa = eval('document.getElementById("'+idCapa+'")');
			
			if ( (prefijo == 'pag' ) && (eval('capa.style.display') == 'block') )
			{
				pagina = idCapa.substr(4,idCapa.length);
				eval('formulario.est_'+pagina+'.value="borrada"');
			 	eval('formulario.'+accionActivaP+'.value="borrar"');
				break;
			}//if
		}//for
		
		if (this.iconoCSS == '')
		{
			img = document.getElementsByTagName("img");
			for (i=0;i<img.length;i++) 
			{
				// Ventana seleccion
				src = img[i].src.split('/');
				long = src.length;
				imagen = src[long-1];
				if (imagen == '13.gif')
				{
					id = img[i].id;
					imgOff = img[i].src.replace(imagen,"13off.gif");
					eval('document.getElementById("'+id+'").src = "'+imgOff+'";'); 
				}
				// Calendario
				if (imagen == '17.gif') {
					id = img[i].id;
					imgOff = img[i].src.replace(imagen,"17off.gif");
					eval('document.getElementById("'+id+'").src = "'+imgOff+'";');
				}
			}
		}
		
		for (i=0;i<formulario.length;i++) 
		{
  			//nombreCampo = formulario.elements[i].name;
  			nombreCampo = formulario.elements[i].id;
	  		campo = nombreCampo.split('___'); 
  			if ( (campo[0] == 'cam') && (campo[2] == pagina) && (formulario.elements[i].type != 'hidden')  )
  			{
				classCampo = eval('document.getElementById("'+nombreCampo+'").className');
				vClassCampo = classCampo.split(' '); // ej. text alerta rowOn
				vClassCampo[1] = (vClassCampo[1] != undefined)? vClassCampo[1] : '';
				vClassCampo[2] = (vClassCampo[2] != undefined)? vClassCampo[2] : '';
				nuevoClass = classCampo.replace(vClassCampo[1],"delete");
				eval('formulario.elements[i].className = "'+nuevoClass+'"');
				eval('formulario.elements[i].readOnly = true');
	  		}//if
	  } //for
	}
);


//////////////////////////////////////////////////////
// Funci�n para el objeto CWSelector
//////////////////////////////////////////////////////
objBTTEliminar.method('eliminarLista', function (destino, separador)
	{
		campoDestino = eval('document.getElementById("'+destino+'")');
    	if (campoDestino.options.length > 0)
			campoDestino.options[campoDestino.options.selectedIndex] = null;
		for(i=0;i<campoDestino.options.length;i++)
			campoDestino.options[i].selected = true;
		
		//Marcamos la modificacion en el panel (ficha/fila)
   		cadenaAux = 'document.'+this.idPanel+'_comp';
		objComprobacion = eval(cadenaAux);//Instanciar el objeto
		objComprobacion.marcarModificacionCampo(destino);
	}
);


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////// 	CLASE PARA EL bot�n EXPANDIR del arbol.- objBTTArbol
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function objBTTArbol(nomObjeto,idPanel,esMaestro,esDetalle)
{
	//llamamos al constructor del padre
	this.uber('constructor',nomObjeto,idPanel,esMaestro,esDetalle);
	this.nomClase = 'objBTTArbol';
}

//DECLARACION DE HERENCIA
objBTTArbol.inherits(objBotonToolTip);

objBTTArbol.method('accionarPanel', function (anchoArbol)
	{	
		celdaArbol = document.getElementById('celdaArbol');
		celdaPanel = document.getElementById('celdaPanel');
		arbolCab = document.getElementById('divArbolCab');
		arbol = document.getElementById('divArbol');
		arbolNo = document.getElementById('divArbolOculto');
		if (celdaArbol.style.width == "1%")
		{
			celdaArbol.style.width=anchoArbol+"%";
			anchoPanel = 100 - anchoArbol;
			celdaPanel.style.width=anchoPanel+"%";
			eval('arbol.style.display="block"');
			eval('arbolCab.style.display="block"');
			eval('arbolNo.style.display="none"');
		}
		else 
		{
			celdaArbol.style.width="1%";
			celdaPanel.style.width="99%";
			eval('arbol.style.display="none"');
			eval('arbolCab.style.display="none"');
			eval('arbolNo.style.display="block"');
		}
	}
);