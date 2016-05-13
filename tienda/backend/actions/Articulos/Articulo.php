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
*  www.gvhidra.org
*
*/

/**
* Clase Manejadora Articulo
* 
* Creada con Genaro: generador de c�digo de gvHIDRA
* 
* @autor genaro
* @version 2.0
* @license http://opensource.org/licenses/gpl-2.0.php GNU Public License v.2
*/

class Articulo extends gvHidraForm_DB
{
	public function __construct() {

		//Recogemos dsn de conexion
		$conf = ConfigFramework::getConfig();
		$g_dsn = $conf->getDSN('g_dsn');

		$nombreTablas= array('articulo');
		parent::__construct($g_dsn, $nombreTablas);

		/************************ QUERYs ************************/
		
		//Consulta del modo de trabajo LIS
		$str_select = "SELECT idarticulo as \"lis_idarticulo\", nombre as \"lis_nombre\", descripcion as \"lis_descripcion\", precio as \"lis_precio\", imagen as \"lis_imagen\", stock as \"lis_stock\", categoria as \"lis_categoria\" FROM articulo";
		$this->setSelectForSearchQuery($str_select);

		//Where del modo de trabajo LIS
		//$str_where = "";
		//$this->setWhereForSearchQuery($str_where);

		//Order del modo de trabajo LIS
		$this->setOrderByForSearchQuery('1');


		//Consulta del modo de trabajo EDI
		$str_select_editar = "SELECT idarticulo as \"edi_idarticulo\", nombre as \"edi_nombre\", descripcion as \"edi_descripcion\", precio as \"edi_precio\", imagen as \"edi_imagen\", stock as \"edi_stock\", categoria as \"edi_categoria\" FROM articulo";		 
		$this->setSelectForEditQuery($str_select_editar);

		//Where del modo de trabajo EDI		 
		//$str_where_editar = "";
		//$this->setWhereForEditQuery($str_where_editar);

		//Order del modo de trabajo EDI
		$this->setOrderByForEditQuery('1');
		//$this->addDefaultData("edi_idarticulo","null");
		/************************ END QUERYs ************************/


		/************************ MATCHINGs ************************/

		//Seccion de matching: asociacion campos TPL y campos BD

		//Modo de trabajo FIL
		$this->addMatching("fil_idarticulo","idarticulo","articulo");
		$this->addMatching("fil_nombre","nombre","articulo");
		$this->addMatching("fil_descripcion","descripcion","articulo");
		$this->addMatching("fil_precio","precio","articulo");
		$this->addMatching("fil_imagen","imagen","articulo");
		$this->addMatching("fil_stock","stock","articulo");
		$this->addMatching("fil_categoria","categoria","articulo");

		//Modo de trabajo LIS
		$this->addMatching("lis_idarticulo","idarticulo","articulo");
		$this->addMatching("lis_nombre","nombre","articulo");
		$this->addMatching("lis_descripcion","descripcion","articulo");
		$this->addMatching("lis_precio","precio","articulo");
		$this->addMatching("lis_imagen","imagen","articulo");
		$this->addMatching("lis_stock","stock","articulo");
		$this->addMatching("lis_categoria","categoria","articulo");

		//Modo de trabajo EDI
		$this->addMatching("edi_idarticulo","idarticulo","articulo");
		$this->addMatching("edi_nombre","nombre","articulo");
		$this->addMatching("edi_descripcion","descripcion","articulo");
		$this->addMatching("edi_precio","precio","articulo");
		$this->addMatching("edi_imagen","imagen","articulo");
		$this->addMatching("edi_stock","stock","articulo");
		$this->addMatching("edi_categoria","categoria","articulo");

		/************************ END MATCHINGs ************************/


		/************************ TYPEs ************************/
	
		//Fechas: gvHidraDate type

		//Strings: gvHidraString type
		$string = new gvHidraString(false, 40);
		$this->addFieldType('fil_nombre',$string);
		$this->addFieldType('lis_nombre',$string);
		$this->addFieldType('edi_nombre',$string);
		
		$string = new gvHidraString(false, 40);
		$this->addFieldType('fil_imagen',$string);
		$this->addFieldType('lis_imagen',$string);
		$this->addFieldType('edi_imagen',$string);
		

		//Integers: gvHidraInteger type
		$int = new gvHidraInteger(false, 4);
		$this->addFieldType('fil_idarticulo',$int);
		$this->addFieldType('lis_idarticulo',$int);		
		$int = new gvHidraInteger(false, 4);
		$this->addFieldType('edi_idarticulo',$int);
		
		$int = new gvHidraInteger(false, 4);
		$stockEdi = new gvHidraInteger(true,4);
		$this->addFieldType('fil_stock',$int);
		$this->addFieldType('lis_stock',$int);		
		$this->addFieldType('edi_stock',$stockEdi);
		
		
		

		//Floats: gvHidraFloat type
		$float = new gvHidraFloat(false, 7);
		$precioEdi = new gvHidraFloat(true,7);
		$float->setFloatLength(2);
		$this->addFieldType('fil_precio',$float);
		$this->addFieldType('lis_precio',$float);		
		$this->addFieldType('edi_precio',$precioEdi);
		

		/************************ END TYPEs ************************/
				
		/************************ COMPONENTS ************************/
		
		//Declaracion de Listas y WindowSelection
		$categorias = new gvHidraList("fil_categoria","CATEGORIAS");
		$categorias->addOption("","");
		$this->addList($categorias);

		$categoriasEdi = new gvHidraList("edi_categoria","CATEGORIAS");
		$categoriasEdi->addOption("","");
		$this->addList($categoriasEdi);


		//La definici�n debe estar en el AppMainWindow.php


		/************************ END COMPONENTS ************************/						

		
		//Mantener los valores del modo de trabajo FIL tras la busqueda
		$this->keepFilterValuesAfterSearch(false);
		$this->showOnlyNewRecordsAfterInsert(false);

	}//End construct

	/************************ CRUD METHODs ************************/

	/**
	* metodo preBuscar
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui la logica para parametrizar la busqueda. Por ejemplo:
	* - Incluir condiciones de filtrado.
	* - Cancelar la accion de buscar. 
	*/	
	public function preBuscar($objDatos) {

		return 0;
	}

	/**
	* metodo postBuscar
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui la logica para parametrizar los datos obtenidos. Por ejemplo:
	* - Completar la informacion obtenida.
	* - Cambiar el color de las filas dependiendo de su valor
	*/	
	public function postBuscar($objDatos) {
		
		return 0;
	}

	/**
	* metodo preEditar
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui la logica para parametrizar la edicion. Por ejemplo:
	* - Incluir condiciones de filtrado.
	* - Cancelar la accion. 
	*/	
	public function preEditar($objDatos) {

		return 0;
	}

	/**
	* metodo postEditar
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui la logica para parametrizar los datos obtenidos. Por ejemplo:
	* - Completar la informacion obtenida.
	*/	
	public function postEditar($objDatos) {

		return 0;
	}

	/**
	* metodo preInsertar
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui la logica para parametrizar los datos a insertar. Por ejemplo:
	* - Calcular el valor de una secuencia.
	* - Cancelar la acci?n de insercion.
	*/		
	public function preInsertar($objDatos) {
		$categoria = $objDatos->getValue("edi_categoria");

		if(empty($categoria) || $categoria == "" || is_null($categoria)){
			$this->showMessage("APL-2");
			return -1;
		}
		/*$precio = $objDatos->getValue("edi_precio");
		if(empty($precio) || $precio == "" || is_null($precio)){
			$this->showMessage("APL-3");
			return -1;
		}*/
		return 0;
	}
	
	/**
	* metodo postInsertar
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui la logica para completar la operacion de insercion. Por ejemplo:
	* - Insertar en una segunda tabla.
	*/		
	public function postInsertar($objDatos) {
		
		return 0;
	}

	/**
	* metodo preModificar
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui la logica para parametrizar la operacion de actualizacion. Por ejemplo:
	* - Calcular valores derivados.
	* - Cancelar la acci?n de actualizacion.
	*/
	public function preModificar($objDatos) {
		$categoria = $objDatos->getValue("edi_categoria");

		if(empty($categoria) || $categoria == "" || is_null($categoria)){
			$this->showMessage("APL-2");
			return -1;
		}

		return 0;
	}
	
	/**
	* metodo postModificar
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui la logica para completar la operacion de actulizacion. Por ejemplo:
	* - Actualizar en una segunda tabla
	*/	
	public function postModificar($objDatos) {
		
		return 0;
	}
	
	/**
	* metodo preBorrar
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui la logica para parametrizar la operacion de borrado. Por ejemplo:
	* - Cancelar la acci?n de borrado.
	*/	
	public function preBorrar($objDatos) {
		
		return 0;
	}
	
	/**
	* metodo postBorrar
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui la logica para completar la operacion de borrado. Por ejemplo:
	* - Borrar en una segunda tabla
	*/	
	public function postBorrar($objDatos) {
		
		return 0;
	}
	
	/**
	* metodo preNuevo
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui los valores por defecto antes de insertar.
	*/	
	public function preNuevo($objDatos) {
		
		return 0;
	}
	
	/**
	* metodo preIniciarVentana
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui la logica a ejecutar cuando entra en la ventana. Por ejemplo:
	* - Puede comprobar que el usuario tiene los permisos necesarios.
	*/	
	public function preIniciarVentana($objDatos) {
		
		return 0;
	}
	
	/************************ END CRUD METHODs ************************/
	
	/**
	* metodo accionesParticulares
	* 
	* @access public
	* @param string $str_accion
	* @param object $objDatos
	* 
	* Incorpore aqui la logica de sus acciones particulares. 
	* -En el parametro $str_accion aparece el id de la accion.
	* -En el parametro $objDatos esta la informacion de la peticion. Recuerde que debe fijar la operacion
	* con el metodo setOperacion.
	*/	
	public function accionesParticulares($str_accion, $objDatos) {
        
		throw new Exception('Se ha intentado ejecutar la acci�n '.$str_accion.' y no est� programada.');        
    }
	
}//End  Articulo