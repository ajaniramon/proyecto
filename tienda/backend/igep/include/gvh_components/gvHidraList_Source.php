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
 * gvHidraList_Source: interfaz que tienen que cumplir las fuentes de datos nuevas creadas por los programadores
 *
 *
 * @version $Id: gvHidraList_Source.php,v 1.2 2010-11-17 16:36:07 afelixf Exp $
 * 
 * @author Toni: <felix_ant@gva.es>
 *
 * @package gvHIDRA
 */ 
interface gvHidraList_Source {


	/**
     * build: Devuelve una matriz de arrays asociativos [valor|descripción]
     *
     * @access public
     * @param array        $dependence Array de valores que que parametrizan el resultado de la lista
     * @return array     $dependenceType Tipo de dependencia 0 -> Fuerte 1-> Débil. Null sin dependencia.
    */ 
    public function build($dependence,$dependenceType);
}

?>