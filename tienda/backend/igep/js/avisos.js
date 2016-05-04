/***************** CONSTRUCTOR oAviso **********************/
function oAviso(rutaImg) 
{
	this.nomVariable = 'aviso';
	this.idCapa = 'capaAviso';
	this.tipo = 'error';
	this.codError = 'codError';
	this.descBreve = 'descBreve';
	this.descLarga = 'descLarga';
    this.textoBtnAceptar = 'Aceptar';
	this.accionBtnAceptar = '';
	this.formulario = null;
    this.textoBtnCancelar = null;
	this.imgFondo = rutaImg+'pestanyas/pix_trans.gif';
	this.rutaImg = rutaImg;
	this.rutaImgAvisos = rutaImg+'avisos/';
	this.zIndice =  200; //ZIndex a partir del cual se trabaja
/*	this.ancho = 200; //Ancho de la capa de aviso
	this.alto = 200;//Alto de la capa de aviso*/
	
	ie = (document.all)? true:false; //Cambia de IE al resto
	if(ie) 
	{
		this.anchoPagina = document.body.clientWidth;
		this.altoPagina = document.body.clientHeight;
	}
	else 
	{
		this.anchoPagina = innerWidth;
		this.altoPagina = innerHeight;
	}

	/*  M�todos */
	this.mostrarAviso = f_oAviso_mostrarAviso;
	this.mostrarAbout = f_oAviso_mostrarAbout;
	this.mostrarMensajeCargando = f_oAviso_mostrarMensajeCargando;
	this.set =f_oAviso_set;
	this.cerrarCapa = f_oAviso_cerrarCapa;
	this.capaBloqueo = f_oAviso_capaBloqueo;
	this.capaError = f_oAviso_capaError;
	this.enviaForm =	f_oAviso_enviaForm;
}

/******************* SET ***********************************************/
function f_oAviso_set(nombre, idCapa, tipo, codError, descBreve, descLarga, textoBtnCancelar, textoBtnAceptar, nombreForm, accionBtnAceptar)
{
	this.nomVariable = nombre;
	this.idCapa = idCapa;
	this.tipo = tipo.toUpperCase();
	this.codError = codError;
	this.descBreve = descBreve;
	this.descLarga = descLarga;
	this.textoBtnCancelar = textoBtnCancelar;
	if (textoBtnAceptar) this.textoBtnAceptar = textoBtnAceptar;	
	if ((nombreForm) && (nombreForm!='')) 
	{
		this.formulario = eval('document.forms["'+nombreForm+'"]');
	}
	else
	{
		this.formulario = document.forms[1];
	}
	if (accionBtnAceptar) this.accionBtnAceptar = accionBtnAceptar;
}

function f_oAviso_enviaForm() 
{	
	if(this.accionBtnAceptar!='')
	{
		this.formulario.action = this.accionBtnAceptar;
	}	
	//this.formulario.target = '_self'; // No s� pq estaba, necesitamos ejecutar el formulario x el oculto (09/03/2010)	
	this.formulario.submit();
	this.cerrarCapa();
}


/******************* CERRAR CAPA *************************************/
function f_oAviso_cerrarCapa(idObjGen) 
{	
	var capaAviso = null;
	capaAviso = eval('document.getElementById("'+this.idCapa+'")');
	capaAviso.style.display = "none";
	
	var capaBloqueo = document.getElementById("capaBloqueo");
  	document.body.removeChild(capaBloqueo);
  	
	if ((idObjGen!=null) && (idObjGen!=''))
	{				
		document.getElementById(idObjGen).focus();
	}	
}

/******************* Mostrar Acerca de *************************************/
function f_oAviso_mostrarAbout(objetoGenerador)
{
	var capaAviso = null;
	var contenido = '';
	var imgBk = '';
	var cerrar = '';
	var idObjGenerador ='';

	if (objetoGenerador !== undefined)
	{		
		idObjGenerador = objetoGenerador.id;
	}
	else
	{
		idObjGenerador='';
	}
		
	//Creamos la capa de bloqueo
	this.capaBloqueo();
	
	//Creamos la capa de Error
	this.capaError();
		
	contenido += '<div id="about" class=" about">';
		contenido += '<div id="superior" class="row stoolbarAbout">';
			contenido += '<div id="logoLateral" class="col-md-6 logoAbout text-center">';
				contenido += '<img class="logoAbout" style="margin-bottom: 10px;"  src="igep/images/logo.jpg" border="0">';
				contenido += '<br><br>';
				contenido += '<a href="http://www.gvhidra.org" target="_blank" style="color:black;text-decoration: none;cursor: pointer;">www.gvhidra.org</a>';
			contenido += '</div>';
			contenido += '<div id="texto" class="col-md-6 titleAbout text-center">';
				contenido += '<span class="text1 text-center bg-success">'+this.tipo+'</span><br/><br/>';
				contenido += '<span class="text2 text-center bg-danger">'+this.codError+'</span><br/><br/>';
				contenido += '<span class="text3 text-center bg-danger">gvHidra '+this.descBreve+'</span>';
			contenido += '</div>';
		contenido += '</div>';
		contenido += '<div id="inferior" class="row logoBottomAbout">';
			contenido += '<img src="'+this.rutaImg+'logos/logo.gif" border="0"><br/>';
		contenido += '</div>';
	
		contenido += '<div id="inferior" class="toolbarBottomAbout">';
			//contenido += '<button type="button" id="btnAceptar" name="btnAceptar" class="text button" style="cursor:pointer" onmouseover="this.className=\'text\';" onmouseout="this.className=\'text button\';" onClick="'+this.nomVariable+'.cerrarCapa(\''+idObjGenerador+'\');">';
			contenido += '<button type="button" id="btnAceptar" name="btnAceptar" class="button btn" onClick="'+this.nomVariable+'.cerrarCapa(\''+idObjGenerador+'\');">';
			contenido += '<img style="border-style: none;" id="aceptar" src="'+this.rutaImg+'acciones/41.gif"> Aceptar</button>';
		contenido += '</div>';
	contenido += '</div>';
	
	capaAbout = eval('document.getElementById("'+this.idCapa+'")');
	capaAbout.innerHTML = contenido;
	capaAbout.style.display = 'block';
	document.getElementById('btnAceptar').focus();  
}

/******************* Mostrar Aviso *************************************/
function f_oAviso_mostrarAviso(objetoGenerador)
{
	var capaAviso = null;
	var contenido = '';
	var imgBk = '';
	var cerrar = '';
	var idObjGenerador ='';

	if (objetoGenerador !== undefined)
	{		
		idObjGenerador = objetoGenerador.id;
	}
	else
	{
		idObjGenerador='';
	}
		
	//Creamos la capa de bloqueo
	this.capaBloqueo();
	
	//Creamos la capa de Error
	this.capaError();
	
	switch(this.tipo) //Seg�n el tipo de aviso...
	{
		case 'ERROR':
		case 'error':
			imgBk = 'aviso-error';
		break;
		
		case 'AVISO':
		case 'aviso':
			imgBk = 'aviso-aviso';
		break;
		
		case 'SUGERENCIA':
		case 'sugerencia':
			 imgBk = 'aviso-sugerencia';
		break;
		
		case 'ALERTA':
		case 'alerta':
			imgBk = 'aviso-alerta';
		break;
		
		case 'CONFIRM':
		case 'confirm':
			imgBk = 'aviso-confirm';
		break;
	};
	
 	// Dibuja la ventana y fija el contenido de la capa
	//Formulario y campos ocultos para el manejo de mensajes
	contenido += '<div class="boxMessage">';	
	contenido += '<div class="alert '+imgBk+'">';
	contenido +=    '<div class="row alert-cod">';
	contenido += 		'<div class="col-md-12">'+this.codError+'</div>';
	contenido += 	'</div>';
	contenido +=    '<div class="row alert-header">';
	contenido += 		'<div class="col-md-3"><span class="glyphicon glyphicon-warning-sign alert-glyphicon" aria-hidden="true"></span></div> ';
	contenido += 		'<div class="col-md-9"><h4>'+this.descBreve+'</h4></div>';
	contenido += 	'</div>';
	contenido +=    '<div class="row aviso-desc-larg">'+this.descLarga+'</div>';	
	
	/*if ((this.textoBtnCancelar != null) || ((this.tipo == 'CONFIRM') || (this.tipo == 'confirm')))
	{
		contenido += '<button type="button" id="btnAceptar" name="btnAceptar" class="text button" style="cursor:pointer" onmouseover="this.className=\'text\';" onmouseout="this.className=\'text button\';" onClick="'+this.nomVariable+'.enviaForm();">';
			contenido += '<img style="border-style: none; " id="aceptar" src="'+this.rutaImg+'acciones/41.gif" />'+this.textoBtnAceptar;	
		contenido +='</button>';										
		
		contenido += '&nbsp;<button type="button" id="btnCancelar" name="btnCancelar" class="text button" style="cursor:pointer" onmouseover="this.className=\'text\';" onmouseout="this.className=\'text button\';" onClick="'+this.nomVariable+'.cerrarCapa();">';
			contenido += '<img style="border-style: none; " id="cancelar" src="'+this.rutaImg+'acciones/42.gif" />'+this.textoBtnCancelar;
		contenido +='</button>';
	}
	else 
	{
		contenido += '<button type="button" id="btnAceptar" name="btnAceptar" class="text button" style="cursor:pointer" onmouseover="this.className=\'text\';" onmouseout="this.className=\'text button\';" onClick="'+this.nomVariable+'.cerrarCapa(\''+idObjGenerador+'\');">';
			contenido += '<img style="border-style: none; " id="aceptar" src="'+this.rutaImg+'acciones/41.gif" />'+this.textoBtnAceptar;	
		contenido +='</button>';										
	}*/
	
	contenido += '<div class="row text-right" ">';
	if ((this.textoBtnCancelar != null) || ((this.tipo == 'CONFIRM') || (this.tipo == 'confirm')))
	{
		contenido += '<button type="button" id="btnAceptar" name="btnAceptar" class="bottom-alert-form " onClick="'+this.nomVariable+'.enviaForm();">';
			contenido += '<img style="border-style: none; " id="aceptar" src="'+this.rutaImg+'acciones/41.gif" />'+this.textoBtnAceptar;	
		contenido +='</button>';										
		
		contenido += '&nbsp;<button type="button" id="btnCancelar" name="btnCancelar" class="bottom-alert-form" onClick="'+this.nomVariable+'.cerrarCapa();">';
			contenido += '<img style="border-style: none; " id="cancelar" src="'+this.rutaImg+'acciones/42.gif" />'+this.textoBtnCancelar;
		contenido +='</button>';
	}
	else 
	{
		contenido += '<button type="button" id="btnAceptar" name="btnAceptar" class="bottom-alert-form" onClick="'+this.nomVariable+'.cerrarCapa(\''+idObjGenerador+'\');">';
			contenido += '<span class="glyphicon glyphicon glyphicon-ok" aria-hidden="true"></span>'+this.textoBtnAceptar;	
		contenido +='</button>';										
	}
	
	contenido += '</div>';
	contenido += '</div>';
	
	contenido += '</div>';
	//Cierre Formulario
	//contenido +='<form>'
	capaAviso = eval('document.getElementById("'+this.idCapa+'")');
	capaAviso.innerHTML = contenido;
	capaAviso.style.display = 'block';
	document.getElementById('btnAceptar').focus();  
}

/******************** CREAR CAPA Bloqueo *********************************/
function f_oAviso_capaBloqueo() 
{
	//Si la capa de bloqueo no existe la crea.      
	if(document.getElementById("capaBloqueo")==null)
	{
		// crear la capa de bloqueo para explorer y para mozilla
		var nuevo = document.createElement("div");
		nuevo.id="capaBloqueo";
		nuevo.style.position="absolute";
		nuevo.style.zIndex=this.zIndice;
		nuevo.style.left=0;
		nuevo.style.top=0;
		nuevo.style.border='1px';
		/*nuevo.style.backgroundImage="url('"+this.imgFondo+"')";*/
		nuevo.style.width='100%';
		nuevo.style.height='100%';
		document.body.appendChild(nuevo);		
	}
}

/******************** CREAR CAPA ERROR *********************************/

function f_oAviso_capaError() 
{
	var capa = this.idCapa;	
	var msgTop, msgLeft;

	// calcular posicion de la capa de ventana de error
	LeftPosition = (this.anchoPagina) ? (this.anchoPagina-this.ancho)/2 : 0;
	TopPosition = (this.altoPagina) ? (this.altoPagina-this.alto)/2 : 0;
	
	Z = parseInt(this.zIndice+2, 10);
	obj_capaError = eval('document.getElementById("'+this.idCapa+'")');
	obj_capaError.style.zIndex = Z;
	obj_capaError.style.width = this.ancho;
	obj_capaError.style.height = this.alto;	
	obj_capaError.style.left = LeftPosition;
	obj_capaError.style.top = TopPosition;	
	obj_capaError.style.display = "block";
}

/************************* CONTENIDO CAPA ERROR **************************/


/******************* Mostrar Aviso *************************************/
function f_oAviso_mostrarMensajeCargando(mensaje)
{
	var capaAviso = null;
	var contenido = '';
	var imgBk = '';
	var cerrar = '';

	//Creamos la capa de bloqueo
	this.capaBloqueo();
	
	//Creamos la capa de Error
	this.capaError();
	
	imgBk = this.rutaImgAvisos+'gvhidra.gif';	
		
	contenido += '<div id="loading">';
	contenido += '<ul class="bokeh">';
	contenido += '<li></li><li></li><li></li><li></li><li></li><li></li>';
	contenido += '</ul>';
	contenido += '</div>';
	capaAviso = eval('document.getElementById("'+this.idCapa+'")');
	capaAviso.innerHTML = contenido;
	capaAviso.style.display = 'block';
}

/** FUNCTION QUE MUESTRA EL DIV EN LA POSICI�N DEL RAT�N **/
function showdiv(event,id)
{
	//determina un margen de pixels del div al raton
	margin=5;
	//La variable IE determina si estamos utilizando IE
	var IE = document.all?true:false;

	var tempX = 0;
	var tempY = 0;

	if(IE)
	{ //para IE
		tempX = event.x
		tempY = event.y
		if(window.pageYOffset){
			tempY=(tempY+window.pageYOffset);
			tempX=(tempX+window.pageXOffset);
		}else{
			tempY=(tempY+Math.max(document.body.scrollTop,document.documentElement.scrollTop));
			tempX=(tempX+Math.max(document.body.scrollLeft,document.documentElement.scrollLeft));
		}
	}else{ //para netscape
		document.captureEvents(Event.MOUSEMOVE);
		tempX = event.pageX;
		tempY = event.pageY;
	}

	if (tempX < 0){tempX = 0;} else {tempX = tempX - 15;}
	if (tempY < 0){tempY = 0;} 

	id.style.top = (tempY+margin)+"px";
	id.style.left = (tempX+margin)+"px";
	id.style.display='block';
	/*document.getElementById(capaInf).style.top = (tempY+margin)+"px";
	document.getElementById(capaInf).style.left = (tempX+margin)+"px";
	document.getElementById(capaInf).style.display='block';*/
	return;
}