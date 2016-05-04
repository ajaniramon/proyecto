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
 * IgepTransformer es una clase que permite transformar la representación de tipos de datos.  
 * 
 * @version $Id: IgepTransformer.php,v 1.22 2010-01-28 17:32:44 gaspar Exp $
 * @author Toni: <felix_ant@gva.es>
 * @package gvHIDRA
 */
 class IgepTransformer 
 {
    /**
     * @var array Contiene la los tipos que requieren modificación
     */
    var $type_mod = array();
    var $decimal_metadata = array();
    var $character_metadata = array();
    var $date_metadata = array();
    private $transform_errors = array();
    private $validate;

	/**
	 * Constructor
	 * 
	 * @param	bool	indica si antes de transformar tiene que buscar errores en campo origen
	 * 					(normalmente para datos que introduce el usuario)
	 */
	function __construct($validate=false)
	{
		if (!is_bool($validate))
			throw new gvHidraException('El constructor de IgepTransformer espera un booleano');
		$this->validate = $validate;
	}

    /**
    * Indica la conversion a hacer con fechas
    *
    * @access   public
    * @param   string from
    * @param   string to
    * @return  none
    */   
    function setDate($from, $to){
    	// este separador se usa para validacion de campo origen,
    	// aunque los formatos origen y destino sean los mismos
		$this->date_metadata['separatorIn'] = substr($from,1,1);
        $dia = strpos($from,'d');
        if ($dia === FALSE)
        	$dia = strpos($from,'j');
        $this->date_metadata['posDay'] = $dia/2;
        $mes = strpos($from,'m');
        if ($mes === FALSE)
        	$mes = strpos($from,'n');
        $this->date_metadata['posMonth'] = $mes/2;
        $this->date_metadata['posYear'] = strpos($from,'Y')/2;

        if($from!=$to){
    	    $this->type_mod[TIPO_FECHA] = true;
	        $this->type_mod[TIPO_FECHAHORA] = true;
            $this->date_metadata['separatorOut'] = substr($to,1,1); 
            $this->date_metadata['position'] = explode($this->date_metadata['separatorOut'],$to);
		}
    }
    
    /**
    * Indica la conversion a hacer con campos numericos
    *
    * @access   public
    * @param   string from
    * @param   string to
    * @param   string fromGroup
    * @param   string toGroup
    * @return  none
    */   
    function setDecimal($from, $to, $fromGroup='', $toGroup=''){
    	// esto se usa para validacion de campo origen,
    	// aunque los formatos origen y destino sean los mismos
		$this->decimal_metadata['from'] = $from;
		$this->decimal_metadata['fromGroup'] = $fromGroup;
        if ($from != $to or $fromGroup != $toGroup){
            $this->type_mod[TIPO_DECIMAL] = true;
            $this->decimal_metadata['to'] = $to;
            $this->decimal_metadata['toGroup'] = $toGroup;
        }
        if ($from == $fromGroup)
        	throw new gvHidraException('El caracter decimal no puede ser el mismo que el separador de miles (from)');
        if ($to == $toGroup)
        	throw new gvHidraException('El caracter decimal no puede ser el mismo que el separador de miles (to)');
    }

    /**
    * Indica la conversion a hacer con campos cadena
    *
    * @access   public
    * @param   string from
    * @param   string to
    * @return  none
    */   
    function setCharacter($from, $to){
        if($from!=$to){
            $this->type_mod[TIPO_CARACTER] = true;
            array_push($this->character_metadata,array('from'=>$from,'to'=>$to));
        }
    }

//    /**
//    * Indica si hay alguna conversion registrada
//    * Util para optimizar los procesos donde se invoca la conversion para
//    * un conjunto de filas y columnas
//    * NO ADECUADO cuando:
//    * - vamos a mostrar en pantalla, ya que los numeros pueden necesitar completar los decimales
//    * - tampoco cuando venimos de negocio o bd, porque pueden llevar exponente
//    * - tampoco cuando venimos de interfaz, pues requiere validacion
//    * Por estos motivos, desaparece el metodo
//    *
//    * @access   public
//    * @return  boolean
//    */   
//    function existsTransform(){
//    	return true;
//        return ($this->type_mod[TIPO_CARACTER] or
//        		 $this->type_mod[TIPO_DECIMAL] or
//        		 $this->type_mod[TIPO_FECHA] or
//        		 $this->type_mod[TIPO_FECHAHORA]
//        	);
//    }

	/**
	 * Quita exponente a numero de negocio (1e2 -> 100).
	 * Si recibe parametros separadores acepta otros formatos de entrada para numeros
	 * (usado en IgepConexion::transform_BD2User para convertir numeros de bd)
	 * Supuestos:
	 * - El numero no puede empezar por e ('e4' no es valido)
	 * - El separador de grupos no se tiene en cuenta
	 * 
	 * @param string o numero
	 * @param string separador decimal
	 * @param string separador de grupos
	 * @return string
	 */
	function expandExponent($num, $sep_d=null, $sep_g=null) {
		$snum = strval($num);
		if (stripos($snum,'e') > 0) {
			if (is_null($sep_d))
				$n = ConfigFramework::getNumericSeparatorsFW();
			else
				$n = array('DECIMAL'=>$sep_d, 'GROUP'=>$sep_g);
			// number_format NO FUNCIONA BIEN CUANDO TIENE QUE PONER MAS DE 5 CEROS
//			return rtrim(strval(number_format($snum, abs(substr($snum,$i))+2, $n['DECIMAL'], $n['GROUP'])
//							   ),
//					     '0'); 	
			$e = explode('e',strtolower($snum));
			if (strpos($e[0],$n['DECIMAL']) === FALSE)
				$e[0].=$n['DECIMAL'];
			$snum = str_pad($e[0], abs($e[1])+4, '0', ($e[1]>=0? STR_PAD_RIGHT: STR_PAD_LEFT));
			// mover el caracter decimal
			$d = strpos($snum,$n['DECIMAL']);
			if ($e[1]>=0)
				$snum = substr($snum,0,$d).substr($snum,$d+1,$e[1]).$n['DECIMAL'].substr($snum,$d + $e[1] + 1);
			else
				$snum = substr($snum,0,$d + $e[1] ).$n['DECIMAL'].str_replace($n['DECIMAL'],'',substr($snum,$d + $e[1]));
			if (strpos($snum,$n['DECIMAL']) !== false) {
				$snum = trim($snum, '0');
				$snum = rtrim($snum, $n['DECIMAL']);
			}
			if ($snum[0]==$n['DECIMAL'])
				$snum = '0'.$snum;
		}
		return $snum;
	}

    /**
    * Recibe un numero en formato negocio y completa los decimales con 0's'
    * Viene con tipo texto, y sin exponente
    * 
    * @access   public
    * @param   number num
    * @param   number decimales
    * @return  string
    */   
    function decimalPad($num,$decimales){
    	$carn = ConfigFramework::getNumericSeparatorsFW();
        $partes = explode($carn['DECIMAL'],$num);
        $partes[1] = rtrim(@$partes[1], "0");
        if (strlen($partes[1]) > $decimales) {
        	// error, este metodo no es para truncar
        	throw new gvHidraException("El numero $num tiene ".strlen($partes[1])." decimales y no se puede truncar a $decimales");
        }
        return $partes[0].($decimales==0? '': $carn['DECIMAL'].str_pad($partes[1], $decimales, '0'));
    }

    /**
    * Recibe un numero en formato bd y completa los decimales con 0's'
    * Devuelve numero en bd
    *
    * @access   public
    * @param   string num
    * @param   number decimales
    * @return  string
    */   
    function decimalPadDatos($num,$decimales,$dsn){
    	$carn = IgepDB::caracteresNumericos($dsn);
        $partes = explode($carn['DECIMAL'],strval($num));
        $partes[1] = rtrim(@$partes[1], "0");
        if (strlen($partes[1]) > $decimales) {
        	// error, este metodo no es para truncar
        	throw new gvHidraException("El numero $num tiene ".strlen($partes[1])." decimales y no se puede truncar a $decimales");
        }
        return $partes[0].($decimales==0? '': $carn['DECIMAL'].str_pad($partes[1], $decimales, '0'));
    }


    /**
    * Lanza el proceso de conversion sobre el valor que recibe, en funcion del tipo que recibe
    * Las conversiones se hacer siempre con cadenas de texto
    *
    * @access   public
    * @param   string type
    * @param   string value
    * @return  string
    */   
    function process($type,$value){
        if (empty($value))
            return $value;
        switch ($type){
            case TIPO_CARACTER:
				$value = $this->_processCharacter($value);                    
				break;
            case TIPO_DECIMAL:
				$value = $this->_processDecimal($value);
				break;
            case TIPO_FECHA:
                $value = $this->_processDate($value);
				break;                
            case TIPO_FECHAHORA:
				$value = $this->_processDateTime($value);
				break;
			default:
				if (!is_string($type))
					throw new gvHidraException('Tipo incorrecto en IgepTransformer: '.var_export($type,true));
				if (!class_exists($type))
					throw new gvHidraException('Tipo no soportado en IgepTransformer: '.$type);
				if (!in_array('gvHidraTypeBase', class_parents($type)))
					throw new gvHidraException('Tipo predefinido no hereda de gvHidraTypeBase: '.$type);
        }
        return $value;
    }

	/**
	 * Comprueba los numeros de una fecha
	 * 
	 * @return boolean true si es valida
	 */
	private function isDate($y, $m, $d) {
		return (is_numeric($y) and is_numeric($m) and is_numeric($d) 
				and checkDate($m, $d, $y) and $y<=9999);
	}

    /**
    * Convierte fechas
    * Devuelve false si hay errores.
    *
    * @access   private
    * @param   string data
    * @return  string
    */   
    private function _processDate($data){
        //Preparamos datos para el caso de las fechas
        if ($this->validate and empty($this->date_metadata['separatorIn'])) {
        	throw new gvHidraException("Error de uso del transformer: se intenta transformar una fecha sin llamar previamente a setDate");
        }
        $timeless = explode(' ',$data);
		if ($this->validate or isset($this->type_mod[TIPO_FECHA])) {
			// prepara el valor origen, bien para validar o para transformar
	        $hay_error = false;
	        if (count($timeless) > 1) {
	        	$this->addError("El campo '$data' no es una fecha sin hora válida.");
	        	$hay_error = true;
	        	//return false;
	        }
			$d=$this->date_metadata['posDay'];
			$j=$d;
			$m=$this->date_metadata['posMonth'];
	        $n=$m;
			$Y=$this->date_metadata['posYear'];
	        $date = explode($this->date_metadata['separatorIn'], $timeless[0]);
		    if (!$hay_error and (count($date) != 3 or !$this->isDate($date[$Y], $date[$n], $date[$j]) ) ) {
	        	$this->addError("El campo '$data' no es una fecha válida.");
	        	$hay_error = true;
	        	//return false;
		    }
		}
        if (isset($this->type_mod[TIPO_FECHA])) {
            // en fechas convertidas siempre rellenamos dia y mes con ceros
            if (strlen($date[$j]) < 2)
            	$date[$j] = str_pad($date[$j], 2, "0", STR_PAD_LEFT);
            if (strlen(@$date[$n]) < 2)
            	$date[$n] = str_pad(@$date[$n], 2, "0", STR_PAD_LEFT);
			$position = $this->date_metadata['position'];
            return @$date[$$position[0]].$this->date_metadata['separatorOut'].
                   $date[$$position[1]].$this->date_metadata['separatorOut'].
                   @$date[$$position[2]];
        } else
			return $timeless[0]; 
    } 

   /**
    * Convierte fechas con hora
    * Devuelve false si hay errores.
    *
    * @access   private
    * @param   string data
    * @return  string
    */   
    private function _processDateTime($data){
        //Preparamos datos para el caso de las fechas
        if ($this->validate and empty($this->date_metadata['separatorIn'])) {
        	throw new gvHidraException("Error de uso del transformer: se intenta transformar una fecha-hora sin llamar previamente a setDate");
        }
        if ($this->validate or isset($this->type_mod[TIPO_FECHAHORA])) {
			// prepara el valor origen, bien para validar o para transformar
	        $d=$this->date_metadata['posDay'];
	        $j=$d;
	        $m=$this->date_metadata['posMonth'];
	        $n=$m;
	        $Y=$this->date_metadata['posYear'];
	        $position = $this->date_metadata['position'];
	        $dateTime = explode(' ',$data);
	        $date = explode($this->date_metadata['separatorIn'],$dateTime[0]);
		    if (count($date) != 3 or !$this->isDate($date[$Y], $date[$n], $date[$j]) ) {
	        	$this->addError("El campo '$data' no es una fecha válida.");
	        	//return false;
			} elseif (!empty($dateTime[1])){
				$h = explode(':',$dateTime[1]);
				if (count($h)<2 or
				   !is_numeric($h[0]) or $h[0]<0 or $h[0]>23 or
				   !is_numeric($h[1]) or $h[1]<0 or $h[1]>59 or
				   (isset($h[2]) and (!is_numeric($h[2]) or $h[2]<0 or $h[2]>59))) {
					$this->addError("El campo '$data' no tiene una hora válida.");
					//return false;
				}
			}
        }
		if (isset($this->type_mod[TIPO_FECHAHORA])) {
			// en fechas convertidas siempre rellenamos dia y mes con ceros
			if (strlen($date[$j]) < 2)
				$date[$j] = str_pad($date[$j], 2, "0", STR_PAD_LEFT);
	        if (strlen(@$date[$n]) < 2)
	        	$date[$n] = str_pad(@$date[$n], 2, "0", STR_PAD_LEFT);
	        return @$date[$$position[0]].$this->date_metadata['separatorOut'].
	                $date[$$position[1]].$this->date_metadata['separatorOut'].
	                @$date[$$position[2]].(!empty($dateTime[1])? ' '.$dateTime[1]: '');
		} else
			return $data;
    }

    /**
    * Convierte numeros.
    * El numero de decimales no se modifica, luego ha de recibir el numero con la cantidad deseada,
    * aunque sea ,00
    * Devuelve false si hay errores.
    *
    * @access   private
    * @param   string data
    * @return  string
    */   
    private function _processDecimal($data){
        if ($this->validate and empty($this->decimal_metadata['from'])) {
        	throw new gvHidraException("Error de uso del transformer: se intenta transformar un numero sin llamar previamente a setDecimal");
        }
        if ($this->validate or isset($this->type_mod[TIPO_DECIMAL])) {
			// prepara el valor origen, bien para validar o para transformar
	    	$data_orig = $data;
			$tmp_dec = '__dec__';
			$posSeparator = strrpos($data, $this->decimal_metadata['from']);
			$num_dec = 0;
			// si hay caracter decimal, lo reemplazo temporalmente con uno especial
			// que no pueda provocar ambiguedad con el separador de grupos
			if ($posSeparator !== false) {
				$num_dec = strlen($data) - $posSeparator -1;
				$data = substr_replace($data,$tmp_dec,$posSeparator,1);
			}
			// se eliminan los grupos, si los hay
			if (!empty($this->decimal_metadata['fromGroup']))
				$data = str_replace($this->decimal_metadata['fromGroup'], '', $data);
			// si habia separador decimal pongo el que maneja php (negocio)
			if ($posSeparator !== false) {
				$car = ConfigFramework::getNumericSeparatorsFW();
				$data = str_replace($tmp_dec, $car['DECIMAL'], $data);
			}
			if (!is_numeric($data)) {
				$this->addError("El valor '$data_orig' no es un número válido.");
				//return false;
			}
        }
		if (isset($this->type_mod[TIPO_DECIMAL]))
			return number_format(floatval($data), $num_dec, $this->decimal_metadata['to'], $this->decimal_metadata['toGroup']);
		else
			return $data;
    }

    /**
    * Convierte cadenas
    *
    * @access   private
    * @param   string data
    * @return  string
    */   
    private function _processCharacter($data){
		if (isset($this->type_mod[TIPO_CARACTER])) {
	        foreach($this->character_metadata as $change){
	            $data = str_replace($change['from'],$change['to'],$data);
			}
		}
        return $data;
    }

	/**
	 * Registra un error de transformacion
	 * 
	 * @param string msg texto del error a mostrar al usuario, cuando corresponda
	 * 
	 */
	private function addError($msg)
	{
		$this->transform_errors[] = $msg;
	}

	/**
	 * Devuelve la lista de errores de transformacion
	 * 
	 * @return mixed
	 * 
	 */
	public function getTransformErrors()
	{
		return $this->transform_errors;
	}

}

?>