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
 * gvHidraForm_dummy
 * Clase para generacion de prototipos de ventana.
 * 
 * Como su nombre indica, se trata de un ejemplo que permite hacer prototipos animados de pantallas emulando
 * el comportamiento de una ventana real.
 * 
 * @version $Id: gvHidraForm_dummy.php,v 1.7 2010-04-06 09:01:09 afelixf Exp $
 * 
 * @author Gaspar: <quiles_gas@gva.es> 
 * @author Toni: <felix_ant@gva.es>
 *
 * @package gvHIDRA
 */   
class gvHidraForm_dummy extends gvHidraForm {	

	/**
	 * Constructor de 
	 */
	public function __construct($data, $ids) {

		$this->setDataForSearch($data);
		$this->setIdsForData($ids);
		$this->setDataForEdit(null);

		parent::__construct();
		
	} //Fin de Constructor


	//Busqueda
    public function prepareDataSource() {

    }

    public function recoverData() {

    	return $this->data;
    } 

    //Edicion
    public function prepareDataSourceEdit() {

    	$this->filasSeleccionadas = $this->comunica->getAlltuplas();
    }
    
    public function recoverDataEdit() {

		$filas = array();
    	foreach ($this->filasSeleccionadas as $key=>$value) {
    		$res = $this->existeRegistro($value,2);
    		if ($res[0]) {
    			$filas[$key] = $res[1];
    		}
    	}
	    if (empty($filas))
        	$filas = $this->dataEdit;
       	return $filas;
    }
    

	//Maestro-detalle
	public function prepareDataSourceDetails($detail,$masterData) { 

		$v_datos = array();
		foreach ($this->v_hijos[$this->panelDetalleActivo] as $padre => $hijo){         
			
			$filapadre = array_keys($masterData);
	        //Si es una lista le asignamos el valor del seleccionado
	        if(is_array($masterData[$filapadre[0]][$padre])){

	            $v_datos[$hijo] = $masterData[$filapadre[0]][$padre]['seleccionado'];
			}
	        else{

	            $v_datos[$hijo] = $masterData[$filapadre[0]][$padre];
			}
		}
		//Tenemos que seleccionar dentro del array detalle todas las filas que pertenezcan al maestro seleccionado.
		$detail->filaSeleccionadaMaestro = $v_datos;
	}

    
    public function recoverDataDetail() {

		$filas = array();				
		//Obtenemos las keys
		$fieldsToCompare = array_keys($this->filaSeleccionadaMaestro);
		$numberOfComparations = count($fieldsToCompare);
		
		foreach($this->data as $index => $row) {
			$comparations = 0;
			foreach($fieldsToCompare as $field) {
				if($row[$field]!=$this->filaSeleccionadaMaestro[$field])
					break;
				$comparations++;
				if($comparations==$numberOfComparations)
					$filas[]=$row;					
			}
		}
		return $filas;		
    }    

    

	//Insercion
	public function processInsert() {

		$m_datosTpl = $this->comunica->getAllTuplas();
		$nuevas = array();
		foreach ($m_datosTpl as $filanueva) {
			$res = $this->existeRegistro($filanueva);
			if ($res[0]) {
				// error de identificacion
				$this->showMessage('IGEP-1',array('El registro \''.$res[2].'\' ya existe'));
				return -1;
			} else
				$nuevas[] = $filanueva;
		}
        // confirmacion de la insercion
		$this->data = array_merge($this->data, $nuevas);
		if($this->dataEdit != null) {
			$this->dataEdit = $nuevas;
		}
		
        $this->comunica->reset();
        $comunicaUsuario = new IgepComunicaUsuario($this->comunica,$this->v_preInsercionDatos,$this->v_listas);
        $retorno = $this->postInsertar($comunicaUsuario);

        $errores = $this->obj_errorNegocio->hayError();     
        //Cancelamos la transaccion si hay errores o el return es -1
        if($errores or $retorno==-1){
        	return -1;
        }
        
	}

	//Borrado
	public function processDelete() {

		$m_datosTpl = $this->comunica->getAllTuplas();
		$aborrar = array();
		//IgepDebug::setDebug(DEBUG_IGEP,'processDelete: '.var_export($m_datosTpl,true));		

		// Confirmamos el borrado
		foreach ($m_datosTpl as $filaborrada) {

			//Borramos la tupla del primer conjunto
			$res = $this->existeRegistro($filaborrada);
			if ($res[0]) {
				unset($this->data[$res[3]]);
			}

			//Borramos la tupla del segundo conjunto
			if($this->dataEdit != null) {
				$res = $this->existeRegistro($filaborrada,2);
				if ($res[0]) {
					unset($this->dataEdit[$res[3]]);
				}
			}
		}

        $this->comunica->reset();
        $comunicaUsuario = new IgepComunicaUsuario($this->comunica,$this->v_preInsercionDatos,$this->v_listas);
        $retorno = $this->postBorrar($comunicaUsuario);

        //Comprobación de errores
        $errores = $this->obj_errorNegocio->hayError();
        //Cancelamos la transaccion si hay errores o el return es -1
        if($errores or $retorno==-1)
        	return -1;

		//IgepDebug::setDebug(DEBUG_IGEP,'processDelete: '.var_export($this->data,true));		
		return $retorno;
	}


	//Modificacion
	public function processUpdate() {

		$m_datosTpl = $this->comunica->getAllTuplas();
		//IgepDebug::setDebug(DEBUG_IGEP,'processUpdate: '.var_export($m_datosTpl,true));		

        $retorno = 0;
		// confirmamos la actualizacion
		foreach ($m_datosTpl as $reg) {
			
			if($this->dataEdit == null) {
				$res = $this->existeRegistro($reg);
				if ($res[0])
					$this->data[$res[3]] = $reg;
				else 
					throw new Exception('Registro \''.$res[2].'\' no encontrado para actualizarlo');
			}
			else {
				$res = $this->existeRegistro($reg,2);
				if ($res[0])
					$this->dataEdit[$res[3]] = $reg;
				else 
					throw new Exception('Registro \''.$res[2].'\' no encontrado para actualizarlo');
				
				//Si existe en el primer subconjunto actualizamos
				$res = $this->existeRegistro($reg,1);
				if ($res[0]) {
				
					$data = $this->data[$res[3]];
					foreach($data as $field => $value) {
						if(array_key_exists($field,$reg))
							$data[$field] = $reg[$field];
					}
					$this->data[$res[3]] = $data;
				}
				
			}			
		}
		
		
        //Lanzamos el postModificar si no ha habido errores
		$this->comunica->reset();
        $comunicaUsuario = new IgepComunicaUsuario($this->comunica,$this->v_preInsercionDatos,$this->v_listas);
        $retorno = $this->postModificar($comunicaUsuario);
        
        /*Comprobación de errores*/
        $errores = $this->obj_errorNegocio->hayError();     
        //Cancelamos la transaccion si hay errores o el return es -1
        if($errores or $retorno==-1){
        	return -1;
        }

		//IgepDebug::setDebug(DEBUG_IGEP,'processUpdate: '.var_export($this->data,true));		
		return $retorno;
	}


	/**
	 * Metodo para comprobar si un registro ya existe en array de datos, teniendo en cuenta los campos clave
	 * Devuelve:
	 * @param array contiene tres elementos con:
	 * 	  0: booleano indicando si existe
	 *    1: registro encontrado
	 *    2: campos clave concatenados del registro buscado
	 *    3: clave del registro encontrado
	 */
	private function existeRegistro($registro, $matrixId = 1) {

		if (empty($this->ids))
			throw new Exception('No se ha definido los campos identificadores');
			
		if($matrixId==1)
			$data = $this->getDataForSearch();
		else
			$data = $this->getDataForEdit();
			
		// recorro datos para ver si ya existe
		$clave = array();
		foreach ($this->ids as $campo)
			$clave[] = $registro[$campo];
		$clave_concat = implode('-',$clave);
		
   		foreach ($data as $kreg=>$reg) {
			$found = true;
			foreach ($this->ids as $id) {
				if ($registro[$id] != $reg[$id]) {
					$found = false;
					break;
				}
			}
			if ($found)
				return array(true,$reg,$clave_concat,$kreg);
   		}
   		return array(false,null,$clave_concat,null);
	}
	
	public function postInsertar($objDatos) {
		return 0;
	}
	
	public function postModificar($objDatos) {
		return 0;
	}
	
	public function postBorrar($objDatos) {
		return 0;
	}


	/**
	 * Metodo para fijar los ids de los arrays de datos
	 * 
	 * @param array contiene los nombres de los campos que forma el identificador del registro
	 */
	public function setIdsForData($fieldsForSearch) {

		$this->ids = $fieldsForSearch;
	}	

	/**
	 * Metodo para fijar la matriz de datos con la que trabajará el patrón tras realizar la accion de buscar.
	 * 
	 * @param array contiene la matriz de datos
	 */
	public function setDataForSearch($data) {

		$this->data = $data;
	}

	/**
	 * Metodo que devuelve el contenido de la matriz de datos del modo consulta.
	 * 
	 * @return array contiene la matriz de datos
	 */
	public function getDataForSearch() {
		
		return $this->data;
	}

	/**
	 * Metodo que, para patrones tabular-registro, fija el contenido de la matriz de datos del modo edicion/insercion.
	 * 
	 * @param array contiene la matriz de datos
	 */	
	public function setDataForEdit($data) {

		$this->dataEdit = $data;
	}	

	/**
	 * Metodo que, para patrones tabular-registro, devuelve el contenido de la matriz de datos del modo edicion/insercion.
	 * 
	 * @return array contiene la matriz de datos
	 */	
	public function getDataForEdit() {

		return $this->dataEdit;		
	}	
}
?>