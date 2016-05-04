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
 * typeNIF contiene información relativa a los campos de tipo fecha 
 *
 *
 * @version $Id: typeNIF.php,v 1.6 2010-03-08 18:13:20 afelixf Exp $
 * 
 * @author Toni: <felix_ant@gva.es>
 *
 * @package cit
 */ 
class typeNIF extends gvHidraTypeBase implements gvHidraType
{
    private $validateNIF= TRUE;
    private $validateCIF= FALSE;
    private $validateNIE= FALSE;

    /**
    * constructor
    */
    public function __construct($required=false,$maxLength=null){
    	parent::__construct($required,$maxLength);
    }//Fin de constructor
    
    public function setNIF($value){
    	$this->validateNIF = $value;
    }
    
    public function setCIF($value){
    	$this->validateCIF = $value;
    }
    
    public function setNIE($value){
    	$this->validateNIE = $value;
    }
	
	
    public function validate($value){
		if(parent::validate($value)==0)    		
    		$this->valida_nif_cif_nie($value);
    }

	private function valida_nif_cif_nie($cif) {

		//función creada por David Vidal Serra, Copyleft 2005
        $cif=strtoupper($cif);
        if(empty($cif))
        	return 0;
        //Comprobamos si es incorrecto
        if (!preg_match('/((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)/',$cif)) {throw new Exception('No tiene el formato adecuado.');}
        for ($i=0;$i<9;$i++) {$num[$i]=substr($cif,$i,1);}
        $suma=$num[2]+$num[4]+$num[6];
        for ($i=1;$i<8;$i+=2) {$suma+=substr((2*$num[$i]),0,1)+substr((2*$num[$i]),1,1);}
        $n=10-substr($suma,strlen($suma)-1,1);
        //Si es un CIF
        if($this->validateCIF){
	        if (preg_match('/^[ABCDEFGHNPQS]{1}/',$cif)) {
	                if ($num[8]==chr(64+$n) || $num[8]==substr($n,strlen($n)-1,1)){return 0;} else {throw new Exception('No es un CIF válido.');}}
	        if (preg_match('/^[KLM]{1}/',$cif)) {
	                if ($num[8]==chr(64+$n)) {return 0;} else {throw new Exception('No es un CIF válido.');}}
        }
        //Si es un NIE
        if($this->validateNIE){
        	if (preg_match('/^[TX]{1}/',$cif)) {
                if ($num[8]==substr('TRWAGMYFPDXBNJZSQVHLCKE',substr(preg_replace('/X/','0',$cif),0,8)%23,1) || preg_match('/^[T]{1}[A-Z0-9]{8}$/',$cif)) {return 0;} else {throw new Exception('No es un NIE válido.');}}
        }
        //Si es un NIF
        if($this->validateNIF){
	        if (preg_match('/(^[0-9]{8}[A-Z]{1}$)/',$cif)) {
	                if ($num[8]==substr('TRWAGMYFPDXBNJZSQVHLCKE',substr($cif,0,8)%23,1)) {return 0;} else {throw new Exception('No es un NIF válido.');}}
        }
    	throw new Exception('No tiene el formato adecuado.');
	}
}//Fin clase typeNIF
?>