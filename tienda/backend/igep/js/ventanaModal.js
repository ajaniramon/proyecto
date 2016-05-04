function openModal(url, paramsSource, width, height, resizable, scroll)
{		
	// cálculo de la posición de la ventana
	heightPosition = screen.height*0.6;
	widthPosition = screen.width*0.9;
	LeftPosition = (screen.width) ? (screen.width-widthPosition)/2 : 0;
	TopPosition = (screen.height) ? (screen.height-heightPosition)/2 : 0;
	
	if ((resizable == undefined) || (resizable == null))
		resizable = 'no';
	if ((scroll == undefined) || (scroll == null))
		scroll = 'no';		
	
	//Cambiamos los settings para vindow.open
	settings = 'top='+TopPosition+',left='+LeftPosition+',scrollbars=yes,resizable='+resizable+',width='+width+',height='+height; 
	settings = settings+',menubar=no,toolbar=no,location=no';		
		
	//Creamos en el DOM del padre (no del iframe) una referencia a la ventana hija
	var vModal = window.open(url, 'M', settings, null);		
	vModal.dialogArguments = paramsSource;
	parent.window.vModal = vModal;
	var timer = setInterval(
		function() 
		{   
			if(vModal.closed) 
			{
				clearInterval(timer);
				returnValue = vModal.returnValue;				
				formulario = paramsSource.formulario;
				action = paramsSource.returnPath;		
				parent.window.document.forms[formulario].action = action;
				parent.window.document.forms[formulario].target = 'oculto';
				parent.window.document.forms[formulario].submit();
			}				
			else
			{
				//Definida en el caso de ser modal en CWVentana
				vModal.blur();
				vModal.focus();
			}				
		}, 1000);
}

function returnModal(accion)
{
	// Se ejecuta en la modal    
	switch(accion)
	{
		case 'submit':
				window.returnValue = 'He guardado en la ventana Modal';		
		break;
		case 'cancel':
			window.returnValue = 'He cancelado la ventana Modal';		
		break;		
		case 'accion':
			window.returnValue = 'ACCION';    		
		break;	
		default:
			window.returnValue = 'NPI';
	}	
	window.close();
}