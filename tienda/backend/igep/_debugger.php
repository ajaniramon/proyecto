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
 * Formulario para visualizar la información del debug
 */

// nos situamos a nivel del proyecto
chdir('..');

// inicialización framework
include_once('igep/include_class.php');
IgepSession::session_start();
include_once ('igep/include_all.php');
$aux = new AppMainWindow();

$configuration = ConfigFramework::getConfig();
$custom = $configuration->getCustomDirName();

 //Inicializamos algunos valores
 if (isset($_REQUEST['db_fecha'])) {
	$valorFecha = $_REQUEST['db_fecha'];
	IgepComunicacion::transform_User2FW($valorFecha,TIPO_FECHAHORA);
 } else
	$valorFecha = new gvHidraTimestamp();

 //autoreload 
 if(isset($_REQUEST['autoreload'])) 
 	$valorAutoreload = 'checked';
 else
 	$valorAutoreload = '';
 	 
 //Comprobamos si ha pulsado alguno de los botones que indican tiempo
if (isset($_REQUEST['Refrescar']))
 switch ($_REQUEST['Refrescar']){
 	case 'Ayer':
 		$valorFecha = new gvHidraTimestamp('-1 day');
 		break;
 	case 'Hoy':
 		$valorFecha = new gvHidraTimestamp('today');
 		break;
 	case '-10m':
 		$valorFecha = new gvHidraTimestamp('-10 minutes');
 		break; 	
 	case '-5m':
 		$valorFecha = new gvHidraTimestamp('-5 minutes');
 		break;
 	case 'Ya!':	
 		$valorFecha = new gvHidraTimestamp('now');
 }

if (is_null($valorFecha))
	echo 'La fecha no es correcta';
 
 if(isset($_REQUEST['db_usuario']))
	$valorUsuario = $_REQUEST['db_usuario'];
 else
	$valorUsuario = '';
 
 if(isset($_REQUEST['db_aplicacion']))
	$valorAplicacion = $_REQUEST['db_aplicacion'];
 else{
	$valorAplicacion = $configuration->getApplicationName();
 }
 
 if(isset($_REQUEST["db_tipo"]))
	$valorTipo = $_REQUEST["db_tipo"];
 else
	$valorTipo = 5; 
 
 //Comprobamos si tenemos que calcular la consulta
 $resultDatos = array(); 
 if ($valorAplicacion!='' and !is_null($valorFecha)){   
	$dsn_log = $configuration->getDSNLog();
	$resultDatos = array();
	if (empty($dsn_log)) {
		echo 'No existe el dsn para el Log';
	} else {
		//$dsn_log['username']='usu';
		$conexion = new IgepConexion($dsn_log);
		$db_pear = $conexion->getPEARConnection();
		if (PEAR::isError($db_pear)) {
			echo 'Error de conexión al debug: '.$db_pear->userinfo;
		} else {
			$fechabd = clone $valorFecha;
			$conexion->prepararOperacion($fechabd, TIPO_FECHAHORA);
			//si hay usuario
			$whereUsuario='';
			if($valorUsuario!='')
				$whereUsuario = "and usuario='$valorUsuario'";
			$select = "SELECT iderror,aplicacion,modulo,version,usuario as \"usuario\",fecha as \"fecha\",tipo as \"tipo\",mensaje as \"mensaje\"
						FROM tcmn_errlog WHERE aplicacion='$valorAplicacion' and fecha>'$fechabd' and tipo<=$valorTipo $whereUsuario
						ORDER BY iderror DESC";
			// quitamos el log del consultar para que no interfiera en la salida
			$log = $configuration->getLogStatus();
			$configuration->setLogStatus(LOG_ERRORS);
			$resultDatos = $conexion->consultar($select, array('DATATYPES'=>array('fecha'=>TIPO_FECHAHORA)));
			$configuration->setLogStatus($log);
			if ($resultDatos==-1) {
				$resultDatos = array();
				$err = $conexion->obj_errorConexion->getDescErrorDB();
				echo 'Error en consulta: '.$err[0];
			}
		}
	}
 }
 $htmlResult = '<html>';
 $htmlResult.= '<head>';
 $htmlResult.= '<title>';
 $htmlResult.= 'Debugger de gvHIDRA';
 $htmlResult.= '</title>'; 
 if (!empty($custom))
 $htmlResult.= "<link rel='stylesheet' href='../custom/$custom/css/bootstrap.css' type='text/css' />";
 $htmlResult.= "<link rel='stylesheet' href='../custom/$custom/css/debugger.css' type='text/css' />";
 $htmlResult.= '</head>'; 
 $htmlResult.= '<body onLoad="javascript:formulario=document.forms[\'Refrescar\'];if(\''.$valorAutoreload.'\'==\'checked\'){setTimeout(\'formulario.submit();\',4000);};">';
 //Para meter los parámetros de la consulta
 $htmlResult.= "<form name='Refrescar' id='Refrescar' method='post' action=''>";
 $htmlResult.= '<div id="debugger" class="main-block">';
 $htmlResult.= '<div class="row text-center" id="titleDebugger">Criterios de filtrado</div><br/>';
 $htmlResult.= '<div class="tabularLineHead">&nbsp;</div>';
 $htmlResult.= '<div class="row" id="panelDebugger">'; 
 $htmlResult.= '<div class="col-md-4" id="aplDebugger">Aplicación: <input class="edit" size="12" type="text" name="db_aplicacion" id="db_aplicacion" value="'.$valorAplicacion.'"/></div>';
 $htmlResult.= '<div class="col-md-4" id="userDebugger">Usuario: <input class="edit" size="12" type="text" name="db_usuario" id="db_usuario" value="'.$valorUsuario.'"/></div>';
 $htmlResult.= '<div class="col-md-4" id="refreshDebugger">';
 $htmlResult.= '<input class="buttonDebugger" type="submit" name="Refrescar"/ value="Refrescar">';
 $htmlResult.= '&nbsp;&nbsp;&nbsp;&nbsp;Auto <input class="edit" id="autoreload" name="autoreload" type="checkbox" '.$valorAutoreload.' value="checked">';
 $htmlResult.= '</div>';
 $htmlResult.= '</div><br/>';
 
 $htmlResult.= '<div class="paramsDebugger row ">';
/* $htmlResult.= '<tr>';
 $htmlResult.= '<td colspan="3" width="85%" class="text tabularTitle">';
 $htmlResult.= '<b>Criterios de filtrado</b>';
 $htmlResult.= '</td>';
 $htmlResult.= '<td width="20%" class="titlePanel">';
 $htmlResult.= '<input class="button" type="submit" name="Refrescar"/ value="Refrescar">';
 $htmlResult.= '&nbsp;&nbsp;Auto <input id="autoreload" name="autoreload" type="checkbox" '.$valorAutoreload.' value="checked">'; 
 
 $htmlResult.= '</td>';
 $htmlResult.= '</tr>';*/

 /*$htmlResult.= '<td width="17%">'; 
 $htmlResult.= '&nbsp;Usuario: <input class="edit" type="text" name="db_usuario" id="db_usuario" value="'.$valorUsuario.'"/>';
 $htmlResult.= '</td>';*/
 $htmlResult.= '<div class="col-md-6" >';
 $htmlResult.= '&nbsp;Fecha: <input class="edit" type="text" name="db_fecha" id="db_fecha" value="'.(is_null($valorFecha)? $_REQUEST['db_fecha']: $valorFecha->formatUser()).'" style=\'padding:0px;\'  size=\'15\'/>';
 $htmlResult.= '&nbsp; <input class="buttonDebugger" type="submit" name="Refrescar"/ value="Ayer">';
 $htmlResult.= '&nbsp;<input class="buttonDebugger" type="submit" name="Refrescar"/ value="Hoy">';
 $htmlResult.= '&nbsp;<input class="buttonDebugger" type="submit" name="Refrescar"/ value="-10m">';
 $htmlResult.= '&nbsp;<input class="buttonDebugger" type="submit" name="Refrescar"/ value="-5m">';
 $htmlResult.= '&nbsp;<input class="buttonDebugger" type="submit" name="Refrescar"/ value="Ya!">';
 $htmlResult.= '</div>';
 $htmlResult.= '<div class="col-md-6">';
 $htmlResult.= '&nbsp; Tipo: <select class="edit" name="db_tipo">';
 
 if($valorTipo==0) 
  $htmlResult.= '<option value="0" selected>PANIC</option>';
 else
  $htmlResult.= '<option value="0">PANIC</option>';
 if($valorTipo==1) 
  $htmlResult.= '<option value="1" selected>ERROR</option>';
 else
  $htmlResult.= '<option value="1">ERROR</option>';
 if($valorTipo==2) 
  $htmlResult.= '<option value="2" selected>WARNING</option>';
 else
  $htmlResult.= '<option value="2">WARNING</option>';
 if($valorTipo==3)
  $htmlResult.= '<option value="3" selected>NOTICE</option>';
 else
  $htmlResult.= '<option value="3">NOTICE</option>';
 if($valorTipo==4)
  $htmlResult.= '<option value="4" selected>DEBUG_USER</option>';
 else
  $htmlResult.= '<option value="4">DEBUG_USER</option>';
 if($valorTipo==5)
  $htmlResult.= '<option value="5" selected>DEBUG_IGEP</option>';
 else
  $htmlResult.= '<option value="5">DEBUG_IGEP</option>';
 $htmlResult.= '</select>';
 $htmlResult.= '</div>'; 
/* $htmlResult.= '<td>';
 $htmlResult.= '&nbsp; Aplicación: <input type="text" name="db_aplicacion" id="db_aplicacion" value="'.$valorAplicacion.'" style=\'padding:0px;\'  size=\'12\'/>'; 
 $htmlResult.= '</td>';*/
 $htmlResult.= '</div>';
 $htmlResult.= '</form>';
 
 //$htmlResult.= '<div class="tabularLineHead">&nbsp;</div>';
 $htmlResult.= '<br>';
 $htmlResult.= '<div class="row bg-warning text-center" id="titleDebugger"><h4>Traza de ejecución</h4></div>';
 $htmlResult.= '<div class="tabularLineHead">&nbsp;</div>';
  $htmlResult.= '<div class="table-responsive">';
 $htmlResult.= '<table class="resultDebugger table table-bordered">';
 $htmlResult.= '<tr>';
 $htmlResult.= '<th class="column" border="1">';
 $htmlResult.= 'Nº';
 $htmlResult.= '</th>';
 $htmlResult.= '<th class="column" border="1" width="15%">';
 $htmlResult.= 'FECHA';
 $htmlResult.= '</th>';
 if(empty($valorUsuario)){
 	$htmlResult.= '<th class="column" border="1">';
 	$htmlResult.= 'USUARIO';
 	$htmlResult.= '</th>';
 } 
 $htmlResult.= '<th class="column" border="1">';
 $htmlResult.= 'TIPO';
 $htmlResult.= '</th>';
 $htmlResult.= '<th class="column" border="1">';
 $htmlResult.= 'MENSAJE';
 $htmlResult.= '</th>';
 $htmlResult.= '</tr>'; 
 //Iteramos para mostrar los resultados
 if(count($resultDatos)==0){
   $htmlResult.= '<tr>';
   if(empty($valorUsuario))
   	$htmlResult.= '<td colspan="5" align="center" border="1">';
   else
   	$htmlResult.= '<td colspan="4" align="center" border="1">';
   $htmlResult.= '<div class="tabularLineHead">&nbsp;</div>';
   $htmlResult.= '<div class="noData">NO HAY DATOS</div>';
   $htmlResult.= '</td>';
   $htmlResult.= '</tr>';
 }
 else{
 	/*
   $htmlResult.= '<tr><td colspan="5" align="center" border="1">';
   $htmlResult.= '<div class="tabularLineHead">&nbsp;</div></td></tr>';
   */
   foreach($resultDatos as $indice=>$tupla){
     $htmlResult.= '<tr>'; 
     $htmlResult.= '<td class="dataColumn">';
     $htmlResult.= ($indice+1);
     $htmlResult.= '</td>';
     $htmlResult.= '<td class="dataColumn">';
     if (!is_null($tupla['fecha']))
     	$htmlResult.= $tupla['fecha']->formatUser();
     $htmlResult.= '</td>';
 	 if(empty($valorUsuario)){
	     $htmlResult.= '<td class="dataColumn">';
	     $htmlResult.= $tupla['usuario'];
	     $htmlResult.= '</td>';     
 	 }
     $htmlResult.= '<td class="dataColumn">';
     switch($tupla['tipo']){
      case 0:
        $htmlResult.= '<font color="#BB000C">PANIC</font>';  
        break;
      case 1:
        $htmlResult.= '<font color="#BB000C">ERROR</font>';
        break;
      case 2:
        $htmlResult.= '<font color="#FFAB3F">WARNING</font>';
        break;
      case 3:
        $htmlResult.= '<font color="#2C658F">NOTICE</font>';
        break;
      case 4:
        $htmlResult.= '<font color="#DFDFDF">DEBUG_USER</font>';
        break;
      case 5:
        $htmlResult.= '<font color="#7A8F2C">DEBUG_IGEP</font>';
        break;    
     }
     $htmlResult.= '</td>';
     $htmlResult.= '<td class="dataColumn">';
     $htmlResult.= $tupla['mensaje'];
     $htmlResult.= '</td>';
     $htmlResult.= '</tr>';
   }
 }
 $htmlResult.= '</table>';
  $htmlResult.= '</div>';
 $htmlResult.= '</div>';
 $htmlResult.= '</body>';
 $htmlResult.= '</html>';
 
 echo $htmlResult; 
?>
