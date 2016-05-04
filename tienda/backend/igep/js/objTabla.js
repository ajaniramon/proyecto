/**
* objTabla: Maneja las filas nuevas, para poder distinguir las 
* filas en las que el usuario finalmente introduce datos, de 
* las filas que se habilitan para insertar, pero no se rellenan.
**/
function oTabla(idPanel,nomObjeto)
{
	this.nomObjeto = nomObjeto;
	this.camposErroneos=""; //Guarda en una CADENA la lista de campos erróneos
	this.idPanel = idPanel; // str nombre de la variable del objeto creado
	//Referencia a las filas insertadas (por el usuario)
	this.vFilasComprobar = new Array();
	this.nomForm = "F_"+idPanel;
	this.formulario = eval('document.forms["'+this.nomForm+'"]');

// Variables para el coloreado de las filas
	this.idFilaAnt = '';
	this.estadoFilaAnt = '';
	
	this.vColumnas = new Array();
	this.vEstadosCol = new Array();
	
	this.hayFilaChequeada = false;

	//Privado
	this.cambiarEstado = f_oTabla_cambiarEstado;
	this.seleccionarTodos= f_oTabla_seleccionarTodos;
	this.deseleccionarTodos = f_oTabla_deseleccionarTodos;
	this.deseleccionarTodos2 = f_oTabla_deseleccionarTodos2;
	this.checkFila = f_oTabla_checkFila;
	this.overFila = f_oTabla_overFila;
	this.outFila = f_oTabla_outFila;
	this.ordenarTabla = f_oTabla_ordenarTabla;
	this.columnaEstado = f_oTabla_columnaEstado;
	this.hayFilaSeleccionada = f_oTabla_hayFilaSeleccionada;
}

function f_oTabla_hayFilaSeleccionada()
{	
	formulario = this.formulario;
	for(i=0;i<formulario.length;i++)
	{					
		elemento = eval('formulario.elements[i]');
		id = elemento.id;
		tipo = elemento.type;
		
		if ( (tipo == 'checkbox') && (id.split('_')[0] == 'check') && (elemento.checked) )
		{
			idFila = id.split('check_')[1];
			this.hayFilaChequeada = true;
			return true;
		}
	}
	return false;
}

function f_oTabla_columnaEstado(campo,estado)
{
	this.vColumnas.push(campo);
    this.vEstadosCol.push(estado);
}

function f_oTabla_checkFila(idFila)  //idFila: Tabla1_3
{	
	// CAMBIO DEL ESTADO DE LA FILA
	fila = eval('document.getElementById("'+idFila+'")');
	classFila = fila.className;
	vidFila = idFila.split("_");	
	// Tenemos q saber si estamos en una tabla de ventana de selección o no
	esVentana = 0;
	if (eval('document.getElementById("resBusqueda")'))
		esVentana = 1;
		
	for (var i=0; i<this.formulario.length; i++)
	{
		campo = this.formulario.elements[i];		
		idCampo = String(campo.id); // ej. cam___codigoEstado___Tabla1_5

		// Comprobar si es una ventana de selección
		if (esVentana == 0)
		{
			vIdCampo = idCampo.split('___');
			filaCampo = vIdCampo[2];
		}
		else
		{
			vIdCampo = idCampo.split('_');
			if (vIdCampo[0] == 'vsCheck')
				filaCampo = vIdCampo[1]+'_'+vIdCampo[2];
			else filaCampo = vIdCampo[1];
		}
		prefijo = vIdCampo[0];
		// El campo ha de ser de la fila seleccionada, no ha de ser un campo oculto
		// Solo campos de formulario			
		if (  ((filaCampo == idFila) || (filaCampo == vidFila[1]))&& (campo.type != 'hidden') )
		{
			classCampo = campo.className;
			switch(esVentana)
			{
				case 0: // Tabla normal
					// Tratamos el campo oculto con el valor anterior q tb tiene el class original
					idOculto = 'ant___'+vIdCampo[1]+'___'+filaCampo; // ej. ant___codigoEstado___Tabla1_5
					if (campo.type == 'select-one')
						idOculto = 'lcam___'+vIdCampo[1]+'___'+filaCampo; // los campos ocultos para listas tienen el prefijo 'lcam'
					if (campo.type == 'radio')
					{
						name = this.formulario.elements[i].name;
						vName = name.split('___');
						idOculto = 'ant___'+vName[1]+'___'+vName[2];
					}
					campoOculto = document.getElementById(idOculto);
					classCampoOculto = campoOculto.className;
		
					if (arguments[1]) // Existe un segundo argumento cdo hemos seleccionado el CheckTodos
						estado = arguments[1];
					else
					{ //Cogemos el estado del check correspondiente a la fila
						idCheckFila = "check_"+idFila;
						checkFila = eval('document.getElementById(idCheckFila)');
						if (checkFila.type == 'checkbox')
							estado = checkFila.checked;
					}		
					if (estado) // Si estado=true activamos filas y campos
					{
						this.hayFilaChequeada = true;
						fila.className = classFila+" rowOn";			
						campo.className = classCampoOculto+" rowOn";
					}
					else // Si estado=false desactivamos filas y campos
					{
						classFila = fila.className.replace("rowOn","");
						fila.className = classFila;						
						classCampoOculto = classCampoOculto.replace("rowOn","");		
						campo.className = classCampoOculto;
					}
					if (campo.type == 'radio')//Los radios tiene labels que hay que tratar aparte  
					{
						idLabelRadio = 'l'+campo.id;
						labelRadio = document.getElementById(idLabelRadio);
						if (labelRadio) labelRadio.className = campo.className;								
					}
				break;
				case 1: // Ventana de selección
					if (campo.type == 'checkbox')
					{
						if (campo.checked == true)
						{
							fila.className = 'text rowOn';
							campo.className = 'text tableNoEdit rowOn';
						}
						else
						{
							fila.className = 'text';
							campo.className = 'text tableNoEdit';
						}
					}	
				break;
			}
		}
	}	
}

//	FUNCIONAMIENTO DEL BOTÓN SUPERIOR Q SELECCIONA/DESELECCIONA TODOS LOS CHECKBOX ///
function f_oTabla_seleccionarTodos(selec) 
{
	formulario = this.formulario;
	selTodos = eval('document.getElementById("'+selec+'")');
	nomSelTodos = selTodos.id;	
	estado = selTodos.checked;
	this.hayFilaChequeada = true;
	
	// Vamos a chequear/deschequear todas las filas, según el "seleccionarTodos"
	var vInput = formulario.getElementsByTagName("input");// Vector que contiene todos los componentes 'input'
	for(var i=0;i<vInput.length;i++)
	{
		componente = formulario.getElementsByTagName("input")[i];
		vNombre = componente.id.split('_'); 
		// Checkbox correspondientes a la selección de filas
		if ((componente.type == "checkbox") && ((vNombre[0] == 'check') || (vNombre[0] == 'seleccionarTodo'))) 
		{		
			componente.checked = estado; // Chequeamos las filas
			if (vNombre[0] == 'check') // Vamos a colorear las filas
			{
				idFila = vNombre[1]+'_'+vNombre[2];		
				this.checkFila(idFila,estado);
			}				
		}		
	}
}

// DESELECCIONAMOS TODOS LOS CHECKBOX Y DEJAMOS SOLO EL QUE HA PINCHADO //
// MAESTRO-DETALLE SOLO PUEDE SELECCIONAR UNA FILA PARA MOSTRAR SU DETALLE //
function f_oTabla_deseleccionarTodos(seleccionado) 
{
	formulario = this.formulario;
	checkFila = eval('document.getElementById("'+seleccionado+'")');
	idCheckFila = checkFila.id;	
	estado = checkFila.checked;
	this.hayFilaChequeada = true;
	
	estadoRestoFilas = true;
	if (estado) estadoRestoFilas = false;
	
	// Vamos a chequear/deschequear todas las filas, según el "seleccionarTodos"
	var vInput = formulario.getElementsByTagName("input");// Vector que contiene todos los componentes 'input'
	for(var i=0;i<vInput.length;i++)
	{
		componente = vInput[i];
		vNombre = componente.id.split('_'); 
		// Checkbox correspondientes a la selección de filas
		if ((componente.type == "checkbox") && (vNombre[0] == 'check') && (componente.id != seleccionado)) 
		{
			componente.checked = estadoRestoFilas; // DesChequeamos las filas
			if (vNombre[0] == 'check') // Vamos a colorear las filas con el estado original
			{
				idFila = vNombre[1]+'_'+vNombre[2];		
				this.checkFila(idFila, estadoRestoFilas);
			}				
		}		
	}
}//Fin deseleccionarTodos


// Función que deselecciona TODOS los checkbox
function f_oTabla_deseleccionarTodos2 (seleccionado) 
{
	formulario = this.formulario;
	checkFila = eval('document.getElementById("'+seleccionado+'")');
	idCheckFila = checkFila.id;	
	estado = checkFila.checked;
	this.hayFilaChequeada = true;
	estadoRestoFilas = false;
	
	var vInput = formulario.getElementsByTagName("input");// Vector que contiene todos los componentes 'input'
	for(var i=0; i<vInput.length; i++)//Para cada elemento input del formulario
	{
		componente = vInput[i];
		vNombre = componente.id.split('_'); 
		// Checkbox correspondientes a la selección de filas
		if ((componente.type == "checkbox") && (vNombre[0] == 'check') && (componente.id != seleccionado) && (componente.checked == true)) 
		{
			componente.checked = false; // DesChequeamos las filas
			if (vNombre[0] == 'check') // Vamos a colorear las filas con el estado original
			{
				idFila = vNombre[1]+'_'+vNombre[2];		
				this.checkFila(idFila, false);
			}				
		}		
	}
}//Fin deseleccionarTodos2


function f_oTabla_cambiarEstado(valor, campo, nomCampoEstado) 
{
	formulario = this.formulario;
	yaExiste=false;
    for(i=0; i<this.vFilasComprobar.length; i++)
    {
    	if (this.vFilasComprobar[i]==nomCampoEstado.split("_")[2])
    	{
    		yaExiste=true;
    		break;
    	}	    	
    }
    if (!yaExiste) this.vFilasComprobar.push(nomCampoEstado.split("_")[2]);
	eval('formulario.'+nomCampoEstado+'.value = "'+valor+'"');
}

function f_oTabla_overFila(fila)
{
	this.idFilaAnt = fila.id;
	this.estadoFilaAnt = fila.className;
// Si no está activada ni está borrada la activamos
	vClassFila = fila.className.split(" ");
	vClassFila[1] = (vClassFila[1] != undefined)? vClassFila[1] : '';
	vClassFila[2] = (vClassFila[2] != undefined)? vClassFila[2] : '';
	if ( ((vClassFila[1] != "rowOver") && (vClassFila[1] != "rowDeleted")) || ((vClassFila[2] != "rowOver") && (vClassFila[2] != "rowDeleted")) )
		fila.className = "text "+vClassFila[1]+" rowOver";
}

function f_oTabla_outFila(fila)
{
	check = "check_"+fila.id;
	formulario = this.formulario;

	if (vClass[2] == 'rowOver')
		eval('fila.className = "'+this.estadoFilaAnt+'"');
	
	/*if ((formulario != undefined) && (eval('formulario.'+check))) 
	{
		// Salimos d una fila chequeada
 		vClass = fila.className.split(" ");
	    vClass[1] = (vClass[1] != undefined)? vClass[1] : '';
    	vClass[2] = (vClass[2] != undefined)? vClass[2] : '';
	 	if (eval('formulario.'+check+'.checked')) 
	 	{
	 		fila.className = "text "+vClass[1]+" rowOn";;
	 	}
	 	else 
	 	{
	 		if ( (vClass[1] == "rowDeleted") || (vClass[2] == "rowDeleted"))
	 		{
				 eval('fila.className = "'+this.estadoFilaAnt+'"');
	 		}
	 		else  fila.className = "text "+vClass[1]+" "+vClass[2];
	 	}
	 } 
	 else 
	 {
	 	fila.className = "text "+vClass[1]+" "+vClass[2];
	 }*/
}

// DESELECCIONAMOS TODOS LOS CHECKBOX Y DEJAMOS SOLO EL QUE HA PINCHADO //
// MAESTRO-DETALLE SOLO PUEDE SELECCIONAR UNA FILA PARA MOSTRAR SU DETALLE //
function f_oTabla_ordenarTabla(claseManejadora, numCol, orden) {
	
	var cadenaOrden = '';
	if (arguments[2])
	{
		orden = arguments[2];
		cadenaOrden = '&IGEPord='+orden;	
	}
	aviso.mostrarMensajeCargando('Cargando');
	formulario = this.formulario;
	formulario.target='oculto';
	var accion ='phrame.php?action=ordenarTabla&IGEPclaseM='+claseManejadora+'&IGEPcol='+numCol+cadenaOrden;
	formulario.action = accion;
	formulario.submit();
}