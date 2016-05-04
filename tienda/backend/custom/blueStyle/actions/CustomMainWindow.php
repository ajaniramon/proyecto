<?php
								
class CustomMainWindow extends gvHidraMainWindow{	

	public function CustomMainWindow(){

		parent::__construct();
		
		//Cargamos propiedades específicas del CS
		//Cuando estamos en desarrollo registramos todos los movimientos
		$conf = ConfigFramework::getConfig();
		//Fija la ubicacion del directorio de compilacion
		$dir_templates_c = 'templates_c/';
		$conf->setTemplatesCompilationDir($dir_templates_c);		
		
		/* para evitar un error de STRICT que dice que tienes que indicar la zona horaria por defecto*/
		date_default_timezone_set("Europe/Madrid");
	}
}//Fin de CustomMainWindow
?>