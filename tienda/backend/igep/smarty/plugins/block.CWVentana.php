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
* Pluggin CWVentana
*
* Componente ventana
* @author  David Pascual <pascual_dav@gva.es>
* @author  Keka <bermejo_mjo@gva.es>
* @author  Antonio Felix <felix_ant@gva.es>
* @author  Raquel Borjabad <borjabad_raq@gva.es>
* @author  Verónica <navarro_ver@gva.es>
*
*/
function smarty_block_CWVentana($params, $content, &$smarty)
{
	$igepSmarty = new IgepSmarty();
	$igepSmarty->inicioVentana();

	$llamadaJS = "";
	$igepSmarty->addAccionEvento("onLoad", $llamadaJS, 200);

	if(!isset($content)) // Si se abre la etiqueta {CWVentana}...
	{
		$smarty->igepPlugin->registrarInclusionJS('window.js');
		$smarty->igepPlugin->registrarInclusionJS('avisos.js');
		$smarty->igepPlugin->registrarInclusionJS('escape.js');
		$smarty->igepPlugin->registrarInclusionJS('jquery1_11_2.js');

		$smarty->igepPlugin->registrarInclusionJS('raphael.min.js');
		$smarty->igepPlugin->registrarInclusionJS('morris.min.js');
		$smarty->igepPlugin->registrarInclusionJS('jquery.js');

		$smarty->igepPlugin->registrarInclusionJS('bootstrap.js');
		$smarty->igepPlugin->registrarInclusionJS('colResizable-1.5.min.js');
		$smarty->igepPlugin->registrarInclusionCSS('normalize.css');
		$smarty->igepPlugin->registrarInclusionCSS('bootstrap.css');
		$smarty->igepPlugin->registrarInclusionCSS('aplicacion.css');
		$smarty->igepPlugin->registrarInclusionCSS('iconos.css');
		$smarty->igepPlugin->registrarInclusionCSS('sb-admin.css');
		$smarty->igepPlugin->registrarInclusionCSS('font-awesome.min.css');
		$smarty->igepPlugin->registrarInclusionCSS('../../../igep/css/morris.css');
		// CSS de cada aplicación
		$smarty->igepPlugin->registrarInclusionCSS('../../../css/appStyle.css');
		// CSS propio del fw
		$smarty->igepPlugin->registrarInclusionCSS('../../../igep/css/igep.css');
		// Ventana modal
		$smarty->igepPlugin->registrarInclusionJS('ventanaModal.js');
		// Combobox
		$smarty->igepPlugin->registrarInclusionJS('jquery-ui_1_11_4.js');
		$smarty->igepPlugin->registrarInclusionCSS('../../../igep/css/jquery-ui_1_11_4.css');
		$smarty->igepPlugin->registrarInclusionJS('listaAutocomplete.js');

		//Primero defino el nombre del componente.
		$n_comp="CWVentana";
		// Necesitamos saber cuántas instancias de este componente existen ya / para poner el codigo o no
		$num=$smarty->igepPlugin->registrarInstancia($n_comp);
	}
	else
	{

		$punteroPila = count($smarty->_tag_stack)-1;
		$modal = $idPanel = $smarty->_tag_stack[$punteroPila][1]['modal'];

		$ini_html ='';

		$miTitulo = 'gvHidra';
		if ($params['titulo'])
		{
			$miTitulo = $params['titulo'];
		}

		$ini_html ='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';

		$ini_html .= "\n<html>\n";
        $ini_html .= "<head>\n";
        $ini_html .= "<link rel='icon' type='image/ico' href='".IMG_PATH_CUSTOM."favicon.ico'/>";
		$ini_html .= "<meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1'>\n";
		$ini_html .= "<link href='http://fonts.googleapis.com/css?family=Roboto:400,500' rel='stylesheet' type='text/css'>\n";
        $ini_html .= "<title>".$miTitulo."</title>\n";


		$script_literal = <<<cabeceraMenu
<script type="text/javascript">
<!--//--><![CDATA[//><!--

// PHP Layers Menu 3.2.0-rc (C) 2001-2004 Marco Pratesi - http://www.marcopratesi.it/
DOM = (document.getElementById) ? 1 : 0;
NS4 = (document.layers) ? 1 : 0;
// We need to explicitly detect Konqueror
// because Konqueror 3 sets IE = 1 ... AAAAAAAAAARGHHH!!!
Konqueror = (navigator.userAgent.indexOf('Konqueror') > -1) ? 1 : 0;
// We need to detect Konqueror 2.2 as it does not handle the window.onresize event
Konqueror22 = (navigator.userAgent.indexOf('Konqueror 2.2') > -1 || navigator.userAgent.indexOf('Konqueror/2.2') > -1) ? 1 : 0;
Konqueror30 =
	(
		navigator.userAgent.indexOf('Konqueror 3.0') > -1
		|| navigator.userAgent.indexOf('Konqueror/3.0') > -1
		|| navigator.userAgent.indexOf('Konqueror 3;') > -1
		|| navigator.userAgent.indexOf('Konqueror/3;') > -1
		|| navigator.userAgent.indexOf('Konqueror 3)') > -1
		|| navigator.userAgent.indexOf('Konqueror/3)') > -1
	)
	? 1 : 0;
Konqueror31 = (navigator.userAgent.indexOf('Konqueror 3.1') > -1 || navigator.userAgent.indexOf('Konqueror/3.1') > -1) ? 1 : 0;
// We need to detect Konqueror 3.2 and 3.3 as they are affected by the see-through effect only for 2 form elements
Konqueror32 = (navigator.userAgent.indexOf('Konqueror 3.2') > -1 || navigator.userAgent.indexOf('Konqueror/3.2') > -1) ? 1 : 0;
Konqueror33 = (navigator.userAgent.indexOf('Konqueror 3.3') > -1 || navigator.userAgent.indexOf('Konqueror/3.3') > -1) ? 1 : 0;
Opera = (navigator.userAgent.indexOf('Opera') > -1) ? 1 : 0;
Opera5 = (navigator.userAgent.indexOf('Opera 5') > -1 || navigator.userAgent.indexOf('Opera/5') > -1) ? 1 : 0;
Opera6 = (navigator.userAgent.indexOf('Opera 6') > -1 || navigator.userAgent.indexOf('Opera/6') > -1) ? 1 : 0;
Opera56 = Opera5 || Opera6;
IE = (navigator.userAgent.indexOf('MSIE') > -1) ? 1 : 0;
IE = IE && !Opera;
IE5 = IE && DOM;
IE4 = (document.all) ? 1 : 0;
IE4 = IE4 && IE && !DOM;

if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)){ //test for MSIE x.x;
 var ieversion=new Number(RegExp.$1) // capture x.x portion and store as a number
 if (ieversion>=9)
  IE9 = true;
 else if (ieversion>=8)
  IE8 = true;
 else if (ieversion>=7)
  IE7 = true;
 else if (ieversion>=6)
  IE6 = true;
}
//--><!]]>
</script>
cabeceraMenu;

		$ini_html .= $script_literal;

		$ini_html .= 	$smarty->igepPlugin->getFicherosCSS();
		$ini_html .= 	$smarty->igepPlugin->getFicherosJS();

		$ini_html .="<script  type='text/javascript'>\n//<![CDATA[\n";
		$ini_html .="function inicializarVentanaJS()\n";
		$ini_html .= "{;\n";
		if ($params['onLoad'])
		{
			$ini_html .=$params['onLoad'].";\n";
		}
		$ini_html .="}\n"; // fin función inicializarVentanaJS

		$ini_html .="function finalizarVentanaJS()\n";
		$ini_html .= "{;\n";
		if ($params['onUnload'])
		{
			$ini_html .=$params['onUnload'].";\n";
		}
		$ini_html .="}\n"; // fin función finalizarVentanaJS
		$ini_html .="\n//]]\n</script>\n";


		if ($modal)
		{
			$ini_html .="<script  type='text/javascript'>\n//<![CDATA[\n";
			//$ini_html .="if (!window.reFocusModal){ window.reFocusModal = function reFocusModal(w) { window.blur(); w.focus();}}\n";
			$ini_html .="window.returnValue = {};\n";
			$ini_html .="\n//]]\n</script>\n";
		}


		$ini_html .= "</head>\n";
		$ini_html .="<body class='text' onLoad=\"javascript:inicializarVentanaJS();\" onUnload=\"javascript:finalizarVentanaJS();\">\n";

		$ini_html .="<div class='main-block '>\n";


		$ini_html .= "<iframe id='oculto' src='about:blank' name='oculto' style='display:none; width:0px; height:0px; border: 0px;'></iframe>";

		// Acerca de...
		$script = "var about = new oAviso(";
		$script .= "'".IMG_PATH_CUSTOM."'";
		$script .= ");";

		// Ventanas emergentes
		$script .= "var aviso= new oAviso(";
		$script .= "'".IMG_PATH_CUSTOM."'";
		$script .= ");";
		// Ventanas de confirmación
		$script .= "var confirm = new oAviso(";
		$script .= "'".IMG_PATH_CUSTOM."'";
		$script .= ");";
		// REVIEW: Vero y David - Repasar la ruta a las imágenes de los avisos para colocarlos en el directorio custom
		//$script .="oAviso.rutaImg = '".IMG_PATH_CUSTOM."avisos/'";
		$igepSmarty->addPreScript($script);
		//Registramos el objeto JS
		$smarty->igepPlugin->registerJSObj('about');
		//Registramos el objeto JS
		$smarty->igepPlugin->registerJSObj('aviso');
		// Registramos el objeto para las ventanas de confirmación
		$smarty->igepPlugin->registerJSObj('confirm');

		if ( ($params['tipoAviso']) && (trim($params['tipoAviso'])!="") )
		{
			// Hay ventana de aviso
			// tipoAviso | codError | descBreve | textoAviso
			$script = "aviso.set('aviso','capaAviso',";
			$script .= "'".$params['tipoAviso']."',";
			$script .= "'".$params['codAviso']."',";
			$script .= "desescapeIGEPjs('".$params['descBreve']."'),";
			$script .= "desescapeIGEPjs('".$params['textoAviso']."'));";
			$script .= "aviso.mostrarAviso();\n";
			$igepSmarty->addPreScript($script);
		}

		// Capa para "Acerca de..."
		$ini_html .= "<div id=\"capaAbout\" style=\"position:absolute;display:none;\"></div>\n";
		// Capa para mostrar los mensajes (error, alerta, aviso y sugerencia)
		$ini_html .= "<div id=\"capaAviso\" style=\"position:absolute;display:none;\"></div>\n";
		$fin_html .= $smarty->igepPlugin->addJSObjects2Document();
		$fin_html .= "</div>\n";
		$fin_html .= "<script type='text/javascript' src='igep/js/gvh_jquery.js'></script>";
		$fin_html .= "</body>\n";
		$fin_html .= "</html>\n";

		return  $ini_html.$igepSmarty->getPreScript().$content.$fin_html;

	}//FIN else isset
}//Fin funcion
?>