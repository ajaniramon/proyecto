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
* Fichero con todos los includes SOLO DE CLASSES
* 
* package gvHIDRA
*/

include_once('igep/include/IgepConstants.php');
include_once('igep/include/gvHidraErrorHandlers.php');
include_once('igep/ConfigFramework.php');
include_once('igep/include/GVHAutoLoad.php');
$al = GVHAutoLoad::singleton();


include_once('igep/phrame/include.php');


//Clases del proyecto IGEP
include_once('igep/include/IgepDebug.php');
include_once('igep/include/gvh_views/include.php');
include_once('igep/include/gvh_types/include.php');


$al->registerClass('gvHidraSelectionWindow_Source', 'igep/include/gvh_components/gvHidraSelectionWindow_Source.php');
$al->registerClass('gvHidraList_Source', 'igep/include/gvh_components/gvHidraList_Source.php');
$al->registerClass('gvHidraSelectionWindow', 'igep/include/gvh_components/gvHidraSelectionWindow.php');
$al->registerClass('_IgepEstructuraLista', 'igep/include/gvh_components/gvHidraList.php'); // incluida en gvhidraList.php
$al->registerClass('gvHidraList', 'igep/include/gvh_components/gvHidraList.php');
$al->registerClass('gvHidraCheckBox', 'igep/include/gvh_components/gvHidraCheckBox.php');
$al->registerClass('IgepArbol', 'igep/include/IgepArbol.php');
include_once('igep/include/IgepError.php');
include_once('igep/include/IgepSession.php');
include_once('igep/include/igep_utils/include.php');
$al->registerClass('IgepDB', 'igep/include/igep_bd/IgepDB.php');
$al->registerClass('MDB2', 'MDB2.php');
include_once('PEAR.php');

include_once 'igep/include/IgepConexion.php';
include_once 'igep/include/IgepPersistencia.php';
include_once 'igep/include/IgepComunicacion.php';
include_once 'igep/include/IgepComunicaUsuario.php';
include_once 'igep/include/IgepComunicaIU.php';
$al->registerClass('IgepMensaje', 'igep/include/IgepMensaje.php');
require_once 'igep/include/IgepSmarty.php';

include_once 'igep/include/gvh_patterns/include.php';
$al->registerClass('IgepSalto', 'igep/include/IgepSalto.php');
include_once 'igep/include/IgepAccionesGenericas.php';
//Clases de actions del FW
include_once 'igep/actions/gvHidraMainWindow.php';
//Clases del custom
$customDirname = ConfigFramework::getCustomDirName();
include_once ('custom/'.$customDirname.'/include_class.php');

//Relativo a la aplicación que vaya a gastar el framework
include_once('include/include.php');
include_once('include/mappings.php');
?>
