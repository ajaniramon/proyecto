function mostrarOpciones(idCapa)
{
	mostrarCapas(idCapa);
	if ((document.getElementById("breadcrumbs")))
		breadCrumb(idCapa);
}

function cerrarAplicacion(formulario)
{
	aviso = eval('aviso');
	if (document.getElementById('permitirCerrarAplicacion').value!='si') 
	{
		aviso.set('aviso','capaAviso','ALERTA','IGEP-909','Cambios pendientes','Existen datos pendientes de salvar. <br/>SALVE o CANCELE los mismos antes de salir.');
		aviso.mostrarAviso();
	}
	else
	{
		formulario.submit();
	}	
}

function mostrarCapas(idCapa)
{	
	prefijo = idCapa.substring(0,1);
	var capas = document.getElementsByTagName('div');
	var i;
	for(i=0;i<capas.length;i++) 
	{
		prefCapa = capas[i].id.substring(0,1);
		if (prefijo == prefCapa) {			
			capa = eval('document.getElementById("'+capas[i].id+'")');
			capa.style.display = "none";
		}
	}
	capa = eval('document.getElementById("'+idCapa+'")');
	capa.style.display = "block";
}//mostrarCapas

function breadCrumb(idCapa)
{	
	texto = '';	
	idLi = 'W'+idCapa;
	
	if (!(navBar = document.getElementById("breadcrumbs-one"))) return;
	while (navBar.lastChild) navBar.removeChild(navBar.lastChild);
	
	/* Array Pila para guardar el camino */
	var vNavBarContent = new Array();
	
	if (!(liActual = document.getElementById(idLi))) return;
	
	raiz = false;
	while (!raiz) 
	{
		var nodoPadre = liActual.parentNode;
		if (nodoPadre.id == "breadcrumbs-one") break;
		
		if (liActual.tagName == "LI")
		{	
			if (nodoPadre.tagName == "UL") nodoPadre = nodoPadre.parentNode;
			var textoLi = liActual.firstChild;			
			if (textoLi.nodeName=="#text"){
				var nuevoLI = crearLi(textoLi.nodeValue, liActual.id.substring(1, liActual.id.length));				
				vNavBarContent.push(nuevoLI);
			}			
		}
		else if ((liActual.tagName == "DIV"))
		{
			raiz = true;
		}
		liActual = nodoPadre;
	}
	
	while (vNavBarContent.length>0) 
	{
		var hijo = vNavBarContent.pop();
		navBar.appendChild(hijo);
	}
}//breadCrumb

function crearLi(texto, idRef)
{
	var nuevoLI = document.createElement("LI");
	var nuevoA = document.createElement("A");
	
	nuevoA.innerHTML = texto;
	nuevoA.href ='javascript:mostrarOpciones("'+idRef+'");';
	nuevoLI.appendChild(nuevoA);
	
	return nuevoLI;
}//crearLi