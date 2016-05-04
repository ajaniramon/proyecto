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
*  www.gvhidra.org
*
*/

/**
* Clase Manejadora <<$classname_detalle|capitalize>>
* 
* Creada con Genaro: generador de código de gvHIDRA
* 
* @autor genaro
* @version 1.0
* @license http://opensource.org/licenses/gpl-2.0.php GNU Public License v.2
*/

class <<$classname_detalle|capitalize>> extends gvHidraForm_DB
{
	public function __construct() {

		//Recogemos dsn de conexion
		$conf = ConfigFramework::getConfig();
		$g_dsn = $conf->getDSN('<<$dsn>>');


		$nombreTablas= array('<<$tablename_detalle>>');
		parent::__construct($g_dsn, $nombreTablas);



		/************************ QUERYs ************************/
		
		//Consulta del modo de trabajo LIS
		$str_select = "SELECT <<section name=select loop=$fields_detalle>><<$fields_detalle[select]>> as \"<<if $foreignKey_detalle[select] eq "true">><<$fields_detalle[select]>><<else>>lis_<<$fields_detalle[select]>><</if>>\"<<if $smarty.section.select.last>><<else>>, <</if>><</section>> FROM <<$tablename_detalle>>";
		$this->setSelectForSearchQuery($str_select);

		//Where del modo de trabajo LIS
		//$str_where = "";
		//$this->setWhereForSearchQuery($str_where);

		//Order del modo de trabajo LIS
		$this->setOrderByForSearchQuery('1');

		//Consulta del modo de trabajo EDI
		$str_select_editar = "SELECT <<section name=select loop=$fields_detalle>><<$fields_detalle[select]>> as \"<<if $foreignKey_detalle[select] eq "true">><<$fields_detalle[select]>><<else>>edi_<<$fields_detalle[select]>><</if>>\"<<if $smarty.section.select.last>><<else>>, <</if>><</section>> FROM <<$tablename_detalle>>";
		$this->setSelectForeditQuery($str_select_editar);

		//Where del modo de trabajo EDI
		//$str_where_editar = "";
		//$this->setWhereForEditQuery($str_where_listar);

		//Order del modo de trabajo EDI
		$this->setOrderByForeditQuery('1');

		/************************ END QUERYs ************************/
		


		/************************ MATCHINGs ************************/

		//Seccion de matching: asociacion campos TPL y campos BD
<<section name=lis loop=$fields_detalle>>
		$this->addMatching("<<if $foreignKey_detalle[lis] eq "true">><<$fields_detalle[lis]>><<else>>lis_<<$fields_detalle[lis]>><</if>>", "<<$fields_detalle[lis]>>", "<<$tablename_detalle>>");
		$this->addMatching("<<if $foreignKey_detalle[lis] eq "true">><<$fields_detalle[lis]>><<else>>edi_<<$fields_detalle[lis]>><</if>>", "<<$fields_detalle[lis]>>", "<<$tablename_detalle>>");
		
//	<<if $foreignKey_detalle[lis] eq "true">>
//			$this->addMatching('<<$fields_detalle[lis]>>', '<<$fields_detalle[lis]>>', '<<$tablename_detalle>>');
//	<<else>>
//			$this->addMatching('lis_<<$fields_detalle[lis]>>', '<<$fields_detalle[lis]>>', '<<$tablename_detalle>>');
//			$this->addMatching('edi_<<$fields_detalle[lis]>>', '<<$fields_detalle[lis]>>', '<<$tablename_detalle>>');
//	<</if>>		
<</section>>


		/************************ END MATCHINGs ************************/


		/************************ TYPEs ************************/
	
		//Fechas: gvHidraDate type
<<section name=fecha loop=$fields_detalle>>
<<assign var='campo' value=$fields_detalle[fecha]>>
<<assign var='reqVal' value=$customFields.$campo.reqVal>>
<<assign var='calVal_lis' value=$customFields.$campo.calVal>>
<<assign var='calVal_edi' value=$customFields.$campo.calVal>>
<<if $types_detalle[fecha] eq "gvHidraDate">>
		$fecha = new gvHidraDate(false);
<<if $calVal_fil eq 1>>
    	$fecha->setCalendar(true);
<<else>>
    	$fecha->setCalendar(false);
<</if>>
    	$this->addFieldType('lis_<<$fields_detalle[fecha]>>',$fecha);
<<if $notnulls[fecha] eq 'true'>>
		$fecha = new gvHidraDate(false);
<</if>>
<<if $calVal_edi eq 1>>
		$fecha->setCalendar(true);
<<else>>
		$fecha->setCalendar(false);
<</if>>
<<if $reqVal eq 1>>
		$fecha->setRequired(true);
<</if>>
		$this->addFieldType('edi_<<$fields_detalle[fecha]>>',$fecha);

<</if>>
<<if $types_detalle[fecha] eq "gvHidraDatetime">>
		$fecha = new gvHidraDatetime(false);
<<if $calVal_fil eq 1>>
		$fecha->setCalendar(true);
<<else>>
		$fecha->setCalendar(false);
<</if>>
		$this->addFieldType('lis_<<$fields_detalle[fecha]>>',$fecha);
<<if $notnulls[fecha] eq 'true'>>
		$fecha = new gvHidraDatetime(true);
<</if>>
<<if $calVal_edi eq 1>>
		$fecha->setCalendar(true);
<<else>>
		$fecha->setCalendar(false);
<</if>>
<<if $reqVal eq 1>>
		$fecha->setRequired(true);
<</if>>
		$this->addFieldType('edi_<<$fields_detalle[fecha]>>',$fecha);

<</if>>
<</section>>

		//Strings: gvHidraString type
<<section name=string loop=$fields_detalle>>
<<assign var='campo' value=$fields_detalle[string]>>
<<assign var='mascara' value=$customFields.$campo.maskVal>>
<<assign var='reqVal' value=$customFields.$campo.reqVal>>
<<if $types_detalle[string] eq "gvHidraString">>
<<if $lengths_detalle[string] eq "">>
<<assign var='length' value='200'>>
<<else>>
<<assign var='length' value=$lengths_detalle[string]>>
<</if>>
		$string = new gvHidraString(false, <<$length>>);
<<if $mascara neq ''>>
		$string -> setInputMask('<<$mascara>>');
<</if>>
		$this->addFieldType('lis_<<$fields_detalle[string]>>',$string);
<<if $notnulls[string] eq 'true'>>
		$string = new gvHidraString(false, <<$length>>);
<<if $mascara neq ''>>
		$string->setInputMask('<<$mascara>>');
<</if>>
<<if $reqVal eq 1>>
		$string->setRequired(true);
<</if>>
		$this->addFieldType('edi_<<$fields_detalle[string]>>',$string);
<</if>>
<</if>>
<</section>>

		//Integers: gvHidraInteger type
<<section name=int loop=$fields_detalle>>
<<assign var='campo' value=$fields_detalle[int]>>
<<assign var='reqVal' value=$customFields.$campo.reqVal>>
<<if $types_detalle[int] eq "gvHidraInteger">>
		$int = new gvHidraInteger(false, <<$lengths_detalle[int]>>);
		$this->addFieldType('lis_<<$fields_detalle[int]>>',$int);		
<<if $notnulls[int] eq 'true'>>
		$int = new gvHidraInteger(true, <<$lengths_detalle[int]>>);
<</if>>
<<if $reqVal eq 1>>
		$int->setRequired(true);
<</if>>
		$this->addFieldType('edi_<<$fields_detalle[int]>>',$int);
		
<</if>>
<</section>>

		//Floats: gvHidraFloat type
<<section name=float loop=$fields_detalle>>
<<assign var='campo' value=$fields_detalle[float]>>
<<assign var='reqVal' value=$customFields.$campo.reqVal>>
<<if $types_detalle[float] eq "gvHidraFloat">>
<<assign var='partes' value=','|explode:$lengths_detalle[float]>>
		$float = new gvHidraFloat(false, <<$partes[0]>>);
		$float->setFloatLength(<<$partes[1]>>);
		$this->addFieldType('lis_<<$fields_detalle[float]>>',$float);		
<<if $notnulls[float] eq 'true'>>
		$float = new gvHidraFloat(true, <<$partes[0]>>);
		$float->setFloatLength(<<$partes[1]>>);
<</if>>
<<if $reqVal eq 1>>
		$float->setRequired(true);
<</if>>
		$this->addFieldType('edi_<<$fields_detalle[float]>>',$float);
		
<</if>>
<</section>>

		/************************ END TYPEs ************************/
				
		/************************ COMPONENTS ************************/
		
		//Declaracion de Listas y WindowSelection
		//La definición debe estar en el AppMainWindow.php

<<section name=components loop=$fields_detalle>>
<<assign var='campo' value=$fields_detalle[components]>>
<<assign var='componente' value=$customFields.$campo.componente>>
<<if $componente eq 2>>
<<*CheckBox*>>
		$check_lis = new gvHidraCheckBox('lis_<<$fields_detalle[components]>>');
		$check_lis->setChecked(false);
		$check_lis->setValueChecked('');
		$check_lis->setValueUnchecked('');
		$this->addCheckBox($check_lis);
		
		$check_edi = new gvHidraCheckBox('edi_<<$fields_detalle[components]>>');
		$check_edi->setChecked(false);
		$check_edi->setValueChecked('');
		$check_edi->setValueUnchecked('');
		$this->addCheckBox($check_lis);
		
<</if>>
<<if $componente eq 3>>
<<*RadioButton*>>
		$radio_lis = new gvHidraList('lis_<<$fields_detalle[components]>>');
		$radio_lis->setRadio(true);
		$radio_lis->addOption('','Default 1');
		$radio_lis->addOption('','Default 2');
		$radio_lis->setSelected('');
		$this->addList($radio_lis);
		
		$radio_edi = new gvHidraList('edi_<<$fields_detalle[components]>>');
		$radio_edi->setRadio(true);
		$radio_edi->addOption('','Default 1');
		$radio_edi->addOption('','Default 2');
		$radio_edi->setSelected('');
		$this->addList($radio_edi);
		
<</if>>
<<if $componente eq 4>>
<<*Lista*>>
		$lista_lis = new gvHidraList('edi_<<$fields_detalle[components]>>');
		$lista_lis->addOption('','Default 1');
		$lista_lis->addOption('','Default 2');
		$lista_lis->setSelected('');
		$this->addList($lista_lis);
		
<</if>>
<</section>>
		/************************ END COMPONENTS ************************/						
		
		//Asociamos con la clase maestro
		$this->addMaster("<<$classname_maestro|capitalize>>");
		
	}//End construct

	/************************ CRUD METHODs ************************/

	/**
	* metodo preRecargar
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui la logica para parametrizar la carga del detalle. Por ejemplo:
	* - Incluir condiciones.
	* - Cancelar la accion. 
	*/	
	public function preBuscar($objDatos) {
		
		return 0;
	}

	/**
	* metodo postRecargar
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui la logica para parametrizar los datos obtenidos. Por ejemplo:
	* - Completar la informacion obtenida.
	* - Cambiar el color de las filas dependiendo de su valor
	*/	
	public function postRecargar($objDatos) {
		
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
	* - Cancelar la acción de insercion.
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
	* - Cancelar la acción de actualizacion.
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
	* - Cancelar la acción de borrado.
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
        
		throw new Exception('Se ha intentado ejecutar la acción '.$str_accion.' y no está programada.');        
    }
	
}//End <<$classname_detalle|capitalize>>

?>