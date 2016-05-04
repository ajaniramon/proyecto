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

/**
* Pluggin Paginador
*
* Prepara la inserción del código de paginación
* @author  David <pascual_dav@gva.es>
* @author  Toni <felix_ant@gva.es>
* @author  Raquel <borjabad_raq@gva.es>
* @author  Verónica <navarro_ver@gva.es>
*/

function smarty_function_CWPaginador($params, &$smarty) 
{
	/////////////////////////////////////////////////////////////////////
	// LECTURA DE VALORES DE LA PILA //
	/////////////////////////////////////////////////////////////////////
	
	//Puntero a la pila de etiquetas que contiene a (CWTabla, CWFichaEdicion) 
	$punteroPilaPadre = count($smarty->_tag_stack)-1;			
	$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];
	//Puntero a la etiqueta Abuelo () 
	$punteroPilaAbuelo = $punteroPilaPadre - 1;		
	$CWAbuelo = ($smarty->_tag_stack[$punteroPilaAbuelo][0]);	
	//Puntero a la etiqueta BisAbuelo (CWPanel) 
	$punteroPilaBisAbuelo = $punteroPilaAbuelo - 1;
	$CWBisAbuelo = $smarty->_tag_stack[$punteroPilaBisAbuelo][0];
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	// CODIGO NECESARIO PARA CADA COMPONENTE //
	///////////////////////////////////////////////////////////////////////////////////////////////
	
	// Primero defino el nombre del componente.
	$n_comp="CWPaginador";	
	// Necesitamos saber cuántas instancias de este componente existen ya / para poner el codigo o no
	//$num=$smarty->cw->anadirComponente($n_comp);
	
	$igepSmarty = new IgepSmarty();			
		
	if($params['pagInicial']) 
	{
		$pagInicial=$params['pagInicial'];
	} 
	else 
	{
		$pagInicial=0;
	}
	
	if($params['enlacesVisibles']) 
	{
		$enlaces=$params['enlacesVisibles'];
	} 
	else 
	{
		$enlaces=5;
	}
	
	$btnIco = false;
	if($params['iconCSS'])
		$btnIco = $params['iconCSS'];
	
	$idTabla = $smarty->_tag_stack[$punteroPilaPadre][1]['id'];
		
	//MAESTRO/DETALLE
	if (isset($smarty->_tag_stack[$punteroPilaBisAbuelo][1]['esMaestro'])) {
	  		$esMaestro = $smarty->_tag_stack[$punteroPilaBisAbuelo][1]['esMaestro'];
	}
	else { $esMaestro = ''; }
	
	//Tipo de comprobación establecida
	//MAESTRO/DETALLE
	if (isset($smarty->_tag_stack[$punteroPilaBisAbuelo][1]['tipoComprobacion'])) {
	  		$tipoComprobacion = $smarty->_tag_stack[$punteroPilaBisAbuelo][1]['tipoComprobacion'];
	}		
	
	$claseManejadora = $smarty->_tag_stack[$punteroPilaBisAbuelo][1]['claseManejadora'];

	if ($smarty->_tag_stack[$punteroPilaPadre][0]=="CWFichaEdicion") 
	{
	  	if ($smarty->_tag_stack[$punteroPilaBisAbuelo][1]['itemSeleccionado'])
	  	{
	  		$itemSeleccionado = $smarty->_tag_stack[$punteroPilaBisAbuelo][1]['itemSeleccionado'];		  		
	  	}
	  	else
	  	{	
	  		$itemSeleccionado = '0';		  		  		
	  	}//Fin if-else item seleccionado
	  	$pagInicial = $itemSeleccionado;
	    $formulario = "F_".$smarty->_tag_stack[$punteroPilaBisAbuelo][1]['id'];
	  	if ($esMaestro == "true")
	  		$actionForm = "phrame.php?action=".$claseManejadora."__recargar";
  	}// Fin if "FichaEdicion"
  	else //Estamos en modo maestro, y en una tabla
  	{
  		//Calcular cual es la página activa en funcion del item seleccionado y las filas en pantalla
  		//$iterActual%$numFilasPantalla
  		$itemSeleccionado = $smarty->_tag_stack[$punteroPilaBisAbuelo][1]['itemSeleccionado']; 	
  		$numFilasPantalla = $smarty->_tag_stack[$punteroPilaPadre][1]['numFilasPantalla'];
  		$pagInicial = 0;
  		if ($numFilasPantalla > 0)
  			$pagInicial=floor($itemSeleccionado/$numFilasPantalla);
  	}

	$nombre_paginador = $smarty->_tag_stack[$punteroPilaBisAbuelo][1]['id']."_paginacion";	
	$numRegistros = count($smarty->_tag_stack[$punteroPilaPadre][1]['datos']);
	$numFilasPantalla = $smarty->_tag_stack[$punteroPilaPadre][1]['numFilasPantalla'];
	$pagInsertar = 0;
	$pagInsertar = $smarty->_tag_stack[$punteroPilaPadre][1]['numPagInsertar'];
	$pagExactas = 0;
	switch (trim($CWPadre))
	{
		case "CWTabla":
			if ($numFilasPantalla != 0)
			{
				if ($numRegistros%$numFilasPantalla == 0)
				{
					$pagTotalesDatos = floor($numRegistros/$numFilasPantalla);
					$pagTotalesInsertar = $pagTotalesDatos + $pagInsertar;
					$pagExactas = 1;
				} 
				else
				{
					$pagTotalesDatos = floor($numRegistros/$numFilasPantalla)+1;
					$pagTotalesInsertar = $pagTotalesDatos+$pagInsertar;
				}
			}
			$prefijoPag ="\"pag_".$idTabla."_\",";
			$textoEnlaces="Pg.";
			
		break;
		case "CWFichaEdicion":
				$pagTotalesDatos = $numRegistros;		
				$pagTotalesInsertar = $pagTotalesDatos + $pagInsertar;
				$pagExactas = 1;		
				$prefijoPag ="\"pag_".$idTabla."_\",";
				$textoEnlaces="Reg";
		break;
		default:
			$pagTotalesDatos=1;
			$textoEnlaces="Error";
		break;
	}	
	$pagActual = "pagActual___".$claseManejadora;
	if ($CWPadre == "CWFichaEdicion")
	{
		$hidden = "<input type=hidden id=\"".$pagActual."\" name=\"".$pagActual."\" value=\"".$itemSeleccionado."\" />";	  	
	}
	$ini = "\n <!-- ______________ INICIO PAGINADOR $nombre_paginador ______________ --> \n";
	$smarty->igepPlugin->registrarInclusionJS('paginacion.js');
	
	$pagComprueba = 'false';	
	if ($tipoComprobacion != "cambioFoco")
	{	
		$pagComprueba = "true";
	}
	
	if ($CWPadre == "CWFichaEdicion")
	{
		$script = "var ".$nombre_paginador." = new oPaginacion(\"".$nombre_paginador."\", \"".$pagActual."\");\n";
	}
	else
	{
		$script = "var ".$nombre_paginador." = new oPaginacion(\"".$nombre_paginador."\");\n";
	}

	$script .= $nombre_paginador.".set(";
		$script .= $prefijoPag;
		$script .="\"capa_".$nombre_paginador."\",";
		$script .=$pagInicial.",";
		$script .= $pagTotalesDatos.",";
		$script .= $pagInsertar.",";
		$script .= $pagExactas.",";
		$script .=$enlaces.",";
		$script .="'".$textoEnlaces."',";
		$script .= "'".$formulario."',";
		$script .= "'".$esMaestro."',";
		$script .= "'".$numRegistros."',";
		$script .= "'".$actionForm."',";
		$script .= "'".IMG_PATH_CUSTOM."',";
		$script .= "'".$btnIco."'";
		$script .=");\n";
	$script .= $nombre_paginador.".fijarNombreFicha('$idTabla');";
	$script .= $nombre_paginador.".dibujar_enlaces()";	
	
	$igepSmarty->addPostScript($script);

	//Registramos el objeto paginador
	$smarty->igepPlugin->registerJSObj($nombre_paginador);
	$fin = "\n <!-- ______________ FIN PAGINADOR ______________ --> \n";
	return ($ini.$hidden.$igepSmarty->getPostScript().$fin);
}
?>