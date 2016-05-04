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
* Igep Pantalla es una clase que utilizamos para definir el comportamiento general de las pantallas.
* Definimos el comportamiento de las pestañas asi como la definición de los mensajes de aviso. En principio hemos hemos hecho una distincion 
* entre la pantalla que puede ser o bien de tipo ficha o bien de tipo tabla
* @package gvHIDRA
*/
class IgepDashboard {

/**
 * Constructor de la clase.
 */
	public function __construct() {

		global $s;
		
		$datosAplicacion = IgepSession::dameDatosAplicacion();
		$datosUsuario = IgepSession::dameDatosUsuario();
		//Obtenemos los datos de  configuración
		$conf = ConfigFramework::getConfig();
		
		$s->assign("smty_usuario",$datosUsuario["nombre"]);
		$s->assign("smty_aplicacion",$datosAplicacion["daplicacion"]);
		$s->assign("smty_tituloApl",$conf->getApplicationName());
		$s->assign("smty_version",$conf->getAppVersion());
		$s->assign("smty_gvHidraVersion",$conf->getgvHidraVersion());
		$s->assign("smty_ubicacion", $conf->isEnableBreadCrumb());
		
		
		//TODO: parametro no usado, comento
		$s->assign("smty_codaplic",strtoupper($conf->getApplicationName()));
		
		//Para que se pueda añadir JS en la ventana principal
		if(IgepSession::existeVariable('principal','obj_IgSmarty')){
			$obj_IgepSmarty = IgepSession::dameVariable('principal','obj_IgSmarty');
			$jsLoad = $obj_IgepSmarty->getScriptLoad(false);
			$s->assign('smty_jsOnLoad',$jsLoad);
			IgepSession::borraVariable('principal','obj_IgSmarty');
		}
		
		//Para que saque el mensaje de la pantalla de error.
		$mensaje = IgepSession::dameVariable('principal','obj_mensaje');
		if(isset($mensaje)) {
			$tipo =  $mensaje->getTipo();
			$s->assign("smty_tipoAviso", $tipo);
			$codError =  $mensaje->getCodigo();
			$s->assign("smty_codError", $codError);
			$descBreve = $mensaje->getDescripcionCorta();
			$s->assign("smty_descBreve", $descBreve);
			$textoAviso = $mensaje->getDescripcionLarga();
			$s->assign("smty_textoAviso", $textoAviso);
			IgepSession::borraVariable('principal','obj_mensaje');
		}
		//CAMBIO PARA MANTENER LIMPIA LA SESSION
		//Comprobamos si hemos visitado paneles
		if(IgepSession::existeVariable('global','panelesVisitados')) {
			//Borramos los paneles visitados
			IgepSession::_borrarPanelesVisitados();
			IgepSession::borraSalto();
		}
		//Limpiamos la variable de paneles Visitados
		IgepSession::guardaVariable('global','panelesVisitados',array());
	}
}//Fin de clase IgepDashboard
?>