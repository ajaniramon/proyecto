// Para poder activar las capas del detalle después d habernos ido a la pestanya de búsqueda
var paneles = new Array();
var pestanyas = new Array();

function oPestanyas(nom) 
{
	this.nom = nom; // str nombre de la variable del objeto creado
	this.tipo = ''; // listado(lis), busqueda (fil), ficha (edi)
	this.panel = ""; // panel donde esta la pestanya
	this.vPestanyas = new Array(); //guardaremos las pestanyas q queremos recorrer
	this.pestanyaActiva = 0;
	this.numPestanyas = 0;

	//metodos
	this.addPestanya = faddPestanya;
	this.activarPanel = factivarPanel;
}

function faddPestanya (tipo, panel) 
{
	//Añadimos al vector las diferentes pestanyas de ese pestanyero
	this.numPestanyas = this.vPestanyas.push([tipo,panel]);
}//Fin método faddPestanya

function factivarPanel (panel, pestanya)
{	
	src = pestanya.src;
	if (/on.gif/.test(src))
	{ }
	else
	{
		for (var i=0;i<this.vPestanyas.length; i++)//Recorremos el vector de pestanyas
		{	
			tipo=this.vPestanyas[i][0];
			panelPestanya=this.vPestanyas[i][1];
			if (panelPestanya==panel)
			{		
				capaPestanya = eval('document.getElementById("'+panelPestanya+'")');
				capaPestanya.style.display = "block";
				
				nomPestanya = 'pest_'+tipo+'_'+this.nom;
				if (eval('document["'+nomPestanya+'"]'))
				{
					imagen = eval('document["'+nomPestanya+'"]');
					imagen.src = imagen.src.replace("_off","_on");
					document.getElementById(nomPestanya).className = 'tab_on';
				}
			}
			else
			{
				capaPestanya = eval('document.getElementById("'+panelPestanya+'")');
				capaPestanya.style.display = "none";	
				nomPestanya = 'pest_'+tipo+'_'+this.nom;
				if (eval('document["'+nomPestanya+'"]'))
				{
					imagen = eval('document["'+nomPestanya+'"]');
					// Evitamos las pestañas que aún no han sido activadas, esto ocurre en el caso de un tres modos.
					vSrc = imagen.src.split('trans.gif');
					if (vSrc.length == 1)
					{
						imagen.src = imagen.src.replace("_on","_off");	
						document.getElementById(imagen.id).className = 'tab_off';
					}
				}
			}
		}
	}
}

/**********************************************************************************************/
/**********		TRATAMIENTO PANELES MAESTRO/DETALLE					************/
/**********************************************************************************************/

function mostrarPanel()
{
	// Mostramos el panel dl detalle
	for(i=0;i<paneles.length;i++)
	{
		if (paneles[i] != 'P_fil')
		{
			capaPestanya = eval('document.getElementById("'+paneles[i]+'")');
			capaPestanya.style.display = "block";
		}
	}
		
// Activamos la pestanya correspondiente al panel
	var images = document.getElementsByTagName('img');
	for(i=0;i<pestanyas.length;i++) {
		vImagenes = pestanyas[i].split('=>');
		ruta = vImagenes[0];
		for(j=0;j<images.length;j++)
		{
			if (vImagenes[1] == images[j].id) 
			{
				document.getElementById(vImagenes[1]).className = 'tab_on';				
				images[j].src = vImagenes[0];
				break;
			}
		}
	}
}

function ocultarPanel(sufPanel)
{
	var capas = document.getElementsByTagName('div');
	
	for(i=0;i<capas.length;i++) {
		// ocultamos los paneles del detalle
		idCapa = capas[i].id;
		// Capa "detalles" que corresponde con las solapas de un detalle.
		if (idCapa == 'detalles')
		{
			capa = eval('document.getElementById("'+idCapa+'")');
			capa.style.display = 'none';
			paneles.push(idCapa);
		}
		else if (idCapa.substring(0,2) == 'P_')
		{
			capa = eval('document.getElementById("'+idCapa+'")');			
			
			estado = capa.style.display;
			// Añadimos al array las capas que vamos a ocultar para luego poder volver a activarlas
			if ((estado == 'block') && (estado != 'none'))
			{	
				capa.style.display = 'none';
				paneles.push(idCapa);
			}
		}
	}
	
	// OCULTAMOS LAS PESTAÑAS DEL DETALLE
	var imagenes = document.getElementsByTagName('img');
	for (i=0;i<imagenes.length;i++)
	{
		nameImg = imagenes[i].id;
		pestDetalle = nameImg.indexOf(sufPanel,0); // sufPanel == 'Detalle'
		pest = nameImg.indexOf('pest_',0);
		pestFil = nameImg.indexOf('_fil',0);
		if ( (pest != -1) & (pestDetalle != -1))  {
			vImagenesSrc = imagenes[i].src.split('/');
			img = vImagenesSrc[vImagenesSrc.length-1];
			// Añadimos al vector de pestanyas para luego activar cuando volvamos a pinchar en la pestanya dl maestro
			// formato.- ( "ruta => nombreImg")
			cadena = imagenes[i].src + "=>" + imagenes[i].id;
			pestanyas.push(cadena);
			imagenes[i].src = imagenes[i].src.replace(img,"pix_trans.gif");
			document.getElementById(nameImg).className = 'hiddenTab';
		}
	}
}