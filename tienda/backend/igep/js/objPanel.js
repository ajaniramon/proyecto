/**
* oPanel: Permite consultar y/o modificar los paneles de la ventana
**/
function oPanel(nomObjeto)
{
	this.nomObjeto = nomObjeto;	
	this.capasDetalle = new Array();
	this.pestanyasDetalle = new Array();
	
	//metodos
	this.mostrarPanel = foPanel_mostrarPanel;
	this.ocultarPanel = foPanel_ocultarPanel;
}


/**********************************************************************************************/
/**********		TRATAMIENTO PANELES MAESTRO/DETALLE					************/
/**********************************************************************************************/

function foPanel_mostrarPanel()
{
	// Mostramos el panel dl detalle
	for(i=0;i<this.capasDetalle.length;i++)
	{
		capaPestanya = eval('document.getElementById("'+this.capasDetalle[i]+'")');
		capaPestanya.style.display = "block";	
	}
	
// Activamos la pestanya correspondiente al panel
	var images = document.getElementsByTagName('img');
	for(i=0;i<this.pestanyasDetalle.length;i++)
	{
		vImagenes = this.pestanyasDetalle[i].split('=>');
		ruta = vImagenes[0];
		for(j=0;j<images.length;j++){
			//if (vImagenes[1] == images[j].name) 
			if (vImagenes[1] == images[j].id) 
			{
					images[j].src = vImagenes[0];
					break;
			}
		}
	}
}

function foPanel_ocultarPanel(sufPanel) 
{
	var capas = document.getElementsByTagName('div');
	for(i=0;i<capas.length;i++) {
		// ocultamos los paneles del detalle
		idCapa = capas[i].id;
		capa = eval('document.getElementById("'+idCapa+'")');
		posicion = idCapa.indexOf(sufPanel,0); // sufPanel = 'Detalle'

		if (idCapa == 'detalles')
			eval('capa.style.display = "none"');
		else if ( (posicion != -1) && (eval('capa.style.display') == 'block') )
		{
			// Encontramos un panel dl Detalle lo añadimos al vector para luego
			// tener q capas activar al pulsar otra vez en la pestanya dl maestro
			this.capasDetalle.push(idCapa);
			capa.style.display = "none";
		}
	}
	
	var imagenes = document.getElementsByTagName('img');
	for (i=0;i<imagenes.length;i++)
	{
		//nameImg = imagenes[i].name;
		nameImg = imagenes[i].id;
		pestDetalle = nameImg.indexOf(sufPanel,0);
		pest = nameImg.indexOf('pest_',0);
		pestFil = nameImg.indexOf('_fil',0);
		// Ocultamos las pestañas del detalle
		
		if ( (pest != -1) & (pestDetalle != -1))  {
			vImagenesSrc = imagenes[i].src.split('/');
			img = vImagenesSrc[vImagenesSrc.length-1];
			// Añadimos al vector de pestanyas para luego activar cuando volvamos a pinchar en la pestanya dl maestro
			// formato.- ( "ruta => nombreImg")
			//cadena = imagenes[i].src + "=>" + imagenes[i].name;
			cadena = imagenes[i].src + "=>" + imagenes[i].id;
			this.pestanyasDetalle.push(cadena);
			// Hay que desactivar la capa
			document.getElementById(imagenes[i].id).className = 'hiddenTab';
			imagenes[i].src = imagenes[i].src.replace(img,"pix_trans.gif");
		}
	}
}