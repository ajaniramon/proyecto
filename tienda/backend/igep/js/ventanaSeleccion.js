function cerrar(formOrigen,actionOrigen)
{
	eval('window.opener.document.forms["'+formOrigen+'"].action="'+actionOrigen+'"');
}

function aceptarCancelarSeleccion(accion, actionOrigen, formulario, fila, panel, actuaSobreForm, fieldsTPL, fieldsSource) 
{
	// fieldsTPL es una matriz con los campos de la tpl y la bd

	oComprobacion = eval('vSeleccion_comp');
	checkSeleccionado = oComprobacion.soloUnCheck();
	eval('window.opener.document.forms["'+actuaSobreForm+'"].target="_self"');
	var arrayCamposAfectados = new Array();//Array de campos modificados
	if (accion == 'aceptar')
	{
		if (checkSeleccionado != '') 
		{
			// Componemos el nombre del campo en la ventana de Selección
			filaSeleccionada = checkSeleccionado.split('_'); // check_Tabla1_1
			campoFoco = '';			
			for (i=0; i<fieldsTPL.length; i++)//Para cada campo que va a actualizarse 
			{
				// Tenemos que comprobar si existe el campo o no
				// Primero comprobar si el nombre del campo es tipo "nomCampo_numFila" (forma anterior de trabajar)
				// Si no comprobar si el campo tiene el nombre nuevo "cam___nomCampo___lis_numFila" 
				if (eval('document.forms[0].'+fieldsSource[i]+'_'+filaSeleccionada[2]))
				{
					campoOrigen = fieldsSource[i]+'_'+filaSeleccionada[2];
				}
				else
				{
					if (eval('document.forms[0].cam___'+fieldsSource[i]+'___'+panel+'_'+filaSeleccionada[2]))
						campoOrigen = 'cam___'+fieldsSource[i]+'___'+panel+'_'+filaSeleccionada[2];
					else
						continue;
				}

				if (actuaSobreForm == "F_fil") // Estamos en un panel de búsqueda
				{
					campoDestino = fieldsTPL[i];
				}
				else 
				{
					campoDestino = fieldsTPL[i]+'___'+panel+'_'+fila;
				}

				estadoCampo = 'est_'+panel+'_'+fila;//Estado del registro
				if (eval('window.opener.document.forms["'+actuaSobreForm+'"].ins___'+campoDestino)) 
				{
					campoDestino = 'ins___'+campoDestino;
					valorEstado = 'insertada';
				}
				else if (eval('window.opener.document.forms["'+actuaSobreForm+'"].cam___'+campoDestino)) 
				{
					campoDestino = 'cam___'+campoDestino;
					valorEstado = 'modificada';	
				}
				tipoCampoDestino = eval('window.opener.document.forms["'+actuaSobreForm+'"].'+campoDestino+'.type');
				if (tipoCampoDestino != 'hidden')  // Devolver el foco al campo q no está oculto por si tiene algún evento q realizar
					campoFoco = campoDestino;					
				
		
				if(campoOrigen!=null)
				{
						
					// IMÁGENES
					tipoImgOrigen = eval('document.forms[0].'+campoOrigen+'.tagName');
					if (tipoImgOrigen == 'IMG') 
					{
						valor = eval('document.forms[0].'+campoOrigen+'.src');
					}					
					else
					{
						 valor = eval('document.forms[0].'+campoOrigen+'.value');
					}
					
					// Copiamos el valor
					tipoImgDestino = eval('window.opener.document.forms["'+actuaSobreForm+'"].'+campoDestino+'.tagName');					
					obj_campoDestino = eval('window.opener.document.forms["'+actuaSobreForm+'"].'+campoDestino);
					if (tipoImgDestino == 'IMG') 
					{					
						eval('window.opener.document.forms["'+actuaSobreForm+'"].'+campoDestino+'.src="'+valor+'"');
					}
					else {
						eval('window.opener.document.forms["'+actuaSobreForm+'"].'+campoDestino+'.value="'+valor+'"');
					}
					
					//Comprobamos que existe el campo antes asignarle el nuevo valor
					if(obj_campoDestino!=null)
					{
						obj_campoDestino.value = valor;
						if (actuaSobreForm != "F_fil") // En el panel de búsqueda no existe este campo pq x ahora no hay botones ToolTip
						{
							eval('window.opener.document.forms["'+actuaSobreForm+'"].'+estadoCampo+'.value="'+valorEstado+'"');
						}
						arrayCamposAfectados.push(obj_campoDestino);//Guardamos el elemento para luego disparar su evento asociado
					}//Fin if obj_campoDestino
				}//Fin if campoOrigen
			}//Fin For 
			
			//Para cada elemento modificado, disparamos el evento onChange y onBlur
			//del campo destino para activar las acciones de interfaz que tenga asociadas			
			for (i=0; i<arrayCamposAfectados.length; i++)//Para cada campo que va a actualizarse 
			{
				obj_campoDestino = arrayCamposAfectados[i];
				estadoAccesoCampo = obj_campoDestino.readOnly; //ReadOnly si/no
				if (estadoAccesoCampo == true)
				{
					obj_campoDestino.readOnly = false;
				}				
				nomEvent = 'blur';
				if(document.dispatchEvent) { // W3C
					var evt = window.opener.document.createEvent("HTMLEvents");
					evt.initEvent(nomEvent, true, true); // tipo de evento (change, click...), burbujeo, cancelable
					obj_campoDestino.dispatchEvent(evt);					
				}
				else
					obj_campoDestino.fireEvent('onblur');
				obj_campoDestino.readOnly = estadoAccesoCampo;
			}//Fin disparo eventos
			
			
			if (campoFoco != '') 
			{
				eval('window.opener.document.forms["'+actuaSobreForm+'"].'+campoFoco+'.focus()');
			}
			eval('window.opener.document.forms["'+actuaSobreForm+'"].action="'+actionOrigen+'"');
			window.close();
		} // if checkSeleccionado
		else 
		{
			alert('La selección debe ser siempre de un único registro');
		}
	} // if aceptar/cancelar
	else if (accion == 'cancelar') 
	{
		eval('window.opener.document.forms["'+actuaSobreForm+'"].action="'+actionOrigen+'"');
		window.close();
	}
}

function buscar(claseManejadora, formVtanaOrigen, campoSinPrefijo, panelActua, filaActual)
{
	action = "phrame.php?action=buscarVentanaSeleccion&claseManejadora="+claseManejadora+"&nomForm="+formVtanaOrigen+"&nomCampo="+campoSinPrefijo+"&panelActua="+panelActua+"&filaActual="+filaActual;
	eval('document.forms["F_vSeleccion"].action="'+action+'"');
	document.forms["F_vSeleccion"].submit();
}

//***************************************************************************************//
function GetXmlHttpObject()
{
	var xmlHttp=null;	
	try
	{
		// Firefox, Opera 8.0+, Safari
		xmlHttp=new XMLHttpRequest();
	}
	catch (e)
	{
		// Internet Explorer
		try
		{
			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{
			xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	return xmlHttp;
}

function openWS(claseManejadora,actuaSobre,formVS,campo,panelActua)
{
	// Hay que componer el id del botón de abrir la ventana de selección
	vCampo = campo.split('___');
	pagina = vCampo[1];	
	if ((pagina === 'undefined') || (pagina == 'undefined') || (pagina == null) || (pagina === null))
		// Estamos en panel filtro
		bt = eval(document.getElementById('vs_'+actuaSobre));
	else
		// Estamos en panel tabular o registro
		bt = eval(document.getElementById('vs_'+actuaSobre+'___'+vCampo[1]));
	
	expr = /off/;
	if (!expr.test(bt.src)) 
	//if (!expr.test(this.src)) 
	{ 
		ajax=new XMLHttpRequest();
		var accion ='phrame.php?action=launchSelectionWindow&claseManejadora='+claseManejadora+'&selectionWindow='+actuaSobre;
		ajax.open('GET', accion, true);
		ajax.onreadystatechange = function() 
			{
				if (ajax.readyState==4) 
				{
					size = ajax.responseText;				
					abrirVentanaSeleccion(formVS, campo, claseManejadora, panelActua, size);
				}
			}
		ajax.send(null);
	}
}

//***************************************************************************************//

// Eliminamos el parámetro 'filaActual' pq ya lo descompone negocio del nombre del campo
function abrirVentanaSeleccion(formVtanaOrigen, campoSinPrefijo, claseManejadora, panelActua, size)
{
// Guardamos el action dl formulario origen para recuperarlo una vez cerrada la ventana
	var actionOrigen = eval('document.forms["'+formVtanaOrigen+'"].action');
// Necesitamos saber si estamos en modo inserción o modificación
	var accionActivaN = "";
	if (eval('document.forms["'+formVtanaOrigen+'"].accionActiva'))
	{
		accionActivaN = eval('document.forms["'+formVtanaOrigen+'"].accionActiva.value');
	}
	var accionActivaP = eval('document.forms["'+formVtanaOrigen+'"].accionActivaP_'+formVtanaOrigen+'.value');
//alert('accionActivaN: '+accionActivaN+'  accionActivaP: '+accionActivaP);
	var campo = "cam___"+campoSinPrefijo; // Venimos de modificación
	if ((accionActivaN == 'insertada')  || (accionActivaP == 'insertar'))// Venimos de inserción
		campo = "ins___"+campoSinPrefijo;
	if (formVtanaOrigen == "F_fil")
 		campo = campoSinPrefijo;
	
	var medidas = size.split('|');
	var w = medidas[0];
	var h = medidas[1];

// Abre una ventana en blanco
	if (IE4)
	{
		h = h+100;
		Open_Vtna('igep/blanco.htm','Seleccion',w,h,'no','no','no','no','no','yes');
	}
	else
		Open_Vtna('igep/blanco.htm','Seleccion',w,h,'no','no','no','no','no','yes');
// Creamos el nuevo action para q ejecute la ventana d selección
//	actionNueva = "phrame.php?action=abrirVentanaSeleccion&claseManejadora="+claseManejadora+"&nomForm="+formVtanaOrigen+"&nomCampo="+campo+"&panelActua="+panelActua+"&filaActual="+filaActual+"&actionOrigen="+actionOrigen;
	actionNueva = "phrame.php?action=abrirVentanaSeleccion&claseManejadora="+claseManejadora+"&nomForm="+formVtanaOrigen+"&nomCampo="+campo+"&panelActua="+panelActua+"&actionOrigen="+actionOrigen;
	eval('document.forms["'+formVtanaOrigen+'"].action = "'+actionNueva+'"');
// El destino dl formulario d es la ventana
	eval('document.forms["'+formVtanaOrigen+'"].target = "Seleccion"');
	eval('document.forms["'+formVtanaOrigen+'"].submit();');
}