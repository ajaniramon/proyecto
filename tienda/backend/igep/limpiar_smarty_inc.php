<?php
/**
* Limpia templates_c. Este php no hay que invocarlo directamente desde el
* navegador ya que en ese caso fallan los includes. Se invoca desde el 
* fichero limpiar_smarty.php que debe estar en la raiz de cualquier aplicación
* que utilice igep.
* 
* @author GASPAR
* @version 1.0
* @since 10/09/2004
*/
include_once('igep/include_class.php');
IgepSession::session_start();
include_once ('igep/include_all.php');

$aux = new AppMainWindow();

$opcion = (@$_GET['opcion']) ? $_GET['opcion'] : 'limpiar';

$s = new Smarty_Phrame();

if ($opcion == 'carpeta')
  echo $s->compile_dir;
elseif ($opcion == 'aplicacion'){
  $aplicacion = ConfigFramework::getApplicationName();
  echo $aplicacion;
}
else{
  if ($s -> clear_compiled_tpl()) {
	echo "Se ha limpiado la carpeta: $s->compile_dir";
  } else
	echo "Ha fallado";
}
?>
