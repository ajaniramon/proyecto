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
* ComunSession es una clase que maneja la informaci�n contenida en la 
* Session de COMUN. Concretamente se va a encargar de manejar la
* informaci�n de la aplicaci�n, del usuario y de la conexi�n actual.
* No tiene ninguna propiedad puesto que va a ser una clase que se va a utilizar
* sin instanciarse nunca (sus m�todos son standalone).
*
* @author David: <pascual_dav@gva.es> 
* @author Keka: <bermejo_mjo@gva.es>
* @author Vero: <navarro_ver@gva.es>
* @author Raquel: <borjabad_raq@gva.es> 
* @author Toni: <felix_ant@gva.es>
* @version	$Id: ComunSession.php,v 1.18 2009-11-09 09:05:03 gaspar Exp $
*/

class ComunSession {	
		
	/**
	* Devuelve el nombre de la aplicaci�n actual. Este m�todo es el �nico m�todo que realiza distinci�n entre
	* aplicaciones Igep y no Igep, y adem�s impone una restricci�n de uso de esta clase. Esto se debe a que 
	* este  m�todo ser� llamado por la mayor parte de los m�todos de esta clase para acceder a los datos relativos
	* a la conexi�n a una aplicaci�n, la aplicaci�n actual.
	* Las aplicaciones Igep tienen definida el nombre de la aplicaci�n actual (en el fichero de configuraci�n).
	* @access	public	 
	* @return	string	 	 
	*/							
    static function dameAplicacion(){
	   if(class_exists('ConfigFramework')){
			$codApp = ConfigFramework::getApplicationName();
	   }
	   if($codApp!='')
	       return $codApp;
	   else
	       die('Error: No se ha detectado el c�digo de la aplicaci�n.');
	}
			
	/**
	* Devuelve el login del usuario
	* @access	public	 
	* @return	string	 	 
	*/								
	static function  dameUsuario(){
		return @$_SESSION[self::dameAplicacion()]['usuario']['usuario'];
	}
			
	/**
	* Devuelve el nombre de la Base de Datos.
	* @access	public	 
	* @return	string	 	 
	*/						
	static function  dameBaseDatos(){
		return $_SESSION['validacion']['bd'];
	}	

	/**
	* Devuelve el nombre del servidor web
	* @access	public	 
	* @return	string	 	 
	*/						
	static function  dameServidor(){
		return $_SESSION['validacion']['server'];
	}	

	/**
	* Devuelve los datos del usuario. Necesita de la aplicaci�n actual.
	* @access	public	 
	* @return	array	 	 
	*/						
	static function  dameDatosUsuario(){				
		return $_SESSION[self::dameAplicacion()]['usuario'];
	}
	
	/**
	* Devuelve un array con los datos de la aplicaci�n actual. En este conjunto de datos est�n:
	* <ul>
	*<il>daplicacion: descripci�n de la aplicacion.</il>
	*<il>bd: �?.</il>
	*<il>version: versi�n de la aplicaci�n que se est� ejecutando.</il>
	*<il>ultentra: ultima entrada realizada por este usuario en la aplicaci�n.</il>
	* </ul>
	* @access	public	 
	* @return	string	 	 
	*/						
	static function dameDatosAplicacion(){	
		$nombreAplica = self::dameAplicacion();		
		$datosAplicacion['daplicacion'] =  $_SESSION[$nombreAplica]['daplicacion'];
		$datosAplicacion['bd'] = $_SESSION['validacion']['bd'];
		$datosAplicacion['version'] = @$_SESSION[$nombreAplica]['version'];
		$datosAplicacion['ultentra'] = @$_SESSION[$nombreAplica]['ultentra'];
		return $datosAplicacion;
	}
	
	/**
	* Devuelve el rol del usuario para la aplicaci�n actual
	* @access	public	 
	* @return	string	 	 
	*/					
	static function dameRol(){
		return $_SESSION[self::dameAplicacion()]['rolusuar'];
	}
	
	/**
	* Devuelve el Parametro1 del usuario para la aplicaci�n actual
	* @access	public	 
	* @return	string	 	 
	*/					
	static function dameParam1(){
		return $_SESSION[self::dameAplicacion()]['param1'];
	}

	/**
	* Devuelve el Parametro2 del usuario para la aplicaci�n actual
	* @access	public	 
	* @return	string	 	 
	*/					
	static function dameParam2(){
		return $_SESSION[self::dameAplicacion()]['param2'];
	}
	
	/**
	* Devuelve el Parametro3 del usuario para la aplicaci�n actual.
	* @access	public	 
	* @return	string	 	 
	*/					
	static function dameParam3(){
		return $_SESSION[self::dameAplicacion()]['param3'];
	}
		
	/**
	* Devuelve el array con todos los modulos concedidos para un usuario y para la aplicaci�n actual.
	* @access	public	 
	* @return	array	 	 
	*/				
	static function dameModulos(){
		return $_SESSION[self::dameAplicacion()]['modulos'];
	}
	
	/**
	* Devuelve el array con los valores de un m�dulo en concreto para la aplicaci�n actual. Si no existe retorna -1
	* @access	public
	* @param	string	nomModulo
	* @return	array	 	 
	*/
	static function dameModulo($nomModulo){
		if (self::hayModulo($nomModulo))
			return $_SESSION[self::dameAplicacion()]['modulos'][$nomModulo];
		else
			return -1;
	}
		
	/**
	* Comprueba si el usuario al que pertenece la SESSION tiene concedido cierto m�dulo para la aplicaci�n actual.
	* @access	public
	* @param	string	nomModulo
	* @return	bool	 	 
	*/			
	static function hayModulo($nomModulo){
		$mods = @$_SESSION[self::dameAplicacion()]['modulos'];
		if (empty($mods)) return false;
		return (array_key_exists($nomModulo,$mods));		
	}
			
	/**
	* Borra todos los datos de una aplicaci�n actual. Se utilizar� generalmente antes de cerrar la ventana del navegador.
	* @access	public
	* @param	string	nomAplicacion	 	 
	*/		
	static function borraAplicacion($nomAplicacion){			
		unset($_SESSION[$nomAplicacion]);	
	}

	/**
	* Comprueba si cierta aplicaci�n esta activa. Se utilizar� generalmente antes de cerrar la aplicaci�n.
	* @access	public
	* @param	string	nomAplicacion	 	 
	*/		
	static function existeAplicacion($nomAplicacion){			
		return isset($_SESSION[$nomAplicacion]);	
	}

	
}//Fin de ComunSession
?>