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

function smarty_block_CWSolapa($params, $content, &$smarty)
{
	if(!isset($content)) // Si se abre la etiqueta {CWSolapa}...
	{
		$n_comp = "CWSolapa";
		$num=$smarty->igepPlugin->registrarInstancia($n_comp);
	}
	else //{CWSolapa id="Solapa2" posicionSolapa="2"}
	{
		$igepSmarty = new IgepSmarty();

		$posSolapa=0;
		if ($params['posicionSolapa'])//La posicion es obligatoria, y debe empezar en 0 y ser consecutiva...
		{
			$posSolapa = $params['posicionSolapa'];
		}


		if ($params['titulo'])
		{
			$titulo = $params['titulo'];
		}
		else
		{
			die('<br/>ERROR DE USO DE gvHIDRA EN LA TPL: <br/> TODA SOLAPA DEBE TENER UN TITULO ASOCIADO.<br/>');
		}



		////////////////////////////////////////////////////////////////////////////
		//// LECTURA DE VALORES DE LA PILA ////
		////////////////////////////////////////////////////////////////////////////
		//Puntero a la pila de etiquetas que contiene a CWSolapa
		$punteroPilaSolapa = count($smarty->_tag_stack)-1;
		$CWSolapa = $smarty->_tag_stack[$punteroPilaSolapa][0];
		//Puntero a la etiqueta Padre (Será un CWFicha)
		$punteroPilaPadre = $punteroPilaSolapa - 1;
		$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];
		//Puntero a la etiqueta Padre (Será un CWFicha)
		$punteroPilaAbuelo = $punteroPilaPadre - 1;
		$CWAbuelo = $smarty->_tag_stack[$punteroPilaAbuelo][0];
		////////////////////////////////////////////////////////////////////////////////////////
		/////// FIN LECTURA DE VALORES DE LA PILA  //////
		////////////////////////////////////////////////////////////////////////////////////////



		$iterActual=$smarty->_tag_stack[$punteroPilaPadre][2];
		$idFicha=$smarty->_tag_stack[$punteroPilaAbuelo][1]['id'];
		$solapaActiva = 0;
		if (isset($smarty->_tag_stack[$punteroPilaAbuelo][1]['solapaActiva']))
			$solapaActiva = $smarty->_tag_stack[$punteroPilaAbuelo][1]['solapaActiva'];

		$ini_html="";
		$fin_html="";
		$estado = " display:none; ";

		if($iterActual == 0)//Si es la primera iteracion, la solapa registra su título.
		{
			//Creo el vector de cadenas (títulos) que se busca en el Abuelo. Un titulo por solapa.
			$smarty->_tag_stack[$punteroPilaPadre][1]['titulosSolapas'][] = $titulo;
		}

		if ($posSolapa == $solapaActiva) {//Si soy la primera solapa
			$estado = " display:block; ";
		}

		$idSolapa = "solData__".$idFicha.'__'.$iterActual.'__'.$posSolapa;

		$ini_html.="<!-- Capa solapa".$idSolapa." -->\n";
		$ini_html .="<div id='".$idSolapa."' class='containerFlaps' style=\"".$estado."\" >\n";
		$fin_html .= "</div> \n";
		if ($CWAbuelo == "CWContenedor")
			$script = "solapaB.addSolapa('".$idSolapa."');\n";
		else
			$script = "solapaE.addSolapa('".$idSolapa."');\n";
		$igepSmarty->addPreScript($script);

		$resultado = $igepSmarty->getPreScript().$ini_html.$content.$fin_html."\n";
		return $resultado;
	}
}
?>