<?php 
/* gvHIDRA. Herramienta Integral de Desarrollo Rápido de Aplicaciones de la Generalitat Valenciana
*
* Copyright (C) 2006 Generalitat Valenciana.
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,USA.
*
* For more information, contact:
*
*  Generalitat Valenciana
*  Conselleria d'Infraestructures i Transport
*  Av. Blasco Ibáñez, 50
*  46010 VALENCIA
*  SPAIN
*  +34 96386 24 83
*  gvhidra@gva.es
*  www.gvpontis.gva.es
*
*/
require_once('igep/include/IgepSmarty.php');

function smarty_block_CWTabla($params, $content, &$smarty) {	
	
	// -----------------------------------------------------------------------------------------------------------------
	// ------------------------------------ Parámetros para herederos ------------------------------------		
	// Los parámetros de la lista citada a continuación, no son utilizados 
	// directamente por este plugin pero sI por alguno de sus descendientes,
	// por eso no apecen referenciados en este código, pero si en la documentación
	// o en alguna TPL o en algún plugin descendiente (interno) a este referenciados
	// a través de la Pila de Smarty
	// -----------------------------------------------------------------------------------------------------------------
	
	// Parámetro: 	animacionFila {true/false}
	//		Lo utiliza el plugin CWFila para decidir si incluye la animación
	//		de coloreado en naranja cuando el ratón pasa por encima de la fila
	//		el valor por defecto en caso de que no aparezca es FALSE, es decir
	//		no habrá animacion (menor sobrecarga de CPU)
	
	// Parámetro: 	id
	//		Lo utiliza el plugin CWFila para componer el identificador de la Fila como objeto
	// 		html (el id del elemento <TR>)
	
	// -----------------------------------------------------------------------------------------------------------------
	// ---------------------------------- FIN parámetros para herederos ---------------------------------
	// -----------------------------------------------------------------------------------------------------------------
	
	if($params['datos']) 
	{
		$datosTabla=$params['datos'];
	}
//	else 
//	{
		$punteroPila = count($smarty->_tag_stack)-1;
		//Puntero a la etiqueta Abuelo 
		$punteroPilaAbuelo = $punteroPila - 2;		
		$idPanel = $smarty->_tag_stack[$punteroPilaAbuelo][1]['id'];	
		
		$igepSmarty = new IgepSmarty();		
		$smarty->igepPlugin->registrarInclusionJS('objTabla.js');
	  	$script = "var ".$idPanel."_tabla = new oTabla('".$idPanel."','".$idPanel."_tabla');\n";
	  	$igepSmarty->addPreScript($script);
		
	//	return $igepSmarty->getPreScript()."<br/>".$content;
	//}
    $numTotalRegistros=count($datosTabla);
    
	$punteroPila = count($smarty->_tag_stack)-1;
	if($params['numFilasPantalla']) {
		$numFilasPantalla = $params['numFilasPantalla'];
	}
	else 
	{
		$smarty->_tag_stack[$punteroPila][1]['numFilasPantalla'] = 6;
	}
	
	// REVIEW: 02/03/2009 Vero, siempre vamos a tener una página para insertar.
	$numPagInsertar = 1;
	$smarty->_tag_stack[$punteroPila][1]['numPagInsertar'] = 1;

	//Indica si la selección será única (un sólo registro) o múltiple
	$seleccionUnica = false;
	if (
		(isset($params['seleccionUnica']))
		&& 
		(
			($params['seleccionUnica'] === true) || (strtolower(trim($params['seleccionUnica'])) =='true')
		)
	)
	{
		$seleccionUnica = true;
	}
	$smarty->_tag_stack[$punteroPila][1]['seleccionUnica'] = $seleccionUnica;
	
	//Indica si debe aparecer columna de chequeo
	$conCheck = false;
	if (
		(isset($params['conCheck']))
		&& 
		(
			($params['conCheck'] === true) || (strtolower(trim($params['conCheck'])) =='true')
		)
	)
	{
		$conCheck = true;
	}
	$smarty->_tag_stack[$punteroPila][1]['conCheck'] = $conCheck;
	
	
	//Indica si debe aparecer columna de chequeo general (chequear/deschequear todos)
	$conCheckTodos = false;
	if (
		(isset($params['conCheckTodos']))
		&& 
		(
			($params['conCheckTodos'] === true) || (strtolower(trim($params['conCheckTodos'])) =='true')
		)
	)
	{
		$conCheckTodos = true;
	}
	$smarty->_tag_stack[$punteroPila][1]['conCheckTodos'] = $conCheckTodos;
	
	
	
		
	// Se abre la etiqueta
	if(!isset($content)) 
	{
		///////////////////////////////////////////
		// CÓDIGO NECESARIO PARA CADA COMPONENTE //
		///////////////////////////////////////////
		// Primero defino el nombre del componente.
		$n_comp="CWTabla";	
		//Añadimos el componente al control de Instancias
		$numtabla=$smarty->igepPlugin->registrarInstancia($n_comp);
		if($params['id']) 
		{
			$nombre=$params['id'];
		}
		else
		{
			$nombre=$n_comp.$numtabla;
		}
		unset($smarty->_tag_stack[$punteroPila][1]['titulosColumnas']);
	} 
	else //Se cierra la etiqueta 
	{
		$igepSmarty = new IgepSmarty();			
		
		if($params['id']) 
		{
			$idTabla = $params['id'];
			$pp = $params['id'];
		}
		else 
		{
			$idTabla = $n_comp.$numtabla;
		}		
		
		// Obtenemos el número de páginas
	  	$numPaginas = intval($numTotalRegistros/$numFilasPantalla);
	  	// Sumamos 1 al numPaginas pq empezamos a numerar por 0
	  	$pagExactas = 0;
	  	if ($numTotalRegistros%$numFilasPantalla == 0)
	  	{	  	
	  		$numPaginasTotales = $numPaginas+$numPagInsertar;
	  		$pagExactas = 1;
	  	}
	  	else
	  	{
	  		$numPaginasTotales = ($numPaginas+1)+$numPagInsertar;	  		
	  	}	  		  	
		
		//Puntero a la etiqueta Abuelo 
		$punteroPilaAbuelo = $punteroPila - 2;		
		$idPanel = $smarty->_tag_stack[$punteroPilaAbuelo][1]['id'];	
		
	  	$smarty->igepPlugin->registrarInclusionJS('objTabla.js');
	  	$script = "var ".$idPanel."_tabla = new oTabla('".$idPanel."','".$idPanel."_tabla');\n";
		$igepSmarty->addPreScript($script);
		
		//Registramos el objeto JS
		$smarty->igepPlugin->registerJSObj($idPanel."_tabla"); 
	  	$fin_tabla .= "<!-- FIN TABLA DATOS -->\n";
		
		return $igepSmarty->getPreScript()."<br>".$content.$fin_tabla;
	}
}
?>