<?php
/* gvHIDRA. Herramienta Integral de Desarrollo R�pido de Aplicaciones de la Generalitat Valenciana
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
*  Av. Blasco Ib��ez, 50
*  46010 VALENCIA
*  SPAIN
*  +34 96386 24 83
*  gvhidra@gva.es
*  www.gvpontis.gva.es
*
*/
require_once('igep/include/IgepSmarty.php');

include ("./igep/smarty/plugins/menuLayer/lib/PHPLIB.php");
include ("./igep/smarty/plugins/menuLayer/lib/layersmenu-common.inc.php");
include ("./igep/smarty/plugins/menuLayer/lib/layersmenu.inc.php");
/**
* Pluggin Menu
*
* Adapatacion a Smarty de un sobconjunto del Menu GPL PHPML de Marco Pratesi
* @author  David <pascual_dav@gva.es>
* @author  Keka <bermejo_mjo@gva.es>
* @author  Toni <felix_ant@gva.es>
* @author  Raquel <borjabad_raq@gva.es>
* @author  Ver�nica <navarro_ver@gva.es>
*/

function smarty_function_CWMenuLayer($params,  &$smarty)
{
	$igepSmarty = new IgepSmarty();
	
	$n_comp="CWMenuLayer";	
	// Incrementamos  el n�mero de componentes 
	$num = $smarty->igepPlugin->registrarInstancia($n_comp);
		
	//Comineza la inicializaci�n del objeto Menu
	$mid = new LayersMenu();	//Constructor sin par�metros de apariencia
	
	//Le asignamos el nombre a la instancia
	if ($params['name']) //Si viene por par�metro...
	{
		$str_nombreMenu=$params['name'];
	}
	else //Si no est� establecido...
	{
		$str_nombreMenu=$n_comp.$num; 
	}
	
	//Definimos si vamos a ubicar las im�genes
	//dentro del subdirectorio del plugin, o vamos
	//a emplear la carpeta de im�genes de la aplicaci�n	
	if ($params['usarImagenesAplicacion']=="true") //Si viene por par�metro...
	{
		//aqui NO
		$myDirPath = "./igep/smarty/plugins/menuLayer/";	 	
		$mid->setDirroot($myDirPath);
		$mid->setImgdir("../../../../images/"); //Subimos al directorio de la aplicaci�n 
		$myWwwPath = "./igep/smarty/plugins/menuLayer/"; //Marcamos el directorio Web vac�o
		$mid->setImgwww($myWwwPath."images/");
		//Guardamos la ruta a la imagen para el menuOff
		$imagenRaizMenu ="./igep/smarty/plugins/menuLayer/images/";
	}
	else //Si no est� establecido...
	{		
		$myDirPath = "./igep/smarty/plugins/menuLayer/";	 	
		$mid->setDirroot($myDirPath);
		$mid->setImgdir("images/"); //Subimos al directorio de la aplicaci�n 
		$myWwwPath = "./igep/smarty/plugins/menuLayer/"; //Marcamos el directorio Web vac�o
		$mid->setImgwww($myWwwPath."images/");
		$imagenRaizMenu ="./igep/smarty/plugins/menuLayer/images/";
	}
	
	//Miramos si est�n fijados los parametros de las im�genes
	//imagen de descenso e imagen de despliegue del men�
	if ($params['imgDescenso'] && $params['imgDespliegue']) 
	{
		$mid->setDownArrowImg($params['imgDescenso']);
		$mid->setForwardArrowImg( $params['imgDespliegue']);
		$imagenRaizMenu.=$params['imgDescenso'];
	}
	else
	{
		$mid->setDownArrowImg("down-arrow.png");
		$mid->setForwardArrowImg("forward-arrow.png");
		$imagenRaizMenu.="down-arrow.png";
	}
		
	$smarty->igepPlugin->registrarInclusionCSS('layersmenu-cit.css');
	

	
	$smarty->igepPlugin->registrarInclusionJS('layersmenu-library.js','./igep/smarty/plugins/menuLayer/libjs/');
	$smarty->igepPlugin->registrarInclusionJS('layersmenu.js','./igep/smarty/plugins/menuLayer/libjs/');

	//A continuaci�n, definimos la estructura del menu estructura. La definici�n es OBLIGATORIA		
	//Para hacerlo, debemos utilizar UNA y SOLO UNA de estas opciones:	
	//	- Existe el par�metro fichero que indica el un fichero con una estructura del menu
	//	- Existe el parametro cadenaMenu que contiene un string con la estructura del men�	
	if ($params['fichero']  && $params['cadenaMenu']) //Si est�n fijados LOS dos par�metros
	{
		mydebug("CW::MenuLayer: ERROR  debe elegirse la estructura del menu mediante los dos posibles par�metros");		
	}
	else if ($params['fichero']) //Si se ha elegido definir la estructura en un fichero
	{
		//OJO con las rutas, comprobar que funciona.... o pasar par�metros para generalizar
		$fich_str = "".$params['fichero'];		
		$mid->setMenuStructureFile($fich_str);
	}
	else if ($params['cadenaMenu']) //Si se ha elegido definir la estructura en un fichero
	{
		$mid->setMenuStructureString(trim($params['cadenaMenu']));
	}


	//A continuaci�n generamos la cadena HTML correspondiente al componente		
	$mid->parseStructureForMenu($str_nombreMenu);
	$mid->newHorizontalMenu($str_nombreMenu);	
	$mid->makeHeader();	
	$str_cabecera = $mid->getHeader();
	$str_menu = $mid->getMenu($str_nombreMenu);
	$mid->makeFooter();
	$str_pie = $mid->getFooter();
	
	$str_menuCompleto ="";
	$str_menuCompleto.="<div id='capa_menuFalso' style='display:none' ";
		// tipoAviso | codError | descBreve | textoAviso
		$capa_js .= "onClick=\"javascript:";
		$capa_js .= "aviso.set('aviso','capaAviso',";
		$capa_js .= "'ALERTA', ";
		$capa_js .= "'IGEP IU', ";
		$capa_js .= "'Cambios pendientes',";
		$capa_js .= "'Existen datos pendientes de salvar. <br/>SALVE o CANCELE los mismos antes de salir.');";				
		$capa_js .= "aviso.mostrarAviso();\"";			
	$str_menuCompleto.=$capa_js;
	$str_menuCompleto.=" >";
	
	$str_menuCompleto.="Menu&nbsp;<img src='".$imagenRaizMenu."' alt='-' /> ";
	
	$str_menuCompleto.="</div>";
	$str_menuCompleto.="<div id='capa_menuReal' style='display:inline;'>";
	$str_menuCompleto.=$str_cabecera.$str_menu.$str_pie;
	$str_menuCompleto.="</div>";
	
	
	return($estilo.$script.$str_menuCompleto);

}//Fin function.CWMenuLayer
?>
