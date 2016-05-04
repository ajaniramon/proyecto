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

function smarty_block_CWFila($params, $content, &$smarty, &$repeat) 
{	
	////////////////////////////////////////////////////////////////////////////////
	// INICIALIZACIÓN DE VALORES EN LA PILA //
	////////////////////////////////////////////////////////////////////////////////
	
	//Puntero a la pila de etiquetas que contiene a CWFila 
	$indicePila = count($smarty->_tag_stack)-1;
	//Puntero a la etiqueta Padre (CWTabla) 
	$punteroPilaPadre = $indicePila - 1;
	//Puntero a la etiqueta BisAbuelo (CWPanel) 
	$punteroPilaBisAbuelo = $punteroPilaPadre - 2;
	$idPanel = $smarty->_tag_stack[$punteroPilaBisAbuelo][1]['id'];
	// ****************************************************//
		
	// ITERACIÓN ACTUAL.- posicion [2] de la pila de CWFila
	// posicion [5] de la pila de CWFila - indica cuando la tabla 
		//no tiene datos y es la primera Fila a insertar para poder
		//inicializar las variable q controlan la generacion
	if (isset($smarty->_tag_stack[$indicePila][2]))
	{
		$smarty->_tag_stack[$indicePila][2]++;
		$smarty->_tag_stack[$indicePila][5]++;
	}
	else
	{
		$smarty->_tag_stack[$indicePila][2] = 0;
		$smarty->_tag_stack[$indicePila][5] = 0;
	}
	$iterActual = $smarty->_tag_stack[$indicePila][2];
    $smarty->assign('smty_iteracionActual',$iterActual);
	// ****************************************************//
	
	// NUM. ITERACIONES TOTALES .- posicion [3] de la pila de CWFila
	// conicide con el numero de registros que llegan desde el "negocio"
	$smarty->_tag_stack[$indicePila][3] = count($smarty->_tag_stack[$punteroPilaPadre][1]['datos']);
	$iterTotales = $smarty->_tag_stack[$indicePila][3];
	// ****************************************************//
	$conOrdenacion = true;
    if (isset($smarty->_tag_stack[$punteroPilaPadre][1]['noOrdenacion']))
    {
    	$conOrdenacion = false;
    }
    
    
    // Indica si debe incluirse el código JS que colorea la fila si el ratón pasa por encima 
	if($smarty->_tag_stack[$punteroPilaPadre][1]['animacionFila']) 
	{
		$animacionFila = $smarty->_tag_stack[$punteroPilaPadre][1]['animacionFila'];
	}
    
	// 	NUM. DE FILAS POR PANTALLA
	if($smarty->_tag_stack[$punteroPilaPadre][1]['numFilasPantalla']) 
	{
		$numFilasPantalla = $smarty->_tag_stack[$punteroPilaPadre][1]['numFilasPantalla'];
	}		
	// id del Panel para obtener el nombre del formulario
	if($smarty->_tag_stack[$punteroPilaBisAbuelo][1]['id']) 
	{
		$nomForm = "F_".$smarty->_tag_stack[$punteroPilaBisAbuelo][1]['id'];
	}
	
	// Clase Manejadora del Panel para obtener el nombre del formulario
	if($smarty->_tag_stack[$punteroPilaBisAbuelo][1]['claseManejadora']) 
	{
		$claseManejadora = $smarty->_tag_stack[$punteroPilaBisAbuelo][1]['claseManejadora'];
	}
	
	// ESTAMOS EN UN MAESTRO/DETALLE
	if (isset($smarty->_tag_stack[$punteroPilaBisAbuelo][1]['esMaestro']))
	{
		$esMaestro = $smarty->_tag_stack[$punteroPilaBisAbuelo][1]['esMaestro'];
	}
	else
	{
		$esMaestro = '';
	}
		
	// NUM. FILAS FALTAN para completar pantalla
	$numFilasFaltan = 0;
	if ($numFilasPantalla > 0)
	{
		$numFilasFaltan = ($numFilasPantalla - ($iterTotales%$numFilasPantalla));		
		if ($numFilasFaltan == $numFilasPantalla)
		{
			$numFilasFaltan = 0;
		}
	} 
	// ****************************************************//    
	
    // NUM. PAGINAS A INSERTAR
    $numPagInsertar = 1;
	if($smarty->_tag_stack[$punteroPilaPadre][1]['numPagInsertar']) 
	{
		$numPagInsertar = $smarty->_tag_stack[$punteroPilaPadre][1]['numPagInsertar'];
	}
	// FILAS NUEVAS	DE LAS PAG A INSERTAR.- posicion [4] de la pila de CWFila
	// sin contar las que completan la ultima pagina
	if (isset($smarty->_tag_stack[$indicePila][4]))
	{
		if ($iterActual > ($iterTotales + $numFilasFaltan))
		{
			$smarty->_tag_stack[$indicePila][4]--;
		}
	}
	else
	{
		$smarty->_tag_stack[$indicePila][4] = $numPagInsertar*$numFilasPantalla;
	}
	$numFilasNuevas = $smarty->_tag_stack[$indicePila][4];
	// ****************************************************//    
	
	// Identificador de la tabla 
	$idTabla = $smarty->_tag_stack[$punteroPilaPadre][1]['id'];
    
	if (!isset($content)) //Cuando encontramos la etiqueta de apertura
	{
 		////////////////////////////////////////////////////////////////////////////////////////////////
		// CODIGO NECESARIO PARA CADA COMPONENTE //
		////////////////////////////////////////////////////////////////////////////////////////////////
		// Primero defino el nombre del componente.
		$n_comp="CWFila";	
		
		// Necesitamos saber cuántas instancias de este componente existen ya 
		// para poner el codigo o no
		$num=$smarty->igepPlugin->registrarInstancia($n_comp);
		
		$idFila = "F_".$idTabla."_".$iterActual;
		
		//////////////////////////////////////////////////////////////////////////////////////////////////
		// FIN CODIGO NECESARIO DE CADA COMPONENTE //
		//////////////////////////////////////////////////////////////////////////////////////////////////
	}
	else //Encontramos la etiqueta de cierre
	{	
	 	// Obtenemos el vector d registros	 
	 	$datosTabla = $smarty->_tag_stack[$punteroPilaPadre][1]['datos'];
	 	
	 	// Obtenemos las referencias a las columnas	 
	 	if (count ($datosTabla) > 0) 
	 	{	 		
	 		$refColumna = array_keys($datosTabla[0]);
	 	}
	 	else
	 	{
	 		$refColumna = null;
	 	}	
	  
	  
	 	//Obtendremos el vector d titulos de campos
	 	$listado = isset($params['tipoListado']) ? $params['tipoListado'] : "false";
	 	
	 	//Inicializamos el valor para que no aparezca warning
	 	$v_titulosRef = array();
	 	
	 	if (isset($smarty->_tag_stack[$punteroPilaPadre][1]['titulosColumnas']))
	 	{		 		
	 		$v_titulosRef = $smarty->_tag_stack[$punteroPilaPadre][1]['titulosColumnas'];
	 	}	 	
	 	else if ( (isset($smarty->_tag_stack[$punteroPilaPadre][1]['datos'])) && ($listado=='true'))
	 	{		 		
	 		$v_titulosRef = array_keys($datosTabla[0]);	 		
	 		$smarty->_tag_stack[$punteroPilaPadre][1]['titulosColumnas'] = $v_titulosRef;
	 	}
	 		  
	 	$numCampos = count($v_titulosRef);
	
		$seleccionUnica = $smarty->_tag_stack[$punteroPilaPadre][1]['seleccionUnica']; 
		//Comprobamos si quieren la fila de checkbox
 		$conCheck = $smarty->_tag_stack[$punteroPilaPadre][1]['conCheck'];
	 	if ($conCheck == true)
	  	{
	 		$numCampos++;
	 	}

	 	$rowEdit = '';
	 	if (isset($smarty->_tag_stack[$punteroPilaPadre][1]['rowEdit']))
	 		$rowEdit = $smarty->_tag_stack[$punteroPilaPadre][1]['rowEdit'];
	 	
	  	// Para cuadrar la cabecera de la tabla (que se repetirá por capa)				
		// Formamos los títulos de la cabecera
		$numColsCabecera = $numCampos;
		$html_cabecera = '';
		
// REVIEW: Distribución de columnas
		// Si tipoListado = false distribuimos las columnas respecto al tamaño indicado en los componentes 
		/*if ($listado == 'false')
		{		
			$defColumnas = "<colgroup>";
			if (($conCheck) && ($rowEdit == ''))
				$defColumnas .= "<col width=\"2%\" />";	
			if (($conCheck) && ($rowEdit !=''))
				$defColumnas .= "<col width=\"5%\" />";
			$total = $smarty->_tag_stack[$punteroPilaPadre][6]['sizes']['total'];
			for($i=0;$i<count($v_titulosRef);$i++)
			{
				
				if ($total==0)
					$defColumnas .= "<col/>";
				else {	
					$ancho = intval($smarty->_tag_stack[$punteroPilaPadre][6]['sizes'][$i])*(95/$total);
					$defColumnas .= "<col width=\"".$ancho."%\" />";
				}
			}
			$defColumnas .= "</colgroup>";		
		//$html_cabecera .= $defColumnas;
		}*/
		
		$html_cabecera .= "<tr align='center' class='tabularHead'>";
		$conCheckTodos = false;
		if (isset($smarty->_tag_stack[$punteroPilaPadre][1]['conCheckTodos'])) 
		{
			$conCheckTodos = $smarty->_tag_stack[$punteroPilaPadre][1]['conCheckTodos'];
		}
		// Tiene checkbox para seleccionar todos en la cabecera
		if ( ($conCheckTodos == true) && ($seleccionUnica == false) ) 
		{
			if (($rowEdit == ''))
			{
				if ($listado == true)
					$html_cabecera .= "<th class='columnTitle' width=\"15px\" data-gvhTypeTab=\"panelList\">&nbsp;\n";
				else
					$html_cabecera .= "<th class='columnTitle' width=\"5px\" data-gvhTypeTab=\"panelTab\">&nbsp;\n";
			}
			else
				$html_cabecera .= "<th class='columnTitle' width=\"20px\" data-gvhTypeTab=\"panelTabRowEdit\">&nbsp;\n";
			$html_cabecera .= "<input type='checkbox' id='seleccionarTodo_".$iterActual."' name=\"seleccionarTodo_".$iterActual."\" value=\"N\" onClick=\"javascript:".$idPanel."_tabla.seleccionarTodos(this.name);\" />\n";
			$html_cabecera .= "</th>\n";
			$numColsCabecera--;
		}
		// No tiene checkbox para seleccionar todos en la cabecera
		else if ($conCheck == true)
		{
			if (($rowEdit == ''))
			{
				if ($listado == true)
					$html_cabecera .= "<th class='columnTitle' width=\"15px\" data-gvhTypeTab=\"panelList\">&nbsp;\n";
				else
					$html_cabecera .= "<th class='columnTitle' width=\"5px\" data-gvhTypeTab=\"panelTab\">&nbsp;\n";
			}
			else
				$html_cabecera .= "<th class='columnTitle' width=\"10px\" data-gvhTypeTab=\"panelTabRowEdit\">&nbsp;\n";
			$html_cabecera .= "&nbsp;\n";
			$html_cabecera .= "</th>\n";
			$numColsCabecera--;
		}
				
		$numCol = 0;
		$total = $smarty->_tag_stack[$punteroPilaPadre][6]['sizes']['total'];
		foreach ($v_titulosRef as $refCol => $titCol)
		{
			if ($total == 0)
			{
				$ancho = 'auto';
			}
			else
			{
				$ancho = intval($smarty->_tag_stack[$punteroPilaPadre][6]['sizes'][$numCol])*(95/$total)."%";
			}
			$html_cabecera .="<th class='columnTitle' width=\"".$ancho."\">\n";
			$html_cabecera .= "<label class=\"text tabularTitle\">";			
				
			if ( ($conOrdenacion) && ($titCol != '') )
			{
				$html_cabecera .= "<a href=\"javascript:";
					$html_cabecera .= $idPanel."_tabla.ordenarTabla('".$claseManejadora."','".$refCol."','ASC');";
					$html_cabecera .=" \">";
				//$html_cabecera .= "<img alt='v' src='".IMG_PATH_CUSTOM."acciones/16.gif' /></a>";
				$html_cabecera .= "<span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span>&nbsp;</a>";
			}										
			$html_cabecera .= $titCol;			
			if ( ($conOrdenacion) && ($titCol != '') )
			{	
				$html_cabecera .= "<a href=\"javascript:";
					$html_cabecera .= $idPanel."_tabla.ordenarTabla('".$claseManejadora."','".$refCol."','DESC');";
					$html_cabecera .=" \">";
					$html_cabecera .= "&nbsp;<span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a>";
				//$html_cabecera .= "<img alt='^'  src='".IMG_PATH_CUSTOM."acciones/14.gif' /></a>";				
			}
			$html_cabecera .="</label></th>\n";
			$numCol++;
		}
		
		$html_cabecera .= "</tr>\n";	
		$separa_fila = '';
	  
		// Número d filas
		$numFilas = count($datosTabla);
		
		// Obtenemos el número d página
		$numPagina = 1;
		if ($numFilasPantalla > 0)
			$numPagina = intval($iterActual/$numFilasPantalla);
		
	 	if ($numFilas == 0 && $smarty->_tag_stack[$indicePila][5] == 0) //Si NO hay datos sólo debemos pintar filas de inserción
	 	{
		 	$iterActual = 1;
		 	$iterTotales = 0;
		 	$numFilasFaltan = 0;
		}
  		
		// *************************************************************************** //
		// ************  ANIMACIÓN FILA **************************************** //
		// Identificador de la fila
		$idFila = $smarty->_tag_stack[$punteroPilaPadre][1]['id']."_".($iterActual-1);
	  	// Campo oculto para indicar el estado de la fila
	  	$colspan = $numCampos;
	  	$ini_fila = "<tr><td colspan='$colspan'><input type='hidden' id='est_".$idFila."' name='est_".$idFila."' value='nada' /></td></tr>\n";
	  	// Código html de la fila
	  	if ($animacionFila == "true") //Incluimos o no incluimos el javascript
	  	{
			$ini_fila .= "<tr id=\"".$idFila."\" onMouseOver=\"javascript:".$idPanel."_tabla.overFila(this);\" onMouseOut=\"javascript:".$idPanel."_tabla.outFila(this);\">\n";
	  	}
	  	else
	  	{
	  		$classFila = '';
	  		$fondo = $smarty->_tag_stack[$punteroPilaPadre][1]['datos'][$iterActual-1]['__gvHidraRowColor'];
			if((!isset($fondo)) || (empty($fondo)) || ($fondo == '') )
			{
				$classFila = 'oddRecord';
				if (($iterActual%2) == 0)
				{
					$classFila = 'evenRecord';
				}
			}
			$ini_fila .= "<tr id=\"".$idFila."\" class=\"".$classFila." ".$fondo."\">\n";
	  	}
	  	
	  	// *************************************************************************** //
		// *************************************************************************** //	  	
	  	$seleccionado ='';
	  	
	  	$checkFila = "";
	  	/// MAESTRO/DETALLE ////	  	 
	  	if ($esMaestro == "true")
	  	{
	  		$claseManejadora = $smarty->_tag_stack[$punteroPilaBisAbuelo][1]['claseManejadora'];
	  		// Obtenemos el elemento seleccionado del maestro
	  		if (!isset($smarty->_tag_stack[$punteroPilaBisAbuelo][1]['itemSeleccionado']))
	  		{
	  			$seleccionado ='';
	  		}
	  		else
	  		{
	  			$itemSeleccionado = $smarty->_tag_stack[$punteroPilaBisAbuelo][1]['itemSeleccionado'];
	  			if ($itemSeleccionado == ($iterActual-1))
	  			{
	  				$seleccionado = "checked";
	  			}
	  		}
	  		if ( ($conCheck == true) && ($iterActual <= $iterTotales) )
	  		{	  		
		  		$check = "check_".$idFila;
		  		$ini_fila .= "<td class='columnTitle'>";
		  		$ini_fila .= "<input type='checkbox' name=\"";
		  			$ini_fila .= $check."\" id=\"".$check."\" ".$seleccionado;
		  			$ini_fila .= " onClick=\"if(this.checked) {".$idPanel."_tabla.deseleccionarTodos2(this.id);".$idPanel."_tabla.checkFila('$idFila');this.form.action='phrame.php?action=";
		  			$ini_fila .= $claseManejadora."__recargar';aviso.mostrarMensajeCargando('Cargando');this.form.target='oculto';this.form.submit();} else {this.checked=true}\" />\n";
		  		$ini_fila .= "</td>\n";
		  		$conCheck = false;
	  		}
	  	}
	  	/// FIN MAESTRO/DETALLE ////
	  	
	  	// Para cuando NO es maestro/detalle
	  	// CON CHECKBOX.- La tabla tiene columna con CheckBox
	  	if ( ($conCheck == true) && ($iterActual <= $iterTotales) )
	  	{
	  		$check = "check_".$idFila;
	  		if ($idPanel == 'vSeleccion')
	  			$check = "vsCheck_".$idFila;
			$checkFila .= "<td class='columnTitle'>\n";
	  		$checkFila .= "<input type='checkbox' id=\"".$check."\" name=\"".$check."\" onClick=\"";

	  		if ($seleccionUnica == true)
				$checkFila.= $idPanel."_tabla.deseleccionarTodos2(this.id);";

			$checkFila.= $idPanel."_tabla.checkFila('$idFila');";			
	  		
			$checkFila.= "\" />\n ";
			
			if ($rowEdit != '')
			{
				$nomObjeto = "bttlModificar_".$idPanel;
				$tresModos = false;
				if ($rowEdit == 'ficha')
					$tresModos = true;
				$iter = $iterActual-1;
				$llamadaJavascript = $nomObjeto." = new objBTTModificar('".$nomObjeto."','".$idPanel."','".$esMaestro."','".$esDetalle."','".$tresModos."');";				
				$llamadaJavascript .= "document.getElementById('$check').checked = true;";
				$llamadaJavascript .= $idPanel."_tabla.deseleccionarTodos('$check');";
				if ($rowEdit == 'tabla')
				{
					$llamadaJavascript .= $idPanel."_tabla.checkFila('$idFila');";
					$llamadaJavascript .= "if (".$idPanel."_tabla.hayFilaSeleccionada()) ";
					$llamadaJavascript .= $nomObjeto.".modificarTabla();";
				}
				elseif ($rowEdit == 'ficha')
				{
					$nomForm = 'F_'.$idPanel;
					$llamadaJavascript .= "document.forms['".$nomForm."'].target='oculto';";
					$llamadaJavascript .= "document.forms['".$nomForm."'].action='phrame.php?action=".$claseManejadora."__editar';";
					$llamadaJavascript .= "document.forms['".$nomForm."'].submit();";
				}
				$id = "btnEdit_".$idFila;
				$ruta = IMG_PATH_CUSTOM."botones/02.gif";

				$boton = "<button type='button' title='Editar registro' id='".$id."' data-gvhposition='panel_$idPanel' style='display:inline;' class='btnToolTip btnRowEdit' onClick=\"javascript:".$llamadaJavascript."\">\n";
				$boton .= "<span id='icon_".$id."' class='glyphicon glyphicon-edit' aria-hidden='true'></span> ";
				$boton .= "</button>";
				
				$checkFila .= $boton;
			}
			$checkFila.= "</td>\n";
	  	}
	  	elseif (($conCheck == true))
	  	{
	  		$checkFila .= "<td class='columnTitle'>\n";
	  		$checkFila .= "&nbsp;";
	  		$checkFila .= "</td>\n";
	  	}
  		$ini_fila .= $checkFila;
	  	
		// Si estamos en una iteración con datos en los registros
	
	  	if ($iterActual <= $iterTotales)
	  	{
	  		// Como incrementamos al inicio del Block, iterActual está adelantada
			// con respecto a los datos (que empiezan en 0) pero no respecto a la
			// paginación (que empieza en uno), por lo que en función de lo que estemos
			// haciendo, emplearemos iterActual o iterActual - 1	
			
			// REGISTRO ACTUAL, solo en el caso de que tratemos filas con datos
			if($iterActual<=$iterTotales)
			{
				$regActual = $datosTabla[($iterActual-1)]; // Registro de la fila		
			}

// REVIEW: Nueva ventana de selección			
		  	// LISTADO SIN COMPONENTES
			//if (($idPanel == 'vSeleccion') && ($smarty->_tag_stack[$indicePila][1]['vSeleccion'] != 1))
			//if (($listado == 'true') || 
			if (($idPanel == 'vSeleccion') && ($listado == 'true') && ($smarty->_tag_stack[$indicePila][1]['vsTPL'] != 1)) 
			{
				foreach ($regActual as $key => $value) 
				{
					$classFila = 'oddRecord';
					if (($regActual%2) == 0)
					{
						$classFila = 'evenRecord';
					}
   					$ini_fila .= "<td>\n";
   					$ini_fila .= "<input type='text' class=\"text tableNoEdit\" readOnly id=\"".$key."_".($iterActual-1)."\" name=\"".$key."_".($iterActual-1)."\" value=\"".$value."\" />\n";
   					$ini_fila .= "</td>\n";
				}
			}
	  		// ******************************************************* //

			$clickFila = '';
			$tablePanel = 'tablePanel_';

			$esDetalle = '';
			if (($smarty->_tag_stack[$punteroPilaBisAbuelo][1]['detalleDe']) != '')
			{
				$esDetalle = 'true';
				$tablePanel = 'tablePanelD_';
			}			
			// PRIMERA ITERACIÓN.- PRIMERA PÁGINA
			if($iterActual == 1)
			{
				$ini_tabla = "<!-- INICIO CAPA DE LA PÁGINA 0 -->\n";	
				$ini_tabla .= "<div id=\"pag_".$idTabla."_0\" class=\"table-responsive tabular\" data-gvhTable=\"DataTable\">\n";
				$ini_tabla .= "<table id='".$tablePanel."0' class='table table-bordered'>\n";
									
				$fin = $clickFila;

			//	$fin .= "<input type='hidden' id='est_".$idFila."' name='est_".$idFila."' value='nada' />\n";
				$fin .= "</tr>\n";
				$resultado=$ini_tabla.$html_cabecera.$ini_fila.$content.$fin.$separa_fila;
			}
	  		// ULTIMA FILA D LA PÁGINA PERO NO ES LA ÚLTIMA PÁGINA	
			elseif ( ($iterActual%$numFilasPantalla == 0) && ($iterActual < $iterTotales) ) 
			{
				// Cerrar página anterior	
				$fin = $clickFila;
			//	$fin .= "<input type='hidden' id='est_".$idFila."' name='est_".$idFila."' value='nada' />\n";
				$fin .= "</tr>\n".$separa_fila;				
				$fin .="</table>\n";
				$fin .="</div>\n";
				$fin .= "<!-- CIERRO CAPA DE LA PÁGINA ".($numPagina - 1)." -->\n";
				// Abrir una página nueva
				$fin .= "<!-- INICIO CAPA DE LA PÁGINA ".$numPagina." -->\n";
				$fin .= "<div id=\"pag_".$idTabla."_".$numPagina."\"  class=\"table-responsive tabular\"  data-gvhTable=\"DataTable\">\n";
				$fin .= "<table id='".$tablePanel.$numPagina."' class='table table-bordered'> \n";
				$resultado=$ini_fila.$content.$fin.$html_cabecera;
			}
			// ÚLTIMA PÁGINA
			elseif ($iterActual == $iterTotales) 
			{
				$fin = $clickFila;
			//	$fin .= "<input type='hidden' id='est_".$idFila."' name='est_".$idFila."' value='nada' />\n";
				$fin .="</tr>\n".$separa_fila;
				if ($numFilasFaltan == 0) 
				{	// Última página, es completa.					
					$fin .="</table>\n";			
					$fin .="</div>\n";
					$fin .= "<!-- CIERRO ÚLTIMA PÁGINA COMPLETA CON DATOS -->\n";
					$resultado=$ini_fila.$content.$fin;
				}
				else 
				{
					// Última página pero HAY q completar con filas vacías					
					$resultado=$ini_fila.$content.$fin;
				}
			}
			// FILAS INTERMEDIAS
			else 
			{
			//	$fin .= "<input type='hidden' id='est_".$idFila."' name='est_".$idFila."' value='nada' />\n";
				$fin = "</tr>\n";
				$resultado=$ini_fila.$content.$fin.$separa_fila;
			}		    	
	  }   
 // HEMOS SUPERADO LAS ITERACIONES CON DATOS, VAMOS A INSERTAR FILAS NUEVAS
	 // else   //es decir $iterActual > $iterTotales
	  elseif ($iterActual <= ($iterTotales+$numFilasFaltan+($numPagInsertar*$numFilasPantalla))) 
	  {
		// FILAS COMPLETAR ÚLTIMA PÁGINA	
		if ( 
			($numFilasFaltan != 0) && 
			($iterActual <= ($iterTotales + $numFilasFaltan))			
			)
		{
			$insert = "<!-- INSERTAREMOS UNA FILA -->\n";
			$insert .= $ini_fila;
			$insert .= $content;
			$insert .= $clickFila."</tr>\n";
			$insert .= $separa_fila;
			// Hemos llegado a la última fila, hay q cerrar la tabla
			if ($iterActual == $iterTotales+$numFilasFaltan) 
			{
				$insert .= "</table></div>\n";
				$insert .= "<!-- CERRAMOS ÚLTIMA PÁGINA D DATOS CON FILAS INSERTAR -->\n";
			}
			$resultado = $insert;		
		}
		else 
		{
			//////////////////////////////////////////////////////////////////////////////////////////////////////////
			// PÁGINAS NUEVAS PARA INSERTAR .- numFilasNuevas > 0
			// Hemos completado ya la última página d datos con filas nuevas
			$pagIns = "";		
			$FilasTotalesConInsertar = $iterTotales + $numFilasFaltan + $numFilasNuevas+1;
			$tablePanel = 'tablePanel_';

			$esDetalle = '';
			if (($smarty->_tag_stack[$punteroPilaBisAbuelo][1]['detalleDe']) != '')
			{
				$esDetalle = 'true';
				$tablePanel = 'tablePanelD_';
			}
			// Estamos en la primera fila de la primera página a insertar
			if ( $iterActual == ($iterTotales + $numFilasFaltan+1))
			{	
				$ini_tabla = "<!-- INICIO CAPAS INSERTAR.- PÁGINA ".$numPagina." -->\n";	
				$ini_tabla .= "<div id=\"pag_".$idTabla."_".$numPagina."\" class=\"table-responsive tabular\">\n";
				$ini_tabla .= "<table id='".$tablePanel.$numPagina."' class='table table-bordered'>\n";
				$fin = "</tr>\n";
				$resultado = $ini_tabla.$html_cabecera.$ini_fila.$content.$fin.$separa_fila;		
			}
			// Última fila de la página a insertar, hay más páginas para insertar
			elseif ( ($iterActual%$numFilasPantalla == 0) && ($iterActual < $FilasTotalesConInsertar) ) 
			{
				// Cerrar página anterior	
				$fin = "</tr>\n".$separa_fila;				
				$fin .="</table>\n";
				$fin .="</div>\n";
				$fin .= "<!-- INSERCIÓN.- CIERRO CAPA DE LA PÁGINA ".($numPagina - 1)." -->\n";
				// Abrir una página nueva
				$fin .= "<!-- INSERCIÓN.- INICIO CAPA DE LA PÁGINA ".$numPagina." -->\n";
				$fin .= "<div id=\"pag_".$idTabla."_".$numPagina."\" style=\"display:none;\">\n";
				$fin .= "<table id='".$tablePanel.$numPagina."'>\n";
				$resultado=$ini_fila.$content.$fin.$html_cabecera;
			}
			// Última página a insertar
			elseif ( $iterActual == $iterTotales + $numFilasFaltan + ($numFilasPantalla * $numPagInsertar))
			{
				$fin = "</tr>\n".$separa_fila;
				$fin .="</table>\n";
				$fin .="</div>\n";
				$fin .= "<!-- INSERCIÓN.- CIERRO ÚLTIMA PÁGINA -->\n";
				$resultado=$ini_fila.$content.$fin;
			}
			// Filas intermedias
			else 
			{
				$fin = "</tr>\n";
				$resultado=$ini_fila.$content.$fin.$separa_fila;
			}	
		}		
	}
	else
	{
		$total = $iterTotales+$numFilasFaltan+($numPagInsertar*$numFilasPantalla);
		if ( $iterActual == ($total+1))
		{	
			$ini_insBusqueda = "<!-- CAPA PARA INSERCIÓN DESDE BÚSQUEDA-->\n";
			$ini_insBusqueda .= "<div id=\"pag_lis_999\" style=\"display:none;\">\n";
			$ini_insBusqueda .= "<table id='".$tablePanel."999' class='table table-bordered'>\n";				
				
			$fin_insBusqueda = "</tr>\n";
			$resultado = $ini_insBusqueda.$html_cabecera.$ini_fila.$content.$fin_insBusqueda.$separa_fila;
		} 
		elseif ( $iterActual == ( $total + $numFilasPantalla ) )
		{
			$fin_insBusqueda = "</tr>\n".$separa_fila;
			$fin_insBusqueda .="</table>\n";
			$fin_insBusqueda .="</div>\n";
			$fin_insBusqueda .= "<!-- INSERCIÓN.- CIERRO PÁGINA INSERCIÓN BÚSQUEDA-->\n";
			$resultado = $ini_fila.$content.$fin_insBusqueda;
		}
		else 
		{
				$fin_insBusqueda = "</tr>\n";
				$resultado=$ini_fila.$content.$fin_insBusqueda.$separa_fila;
		}
	}
	
	
	$total = $iterTotales+$numFilasFaltan+($numPagInsertar*$numFilasPantalla);
	$total = $total + $numFilasPantalla;
	if ($iterActual < $total) 
	{
		$repeat = 1;		
	}
	  
	return($resultado);
  }
	
}	
?>