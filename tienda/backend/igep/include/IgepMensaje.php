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
 *
 * @package	gvHIDRA
 */

class IgepMensaje {

	 /**
     * codigo de error 
     *
     * @var string str_codigo
     */	
	var $str_codigo;

	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 				DEFINIMOS LOS MENSAJES GENERALES
	// Tipos: AVISO, ERROR, SUGERENCIA, ALERTA
	// Si los mensajes tienen parámetros, se indican mediante %0%, %1%, ...
	// Los parámetros se pasan en un array a IgepMensaje o a SetMensaje.
	////////////////////////////////////////////////////////////////////////////////////////////////////////////		
	/**
     * mensajes de IGEP 
     *
     * @var string mensajes
     */ 
	var $mensajes = array(								
		'IGEP-1'=>array('descCorta'=>'Error al Insertar','descLarga'=>'Ha habido problemas al Insertar el registro en la Base de Datos. %0%','tipo'=>'ERROR'),
		'IGEP-2'=>array('descCorta'=>'Error al Borrar','descLarga'=>'Ha habido problemas al Borrar el registro en la Base de Datos. %0%','tipo'=>'ERROR'),
		'IGEP-3'=>array('descCorta'=>'Error al Actualizar','descLarga'=>'Ha habido problemas al Actualizar el registro en la Base de Datos. %0%','tipo'=>'ERROR'),
		'IGEP-4'=>array('descCorta'=>'Error de Concurrencia','descLarga'=>'Ha habido problemas al realizar la operaci&oacute;n. Otro usuario ha actualizado el registro.','tipo'=>'ERROR'),
		'IGEP-5'=>array('descCorta'=>'Error de Consulta','descLarga'=>'Ha habido problemas al realizar la consulta a la Base de Datos. %0%','tipo'=>'ERROR'),
		'IGEP-6'=>array('descCorta'=>'Error de Conexión con la Base de Datos','descLarga'=>'No se ha podido establecer la conexión con la Base de Datos. Consulte con el Administrador de la aplicación. %0%','tipo'=>'ERROR'),
		'IGEP-7'=>array('descCorta'=>'Error de Desconexión con la Base de Datos','descLarga'=>'No se ha podido realizar la desconexión con la Base de Datos. Consulte con el Administrador de la aplicación.','tipo'=>'ERROR'),
		'IGEP-8'=>array('descCorta'=>'Error al iniciar la Transacción','descLarga'=>'No se ha podido realizar la operación de inicio de la transacción. %0%','tipo'=>'ERROR'),
		'IGEP-9'=>array('descCorta'=>'Error al acabar la Transacción','descLarga'=>'No se ha podido realizar la operación COMMIT o ROLLBACK que marca el fin de la transacción. %0%','tipo'=>'ERROR'),
		'IGEP-10'=>array('descCorta'=>'No se han encontrado datos','descLarga'=>'La consulta no ha devuelto datos que cumplan con los criterios de búsqueda introducidos.','tipo'=>'AVISO'),
		'IGEP-11'=>array('descCorta'=>'Error en la realización de la operación','descLarga'=>'Ha habido un problema al realizar una operación en la Base de Datos. %0%','tipo'=>'ERROR'),
		'IGEP-12'=>array('descCorta'=>'Error al calcular el número de Secuencia','descLarga'=>'Ha habido un problema al calcular el número de Secuencia de la Inserción. La operación no se ha realizado. %0%','tipo'=>'ERROR'),
		'IGEP-13'=>array('descCorta'=>'Error de consulta en el detalle','descLarga'=>'Ha habido un problema al recargar alguno de los detalles. El error que se produce es: %0%','tipo'=>'ERROR'),
		'IGEP-14'=>array('descCorta'=>'No se mostrarán todos los registros','descLarga'=>'Su última consulta ha devuelto demasiados registros. Sólo se mostrarán los %0% primeros. Si quiere consultar algún registro que no se ha presentado restrinja la búsqueda.','tipo'=>'AVISO'),
		'IGEP-15'=>array('descCorta'=>'Error al lanzar la consulta','descLarga'=>'La consulta lanzada ha causado error. %0%','tipo'=>'ERROR'),
		'IGEP-16'=>array('descCorta'=>'No tiene ningun registro seleccionado','descLarga'=>'Debe seleccionar al menos un registro antes de pulsar el bot&oacute;n de edición. Para seleccionar un registro pulse sobre el check que aparece al principio de cada uno de ellos.','tipo'=>'SUGERENCIA'),
		'IGEP-17'=>array('descCorta'=>'Errores en la validación de los datos.','descLarga'=>'La operación no se ha realizado ya que se han producido los siguientes errores en la validación de los datos: %0%','tipo'=>'ERROR'),								
		'IGEP-18'=>array('descCorta'=>'Error de validación.','descLarga'=>'El campo no cumple con las restricciones impuestas','tipo'=>'AVISO'),
		'IGEP-19'=>array('descCorta'=>'Error en la creación de la Ventana Selección.','descLarga'=>'Se ha intentado crear la ventana seleccion sobre el campo %0% y no se ha encontrado la definición en la clase %1%','tipo'=>'ERROR'),
		'IGEP-20'=>array('descCorta'=>'Borrado realizado correctamente.','descLarga'=>'El borrado se ha realizado correctamente. El panel ha quedado vacio por tanto tiene que realizar una nueva búsqueda.','tipo'=>'AVISO'),
        'IGEP-21'=>array('descCorta'=>'Error al crear/recuperar panel detalle.','No se ha podido crear/recuperar el panel detalle %0%. Compruebe que la creación del mismo es correcta.','tipo'=>'ERROR'),
		'IGEP-22'=>array('descCorta'=>'Posibles problemas de compatibilidad con el navegador actual','descLarga'=>'La aplicación actual esta diseñada para navegadores Mozilla/Firefox. El uso con su navegador actual puede acarrear errores en su funcionamiento.','tipo'=>'SUGERENCIA'),
		'IGEP-23'=>array('descCorta'=>'Desconexión por inactividad','descLarga'=>'Por política de seguridad, la sesión se ha cerrado por inactividad. Tiene que salir y volver a entrar en la aplicación.','tipo'=>'ERROR'),
		'IGEP-CODMSG'=>array('descCorta'=>'Error en identificador de mensaje','descLarga'=>'Ha habido un problema en la codificación de errores. Consulte con el Administrador de la aplicación.','tipo'=>'ERROR')
		);
								
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 				DEFINIMOS LOS CÓDIGOS DE LOS MENSAJES GENERALES DE JAVASCRIPT
	//	Los comentarios siguientes SÓLO ilustran el rango reservado para los mnesajes de error estáticos
	//	generados directamente por los plugins y que hacen llamadas "estáticas" a los mensajes javascript
	// 	Lista: 
	// 	-------------------------------------------------
	// 	-------------------------------------------------
	//  comprobarObligatorios		-> IGEP-901 Ficheros: paginacion.js, function.CWBoton.php
	//  LONGITUDMAXIMA				-> IGEP-902 Ficheros: objComprobacion.js
	//  LONGITUDMINIMA				-> IGEP-903 Ficheros: objComprobacion.js
	//  FECHAINICIO					-> IGEP-904 Ficheros: objComprobacion.js
	//  FECHAFIN					-> IGEP-905 Ficheros: objComprobacion.js
	//  RANGOFECHAS					-> IGEP-906 Ficheros: objComprobacion.js
	//  ESVACIO						-> IGEP-907 Ficheros: objComprobacion.js
	//  MASCARA						-> IGEP-908 Ficheros: objComprobacion.js
	//  DEFAULT (Error Indefinido)	-> IGEP-900 Ficheros: objComprobacion.js
	// 	-------------------------------------------------
	//	Último número usado: 908  (Actulizar por el programador según se incluyan)
	//////////////////////////////////////////////////////////////////////////////////////////////////////////	

	public function __construct($codigo=-1, $vp_args=array()) {
		//Inicializamos las variables actuales
		$this->str_tipo="";
		if ($codigo != -1) $this->setMensaje($codigo,$vp_args);
	}//fin de IgepMensaje

	public function IgepMensaje($codigo=-1, $vp_args=array()) {
		//Inicializamos las variables actuales
		$this->str_tipo="";
		if ($codigo != -1) $this->setMensaje($codigo,$vp_args);
	}//fin de IgepMensaje

	
	public function setMensaje($codigo, $vp_args = array()) {
		$this->str_tipo = $codigo;
		if (!$this->getTipo()) {
			include("mensajes.php");
			global $g_mensajesParticulares;
			if ($g_mensajesParticulares[$this->str_tipo]) {
				$this->mensajes[$this->str_tipo] = $g_mensajesParticulares[$this->str_tipo];
			}
		}
		if (/*!$this->getDescripcionCorta() ||
		    !$this->getDescripcionLarga() ||*/
		    !$this->getTipo())
			$this->str_tipo = 'IGEP-CODMSG';
		else {
			$msg = $this->mensajes[$this->str_tipo]["descLarga"];
			$contador = count($vp_args);
			if ($contador > 0) {		 
				for ($i=0; $i < $contador; $i++)
					$msg = str_replace("%$i%",$vp_args[$i],$msg);
				$this->mensajes[$this->str_tipo]["descLarga"] = $msg;
			}
		}			
	} //fin de setMensaje

	public function getCodigo() {
		return $this->str_tipo; 
	}//fin de getCodigo

	public function getDescripcionCorta() {
		if ($this->str_tipo=="")
			return  "";
		else
			return IgepSmarty::escapeIGEP($this->mensajes[$this->str_tipo]["descCorta"]);
	}//fin de getDescripcionCorta
								
	public function getDescripcionLarga() {
		if ($this->str_tipo=="")
			return  "";
		else
			return IgepSmarty::escapeIGEP($this->mensajes[$this->str_tipo]["descLarga"]);		
	}//fin de getDescripcionLarga

	public function getTipo() {
		if(isset($this->mensajes[$this->str_tipo]))
			return $this->mensajes[$this->str_tipo]["tipo"];
		return "";
	}//fin de getTipo

}	 //Fin de la Clase IgepMensaje
?>