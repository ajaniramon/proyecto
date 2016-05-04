/**
 * escapeIgepjs: Funcion para sustituir carácteres especiales.
 * La funcion sustituye los carácteres problemáticos por una cadena
 * formada por un prefijo (!_), la raiz (letra de representación
 * del carácter) y un sufijo (_!)
 * Los carácteres a sustituir son:
 * \b	Backspace			raíz: b
 * \f	Form feed			raíz: f
 * \r	Retorno de carro	raíz: r
 * \n	Linea Nueva			raíz: n
 * \t	Tabulador			raíz: t
 * \'	Comilla simple		raíz: cs
 * \"	Comilla doble		raíz: cd
 * \\	Contrabarra			raíz: cb
 * 
 * La funcion antagónica es desescapeIGEPjs.
 * Existen funciones similares en PHP para poder enviar
 * o recibir cadenas problemáticas en entre los dos lenguajes
 * y por extesnsión entre cliente y servidor
 * 
 * @params string	cadena	String donde se realiza el reemplazo 
 */
 
function escapeIGEPjs (cadena)
{	
	var expRegu;
	var cadEscape='';

	expRegu = /\b/g;
	cadEscape = '!_b_!';
	cadena = cadena.replace(expRegu, cadEscape);
	
	expRegu = /\f/g;
	cadEscape = '!_f_!';
	cadena = cadena.replace(expRegu, cadEscape);

	expRegu = /\r/g;
	cadEscape = '!_r_!';
	cadena = cadena.replace(expRegu, cadEscape);

	expRegu = /\n/g;
	cadEscape = '!_n_!';
	cadena = cadena.replace(expRegu, cadEscape);
	
	expRegu = /\'/g;
	cadEscape = '!_cs_!';
	cadena = cadena.replace(expRegu, cadEscape);

	expRegu = /\"/g;
	cadEscape = '!_cd_!';
	cadena = cadena.replace(expRegu, cadEscape);

	expRegu = /\\/gi;
	cadEscape = '!_cb_!';
	cadena = cadena.replace(expRegu, cadEscape);	
	return(cadena);
} //FIN escapeIGEP


function desescapeIGEPjs (cadena)
{	
	var expRegu;
	var cadEscape='';
	
	expRegu = /!_b_!/gi;
	cadEscape = '\b';
	cadena = cadena.replace(expRegu, cadEscape);
	
	expRegu = /!_f_!/gi;
	cadEscape = '\f';
	cadena = cadena.replace(expRegu, cadEscape);


	expRegu = /!_r_!/gi;
	cadEscape = '\r';
	cadena = cadena.replace(expRegu, cadEscape);

	expRegu = /!_r_!/gi;
	cadEscape = '\r';
	cadena = cadena.replace(expRegu, cadEscape);

	expRegu = /!_n_!/gi;
	cadEscape = '\n';
	cadena = cadena.replace(expRegu, cadEscape);
	
	expRegu = /!_cs_!/gi;
	cadEscape = "'";
	cadena = cadena.replace(expRegu, cadEscape);

	expRegu = /!_cd_!/gi;
	cadEscape = '"';
	cadena = cadena.replace(expRegu, cadEscape);

	expRegu = /!_cb_!/gi;
	cadEscape = '\\';
	cadena = cadena.replace(expRegu, cadEscape);			
	return(cadena);
} //FIN desescapeIGEPjs



