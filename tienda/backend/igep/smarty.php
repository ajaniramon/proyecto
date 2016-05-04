<?php
/**
* Este fichero contendrá la clase Smarty_Phrame con relacion a la clase ComponentesWeb
* utilizando la estructura CWHTM como libreria
*/
define('SMARTY_DIR','igep/smarty/');
include(SMARTY_DIR.'Smarty.class.php');
// Se requieren estos includes
require_once('igep/include/IgepPlugin.php');

class Smarty_Phrame extends Smarty {
	/* Propiedades de la clase */
	var $igepPlugin;

	function Smarty_Phrame() {		
		$this->Smarty();
		$configuration = ConfigFramework::getConfig();
		$customDirname = $configuration->getCustomDirName();
		$this->template_dir = array('igep/plantillas','plantillas','custom/'.$customDirname.'/plantillas');		
		$this->compile_dir =  $configuration->getTemplatesCompilationDir();
		$this->plugins_dir= array(SMARTY_DIR.'plugins/' );
		$this->caching = false;
		if (!$configuration->getSmartyCompileCheck())
			$this->compile_check=false;
		/* Instanciamos la clase que gestiona los componentes web */
		$this->igepPlugin = new IgepPlugin();
						
   }//FIN funcion Smarty_Phrame
   
}; //FIN clase Smarty_Phrame
?>