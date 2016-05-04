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

function smarty_block_CWFicha($params, $content, &$smarty, &$repeat)
{
	////////////////////////////////////////////////////////////////////////////////
	// INICIALIZACIÓN DE VALORES EN LA PILA //
	////////////////////////////////////////////////////////////////////////////////

	//Puntero a la pila de etiquetas que contiene a CWFicha
	$indicePila = count($smarty->_tag_stack)-1;
	//Puntero a la etiqueta Padre (CWFichaEdicion o CWContenedor si es una búsqueda)
	$punteroPilaPadre = $indicePila - 1;
	$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];

	//Puntero a la etiqueta Abuelo (CWContenedor o CWPanel si es una búsqueda)
	$punteroPilaAbuelo = $punteroPilaPadre - 1;
	$CWAbuelo = $smarty->_tag_stack[$punteroPilaAbuelo][0];

	if ($CWAbuelo =='CWPanel')
		$punteroPanel = $punteroPilaAbuelo;
	else
		$punteroPanel = $punteroPilaAbuelo - 1;
	$idPanel = $smarty->_tag_stack[$punteroPanel][1]['id'];

	// ****************************************************//

	// ITERACIÓN ACTUAL.- posicion [2] de la pila de CWFicha
	if (isset($smarty->_tag_stack[$indicePila][2]))
	{
		$smarty->_tag_stack[$indicePila][2]++;
	}
	else
	{
		$smarty->_tag_stack[$indicePila][2] = 0;
	} // (isset($smarty->_tag_stack[$indicePila][2]))
	$pagActual = $smarty->_tag_stack[$indicePila][2];
    $smarty->assign('smty_iteracionActual',$pagActual);
	// ****************************************************//

	// NUM. ITERACIONES TOTALES .- posicion [3] de la pila de CWFicha
	// coincide con el numero de registros que llegan desde el "negocio"
	$smarty->_tag_stack[$indicePila][3] = count($smarty->_tag_stack[$punteroPilaPadre][1]['datos']);
	$pagTotales = $smarty->_tag_stack[$indicePila][3];

	// ****************************************************//

	// NUM. PAGINAS A INSERTAR
	if($smarty->_tag_stack[$punteroPilaPadre][1]['numPagInsertar'])
	{
		$numPagInsertar = $smarty->_tag_stack[$punteroPilaPadre][1]['numPagInsertar'];
	}


	// ****************************************************//

	// Identificador de la página
	$idPagina = $smarty->_tag_stack[$punteroPilaPadre][1]['id'];
	$idFicha = "pag_".$idPagina."_".($pagActual-1);
	$htmlSolapas = '';

	if (!isset($content))
	{
 		///////////////////////////////////////////
		// CODIGO NECESARIO PARA CADA COMPONENTE //
		///////////////////////////////////////////
		// Primero defino el nombre del componente.
		$n_comp="CWFicha";
		// Necesitamos saber cuántas instancias de este componente existen ya / para poner el codigo o no
		$num = $smarty->igepPlugin->registrarInstancia($n_comp);

		/////////////////////////////////////////////
		// FIN CODIGO NECESARIO DE CADA COMPONENTE //
		/////////////////////////////////////////////
	}
	else //Etiqueta de cierre
	{
		//Nuevo
		$htmlSolapas = '';
		if ($params['titulosSolapas']) //Si hay solapas...
		{
			$htmlSolapas .= "<div style='display:block;' >"; // Capa para encerrar todas las solapas
			$igepSmarty = new IgepSmarty();
			$cantSolapas = count($params['titulosSolapas']);//cantidad de solapas definidas por ficha
			$smarty->igepPlugin->registrarInclusionJS('solapa.js');

			$numPaginas = 1;
			if ($CWPadre == "CWContenedor") //Si soy un panel de búsqueda...
			{
				$nomObjSolapa = "solapaB";
			}
			else
			{
				$nomObjSolapa = "solapaE";
				if ($pagActual <= $pagTotales)
					$numPaginas = $pagTotales;
				else
					$numPaginas = $pagTotales+$numPagInsertar;
			}

			if ($pagActual==1) //Primera página
			{
				$script = "var ".$nomObjSolapa." = new oSolapa('".$nomObjSolapa."', ".$cantSolapas.");\n";
				$script .= $nomObjSolapa." = eval('".$nomObjSolapa."');";//REVIEW: VERO Y DAVID - Hace falta el eval?
				//Registramos el objeto JS
				$smarty->igepPlugin->registerJSObj($nomObjSolapa);
				$igepSmarty->addPreScript($script);
			}

			$solapaActiva = 0;
			if (isset($smarty->_tag_stack[$punteroPilaPadre][1]['solapaActiva']))
				$solapaActiva = $smarty->_tag_stack[$punteroPilaPadre][1]['solapaActiva'];

			for ($posSolapa=0; $posSolapa<$cantSolapas; $posSolapa++)
			{
				$tituloSolapa = $params['titulosSolapas'][$posSolapa];
				$idSolapaCont = "solCont__".$idPagina.'__'.($pagActual-1).'__'.$posSolapa;
				$idSolapaTxt = "solTxt__".$idPagina.'__'.($pagActual-1).'__'.$posSolapa;
				$idSolapaEsq = "solEsq__".$idPagina.'__'.($pagActual-1).'__'.$posSolapa;

				$classCont = 'flap'; //'solapa';
				$classTxt = 'optionFlap'; //'opcion';
				$classEsq = 'cornerFlap'; //'esqSolapa';
				if ($posSolapa == $solapaActiva)
				{
					$classCont = 'flapOn'; //'solapaActiva';
					$classTxt = 'optionFlapOn'; //'opcionActiva';
					$classEsq = 'cornerFlapOn'; //'esqSolapaActiva';
				}

				$htmlSolapas .= "<div id='$idSolapaCont' class='$classCont'>";
				$htmlSolapas .= "<a id=".$idSolapaTxt." href='#' class='".$classTxt."' tabindex='10020' onClick='";
					$htmlSolapas .= $nomObjSolapa.".solapaOn(this, ".$numPaginas.");'";
				$htmlSolapas .= ">";
				$htmlSolapas .= $tituloSolapa;
				$htmlSolapas .= "</a>";
				$htmlSolapas .= "</div>";
				$htmlSolapas .= "<div id='$idSolapaEsq' class='$classEsq' ></div>";

			}//Fin for
			$htmlSolapas .= '</div>';
			$htmlSolapas = $igepSmarty->getPreScript().$htmlSolapas;

			//REVIEW: Vero y David. Revisar código
		  	if ($CWPadre == "CWContenedor") //Si soy un panel de búsqueda...
		  	{
			  	$idPaginaBus = $smarty->_tag_stack[$punteroPanel][1]['id'];
				$idFichaBus = "pag_".$idPaginaBus."_0";
		  		$iniFicha = "";
				$iniFicha .= $htmlSolapas;
				$finFicha = "</div>";
				$resultado = $iniFicha.$content.$finFicha;
		  	}//Fin if mi padre es contenedor

		}//Fin SOLAPAS


		if  ($CWPadre == "CWFichaEdicion")
		{
			// Obtenemos el vector d registros
			$datos = $smarty->_tag_stack[$punteroPilaPadre][1]['datos'];
			$iniFicha = "";
			$html_iniRegNuevo="";
			$html_finRegNuevo="";
			$finFicha = "</div>";

			// Si estamos en una iteraciï¿½n con datos en los registros
			if ($pagActual <= $pagTotales)
			{
				$iniFicha .= "<div id='".$idFicha."' style='display:none;'><br/>\n";
			  	$resultado = $iniFicha.$htmlSolapas.$content.$finFicha;
			} // ($pagActual <= $pagTotales)
			// HEMOS SUPERADO LAS ITERACIONES CON DATOS, VAMOS A INSERTAR PÁGINAS NUEVAS
			elseif ($pagActual < ($pagTotales + 1))
			{
				$html_iniRegNuevo = "\n<!-- NUEVO REGISTRO EN BLANCO -->\n";
			  	$html_iniRegNuevo .= "<div id='".$idFicha."' style=\"display:none;\"><br/>\n";

			  	$html_finRegNuevo = "<br/></div>\n";
				$html_finRegNuevo .= "\n<!-- FINAL DEL NUEVO REGISTRO EN BLANCO -->\n";
				$resultado = $html_iniRegNuevo.$htmlSolapas.$content.$html_finRegNuevo;
				//$resultado = $htmlSolapas.$html_iniRegNuevo.$content.$html_finRegNuevo;
			} // ($pagActual <= $pagTotales)
			else
			{
				$html_iniRegNuevo = "<!-- CAPA PARA INSERCIï¿½N DESDE Bï¿½SQUEDA-->\n";
				$idPag = "pag_edi_999";
				// Capa bloqueo que se crea para indicar cuando en los detalles no hay datos
				// No se activen los campos de inserción en la tabla en un tres modos
				$tresModos = $smarty->_tag_stack[$punteroPanel][1]['tresModos'];
				// No hay páginas && Primera página && Panel edi detalle que no es un tabular registro
				if (($pagTotales == 0) && ($pagActual == 1) && ((($idPanel == 'edi') || ($idPanel == 'lisDetalle')) && ($tresModos != 1 )))
				{
					$style = "display: block;
							padding-top: 50px;
							width: 100%;
							height: 100px;";
					$html_iniRegNuevo .= "<div id=\"blockPanel\" class='blockPanel' style=\"".$style."\">\n";
					$html_iniRegNuevo .= "NO SE HAN ENCONTRADO DATOS\n";
					$html_iniRegNuevo .= "</div>";
				}
				$html_iniRegNuevo .= "<div id=\"".$idFicha."\" style=\"display:none;\">\n";

				$html_finRegNuevo = "";
			  	$html_finRegNuevo .= "<br/></div>\n";
				$html_finRegNuevo .= "\n<!-- FINAL DEL NUEVO REGISTRO EN BLANCO -->\n";
				$resultado = $html_iniRegNuevo.$htmlSolapas.$content.$html_finRegNuevo;
			}

			$idEstado = str_replace('pag_', '', $idFicha);
			$estado = "<input type='hidden' id='est_".$idEstado."' name='est_".$idEstado."' value='nada' />\n";
			$resultado = $estado.$resultado;
		}	// ($CWPadre == "CWFichaEdicion")

		else if (($CWPadre == "CWContenedor") && (!isset($params['titulosSolapas'])))//
		// if (Es panel de búsqueda) && (no hay solapas)
		{
			// Para cuando la ficha esta dentro d otro componente, mostramos el contenido completo
			// Ahora se utiliza para tener una ficha en el panel de búsqueda, donde el padre es CWContenedor
			$resultado = $content;
		}
	} // else (!isset($content))

	$total = $pagTotales+$numPagInsertar;
	if ($pagActual < $total)
	{
		$repeat = 1;
	}

	return($resultado);
  } // CWFicha
?>