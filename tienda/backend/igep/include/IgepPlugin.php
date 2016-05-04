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
/**
 * Created on 21-mar-2005
 * Clase de apoyo a Smarty, sustituye parte de la funcionaidad de la clase
 * Componentes_web
 * Una instancia de esta clse se crea dentro de la clase Smarty_Phrame
 * para que los plugins puedan invocarla.
 *
 * @version	$Id: IgepPlugin.php,v 1.16 2008-06-09 15:23:13 afelixf Exp $
 * @author David: <pascual_dav@gva.es> 
 * @author Keka: <bermejo_mjo@gva.es>
 * @author Vero: <navarro_ver@gva.es>
 * @author Raquel: <borjabad_raq@gva.es> 
 * @author Toni: <felix_ant@gva.es>
 * @package	gvHIDRA
 **/


class IgepPlugin
{
	var $v_instancia;
	var $v_ficheroJS;
	
	/**
	 * Vector de objetos JS que se registrarán en el documento
	 * para poder acceder a ellos desde el iframe oculto 
	 */
	var $v_objJSDocument;

function IgepPlugin()
{
	define("APP_PATH",''); // dirname($_SERVER['REQUEST_URI'])."/"
	define("IMG_PATH",APP_PATH."igep/images/");
	define("JS_PATH",APP_PATH."igep/js/");
	//REVIEW: Toni y David. quitar el new.
	$configuration = ConfigFramework::getConfig();
	$customDirname = $configuration->getCustomDirName();
	define("PATH_CUSTOM",APP_PATH.'custom/'.$customDirname."/");
	define("IMG_PATH_CUSTOM",APP_PATH.'custom/'.$customDirname.'/images/');
	define("CSS_PATH",APP_PATH.'custom/'.$customDirname.'/css/');

	define("MODXML_PATH",APP_PATH."include/");

	$this->v_instancia = array();
	$this->v_ficheroJS = array();
	$this->v_ficheroCSS = array();
	$this->v_objJSDocument = array();
} //FIN constructor


function registrarInstancia($tipoComponente)
{
	if (isset($this->v_instancia[$tipoComponente]['numeroInstancia']))
		$this->v_instancia[$tipoComponente]['numeroInstancia']++;
	else 
		$this->v_instancia[$tipoComponente]['numeroInstancia'] = 1;
	return($this->v_instancia[$tipoComponente]['numeroInstancia']);
} //FIN registrarInstancia


function getNumeroInstancia($tipoComponente)
{	
	if (isset($this->v_instancia[$tipoComponente]['numeroInstancia']))
		$resultado = $this->v_instancia[$tipoComponente]['numeroInstancia'];
	else
		$resultado = 0;
	return($resultado);
} //FIN registrarInstancia


function registrarInclusionJS($nombreFichero,$ruta='')
{
	$fichero = JS_PATH.$nombreFichero;
	if ($ruta != '')
	{
		$fichero = $ruta.$nombreFichero;
	}
	if (isset($this->v_ficheroJS[$fichero]))
		$this->v_ficheroJS[$fichero]++;
	else 
		$this->v_ficheroJS[$fichero] = 1;
	return($this->v_ficheroJS[$fichero]);
} //FIN registrarInclusionJS


function incluidoJS($nombreFichero)
{
	return(array_key_exists($nombreFichero, $this->v_ficheroJS));
} //FIN incluidoJS


function getFicherosJS()
{
	$str_aux = "\n<!-- Ficheros JS -->\n";
	$v_ficheros = array_keys($this->v_ficheroJS);
	$numElem = count($v_ficheros);
	for ($i =0; $i<$numElem; $i++)
	{			
		$str_aux .= "<script type='text/javascript' src='".$v_ficheros[$i]."'></script>\n";
	}
	$str_aux .= "\n<!-- Fin de Ficheros JS -->\n";
	return($str_aux);
}

function registrarInclusionCSS($nombreFichero, $ruta='')
{
	$fichero = CSS_PATH.$nombreFichero;
	if ($ruta != '')
	{
		$fichero = $ruta.$nombreFichero;
	}
	if (isset($this->v_ficheroCSS[$fichero]))
		$this->v_ficheroCSS[$fichero]++;
	else 
		$this->v_ficheroCSS[$fichero] = 1;
		
	return($this->v_ficheroCSS[$fichero]);
} //FIN registrarInclusionJS


function getFicherosCSS()
{
	$str_aux = "\n<!-- Fichero(s) CSS -->\n";		
	$v_ficheros = array_keys($this->v_ficheroCSS);
	$numElem = count($v_ficheros);	
	for ($i =0; $i<$numElem; $i++)
	{	
		$str_aux .= "<link rel='stylesheet' href='".$v_ficheros[$i]."' type='text/css' />\n";		
	}
	$str_aux .= "\n<!-- Fin de Fichero(s) CSS -->\n";
	return($str_aux);
}


function registerJSObj($nombreObjeto)
{
	if (isset($this->v_objJSDocument[$nombreObjeto]))
		$this->v_objJSDocument[$nombreObjeto]++;
	else 
		$this->v_objJSDocument[$nombreObjeto] = 1;
	return($this->v_objJSDocument[$nombreObjeto]);
} //FIN addObjJS2Document


function addJSObjects2Document()
{
	$cadenaJs ='';
	$cadenaJs ="<!-- Registro de los objetos JS en el arbol DOM para invocación desde iframe -->\n";
	$cadenaJs.="<script type='text/javascript'>\n";
	$cadenaJs.="<!--//--><![CDATA[//><!--\n";
	foreach($this->v_objJSDocument as $nomObjeto=>$numRegistros)
	{		
		$this->v_objJSDocument;		
		$cadenaJs .= "document.".$nomObjeto."=eval('".$nomObjeto."');\n";
	}
	$cadenaJs.="\n//--><!]]>\n</script>\n";
	$cadenaJs.="<!-- Fin Registro objetos JS -->\n";
	return($cadenaJs);
} //Fin addJSObjects2Document



}//Fin Class IgepPlugin
?>
