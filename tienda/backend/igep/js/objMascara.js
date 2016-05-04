/*
//Cuando se convierta objeto si es que llega a ser posible
function oMascara(nomObjeto, campo, evento, mascara)
{
	//Atributos Privados de la clase
	this.nomObjeto = nomObjeto; // str nombre de la variable del objeto creado
	this.campo = campo; //Objeto input HTML al que se aplica la máscara
	this.evento = evento; //Evento de tecla Pulsada
	this.mascara = mascara; //Cadena con la mascara
	
	this.cCaracter = 'c';
	this.cMinuscula = 'm';
	this.cMayuscula = 'M';
	this.cNumero = 'n';
	this.aplicaMascara = aplicaMascara; 
}
*/
//Backspace (8), Tab (9), Return (13)

/**/

function aplicaMascara(campo, evento, mascara)
{
	var codTeclaPulsada = getKeyCode(evento);
	var teclaPulsada = String.fromCharCode(getKeyCode(evento));
	var targ = getTarget(evento);
	var numCarTecleados = targ.value.length;
	
	//Si se pulsa el tabulador o el intro y la mascara NO es correcta, NO se pierde el foco?
	//TO DO **PDTE***
	
	//Si se pulsa la tecla de borrado y el campo no está vacío borramos el caracter
	// y las cts de mascara que existan
	if ( (codTeclaPulsada == 8) && (campo.value.length>0) )
	{
		//alert('Borrado: '+mascara.charAt(numCarTecleados)+' Caracter Tecleado: '+teclaPulsada);
		campo.value = campo.value.substring(0,(campo.value.length - 1));
		
		//Con este bucle eliminamos los caracteres CONSTANTES de la máscara
		i=1;
		while ( (!isMaskChar(mascara.charAt(numCarTecleados-i))) && (campo.value.length>0) )
		{
			campo.value = campo.value.substring(0, (campo.value.length - 1));
			i++;
		}
		return false;
	}
	
	//Si el número de caracteres coincide con la máscara hemos acabado
	if (numCarTecleados == mascara.length)
	{
		//alert('Final Mascara: '+mascara.charAt(numCarTecleados)+' Caracter Tecleado: '+teclaPulsada);
		return false;
	}

	//Si el caracter siguiente de la máscara es distinto de los caracteres especiales
	//de máscara, estamos ante una constante, la incluímos y seguimos adelante  
	if ( (!isMaskChar(mascara.charAt(numCarTecleados+1))) && (mascara.length>numCarTecleados+1) )
	{	
		//alert('CTSmascara: '+mascara.charAt(numCarTecleados+1)+' Caracter Tecleado: '+teclaPulsada + 'Tot: '+numCarTecleados);
		campo.value = campo.value + teclaPulsada + mascara.charAt(numCarTecleados+1);
		return false;
	}

	//Si es el comodín (cualquier carácter)
	if (mascara.charAt(numCarTecleados) == 'c')
	{
		//alert('Comodin: '+mascara.charAt(numCarTecleados)+' Caracter Tecleado: '+teclaPulsada);
		return true;
	}
	
	
	//Si la mascara es númerica y el caracter introducido lo cumple, es correcto
	if ((mascara.charAt(numCarTecleados) == 'n') && isNumeric(teclaPulsada)) 
	{
		//alert('n: '+mascara.charAt(numCarTecleados)+' Caracter Tecleado: '+teclaPulsada);
		return true;
	}
		
	//Si la máscara es de letras, comprobamos la condición	
	if ((mascara.charAt(numCarTecleados) == 'm') && isAlpha(teclaPulsada))
	{
		//alert('m: '+máscara.charAt(numCarTecleados)+' Caracter Tecleado: '+teclaPulsada);
		campo.value = campo.value + teclaPulsada.toLowerCase();		
		return false;
	}
	
	
	//Si la máscara es de letras, comprobamos la condición	
	if ( (mascara.charAt(numCarTecleados) == 'M') && isAlpha(teclaPulsada) )
	{
		//alert('M: '+mascara.charAt(numCarTecleados)+' Caracter Tecleado: '+teclaPulsada+ ' alfa? '+isAlpha(teclaPulsada));
		campo.value = campo.value + teclaPulsada.toUpperCase();		
		return false;
	}
	
	//Si el caracter pulsado coincide con la máscara, también es correcto
	//escepto para la máscara numérica (n) que no es un número
	if ( (mascara.charAt(numCarTecleados) == teclaPulsada) && (teclaPulsada !='n') )
	{
		//alert('mascara eq tecla: '+mascara.charAt(numCarTecleados)+' Caracter Tecleado: '+teclaPulsada);
		return true;
	}
	
	//Si el caracter de la máscara es el comodín obligatorio, cualquier caracter introducido es válido
	if ((mascara.charAt(numCarTecleados+1) == '?') )
	{	
		//alert('?: '+mascara.charAt(numCarTecleados)+' Caracter Tecleado: '+teclaPulsada);
		campo.value = campo.value + teclaPulsada + mascara.charAt(numCarTecleados+1);
		return true;//átrue?
	}
	
	if (teclaPulsada.charCodeAt(0) < 32)
	{
		//alert('32: '+mascara.charAt(numCarTecleados)+' Caracter Tecleado: '+teclaPulsada);
		return true;
	}
	return false;
}

function getTarget(e)
{
	//Estandar propuesto (Mozilla)
	if (e.target) return e.target;
	
	// Internet Explorer
	if (e.srcElement) return e.srcElement;
}

function getKeyCode(e) 
{
	//Estandar propuesto (Mozilla)
	if (e.target) return e.which
		
	// Internet Explorer
	if (e.srcElement) return e.keyCode
}

//Números
function isNumeric(c)
{
	var strNumeros = "0123456789";
	if (strNumeros.indexOf(c) == -1) return false;
	else return true;
}

//Letras a..z y A..Z
function isAlpha(c)
{
	var lCode = c.charCodeAt(0);
	//alert(lCode);
	if (lCode >= 65 && lCode <= 122 ) return true;
	else return false;
}

//Símbolos de puntuación ESPACIO ! " # & % & ( ) * + ' - . /
function isPunct(c)
{
	var lCode = c.charCodeAt(0);
	if (lCode >= 32 && lCode <= 47 ) return true;
	else return false;
}

//Es un carácter especial de la máscara
function isMaskChar(c)
{
	//alert('c: '+c);
	if
		(	(c == 'c')
			||(c == 'm')
			||(c == 'M')
			||(c == 'n')
		)
	return true;
	else return false
	/*	
	if
		(	(c == this.cCaracter)
			||(c == this.cMinuscula)
			||(c == this.cMayuscula)
			||(c == this.cNumero)
		)
	return true;
	else return false
	*/
}
