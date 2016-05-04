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
 * Creado el 25-may-2006
 *
 * Este fichero contiene las constantes que se definen en gvHIDRA
 *   
 * @version  $Id: IgepConstants.php,v 1.20 2010-06-04 15:08:57 afelixf Exp $ 
 * @author Toni: <felix_ant@gva.es> 
 * @package gvHIDRA
 */

/**
* Define un tipo caracter en gvHidra
*/
define('TIPO_CARACTER','gvHidraString');

/**
* Define un tipo entero en gvHidra
*/
define('TIPO_ENTERO','gvHidraInteger');

/**
* Define un tipo float en gvHidra
*/
define('TIPO_DECIMAL','gvHidraFloat');

/**
* Define un tipo fecha en gvHidra
*/
define('TIPO_FECHA','gvHidraDate');

/**
* Define un tipo fecha-hora en gvHidra
*/
define('TIPO_FECHAHORA','gvHidraDatetime');

/**
 * Define para normalizar los saltos de línea 
 */
define('SALTO_LINEA',chr(13).chr(10));

//IgepDebug
//Tipos de DEBUG
define('PANIC',0);
define('ERROR',1);
define('WARNING',2);
define('NOTICE',3);
define('DEBUG_USER',4);
define('DEBUG_IGEP',5);

//Valores de la variable $g_debug
define('LOG_NONE',0);
define('LOG_ERRORS',2);
define('LOG_AUDIT',4);
define('LOG_ALL',6);

?>
