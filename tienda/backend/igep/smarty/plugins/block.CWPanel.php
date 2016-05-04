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

function smarty_block_CWPanel($params, $content, &$smarty) {
	
	$indicePila = count($smarty->_tag_stack)-1;
	if(!isset($content)) // Si se abre la etiqueta {CWMarcoPanel}...
	{	
		$n_comp = "CWPanel";	
		// Necesitamos saber cuántas instancias de este componente existen ya / para poner el codigo o no
		$num=$smarty->igepPlugin->registrarInstancia($n_comp);
		
		// Asignamos a la pila dl panel un valor x defecto para "tipoComprobacion" 
		// antes d asignar el content para q esté definido para los hijos
		
		if (!isset($params['tipoComprobacion'])) // Por defecto le pasamos la comprobación al botón (envio)
		{
			$smarty->_tag_stack[$indicePila][1]['tipoComprobacion']="envio";
		}
		
		$smarty->_tag_stack[$indicePila][1]['upLoad'] = 0;
	} 
	else 
	{		
		///////////////////////////////////////////////////////////
		// Miramos si en el panel existe un componente CWUpLoad ///
		///////////////////////////////////////////////////////////
		
		$igepSmarty = new IgepSmarty();	
		if ($params['id'])
		{
			$nombre = $params['id'];
			$idPanel = $params['id'];
		}
		else 
		{ 
			$nombre = 'IDPANEL'; 
			$idPanel = 'IDPANEL';
		}
		
// parámetro q nos indicará la clase con la q el negocio trabajará		
		$claseManejadora = '';
		if ($params['claseManejadora']) 
		{
			$claseManejadora = $params['claseManejadora'];
			$campoClaseManejadora = "<input type='hidden' id='claseManejadora' name='claseManejadora' value=".$params['claseManejadora']." />";	
		}
		
// Llamada por defecto al phrame.php		
		$action = "phrame.php?action=";
		if ($params['action'])
		{
			if ($idPanel == 'vSeleccion') {
				$action = $action.$params['action'];
			}
			else {			
				$action = $action.$claseManejadora."__".$params['action'];
			}
		}
		else 
		{ 
// Por defecto, si no hay parámetro action, la acción q se realizará es cancelarTodo			
			$action = $action.$claseManejadora."__cancelarTodo"; 
		}
		
		if ($params['method']) 
		{
			$metodo = $params['method'];
		}
		else {
			$metodo = ''; 
		}
		
		if ($params['estado'] == 'on') 
		{
			$estado = "display:block;";
		}
		else 
		{ 
			$estado = "display:none;"; 
		}		
		

		if ($params['id'] == 'vSeleccion') 
			$smarty->igepPlugin->registrarInclusionJS('ventanaSeleccion.js');

/////////////////////////////////////////////////////////////////
/// COMPROBACIÓN DE CAMPOS ////
		if (($params['tipoComprobacion'] == "todo") || ($params['tipoComprobacion'] == "envio"))
		{
			$smarty->igepPlugin->registrarInclusionJS('objComprobacion.js');
			$script = $idPanel."_comp = new oComprobacion('".$idPanel."','".$idPanel."_comp');";
			$igepSmarty->addPreScript($script);
			
			//Registramos el objeto JS
			$smarty->igepPlugin->registerJSObj($idPanel."_comp");
		}
		
/////////////////////////////////////////////////////////////////
		
		$smarty->igepPlugin->registrarInclusionJS('objPanel.js');
		$script = $idPanel."_panel = new oPanel('".$idPanel."','".$idPanel."_panel');";
		$igepSmarty->addPreScript($script);

		$ini_tabla = "<!-- INI: CWPanel -->\n";
		switch ($idPanel)
		{
			case 'fil':
				$class = 'panelFil';
			break;
			case 'lis':
			case 'edi':
				$class = 'panelPrimary';
			break;
			case 'lisDetalle':
			case 'ediDetalle':
				$class = 'panelDetail';
			break;
		}
		$ini_tabla .= "<div id='P_".$idPanel."' class='$class' style=\"".$estado."\">\n";
		$ini_tabla .= "<div class='block-panel bg-info'>";
		$upLoad = '';
		if ($smarty->_tag_stack[$indicePila][1]['upLoad'] == 1)
			$upLoad = "enctype=\"multipart/form-data\" ";
		$ini_tabla .= "<form id='F_".$idPanel."' name='F_".$idPanel."' action=\"".$action."\" method=\"".$metodo."\" ".$upLoad.">\n";	

		
		$ini_tabla .= "<input type='hidden' name='accionActivaP_F_".$idPanel."' id='accionActivaP_F_".$idPanel."' />\n";				
		$valor = 'nada';				
		if ($params['accion'] == 'insertar')
				$valor = 'insertada';
		$ini_tabla .= "<input type='hidden' name='accionActiva' id='accionActiva' value='".$valor."' />";
		$ini_tabla .= $campoClaseManejadora;

		$ini_tabla .= "<div>\n";
			
		
		$fin_tabla .= "</div>\n";
		$fin_tabla .= "</form>\n";
		$fin_tabla .= "</div>\n";
		$fin_tabla .= "</div>\n";
		$fin_tabla .= "<!-- FIN: CWPanel -->\n";
		
		return $igepSmarty->getPreScript().$ini_tabla.$content.$fin_tabla."\n";		
	}
}
?>