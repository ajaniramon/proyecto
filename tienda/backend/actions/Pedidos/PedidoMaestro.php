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
* Clase Manejadora PedidoMaestro
* 
* Creada con Genaro: generador de c�digo de gvHIDRA
* 
* @autor genaro
* @version 2.0
* @license http://opensource.org/licenses/gpl-2.0.php GNU Public License v.2
*/

class PedidoMaestro extends gvHidraForm_DB
{
	public function __construct() {

		//Recogemos dsn de conexion
		$conf = ConfigFramework::getConfig();
		$g_dsn = $conf->getDSN('g_dsn');

		$nombreTablas= array('pedido');
		parent::__construct($g_dsn, $nombreTablas);

		
		/************************ QUERYs ************************/
		
		//Consulta del modo de trabajo LIS
		$str_select = <<<query
SELECT pedido.idpedido as "lis_idpedido", pedido.fecha as "lis_fecha", total as "lis_total", pedido.dni as "lis_dni", concat(cliente.nombre,concat(" ",cliente.apellido)) as "lis_nombre" FROM pedido  INNER JOIN cliente  ON pedido.dni = cliente.dni
query;
;
		$this->setSelectForSearchQuery($str_select);

		//Where del modo de trabajo LIS
		//$str_where = "";
		//$this->setWhereForSearchQuery($str_where);

		//Order del modo de trabajo LIS
		$this->setOrderByForSearchQuery('1');

		/************************ END QUERYs ************************/

		/************************ MATCHINGs ************************/

		//Seccion de matching: asociacion campos TPL y campos BD

		//Modo de trabajo FIL
		$this->addMatching("fil_idpedido", "idpedido", "pedido");
		$this->addMatching("fil_fecha", "fecha", "pedido");
		$this->addMatching("fil_total", "total", "pedido");
		$this->addMatching("fil_dni", "dni", "pedido");

		//Modo de trabajo LIS
		$this->addMatching("lis_idpedido", "idpedido", "pedido");
		$this->addMatching("lis_fecha", "fecha", "pedido");
		$this->addMatching("lis_total", "total", "pedido");
		$this->addMatching("lis_dni", "dni", "pedido");

		/************************ END MATCHINGs ************************/


		/************************ TYPEs ************************/

		//Fechas: gvHidraDate type
		$fecha = new gvHidraDatetime(false);
		$fecha->setCalendar(false);
		$this->addFieldType('fil_fecha',$fecha);
		$fecha->setCalendar(false);
		$this->addFieldType('lis_fecha',$fecha);


		//Strings: gvHidraString type
		$string = new gvHidraString(false, 9);
		$this->addFieldType('fil_dni',$string);
		$string = new gvHidraString(false, 9);
		$this->addFieldType('lis_dni',$string);

		$nombre = new gvHidraString(false,9);
		$this->addFieldType('lis_nombre',$nombre);

		//Integers: gvHidraInteger type
		$int = new gvHidraInteger(false, 4);
		$this->addFieldType('fil_idpedido',$int);
		$int = new gvHidraInteger(true, 4);
		$this->addFieldType('lis_idpedido',$int);
		

		//Floats: gvHidraFloat type
		$float = new gvHidraFloat(false, 7);
		$float->setFloatLength(2);
		$this->addFieldType('fil_total',$float);
		$float = new gvHidraFloat(true, 7);
		$float->setFloatLength(2);
		$this->addFieldType('lis_total',$float);
		

		/************************ END TYPEs ************************/
				
		/************************ COMPONENTS ************************/
		
		//Declaracion de Listas y WindowSelection
		//La definici�n debe estar en el AppMainWindow.php

		/************************ END COMPONENTS ************************/

		//Relacionamos con las clases detalle
		$this->addSlave('PedidoDetalle', array('lis_idpedido'), array('lis_idpedido'));		

		//Mantener los valores del modo de trabajo FIL tras la busqueda
		$this->keepFilterValuesAfterSearch(true);

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
	* metodo preInsertar
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui la logica para parametrizar los datos a insertar. Por ejemplo:
	* - Calcular el valor de una secuencia.
	* - Cancelar la acci�n de insercion.
	*/		
	public function preInsertar($objDatos) {
		
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
	* - Cancelar la acci�n de actualizacion.
	*/
	public function preModificar($objDatos) {
		
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
	* - Cancelar la acci�n de borrado.
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
	
}//End PedidoMaestro

?>