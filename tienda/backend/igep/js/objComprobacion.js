function oComprobacion(idPanel, nomObjeto)
{
	/**
    * Cadena con el nombre del objeto
    * @access	private
    * @var string nomObjeto
    */		
	this.nomObjeto = nomObjeto;
	
	/**
    * Cadena con el texto del Mensaje de error
    * @access	private
    * @var string camposErroneos
    */
    this.camposErroneos = "";
     
    /**
    * Identificador del Panel donde se encuentra el objeto
    * Esta cadena esta relacionada con el nombre de otros
    * objetos de ese panel, así como con el formulario
    * @access	private
    * @var string idPanel
    */
	this.idPanel = idPanel;
	this.formulario = "F_"+idPanel;

	/**
	* Vector de campos 
    * Esta cadena esta relacionada con el nombre de otros
    * objetos de ese panel, así como con el formulario
    * @access	private
    * @var string idPanel
    */
	this.vCamposComprobar = new Array();

	/**
	* Instancia del objeto Paginador referenciará al
	* paginador de ese panel simpre que no sea fil
	* @access	private
	* @var oPaginacion objPaginador
    */
	this.objPaginador = null;
	/*
	if ( (this.idPanel!=null) && ((this.idPanel!='fil')) )
	{
		this.objPaginador = eval(this.idPanel+'_paginacion');
	}
	*/
	

	this.error = false;

	//Métodos de la Clase
	//Declaracion de las cabeceras	
	this.esVacio = f_oComprobacion_esVacio;
	this.soloUnCheck = f_oComprobacion_soloUnCheck;
	this.establecerBooleano = f_oComprobacion_establecerBooleano;
	this.actualizarElemento = f_oComprobacion_actualizarElemento;
	this.comprobarMaximo = f_oComprobacion_comprobarMaximo;
	this.comprobarMinimo = f_oComprobacion_comprobarMinimo;
	this.informaAvisoJS = f_oComprobacion_informaAvisoJS;
	this.getCamposErroneos =f_oComprobacion_getCamposErroneos;
	this.getPagActiva =f_oComprobacion_getPagActiva;
	this.addCampo = f_oComprobacion_addCampo;
	this.getAccion = f_oComprobacion_getAccion;
	this.obtenerEtiquetaCampo = f_oComprobacion_obtenerEtiquetaCampo;
	this.comprobarObligatorios = f_oComprobacion_comprobarObligatorios;
	this.comprobarModificacion = f_oComprobacion_comprobarModificacion;
	this.marcarModificacionCampo = f_oComprobacion_marcarModificacionCampo;
	this.bloquearSalida = f_oComprobacion_bloquearSalida;
	this.formateaNumero = f_oComprobacion_formateaNumero;
	this.formatoFecha = f_oComprobacion_formatoFecha;
	this.mostrarDiaSemanaJS = f_oComprobacion_mostrarDiaSemanaJS;	
	this.calcularSemanaJS = f_oComprobacion_calcularSemanaJS;
	this.calcularDiaAnyoJS = f_oComprobacion_calcularDiaAnyoJS;
	this.mostrarInfoFechaJS = f_oComprobacion_mostrarInfoFechaJS;
}

/* Elimina caracteres blancos por delante y por detrás de la cadena */
function trimRight( str ) 
{
	var resultStr = "";
	var i = 0;
	
	if (str+"" == "undefined" || str == null)
	return null;
	
	str += "";
	if (str.length == 0)
		resultStr = "";
	else {
		i = str.length - 1;
		while ((i >= 0) && (str.charAt(i) == " "))
		i--;
		resultStr = str.substring(0, i + 1);
	}
	
	return resultStr;
}

function trimLeft( str ) {
	var resultStr = "";
	var i = len = 0;
	
	if (str+"" == "undefined" || str == null)
		return null;	
	str += "";
	if (str.length == 0)
		resultStr = "";
	else {
		len = str.length;
		while ((i <= len) && (str.charAt(i) == " "))
			i++;
		resultStr = str.substring(i, len);
	}
	return resultStr;
}

function trim( str ) {
	var resultStr = "";
	
	resultStr = trimLeft(str);
	resultStr = trimRight(resultStr);
	
	return resultStr;
}

String.prototype.trim = function()
{
return this.replace(/^\s*(\b.*\b|)\s*$/, "$1");
}


/**
* Comprueba si el elemento HTML es vacío
* @access	private
* @var tipoCampo text|textarea|select-one
* @var campo id o name del campo
*/
function f_oComprobacion_esVacio(tipoCampo, campo)
{
	switch(tipoCampo)
 	{
 		case 'text':
 		case 'textarea':
 			if (trim(campo.value) == '') return true;
 			break;
 		case 'select-one':
 		case 'select-multiple': 			
 			if (campo.options.selectedIndex == -1)
 				return true;
			if (trim(campo.options[campo.options.selectedIndex].value) == '') return true;
 		break;
 	}
 	return false;
}

// Comprobar que solamente se ha seleccionado un checkbox
function f_oComprobacion_soloUnCheck() 
{
	formulario = eval('document.getElementById("'+this.formulario+'")');
	contador = 0;
	checkSeleccionado = '';
	for(i=0;i<formulario.length;i++)
	{
		if ((formulario.elements[i].type == "checkbox") & (formulario.elements[i].checked)) 
		{
			contador++;
			if (contador >1) 
			{
				checkSeleccionado = '';
				break;
			}
			checkSeleccionado = formulario.elements[i].id;
		}
	}
	return checkSeleccionado;
}

//funcion que copia el valor correspondiente al estado del check box
function f_oComprobacion_establecerBooleano(campoCheck,nomCampoOculto,valorSi, valorNo) 
{
	nomForm = "F_"+this.idPanel;
	formulario = eval('document.forms["'+nomForm+'"]');
	if (campoCheck.checked == true) {
		eval('formulario.'+nomCampoOculto+'.value=valorSi');
	}
	else{
		eval('formulario.'+nomCampoOculto+'.value=valorNo');
	}
}

//function f_oComprobacion_actualizarElemento(tipoDestino, objElemento, destino)
function f_oComprobacion_actualizarElemento(objElemento, destino)
{
	if (this.error) return;
	formulario = eval('document.getElementById("'+this.formulario+'")');
	objElemento = eval(objElemento);
	tipo = objElemento.type;
	origen = objElemento.id;
	switch (tipo) {
		case 'select-multiple':
			valor = objElemento.options[objElemento.selectedIndex].value;
			origen = objElemento.name;
		break;
		case 'select-one':
			valor = objElemento.options[objElemento.selectedIndex].value;
		break;
		case 'text':
		case 'textarea':
		case 'hidden':
			valor = objElemento.value;
		break;
		case 'radio':
			valor = objElemento.value;
			origen = objElemento.name;
		break;
		default:
			valor = objElemento.value;
		break;
	}
	claseManejadora = formulario.claseManejadora.value;
	accionAntigua = formulario.action;
	formulario.action = 'phrame.php?action=gvHrefreshUI&gvHclass='+claseManejadora+'&gvHfname='+this.formulario+'&gvHfrom='+origen+'&gvHvalue='+valor+'&gvHtarget='+destino;
	formulario.target = 'oculto';
	formulario.submit();
	formulario.action = accionAntigua;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
*Comprueba que la longitud del campo es inferior a la longitud máxima
Devuelve true/false
*/
function f_oComprobacion_comprobarMaximo(tipoCampo, campo, maxLongitud)
 {
 	switch(tipoCampo)
 	 {
 		case 'textarea':
 			if (campo.value.length > maxLongitud) campo.value = campo.value.slice(0, maxLongitud);
 		break;
 		case 'text':
			return(campo.value.length>maxLongitud);
 		break;
 	};
 }
  
/*
Comprueba que la longitud del campo es superior a la longitud mínima
Devuelve true/false
*/
function f_oComprobacion_comprobarMinimo(tipoCampo, campo, minLongitud)
{
	return (campo.value.length < minLongitud);
}


function f_oComprobacion_informaAvisoJS(mensaje, objetoGenerador) 
{
	var descBreve='';
	var textoAviso='';
	var codAviso='IGEP-';
	var tipoAviso='';
	switch (mensaje)
	{
		case 'LONGITUDMAXIMA':
			codAviso = 'IGEP-902';
			tipoAviso='ALERTA';
			descBreve='Se sobrepasa la longitud <br/> m&aacute;xima permitida.';
			textoAviso='No puede usted introducir m&aacute;s de '+arguments[2]+' caracteres.';
			this.error=this.comprobarMaximo (objetoGenerador.type, objetoGenerador, arguments[2]);			
		break;
		case 'LONGITUDMINIMA':
			codAviso = 'IGEP-903';
			tipoAviso='ALERTA';
			descBreve='Longitud mínima no alcanzada.';
			textoAviso='Debe usted introducir al menos '+arguments[2]+' caracteres.';
			this.error=this.comprobarMinimo (objetoGenerador.type, objetoGenerador, arguments[2]);
		break;
		case 'FECHAINICIO':
			codAviso = 'IGEP-904';
			tipoAviso='ALERTA';
			descBreve='Fecha inferior al límite.';
			textoAviso='La fecha debe ser posterior a: '+arguments[2]+'.';
			this.error=comprobarLimiteFechas (objetoGenerador.value, arguments[2]);
		break;
		case 'FECHAFIN':
			codAviso = 'IGEP-905';
			tipoAviso='ALERTA';
			descBreve='Fecha superior al límite';
			textoAviso='La fecha debe ser inferior a: '+arguments[2]+'.';
			this.error=comprobarLimiteFechas (arguments[2], objetoGenerador.value);
		break;
		case 'RANGOFECHAS':
			codAviso = 'IGEP-906';
			tipoAviso='ALERTA';
			descBreve='Fecha fuera de rango';
			textoAviso='La fecha debe situarse entre: '+arguments[2]+' y '+arguments[3]+'.';
			error1 = comprobarLimiteFechas (arguments[2], objetoGenerador.value);
			error2 = comprobarLimiteFechas (objetoGenerador.value, arguments[3]);			
			if (error1 || error2)
			{
				this.error = true;
			}
		break;
		case 'ESVACIO':
			codAviso = 'IGEP-907';
			tipoAviso='ALERTA';
			descBreve='Campo obligatorio';
			textoAviso='El campo no puede ser vac&iacute;o.';		
			this.error=this.esVacio (objetoGenerador.type, objetoGenerador);

		break;
		case 'MASCARA':
			//Método en desuso?
			codAviso = 'IGEP-908';
			//Si hay máscara y es de fecha (nn/nn/nnnn o nn/nn/nn) comprobamos 
			//Si la fecha existe
			if ( (arguments[2]=='nn/nn/nnnn') || (arguments[2]=='nn/nn/nn' ) )
			{				
				tipoAviso='ALERTA';
				descBreve='Fecha Errónea';
				textoAviso='La fecha introducida ('+objetoGenerador.value+') no es una fecha válida.'; 
				this.error=comprobarFecha(objetoGenerador.value);
			};
			
		break;
		case 'FORMATO_NUMERICO':
			//Informa sobre errores en la introducción de números
			codAviso = 'IGEP-909';
			tipoAviso='ERROR';
			descBreve='Campos numéricos';
			textoAviso='El campo valor que ha intoducido en el campo no es un número válido.';				
		break;
		case 'FORMATO_FECHA':
			//Informa sobre errores en la introducción de números
			codAviso = 'IGEP-910';
			tipoAviso='ERROR';
			descBreve='Formato fecha';
			textoAviso='El campo de fecha no tiene el formato correcto (dd/mm/aaaa o dd-mm-aaaa).';				
		break;
		case 'REGEXP':
			//Valida la expresion regular
			this.error=false;
			if (objetoGenerador.value!='') {
				if (!arguments[2].test(objetoGenerador.value)) {
					codAviso = 'IGEP-911';
					tipoAviso='ERROR';
					descBreve='Error de formato<br/>';
					textoAviso='El campo no cumple con el formato de entrada propuesto.';
					this.error=true;
				}
			}			
		break;
		default:
			codAviso = 'IGEP-900';
			tipoAviso='ERROR';
			descBreve='Error indefinido';
			textoAviso='Hay un error de E/S en la IU sin definir.';
			this.error=true;
		break;		
	};

	if(this.error)
	{		
		//this.error=false;
		aviso=eval('aviso');
		aviso.set('aviso','capaAviso',tipoAviso, codAviso, descBreve, textoAviso);
		aviso.mostrarAviso(objetoGenerador);
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*
FUNCION: getCamposErroneos();
*/ 
function f_oComprobacion_getCamposErroneos()
{	
	return(this.camposErroneos);
}


/*
-----------------------------------
*/
function f_oComprobacion_getPagActiva()
{

	if ( (this.objPaginador==null) || (this.objPaginador===null) )
	{		
		this.objPaginador = eval(this.idPanel+'_paginacion');
	}
	
	if ( (this.objPaginador==null) || (this.objPaginador===null) )
	{	
		alert('ObjComprobación: No existe un paginador, necesitas definirlo');
		return(-1);		
	}
	else 
	{
		return(this.objPaginador.getPaginaActiva());		
	}	
}


/*
-----------------------------------
*/

function f_oComprobacion_addCampo(campo)
{
	//Añadimos al vector el campo a comprobar
	this.vCamposComprobar.push(campo);
}//Fin método faddCampo


function f_oComprobacion_getAccion() 
{	
	btnToolTip = null;
	accionActiva = null;
	if ( eval('document.getElementById("accionActivaP_F_'+this.idPanel+'")') )
	{
		btnToolTip = eval('document.getElementById("accionActivaP_F_'+this.idPanel+'")'); // Cdo es un cambio d capa		
		if ((btnToolTip != null) && (btnToolTip.value != ""))
			return(btnToolTip.value);
	}
	if ( eval('document.getElementById("accionActiva")') )
	{
		accionActiva = eval('document.getElementById("accionActiva")'); // Para cdo viene d Phrame
		if ((accionActiva != null) && (accionActiva.value != ''))
			return(accionActiva.value);
	}

	return('buscar');
}

/*
PRIVADA
Devuelve el texto asociado a un campo
*/
function f_oComprobacion_obtenerEtiquetaCampo(nomCampo) 
{
	if ( eval('document.getElementById("txt'+this.idPanel+'_'+nomCampo+'")') )
	{
		txtLabel = eval('document.getElementById("txt'+this.idPanel+'_'+nomCampo+'")');
		txtLabel = txtLabel.innerHTML;
	}
	else
	{
		txtLabel = nomCampo;
	}
	return (txtLabel);
}



function f_oComprobacion_comprobarObligatorios() 
{
	switch (this.getAccion())
	{
		case 'modificar':
			prefijo ="cam___";
		break;
		case 'insertar':
			prefijo ="ins___";
		break;
		default:
			prefijo ="";
		break;
	}
	
	this.camposErroneos="";
	numCampos = this.vCamposComprobar.length;
	vCamposErroneos = new Array();
	switch (this.idPanel)
	{
		case 'fil':
		case 'filDetalle':
		case 'filMaestro':
			for (i=0;i<numCampos;i++) 
			{
				campo = eval('document.getElementById("'+this.vCamposComprobar[i]+'")');
				
				if (this.esVacio(campo.type,campo)) 
				{					
					vCamposErroneos.push(campo.id);
					this.camposErroneos += "<br/> * "+this.obtenerEtiquetaCampo(this.vCamposComprobar[i]);
				}
			}
		break;
		case 'edi':
		case 'ediDetalle':
		case 'ediMaestro':
			for (i=0;i<numCampos;i++) 
			{
				longNombre = this.vCamposComprobar[i].length;
				nomCampo = this.vCamposComprobar[i].substr(0,longNombre-1);
				nomCampo = nomCampo+this.getPagActiva();
				nomCampo = nomCampo.replace('cam___', prefijo);				
				if (eval('document.getElementById("'+nomCampo+'")')) 
				{
					campo = eval('document.getElementById("'+nomCampo+'")');
					if (this.esVacio(campo.type,campo)) 
					{
						vCampo = nomCampo.split('___');
						if (vCampo.length > 1) 
						{
							nomCampo = vCampo[1];
						};
						vCamposErroneos.push(nomCampo);
						this.camposErroneos += "<br/> * "+this.obtenerEtiquetaCampo(this.vCamposComprobar[i]);
					}
				}
			}
		break;
		
		case 'lis':
		case 'lisDetalle':
		case 'lisMaestro':
			objTabla = eval(this.idPanel+'_tabla');
			numFilas = objTabla.vFilasComprobar.length;
			// nomCampo = cam___nomCampo___panel_fila
			for (i=0;i<numCampos;i++)  // Recorrer los campos a comprobar 
			{
					// Cambio de prefijo para el estado en el q estamos				
					nomCampo = this.vCamposComprobar[i];
					if (prefijo != '')  nomCampo = nomCampo.replace('cam___',prefijo);
 					v_nomCampo = nomCampo.split('___'); 
 					
					if (v_nomCampo.length == 1) // Es un campo external
					{
						campo = eval('document.getElementById("'+nomCampo+'")');
						if (this.esVacio(campo.type,campo)) 
						{
							campoComprobar = nomCampo;
						   	yaExiste=false;
							for(j=0;j<vCamposErroneos.length;j++)
						    {
						    	if (vCamposErroneos[j]==campoComprobar)
						    	{
						    		yaExiste=true;
						    		break;
						    	}	    	
						    }
							if (!yaExiste)
							{
								vCamposErroneos.push(campoComprobar);
								this.camposErroneos += "<br/> * "+this.obtenerEtiquetaCampo(campoComprobar);
							}
						}
					}
					else
					{
					
	 					// Tenemos q cambiar la fila por el número d fila del campo a comprobar
	 					numFila = v_nomCampo[2].split('_')[1];
	 					for (fila=0;fila<numFilas;fila++)
	 					{
	 						cadFila = "_"+numFila;
	 						nuevaFila = "_"+objTabla.vFilasComprobar[fila];
	 						campoComprobar = nomCampo.replace(cadFila,nuevaFila);
	 						if (eval('document.getElementById("'+campoComprobar+'")')) 
							{
								campo = eval('document.getElementById("'+campoComprobar+'")');
								if (this.esVacio(campo.type,campo)) 
								{
									vCampo = campoComprobar.split('___');
									if (vCampo.length > 1) 
									{
										campoComprobar = vCampo[1];
									}
									
								   	yaExiste=false;
		    						for(j=0;j<vCamposErroneos.length;j++)
								    {
								    	if (vCamposErroneos[j]==campoComprobar)
								    	{
								    		yaExiste=true;
								    		break;
								    	}	    	
								    }
	 								if (!yaExiste)
									{
										vCamposErroneos.push(campoComprobar);
										this.camposErroneos += "<br/> * "+this.obtenerEtiquetaCampo(campoComprobar);
									}
								} //if
							}//if
	 					}//for
					}//else
				}//for
		break;
	}
	if (this.camposErroneos != '')
	{
		return false;
	}
	return true;
}

function f_oComprobacion_bloquearSalida(estado)
{
	str_imagen = this.idPanel+'_imgModificado';

	// Existe menú en la ventana 
	if (eval('document.getElementById("capa_menuReal")'))
	{
		if (estado == true)
		{
			document.getElementById(str_imagen).style.display='inline';
			document.getElementById('capa_menuFalso').style.display='inline';
			document.getElementById('capa_menuReal').style.display='none';
			document.getElementById('permitirCerrarAplicacion').value='no';
		}
		else 
		{
			document.getElementById(str_imagen).style.display='none';
			document.getElementById('capa_menuFalso').style.display='none';
			document.getElementById('capa_menuReal').style.display='inline';
			document.getElementById('permitirCerrarAplicacion').value='si';
		}
	}
}

function f_oComprobacion_comprobarModificacion(idCampo) 
{
	//Si no soy un panel de edición fin	(y las tablas?)
	if ((this.idPanel !='edi') && (this.idPanel !='ediDetalle')) return;
	
	var arrayAux = String(idCampo).split('___');
	idCampoEstado = 'est_'+arrayAux[2];
	campoEstado = eval('document.getElementById("'+idCampoEstado+'")');
	
	// 08/02/2010 Vero: Para comprobar si ha habido modificación en alguno de los radio
	if (arguments[1])
	{
		numRadios = arguments[1];
		nomCampo = arrayAux[1];
		idRadioAnt = 'ant___'+nomCampo+'___'+arrayAux[2];		
		radioAnt = eval('document.getElementById("'+idRadioAnt+'")');	
		for (i=0;i<numRadios;i++)
		{
			idRadio = arrayAux[0]+'___'+nomCampo+''+i+'___'+arrayAux[2];	
			//idRadioAnt = String(idRadio).replace('cam','ant');
			radio = eval('document.getElementById("'+idRadio+'")');			
			//Si alguno de los campos no Existe...
			if ((radio == null) || (radioAnt == null)) return;
			if (radio.value != radioAnt.value) 
			{	
		 		if ( (campoEstado.value=='nada') || (campoEstado.value==''))
		 		{
		 			campoEstado.value='modificada';
		 			//Marcamos la página como modificada 
		 			if ( (this.objPaginador==null) || (this.objPaginador===null) )
					{
						this.objPaginador = eval(this.idPanel+'_paginacion');
					}
		 			this.objPaginador.paginasModificadas[this.objPaginador.getPaginaActiva()] = true;
		 		}
		 		this.bloquearSalida(true);
		 		break;
			}
		}
	}
	else
	{
		idCampoAnt = String(idCampo).replace('cam','ant');
		campo = eval('document.getElementById("'+idCampo+'")');
		campoAnt = eval('document.getElementById("'+idCampoAnt+'")');	
	
		//Si alguno de los campos no Existe...
		if ( 
			(campo == null) || 
			(campoAnt == null)		
		) return;
	
		if (campo.value != campoAnt.value) 
		{	
	 		if ( (campoEstado.value=='nada') || (campoEstado.value==''))
	 		{
	 			campoEstado.value='modificada';
	 			//Marcamos la página como modificada 
	 			if ( (this.objPaginador==null) || (this.objPaginador===null) )
				{
					this.objPaginador = eval(this.idPanel+'_paginacion');
				}
	 			this.objPaginador.paginasModificadas[this.objPaginador.getPaginaActiva()] = true;
	 		}
	 		this.bloquearSalida(true);
		}
	}
}


//Marca el campo estado asociado a la ficha/fila como modificado
function f_oComprobacion_marcarModificacionCampo(idCampo)
{
	//DEBUG: Darle un repasito, teoría de Toni
	// Si no soy un panel de edición fin    (y las tablas?)
	if ((this.idPanel !='edi') && (this.idPanel !='ediDetalle')) return;

	var arrayAux1 = String(idCampo).split('___');
	//Si es una lista múltiple sobrarán también los corchetes "[]"
	var arrayAux2 = String(arrayAux1[2]).split('[');
	idCampoEstado = 'est_'+arrayAux2[0];
	campoEstado = eval('document.getElementById("'+idCampoEstado+'")');
	if ((campoEstado.value=='nada') || (campoEstado.value==''))
	{
		campoEstado.value='modificada';
		this.bloquearSalida(true);
	}
}

///////////////////////////////////////////////////////////////////////////////////////
/// INFORMACIÓN ADICIONAL A LA FECHA
/// Semana del año, día de la semana, día del año
///////////////////////////////////////////////////////////////////////////////////////
function f_oComprobacion_formatoFecha(fecha)
{
	if (fecha != '')
	vFecha = fecha.split('/');	
	if ((vFecha.length == 1) && (fecha != ''))
	{
		vFecha = fecha.split('-');				
		if (vFecha.length == 1)
			this.informaAvisoJS('FORMATO_FECHA', this);
	}
	return(vFecha);
}

function f_oComprobacion_calcularDiaAnyoJS(fecha) 
{
    vFecha = this.formatoFecha(fecha);
    // Se empieza a contar desde 0 tanto para el mes como el día
	var now = new Date(vFecha[2], vFecha[1]-1, vFecha[0]);
	var then = new Date(vFecha[2], 0, 0);	
	var dif = now.getTime() - then.getTime();
	dia = Math.floor(dif/(1000*60*60*24))+1; 
    return(dia);
}

function f_oComprobacion_calcularSemanaJS(fecha) 
{
    vFecha = this.formatoFecha(fecha);
    // Se empieza a contar desde 0 tanto para el mes como el día
	var now = new Date(vFecha[2], (vFecha[1]-1), vFecha[0], 0, 0, 0);
	var then = new Date(vFecha[2], 0, 1, 0, 0, 0);
	var time = now - then;
	var day = then.getDay();  // Devuelve el número d día de la semana
	(day > 3) && (day -= 4) || (day += 3);
    semana = Math.round(((time / Date.DAY) + day) / 7);
	return(semana);
}

function f_oComprobacion_mostrarDiaSemanaJS(fecha,tipo) 
{
    vFecha = this.formatoFecha(fecha);
	
	var dia = vFecha[0];	
	var mes = vFecha[1];
	
	var anyo = vFecha[2];
	if (anyo.length == 2)
	{ 
		if (anyo == 10) 
			anyo = "200" + anyo;
		if (anyo < 80)
			anyo = "20" + anyo;
		if (anyo <= 99)
			anyo = "19" + anyo;
		if (anyo < 1000)
			anyo = anyo + 1900;
	}
	
	var userYear=anyo;  // Año introducido, puede ser d 2 o 4 dígitos
		
	var dfecha = " "+ anyo +", "+mes + ",  "+dia;
	var thenx = new Date(anyo,mes,dia);
	var year=thenx.getFullYear();
	if (year<100)
	   year="19" + thenx.getYear();
	else
	   year=thenx.getYear();
	
	if (year > 1969) wyear=year; 
	else 
	{
		if (userYear<1900) 
		{ 
			if (userYear>1800) 
			{
				wrelyear= (eval(year)-1801)%(28);
				wyear = wrelyear+1981;
			}
			else wyear = 1970;
		}
		else
		{ 
			if (userYear>1900) 
			{
				wrelyear= (eval(userYear)-1901)%(28); 
				wyear= wrelyear+1985;
			}
			else 
			if (userYear==1900)
				wyear= 1990;
		}
	}
	var dob = " "+ wyear +", "+mes + ",  "+dia;
	var thenx = new Date(dob);
	
	var theday = thenx.getDay()+1;
	var date=thenx.getDate();
	
	var weekday = new Array(6);
	weekday[1]="Domingo";
	weekday[2]="Lunes";
	weekday[3]="Martes";
	weekday[4]="Miércoles";
	weekday[5]="Jueves";
	weekday[6]="Viernes";
	weekday[7]="Sábado";

	if ((dia != date) && (dia != ''))
		this.informaAvisoJS('FORMATO_FECHA', this);
	else 
	{
		dayborn = weekday[theday];
		if (dayborn == "Miércoles")
			diaSemana = "X";
		else
			diaSemana = dayborn.substring(0,1);
	}
	if (tipo == 'short')
		return diaSemana;
	else
		return weekday[theday];	
}

/**
  * mostrarInfoFechaJS() Muestra información adicional de una fecha dada
  * @param	dayOfWeek	boolean	Booleano para devolver el nombre del día de la semana (short/long)
  * @param	dayOfYear	boolean	Booleano para devolver el número de día respecto al año entero
  * @param	weekOfYear	boolean		Booleano para devolver el número de la semana del año
*/
function f_oComprobacion_mostrarInfoFechaJS(fecha,campoFecha,dayOfWeek,dayOfYear,weekOfYear) 
{
		idCapa = 'infoFecha'+campoFecha;
		capaDia = eval('document.getElementById("'+idCapa+'")');
		textoCapa = capaDia.childNodes[0];
		infoFecha = '';
		if (fecha != '')
		{
			if (dayOfWeek != 'none')
			  infoFecha += " "+this.mostrarDiaSemanaJS(fecha,dayOfWeek);
			if (dayOfYear)
			  infoFecha += " D"+this.calcularDiaAnyoJS(fecha);		
			if (weekOfYear)
			  infoFecha += " S"+this.calcularSemanaJS(fecha);		
		}
		textoCapa.data = infoFecha+" ";
}
//*********************************************************************************************************************************/


/**
  * formateaNumero() Dada una cadena (formateada o no) que representa un número
  * devuelve una cadena con el número formateado conforme al idioma (es-ES)
  * @param	strNum	string	Cadena (formateada o no) del número a formatear
  * @param	nDec	int		Número de decimales (con redondeo)
*/
function f_oComprobacion_formateaNumero(strNum, nDec)
{	
	strNumOrig = strNum;//Guardfamos la cadena inicial
	
	//Si NO hay número decimales definidos lo fijamos a dos
	if ((nDec =='') || (nDec ==null)) nDec = 2;
	
	//Cadena vacía o nula...
	if ((strNum =='') || (strNum ==null)) return;
	
	//Coprueba si la cadena viene formateada y cual es el formato
	//Conseguimos la cadena numérica equivalente válida en javascript
	
	//1.- Si aparecen VARIAS comas (',')...
	if (strNum.indexOf(',') != strNum.lastIndexOf(','))
	{
		//... consideramos el número como mál formado
		this.error= true;
		this.informaAvisoJS('FORMATO_NUMERICO', this);
		return strNumOrig;
	}
	
	//Si hay VARIOS puntos ('.') o un punto Y alguna coma
	if (
		(strNum.indexOf('.') != strNum.lastIndexOf('.'))
		||
			(
				(strNum.indexOf('.')>0)
				&&
				(strNum.indexOf(',')>0) 
			)
		)
	{
		//Podemos asumir que el punto se ha usado como separador de miles, así que
		//eliminamos todos los separadores de miles (carácteres '.')
		//para convertirlo en una cadena numérica válida en javascript
		strNum = strNum.split('.').join('');
	}
	
	//Si aparece UNA coma...
	if (strNum.indexOf(',')>0)
	{
		//Podemos asumir que la coma (','), es el separador decimal, lo cambiamos
		// por el separador decimal que reconoce javascript, el punto ('.')
		strNum = strNum.split(',').join('.');
	}
	
	//Convertimos a número la cadena numérica
	num = parseFloat(strNum);
	if (isNaN(num))
	{
		this.error= true;
		this.informaAvisoJS('FORMATO_NUMERICO', this);
		return strNumOrig;		
	}
		
	//Convertimos el número a entero y quitamos los decimales
	var x = Math.round(num * Math.pow(10, nDec));
	
	//Signo del número
	var signo='';
	if (x < 0)	signo='-';
	
	//Creamos un array con el número
	var y = (''+Math.abs(x)).split('');
	
	
	var z = y.length - nDec;
	if (z<0) z--;
	for(var i = z; i < 0; i++) y.unshift('0');
	
	y.splice(z, 0, ',');
	if(y[0] == ',') y.unshift('0');
	while (z > 3)
	{
		z-=3;
		y.splice(z, 0, '.');
	}
	
	var retorno = signo+y.join('');
	return retorno;
}

