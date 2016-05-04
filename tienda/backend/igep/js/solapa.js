// IM�GENES DE LOS FINALES DE LAS PESTA�AS
var p22 = "igep/images/pestanyas/p22.gif";
var p12 = "igep/images/pestanyas/p12.gif";
var p21 = "igep/images/pestanyas/p21.gif";
var p2 = "igep/images/pestanyas/p2.gif";
var p1 = "igep/images/pestanyas/p1.gif";


function oSolapa(nomObjeto, cantSolapas) 
{
	//Atributos Privados de la clase
	this.nomObjeto = nomObjeto; // str nombre de la variable del objeto creado
	this.solapaAActivar = 0;
	this.solapaADesactivar = 0;
	this.numSolapas = 0;//N�mero de Solapas totales que controla el objeto (TODAS las p�ginas)
	this.cantSolapas = cantSolapas; // N�mero de solapas en una p�gina
	this.v_idSolapas = new Array(); //Vector de ids de Solapas, el n�mero corresponde al boton y el nombre a la Capa
	
	//M�todos p�blicos de la clase
	this.addSolapa = f_oSolapa_addSolapa;//Funci�n que anyade el id de la solapa al Vector de solapas
	this.solapaOn = f_oSolapa_solapaOn; //Funci�n que activa la solapa que recibe por par�metro y desactiva las otras	
}//Fin constructor oSolapa

function f_oSolapa_addSolapa(idSolapa) 
{
	//A�adimos el id de la nueva solapa y actualizamos el numero de solapas
	this.numSolapas = this.v_idSolapas.push(idSolapa);
}//Fin m�todo faddSolapa

function f_oSolapa_solapaOn(solapaActivada, numPags)
{
	if (solapaActivada.className == "optionFlap") //Si NO est� activada...
	{	
		idFicha = solapaActivada.id.split("__")[1];
		posSolapa = parseInt(solapaActivada.id.split("__")[3], 10);
		iterActual = parseInt(solapaActivada.id.split("__")[2], 10);		

		for(var posicion=0; posicion<this.cantSolapas; posicion++) // Recorremos las solapas de esa ficha
		{
			capaTxt = eval('document.getElementById("solTxt__'+idFicha+'__'+iterActual+'__'+posicion+'")');
			capaCont = eval('document.getElementById("solCont__'+idFicha+'__'+iterActual+'__'+posicion+'")');
			capaEsq = '';
			if  (eval('document.getElementById("solEsq__'+idFicha+'__'+iterActual+'__'+posicion+'")'))
				capaEsq = eval('document.getElementById("solEsq__'+idFicha+'__'+iterActual+'__'+posicion+'")');		
			capaData = eval('document.getElementById("solData__'+idFicha+'__'+iterActual+'__'+posicion+'")');
			if (posicion == posSolapa) //Si la posici�n pulsada es la elegida
			{	
				solapaActiva = eval('document.getElementById("solapaActiva")');
				if(solapaActiva!=null)
					solapaActiva.value = posSolapa;
				//Activamos TODAS las solapas Txt/Capadata de dicha posici�n
				capaTxt.className = 'optionFlapOn';
				capaCont.className = 'flapOn';
				if (capaEsq != '')
					capaEsq.className = 'cornerFlapOn';
				if(capaData!=null)
					capaData.style.display = 'block';  // Activamos la ficha de datos correspondiente a la solapa 'posSolapa' y la p�gina 'iterActual'
			}
			else
			{
				capaTxt.className = 'optionFlap';
				capaCont.className = 'flap';
				if (capaEsq != '')
					capaEsq.className = 'cornerFlap';
				capaData.style.display = 'none';  // Ocultar todas las fichas de datos que no corresponden
			}
		}//Fin for posicion
	}//Fin if
}//Fin m�todo fsolapaOn

