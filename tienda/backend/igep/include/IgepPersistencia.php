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
* Fichero IgepPersistencia. Contiene la clase del mismo nombre.
*
* @version	$Id: IgepPersistencia.php,v 1.39 2008-06-09 15:23:13 afelixf Exp $
* @author David: <pascual_dav@gva.es> 
* @author Keka: <bermejo_mjo@gva.es>
* @author Vero: <navarro_ver@gva.es>
* @author Raquel: <borjabad_raq@gva.es> 
* @author Toni: <felix_ant@gva.es>
* @package	gvHIDRA
*/

/**
* IgepPersistencia es una clase que corresponde a un manejador de tabla de BD de una
* conexión dada. Permite realizar las operaciones básicas de actualización 
* sobre dicha tabla (inserción, borrado y modificación de tuplas).
* 
* Consta de las siguientes propiedades:
* <ul>
* <li><b>str_tabla</b> - Contiene el nombre de la tabla a la que hace referencia.</li>
* <li><b>obj_conexion</b> - El objeto conexión por el que se ha accedido a la tabla</li>
* <li><b>$obj_errorBD</b> - Referencia al objeto de error global</li>
* </ul>
*
* @version	$Id: IgepPersistencia.php,v 1.39 2008-06-09 15:23:13 afelixf Exp $
* @author David: <pascual_dav@gva.es> 
* @author Keka: <bermejo_mjo@gva.es>
* @author Vero: <navarro_ver@gva.es>
* @author Raquel: <borjabad_raq@gva.es> 
* @author Toni: <felix_ant@gva.es>
* @package	gvHIDRA
*/
class IgepPersistencia {
	
    /**
    * tabla a modificar
    *
    * @var string str_tabla
    */
    var $str_tabla;
      
    /**
    * objeto conexion
    *
    * @var object obj_conexion
    */	
    var $obj_conexion;
      
      	
    /**
    * variable de error
    *
    * @var object obj_errorNegocio
    */		
    var $obj_errorBD;
  	
    /**
    * Constructor
    *
    * @access	public
    * @param	object	$obj_conexion
    * @param	string	$str_tabla
    */
    function IgepPersistencia($obj_conexion, $str_tabla) {		
        //Instanciamos la clase
		global $g_error;
    	$this->obj_errorBD =& $g_error; 
    	$this->obj_conexion = $obj_conexion;		
    	$this->str_tabla = $str_tabla;		
    }

	/**
	* Retorna el nombre de la tabla a la que hace referencia.
	*
	* @access	public
	* @return	string
	*/	
	function getTabla() {
		return $this->str_tabla;
	}
	
	/**
	* Dada una matriz asociativa de datos realiza los INSERTs en la tabla
	* asociada al objeto.
	*
	* @access	public
	* @param	array	$m_datos 	Matriz de vectores asociativos por nombres de columna / valor
	*/		
	function insertar($m_datos)
	{
		$query = '';		
		$numFilas = count($m_datos);		
		if ($numFilas<1)//Si no hay datos... 
		{
			IgepDebug::setDebug(DEBUG_IGEP, 'IgepPersistencia: Se ha intentado ejecutar INSERT con una matriz de datos vacía.');
			return;
		}
		
		//Para cada dato
		$numInsercion = 0;
		foreach ($m_datos as $v_filaDatos)
		{
    		$numInsercion++;
			$query = 'INSERT INTO '.$this->str_tabla;
    		$queryKeys =' (';
    		$queryValues =' VALUES (';
    		foreach ($v_filaDatos as $colName=>$value)
    		{
    			$queryKeys.= $colName.', ';
    			if ( $value=='' || is_null($value) ) $queryValues.= 'null, ';
    			else $queryValues.= "'$value', ";
    		}//Fin for columnas
			$queryKeys = substr_replace ($queryKeys, ') ', -2, 2);
   			$queryValues = substr_replace ($queryValues, ') ', -2, 2);
			$query .=$queryKeys.$queryValues;
			
			//Debug:Indicamos que ejecutamos la consulta
			IgepDebug::setDebug(DEBUG_IGEP,"IgepPersistencia: Ejecutamos INSERT ($numInsercion de $numFilas)");

			$res = $this->obj_conexion->exec($query);
			//REVIEW: David - Utilizar MDB2::isError en lugar de MDB2::isError 
			if (MDB2::isError($res))
			{
				//REVIEW: David - Utilizar las funciones $res->getMessage() y $res->getDebugInfo() en la llamada a setError  
				$this->obj_errorBD->setError('IGEP-1', 'IgepPersistencia.php', "'Insertar ($numInsercion de $numFilas)", $res);
				return;
			}
    	}//Fin for filas		
	}//Fin insertar
	 
    /**
    * Dada una tupla o fila de la tabla realiza el DELETE. La
    * tupla debe ser un array asociativo del modo ["campo"]
    * =>"valor". Esta función recibe la fila a borrar porque
    * realiza una comprobación de consistencia (se borra la 
    * tupla siempre y cuando alguien no la haya modificado).
    *
    * @access	public
    * @param	array	$v_filaDatos
    */				
    function borrar($v_filaDatos)
    {		
		$str_condicion='';
        foreach($v_filaDatos as $prop => $val) {			
			if ($str_condicion!='') 
				$str_condicion.=' AND '; 
			$str_condicion.=$prop;
            if (gettype($val)=="string") { 
                if($val!="")
                    $str_condicion.="='".$val."'";
                else
	               $str_condicion.=" is null";
            }
            else { 
                if($val!="")	
                    $str_condicion.="=".$val;
                else
	               $str_condicion.=" is null";
            }		
        }//FIN foreach
        // Se monta la query con los datos obtenidos
        $consulta = "DELETE FROM ".$this->str_tabla. " WHERE " .$str_condicion;

        //Debug:Indicamos que ejecutamos la consulta
        IgepDebug::setDebug(DEBUG_IGEP,'IgepPersistencia: Borrado - '.$consulta);
    
        $res = $this->obj_conexion->exec($consulta);					
        if (PEAR::isError($res))	 		 
            $this->obj_errorBD->setError("IGEP-2",'IgepPersistencia.php',"borrar",$res,$consulta);
        else {
            //Comprobamos si existe error de concurrencia.
            if ($res==0)
                $this->obj_errorBD->setError('IGEP-4','IgepPersistencia.php','borrar','','Error de Concurrencia: '.$consulta);
		} 	
    }// Fin de borrar


	/**
	* Dada una tupla o fila de la tabla realiza el UPDATE. Recibe
	* dos parámetros; uno es la tupla con los nuevos datos y otro es
	* la tupla con los datos que fueron visualizados.Esto se debe a
	* que realiza una comprobación de consistencia (se actuliza la 
	* tupla siempre y cuando alguien no la haya modificado). Los dos
	* parámetros son arrays asociativos de la forma ["campo"]=>"valor".
	*
	* @access	public
	* @param	array	$m_datos
	*/					
	function actualizar($v_filaDatos, $v_filaDatosAntiguos){		
		$str_set = '';
		foreach($v_filaDatos as $prop => $val) {					
			if($v_filaDatosAntiguos[$prop]!=$val){
				if ($str_set!='')  
					$str_set.=' ,';
				$str_set.=$prop;
				if (gettype($val)=="string") { 
					if($val!="")
						$str_set.="='".$val."'";
					else
						$str_set.="= null";
				}
				else { 				
					if($val!="")
						$str_set.="=".$val;
					else
						$str_set.="= null";
				}
			}//Fin del if que comprueba si los datos son actualizados
		}//FIN foreach
		$str_condicion = '';												
		foreach($v_filaDatosAntiguos as $prop => $val) {					
			if ($str_condicion!='') 
				$str_condicion.=' AND ';
			$str_condicion.=$prop;
			if (gettype($val)=='string') { 
				if($val!='')
					$str_condicion.="='".$val."'";
				else
					$str_condicion.=' is null';
			}
			else { 				
				if($val!='')
					$str_condicion.='='.$val;
				else
					$str_condicion.=' is null';
			}
	    }//FIN foreach								
		// Ejecutamos la sentencia de Actualización
		if ($str_condicion!='' AND $str_set!='') {																								
			$consulta = "UPDATE $this->str_tabla SET ".$str_set.' WHERE '.$str_condicion;      
			//Debug:Indicamos que ejecutamos la consulta
			IgepDebug::setDebug(DEBUG_IGEP,'IgepPersistencia: Actualización - '.$consulta);
			$res =  $this->obj_conexion->exec($consulta);				
			if (PEAR::isError($res))	 		 
				$this->obj_errorBD->setError('IGEP-3','IgepPersistencia.php','actualizar',$res, $consulta);
			else {
				//Comprobamos si existe error de concurrencia.
				if ($res==0)  						
					$this->obj_errorBD->setError('IGEP-4','IgepPersistencia.php','actualizar','','Error de Concurrencia: '.$consulta); 						 						 					
			}
		}//if de condición no vacia 		 		
	}//FIN de actualizar

}//FIN clase IgepPersistencia
?>