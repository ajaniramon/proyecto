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

function smarty_block_CWSelector($params, $content, &$smarty) {
		
	//////////////////////////////////////////////////////////////////////
	// LECTURA DE VALORES DE LA PILA //
	//////////////////////////////////////////////////////////////////////
	
	//Puntero a la pila de etiquetas que contiene a CWSelector
	$indicePila = count($smarty->_tag_stack)-1;
	//Puntero a la etiqueta Padre (CWFicha || CWSolapa) 
	$punteroPilaPadre = $indicePila - 1;
	$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0];
	if(!isset($content)) // Si se abre la etiqueta 
	{	
		$n_comp = "CWSelector";	
		// Necesitamos saber cuántas instancias de este componente existen ya / para poner el codigo o no
		$num=$smarty->igepPlugin->registrarInstancia($n_comp);
		//Array para almacenar los campos q contendra este plugin
		$campos = array();
		$smarty->_tag_stack[$indicePila][2]  =  $campos;
	} 
	else 
	{
		if ($CWPadre == "CWSolapa")//Si el padre es un CWSolapa, tenemos que movernos uno más arriba, pq pasamos de él
		{		
			// Subimos 3 posiciones: 1 pq la pila empieza en 0, con -2 estaríamos en CWSolapa, -3 llegamos a CWFicha
			$punteroPilaPadre = count($smarty->_tag_stack)-3; 
			$CWPadre = $smarty->_tag_stack[$punteroPilaPadre][0]; // Ahora es CWFicha		
		}
		
		$punteroPilaAbuelo = $punteroPilaPadre - 1;
		$CWAbuelo = $smarty->_tag_stack[$punteroPilaAbuelo][0]; // CWFichaEdicion
			
		$igepSmarty = new IgepSmarty();
		
		$ini = "";
		$fin = "";
		
		$titulo = "";
		if ($params['titulo'])
		{
			$titulo = $params['titulo'];
		} 
		
		$id = "";
		if ($params['nombre'])
		{
			$id = $params['nombre'];
			$idCWSelector = $params['nombre'];
		} 
		
		$editable = 'true';
		if($params['editable'])
		{
			$editable = $params['editable'];
		}
		
	    $rows = 3;
	    if($params['rows'])
	    {
	      $rows = $params['rows'];
	    }
    
		$botones = array('insertar');
		if($params['botones'])
		{
			$botones = $params['botones'];
		}
		
		$separador =" | ";
		if($params['separador'])
		{
			$separador = $params['separador'];
		}
		
		//El vector de datos sera un array asociativo de tres columna (valor, descripción, seleccionado)
		$v_datos = array();
		$v_lista[0]=array();	
		$v_lista[0]["valor"]="";
		$v_lista[0]["descripcion"]="";
		$v_datos[0]["seleccionado"]="";	
		$v_datos[0]["lista"]=$v_lista;	
		
		if($params['datos'])
		{
			$v_datos = $params['datos'];
		}
		if($params['value'])
		{
			$v_datos = $params['value'];
		}
		
	
		//if ($CWAbuelo == "CWFichaEdicion") 
		if ($CWPadre == "CWFicha") // Comprobamos el padre pq el Selector puede estar en un panel de búsqueda.
		{
			$iterActual = $smarty->_tag_stack[$punteroPilaPadre][2];		
			$numRegTotales = count($smarty->_tag_stack[$punteroPilaAbuelo][1]['datos']);
			$iterActualExtra=0;
			$numRegTotalesExtras=0;
			
			$idFila = $smarty->_tag_stack[$punteroPilaAbuelo][1]['id']."_".$iterActual;
			
			
			$punteroPilaPanel = $punteroPilaAbuelo - 2;
			$CWPanel = $smarty->_tag_stack[$punteroPilaPanel][0];
			$idPanel = $smarty->_tag_stack[$punteroPilaPanel][1]['id'];
			
			if ($iterActual >= $numRegTotales)
			{	
				// Componer el nombre dl campo: params[nombre]_idFila
				// ej: cad_inv_dni__F_tabla1_2
				$idCampo = "ins___".$id."___".$idFila."[]";
				$nameCampo = "ins___".$id."___".$idFila."[]";				
				
				if ($CWPadre == "CWFicha")
				{
					$campoEstadoFila = "est_".$idFila;
					$llamadaJS = "document.getElementById('".$campoEstadoFila."').value='insertada';";
					$igepSmarty->addAccionEvento("onBlur", $llamadaJS);
				}
			}
			else
			{		
				// Componer el nombre dl campo: params[nombre]_idFila
				// ej: cad_inv_dni__F_tabla1_2
				if ($CWAbuelo == "CWFichaEdicion") // Comprobamos el abuelo para saber si es un panel de edición o de búsqueda
				{
					$idCampo = "cam___".$id."___".$idFila."[]";
					$nameCampo = "cam___".$id."___".$idFila."[]";
				}
				else				
				{
					$idCampo = $id."[]";
					$nameCampo = $id."[]";
				}
				if ($CWPadre == "CWFicha")
				{
					$llamadaJS = $idPanel."_comp.comprobarModificacion('".$idCampo."');";
					$igepSmarty->addAccionEvento("onBlur", $llamadaJS);
				}
				//Obtenemos el registro que le corresponde...
				
				$datosReg = $smarty->_tag_stack[$punteroPilaAbuelo][1]['datos'][$iterActual];

				//Si todo ha ido bien, tendremos un array registro con uno de los atributos multivaluado
				//Asignamos el valor de registro, controlando el PEAR:DB			
				//Si es la nueva version de PEAR, da igual que sea Postgres que Oracle (columna en minúsculas)
				//$v_datos = $datosReg[$params['nombre']];
					
				$v_datos = null;
				if (!isset($v_datos)) 
				{
					$v_datos = $datosReg[strtolower($params['nombre'])];				
				}
				//Si aqui aun no tiene valor, puede ser Pear "case sensitive" contra Postgres (columna mayúscula/minúsculas)
				if (!isset($v_datos)) 
				{
					$v_datos = $datosReg[$params['nombre']];					
				}
				//Por último, si aquí tampoco tiene valor, puede ser Pear "case sensitive" contra Oracle (columna mayúsculas)
				if (!isset($v_datos)) 
				{
					$v_datos = $datosReg[strtoupper ($params['nombre'])];				
				}			
				if (!isset($v_datos)) 
				{
					$v_datos = array();
					$v_lista[0]=array();	
					$v_lista[0]["valor"]="";
					$v_lista[0]["descripcion"]="";
					$v_datos[0]["seleccionado"]="";	
					$v_datos[0]["lista"]=$v_lista;				
				}			
			}
			
			// editable = true,false,nuevo | si/no/nuevo
			switch($CWPadre) 
			{
			case "CWFicha":
						switch($editable)
						{
							case "true":
							case "si":
								// Comprobamos si se vuelve a cargar la plantilla con la acción modificar/insertar, activar los campos
								if ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'modificar') {
									$classHTML = " class=\"text modify\"";
									$estadoHTML = "";
								}
								else if ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'insertar') {	
									$classHTML = " class=\"text edit\"";
									$estadoHTML = ""; 
								}
								else {
									$classHTML = " class=\"text edit\"";
									// Comprobamos el abuelo para saber si es un panel de edición o de búsqueda
									if ($CWAbuelo == "CWFichaEdicion") 
										$estadoHTML = " disabled";
									else
										$estadoHTML = " enabled";
								}
							break;
							case "false":
							case "no":
								$classHTML = " class=\"text noEdit\"";
								$estadoHTML = " disabled";
							break;
							case "nuevo":
								// Comprobamos si se vuelve a cargar la plantilla con la acción modificar/insertar, activar los campos
								if ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'modificar') {
									$classHTML = " class=\"text new\"";
									$estadoHTML = " disabled";
								}
								else if ($smarty->_tag_stack[$punteroPilaAbuelo][1]['accion'] == 'insertar') {	
									$classHTML = " class=\"text edit\"";
									$estadoHTML = ""; 
								}
								else {
									$classHTML = " class=\"text new\"";
									$estadoHTML = " disabled";
								}
							break;
						}
					break;
					default:
						$classHTML = " class=\"text edit\"";
						$estadoHTML = "";
				}
		}
	

		$ini .= "<br/>".$titulo."<br/>\n";
		$ini .= "<table style='width: 100%;' cellspacing='3' cellpadding='3'>\n";
		$ini .= "<tr>\n";
		$ini .= "<td style='width: 20%;' class='text groupFields'>\n";
		if ($CWPadre == "CWFicha")
		{
			$llamadaJS = $idPanel."_comp.bloquearSalida(true);";
			$igepSmarty->addAccionEvento("onChange", $llamadaJS, 1);
		}
		$ini .= "<select name='".$nameCampo."' id='".$idCampo."' ".$igepSmarty->getAcciones()." multiple ".$classHTML." ".$estadoHTML." size='".$rows."'>\n";
		
		$numValores=count($v_datos['lista']);
		for($i=0; $i < $numValores; $i++)
		{		
				if(is_array($v_datos['lista'])){
					$ini.="<option ";
					//Si ese es el item seleccionado lo marcamos			
					if (trim($v_datos['seleccionado']) == trim($v_datos['lista'][$i]['valor'])) $ini .=" selected ";
					$ini .="value='".$v_datos['lista'][$i]['valor']."' selected>".trim($v_datos['lista'][$i]['descripcion'])."</option>";
				}			
		}
		
		
		$ini .= "</select>\n";
		$ini .= "</td>\n";
		$ini .= "<td style='width: 5%;' class='text groupFields'>\n";
		
		//////////////////////////////////////////////////////////////////////////////////////////
		////////// BOTONES TOOLTIP
		//////////////////////////////////////////////////////////////////////////////////////////
		$vCampos = $smarty->_tag_stack[$indicePila][2];
		$camposOrigen = "";
		for($i=0;$i<count($vCampos);$i++)
		{
			$camposOrigen .= "'".$vCampos[$i]."'";
			if ($i<count($vCampos)-1) $camposOrigen .= ",";
		}

		$esDetalle = 'false';
		$esMaestro = 'false';
		if (isset($smarty->_tag_stack[$punteroPilaPanel][1]['detalleDe']))
		{
			$esDetalle = 'true';
		}
		if (isset($smarty->_tag_stack[$punteroPilaPanel][1]['esMaestro']))
		{
			$esMaestro = $smarty->_tag_stack[$punteroPilaPanel][1]['esMaestro'];
		}

		$nomObjeto ='';
		if (in_array('insertar',$botones)) 
		{
			$nomObjeto = "bttInsertar_".$idCWSelector;
			$llamadas_js .= $nomObjeto." = new objBTTInsertar('".$nomObjeto."','".$idPanel."','".$esMaestro."','".$esDetalle."');";
			$ini .= "<a href=\"javascript:".$nomObjeto.".copiarToLista('".$idCampo."','".$separador."',".$camposOrigen.")\">";
			if ( (($CWAbuelo != "CWContenedor") && ($CWAbuelo != "CWTabla")) && (
				($smarty->_tag_stack[$punteroPilaPanel][1]['accion'] == 'modificar') ||
				($smarty->_tag_stack[$punteroPilaPanel][1]['accion'] == 'insertar')) )
			{
				$ini .= "<img alt='&lt;' style='border:none' src='".IMG_PATH_CUSTOM."botones/61.gif' id='selCopiar".$idCWSelector."___".$idFila."' title='Copiar' /></a>\n";
			}
			else
			{
//				$ini .= "<img alt='.' style='border:none' src='".IMG_PATH_CUSTOM."pestanyas/pix_trans.gif' id='selCopiar".$idCWSelector."___".$idFila."' /></a>\n";
// Necesitamos tb la imagen de los botones para cuando el selector está un panel de búsqueda
$ini .= "<img alt='.' style='border:none' src='".IMG_PATH_CUSTOM."botones/61.gif' id='selCopiar".$idCWSelector."' /></a>\n";
			} 
			$ini .= "<br>\n";			
		}
		if (in_array('modificar',$botones)) 
		{
			$nomObjeto = "bttModificar_".$idCWSelector;
			$llamadas_js .= $nomObjeto." = new objBTTModificar('".$nomObjeto."','".$idPanel."','".$esMaestro."','".$esDetalle."');";
			$ini .= "<a href=\"javascript:".$nomObjeto.".modificarLista('".$idCampo."','".$separador."',".$camposOrigen.")\">";
			if ( (($CWAbuelo != "CWContenedor") && ($CWAbuelo != "CWTabla")) && (
				($smarty->_tag_stack[$punteroPilaPanel][1]['accion'] == 'modificar') ||
				($smarty->_tag_stack[$punteroPilaPanel][1]['accion'] == 'insertar')) )
			{
				$ini .= "<br><img alt='&gt;' style='border:none' src='".IMG_PATH_CUSTOM."botones/60.gif' id='selModificar".$idCWSelector."___".$idFila."' title='Modificar' /></a>\n";
			}
			else
			{
//				$ini .= "<img alt='.' style='border:none' src='".IMG_PATH_CUSTOM."pestanyas/pix_trans.gif' id='selModificar".$idCWSelector."___".$idFila."' /></a>\n";
// Necesitamos tb la imagen de los botones para cuando el selector está un panel de búsqueda
$ini .= "<br><img alt='.' style='border:none' src='".IMG_PATH_CUSTOM."botones/60.gif' id='selModificar".$idCWSelector."' /></a>\n";
			} 
			$ini .= "<br>\n";
		}
		if (in_array('eliminar',$botones)) 
		{
			$nomObjeto = "bttEliminar_".$idCWSelector;
			$llamadas_js .= $nomObjeto." = new objBTTEliminar('".$nomObjeto."','".$idPanel."','".$esMaestro."','".$esDetalle."');";
			$ini .= "<a href=\"javascript:".$nomObjeto.".eliminarLista('".$idCampo."','".$separador."',".$camposOrigen.")\">";
			if ( (($CWAbuelo != "CWContenedor") && ($CWAbuelo != "CWTabla")) && (
				($smarty->_tag_stack[$punteroPilaPanel][1]['accion'] == 'modificar') ||
				($smarty->_tag_stack[$punteroPilaPanel][1]['accion'] == 'insertar')) )
			{
				$ini .= "<br><img alt='X' style='border:none' src='".IMG_PATH_CUSTOM."botones/42.gif' id='selLimpiar".$idCWSelector.$idFila."___"."' title='Limpiar' /></a>\n";
			}
			else
			{
//				$ini .= "<img alt='.' style='border:none' src='".IMG_PATH_CUSTOM."pestanyas/pix_trans.gif' id='selLimpiar".$idCWSelector."___".$idFila."' /></a>\n";
// Necesitamos tb la imagen de los botones para cuando el selector está un panel de búsqueda
$ini .= "<br><img alt='.' style='border:none' src='".IMG_PATH_CUSTOM."botones/42.gif' id='selLimpiar".$idCWSelector."' /></a>\n";				
			} 
		}		
		//Registramos el objeto JS
		if ($nomObjeto!='') $smarty->igepPlugin->registerJSObj($nomObjeto);
		
		$igepSmarty->addPreScript($llamadas_js); 
		///////////////////////////////////////////////////////////////////////////////////////////////////////		
		
		$ini .= "</td>\n";
		$ini .= "<td style='width: 50%;' align='left' class='text groupFields'>\n";
		
		$fin .= "</td>\n";
		$fin .= "</tr>\n";
		$fin .= "</table>\n<br/>";
		

		$resultado = $igepSmarty->getPreScript().$ini.$content.$fin;
		return ($resultado);
	}
}
?>