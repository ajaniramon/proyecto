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
 *Incluimos la clase ComunSession para que tengamos acceso a los datos comunes de la Session.
 */
include "ComunSession.php";

/**
 * IgepSession es una clase que enmascara el acceso a la Session de Igep. Con ella conseguimos
 * que el acceso a la SESSION sea ordenado y no se produzcan conflictos entre los diferentes paneles
 * que almacenan valores en ella.
 * Es importante tener en cuenta cual es el uso que se debe dar de la SESSION por parte de los programadores:
 *<ul>
 *<li>El más habitual es consultar, dentro de una clase manejadora de un panel (las clases ubicadas en action), el
 * valor de cierto campo, atributo, tupla seleccionada,... Para ello se puede hacer uso de los métodos de consulta de
 * esta clase. Notese que en este caso NUNCA se deberá almacenar valores en la SESSION, ya que si se quiere
 * almacenar valores se deben de almacenar como variables de clase e Igep, internamente, ya se encargará de
 * almacenarlos en la SESSION.</li>
 * <li>Otro uso, menos habitual, pero que puede requerirse es el de crear una instancia de una clase manejadora de un panel
 * desde otra clase manejadora. En este caso los encargados de almacenar los valores en la SESSION serán los programadores.
 * Por esta razón se proporcionan métodos para almacenar valores.</li>
 *</ul>
 * @version	$Id: IgepSession.php,v 1.45 2010-03-10 16:49:56 afelixf Exp $
 * @author David: <pascual_dav@gva.es>
 * @author Keka: <bermejo_mjo@gva.es>
 * @author Vero: <navarro_ver@gva.es>
 * @author Raquel: <borjabad_raq@gva.es>
 * @author Toni: <felix_ant@gva.es>
 * @package gvHIDRA
 */
class IgepSession extends ComunSession
{

	//Ubicacion en la estructura de memoria del SALTO
	const GVHIDRA_JUMP='saltoIgep';

	private static function getApplicationName()
	{
		return ConfigFramework::getApplicationName();
	}
	/*Funciones con todo el Panel*/
	/**
	* Comprueba si existe en la SESSION un objeto Panel
	*
	* @access	public
	* @param	string	$clasePanel
	* @return	bool
	*/
	public static function existePanel($clasePanel)
	{
		return isset($_SESSION[self::getApplicationName()][$clasePanel]);
	}

	/**
	* Inicia la sesion de php, inicializando previamente todo lo necesario
	*
	* @access	public
	* @param	string	$app	codigo de la aplicación
	*/
	public static function session_start($app=null, $start=true)
	{
		if (empty($app))
			$app = ConfigFramework::getApplicationName();
		if (empty($app))
			throw new Exception('No está configurado el nombre corto de la aplicación');

		// fijar ruta para sesiones?
		$tmp = ConfigFramework::getTemporalDir();
		if (!is_null($tmp) && !empty($tmp))
			ini_set('session.save_path', $tmp);

		// nombre a sesion, para que el fichero no crezca tanto
		session_name('gvHIDRA_'.md5($app));

		// fijar ruta de la cookie?
		//ini_set('session.cookie_path', realpath('.'));

		// no permitir el uso de la cookie fuera del http. Evitamos que se acceda por JavaScript
		ini_set('session.cookie_httponly',1);

		// no permitir sessid en get
		ini_set('session.use_only_cookies', TRUE);

		// no probar si el browser acepta cookies
		ini_set('session.use_trans_sid', FALSE);

		// expirar la sesion en cliente
		ini_set('session.cookie_lifetime', 0);

		// expirar la sesion en servidor, aunque no tiene efecto si no se guardan en /tmp
		ini_set('session.gc_maxlifetime', 12000);

  		if ($start) {
			session_start();
			//Creamos el fingerprint con la IP del usuario
			if(empty($_SESSION['fingerprint']))
				$_SESSION['fingerprint'] = md5($_SERVER['REMOTE_ADDR'].'gvHIDRA'.$_SERVER['HTTP_USER_AGENT']);
  		}
	}

	/**
	* Valida el estado de la SESSION
	*
	* @access	public
	*/
	public function isValid()
	{
		if($_SESSION['fingerprint'] != md5($_SERVER['REMOTE_ADDR'].'gvHIDRA'.$_SERVER['HTTP_USER_AGENT']))
			return false;

		if(self::dameUsuario()=='')
			return false;
		return true;
	}

	/**
	* Borra de la SESSION la toda la informacion de la aplicación
	* Se llama en el inicio de la aplicación.
	*
	* @access	public
	*/
	public static function clear()
	{
		if(isset($_SESSION[self::getApplicationName()]))
			unset($_SESSION[self::getApplicationName()]);
	}

	/**
	* Borra de la SESSION la posición correspondiente a un Panel
	*
	* @access	public
	* @param	string	$clasePanel
	*/
	public static function borraPanel($clasePanel)
	{
		unset($_SESSION[self::getApplicationName()][$clasePanel]);
	}

	/**
	* Devuelve el contenido de un panel
	*
	* @access	public
	* @param	string	$clasePanel
	*/
	public static function damePanel($clasePanel)
	{
		return ($_SESSION[self::getApplicationName()][$clasePanel]);
	}

	/**
	* Almacena en la SESSION un objeto Panel. Comprueba que el objeto coincida con la clase adecuada.
	* Si todo va bien devuelve 1 indicando que la operación se ha realizado con exito. De lo contrario retorna -1.
	*
	* @access	public
	* @param	string	$clasePanel
	* @param	object	$objeto
	* @return	integer
	*/
	public static function guardaPanel($clasePanel,$objeto)
	{
		if($clasePanel=='')
			return -1;
		//Comprueba q el objeto corresponda a una instancia del panel excepto en el caso de ventanaSeleccion
		if(get_class($objeto) == $clasePanel){
			$_SESSION[self::getApplicationName()][$clasePanel] = $objeto;
			return 0;
		}
		else
			die("No se puede guardar en la SESSION el panel $clasePanel porque la clase proporcionada no es del tipo correcto");
	}

	/**
	* Para cuando Igep hace uso de la SESSION y no quiere tener restricciones.
	* @access private
	*/
	public static function _guardaPanelIgep($clasePanel,$objeto)
	{
		$_SESSION[self::getApplicationName()][$clasePanel] = $objeto;
		return 0;
	}

		/*Funciones con las variables heredadas de Negocio*/

	/**
	* Devuelve el índice de la tupla actualmente seleccionada en el panel. Este índice hace referencia a la posición dentro del array de resultados.
	* Si no está activo el indice es porque la posición actual es 0.
	*
	* @access	public
	* @param	string	$clasePanel
	* @return	integer
	*/
	public static function dameFilaActual($clasePanel)
	{
		if(isset($_SESSION[self::getApplicationName()][$clasePanel]->int_filaActual))
			return $_SESSION[self::getApplicationName()][$clasePanel]->int_filaActual;
		else
			return 0;
	}

	/**
	* Devuelve el array de resultados correspondiente a la última consulta. Es importante tener en cuenta
	* que este array procede de la consulta realizada con la Select introducida en la variable $str_select. Esta
	* consulta se lanza con el proceso buscar de Igep.
	*
	* @access	public
	* @param	string	$clasePanel
	* @return	array
	*/
	public static function dameUltimaConsulta($clasePanel)
	{
		$object = $_SESSION[self::getApplicationName()][$clasePanel];
		if(is_object($object))
			return $object->getResultForSearch();
		return null;
	}

	/**
	* Devuelve el array de resultados correspondiente a la última edición. Es importante tener en cuenta
	* que este array procede de la consulta realizada con la Select introducida en las variable $str_selectEditar. Esta
	* consulta se lanza con el proceso editar de Igep.
	*
	* @access	public
	* @param	string	$clasePanel
	* @return	array
	*/
	public static function dameUltimaEdicion($clasePanel)
	{
		$object = $_SESSION[self::getApplicationName()][$clasePanel];
		if(is_object($object))
			return $object->getResultForEdit();
		return null;
	}

	/**
	* Devuelve un array con la tupla seleccionada de la última consulta.
	*
	* @access	public
	* @param	string	$clasePanel
	* @return	array
	*/
	public static function dameTuplaSeleccionada($clasePanel)
	{
		$object = $_SESSION[self::getApplicationName()][$clasePanel];
		if(!is_object($object))
			return null;

		$m_datos = $object->getResultForSearch();
		return $m_datos[IgepSession::dameFilaActual($clasePanel)];
	}

	/**
	* Devuelve el valor de un campo de la tupla seleccionada de la última consulta.
	*
	* @access	public
	* @param	string	$clasePanel
	* @param	string	$campo
	* @return	string
	*/
	public static function dameCampoTuplaSeleccionada($clasePanel,$campo)
	{
		$object = $_SESSION[self::getApplicationName()][$clasePanel];
		if(!is_object($object))
			return null;

		$m_datos = $object->getResultForSearch();
		$valor = $m_datos[IgepSession::dameFilaActual($clasePanel)][$campo];
		//Si se trata de una lista en este caso devolvemos el valor del campo seleccionado
		if(is_array($valor))
			return $valor["seleccionado"];
		else
			return $valor;
	}

	/**
	* Almacena en la SESSION un mensaje. Este método enmascara la inserción de mensajes en la clase, ya que
	* se encarga de comprobar que el objeto que recibe es un objeto de la clase IgepMensaje y realiza la asignación
	* a la variable de gvHidraForm_DB destinada para el mensaje de los paneles, obj_mensaje. Si no cumple las condiciones
	* requeridas no se realiza la asignación.
	*
	* @access	public
	* @param	string	$clasePanel
	* @param	object	$mensaje
	*/
	public static function guardaMensaje($clasePanel,$mensaje)
	{
		$claseMensaje = get_class($mensaje);
		if($claseMensaje == "IgepMensaje")
			$_SESSION[self::getApplicationName()][$clasePanel]->obj_mensaje = $mensaje;
	}

  /**
  * Método que indice si existe el panel en la SESSION
  * @param  string  $clasePanel
  * @return integer
  */
	public static function existeMensaje($clasePanel)
	{
		return (!empty($_SESSION[self::getApplicationName()][$clasePanel]->obj_mensaje));
	}

  /**
  * Método que devuelve el mensaje de un panel almacenado en la SESSION
  * @param  string  $clasePanel
  * @return IgepMensaje
  */
	public static function dameMensaje($clasePanel)
	{
		return $_SESSION[self::getApplicationName()][$clasePanel]->obj_mensaje;
	}

  /**
  * Método que borra un panel de la SESSION
  * @param  string  $clasePanel
  * @return none
  */
	public static function borraMensaje($clasePanel)
	{
		unset($_SESSION[self::getApplicationName()][$clasePanel]->obj_mensaje);
	}

	/*Funciones con variables particulares*/

	/**
	* Comprueba si existe en la SESSION alguna variable particular dentro de la definición de un Panel.
	*
	* @access	public
	* @param	string	$clasePanel
	* @param	string	$variable
	* @return	bool
	*/
	public static function existeVariable($clasePanel,$variable)
	{
		return isset($_SESSION[self::getApplicationName()][$clasePanel]->$variable);
	}

	/**
	* Devuelve el valor de una variable de un Panel contenido en la SESSION.
	*
	* @access	public
	* @param	string	$clasePanel
	* @param	string	$variable
	* @return	any
	*/
	public static function dameVariable($clasePanel,$variable)
	{
		if(isset($_SESSION[self::getApplicationName()][$clasePanel]->$variable))
			return $_SESSION[self::getApplicationName()][$clasePanel]->$variable;
		return null;
	}

	/**
	* Borra la referencia a una variable de un Panel en la SESSION.
	*
	* @access	public
	* @param	string	$clasePanel
	* @param	string	$variable
	*/
	public static function borraVariable($clasePanel,$variable)
	{
		unset($_SESSION[self::getApplicationName()][$clasePanel]->$variable);
	}

	/**
	* Almacena en la SESSION el valor de una variable de un Panel
	*
	* @access	public
	* @param	string	$clasePanel
	* @param	string	$nombreVar
	* @param	mixed	$valor
	*/
	public static function guardaVariable($clasePanel, $nombreVar, $valor)
	{
		@$_SESSION[self::getApplicationName()][$clasePanel]->$nombreVar = $valor;
	}

	/**
	* Devuelve el valor de una variable ubicada en la zona global de la SESSION.
	*
	* @access	public
	* @param	string	$nomVariable
	* @return	any
	*/
	public static function dameVariableGlobal($nomVariable)
	{
		$value = @$_SESSION[self::getApplicationName()]['gvhGlobalZone'][$nomVariable];
		if(isset($value))
			return $value;
		return null;
	}

	/**
	* Borra una variable ubicada en la zona global de la SESSION.
	*
	* @access	public
	* @param	string	$nomVariable
	* @return	any
	*/
	public static function borraVariableGlobal($nomVariable)
	{
		unset($_SESSION[self::getApplicationName()]['gvhGlobalZone'][$nomVariable]);
	}

	/**
	* Almacena en la zona de la SESSION global el valor de una variable
	*
	* @access	public
	* @param	string	$clasePanel
	* @param	string	$nombreVar
	* @param	mixed	$valor
	*/
	public static function guardaVariableGlobal($nombreVar, $valor)
	{
		$_SESSION[self::getApplicationName()]['gvhGlobalZone'][$nombreVar] = $valor;
	}


	/**
	* Añade el módulo $nomModulo dinámicamente,
	* el segundo parámetro, $valor es opcional,
	* si se utiliza, se añadira el $valor, al registro
	* de valores del modulo
	* @access	public
	* @param		string	nomModulo
	* @param		array	$valor
	*/
	public static function anyadeModuloValor($nomModulo, $valor=null,  $descripcion=null)
	{
		if ( !(IgepSession::hayModuloDinamico($nomModulo)) )
		{
			$_SESSION[ComunSession::dameAplicacion()]['modulosDIN'][$nomModulo]['valor']= $valor;
			$_SESSION[ComunSession::dameAplicacion()]['modulosDIN'][$nomModulo]['descrip']= $descripcion;
		}
	}

	/**
	* Quita el módulo $nomModulo dinámicamente,
	* el segundo parámetro, $valor es opcional,
	* si aparece, se elimina el el módulo siempre
	* que coincida el valor que tenía asignado
	* con el valor previmanete registrado
	* @access	public
	* @param		string	nomModulo
	* @param		array	$valor
	*/
	public static function quitaModuloValor($nomModulo, $valor=null)
	{
		if ($valor==null)
		{
			if ( (IgepSession::hayModuloDinamico($nomModulo)) )
				unset($_SESSION[ComunSession::dameAplicacion()]['modulosDIN'][$nomModulo]);
		}
		else
		{
			if
			(
				(IgepSession::hayModuloDinamico($nomModulo))
				&& ($_SESSION[ComunSession::dameAplicacion()]['modulosDIN'][$nomModulo]['valor'] == $valor)
			)
				unset($_SESSION[ComunSession::dameAplicacion()]['modulosDIN'][$nomModulo]);
		}
	}

	/**
	* Sobrecarga la funcion de la clase padre, devuelve true siempre que existan módulos, sean o no dinamicos
	* cierto módulo para la aplicación actual.
	* @access	public
	* @param		string	nomModulo
	* @return		bool
	*/
	public static function hayModulo($nomModulo)
	{
		$app = ComunSession::dameAplicacion();
		return (
			(is_array($_SESSION[$app]['modulosDIN']) and array_key_exists($nomModulo,$_SESSION[$app]['modulosDIN']))
			||
			(is_array($_SESSION[$app]['modulos'])    and array_key_exists($nomModulo,$_SESSION[$app]['modulos']))
			);
	}

	/**
	* Comprueba si el usuario al que pertenece la SESSION tiene concedido
	* cierto módulo para la aplicación actual.
	* @access	public
	* @param		string	nomModulo
	* @return		bool
	*/
	public static function hayModuloDinamico($nomModulo)
	{
		if (isset ($_SESSION[ComunSession::dameAplicacion()]['modulosDIN']))
			return (array_key_exists($nomModulo,$_SESSION[ComunSession::dameAplicacion()]['modulosDIN']));
		else
			return false;
	}

	/**
	* Devuelve el array con todos los modulos concedidos para un usuario y para la aplicación actual.
	* @access	public
	* @return		array
	*/
	public function dameModulosDinamicos()
	{
		return $_SESSION[ComunSession::dameAplicacion()]['modulosDIN'];
	}

	/**
	* Sobrecarga el método de la clase padre, devolviendo un array formado por
	* los móodulos dinámicos y los modulos concedidos para un usuario y
	* para la aplicación actual, información que preoviene de comun.
	* @access	public
	* @return		array
	*/
	public static function dameModulos()
	{
		$modulosDin = is_array($_SESSION[ComunSession::dameAplicacion()]['modulosDIN'])?$_SESSION[ComunSession::dameAplicacion()]['modulosDIN']:array();
		$modulos = is_array($_SESSION[ComunSession::dameAplicacion()]['modulos'])?$_SESSION[ComunSession::dameAplicacion()]['modulos']:array();
		$v_modulos = array_merge ($modulosDin, $modulos);
		return($v_modulos);
	}

	/**
	* Devuelve el array con los valores de un módulo Dinámico
	* concreto para la aplicación actual. Si no existe retorna -1
	* @access	public
	* @param		string	nomModulo
	* @return		array
	*/
	public static function dameModuloDinamico($nomModulo)
	{
		if (IgepSession::hayModuloDinamico($nomModulo))
			return $_SESSION[ComunSession::dameAplicacion()]['modulosDIN'][$nomModulo];
		else
			return -1;
	}

	/**
	* Devuelve el objeto salto activo
	* @access	public
	* @return	IgepSalto o null
	*/
	public static function dameSalto()
	{
		if(isset($_SESSION[ComunSession::dameAplicacion()][IgepSession::GVHIDRA_JUMP]))
			return $_SESSION[ComunSession::dameAplicacion()][IgepSession::GVHIDRA_JUMP];
		return null;
	}

	/**
	* Almacena el salto como activo. El parametro de entrada debe ser un IgepSalto
	* @access	public
	* @param	jump	IgepSalto
	* @return	none
	*/
	public static function guardaSalto($jump)
	{
		if(get_class($jump) == 'IgepSalto')
			$_SESSION[ComunSession::dameAplicacion()][IgepSession::GVHIDRA_JUMP] = $jump;
	}

	/**
	* Elimina el salto activo de SESSION
	* @access	public
	* @param	jump	IgepSalto
	* @return	none
	*/
	public static function borraSalto()
	{
		unset($_SESSION[ComunSession::dameAplicacion()][IgepSession::GVHIDRA_JUMP]);
	}


	/**
	* Este método agrega un panel a la lista de paneles visitados
	*
	* @param nombrePanel  nombre que identifica al panel (claseManejadora)
	* @return none
	*/
	public static function _marcarPanelVisitado($nombrePanel)
	{
		$panelesVisitados = IgepSession::dameVariable('global','panelesVisitados');
		if(!isset($panelesVisitados))
			$panelesVisitados=array();
		if(!in_array($nombrePanel,$panelesVisitados))
			array_push($panelesVisitados,$nombrePanel);
		IgepSession::guardaVariable('global','panelesVisitados',$panelesVisitados);
	}

	public static function _borrarPanelesVisitados()
	{
		//Borramos el contenido de los paneles anteriores
		foreach (IgepSession::dameVariable('global','panelesVisitados') as $panelVisitado){
			IgepSession::borraPanel($panelVisitado);
		}
		IgepSession::guardaVariable('global','panelesVisitados',array());
	}
}
?>