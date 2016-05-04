function creaAjax()
{
	var objetoAjax=false;
  	objetoAjax = new XMLHttpRequest();

  	return objetoAjax;
}

function FAjax (url, capa, valores, asincrono)
{
   	var ajax = creaAjax();
   	var capaContenedora = document.getElementById(capa);
    //var carga = document.getElementById(carga);
 		 	
    if(asincrono==null){
    	asincrono = true;	
    }
    
   	ajax.open ('POST', url, asincrono);
    ajax.onreadystatechange = function() 
    {
 		if (ajax.readyState==1) 
  		{
        	capaContenedora.innerHTML="<img src='img/cargando.gif'/>";
        }
        else if (ajax.readyState==4)
        {
        	if(ajax.status==200)
           	{
        		capaContenedora.innerHTML=ajax.responseText;    		
           	}
            else if(ajax.status==404)
            {
		   		capaContenedora.innerHTML = "La direccion existe";
            }
            else
            {
            	capaContenedora.innerHTML = "Error: "+ajax.status;
            }
        }	
    };
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(valores);
    
    return;
}

function FAjaxScript (url, valores, asincrono)
{
   	var ajax = creaAjax();
	 	
    if(asincrono==null){
    	asincrono = true;	
    }
    
   	ajax.open ('POST', url, asincrono);
    ajax.onreadystatechange = function() 
    {
    	if (ajax.readyState==4)
        {
        	if(ajax.status==200)
           	{
        		eval(ajax.responseText);    		
           	}
        }	
    };
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(valores);
    
    return;
}

function seleccionarConexion() {
    conexion = document.getElementById('conexion').value;

    FAjax('include/DBConnection.php', 'panelGenaro', 'conexion='+conexion, false);
    FAjaxScript('include/DBConnectionORA.php', 'conexion='+conexion, false);
}

function limpiarResultado() {

	resultado = document.getElementById('resultado');
	resultado.innerHTML='';
} 

// Manejo de los DIVs 

function mostrarDIV(nomdiv) 
{	
	div = document.getElementById(nomdiv);
	div.style.display = '';

	if(div.id == "personalizaCamposAJAX")
	{
		document.getElementById("menu").style.display='none';
	}	
}

function ocultarDIV(nomdiv) 
{
	div = document.getElementById(nomdiv);
	div.style.display='none';

	if(div.id == "personalizaCamposAJAX")
	{
		document.getElementById("menu").style.display='';
	}	
}

function btnAceptarDiv()
{
	if(document.cambiosEnParametrizar)	
	{ 
		compruebaCampos();		
		alert("Se han REALIZADO cambios en los campos de la pantalla.");	
		guardarParams("frmPersonalizaCampos");
	} 
	ocultarDIV("personalizaCamposAJAX");
	mostrarDIV("panel");
}

function btnCancelarDiv()
{	
	if(document.cambiosEnParametrizar)	
	{ 
		if(confirm("Va a salir del formulario. Los cambios no seran guardados."))
		{		
			cargaParams("frmPersonalizaCampos");
			ocultarDIV("personalizaCamposAJAX");
			mostrarDIV("panel");
		}				
	}
	else
	{
		ocultarDIV("personalizaCamposAJAX");
		mostrarDIV("panel");
	}
}

function btnLimpiarDiv()
{	
	alert("Se han BORRADO los datos.");	
	document.getElementById('frmPersonalizaCampos').reset();
	document.getElementById("flagCambios").value = "";	
	document.cambiosEnParametrizar = false;	
	ocultarDIV("personalizaCamposAJAX");
	mostrarDIV("panel");
	parametrosCargados(false);
}

function parametrosCargados(cargaValores)
{
	var SIN_PARAM = "Sin Parametrizar";
	var CON_PARAM = "Parametros definidos";
	var SIN_STYLE = "sinParametrizar";
	var CON_STYLE = "parametrizado";
	var SIN_IMG = "img/warning.png";
	var CON_IMG = "img/info.png";
	
	if(cargaValores)
	{
		if(document.getElementById('formPatronTabularRegistro').style.display=='block')
		{
			establecerImagenEstilo('ParametrizarSimple',CON_STYLE, CON_IMG, CON_PARAM);
		}
		else
		{
			establecerImagenEstilo('ParametrizarMD', CON_STYLE, CON_IMG, CON_PARAM);
		}
	}
	else
	{
		if(document.getElementById('formPatronTabularRegistro').style.display=='block')
		{
			establecerImagenEstilo('ParametrizarSimple', SIN_STYLE, SIN_IMG, SIN_PARAM);
		}
		else
		{
			establecerImagenEstilo('ParametrizarMD', SIN_STYLE, SIN_IMG, SIN_PARAM);
		}
	}
}

function establecerImagenEstilo(control,clase,imagen,title){
	document.getElementById(control).title = title;
	document.getElementById(control).className = clase;
	document.getElementById(control).src = imagen;
}

var params;
function btnParametrizarCampos(valorTabla)
{		
	if (document.getElementById(valorTabla).value == "")
	{
		alert("Seleccione una tabla de la BBDD.");
	}		
	else
	{			
		ocultarDIV("panel");
			
		if (document.getElementById("flagCambios").value != document.getElementById(valorTabla).value)
		{			
			FAjax('defineOpciones.php', 'personalizaCamposAJAX',  'nombreTabla='+document.getElementById(valorTabla).value+
					'&conexion='+document.getElementById('conexion').value);

			mostrarDIV("personalizaCamposAJAX");
			document.getElementById("flagCambios").value = document.getElementById(valorTabla).value;
			params = guardarParams("frmPersonalizaCampos");
		}
		else if (document.getElementById("flagCambios").value == document.getElementById(valorTabla).value)
		{
			mostrarDIV("personalizaCamposAJAX");		
		}			
	}
}

function guardarParams(idFormulario)
{
	var controles = document.getElementById(idFormulario).elements;
	var data = [];
	for(var i=0;i<controles.length;i++)
	{
		data.push(controles[i].value);
	}
	params = data;
	document.cambiosEnParametrizar = false;
	parametrosCargados(true);
}

function cargaParams(idFormulario)
{
	if(params==null)
	{
		return;
	}
	
	var controles = document.getElementById(idFormulario).elements;
	var data = params;
	for(var i=0;i<controles.length;i++)
	{
		controles[i].value = data[i];
	}
	document.cambiosEnParametrizar = false;
}

// OnLoad de la pagina
function onLoadPagina()
{
	// inicializamos el flag a vacio
	document.getElementById("flagCambios").value = "";	

	ocultarDIV('personalizaCamposAJAX');
}

// Recorrer Formulario frmPersonalizaCampos 
function valoresFormulario()
{
	var valoresCampos= new Array();	
	var valorRegistro= new Array();
	var valorAux= new Array();

	if (document.getElementById("frmPersonalizaCampos") != null)		
	{
		var frm = document.getElementById("frmPersonalizaCampos");
	
		var count=1;
		var countaux=0;
		
		for (var i=0;i<frm.elements.length;i++)
		{
			if (frm.elements[i].type != "button")
			{		
				valorRegistro.push(frm.elements[i].value);
			}		
			count++;
			
			if (count > 9)
			{
				//Eliminamos el valor del tipo, añadido en el array				
				valorRegistro.splice(1,1);				
				if (countaux == 0){	
					valorAux.push(valorRegistro.splice(0,1)+'==>');
					countaux++;
				}else{
					valorAux.push('{'+valorRegistro.splice(0,1)+'==>');
				}
				valorAux.push('titVal=>'+valorRegistro.splice(0,1));
				valorAux.push('tamVal=>'+valorRegistro.splice(0,1));				
				//valorAux.push('maskVal=>'+valorRegistro.splice(0,1));
				valorAux.push('reqVal=>'+valorRegistro.splice(0,1));								
				valorAux.push('calVal=>'+valorRegistro.splice(0,1));
				valorAux.push('visibleVal=>'+valorRegistro.splice(0,1));								
				valorAux.push('componente=>'+valorRegistro.splice(0,1));
				valorAux.push('defVal=>'+valorRegistro.splice(0,1));
				
				valoresCampos.push(valorAux);
	
				// Inicializamos valores					
				count = 1;
				valorRegistro=[];
				valorAux=[];			
			}
		}	

		return(valoresCampos);
	}
	else 
		return '';
}


// EliminaciÃ³n de modulos desde Genaro 
function eliminaModulo(modulo)
{
	var valAuxHijos;
	var valAuxPadres;	

	if(document.getElementById('nombreModulElim').value =="")
	{
		alert('Debe seleccionar una clase para poder eliminarla.');
	}
	else{		
	
		// Llamamos a la función que nos indica si tenemos o no valores hijos o padres de este modulo.	
		FAjax('eliminaModulos.php','divValOculto','nombreModulElim='+document.getElementById('nombreModulElim').value+'&opcion=1');	
		
		alert('Se lanzaran unas comprobaciones previas a la eliminacion de la clase.');	
	
		// Recogemos la información obtenida de los procesos previos al borrado
		valAuxHijos = document.getElementById('valOcultoHijo').value;			
		valAuxPadres = document.getElementById('valOcultoPadre').value;
	
		if (valAuxHijos == 1){
			//tiene Hijo, avisar que se va a eliminar, si OK, se sigue, sinÃ³ se cancela.	
		    if(confirm("Existe una clase Detalle asociada,\n ¿Continuar con la eliminación?")){
				if (valAuxPadres == 1){
					alert ('Existe una clase Maestro asociada, no se puede eliminar la clase seleccionada.');				
				}
				else{
					alert ('NO Existe una clase Maestros asociada.\nSe procede a su eliminación');
					FAjax('eliminaModulos.php','divValOculto','nombreModulElim='+document.getElementById('nombreModulElim').value+'&opcion=2');
					alert ('La clase se ha eliminado.');
					//window.location.reload();	
					FAjax('include/listaModulos.php', 'nombreModulElim', '');
				}				
			}	    			
		}
		else if (valAuxHijos == 0){
			alert('NO Existe una clase Detalle asociado.');
	
			if (valAuxPadres == 1){
				alert ('Existe una clase Maestro asociada, no se puede eliminar la clase seleccionada.');				
			}
			else{
				alert ('NO Existe una clase Maestros asociada.\nSe procede a su eliminación');
				FAjax('eliminaModulos.php','divValOculto','nombreModulElim='+document.getElementById('nombreModulElim').value+'&opcion=2');
				alert ('La clase se ha eliminado.');
				//window.location.reload();	
				FAjax('include/listaModulos.php', 'nombreModulElim', 'defecto=1');
			}			
		}						
	}
}

// FIN 

// Funciones de comprobaciÃ³n
function validaInteger(contenido)  // Dani -> Valida que los carácteres introducidos en un campo tipo "integer" sean correctos. 
{       

        no_es_numero = isNaN(contenido);

        if (no_es_numero)
        {                       
                alert('No se pueden introducir caracteres no numéricos en elementos numéricos. Porfavor, reví­se el valor del campo.');
                exit();
        }        
}

function validaText(contenido)  // Dani -> Valida que los carácteres introducidos en un campo tipo "text" sean correctos. 
{       

        var texto = new String(contenido.indexOf(",",0));

        if (texto >= 0) // Si es 0 o mayor es que ha encontrado una 'coma' en el texto.
        {                       
                alert('El caracter introducido en el campo de texto es incorrecto. Porfavor, revíselo.');
                exit();
        }
}

function validaFloat(contenido)  // Dani -> Valida que los carácteres introducidos en un campo tipo "float" sean correctos. 
{       
        var texto = new String(contenido.indexOf(",",0));

        if (texto >= 0) // Si es 0 o mayor es que ha encontrado una 'coma' en cadena convertida.
        {                       
                alert('El caracter introducido en el campo float es incorrecto. Porfavor, reví­selo.');
                exit();
        }
}

function validaDate(Cadena)  // Dani -> Valida que el formato introducido en un campo tipo "date" sea correcto. 
{       
        
    var flag = 0;

    if (Cadena != ""){           
	    var Fecha= new String(Cadena);  // Crea un string
	    //var RealFecha= new Date();      // Para sacar la fecha de hoy
	    // Cadena Año
	    var Ano= new String(Fecha.substring(Fecha.lastIndexOf("/")+1,Fecha.length));
	    //alert(Ano);
	    // Cadena Mes
	    var Mes= new String(Fecha.substring(Fecha.indexOf("/")+1,Fecha.lastIndexOf("/")));
	    //alert(Mes);
	    // Cadena Día
	    var Dia= new String(Fecha.substring(0,Fecha.indexOf("/")));
	    //alert(Dia);
	
	    // Valido el año
	    if (isNaN(Ano) || Ano.length<4 || parseFloat(Ano)<1900 || Ano == ""){
	    flag = 1;         
	    }
	    // Valido el Mes
	    if (isNaN(Mes) || parseFloat(Mes)<1 || parseFloat(Mes)>12 || Mes == ""){
	                flag = 1;               
	    }
	    // Valido el Dia
	    if (isNaN(Dia) || parseInt(Dia, 10)<1 || parseInt(Dia, 10)>31 || Dia == ""){
	                flag = 1;               
	    }
	    if (Mes==4 || Mes==6 || Mes==9 || Mes==11 || Mes==2) { 
	                if (Mes==2 && Dia > 28 || Dia>30) {
	                           flag = 1;
	                }
	    }
	
	    if (flag == 1)
	    {
			alert("Fecha incorrecta. Formato (dd/mm/yyyy)");
	        exit();
	    }
    }
}



function compruebaCampos()  // Dani -> Recorre el form y deriva cada elemento a la función de validación correspondiente.
{

        var frm = document.getElementById("frmPersonalizaCampos");       
        var i = 0;        
        
        for (i=0; i<frm.elements.length; i++)  // Recorro el form
        {                       
                // Validación para campos integer
                if ((frm.elements[i].id.substr(0, 9) == "tipoCampo") && (frm.elements[i].value == 'integer')) 
                {
                	validaText(frm.elements[i+1].value); // Validamos el titulo 
                	validaInteger(frm.elements[i+2].value); // Campo numerico que indica el tamaño del input de la pantalla
                   	validaInteger(frm.elements[i+3].value); // Estos campos pueden ser para valores numÃ©ricos (integer) o alfanumÃ©ricos (text)                   
                }

                // Validación para campos de fecha                                             
                if ((frm.elements[i].id.substr(0, 9) == "tipoCampo") && (frm.elements[i].value == 'date')) 
                {   
                	validaText(frm.elements[i+1].value); // Validamos el titulo                                                    
                	//validaDate(frm.elements[i+1].value);                   
                }

                // Validación para campos de fecha                             
                if ((frm.elements[i].id.substr(0, 9) == "tipoCampo") && (frm.elements[i].value == 'timestamp')) 
                {                                         
                	validaText(frm.elements[i+1].value); // Validamos el titulo              
                	//validaDate(frm.elements[i+1].value);                    
                }                

                // Validación para campos de texto                             
                if ((frm.elements[i].id.substr(0, 9) == "tipoCampo") && (frm.elements[i].value == 'text')) 
                {                                      
                	validaText(frm.elements[i+1].value); // Validamos el titulo 
                	validaInteger(frm.elements[i+2].value); // Campo numerico que indica el tamaño del input de la pantalla
                    validaText(frm.elements[i+3].value);
                }               
                // Validación para campos float                             
                if ((frm.elements[i].id.substr(0, 9) == "tipoCampo") && (frm.elements[i].value == 'float')) 
                {   
                	validaText(frm.elements[i+1].value); // Validamos el titulo 
                	validaInteger(frm.elements[i+2].value); // Campo numerico que indica el tamaño del input de la pantalla
                	validaFloat(frm.elements[i+3].value);
                }       
        }       
}

// FIN Comprobaciones

function enviarFormulario(tipo) {	
	
	valoresCampos = valoresFormulario();
		
	switch(tipo) {
	
		case 'TABULAR_REGISTRO':
			FAjax('generaCodigo.php', 'resultado', datosFormularioRegistro(valoresCampos));
	        break;
			
		case "MAESTRO_DETALLE":
			FAjax('generaCodigo.php', 'resultado', datosFormularioMD(valoresCampos));
			break;
			
		case "MAESTRO_N_DETALLES":
			FAjax('generaCodigo.php', 'resultado', datosFormularioMDExtendido(valoresCampos));
			break;		
	}
}

function datosFormularioRegistro(valoresCampos){
	return 'nombreModulo='+document.getElementById('nombreModulo').value+
			'&nombreClase='+document.getElementById('nombreClase').value+
			'&nombreTabla='+document.getElementById('nombreTabla').value+
			'&patron='+document.getElementById('patronSeleccionado').value+
			'&tipo='+document.getElementById('tipoDePatron').value+
		    '&conexion='+document.getElementById('conexion').value+
		    '&valoresCampos='+valoresCampos;
}

function datosFormularioMD(valoresCampos){
	var tag = '';
	var ERROR_SGDB = document.getElementById('ERROR_SGBD').value;
	
	if(ERROR_SGDB==1){
		tag = '_';
	}
	
	return 'nombreModulo='+document.getElementById('nombreModuloMD').value+
			'&nombreClaseMaestro='+document.getElementById('nombreClaseMaestroN').value+
			'&nombreTablaMaestro='+document.getElementById('nombreTablaMaestro').value+
			'&primaryKeyMaestro='+document.getElementById(tag + 'primaryKeyMaestro').value+
			'&nombreClaseDetalle1='+document.getElementById('nombreClaseDetalle1').value+
			'&nombreTablaDetalle1='+document.getElementById('nombreTablaDetalle1').value+
			'&foreignKeyDetalle1='+document.getElementById(tag + 'foreignKeyDetalle1').value+
			'&patronMaestro='+document.getElementById('patronMaestro').value+
			'&patronDetalle1='+document.getElementById('patronDetalle1').value+
			'&tipo='+document.getElementById('tipoDePatronMD').value+
		    '&conexion='+document.getElementById('conexion').value+
		    '&valoresCampos='+valoresCampos;
}

function datosFormularioMDExtendido(valoresCampos){
	var tag = '';
	var ERROR_SGDB = document.getElementById('ERROR_SGBD').value;
	
	if(ERROR_SGDB==1){
		tag = '_';
	}
	
	var numDetalles = document.getElementById('numeroDeDetalles').value;
	var params = '';
	
	params = 'nombreModulo='+document.getElementById('nombreModuloMD').value+
			'&nombreClaseMaestro='+document.getElementById('nombreClaseMaestroN').value+
			'&nombreTablaMaestro='+document.getElementById('nombreTablaMaestro').value+
			'&primaryKeyMaestro='+document.getElementById(tag+'primaryKeyMaestro').value+
			'&patronMaestro='+document.getElementById('patronMaestro').value+
			'&numeroDeDetalles='+document.getElementById('numeroDeDetalles').value+
			'&tipo=MAESTRO_N_DETALLES'+
		    '&conexion='+document.getElementById('conexion').value+
		    '&valoresCampos='+valoresCampos;
	
	for (var i=1; i<=numDetalles; i++) {
		params += 
			'&nombreClaseDetalle'+i+'='+document.getElementById('nombreClaseDetalle'+i).value+
			'&nombreTablaDetalle'+i+'='+document.getElementById('nombreTablaDetalle'+i).value+
			'&foreignKeyDetalle'+i+'='+document.getElementById(tag+'foreignKeyDetalle'+i).value+
			'&patronDetalle'+i+'='+document.getElementById('patronDetalle'+i).value;
	}	
	
	return params;
}

function errorSGBD(){
	alert('Ha seleccionado un motor de datos ORACLE, asegurese de que tiene acceso al diccionario de descripción de la tabla. Recomendamos utilizar el usuario propietario.');
	document.getElementById('ERROR_SGBD').value = "1";
	transformarControl('primaryKeyMaestro', 'input');
	
	/*numDetalles = document.getElementById('numeroDeDetalles').value;
	for (var i=1; i<=numDetalles; i++){
		//document.getElementById('ck_nombreTablaDetalle'+i).style.display="none";
		transformarControl('foreignKeyDetalle'+i, 'input');
		document.getElementById('ck_foreignKeyDetalle'+i).style.display="none";
	} */
}

function okSGBD(){
	document.getElementById('ERROR_SGBD').value = "0";
	restablecerControl('primaryKeyMaestro');
	
	/*numDetalles = document.getElementById('numeroDeDetalles').value;
	for (var i=1; i<=numDetalles; i++){
		//document.getElementById('ck_nombreTablaDetalle'+i).style.display="";
		restablecerControl('foreignKeyDetalle'+i);
		document.getElementById('ck_foreignKeyDetalle'+i).style.display="";
	} */
}

function transformarControl(controlId, tipo){
	var	tag  = "_";
	
	if(document.getElementById(tag+controlId)==null){
		var control = document.getElementById(controlId);
		var controlPadre = control.parentNode; 
		var controlNuevo = document.createElement(tipo);
		
		control.style.display="none";
		controlNuevo.id = tag + controlId;
		controlPadre.appendChild(controlNuevo);
	}
	else{
		var control = document.getElementById(controlId);
		var controlNuevo = document.getElementById(tag+controlId);
		
		control.style.display="";
		controlNuevo.style.display="";
	}
}

function convertirControl(controlId, tipo){	
	var control = document.getElementById(controlId);
	if(control!=null){
		var controlPadre = control.parentNode; 
		controlPadre.removeChild(control);
		
		var controlNuevo = document.createElement(tipo);
		controlNuevo.id = controlId;
		controlPadre.appendChild(controlNuevo);
		
		var ckControl = document.getElementById("ck_"+controlId);
		controlPadre.removeChild(ckControl);
		controlPadre.appendChild(ckControl);
	}
}

function restablecerControl(controlId){
	var tag  = "_";
	document.getElementById(controlId).style.display="";
	if(document.getElementById(tag+controlId)!=null){
		document.getElementById(tag+controlId).style.display="none";
	}
}


function seleccionDetalles(numdetalles) {

    conexion = document.getElementById('conexion').value;
    FAjax('seleccionDeNDetalles.php', 'definicionDeDetalles', 'numeroDeDetalles='+numdetalles+'&conexion='+conexion, false);
    document.getElementById('nombreTablaMaestro').value = '';
    
    if(document.getElementById('ERROR_SGBD').value==1){
		for (var i=1; i<=numdetalles; i++){
			transformarControl('foreignKeyDetalle'+i, 'input');
			document.getElementById('ck_nombreTablaDetalle'+i).style.display="none";
			document.getElementById('ck_foreignKeyDetalle'+i).style.display="none";
		} 
	}
    else{
		for (var i=1; i<=numdetalles; i++){
			document.getElementById('ck_nombreTablaDetalle'+i).style.display="";
			document.getElementById('ck_foreignKeyDetalle'+i).style.display="";
		} 
    }
    	
}

function checkNombreTablaDetalle(control){
	var numID = control.id.replace('ck_nombreTablaDetalle','');
	var nombreTabla = document.getElementById('nombreTablaMaestro').value;
	var conexion = document.getElementById('conexion').value;
	var controlCK = document.getElementById('ck_foreignKeyDetalle'+numID);
	
	if(control.checked){
		FAjax('include/listaTablasFK.php', control.id.replace("ck_",""), 'conexion='+conexion+'&allTables='+(control.checked?1:0));
		controlCK.checked=true;
		controlCK.onchange();
		controlCK.style.display="none";
	}
	else{
		FAjax('include/listaTablasFK.php', control.id.replace("ck_",""), 'conexion='+conexion+'&nombreTabla='+nombreTabla);
		controlCK.checked=false;
		controlCK.onchange();
		controlCK.style.display="";
	}
}

function checkForeignKeyDetalle(control){
	var numID = control.id.replace('ck_foreignKeyDetalle','');
	var nombreTabla = document.getElementById('nombreTablaDetalle'+numID).value;
	var conexion = document.getElementById('conexion').value;
	
	if(control.checked){
		convertirControl(control.id.replace("ck_",""), "input");
	}
	else{
		convertirControl(control.id.replace("ck_",""), "select");
		FAjax('include/listaFKs.php', control.id.replace("ck_",""), 'conexion='+conexion+'&nombreTabla='+nombreTabla);
	}
}


// Lista PK's 

function listaPKs(){
	var nombreTabla = document.getElementById('nombreTablaMaestro').value;
	var conexion = document.getElementById('conexion').value;

	//Listamos las PK's	
	//FAjax('include/listaPKs.php', 'primaryKeyMaestro', 'nombreTabla='+nombreTabla+'&conexion='+conexion);
	FAjaxScript('include/listaPKs.php', 'nombreTabla='+nombreTabla+'&conexion='+conexion+'&control='+'primaryKeyMaestro');

	//Lista Tablas con FK sobre la tabla seleccionada como maestro
	// DEBE SER UN BUCLE EN FUNCIÓN DE LA CANTIDAD DE DETALLES QUE TENGAMOS. PARA LAS PRUEBAS USAMOS COMO PANEL DESTINO "nombreTablaDetalle1"
	// Recuperamos el numero de detalles que se han generado
	
	var numDetalles = document.getElementById('numeroDeDetalles').value;

	for (var i=0; i<numDetalles; i++){
		FAjax('include/listaTablasFK.php', 'nombreTablaDetalle'+(i+1), 'nombreTabla='+nombreTabla+'&conexion='+conexion);
		//FAjax('include/listaFKs.php', 'foreignKeyDetalle'+(i+1), 'conexion='+conexion);
		//FAjaxScript('include/listaFKs.php','nombreTabla='+nombreTabla+'&conexion='+conexion+'&control='+'foreignKeyDetalle'+numID);
	} 	
	
}
// Fin 


//Lista FK's 

function listaFKs(numID){
	var nombreTabla = document.getElementById('nombreTablaDetalle'+numID).value;
	var conexion = document.getElementById('conexion').value;
	
	FAjax('include/listaFKs.php', 'foreignKeyDetalle'+numID, 'nombreTabla='+nombreTabla+'&conexion='+conexion);
	//FAjaxScript('include/listaFKs.php','nombreTabla='+nombreTabla+'&conexion='+conexion+'&control='+'foreignKeyDetalle'+numID);
}
// Fin