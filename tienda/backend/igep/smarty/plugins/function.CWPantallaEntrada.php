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

define('GVHIDRA_IMGMODULO', 'menu/menu.gif');
define('GVHIDRA_IMGRAMA', 'menu/51.gif');
define('GVHIDRA_IMGOPCION', 'menu/43.gif');
define('GVHIDRA_TRANSPARENCIA', '');

/**
 * Este fichero contiene el pluggin que construye la
 * además contiene un función recursiva encargada d
 * leer los ficheros XML donde se describen los
 * distintos menús de la aplicación:
 * El principal (/APL/include/menuModulos.xml)
 * El de Administración (/APL/include/menuAdministracion.xml)
 * El de herramientas (/APL/include/menuHerramientas.xml)
 *
 * Nota: APL es la plaicación que usa gvHidra
 *
 * @author	Veronica Navarro <navarro_ver@gva.es>
 * @author	Antonio Felix <felix_ant@gva.es>
 * @author	David Pascual <pascual_dav@gva.es>
 * @version	$Revision: 1.105 $
 * @package igep
 * @category	Archive
 */

/**
 * La función siguiente recibe un nodo del árbol XML y comprueba si le corresponde acceso
 * o no en función de la lista de módulos y roles del usuario activo (ubicado en la SESSION)
 *
 * @access public
 * @param SimpleXML	$nodoControl	Nodo SimpleXML de los descritos en el fichero MenuModulos.xml
 * @return boolean
 */
function comprobarAcceso($nodoAControlar)
{
	$retorno = true; //Valor por defecto

	//Si no hay nodos de control de acceso permiso concedido
	if (!isset($nodoAControlar->controlAcceso)) return($retorno);
	
	//Para cada posible nodo de control de acceso...
	foreach ($nodoAControlar->controlAcceso as $nodoControlAcceso)
	{
		$retorno = false; //Si hay nodos de ocntro lde acceso el valor por defecto es false
		//Tratamiento de roles
		foreach ($nodoControlAcceso->rolPermitido as $nodoRol)
		{
			$idModRol = trim(utf8_decode($nodoRol['valor'])); //Cogemos el atributo valor
			$sesionRol = trim(ComunSession::dameRol());
			if ($sesionRol==$idModRol) return (true); //Permiso concedido!!
		}

		//Tratamiento de módulos de acceso
		$v_sesionModulos = IgepSession::dameModulos();
		foreach ($nodoControlAcceso->moduloPermitido as $nodoModulo) //Para cada nodo moduloPermitido
		{
			$idNodoModulo = trim(utf8_decode($nodoModulo['id']));
			if ($v_sesionModulos[$idNodoModulo]) //Si existe el módulo en la sesión
			{
				$numHijos = 0;
				foreach ($nodoModulo->valorModulo as $nodoValor)
				{
					$str_valorXML = trim(utf8_decode($nodoValor['valor']));
					$str_valorSesion = trim($v_sesionModulos[$idNodoModulo]['valor']);
					if ($str_valorSesion == $str_valorXML) return (true); //Permiso concedido!!
					$numHijos++;
				}//fForeach
				//Si NO hay hijos basta con que aparezca en la sesión
				if ($numHijos == 0) return true; //Permiso concedido!!
			}//Fin if existe el módulo en la sesión
		}//fin para cada nodo moduloPermitido
	}//fin foreach
	return($retorno);
}//Fin function comprobarAcceso



/**
 * El procedimiento encapsula a otra función recursiva, añadiéndo
* inicialización de variables. Genera la cadena del plugin MenuLayer
* @access private
* @param string	$rutaFichero	Cadena que controla el nivel de profundidad en el arbol XML
* @return array	Vector asociativo de dos dimensiones (html,menu) donde se almacena con cadena la pantalla HTML y los menus
*/
function creaMenu($ficheroXML)
{
	//Activamos el log de errores
	libxml_use_internal_errors(true);
 	
	if (file_exists($ficheroXML))
		$simpleXML = simplexml_load_file($ficheroXML, SimpleXMLElement, LIBXML_DTDVALID);
	else
		//REVIEW: David Gestión Errores try/catch/throw
		exit('Error!!');
		
	$v_errores = libxml_get_errors();
	$i_numErrores = count($v_errores);
	if ($i_numErrores>0)
	{
		//REVIEW: David Gestión Errores try/catch/throw
		print_r($v_errores);
		exit('Error!!');
	}
	
	$titColumna = strtolower(basename($ficheroXML, '.xml'));
	$prefColumna = 'X'; //Prefijo que incluira cada capa de cada columna (los tres menús)
	switch($titColumna)
	{
		case 'menumodulos':
			$prefColumna = 'M';
			$menuActv = 'menuMP';
		break;
		case 'menuherramientas';
			$prefColumna = 'H';
			$menuActv = 'menuHA';
		break;
	
		case 'menuadministracion';
			$prefColumna = 'A';
			$menuActv = 'menuAS';
		break;
	}
	$idColumna = $prefColumna.md5($titColumna);
		
	$datosMenu = array
	(
		'menuActv' => $menuActv,
		'modActv' => null,
		'prefijo' => $prefColumna,
		'idRaiz' => $idColumna,
		'descRaiz' => $titColumna,
		'idPadre' => $idColumna,
		'descPadre' => $titColumna
	);

	//Obtenemos TODOS los módulos
	$vMenu = $simpleXML->xpath('//menu');
	$vSimpleXML = $simpleXML->xpath('//modulo');
	$numModulos = count($vSimpleXML);

	// Cadenas para generar las capas con las opciones de los módulos
	$strCapasOpciones = "";
	
	$vMenuModulosTXT = array();
	$vMenuModulosHTML = array();
	$vMenuModuloUbicacion = array();
	
	//Capa que encierra la lista de modulos
	$vMenuModulosHTML[$idColumna].="\n<div id='$idColumna' class='mainMenu'";
	
	$vMenuModulosHTML[$idColumna].=GVHIDRA_TRANSPARENCIA;
	$vMenuModulosHTML[$idColumna].="' >";
	$vMenuModulosHTML[$idColumna].="<div class='contentMainMenu'>";
	
	$vMenuModuloUbicacion[$idColumna]="<div><ul><li id='W$idColumna'>.";
	//$vMenuModuloUbicacion[$idColumna]="<div><ul><li id='W$idColumna'>.";
	
	$stringNivel='.';
	$menuModuloTXT ='';	
	if ($numModulos > 1)//Si hay mas de un módulo, los presentamos todos como opciones
	{
		foreach ($simpleXML->modulo as $nodoModulo) //Para cada modulo...
		{
			if (!comprobarAcceso($nodoModulo)) continue;
			
			$descModulo = utf8_decode($nodoModulo['titulo']);
			$idModulo = $prefColumna.'M'.md5($idColumna.$descModulo);
			$datosMenu['modActv']=$idModulo;
			
			$menuModuloTXT =".|Menu||$descModulo";
			
			$imgModulo = GVHIDRA_IMGMODULO;
			if (isset($nodoModulo['imagen']))//Si se especifica una imagen...
				$imgModulo = utf8_decode($nodoModulo['imagen']);
			
			$menuModulosHTML ='';
			$menuModulosHTML.= "<img src='".IMG_PATH_CUSTOM.$imgModulo."' alt='+' title='";
			$menuModulosHTML.= $descModulo."' />";
			$menuModulosHTML.="<b><a class='text linkOption' ";
			$menuModulosHTML.="href='javascript:mostrarOpciones(\"$idModulo\")'>";
			$menuModulosHTML.="$descModulo</a></b><br/>";
			
			$vMenuModuloUbicacion[$idColumna].="\n<ul><li id='W$idModulo'>$descModulo<ul>\n";//$vMenuModuloUbicacion[$idColumna].="\n<ul>\n";
						
			desplegarNodo($nodoModulo, 'M', $datosMenu, $menuModuloTXT, $vMenuModulosHTML, $vMenuModuloUbicacion[$idColumna], $stringNivel);
						
			$vMenuModulosHTML[$idColumna].=$menuModulosHTML;			
			$vMenuModulosTXT[$idModulo] = $menuModuloTXT;
			$vMenuModuloUbicacion[$idColumna].="</ul></li></ul>\n";//$vMenuModuloUbicacion[$idColumna].="</ul>\n";
		}//Fin para cada módulo
		$vMenuModulosHTML[$idColumna].="\n</div>\n</div>";
		
	}
 	elseif ($numModulos == 1) //Un sólo módulo, simplificamos el primer nivel
	{

		$nodoModulo = $vSimpleXML[0];
		if (!comprobarAcceso($nodoModulo)) return;
					
		$titModulo = utf8_decode($nodoModulo['titulo']);
		$descModulo = utf8_decode($nodoModulo['descripcion']);
		if (empty($descModulo))
			$descModulo = $titModulo;
		
		$menuModuloTXT =".|Menu||$descModulo";
		
		$idModulo = $idColumna;
		$datosMenu['modActv']=$idModulo;
		$datosMenu['idPadre'] = $idModulo;
		$datosMenu['descPadre'] = $descModulo;
		$datosMenu['idRaiz'] = $idModulo;
		
		$vMenuModuloUbicacion[$idColumna].="\n<ul>\n";//$vMenuModuloUbicacion[$idColumna].="\n<ul>\n";//$vMenuModuloUbicacion[$idColumna].="<ul>\n";
		$vMenuModuloUbicacion[$idColumna]="<div><ul><li id='W$idColumna'>.<ul>";
		
		foreach ($nodoModulo->children() as $nombre=>$hijo) //Para cada posible nodo...
		{
			if (!comprobarAcceso($hijo)) continue;
			
			$titHijo = utf8_decode($hijo['titulo']);
			$descHijo =utf8_decode($hijo['descripcion']);
			if (empty($descHijo)) $descHijo = $titHijo;
			$idNodoHijo = $datosMenu['prefijo'].'R'.md5($idModulo.$titHijo);
			$stringNivel='.';
			
			if (strtolower(trim($nombre)) == 'rama')
			{
				$idHijo = $datosMenu['prefijo'].'R'.md5($idModulo.$titHijo);
				$imgHijo = GVHIDRA_IMGRAMA;
				if (isset($nodoHijo['imagen']))//Si se especifica una imagen...
				$imgHijo = utf8_decode($nodoHijo['imagen']);
								
				$vMenuModulosHTML[$idModulo].= "<img src='".IMG_PATH_CUSTOM.$imgHijo."' alt='+' title='";
				$vMenuModulosHTML[$idModulo].= $descHijo. "' />";
				$vMenuModulosHTML[$idModulo].="<b><a class='text linkOption' ";
				$vMenuModulosHTML[$idModulo].="href='javascript:mostrarOpciones(\"$idHijo\")'>";
				$vMenuModulosHTML[$idModulo].="$titHijo</a></b><br/>";
				$stringNivelRama = $stringNivel.'.';
				
				$vMenuModuloUbicacion[$idColumna].="<li id='W$idNodoHijo'>$titHijo<ul>\n";				
				
				desplegarNodo($hijo, 'R', $datosMenu, $menuModuloTXT, $vMenuModulosHTML, $vMenuModuloUbicacion[$idColumna], $stringNivelRama);
				
				$vMenuModuloUbicacion[$idColumna].="</ul></li>\n";
			}
			elseif (strtolower(trim($nombre)) == 'opcion')
			{
				$imgHijo = GVHIDRA_IMGOPCION;
				if (isset($nodoHijo['imagen']))//Si se especifica una imagen...
					$imgHijo = utf8_decode($nodoHijo['imagen']);
				construirMenuOpcion($hijo, $datosMenu, $menuModuloTXT, $vMenuModulosHTML[$idModulo], $vMenuModuloUbicacion[$idModulo], $stringNivel);
			}//Fin rama - opcion
		}//Fin para cada hijo
		$ini_pantalla .= $strCapaModulo."</div>".$vHTML;
		$vMenuModulosHTML[$idModulo].= "\n</div>";
		$vMenuModulosHTML[$idColumna].="\n</div>\n";
		
		$vMenuModuloUbicacion[$idColumna].="</ul>\n";
				
		$vMenuModulosTXT[$idModulo] = $menuModuloTXT;
	}//Fin if-else numero módulos

	if ($numModulos == 0) // Cerramos las capas cdo no hay opciones para mantener la estructura
	{
		$vMenuModulosHTML[$idColumna].="&nbsp;</div></div>";
	}
	$retorno['menuTXT'] = $vMenuModulosTXT;
	$retorno['html']='';
	
	$numModulos = count($vMenuModulosHTML);
	reset($vMenuModulosHTML);
	for ($i=0; $i<$numModulos; $i++)
	{
		$retorno['html'] .= current($vMenuModulosHTML);
		next($vMenuModulosHTML);
	}
	
	$vMenuModuloUbicacion[$idColumna].="\n</li></ul></div>\n\n";
	
	$numModulos = count($vMenuModuloUbicacion);
	reset($vMenuModuloUbicacion);
	for ($i=0; $i<$numModulos; $i++)
	{
		$retorno['ubicacion'] .= current($vMenuModuloUbicacion);
		next($vMenuModuloUbicacion);
	}
	
	$retorno['titulo'] = utf8_decode($vMenu[0]['titulo']);
	$retorno['idRaiz'] = $datosMenu['idRaiz'];	
	return ($retorno);
}//fFuncion creaMenu


/**
 * Recorre el subarbol XMLgenera la cadena del plugin MenuLayer
 * y la parte del HTML correspondiente al subarbol modulo/rama
 * Se le invoca desde el plugin
 * @access public
 * @param SimpleXML	$nodo	Nodo XML del tipo Rama de los descritos en el fichero MenuModulos.xml
 * @param string		$tipoNodo	Cadena de texto que indica si es un módulo o una rama 'R'|'M'
 * @param array		$datosPadre	Array asociativo con datos del nodo padre y el raíz
 * @param string		$menuTXT	Cadena de texto que representa el menu de la aplicacion y sirve de entrada al pluggin MenuLAyer
 * @param array		$menuHTML	Array asociativo que contendrá el código relativo a cada capa
 * @param array		$menuModuloUbicacion	Array asociativo que contendrá el código relativo a cada capa
 * @return void
 */
function desplegarNodo($nodo, $tipoNodo, $datosPadre, &$menuTXT, &$menuHTML, &$menuModuloUbicacion, $stringNivel)
{
	
	$aux_HTML='';
	$aux_Ubicacion='';
	
	if (!comprobarAcceso($nodo)) return;

	//Información Nodo
	$titNodo = utf8_decode($nodo['titulo']);
	$idNodo = $datosPadre['prefijo'].$tipoNodo.md5($datosPadre['idPadre'].$titNodo);
	$descNodo =utf8_decode($nodo['descripcion']);
	if (empty($descNodo)) $descNodo = $titNodo;
	
	//REVIEW: David - Constantes para imágenes
	if (strtolower($tipoNodo) =='R')
		$imgNodo=GVHIDRA_IMGRAMA;
	else //Módulo
		$imgNodo=GVHIDRA_IMGMODULO;

	if (isset($nodo['imagen']))//Si se especifica una imagen para la opción...
		$imgNodo = $nodo['imagen'];

	
	if ($tipoNodo=='M')
	{
		$menuTXT.='';
		//$menuModuloUbicacion.= "\n\t<li id='W$idNodo'>$titNodo<ul>\n\t";
	}
	else if ($tipoNodo=='R')  //Los módulos NO generan MenuTxt
	{
		$menuTXT.= "\n$stringNivel|$titNodo||$descNodo|";
		//$aux_Ubicacion.= "<ul><li>$titNodo</li><ul>";
	}
	else // Opción o tipo O	
	{
		$menuTXT.='';
	}
	
	
	
	//Capa que envuelve el despliegue del Nodo
	$aux_HTML.= "<div id='$idNodo' class='mainSubMenu' style='display:none; ";
	$aux_HTML.=GVHIDRA_TRANSPARENCIA;
	$aux_HTML.="' >";
	$aux_HTML.="<div class='contentMainSubMenu'>";
	
	//Retorno al padre
	//REVIEW: Imágenes de retorno al padre distintas?
	$aux_HTML.= "<img src='".IMG_PATH_CUSTOM."menu/subir.gif' alt='^' title='";
	$aux_HTML.= $datosPadre['descPadre']. "' />";
	$aux_HTML.="<i><a class='text linkOption' href='javascript:mostrarOpciones(\"";
	$aux_HTML.=$datosPadre['idPadre']."\")'>&nbsp;Volver</a></i><br/>";
	
	$datosPadre['idPadre']=$idNodo;
	$datosPadre['descPadre']=$descNodo;
	
	//Para cada nodo hijo...
	foreach ($nodo->children() as $nombre => $hijo)
	{
		if (strtolower(trim(utf8_decode($nombre))) == 'rama')//Si el hijo es rama
		{
			if (!comprobarAcceso($hijo)) continue;
			$titNodoHijo = utf8_decode($hijo['titulo']);
			$idNodoHijo = $datosPadre['prefijo'].'R'.md5($idNodo.$titNodoHijo);
			if (isset($hijo['imagen']))//Si se especifica una imagen para la rama...
				$imgNodoHijo = utf8_decode($hijo['imagen']);
			else
				$imgNodoHijo = GVHIDRA_IMGRAMA;
		
			$stringNivelRama=$stringNivel.'.';
			
			$aux_HTML.= "<img src='".IMG_PATH_CUSTOM.$imgNodoHijo."' alt='++' title='";
			$aux_HTML.= $titNodoHijo. "' />";
			$aux_HTML.="<a class='text linkOption' href='javascript:mostrarOpciones(\"";
			$aux_HTML.=$idNodoHijo."\")'>".$titNodoHijo;
			$aux_HTML.="</a><br/>";

			
			$menuModuloUbicacion.= "<li id='W$idNodoHijo'>$titNodoHijo\n\t<ul>";		

			desplegarNodo($hijo, 'R', $datosPadre, $menuTXT, $menuHTML, $menuModuloUbicacion, $stringNivelRama);

			$menuModuloUbicacion.="</ul></li>";
		}
		elseif (strtolower(trim($nombre)) == 'opcion')
		{
			construirMenuOpcion($hijo, $datosPadre, $menuTXT, $aux_HTML, $menuModuloUbicacion, $stringNivel);
		}
	}
	if ($tipoNodo=='M')
	{
		//$menuModuloUbicacion.="</ul></li>\n";
	}
	elseif ($tipoNodo=='R')
	{
		$menuModuloUbicacion.='';
	}
$menuHTML[$idNodo] = $menuHTML[$idNodo].$aux_HTML.'</div></div>';
$menuModuloUbicacion.=$aux_Ubicacion;
}//Fin desplegarNodo


/**
 * Genera la cadena del plugin MenuLayer y la parte del HTML
 * correspondiente a un nodo hoja, 'opcion'
 * Se le invoca desde el plugin
 * @access public
 * @param SimpleXML		$nodoOpcion		Nodo SimpleXML del tipo opcion
 * @param array			$datosPadre	Array asociativo con datos del nodo padre y el raíz
 * @param string		$menuTXT	Cadena de texto que representa el menu de la aplicacion y sirve de entrada al pluggin MenuLAyer
 * @param string		$menuHTML	Cadena HTML que contine las capas y el js para su control en el menu de la pantalla principal
 * @return void
 */
function construirMenuOpcion($nodoOpcion, $datosPadre, &$menuTXT, &$menuHTML, &$menuModuloUbicacion, $stringNivel)
{
	if (!comprobarAcceso($nodoOpcion)) return;

	$titOpcion = utf8_decode($nodoOpcion['titulo']);
	$idOpcion = utf8_decode($nodoOpcion['id']);
	$descOpcion = utf8_decode($nodoOpcion['descripcion']);
	$url = utf8_decode($nodoOpcion['url']);
	$tipoUrl = utf8_decode($nodoOpcion['tipoUrl']);
	$abrirVentana = strtolower(trim(utf8_decode($nodoOpcion['abrirVentana'])));
	$imgOpcion = GVHIDRA_IMGOPCION;
	
	$modActv = $datosPadre['idRaiz'];
	
	if (isset($nodoOpcion['imagen']))//Si se especifica una imagen para la opción...
		$imgOpcion = utf8_decode($nodoOpcion['imagen']);

	$menuHTML.= "<img src='".IMG_PATH_CUSTOM.$imgOpcion."' title='$descOpcion' alt='-'/>";
	$menuModuloUbicacion.='';
	
	if ($descOpcion == '')
		$title = '';
	else
		$title = "title='".$descOpcion."'";
		
	//Si se quiere sacar la ventana de Acerca de...
	if ($url =="about")
	{
		//$menuHTML .= " <a class='text linkOption' href='#' onClick='javascript:about.mostrarAbout();' ".$title." target='oculto'>$titOpcion</a><br>\n";
		$menuHTML .= "<a class='text linkOption' href='#' data-toggle='modal' data-target='#myModal'> Acerca de</a><br>\n";
		$menuModuloUbicacion.="<li><a href='#'>$titOpcion</a></li>";
	}
	else {
		// Hay que abrir ventana emergente
		if ($abrirVentana=='true')
		{
			$sizeWindow = "1000,500";
			if ($nodoOpcion['sizeWindow'])
				$sizeWindow = utf8_decode($nodoOpcion['sizeWindow']);
			$urlTarget = '||_blank|';
			$menuHTML.=" <a class='text linkOption' ".$title." href=\"javascript:Open_Vtna('$url','urlAbs',$sizeWindow,'no','no','no','no','yes','yes')\">$titOpcion</a><br/>\n";
			$menuModuloUbicacion.="<li><a href='#'>$titOpcion</a></li>\n";
		}
		else
		{
			$urlTarget= "||oculto|";
			$url.= "&amp;modActv=".$datosPadre['modActv']."&amp;menuActv=".$datosPadre['menuActv'];
			$menuHTML.=" <a class='text linkOption' href='$url' ".$title." target='oculto'>$titOpcion</a><br>\n";
			$menuModuloUbicacion.="<li><a href='#'>$titOpcion</a></li>\n";
		}
	}
	
	$menuTXT.= "\n".$stringNivel.".|$titOpcion|$url|$descOpcion.$urlTarget\n";
}// Fin construirMenuOpcion



 

/**
 * Construcción de la pantalla de acceso princiapl a la aplicación gvHidra
 *
 */

function smarty_function_CWPantallaEntrada($params, &$smarty)
{
	if ($params['usuario'])
	{
		$usuario = $params['usuario'];
	}
	else
	{
		$usuario = '';
	}
	
	if ($params['nomApl'])
	{
		$nomApl = $params['nomApl'];
	}
	else
	{
		$nomApl = '';
	}
	
	if ($params['codApl'])
	{
		$codApl = $params['codApl'];
	}
	else
	{
		$codApl = '';
	}
		
	if ($params['version'])
	{
		$versionApl = $params['version'];
	}
	else
	{
		$versionApl = '';
	}

	if ($params['gvhidraversion'])
	{
		$gvHidraVersion = $params['gvhidraversion'];
	}
	else
	{
		$gvHidraVersion = '';
	}
	
	$ubicacion = false;
	if (
		(isset($params['ubicacion']))
		&&
		(
			($params['ubicacion'] === true) || (strtolower(trim($params['ubicacion'])) =='true')
		)
	)
	{	
		$ubicacion = true;
	}

	
	$smarty->igepPlugin->registrarInclusionJS('pantallaInicio.js');
	$smarty->igepPlugin->registrarInclusionJS('window.js');
	
	
	$igepSmarty = new IgepSmarty();
	$script .= "about.set('about','capaAbout','".$nomApl."','Versión ".$versionApl."','".$gvHidraVersion."');";
	
	$igepSmarty->addPreScript($script);		
	$ini_pantalla .= $igepSmarty->getPreScript();
	
	
		$ini_pantalla .= "<div class='main-top-bar text-center row'>".$usuario."";
		$ini_pantalla .= "<div class='pull-right'>\n";
		$ini_pantalla .= "<form id='cerrar' class='main-close' name='cerrar' target='oculto' method='get' action='phrame.php'>\n";
			$ini_pantalla .= "<input type='hidden' id='action' name='action' value='cerrarAplicacion'>";
			$ini_pantalla .= "<input type='hidden' id='permitirCerrarAplicacion' name='permitirCerrarAplicacion' value='si' />\n";

			$ini_pantalla .= "<button type='button' id='close' title='Salir' style='display:inline;' class='btnToolTip' onClick=\"javascript:cerrarAplicacion(document.forms['cerrar']);\">\n";
			$ini_pantalla .= "<span class='glyphicon glyphicon-log-out' aria-hidden='true'></span> ";
			$ini_pantalla .= "</button>";
				
			
			$ini_pantalla .= "</form>\n";
		
					
		$ini_pantalla .= "</div>\n";
		$ini_pantalla .= "<div style='clear:both;'></div>";
	$ini_pantalla .= "</div>\n";

	
		
		$ini_pantalla .= "<div class='main-center text-center row '>\n";
			$ini_pantalla .= "<div id='descrTitle'>".$nomApl."</div>";
			$ini_pantalla .= "<div id='title'>".$codApl."</div>";
			$ini_pantalla .= "<div id='version'>Versión ".$versionApl."</div>";
			$ini_pantalla .= "<div style='clear:both;'></div>";
		$ini_pantalla .= "</div>\n";
	
	
	

/**********************/	
/* CONTAINER MODULES */
	

	/* -------------------------- MENU MÓDULOS (Principal) -------------------------- */
	$vRetorno = creaMenu(MODXML_PATH.'menuModulos.xml');
	IgepSession::guardaVariable ("global", "menuMP", $vRetorno['menuTXT']);
	$ini_menuModulos = '';
	$ini_menuModulos.= $vRetorno['html'];
	
	//Zona de inicio de cada columna	
	//$ini_zonaModulos .= "<div id='titleModulesMP'>";
	$ini_zonaModulos .= '<div id=\'MM_menu\' style=\'display:none;\'>'.$vRetorno['ubicacion'].'</div>';
	$ini_zonaModulos.="<a class=\"titleModules\" href='javascript:mostrarOpciones(\"";
	$ini_zonaModulos.=$vRetorno['idRaiz']."\")'>";
	
	/*if ($vRetorno['titulo'] == '')
		$ini_zonaModulos.= "Módulos principales";
	else
		$ini_zonaModulos.= $vRetorno['titulo'];*/
		
	$ini_zonaModulos.="</a>";
	//$ini_zonaModulos.= "&nbsp;</div>\n";

	/* ------------------------------ MENU HERRAMIENTAS ------------------------------ */
	$vRetorno = creaMenu(MODXML_PATH.'menuHerramientas.xml');
	IgepSession::guardaVariable ("global", "menuHA", $vRetorno['menuTXT']);
	$ini_menuHerramientas ='';
	$ini_menuHerramientas.= $vRetorno['html'];
	
	//$ini_zonaModulos .= "<div id='titleModulesHA'>";
	$ini_zonaModulos .= '<div id=\'MH_menu\' style=\'display:none;\'>'.$vRetorno['ubicacion'].'</div>';
	$ini_zonaModulos.="<a class=\"titleModules\" href='javascript:mostrarOpciones(\"";
	$ini_zonaModulos.=$vRetorno['idRaiz']."\")'>";
	
	/*if ($vRetorno['titulo'] == '')
		$ini_zonaModulos.= "Herramientas auxiliares";
	else
		$ini_zonaModulos.= $vRetorno['titulo'];*/
	$ini_zonaModulos.="</a>";
	//$ini_zonaModulos.= "&nbsp;</div>\n";
	
	/* ----------------------------- MENU ADMINISTRACIÓN ------------------------------ */
	$vRetorno = creaMenu(MODXML_PATH.'menuAdministracion.xml');
	IgepSession::guardaVariable ("global", "menuAS", $vRetorno['menuTXT']);
	$ini_menuAdministracion ='';
	$ini_menuAdministracion.= $vRetorno['html'];
	
	//Cabecera de la columna
	//$ini_zonaModulos .= "<div id='titleModulesAS'>";
	$ini_zonaModulos .= '<div id=\'MA_menu\' style=\'display:none;\'>'.$vRetorno['ubicacion'].'</div>';
	$ini_zonaModulos.="<a class=\"titleModules\" href='javascript:mostrarOpciones(\"";
	$ini_zonaModulos.=$vRetorno['idRaiz']."\")'>";	
	/*if ($vRetorno['titulo'] == '')
		$ini_zonaModulos.="Administración sistema";
	else
		$ini_zonaModulos.= $vRetorno['titulo'];*/
	$ini_zonaModulos .="</a>";	
	//$ini_zonaModulos .= "&nbsp;</div>\n";

	//Concatenamos la zona de inicio de columnas
	$ini_pantalla .= $ini_zonaModulos;
	//Cerramos la zona de columnas


/*************************/

if ($ubicacion)// MIGAS DE PAN
{
	$smarty->igepPlugin->registrarInclusionCSS('breadcrumbs.css');
	$ini_pantalla .= "<div id='breadcrumbs' class='transparency-background'>";
	$ini_pantalla .= "<ul id='breadcrumbs-one' class='transparency-background'></ul>";
	//$ini_pantalla .= "<ul id='breadcrumbs-one'><li>.</li></ul>";//Solo si queremos evitar desplazamiento inicial
	$ini_pantalla .= "</div>";
	
}


//Concatenamos los menus HTML de cada columna
	
	$ini_zonaMod = "<div class='title-module'>Módulos principales</div>";
	$ini_zonaHer = "<div class='title-module'>Herramientas auxiliares</div>";
	$ini_zonaAdmi = "<div class='title-module'>Administración sistema</div>";
	                  
	$ini_pantalla .= "<div class='main-modules row'>\n";					 
			
	$ini_pantalla .="<div style='padding-top:15px;'>";
	
	$ini_pantalla .="<div class='col-md-4'>";
	$ini_pantalla .="<div class='panel-modules'>";
	$ini_pantalla .=$ini_zonaMod;
	$ini_pantalla .="<div id='zonamod'>";
	$ini_pantalla .=$ini_menuModulos;
	$ini_pantalla .="</div>";
	$ini_pantalla .="</div>";
	$ini_pantalla .="</div>";
	
	$ini_pantalla .="<div  class='col-md-4'>";
	$ini_pantalla .="<div class='panel-modules'>";
	$ini_pantalla .=$ini_zonaHer;
	$ini_pantalla .="<div id='zonaher'>";
	$ini_pantalla .=$ini_menuHerramientas;
	$ini_pantalla .="</div>";
	$ini_pantalla .="</div>";
	$ini_pantalla .="</div>";
	
	$ini_pantalla .="<div class='col-md-4'>";
	$ini_pantalla .="<div class='panel-modules'>";
	$ini_pantalla .=$ini_zonaAdmi;
	
	$ini_pantalla .="<div id='zonaad'>";
	$ini_pantalla .=$ini_menuAdministracion;
	
	$ini_pantalla .="</div>";
	$ini_pantalla .="</div>";
	$ini_pantalla .="</div>";
	
	$ini_pantalla .="</div>";
	$ini_pantalla .="</div>";
	
	$ini_pantalla .="<div class='row text-center main-logo'>";
	$ini_pantalla .= "<img src='".IMG_PATH_CUSTOM."logos/logo.gif'>";
	$ini_pantalla .="</div>";
	
	

	/*ventana modal acerca de */
	
	$ini_pantalla .= "<div class='modal fade' id='myModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>";
	$ini_pantalla .= "<div class='modal-dialog'>";
	$ini_pantalla .= "<div class='modal-content text-center'>";
	$ini_pantalla .="<div class='modal-header'>";
	$ini_pantalla .="<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
	$ini_pantalla .="<h4 class='modal-title' id='myModalLabel'>$nomApl</h4>";
	$ini_pantalla .="</div>";
	
	$ini_pantalla .="<div class='modal-body'>";
	$ini_pantalla .="<div class='block-modal-body'>";
	$ini_pantalla .="Nombre de la aplicación:<b> $codApl </b><br/>";
	$ini_pantalla .="Versión: <b> $versionApl </b><br/></br>";
	
	$ini_pantalla .="<div>";
	$ini_pantalla .= "Aplicación de:</p><img class='img-responsive' src='".IMG_PATH_CUSTOM."logos/logo.gif'>";
	$ini_pantalla .="</div>";
	$ini_pantalla .="</div>";
	$ini_pantalla .="</div>";
	
	
	$ini_pantalla .="<div class='modal-footer' style='text-align: center' >";
	$ini_pantalla .= "<img src='".IMG_PATH_CUSTOM."logos/gvlogo.png'>&nbsp;&nbsp;";
	$ini_pantalla .="Implementado con gvHidra versión:<b> $gvHidraVersion </b><br/>";
	$ini_pantalla .="</div>";
	$ini_pantalla .="</div>";
	$ini_pantalla .="</div>";
	$ini_pantalla .="</div>";
	
	/*fin ventana modal acerca de */
	
	
	
	
	/*$fin_pantalla .= "<tr>\n";	
$fin_pantalla .= "<td class='footer'>";
$fin_pantalla .= "<img class="img-responsive" src='".IMG_PATH_CUSTOM."logos/logo.gif'>";
    $fin_pantalla .= "</td>\n";
    $fin_pantalla .= "</tr>\n";*/
	
	$fin_pantalla .= "</div>\n"; //fin container
	return $ini_pantalla.$fin_pantalla;
}//Fin funcion

?>