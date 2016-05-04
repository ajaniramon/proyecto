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
	// Si los mensajes tienen par�metros, se indican mediante %0%, %1%, ...
	// Los par�metros se pasan en un array a IgepMensaje o a SetMensaje.
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
		'IGEP-6'=>array('descCorta'=>'Error de Conexi�n con la Base de Datos','descLarga'=>'No se ha podido establecer la conexi�n con la Base de Datos. Consulte con el Administrador de la aplicaci�n. %0%','tipo'=>'ERROR'),
		'IGEP-7'=>array('descCorta'=>'Error de Desconexi�n con la Base de Datos','descLarga'=>'No se ha podido realizar la desconexi�n con la Base de Datos. Consulte con el Administrador de la aplicaci�n.','tipo'=>'ERROR'),
		'IGEP-8'=>array('descCorta'=>'Error al iniciar la Transacci�n','descLarga'=>'No se ha podido realizar la operaci�n de inicio de la transacci�n. %0%','tipo'=>'ERROR'),
		'IGEP-9'=>array('descCorta'=>'Error al acabar la Transacci�n','descLarga'=>'No se ha podido realizar la operaci�n COMMIT o ROLLBACK que marca el fin de la transacci�n. %0%','tipo'=>'ERROR'),
		'IGEP-10'=>array('descCorta'=>'No se han encontrado datos','descLarga'=>'La consulta no ha devuelto datos que cumplan con los criterios de b�squeda introducidos.','tipo'=>'AVISO'),
		'IGEP-11'=>array('descCorta'=>'Error en la realizaci�n de la operaci�n','descLarga'=>'Ha habido un problema al realizar una operaci�n en la Base de Datos. %0%','tipo'=>'ERROR'),
		'IGEP-12'=>array('descCorta'=>'Error al calcular el n�mero de Secuencia','descLarga'=>'Ha habido un problema al calcular el n�mero de Secuencia de la Inserci�n. La operaci�n no se ha realizado. %0%','tipo'=>'ERROR'),
		'IGEP-13'=>array('descCorta'=>'Error de consulta en el detalle','descLarga'=>'Ha habido un problema al recargar alguno de los detalles. El error que se produce es: %0%','tipo'=>'ERROR'),
		'IGEP-14'=>array('descCorta'=>'No se mostrar�n todos los registros','descLarga'=>'Su �ltima consulta ha devuelto demasiados registros. S�lo se mostrar�n los %0% primeros. Si quiere consultar alg�n registro que no se ha presentado restrinja la b�squeda.','tipo'=>'AVISO'),
		'IGEP-15'=>array('descCorta'=>'Error al lanzar la consulta','descLarga'=>'La consulta lanzada ha causado error. %0%','tipo'=>'ERROR'),
		'IGEP-16'=>array('descCorta'=>'No tiene ningun registro seleccionado','descLarga'=>'Debe seleccionar al menos un registro antes de pulsar el bot&oacute;n de edici�n. Para seleccionar un registro pulse sobre el check que aparece al principio de cada uno de ellos.','tipo'=>'SUGERENCIA'),
		'IGEP-17'=>array('descCorta'=>'Errores en la validaci�n de los datos.','descLarga'=>'La operaci�n no se ha realizado ya que se han producido los siguientes errores en la validaci�n de los datos: %0%','tipo'=>'ERROR'),								
		'IGEP-18'=>array('descCorta'=>'Error de validaci�n.','descLarga'=>'El campo no cumple con las restricciones impuestas','tipo'=>'AVISO'),
		'IGEP-19'=>array('descCorta'=>'Error en la creaci�n de la Ventana Selecci�n.','descLarga'=>'Se ha intentado crear la ventana seleccion sobre el campo %0% y no se ha encontrado la definici�n en la clase %1%','tipo'=>'ERROR'),
		'IGEP-20'=>array('descCorta'=>'Borrado realizado correctamente.','descLarga'=>'El borrado se ha realizado correctamente. El panel ha quedado vacio por tanto tiene que realizar una nueva b�squeda.','tipo'=>'AVISO'),
        'IGEP-21'=>array('descCorta'=>'Error al crear/recuperar panel detalle.','No se ha podido crear/recuperar el panel detalle %0%. Compruebe que la creaci�n del mismo es correcta.','tipo'=>'ERROR'),
		'IGEP-22'=>array('descCorta'=>'Posibles problemas de compatibilidad con el navegador actual','descLarga'=>'La aplicaci�n actual esta dise�ada para navegadores Mozilla/Firefox. El uso con su navegador actual puede acarrear errores en su funcionamiento.','tipo'=>'SUGERENCIA'),
		'IGEP-23'=>array('descCorta'=>'Desconexi�n por inactividad','descLarga'=>'Por pol�tica de seguridad, la sesi�n se ha cerrado por inactividad. Tiene que salir y volver a entrar en la aplicaci�n.','tipo'=>'ERROR'),
		'IGEP-CODMSG'=>array('descCorta'=>'Error en identificador de mensaje','descLarga'=>'Ha habido un problema en la codificaci�n de errores. Consulte con el Administrador de la aplicaci�n.','tipo'=>'ERROR')
		);
								
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 				DEFINIMOS LOS C�DIGOS DE LOS MENSAJES GENERALES DE JAVASCRIPT
	//	Los comentarios siguientes S�LO ilustran el rango reservado para los mnesajes de error est�ticos
	//	generados directamente por los plugins y que hacen llamadas "est�ticas" a los mensajes javascript
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
	//	�ltimo n�mero usado: 908  (Actulizar por el programador seg�n se incluyan)
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