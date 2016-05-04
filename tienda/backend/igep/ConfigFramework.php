<?php
/** gvHIDRA. Herramienta Integral de Desarrollo Rápido de Aplicaciones de la Generalitat Valenciana
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
 * Clase destinada a manejar los parámetros de configuración de gvHidra.
 *
 * Estructurada como un patrón SINGLETON con persistencia funcional en SESION,
 * la clase servirá de base para la configuración de la organización (CUSTOM)
 *
 * @version	$Id: ConfigFramework.php 6047 2015-06-29 09:36:26Z felix_ant $
 * @author Verónica Navarro Porter: <navarro_ver@externos.gva.es>
 * @author Toni Felix: <felix_ant@externos.gva.es>
 * @author David Pascual: <pascual_dav@externos.gva.es>
 * @package gvHIDRA
 */

class ConfigFramework
{

	/**
	 * Especifica el subdirectorio custom
	 * @access private
	 * @var string
	 */
	private $customDirName;


	/**
	 * Especifica la ruta al custom
	 * @access private
	 * @var string
	 */
	private $extCustomPath;

	/**
	 * Especifica el nombre de la aplicacion
	 * @access private
	 * @var string
	 */
	private $applicationName;

	/**
	 * Especifica la versión de la aplicacion
	 * @access private
	 * @var string
	 */
	private $appVersion;


	/**
	 * Texto personalizable de la barra superior de gvHidra
	 * @access private
	 * @var string
	 */
	private $customTitle;


	/**
	 * Texto personalizable de la barra superior de gvHidra
	 * @access private
	 * @var string
	 */
	private $barTitle;
	/**
	 * Patron singletón. Referencia a si mismo como instancia
	 * @access private
	 * @var string
	 */
	private static $_instance;

	/**
	 * Propiedad que indica el estado del log.
	 * @access private
	 * @var string
	 */
	private $logStatus;


	/**
	 * Propiedad que indica si estáo no activo el breadcrumb o ubicación
	 * @access private
	 * @var boolean
	 */
	private $enableBreadCrumb;



	/**
	 * Propiedad que indica el dsn usado para el log.
	 * @access private
	 * @var string
	 */
	private $logDSN;

	/**
	 * Propiedad que determina el tipo de consulta que vamos a realizar. Los tipos posibles son:
	 *	    - 0. Se contruye igualando los campos a los valores.
	 *      - 1. Se construye con like y comodines para cada campo.
	 *      - 2. Por defecto, se contruye con like sólo si el usuario ha especificado comodines.
	 *      - 3. Se contruye con like, case unsensitive y sin considerar las marcas diacríticas (no distingue acentos, ç,..).
	 * @access private
	 * @var string
	 */
	private $queryMode;

	/**
	 * Propiedad que determina el directorio de compilacion de las plantillas de smarty.
	 * @access private
	 * @var string
	 */
	private $templatesCompilationDir;

	/**
	 * Propiedad que determina el directorio temporal para gestion interna del FW (sesiones...)
	 * @access private
	 * @var string
	 */
	private $temporalDir;

	/**
	 * Propiedad que almacena si se recarga el fichero de mappings en cada petición
	 * @access private
	 * @var boolean
	 */
	private $reloadMappings;

	/**
	 * Propiedad que almacena si smarty tiene que comprobar si se ha modificado alguna plantilla y en caso afirmativo recargarla
	 * @access private
	 * @var boolean
	 */
	private $smartyCompileCheck;

	/**
	 * Propiedad que almacena un array asociativo (indexado por IDs) de DSNs
	 * @access private
	 * @var string
	 */
	private $vDSN;

	/**
	 * Propiedad que almacena la cadena del mensaje informativo de inicio
	 * @access private
	 * @var string
	 */
	private $startMsg;

	/**
	 * Propiedad que almacena si el mensaje de inicio es informativo o bloqueante
	 * @access private
	 * @var string
	 */
	private $startMsgType;

	/**
	 * Propiedad que contiene la definición de las listas desplegables del Custom y de la aplicación
	 * @access private
	 * @var array
	 */
	private $defList;

	/**
	 * Propiedad que contiene la definición de las ventanas de seleccion del Custom y de la aplicación
	 * @access private
	 * @var array
	 */
	private $defVS;

	/**
	 * Propiedad que contiene la conexion del log. Esta conexion se intentará que sea persistente (depende del SGBD, solo postgres)
	 * @access private
	 * @var object
	 */
	private $logConnection;

	/**
	 * Guarda errores del transformer
	 * @access private
	 * @var array
	 */
	private static $transformErrors = array();

	/**
	 * Indica el directorio de ubicacion de configuracion externa
	 * @access private
	 * @var array
	 */
	private $extConfigDir;

	/**
	 * Indica el fichero de configuracion externa (si existe)
	 * @access private
	 * @var array
	 */
	private $extConfigFile;


	/**
	 * Servidor de smtp
	 * @access private
	 * @var array
	 */
	private $smtpServer;

	/**
	 * Vector de properties
	 * @var array
	 */
	private $vProperties;


	private function __construct()
	{
		$this->logStatus = LOG_NONE;
		$this->enableBreadCrumb = false;
		$this->queryMode = 2;
		$this->defList = array();
		$this->defVS = array();
		$this->vDSN = array();
		$this->templatesCompilationDir = 'templates_c/';
		$this->temporalDir = null;
		$this->reloadMappings = null;
		$this->smartyCompileCheck = null;
		$this->logDSN = 'gvh_dsn_log';
		$this->startMsg = '';
		$this->startMsgType = 'info';
	}

	public function __destruct()
	{
		//Almacenamos la configuración en la SESSION siempre que exista la aplicación (Cuando se cierra la aplicación no la guardamos)
		if(IgepSession::existeAplicacion($this->applicationName)) {
	
			if(is_object($this->logConnection) AND $this->logConnection->getDriver()=='pdo')
				$this->logConnection = null;
			try {
				$_SESSION[$this->applicationName]['configuration'] = serialize(self::$_instance);
			}catch(Exception $e) {}
		}
	}

	public static function getConfig()
	{
		//Cargamos el fichero de configuración del Framework
		try
		{
			//Comprobar que existe el fichero y cargarlo
			if (empty(self::$_instance))//Review: David y Toni: Clase Pear:Config para cargar fichero
			{
				$g_aplicacion = self::getApplicationName();
				if(!isset($_SESSION[$g_aplicacion]['configuration']))
				{
					$instance = new ConfigFramework();

					//Leemos el fichero de configuración del Framework
					$fichero = './igep/gvHidraConfig.inc.xml';
					$instance->_loadConfigFile($fichero);

					//Leemos el fichero de configuración del CUSTOM
					$customDirName =$instance->getCustomDirName();
					$fichero = './custom/'.$customDirName.'/gvHidraConfig.inc.xml';
					$instance->_loadConfigFile($fichero);

					//Leemos el fichero de configuración de la APLICACIÓN
					$fichero = './gvHidraConfig.inc.xml';
					$instance->_loadConfigFile($fichero);

					//Leemos el include externo si existe
					$extConfigFile =$instance->getExtConfigFile();
					if(!empty($extConfigFile))
					{
						$fichero = $extConfigFile;
						if(file_exists($fichero))
						{
							$instance->_loadConfigFile($fichero);
						}
						else
						{
							throw new Exception('ConfigFramework::getConfig() EXCEPCION: no se puede acceder al fichero de configuración externa: '.$fichero);
						}
					}

					self::$_instance = $instance;
					$_SESSION[$g_aplicacion]['configuration'] = serialize($instance);
				}
				else
				{
					self::$_instance = unserialize($_SESSION[$g_aplicacion]['configuration']);
				}
			}
			return self::$_instance;
		}
		catch (Exception $e)
		{
			throw new Exception('ConfigFramework::getConfig() EXCEPCION: errores en la lectura de los ficheros: '.$e->getMessage());
		}
	}//Fin getConfig (constructor singleton)


    /**
	 * getCustomDirName:: Devuelve el nombre del subdirectorio de CUSTOM actual
	 *
	 * Devuelve el directorio que contiene la información de custom
	 *
	 * @access public
	*/
	public static function getCustomDirName()
	{
		if(empty(self::$_instance))
		{
			// buscar el custom en aplicacion o en FW
			$ficheros = array('./gvHidraConfig.inc.xml');
			$externalConfig = self::getExtConfigFile();
			if(!empty($externalConfig))
			{
				$ficheros = array($externalConfig);
			}

			foreach ($ficheros as $fichero)
			{
				if (file_exists($fichero))
				{
					$sXML = simplexml_load_file($fichero);
					if(!empty($sXML->customDirName))
					{
						$nombreCustom = (string) $sXML->customDirName;
					}
					else
					{
						$nombreCustom = 'default';
					}
					return($nombreCustom);
	    		}
	    		else
	    		{
					throw new Exception(__CLASS__.'::'.__METHOD__.': no existe el fichero '.$fichero);
	    		}
			}
			throw new Exception(__CLASS__.'::'.__METHOD__.': no se ha definido el elemento CUSTOM a utilizar ');
		}
		return self::$_instance->customDirName;
	}

	/**
	 * getCustomPath:: Devuelve la ruta donde se ubica el CUSTOM actual
	 *
	 * Devuelve el directorio que contiene la información de custom
	 *
	 * @access public
	 */
	public static function getCustomPath()
	{
		if(empty(self::$_instance))
		{
			$ficheros = array('./gvHidraConfig.inc.xml');
			$externalConfig = self::getExtConfigFile();
			if(!empty($externalConfig))
			{
				$ficheros = array($externalConfig);
			}
			foreach ($ficheros as $fichero)
			{
				if (file_exists($fichero))
				{
					$sXML = simplexml_load_file($fichero);
					if(!empty($sXML->customDirName))
					{
						$nombreCustom = (string) $sXML->customDirName;
						$vAtributos = $sXML->customDirName->attributes();
						if (!empty($vAtributos['path']))
						{
							$extCustomPath = realpath ($vAtributos->path.DIRECTORY_SEPARATOR.$nombreCustom);
						}
						else
						{
							$extCustomPath = realpath ('igep/custom'.DIRECTORY_SEPARATOR.$nombreCustom);
						}
					}
					unset($sXML);//Liberamos el recurso
					return $extCustomPath;
				}
				else
				{
					throw new Exception(__CLASS__.'::'.__FUNCTION__.': no existe el directorio '.$fichero);
				}
			}
			throw new Exception(__CLASS__.'::'.__FUNCTION__.': No existe el directorio CUSTOM '.$extCustomPath);
		}
		return self::$_instance->extCustomPath;

	}//Fin getCustomPath



	/**
	 * getApplicationName:: Devuelve el nombre de la aplicación actual
	 *
	 * @return string Nombre de la aplicación
	 * @access public
	*/
	public static function getApplicationName()
	{
		if(empty(self::$_instance))
		{
			$ficheros = array('./gvHidraConfig.inc.xml');
			$externalConfig = self::getExtConfigFile();
			if(!empty($externalConfig))
			{
				$ficheros = array($externalConfig);
			}
			foreach ($ficheros as $fichero)
			{
				if (file_exists($fichero))
				{
					$sXML = simplexml_load_file($fichero);
					if(!empty($sXML->applicationName))
					{
						$aux = (string) $sXML->applicationName;
						$nombreApp = utf8_decode($aux);
					}
				}
				else
				{
					throw new Exception('getApplicationName: no existe el fichero '.$fichero);
				}
			}
			if (!empty($nombreApp))
			{
				return($nombreApp);
			}
			throw new Exception('getApplicationName: no está definido el codigo de la aplicación');
		}
		return self::$_instance->applicationName;
	}


    /**
	 * setApplicationName: Establece el nombre de aplicación
	 *
	 * Establece el nombre de aplicación actual
	 * @param string $appName Cadena que indica el nombre de la aplicacion
	 *
	*/
	private function setApplicationName($appName)
	{
		$this->applicationName = $appName;
	}


	/**
	 * getAppVersion:: Devuelve la versión de la aplicación
	 *
	 * Devuelve el número de versión de la aplicación. En desarrollo devolverá HEAD.
	 * @return string Texto con la versión de la aplicación
	 * @access public
	*/
	public static function getAppVersion()
	{
		if(empty(self::$_instance))
		{
			$ficheros = array('./gvHidraConfig.inc.xml');
			$externalConfig = self::getExtConfigFile();
			if(!empty($externalConfig))
			{
				$ficheros[] = $externalConfig;
			}
			$appVersion ='';
			foreach ($ficheros as $fichero)
			{
				if (file_exists($fichero))
				{
						$aux = self::getStringFromXML($fichero,'appVersion');
						if(!empty($aux))
							$appVersion = $aux;
				}
			}
			if (!empty($appVersion))
			{
				return($appVersion);
			}
			else
			{
				throw new Exception('getAppVersion: Debe definir la versión en al menos uno de los ficheros de configuración');
			}
		}
		return self::$_instance->appVersion;
	}

	/**
	 * getgvHidraVersion:: Devuelve la versión de gvHidra
	 *
	 * Devuelve el número de versión del framework gvHidra.
	 * @return string Texto con la versión de la aplicación
	*/
	public function getgvHidraVersion()
	{
		return($this->gvHidraVersion);
	}

	/**
	 * getExtConfigDir:: Devuelve la ubicación del fichero de configuración externa.
	 *
	 * Para poder ubicar el fichero de configuración. Puede utilizarse para ubicarlo fuera del htdocs por seguridad.
	 * @return string Texto con la ruta
	*/
	public static function getExtConfigDir()
	{
		if(empty(self::$_instance))
		{
			$fichero = './gvHidraConfig.inc.xml';
			if (file_exists($fichero))
			{
				$extConfigDir = self::getStringFromXML($fichero,'extConfigDir');
				if (!empty($extConfigDir))
					return($extConfigDir);
			}
			else
			{
				throw new Exception('getExtConfigDir: no existe el fichero '.$fichero);
			}
			return null;
		}
		return (self::$_instance->extConfigDir);
	}

	/**
	 * getExtConfigFile:: Devuelve la ruta canónica al fichero de configuración externo o null si no se usa fichero externo
	 *
	 * Para realizar ajuste de configuración se requiere poder ubicar el ficher de configuración
	 *
	 * Utilizada para mostrar mensajes en la pantalla de validación
	 * @return string Texto informativo
	 */
	public static function getExtConfigFile()
	{
		if(empty(self::$_instance))
		{
			$extConfigFile = './gvHidraConfig.inc.xml';
			$sXML = simplexml_load_file($extConfigFile);
			if (file_exists($extConfigFile))
			{
				$extConfigDir = (string) $sXML->extConfigDir;
				if(empty($extConfigDir))
					return null;
				$v_atributos = $sXML->extConfigDir->attributes();
				if (!empty($v_atributos['fileName']))
				{
					$extConfigFile = realpath ($extConfigDir).DIRECTORY_SEPARATOR.$v_atributos->fileName;
				}

				if (!empty($extConfigFile))
				{
					return($extConfigFile);
				}
				else
				{
					throw new Exception(__CLASS__.'::'.__FUNCTION__.': no existe la ruta '.$extConfigFile);
				}
			}
			throw new Exception(__CLASS__.'::'.__FUNCTION__.': no está definido la ruta en la aplicación');
		}
		// Hemos quitado el $
		return self::$_instance->$extConfigFile;
	}//Fin getExtConfigFile

    /**
	 * setApplicationName: Fija la versión que se esta ejecutando.
	 *
	 * Establece la versión de la aplicación que se esta ejecutando.
	 * @param string $appVersion Cadena que indica la versión de la aplicación
	 *
	*/
	public function setAppVersion($appVersion)
	{
		$this->appVersion = $appVersion;
	}

	/**
	 * getStartMsg:: Devuelve cadena de texto informativa de arranque o cadena vacía
	 *
	 * Utilizada para mostrar mensajes en la pantalla de validación
	 * @return string Texto informativo
	 */
	public static function getStartMsg()
	{
		if(empty(self::$_instance))
		{
			$ficheros = array('./gvHidraConfig.inc.xml');
			$externalConfig = self::getExtConfigFile();
			if(!empty($externalConfig))
			{
				$ficheros = array($externalConfig);
			}
			foreach ($ficheros as $fichero)
			{
				if (file_exists($fichero))
				{
					$sXML = simplexml_load_file($fichero);
					$startMsg = $sXML->startMsg;
					$cadenaMsg = (string) $startMsg;
					unset($sXML);//Liberamos el recurso

					if(!empty($cadenaMsg))
						return(utf8_decode($cadenaMsg));
					else return ('');
				}
				else
				{
					throw new Exception(__CLASS__.'::'.__FUNCTION__.': no existe el fichero '.$fichero);
				}
			}
			throw new Exception(__CLASS__.'::'.__FUNCTION__.': no está definido el título de la aplicación');
		}
		return self::$_instance->startMsg;
	}


	/**
	 * getStartMsgType:: Devuelve el tipo de mensaje (informativo o bloqueante)
	 *
	 * Utilizada para mostrar mensajes en la pantalla de validación
	 * @return mixed tipo del mensaje 'info'|'lock' si existe o null si no existe
	 */
	public static function getStartMsgType()
	{
		if(empty(self::$_instance))
		{
			$ficheros = array('./gvHidraConfig.inc.xml');
			$externalConfig = self::getExtConfigFile();
			if(!empty($externalConfig))
			{
				$ficheros = array($externalConfig);
			}
			foreach ($ficheros as $fichero)
			{
				if (file_exists($fichero))
				{
					$sXML = simplexml_load_file($fichero);
					$startMsg = $sXML->startMsg;
					if (count($startMsg)==0) return null;//No hay Mensaje
					$vAtributos = $startMsg->attributes();
					$startMsgType = (string) $vAtributos['type'];
					unset($sXML);//Liberamos el recurso

					if(!empty($startMsgType))
						return(utf8_decode(trim(strtolower($startMsgType))));
					else return ('info');
				}
				else
				{
					throw new Exception(__CLASS__.'::'.__FUNCTION__.': no existe el fichero '.$fichero);
				}
			}
			throw new Exception(__CLASS__.'::'.__FUNCTION__.': no está definido el título de la aplicación');
		}
		return self::$_instance->startMsgType;
	}



	/**
	 * setStartMsg: Fija el texto informativo del mensaje
	 *
	 * @access public
	 * @param string $msg Cadena con el texto informativo
	 * @param string $type Cadena con el tipo de mensaje
	 *
	 */
	public function setStartMsg($msg, $type='info')
	{
		$this->startMsg = $msg;
		$this->startMsgType = 'info';
		if (trim(strtolower($type)) == 'lock')
		{
			$this->startMsgType = 'lock';
		}
	}



	/**
	 * getCustomTitle:: Devuelve la cadena de descripción de la aplicación fijada en el XML
	 *
	 * Si se desea, puede fijarse la descricpión de la aplicación vía XML en el fichero y
	 * cargarse en sesión. Los métodos de validación podrán sobreescribir este valor con
	 * información obtenida tras la validación
	 * Este método permite acceder a dicho valor
	 * @return string Texto de personalización de la barra superior
	 * @access public
	*/
	public static function getCustomTitle()
	{
		if(empty(self::$_instance))
		{
			$ficheros = array('./gvHidraConfig.inc.xml');
			$externalConfig = self::getExtConfigFile();
			if(!empty($externalConfig))
			{
				$ficheros = array($externalConfig);
			}

			foreach ($ficheros as $fichero)
			{
				if (file_exists($fichero))
				{
					$aux = self::getStringFromXML($fichero,'customTitle');

					if(!empty($aux))
					{
						return(utf8_decode($aux));
					}

					else
					{
						return ('');
					}
				}
				else
				{
					throw new Exception('getCustomTitle: no existe el fichero '.$fichero);
				}
			}
			throw new Exception('getCustomTitle: no está definido el título de la aplicación');
		}
		return self::$_instance->customTitle;
	}


    /**
	 * setCustomTitle: Sobrescribe la cadena de descripción de la aplicación leída en el XML
	 *
	 * @access public
	 * @param string $customTitle Cadena con el breve texto de personalización
	 *
	*/
	public function setCustomTitle($customTitle)
	{
		$this->customTitle = $customTitle;
	}

	/**
	 * getBarTitle:: Devuelve la cadena de personalización de la barra superior
	 *
	 * En la barra superior se ha designado un pequeño espacio para	un texto personalizado.
	 * Este método permite acceder a dicho valor
	 * @return string Texto de personalización de la barra superior
	 * @access public
	 */
	public static function getBarTitle()
	{
		if(empty(self::$_instance))
		{
			$ficheros = array('./gvHidraConfig.inc.xml');
			$externalConfig = self::getExtConfigFile();
			if(!empty($externalConfig))
			{
				$ficheros = array($externalConfig);
			}

			foreach ($ficheros as $fichero)
			{
				if (file_exists($fichero))
				{
					$aux = self::getStringFromXML($fichero,'barTitle');

					if(!empty($aux))
					{
						return(utf8_decode($aux));
					}

					else
					{
						return ('');
					}
				}
				else
				{
					throw new Exception('getBarTitle: no existe el fichero '.$fichero);
				}
			}
			throw new Exception('getBarTitle: no está definido el texto');
		}
		return self::$_instance->barTitle;
	}


	/**
	 * setbarTitle: Fija el texto personalizado de la barra de título
	 *
	 * @access public
	 * @param string $barTitle Cadena con el breve texto de personalización
	 *
	 */
	public function setbarTitle($barTitle)
	{
		$this->barTitle = $barTitle;
	}//Fin setbarTitle

	/**
	 * getLogStatus:: Devuelve el estado del log.
	 *
	 * Devuelve el estado del log en la aplicación. Los valores posibles son LOG_NONE, LOG_ERRORS, LOG_AUDIT y LOG_ALL.
	 * @access public
	*/
	public function getLogStatus()
	{
		return($this->logStatus);
	}


    /**
	 * setLogStatus: Fija el estado del log.
	 *
	 * Establece el estado del log. Los valores posibles son LOG_NONE, LOG_ERRORS, LOG_AUDIT y LOG_ALL.
	 * @access public
	 * @param string $status contiene el nuevo estado del log.
	 *
	*/
	public function setLogStatus($status)
	{
		if ($status>=LOG_NONE && $status<=LOG_ALL)
			$this->logStatus=$status;
		else
			throw new Exception('setLogStatus: Error al fijar logStatus.');
	}


	/**
	 * isEnableBreadCrumb: Devuelve si está o no activo el breadCrumb
	 *
	 * @return boolean
	 */
	public function isEnableBreadCrumb()
	{
		return($this->enableBreadCrumb);
	}



	/**
	 * getQueryMode:: Devuelve el valor del modo de consulta (ver propiedad para var posibles valores).
	 *
	 * Devuelve el valor del modo de consulta para saber de que modo se van a construir las Querys.
	 * @access public
	*/
	public function getQueryMode()
	{
		return($this->queryMode);
	}

    /**
	 * setQueryMode: Fija el valor del modo de consulta (ver propiedad para var posibles valores)
	 *
	 * Fija el valor del modo de consulta para saber de que modo se van a construir las Querys.
	 * @access public
	 * @param string $mode contiene el nuevo modo de consulta.
	 *
	*/
	public function setQueryMode($mode)
	{
		if ($mode>=0 && $mode<=2)
			$this->queryMode=$mode;
		else
			throw new Exception('setQueryMode: Error al fijar queryMode.');
	}


	/**
	 * getTemplatesCompilationDir:: Devuelve el directorio de templates_c
	 *
	 * Devuelve el directorio de compilacion de las plantillas de smarty.
	 * @access public
	*/
	public function getTemplatesCompilationDir()
	{
		return($this->templatesCompilationDir);
	}


    /**
	 * setTemplatesCompilationDir: Fija el directorio de templates_c
	 *
	 * Establece el directorio de compilacion de las plantillas de smarty.
	 * @access public
	 * @param string $dir directorio de compilacion de plantillas
	 *
	*/
	public function setTemplatesCompilationDir($dir)
	{
		$this->templatesCompilationDir = $dir;
	}

	/**
	 * getTemporalDir: Devuelve la ruta del directorio temporal del FW
	 *
	 * Devuelve la ruta del directorio temporal del FW.
	 * Si no existe la instancia lo coge del xml de aplicacion. No hay metodo setter.
	 * @access public
	*/
	public static function getTemporalDir()
	{
		if(empty(self::$_instance))
		{
			$ficheros = array('./gvHidraConfig.inc.xml');
			$externalConfig = self::getExtConfigFile();
			if(!empty($externalConfig))
			{
				$ficheros = array($externalConfig);
			}
			foreach ($ficheros as $fichero)
			{
				if (file_exists($fichero))
				{
					$tmp = self::getStringFromXML($fichero,'temporalDir');
					return($tmp);
	    		}
	    		else
	    		{
					throw new gvHidraException("getTemporalDir:: no existe el fichero $fichero");
	    		}
			}
		}
		return self::$_instance->temporalDir;
	}

	/**
	 * getLogConnection: Devuelve la conexion que se utiliza en el log.
	 *
	 * Devuelve el objeto conexion que se utiliza en el debug/log del FW. Esta conexion será persistente dependiendo
	 * del SGBD (solo Postgres lo permite).
	 * @access public
	*/
	public function getLogConnection()
	{
		return $this->logConnection;
	}

	/**
	 * setLogConnection: Fija la conexion que se utiliza en el log.
	 *
	 * Fija el objeto conexion que utilizara el log para trabajar. Esta conexion será persistente dependiendo
	 * del SGBD (solo Postgres lo permite).
	 *
	 * Uso interno del FW. NO DEBE SER USUADO POR EL PROGRAMADOR
	 *
	 * @access public
	*/
	public function setLogConnection($connection)
	{
		$this->logConnection = $connection;
	}

	/**
	 * getReloadMappings: Devuelve el valor del estado de reloadMappings
	 *
	 * @access public
	*/
	public function getReloadMappings()
	{
		return($this->reloadMappings);
	}


    /**
	 * setReloadMappings: Fija el estado de reloadMappings
	 *
	 * @access public
	 * @param string $mode contiene el nuevo estado
	 *
	*/
	public function setReloadMappings($mode)
	{
		if (is_bool($mode))
			$this->reloadMappings=$mode;
		else
			throw new Exception('setReloadMappings: Error al fijar reloadMappings.');
	}

	/**
	 * getSmartyCompileCheck: Devuelve el valor de smartyCompileCheck
	 *
	 * @access public
	*/
	public function getSmartyCompileCheck()
	{
		return($this->smartyCompileCheck);
	}



	/**
	 * getProperties: Devuelve un array asociativo de "properties"
	 *
	 * Devuelve un array asociativo lcave/valor (propertie/value) de las
	 * properties definidas en el ficheros gvHidraConfig.inc.xml
	 * @return Array Vector asociativo de properties
	 */
	public function getProperties()
	{
		return($this->vProperties);
	}//Fin getProperties


	/**
	 * getProperty: Devuelve el valor de la propiedad pasada por parámetro
	 *
	 * @param string $property Clave de la property
	 * @return mixed String o null si no existe la propiedad
	 */
	public function getProperty($property)
	{
		if (array_key_exists($property, $this->vProperties))
			return ($this->vProperties[$property]);

		return null;
	}//Fin getProperty




	/* ------------------------------------------------------------------------ */
	/* ----------------------------- MÉTODOS PRIVADOS ------------------------- */
	/* ------------------------------------------------------------------------ */

	/**
	 * _loadConfigFile: Carga un fichero de configuración XML
	 *
	 *  Carga el fichero de configuración XML indicado por parámetro y asigna
	 *  los valores leídos en el mismo en la instancia del objeto
	 * @access private
	 * @param string $fichero
	*/
	private function _loadConfigFile($fichero)
	{
		try
		{
			if (file_exists($fichero))
			{
				$sXML = simplexml_load_file($fichero);

				if ($sXML->applicationName)
					$this->applicationName =  utf8_decode((string) $sXML->applicationName);

				if ($sXML->templatesCompilationDir)
					$this->templatesCompilationDir =  (string) $sXML->templatesCompilationDir;

				if ($sXML->temporalDir)
					$this->temporalDir =  (string) $sXML->temporalDir;

				if ($sXML->reloadMappings)
					$this->reloadMappings = (boolean)(strtolower((string) $sXML->reloadMappings)=='true');

				if ($sXML->smartyCompileCheck)
					$this->smartyCompileCheck = (boolean)(strtolower((string) $sXML->smartyCompileCheck)=='true');

				if ($sXML->appVersion)
					$this->appVersion = (string) $sXML->appVersion;

				if ($sXML->gvHidraVersion)
					$this->gvHidraVersion = (string) $sXML->gvHidraVersion;

				if ($sXML->customTitle)
					$this->customTitle = utf8_decode((string) $sXML->customTitle);

				if ($sXML->barTitle)
					$this->barTitle = utf8_decode((string) $sXML->barTitle);

				if ($sXML->customDirName)
					$this->customDirName =  (string) $sXML->customDirName;

				if ($sXML->extConfigDir)
				{
					$this->extConfigDir =  (string) $sXML->extConfigDir;
					$vAtributos = $sXML->extConfigDir->attributes();
					$this->extConfigFile = realpath ($this->extConfigDir).DIRECTORY_SEPARATOR.$vAtributos->fileName;
				}

				//logSettings
				if ($sXML->logSettings)
				{
					$vAtributos = $sXML->logSettings->attributes();
					$estadoLog = (string) $vAtributos['status'];
					switch ($estadoLog)
					{
						case 'LOG_ERRORS':
							$this->logStatus = LOG_ERRORS;
						break;
						case 'LOG_AUDIT':
							$this->logStatus = LOG_AUDIT;
						break;
						case 'LOG_ALL':
							$this->logStatus = LOG_ALL;
						break;
						default:
							$this->logStatus = LOG_NONE;
					}//Fin switch
					$dsnRef = (string) $vAtributos['dsnRef'];
					if (!empty($dsnRef))
						$this->logDSN = $dsnRef;
				}//Fin if logSettings

				//Breadcrumb o ubicación
				$this->enableBreadCrumb = false;
				if ($sXML->breadcrumb)
				{
					$vAtributos = $sXML->breadcrumb->attributes();
					$strBreadCrumb = (string) $vAtributos['visible'];
					$strBreadCrumb = trim(strtolower($strBreadCrumb));
					if ($strBreadCrumb == 'true')
						$this->enableBreadCrumb = true;
				}

				//queryMode
				if ($sXML->queryMode)
				{
					$vAtributos = $sXML->queryMode->attributes();
					$queryMode = (string) $vAtributos['status'];
					switch (trim($queryMode))
					{
						case '0':
							$this->queryMode = 0;
							break;
						case '1':
							$this->queryMode = 1;
							break;
						case '2':
							$this->queryMode = 2;
							break;
						default:
							$this->queryMode = 2;
					}
				}//Fin if queryMode

				//DSNZone
				if ($sXML->DSNZone)
				{
					foreach ($sXML->DSNZone->dbDSN as $db)
					{
						$vAtributosDB = $db->attributes();
						$idDSN = (string) $vAtributosDB['id'];
						$dbType = (string) $vAtributosDB['sgbd'];
						$dbDriver = (string) $vAtributosDB['driver'];
						$dbHost= (string) $db->dbHost;
						$dbPort= (string) $db->dbPort;
						$dbDataBase= (string) $db->dbDatabase;
						$dbUser =(string) $db->dbUser;
						$dbPassword =(string) $db->dbPassword;
						$parse_DBType = $this->_parseDBType($dbType);
						if ($dbType=='thin' or $dbType=='oracle-thin')
							$this->vDSN[$idDSN] = array (
								'phptype'  => $parse_DBType,
								'username' => $dbUser,
								'password' => $dbPassword,
								'hostspec' => "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$dbHost)(PORT=$dbPort))(CONNECT_DATA=(SID=$dbDataBase)))",
								'driver'   => $dbDriver,
							);
						else
							$this->vDSN[$idDSN] = array (
								'phptype'  => $parse_DBType,
								'username' => $dbUser,
								'password' => $dbPassword,
								'hostspec' => $dbHost,
								'port'     => $dbPort,
								'database' => $dbDataBase,
								'driver'   => $dbDriver,
							);
					}//Fin foreach DB

					foreach ($sXML->DSNZone->wsDSN as $ws)
					{
						$vAtributosWS = $ws->attributes();
						$idWS = (string) $vAtributosWS['id'];

						$uriWSDL = (string) $ws->uriWSDL;
						$wsUser = (string) $ws->wsUser;
						$wsPassword = (string) $ws->wsPassword;
						$certificatePath = (string) $ws->wsCertificatePath;
						$serialCert='';
						if(!empty($certificatePath))
						{
							$vAtributosCert = $ws->wsCertificatePath->attributes();
							$serialCert = trim(strtolower($vAtributosCert['serial']));
						}

						$keyPath = (string) $ws->wsKeyPath;
						$passPhrase = (string) $ws->wsPassPhrase;
						if (empty($passPhrase))
						{
							$passPhrase = null;
						}

						$wsPAITraceIdApp = (string) $ws->wsPAITraceIdApp;
						$enablePAITrace = false;
						if(!empty($wsPAITraceIdApp))
						{
							$vAtributosPai = $ws->wsPAITraceIdApp->attributes();
							if (trim(strtolower($vAtributosPai['enablePAITrace']))=='true')
							{
								$enablePAITrace = true;
							}
						}
						$this->vDSN[$idWS] = array (
							'uriWSDL'  => 			$uriWSDL,
							'username' => 			$wsUser,
							'password' => 			$wsPassword,
							'certificatePath'=>		$certificatePath,
							'certificateSerial'=>	$serialCert,
							'keyPath' => 			$keyPath,
							'passPhrase' => 		$passPhrase,
							'wsPAITraceIdApp' =>	$wsPAITraceIdApp,
							'enablePAITrace' => 	$enablePAITrace
						);
					}//Fin foreach WS
				}//Fin if DSNZone

				/* SMTP Server */

				if ($sXML->smtpServer)
				{
					$this->smtpServer = array();

					$host = (string) $sXML->smtpServer->smtpHost;
					$port = (string) $sXML->smtpServer->smtpPort;
					$username = (string) $sXML->smtpServer->smtpUser;
					$password =(string) $sXML->smtpServer->smtpPassword;

					$this->smtpServer['host'] = $host;
					if($port!='')
						$this->smtpServer['port'] = $port;
					if($username!='')
						$this->smtpServer['username'] = $username;
					if($password!='')
						$this->smtpServer['password'] = $password;
				}


				//propertyZone
				if ($sXML->propertyZone)
				{
					foreach ($sXML->propertyZone->property as $property)
					{
						$vAtributosDB = $property->attributes();
						$idProperty = (string) $vAtributosDB['id'];
						$this->vProperties[$idProperty] = utf8_decode((string) $property);
					}
				}


				//startMsg
				 if ($sXML->startMsg)
				 {
					 $this->startMsg =  utf8_decode((string) $sXML->startMsg);
					 $vAtributos = $sXML->startMsg->attributes();
				 	/*
				 	// Posibilidad a futuro
					$idMessage = (string) $vAtributos['id'];
				 	$pathFileMessage = (string) $vAtributos['path'];
				 	*/
				 	$mesaggeType = (string) $vAtributos['type'];
				 	$this->startMsgType = 'info';
				 	if (trim(strtolower($mesaggeType)) == 'lock')
				 	{
				 		$this->startMsgType = 'lock';
				 	}
				 }//Fin if startMsg

	    	}
	    	else //REVIEW tratamiento de Excepciones
	    	{
	    		die('_loadConfigFile::MUERE AL LEER EL FICHERO CONFIG');
	    	}
		}
		catch (Exception $e)
		{
			die('_loadConfigFile::Error en carga de fichero: '.$e);
		}
	}//Fin _loadConfigFile

	/**
	 * _parseDBType: Dada una cadena con el nombre del SGBD devuelve el nombre canónico
	 *
	 * @access private
	 * @param string $sgbdType El SGBD (oracle, oci, oci8, thin, postgres, pgsql, mysql, mysqli...
	*/
	private function _parseDBType($sgbdType)
	{
		$retorno ='';
		switch (strtolower(trim($sgbdType)))
    	{
    		case 'postgres':
    		case 'pgsql':
    			$retorno = 'pgsql';
    		break;
    		case 'oci8':
    		case 'oci':
    		case 'oracle':
    		case 'thin':
    		case 'oracle-thin':
    			$retorno = 'oci8';
    		break;
    		case 'mysqli':
    		case 'mysql':
    			$retorno = 'mysqli';
    		break;
    		case 'sqlsrv':
    			$retorno = 'sqlsrv';
    		break;
    		default:
    			throw new Exception('El tipo de dsn no está soportado: '.$sgbdType);
    	}
    	return($retorno);
	}

	/**
	 * setDBList_DBSource: crea una fuente de datos para una lista mediante consulta SQL
	 *
	 *  Carga la definición de una lista desplegable en el objeto de configuración para
	 * que posteriormente pueda ser utilizada en cualquier parte de la aplicación.
	 * @access public
	 * @param string $key	clave con la que se identificará la lista en la aplicación
	 * @param string $query consulta que se ejecutará al obtener la lista
	 * @return none
	*/
	function setList_DBSource($key,$query)
	{
		$this->defList[$key]['query'] = $query;
		return 0;
	}

	/**
	 * setClassList_ClassSource: crea una fuente de datos para una lista con una clase
	 *
	 *  Asocia una clase a la fuente de datos. Atencion: La clase tiene que implementar la interfaz gvHidraList_Source
	 *
	 * @access public
	 * @param string $key	clave con la que se identificará la lista en la aplicación
	 * @param string $class clase que se instanciará para obtener la lista
	 * @return none
	*/
	function setList_ClassSource($key,$class)
	{
		//Comprobamos que la clase existe
		if(!class_exists($class)){
			IgepDebug::setDebug(ERROR,"Se ha intentado crear una ClassSource para una List con una clase que no existe. Compruebe que la clase $class esta incluida en el fichero include de la aplicacion");
			return -1;
		}
		//Comprobamos que la clase implementa la interfaz
		if(!in_array('gvHidraList_Source',class_implements($class))){

			IgepDebug::setDebug(ERROR,"La clase $class no puede ser fuente de una lista ya que no implementa la interfaz gvHidraListSource");
			return -1;
		}

		$this->defList[$key]['class'] = $class;
		return 0;
	}


	/**
	 * getDefList: devuelve la definición de una lista desplegable
	 *
	 *  Devuelve la definición de una lista desplegable. Concretamente devuelve la sentencia SELECT con
	 * la que se obtienen los resultados deseados.
	 * @access public
	 * @param string $key	clave con la que se identifica la lista en la aplicación
	 * @return string
	*/
	function getDefList($key)
	{
		if(isset($this->defList[$key]))
			return $this->defList[$key];
		return -1;
	}


	/**
	 * getListKeys: devuelve las claves de las listas desplegables
	 *
	 * @access public
	 * @return array
	*/
	function getListKeys($key)
	{
		return array_keys($this->defList);
	}


	/**
	 * Crea una fuente de datos para WindowSelection con origen en una consulta SQL.
	 * @access public
	 * @param string $key	Clave con la que se identificará la VS en la aplicación
	 * @param string $query Expresión (consulta) con la que obtendremos el resultado
	 * @param array $fields Opcional. Campos de busqueda adicionales a los incluidos en la consulta
	 * @return none
	*/
	public function setSelectionWindow_DBSource($key, $query, $fields=null)
	{
		$this->defVS[$key]['consulta'] = $query;
		if(!empty($fields))
			$this->defVS[$key]['camposBusqueda'] = $fields;
		return 0;
	}

	/**
	 * Crea una fuente de datos para WindowSelection con origen en una clase.
	 *
	 * Atencion: La clase tiene que implementar la interfaz gvHidraSelectionWindow_Source
	 *
	 * @access public
	 * @param string $key	Clave con la que se identificará la VS en la aplicación
	 * @param string $class Indica la clase que sera la definición de la VS
	 * @return none
	*/
	public function setSelectionWindow_ClassSource($key, $class)
	{
		//Comprobamos que la clase existe
		if(!class_exists($class)){
			IgepDebug::setDebug(ERROR,"Se ha intentado crear una ClassSource para una WindowSelection con una clase que no existe. Compruebe que la clase $class esta incluida en el fichero include de la aplicacion");
			return -1;
		}
		//Comprobamos que la clase implementa la interfaz
		if(!in_array('gvHidraSelectionWindow_Source',class_implements($class))){

			IgepDebug::setDebug(ERROR,"La clase $class no puede ser fuente de una SelectionWindow ya que no implementa la interfaz gvHidraSelectionWindow_Source");
			return -1;
		}

		$this->defVS[$key]['class'] = $class;
		return 0;
	}



	/**
	 * getDefVS: devuelve la definición de una ventana de seleccion
	 *
	 *  Devuelve la definición de una ventana de seleccion.
	 * @access public
	 * @param string $key	clave con la que se identifica la lista en la aplicación
	 * @return array
	*/
	function getDefVS($key)
	{
		if(isset($this->defVS[$key]))
			return $this->defVS[$key];
		return -1;
	}


	/**
	 * getVSKeys: devuelve las claves de las ventanas de seleccion
	 *
	 * @access public
	 * @return array
	*/
	function getVSKeys()
	{
		return array_keys($this->defVS);
	}

	/**
	 * setDSN: almacena un vector de conexion a una BD
	 *
	 *  Guarda en la configuración una conexión a la BD que posteriormente
	 * se utilizará en la aplicacion.
	 * @access public
	 * @param string $key	clave con la que se identificará el array de conexion
	 * @param string $vdsn	array de conexion basado en la estructura PEAR
	 * @return none
	*/
	function setDSN($key, $vdsn)
	{
		$this->vDSN[$key] = $vdsn;
	}


	/**
	 * getDSN: devuelve el array de conexion a una fuente de datos (SGBD o WS)
	 *
	 * Dada un ID devuelve el array de conexion correspondiente a una fuente de
	 * datos. Dicha fuente puede ser una conexión a un SGBD o bien a un recurso
	 * WService
	 * @access public
	 * @param string $key	clave con la que se identifica el array de conexion
	 * @return array
	*/
	function getDSN($key)
	{
		if(isset($this->vDSN[$key]))
			return $this->vDSN[$key];
		return null;
	}

	public function getSMTPServer() {

		return $this->smtpServer;
	}

	/**
	 * getDSNLog: Devuelve el dsn usado para el Log
	 *
	 * @access public
	 * @return array
	*/
	function getDSNLog()
	{
		return $this->vDSN[$this->logDSN];
	}

	/**
	* getNumericSeparatorsFW: devuelve el formato numérico que utilizará en FW.
	* 	Este formato coincidirá con el formato PHP
	* @acces public
	* @return array
	*/
	static function getNumericSeparatorsFW(){
  		return array('DECIMAL'=>'.','GROUP'=>'');
	}

	/**
	* getNumericSeparatorsUser: devuelve el formato numérico que utilizará el usuario en pantalla.
	*
	* @acces public
	* @return array
	*/
	static function getNumericSeparatorsUser(){
		return array('DECIMAL'=>',','GROUP'=>'.');
	}

	/**
	* getDateMaskFW: devuelve el formato de fechas que utilizará en FW.
	* 	Este formato coincidirá con el formato PHP
	* @acces public
	* @return string
	*/
	static function getDateMaskFW(){
  		return 'Y-n-j';
	}

	/**
	* getDateMaskUser: devuelve la mascara de representacion de fechas de pantalla
	*
	* @acces public
	* @return string
	*/
	static function getDateMaskUser(){
		return 'd/m/Y';
	}

	/**
	* getTimeMask: devuelve la mascara de hora usada en todos los sitios (bd, User, FW)
	* Si se cambia, ha de ser valido para todos los sgbd soportados
	*
	* @acces public
	* @return string
	*/
	static function getTimeMask()
	{
		return 'H:i:s';
	}

	static function getTransformErrors()
	{
		return self::$transformErrors;
	}

	static function setTransformErrors($lis)
	{
		self::$transformErrors = $lis;
		if (!empty($lis))
			IgepDebug::setDebug(DEBUG_IGEP,'La transformación ha detectado errores en la entrada del usuario: '.var_export($lis,true));
	}

	/**
	* getStringFromXML: metodo para obtener el valor de un atributo en un fichero XML.
	*
	* @acces private
	* @return string
	*/
	static private function getStringFromXML($fichero, $atributo)
	{
		$sXML = simplexml_load_file($fichero);
		$value = (string) $sXML->$atributo;
		unset($sXML);//Liberamos el recurso
		return($value);
	}


}//Fin ConfigFramework

?>