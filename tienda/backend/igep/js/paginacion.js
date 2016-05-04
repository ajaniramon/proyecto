/*
  Vease el fichero igepMensaje.php entorno a la línea 52
  para registrar los nuevos mensajes que se añadan
*/

function oPaginacion(nomvariable)
{ 
	this.nom_variable = nomvariable; // str nombre de la variable del objeto creado
//	this.comprobarAlAvanzar = comprobarAlAvanzar; //Indica si debemos hacer comprobaciones sobre la ficha actual al avanzar o retroceder
//	this.imgMarcaModificacion = 'igep/images/avisos/marcaModificado.gif';
	this.paginasModificadas = new Array();//Vector de boolenaos que indica si se ha modificado o no una página
	this.listaPaginas = null;
	this.idPanel = nomvariable.split('_paginacion')[0];
	this.nomPagActiva = ''; // nombre d la capa d la página activa
	this.pagina_activa = 0; // int página activa
	this.paginaAnterior = 0;
	this.sufijo = ""; // str sufijo de las capas de esta paginación
	this.nom_capa_enlaces = ""; // str con el nombre de la capa donde se 'pintan' los enlaces
	this.paginas_totales = 0; // int páginas totales	
	this.tam_marco = 0; // int tamaño del marco de enlaces visibles	
	this.pagExactas = null; //Booleano indica si la página está completa o no (para tablas)
	this.pagInsertar = 0; //Número de páginas de inserción
	this.textoEnlaces = 'Pag';
	this.formulario = '';
	this.esMaestro= '';
	this.numRegistros= '';	//int indica el numero total de registros (para el tabular)		
	this.actionForm = '';
	this.nombreFicha = '';	
	this.rutaImg = ''; // ruta a las imágenes del custom
	this.btnIco = false;
	if (arguments[1])
	{
		this.campoHidden = arguments[1];
	}
	else
	{
		this.campoHidden = '';
	}

	//MÉTODOS
	this.abrir_pagina = f_oPaginacion_abrir_pagina; // pasa a la página
	this.cadenaEnlaces =  f_oPaginacion_cadenaEnlaces; //lo utiliza dibujar_enlaces para obtener la cadena html
	this.dibujar_enlaces =  f_oPaginacion_dibujar_enlaces; //dibuja los enlaces
	this.set =  f_oPaginacion_set; //actualiza el valor de las variables
	this.darAviso =  f_oPaginacion_darAviso;
	this.getPaginaActiva =  f_oPaginacion_getPaginaActiva;
	this.hayModificacion = f_oPaginacion_hayModificacion;
	this.hayError =  f_oPaginacion_hayError;
	this.activarMarcaPaginacion = f_oPaginacion_activarMarcaModificacion;
	this.desactivarMarcaModificacion = f_oPaginacion_desactivarMarcaModificacion;
	this.procesarModificacion = f_oPaginacion_procesarModificacion;
	this.activarPaginasInsercion = f_oPaginacion_activarPaginasInsercion;
	this.fijarNombreFicha = f_oPaginacion_fijarNombreFicha;
	this.dump =  f_oPaginacion_dump;
}

//Esta funcion devuelve la pagina activa
function  f_oPaginacion_getPaginaActiva() 
{
	return (this.pagina_activa);
}

//Esta funcion devuelve la pagina activa
function  f_oPaginacion_fijarNombreFicha(nombreFicha) 
{
	this.nombreFicha = nombreFicha;
}

/*
	Indica si en la página actual se han llevado a cabo
	es decir, inserciones, borrados y/o modificaciones de
	campos del registro.
*/
function  f_oPaginacion_hayModificacion() 
{	
	//Si ya se había registrado la página como modificada...
	if (this.paginasModificadas[parseInt(this.getPaginaActiva(),10)]) return true;
	
	//SOLO PARA CWFicha, si el estado está marcado con modificación...
	estadoFicha = document.getElementById('est_'+this.nombreFicha+'_'+this.getPaginaActiva());
	if ( (estadoFicha != null) && (estadoFicha.value!='nada') ) return (true);
	return (false);
}

/*
	Activa la marca de Paginacion
*/
function f_oPaginacion_activarMarcaModificacion()
{	
	marcaModificado = document.getElementById(this.idPanel+'_imgModificado');	
	if (marcaModificado != null)
	{			
		marcaModificado.style.display="inline";	
	}
}


/*
	Desctiva la marca de Paginacion
*/
function f_oPaginacion_desactivarMarcaModificacion()
{
	marcaModificado = document.getElementById(this.idPanel+'_imgModificado');	
	if (marcaModificado != null)
	{			
		marcaModificado.style.display="none";	
	}
}


/*
	Comprueba si hay modificaciones en la pagina asociada
	y activa la marca de modificaciones
*/
function f_oPaginacion_procesarModificacion()
{
	//Apuntamos a la marca
	marcaModificado = document.getElementById(this.idPanel+'_imgModificado');		
	//Si esa página tiene marcado y está modificada...
	if ( (marcaModificado != null) && (this.hayModificacion()))
	{			
		marcaModificado.style.display="inline";		
		if (!this.paginasModificadas[this.getPaginaActiva()])
		{
			this.paginasModificadas[this.getPaginaActiva()] = true;					
		}
	}
	else if (marcaModificado != null)
	{
		marcaModificado.style.display="none";		
	}
	
//	listaModificados = document.getElementById(this.idPanel+'_listaModificados');	
//	if (listaModificados != null)
//	{	
//		//Vaciamos la lista
//		listaModificados.options.length = 0;
//		listaModificados.options[listaModificados.length] = new Option('cambios', null, true);	
//		numModificadas = this.paginasModificadas.length;
//		for (i=0; i<numModificadas; i++)
//		{
//			if (this.paginasModificadas[i])
//			{
//				listaModificados.options[listaModificados.length] = new Option(i+1, i);			
//			}
//			else	
//			{
//				this.paginasModificadas[i]=false;
//			}
//		}
//		
//		capaListaModificados = document.getElementById('capa_'+this.idPanel+'_listaModificados');	
//		if (listaModificados.options.length >1)//Si ha habido modificaciones...
//		{			
//			if (capaListaModificados != null) capaListaModificados.style.display="inline";
//		}
//		else
//		{
//			if (capaListaModificados != null) capaListaModificados.style.display="none";
//			if (marcaModificado != null) marcaModificado.style.display="none";
//		}
//	}

}


/*
	Indica si la capa activa tiene algún error
*/
function  f_oPaginacion_hayError()
{
	if (eval(this.idPanel+'_comp'))
	{
		objComprobacion = eval(this.idPanel+'_comp');
		return( !objComprobacion.comprobarObligatorios() );
	}
	else 
	{
		return false;
	}	
}


/*
	Construye un aviso de error y lo muestra en pantalla
*/
function  f_oPaginacion_darAviso() 
{
	if ((eval(this.idPanel+'_comp')) && (eval('aviso')) )
	{
		objComprobacion = eval(this.idPanel+'_comp');
		objAviso = eval('aviso');		
		objComprobacion.comprobarObligatorios();
		campos = objComprobacion.getCamposErroneos();
		error = 'Debe introducir un valor en los campos: '+campos;
		objAviso.set('aviso','capaAviso','aviso','IGEP-901','Faltan campos por rellenar',error);
		objAviso.mostrarAviso();
	}
}


/*
	El segundo argumento inidica si se deban hacer o no
	comprobaciones al cambiar de página, es decir, si se
	muestran o no avisos si hay errores al avanzar/retroceder
	por las fichas
*/
function  f_oPaginacion_abrir_pagina(pagina)
{
// REVIEW: Vero 02/03/2009 Comento esto pq volvemos al estado de antes de la paginación, no sé si después hará falta.
	//panel = 'P_'+this.idPanel;
	abre = this.sufijo + pagina;
	this.nomPagActiva = abre;
	cierra = this.sufijo + this.pagina_activa;
	
	if ((this.paginas_totales == 0) && (this.idPanel == "edi")) // Se mostrará la capa NO HAY DATOS
	{
		cierra = eval('document.getElementById("'+cierra+'")');
		cierra.style.display = "none";
	}
	else
	{
		if ((this.pagina_activa > -1) && (abre != cierra))
		{
			cierra = eval('document.getElementById("'+cierra+'")');
			cierra.style.display = "none";
		}	
		else
		{
			// En el caso de una ficha si queremos insertar un registro sin modificar ningún campo	
			// Hay q modificar el campo q nos indica el estado d la ficha, ponerlo a "insertada"
			if ((this.idPanel != 'lis') && (this.idPanel != 'lisDetalle'))
			{					
				accionActiva = document.getElementById('accionActiva').value;
				if (accionActiva == 'insertada') 
				{
					vAbre = abre.split('_');
					estado = 'est_'+vAbre[1]+'_'+vAbre[2];
					eval('document.getElementById("'+estado+'").value="insertada"');
				}
			}
		}
	
		abre = eval('document.getElementById("'+abre+'")');
		// Soy un panel registro detalle
		if (this.idPanel == 'ediDetalle')
		{
			insert = '';
			if (arguments[1])
				insert = 'ins'; // Vamos a abrir una página de inserción
			
			// Existe la capa de bloqueo y no queremos abrir la página de inserción
			if (eval('document.getElementById("blockPanel")') && (insert == ''))
				abre.style.display = "none"; //Ocultamos la capa que tiene los datos
			else
				abre.style.display = "block"; //Mostramos la capa que tiene datos
		}
		else
			abre.style.display = "block"; //Mostramos la capa que tiene datos
		
		this.pagina_activa = pagina;
		this.procesarModificacion();
		this.dibujar_enlaces();
	}
}


/**
 * Esta función activa las páginas de inserción en los patrones tabular
 * y registro. NO se ejecuta cuando venimos a través del tabular-registro
 * porque entonces pasamos por "negocio", la manera de saberlo 
 */
function f_oPaginacion_activarPaginasInsercion()
{
	pagExactas = this.pagExactas.toString();
	if (pagExactas == '1') // Para insertar necesito pag nueva
	{
		primeraIns = parseInt(this.paginas_totales, 10);
	}
	else
	{
		primeraIns = parseInt(this.paginas_totales - 1, 10);
	}
	this.paginas_totales = parseInt((this.paginas_totales + this.pagInsertar), 10);	
	// En el caso de una ficha si queremos insertar un registro sin modificar ningún campo	
	// Hay q modificar el campo q nos indica el estado d la ficha, ponerlo a "insertada"
	if ((this.idPanel != 'lis') && (this.idPanel != 'lisDetalle'))
	{
		estado = 'est_'+this.nombreFicha+'_'+primeraIns;
		eval('document.getElementById("'+estado+'").value="insertada"');
	}

	if (eval('document.getElementById("blockPanel")'))
	{
		bloqueo = eval('document.getElementById("blockPanel")');
		bloqueo.style.display = "none";
	}
	this.abrir_pagina(primeraIns,'ins');
}


function  f_oPaginacion_set(sufijo,nom_capa_enlaces,pagina_inicial,paginas_totales,pagInsertar,pagExactas,tam_marco, textoEnlaces,formulario,esMaestro,numRegistros,actionForm,rutaImg,btnIco)
{
	this.pagina_activa = pagina_inicial; // int página activa
	this.sufijo = sufijo; // str sufijo de las capas de esta paginación
	this.nom_capa_enlaces = nom_capa_enlaces; // str con el nombre de la capa donde se 'pintan' los enlaces
	this.paginas_totales = paginas_totales; // int páginas totales SÓLO con datos
	this.pagInsertar = pagInsertar; // int páginas insertar
	this.pagExactas = pagExactas; // [1,0] si acaba en página completa o no
	this.tam_marco = tam_marco; // int tamaño del marco de enlaces visibles
	this.textoEnlaces	=	textoEnlaces; // Prefijo de texto delante de los enlaces
	this.formulario	=	formulario; // Nombre del from del panel
	this.esMaestro	=	esMaestro; // Estamos en un maestro/detalle
	this.numRegistros = numRegistros;
	this.actionForm = actionForm;
	this.rutaImg = rutaImg;
	this.btnIco = btnIco;
	//Inicializa enlaces
	this.abrir_pagina(this.pagina_activa);
}


function  f_oPaginacion_cadenaEnlaces(pagina)
{
	enlace = '<a href="javascript:';
	enlace += 'if ('+this.nom_variable+'.hayError() == false){';
	if (this.campoHidden != '')
		enlace += 'document.forms[\''+this.formulario+'\'].'+this.campoHidden+'.value=\''+pagina+'\';';
	enlace += this.nom_variable+'.abrir_pagina('+pagina+');';

	//La segunda condicion es "chiripa"
	if ( (this.esMaestro=='true') && (this.formulario!='') )
	{						
		enlace += ';aviso.mostrarMensajeCargando(\'Cargando\');';
		enlace += ';document.forms[\''+this.formulario+'\'].target=\'oculto\';';
		enlace += ';document.forms[\''+this.formulario+'\'].action=\''+this.actionForm+'\';document.forms[\''+this.formulario+'\'].submit();'; 
	}
	enlace += '}else{'+this.nom_variable+'.darAviso();}';
	enlace += '">';

	return (enlace);
}


function  f_oPaginacion_dibujar_enlaces() 
{		
	txtNumRegTabular='';
	pagsATratar = this.paginas_totales;

//	if (this.btnIcon == true)
//	{
		antDiez = '<span class="glyphicon glyphicon-fast-backward pageOff" aria-hidden="true"></span>';
		antDiezOn = '<span class="glyphicon glyphicon-fast-backward" aria-hidden="true"></span>';
		sigDiez = '<span class="glyphicon glyphicon-fast-forward pageOff" aria-hidden="true"></span>';
		sigDiezOn = '<span class="glyphicon glyphicon-fast-forward" aria-hidden="true"></span>';
		anterior = '<span class="glyphicon glyphicon-chevron-left pageOff" aria-hidden="true"></span>';
		anteriorOn = '<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>';
		siguiente = '<span class="glyphicon glyphicon-chevron-right pageOff" aria-hidden="true"></span>';
		siguienteOn = '<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>';
		inicio = '<span class="glyphicon glyphicon-step-backward pageOff" aria-hidden="true"></span>';
		inicioOn = '<span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span>';
		fin = '<span class="glyphicon glyphicon-step-forward pageOff" aria-hidden="true"></span>';
		finOn = '<span class="glyphicon glyphicon-step-forward" aria-hidden="true"></span>';
/*	}
	else
	{
		antDiez = '<img src="'+this.rutaImg+'paginacion/10anterior.gif" class="buttonPag" title="Retroceder 10 páginas" alt="&lt;&lt;" />';
		antDiezOn = '<img src="'+this.rutaImg+'paginacion/anterior10.gif" class="buttonPag" title="Retroceder 10 páginas" alt="&lt;&lt;" />';
		sigDiez = '<img src="'+this.rutaImg+'paginacion/10siguiente.gif" class="buttonPag" title="Avanzar 10 páginas" alt="&gt;&gt;" />';
		sigDiezOn = '<img src="'+this.rutaImg+'paginacion/siguiente10.gif" class="buttonPag" title="Avanzar 10 páginas" alt="&gt;&gt;" />';
		anterior = '<img src="'+this.rutaImg+'paginacion/anterior.gif" class="buttonPag" title="Página actual" alt="·" />';
		anteriorOn = '<img src="'+this.rutaImg+'paginacion/anterior2.gif" class="buttonPag" title="Página anterior" alt="&lt;" />';
		siguiente = '<img src="'+this.rutaImg+'paginacion/siguiente.gif" class="buttonPag" title="Página siguiente" alt="&gt;" />';
		siguienteOn = '<img src="'+this.rutaImg+'paginacion/siguiente2.gif" class="buttonPag" title="Página siguiente" alt="&gt;" />';
		inicio = '<img src="'+this.rutaImg+'paginacion/principio.gif" class="buttonPag" title="Primera página" alt="|&lt;" />';
		inicioOn = '<img src="'+this.rutaImg+'paginacion/principio2.gif" class="buttonPag" title="Primera página" alt="|&lt;" />';
		fin = '<img src="'+this.rutaImg+'paginacion/final.gif" class="buttonPag" title="Última página" alt="&gt|;" />';
		finOn = '<img src="'+this.rutaImg+'paginacion/final2.gif" class="buttonPag" title="Última página" alt="&gt|;" />';
	}*/
	
	if (this.idPanel != 'edi')
		txtNumRegTabular = '(Nº reg. '+this.numRegistros+') ';
	
	if (this.pagina_activa < 9) //Si no hay 10 páginas por detrás
		//No mostramos los enlaces de retroceder 10
		enlace_antdiez = antDiez;
	else //Hay 10 páginas por detrás
	{
		if ((this.pagina_activa - 10) < 0) antdiez = 0;
		else antdiez = this.pagina_activa - 10;

		enlace_antdiez = this.cadenaEnlaces(antdiez);
		enlace_antdiez += antDiezOn+'</a>';
	} //Página activa menor que 0
		
	//Diez siguientes
	if (this.pagina_activa > (pagsATratar - 10))
		enlace_sigdiez = sigDiez;
	else 
	{
		if ((this.pagina_activa+10) > (pagsATratar- 1))
			sigdiez = pagsATratar - 1;
		else 
			sigdiez = this.pagina_activa + 10;
		enlace_sigdiez = this.cadenaEnlaces(sigdiez);
		enlace_sigdiez += sigDiezOn+'</a>';
	}
		
	//Enlace anterior
	if (this.pagina_activa == 0)
		enlace_ant = anterior;
	else 
	{
		enlace_ant = this.cadenaEnlaces(this.pagina_activa-1);
		enlace_ant += anteriorOn+'</a>';
	}	
		
	//Enlace siguiente
	if (this.pagina_activa >= (pagsATratar - 1)) 
		enlace_sig = siguiente;
	else 
	{	
		enlace_sig = this.cadenaEnlaces(this.pagina_activa+1);
		enlace_sig += siguienteOn+'</a>';
	}

	pag_act = parseInt(this.pagina_activa) + 1;
	if (pag_act < 10) 
		enlace_ref = this.textoEnlaces+' 0'+parseInt(this.pagina_activa+1, 10);
	else 
		enlace_ref = this.textoEnlaces +' '+parseInt(this.pagina_activa+1, 10);
	
	if (pagsATratar < 10)
	{
		if (pagsATratar==0)
			enlace_ref += ' de 0'+(pagsATratar+1)+'&nbsp;&nbsp;&nbsp;';
		else
			enlace_ref += ' de 0'+pagsATratar+'&nbsp;&nbsp;&nbsp;';
	}
	else
		enlace_ref += ' de '+pagsATratar+'&nbsp;&nbsp;&nbsp;';
	
	pri = (this.pagina_activa + 1) - Math.ceil(this.tam_marco/2);
	pri = Math.max(0, pri);
	ult = pri + this.tam_marco-1;
	ult = Math.min(ult, (pagsATratar-1));
	if (ult<0) ult = 0; //Si ult es negativo, elegimos el cero
	pri = Math.max(pri,ult-this.tam_marco-1);

	if (this.pagina_activa == pri) 
		principio = inicio+'</a>';
	else 
	{
		principio = this.cadenaEnlaces(0);
		principio += inicioOn+'</a>';
	}	
	if (this.pagina_activa == ult) 
		ultima = fin+'</a>';
	else 
	{
		ultima = this.cadenaEnlaces(this.paginas_totales-1);
		ultima += finOn+'</a>';
	}
	
	enlaces = "";
	if (pagsATratar > 1)
	{
		for(i=pri;i<ult+1;i++) 
		{
			// Le sumamos 1 para que en pantalla aparezca el paginador empezando
			// por 1, pero las capas (páginas) están numeradas a partir del 0
			pag = parseInt(i+1,10);
			if (i<9) 
				pag = '0'+pag;
			if (this.pagina_activa == i)
				enlaces += pag+'&nbsp;';
			else 
			{
				enlaces += this.cadenaEnlaces(i);
				enlaces += pag+'</a>&nbsp;&nbsp;';
			}
		}
	}

	toPage = '';
	totalPag = pagsATratar + 1;
	toPage += 'if (this.value < '+totalPag+') { ';
	toPage += 'if ('+this.nom_variable+'.hayError() == false){';
	if (this.campoHidden != '')
		toPage += 'document.forms[\"'+this.formulario+'\"].'+this.campoHidden+'.value=this.value-1;';
	toPage += this.nom_variable+'.abrir_pagina(this.value-1);';
	if ( (this.esMaestro=='true') && (this.formulario!='') )
	{						
		toPage += 'aviso.mostrarMensajeCargando(\"Cargando\");';
		toPage += 'document.forms[\"'+this.formulario+'\"].target=\"oculto\";';
		toPage += 'document.forms[\"'+this.formulario+'\"].action=\"'+this.actionForm+'\";document.forms[\"'+this.formulario+'\"].submit();'; 
	}
	toPage += '}';
	toPage += 'else { '+this.nom_variable+'.darAviso(); } ';
	toPage += '}';
	toPage += 'else this.value=\" \";';

	selecPag = "&nbsp;&nbsp;<span class='paginador-pag'>Ir a pág.</span> <input style=' box-shadow: 0 1px 1px rgba(0, 0, 0, 3)' class='goToPage ' type='text' id='pag' size='2' ";
	selecPag = selecPag + "onkeypress='javascript:if (isNaN(this.value)) this.value = \"\"'; onChange='javascript:"+toPage+"'>";
	
	if ((this.idPanel == 'vSeleccion') ||(this.idPanel == 'lis') || (this.idPanel == 'edi'))
		nameClass = 'pagesPrimary';
	if ((this.idPanel == 'lisDetalle') || (this.idPanel == 'ediDetalle'))
		nameClass = 'pagesDetail';
	numregHTML = '<div class="'+nameClass+' col-xs-12 col-sm-8 col-md-6 text-left">';
	numregHTML += '<span class="paginador-pag">';
	numregHTML += txtNumRegTabular + enlace_ref;
	numregHTML += '</span>';
	numregHTML +=  '  ' + principio + enlace_antdiez + enlace_ant + enlaces + enlace_sig + enlace_sigdiez + ultima + selecPag;
	numregHTML += '</div>';
		
	enlacesHTML = numregHTML;// + enlacesHTML;
	capa = eval('document.getElementById("'+this.nom_capa_enlaces+'")');
	if ( (capa==null) || (capa===null) )
	{
		//No hacemos nada
	}
	else
		capa.innerHTML = enlacesHTML;
}


function f_oPaginacion_dump ()
{
	return (dump(this));
}


/**
* dump(): Obtiene un string que muestra el estado de un objeto
** 
* La función se inspira en la funcion print_r de PHP, convierte
* un objeto en una cadena que muestra su estado. puede
* combinarse con alert() para depurar errores
* @param Array arr	el vector, vector asociativo u objeto
* @param integer level	nivel de profundidad (opcional)
*/
function dump (arr, level) 
{
	var dumped_text = "";
	if(!level) level = 0;
	if(!level) level = 0;
	
	//The padding given at the beginning of the line.
	var level_padding = "";
	for(var j=0;j<level+1;j++) level_padding += "    ";
	if(typeof(arr) == 'object') //Array/Hashes/Objects
	{
		for(var item in arr) 
		{
			var value = arr[item];
			if(typeof(value) == 'object') //If it is an array,
			{
			   dumped_text += level_padding + "'" + item + "' ...\n";
			   dumped_text += dump(value, level+1);
			}
			else
			{
				dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
			}
		}//Fin for
	}
	else  //Stings/Chars/Numbers etc.
	{
		dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
	}
	return dumped_text;
}// Fin dump