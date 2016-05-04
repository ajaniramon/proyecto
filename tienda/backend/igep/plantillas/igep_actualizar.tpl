<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1'>
<title>Actualizar-Oculto</title>
{literal}
<script type='text/javascript' src='igep/js/window.js'></script>
<script type='text/javascript' src='igep/js/escape.js'></script>
<script  type='text/javascript'>
//<![CDATA[
var formulario = "{/literal}{$smty_formulario}{literal}";
var origen = "{/literal}{$smty_origen}{literal}";
var destino = "";
var opciones = new Array();
var NS4 = (navigator.appName == "Netscape" && parseInt(navigator.appVersion) < 5);
var NSX = (navigator.appName == "Netscape");
var IE4 = (document.all) ? true : false;
var documento = parent.document;

function insertar_opcion(valor,texto,seleccionado)
{
	var punto_insercion=-1;
	var i=0;
	var objeto = new Object();
	objeto.texto = texto;
	objeto.valor = valor;
	objeto.seleccionado = seleccionado;
	punto_insercion = opciones.length;
	opciones.length = opciones.length+1;
	opciones[punto_insercion]=objeto;
}

function cambia(formulario, destino, elementos)
{

	if (parent.document.getElementById(destino)==null)
		destino+='[]';
	
	itemSeleccionado = 0;
	tam = elementos.length;
	formDestino = parent.formulario;
	campoDestino = parent.document.getElementById(destino);
	tamResult=campoDestino.length;
		
 	for (i=0;i<tamResult;i++){
 		campoDestino.options[0] = null ;
 	}
 	for (i=0;i<tam;i++){
  		texto = elementos[i].texto;
  		valor = elementos[i].valor;
  		if(elementos[i].seleccionado != 0)
  			itemSeleccionado = i;  			
  		campoDestino.options[i] = new Option(texto,valor);
 	}

 	campoDestino.selectedIndex = itemSeleccionado;
	
}

function setSelectedRadio(nombreFormulario, nameRadio, valor)
{
	valor+='';	
	miRadio = eval("parent.document.forms[\'"+nombreFormulario+"\']."+nameRadio);
	var i;
    for (i=0; i<miRadio.length; i++)
	{
		if (miRadio[i].value == valor)
		{
			miRadio[i].checked = true;
			break;
		}
    }
    miValorRadioHidden = eval("parent.document.forms[\'"+nombreFormulario+"\'].l"+nameRadio);
    miValorRadioHidden.value=valor;
}//fin setSelectedRadio

function setSelectedMultipleOption(nombreFormulario, nameLista, valor)
{
	select = eval("parent.document.forms[\'"+nombreFormulario+"\']."+nameLista);

	for(var i=0;i<select.options.length;i++)
	{
		for(var j=0;j<valor.length;j++)
		{
			if (select.options[i].value == valor[j])
					select.options[i].selected = true;
		}
	}
    miValorListaHidden = eval("parent.document.forms[\'"+nombreFormulario+"\'].l"+nameLista);
    miValorListaHidden.value=valor;
}//fin setSelectedOption

function setSelectedOption(nombreFormulario, nameLista, valor)
{
	valor+='';
	miLista = eval("parent.document.forms[\'"+nombreFormulario+"\']."+nameLista);	
	var i;
    for (i=0; i<miLista.options.length; i++)
	{
		if (miLista.options[i].value == valor)
		{
			miLista.options[i].selected = true;
			break;
		}
    }
    miValorListaHidden = eval("parent.document.forms[\'"+nombreFormulario+"\'].l"+nameLista);
    miValorListaHidden.value=valor;
}//fin setSelectedOption


function insertar_opcion_radio(nombreFormulario, nameRadio, valorEtiqueta, valorRadio)
{	
	if ((valorRadio =='') || (valorRadio==null)) return;
				
	f = document.getElementById(nombreFormulario);
	radio = document.createElement("input");
	radio.name = nameRadio;
	
	etiqueta = document.createElement("label");
	texto = document.createTextNode(valorEtiqueta);
	
	//radio.id = "rad_ejemplo_"+num_radio;
	radio.type = "radio";
	radio.value = valorRadio;
	//addEvent(radio,"click",clickradio);
	etiqueta.appendChild(texto);
	etiqueta.appendChild(radio);
	f.appendChild(etiqueta);
	//num_radio++;
}



function actualizar() 
{
	miLocation = window.location.href+'';
	
	if (miLocation.indexOf('cancelado')==-1) //Si NO se ha pulsado cancelar...
	{
		vOrigen = origen.split('___');
		if (vOrigen.length > 1) {
			// Componemos el nombre del campo destino ej. cam___nombreCampo___FichaEdicion_0
			campoDestino = vOrigen[0]+'___'+destino+'___'+vOrigen[2];
		}
		else {
			// Es un panel de búsqueda no hay q componer el nombre
			campoDestino = destino;
		}
		{/literal}{$smty_insertarOpciones}{literal};
	}
}

//]]
</script>
{/literal}
</head>
<body onLoad="actualizar();this.parent.document.forms[1].target='_self'">
</body>
</html>