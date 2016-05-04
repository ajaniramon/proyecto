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
 * Gestion del autoload
 * Sigue el patron singleton
 * En vez de poner todos los includes, el usuario registra clases (y su ubicacion) y carpetas
 * y cuando hace falta la definicion de una clase, ésta intenta cargarla.
 *
 * @package gvHIDRA
 */
class GVHAutoLoad {
    private static $instance;
	const DEFAULT_DIR = 'actions';
	private $ini_path;

	private function __construct()
	{
		$this->clases = array();
		$this->dirs = array();
	}

    public static function singleton()
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    public function __clone()
    {
        throw new gvHidraException('Clone is not allowed.');
    }

	function registerClass($clase, $ruta)
	{
		if (!empty($clase) and !empty($ruta))
			$this->clases[$clase] = $ruta;
	}

	/**
	 * Usando el set_include_path no hace falta acabar en /
	 */
	function registerFolder($dir)
	{
		if (!empty($dir) and !in_array($dir, $this->dirs)) {
				$this->dirs[] = $dir;
		}
	}

	/**
	 * metodo llamado en __autoload
	 * Si esta en la lista de clases la carga, y si no va probando si
	 * esta en alguna de las carpetas registradas.
	 */
	function auto_load($class_name)
	{
		if (empty($class_name)) return;
		if (array_key_exists($class_name, $this->clases))
			require_once $this->clases[$class_name];
		else {
			$dirs = $this->dirs;
			if (empty($dirs))
				$dirs[] = self::DEFAULT_DIR;
			foreach ($dirs as $dir) {
				$file = $dir.DIRECTORY_SEPARATOR.$class_name.'.php';
				if (file_exists($file)) {
					require_once $file;
					break;
				}
			}
		}
	}

}

$al = GVHAutoLoad::singleton();
spl_autoload_register(array($al, 'auto_load'));

?>
