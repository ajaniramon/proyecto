function fechaCliente() {
	// Devuelve la fecha dl cliente
	var hoy = new Date();
	var anyo = hoy.getYear();
	if (navigator.appName.indexOf("Netscape") != -1) 
	{
		anyo = anyo +1900;
	}
	anyo = anyo.toString();
	var dia = hoy.getDate();
	dia = dia.toString();
	var mes = parseInt(hoy.getMonth()+1,10);
	mes = mes.toString();
	if (dia < 10) { dia = '0'+dia; }
	if (mes < 10) { mes = '0'+mes; }
	fecha = dia+"/"+mes+"/"+anyo;
	return fecha;
}

//*******************************************************************************//

function esBisiesto(anyo) {
	if ( ( ((anyo % 4) == 0) && ((anyo % 100) != 0) ) || ((anyo % 400) == 0) ) {
		return true;
	}
	return false;
}

//*******************************************************************************//

// SE CREA UN VECTOR DE N ELEMENTOS
function Vector(n) {
	this.length = n;
	for (var i = 1; i <= n; i++) {
		this[i] = 0
	}
	return this
}

// FUNCIÓN PARA Q EL VECTOR SEA BIDIMENSIONAL
function eltos_vector(mes,dias) {
	this.mes = mes;
	this.dias = dias;
}

function vector_mesdia(diasdelmes,vanyo) {
	// RELLENAMOS EL VECTOR diasdelmes CON LOS DIAS D CADA MES	
	diasdelmes[1] = new eltos_vector("01","31");
	// FEBRERO - año bisiesto o no
	if (esBisiesto(vanyo)) {
		diasdelmes[2] = new eltos_vector("02","29");
	}
	else {
		diasdelmes[2] = new eltos_vector("02","28");
	}
	diasdelmes[3] = new eltos_vector("03","31");
	diasdelmes[4] = new eltos_vector("04","30");
	diasdelmes[5] = new eltos_vector("05","31");
	diasdelmes[6] = new eltos_vector("06","30");
	diasdelmes[7] = new eltos_vector("07","31");
	diasdelmes[8] = new eltos_vector("08","31");
	diasdelmes[9] = new eltos_vector("09","30");
	diasdelmes[10] = new eltos_vector("10","31");
	diasdelmes[11] = new eltos_vector("11","30");
	diasdelmes[12] = new eltos_vector("12","31");	
}

function comprobarFecha(fecha)
 {		
    fecha = fecha.split("/");

    if (fecha.length<3)
	{
		return (false);
	}
	
	var dia = fecha[0];
	var mes = fecha[1];
	var anyo = fecha[2];
	var hoy = new Date();
	var actanyo = hoy.getYear();
	
//	if ( (dia.length==0) || (mes.length==0) || (anyo.length==0) )
	if ( (dia==null) || (mes==null) || (anyo==null) )
	{
		return (false);
	}

	if (anyo.length==2)
	{
		if (anyo > 70) 
		{
			anyo = "19"+anyo;
		}
		else
		{
			anyo = "20"+anyo;
		}
	}	
	
	if (navigator.appName.indexOf("Netscape") != -1) 
	{
		actanyo = actanyo +1900;
	}
				
	if ((mes > '12') || (mes < '01'))
	{
		return false;
	}
	mes_dia = new Vector(12);
	vector_mesdia(mes_dia,anyo);
	// COMPROBAR SI LA FECHA ES CORRECTA
	for (var i=1; i<=12; i++)
	{			
		if (mes_dia[i].mes == mes)
		{
			haycero = dia.substr(0,1);
			if (haycero == '0') 
			{
				dia = dia.substr(1,1);
			}
			if ((parseInt(mes_dia[i].dias,10) >= dia) && (dia > 0)) 
			{
				return true;
			}
			else
			{
				// El mes introducido no tiene tantos días
				return false;
			}		
		}//if
	}//for
	return true;
}

function comprobarLimiteFechas(ifecha,ffecha) {
	
	var lainicial = "noinicial";
	var lafinal = "nofinal";		
	
	// Comprobar la fecha inicial
	if (comprobarFecha(ifecha)) {
		ifecha = ifecha.split("/");
		var idia = ifecha[0];
		var imes = ifecha[1];
		var ianyo = ifecha[2];
		lainicial = "correcta";
	}
	else { return false; }
		
	// Comprobar la fecha final
	if (comprobarFecha(ffecha)) {
		ffecha = ffecha.split("/");
		var fdia = ffecha[0];
		var fmes = ffecha[1];
		var fanyo = ffecha[2];
		lafinal = "correcta";
	}
	else { return false; }
			
	// Comprobar q las 2 fechas están en el orden correcto
	if ( lainicial == lafinal) {
// COMPROBAMOS Q LA FINAL NO ES ANTERIOR A LA INICIAL
		if (ianyo > fanyo) {	
			if ((fanyo > '0000') || (fanyo < '00')) {
				return false;
			}
		}
		else { 
			if (ianyo==fanyo) {
				if (imes > fmes) {
					return false;
				}
				else { 
					if (imes==fmes) {
						if (idia > fdia) {
							return false;
						}
					}
				}
			}
		}
		return true;
	}
	else {
		return false;
	}
}