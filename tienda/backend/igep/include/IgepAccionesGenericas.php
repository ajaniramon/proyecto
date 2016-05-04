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
* Incluimos la clase de Igep que nos permite formatear los datos que nos vienen de un form
*/
include_once "IgepComunicacion.php";

/**
* Clase para aislar el acceso a datos al usuario/desarrollador
*/
include_once "IgepComunicaIU.php";
/**
* Incluimos la clase de Igep que nos permite enmascarar la creación de Mensajes en pantalla
*/
include_once "IgepMensaje.php";

/**
* Incluimos la clase de Igep que incluye métodos de escpado de comillas combinados con Javscript
*/
require_once "IgepSmarty.php";



/**
* IgepAccionesGenericas es una clase que contiene el código que corresponde a las acciones genéricas de IGEP que no necesitan
* definición en el fichero mappings para poderse ejecutar. Es decir, acciones que siempre se podrán realizar en un
* panel sin necesidad de que el programador las especifique en el mappings.
* 
* Estas acciones son las que corresponden a acciones de interfaz, recarga automática de listas y operaciones
* con ventanas de selección.
* 
*<ul>
*<li><b>camposDependientes:</b> Acción privada de Igep que sirve para recalcular las listas dependientes con el nuevo valor seleccionado. Nunca tiene que ser utilizado por los programadores.</li>
*<li><b>abrirVentanaSeleccion:</b> Acción privada de Igep que sirve para abrir una ventana de Selección. Nunca tiene que ser utilizado por los programadores.</li>
*<li><b>buscarVentanaSeleccion:</b> Acción privada de Igep que sirve para buscar en una ventana de Selección. Nunca tiene que ser utilizado por los programadores.</li>
*<li><b>ordenarTabla:</b> Acción privada de Igep que ordena los registros de una tabla por un campo y en un sentido.</li>
*</ul>
* 
*
* @version	$Id: IgepAccionesGenericas.php,v 1.74 2011-01-13 13:21:53 vnavarro Exp $
* 
* @author David: <pascual_dav@externos.gva.es>
* @author Vero: <navarro_ver@externos.gva.es>
* @author Toni: <felix_ant@externos.gva.es>
*
* @package	gvHIDRA
*/ 
class IgepAccionesGenericas extends Action
{
	
	/**
	* variable de error
	*
	* @var object obj_errorNegocio
	*/		
	var $obj_errorNegocio;
		
	/**
	* La instancia de IgepComunicación para comunicarnos con la presentación
	* @access	public
	* @var	object	$comunica
	*/		
	var $comunica;	
		
	/** 
	* Variable que contendrá el posible mensaje a enviar al panel. Tiene que ser de la clase IgepMensaje
	*
	* @var	object	$obj_mensaje 
	*/  	
	var $obj_mensaje;
	
	/**
	* Objeto que permite manejar/registrar el javascript de esta clase
	* @access private
	* @var object igepArbol	
	*/		
	var $obj_IgSmarty;

	/**
	* constructor. Generará a partir de los parámetros que se le pasen una conexión a al base de datos y un
	* array de manejadores de  tablas (una por cada una de las que mantenga el panel hijo).
	*/
	public function __construct() {
		global $g_error;
		if(!isset($g_error)) 
			$g_error = new IgepError();	
		$this->obj_errorNegocio = & $g_error;		
	}

	public function IgepAccionesGenericas() {
		global $g_error;
		if(!isset($g_error)) 
			$g_error = new IgepError();	
		$this->obj_errorNegocio = & $g_error;		
	}

	/**
	* Método que se ejecuta tras el constructor y que permite seleccionar la acción a realizar.
	* En esta clase se encuentran las siguientes acciones genéricas:
	* <ul>
	* <ui>camposDependientes: recalcula listas dependientes y dispara acciones de interfaz.</ui>
	* <ui>abrirVentanaSeleccion: abre la ventana de Selección.</ui>
	* <ui>buscarVentanaSeleccion: realiza la busqueda en la ventana de Selección.</ui>
	* </ul>
	*/
	public function perform($actionMapping, $actionForm) 
	{
		//Recogemos la accion y le quitamos el prefijo que nos viene de la ventana		
		$str_accion = $actionForm->get('action');
		//Debug:Indicamos que entramos en Negocio y la accion a ejecutar
		IgepDebug::setDebug(5,'IgepAccionesGenericas: ejecutamos acción '.$str_accion);	
		//creamos la instancia de IgepComunicacion			
		$this->comunica = new IgepComunicacion(array());				
		switch ($str_accion) {

			case 'gvHautocomplete':
				//Recogemos los valores
				$field = $_GET['field'];
				$value = $_GET['term'];
				$claseManejadora = $_GET['claseManejadora'];
				$objClase = & IgepSession::damePanel($claseManejadora);
				$objClase->regenerarInstancia();
				
				$resultado = null;
				
				//Primero delegamos en el programador
				//$resultado = $objClase->setAutocomplete($field,$value);
				
				//Si no tiene programado nada, miramos si tiene matching
				if($resultado==null) {

					if(isset($objClase->matching[$field])) {
					
						$campoBD = $objClase->matching[$field]['campo'];
						$tablaBD = $objClase->matching[$field]['tabla'];
						
						$query = 'SELECT '.$campoBD.' as "autocomplete" FROM '.$tablaBD.' WHERE upper('.$campoBD.") like '%".strtoupper($value)."%' ORDER BY 1";
						$res = $objClase->consultar($query);
		
						$resultado = array();
						if($res!=-1 and is_array($res)) {
							foreach($res as $row)
								$resultado[] = $row['autocomplete'];
						}
					}
				}
				$objClase->limpiarInstancia();

				$json = json_encode($resultado);
				print $json;
				die;				
				break;

			//Acción Genérica de Igep que lanzan automáticamente los plug-ins cuando se actualiza un campo que tiene otros campos dependientes. Tipicamente las listas.
			case 'gvHrefreshUI':
				$this->comunica->buildDataRefreshUI($actionForm);			
				$resultado = $this->calcularCamposDependientes();
				$actionForward = $actionMapping->get('IgepOperacionOculto');		
				break;
				
			case 'launchSelectionWindow':
				
				$objClase = & IgepSession::damePanel($_REQUEST['claseManejadora']);			
				$selectionWindow = $objClase->v_ventanasSeleccion[$_REQUEST['selectionWindow']];				
				if(is_object($selectionWindow)) {
					$size = $selectionWindow->getWindowSize();
					if (isset($size))
						echo $size['height'].'|'.$size['width'];
					else
						echo '730|450';
				}
				die;
			//Acción Genérica de Igep que se lanza al pulsar al botón que lanza la ventana de selección (el botón de los 3 puntos).
			case 'abrirVentanaSeleccion':
				$this->comunica->construirArrayAbrirVentanaSeleccion($actionForm);			
				$this->abrirVentanaSeleccion();
				$actionForward = $actionMapping->get('IgepOperacionOculto');
				break;
			//Acción Genérica de Igep que se lanza al pulsar al botón de búsqueda en la ventana de selección (la lupa).	
			case 'buscarVentanaSeleccion':
				$this->comunica->construirArrayBuscarVentanaSeleccion($actionForm);			
				$this->buscarVentanaSeleccion();
				$actionForward = $actionMapping->get('IgepOperacionOculto');
				break;
				
			case 'ordenarTabla':
				$this->comunica->construirArrayOrdenarTabla($actionForm);								
				
				$datosOrdenacion = $this->comunica->dameDatos('ordenarTabla');
				$objClase = & IgepSession::damePanel($datosOrdenacion['claseManejadora']);
				$objClase->regenerarInstancia();
				
				//Recuperamos el tipo de la columna de ordenación
				$tipo = $objClase->v_descCamposPanel[$datosOrdenacion['columna']]['tipo'];
				
				//Recogemos los datos a ordenar
				$cursor = $objClase->getResultForSearch();
				$this->ordenarCursor($cursor,$datosOrdenacion['columna'],$tipo,$datosOrdenacion['orden']);
				$objClase->setResultForSearch($cursor);
				
				$actionForward = $actionMapping->get('IgepOperacionOculto');
				break;
				
			case 'IgepSaltoVentana':
			case 'IgepRegresoVentana':
				$nombreClase = $_REQUEST['claseManejadora'];
				$nomForm = $_REQUEST['formActua'];
				$idBtnCompleto = $_REQUEST['idBtn'];
				
				$objClase = & IgepSession::damePanel($nombreClase);//TODO: Quitar &
				$objClase->regenerarInstancia();
				//Creamos el objDatos
				$objClase->comunica = new IgepComunicacion($objClase->v_descCamposPanel);
				$objClase->comunica->data2Arrays();
				$comunicaUsuario = new IgepComunicaUsuario($objClase->comunica, $objClase->v_preInsercionDatos, $objClase->v_listas);

				//Validamos los campos antes de saltar
				$validacionIgep = $objClase->comunica->checkDataTypes();
				if ($validacionIgep!= '0')
				{
					$objClase->showMessage('IGEP-17',array($validacionIgep));
					$actionForward = new ActionForward('gvHidraNoAction');
					$actionForward->put('IGEPclaseManejadora',$nombreClase);
					return $actionForward;
				}

				//Obtenemos el objSalto
				if($str_accion == 'IgepSaltoVentana')
				{
					$salto = new IgepSalto($nombreClase,$_REQUEST['idBotonSalto']);
					$salto->setBtnId($idBtnCompleto);
					$resultadoSalto = $objClase->saltoDeVentana($comunicaUsuario, $salto);
				}
				else 
				{
					$salto = IgepSession::dameSalto();
					$salto->setId($_REQUEST['idBotonSalto']);
					$resultadoSalto = $objClase->regresoAVentana($comunicaUsuario, $salto);
				}
				
				if($resultadoSalto==0)
				{
					//Guardamos datos del salto
					IgepSession::guardaSalto($salto);
					$actionForward = $actionMapping->get('IgepSaltoVentana');
					//Si es una vuelta borramos el panel origen
					if($str_accion == 'IgepRegresoVentana') 
					{  
						if($salto->isModal()) 
						{
							$this->obj_IgSmarty = new IgepSmarty();	
							$salto->js = IgepSmarty::getJsCloseModalWindow();
							$this->obj_IgSmarty->addPreScript($salto->js);
							IgepSession::guardaVariable(IgepSession::GVHIDRA_JUMP,'obj_jsOculto',$this->obj_IgSmarty);
							$actionForward = $actionMapping->get('IgepOperacionOculto');
							
						}   
						else {	 
							IgepSession::borraPanel($nombreClase);
							$actionForward->put('IGEPaccionDestinoSalto',$salto->getDestinoVuelta());
						}
					}
					else
					{
						/************ MODAL *********************/
						//Comprobamos si es un salto modal o no
						//Es un salto
						if($salto->isModal())
						{
							//Si es modal
							$salto->setForm($nomForm);
							$actionForwardSalto = new ActionForward('saltoModal');
							$actionForwardSalto->setPath($salto->getDestinoIda());
							$path = $actionForwardSalto->getPath();
							//Si viene de un salto almacenamos los datos para la accion de retorno
							$returnPath = '';
							$width = '';
							$height = '';
							if(is_object($salto)) {
								$returnPath = $salto->getDestinoVuelta();
								$width = $salto->getWidthModal();
								$height = $salto->getHeightModal();
							}
							
							//Creamos un objeto para la gestion de javascript 
							$this->obj_IgSmarty = new IgepSmarty();	
							$salto->js = IgepSmarty::getJsOpenModalWindow($path,$returnPath,$nomForm,$width,$height);
							$this->obj_IgSmarty->addPreScript($salto->js);
							IgepSession::guardaVariable(IgepSession::GVHIDRA_JUMP,'obj_jsOculto',$this->obj_IgSmarty);
							$actionForward = $actionMapping->get('IgepOperacionOculto');
						}
						else
						{
							$actionForward->put('IGEPaccionDestinoSalto',$salto->getDestinoIda());
						}
						/************ MODAL *********************/
					}
				}  
				else {
					if(is_object($resultadoSalto))
						$actionForward =  $resultadoSalto;
					else {
					
						$actionForward = new ActionForward('gvHidraNoAction');
						$actionForward->put('IGEPclaseManejadora',$nombreClase);
					}
				}		
				break;
			case 'cambiarPanelDetalle':
				$nombreClaseMaestro = $_REQUEST['claseManejadora'];
				$nombreClaseDetalle = $_REQUEST['panelActivo'];
				$objClaseMaestro = & IgepSession::damePanel($nombreClaseMaestro);
				$objClaseMaestro->regenerarInstancia();
				$objClaseMaestro->panelDetalleActivo = $nombreClaseDetalle;
				IgepSession::borraVariable($nombreClaseDetalle,'obj_ultimaConsulta');		
				$m_datos = $objClaseMaestro->getResultForSearch();
				$tupla[0] = $m_datos[$objClaseMaestro->int_filaActual];  
				$res = $objClaseMaestro->buildQueryDetails($tupla);
				$actionForward = $actionMapping->get('IgepOperacionOculto');
				break;

			case 'defaultPrint':

				$nombreClase = $_REQUEST['claseManejadora'];
				$actuaSobre = $_REQUEST['actuaSobre'];
				$titulo = $_REQUEST['titulo'];
				$objClase = & IgepSession::damePanel($nombreClase);
				$objClase->regenerarInstancia();
				
				if(!isset($actuaSobre))
					$actuaSobre = 'tabla';
				
				if($actuaSobre=='ficha')
					$data = $objClase->getResultForEdit();
				else
					$data = $objClase->getResultForSearch();
				
				$num_columnas = count($data[0]);
				$nom_columnas = array_keys($data[0]);
				$total_registros = count($data);
				ob_end_clean();
				ob_start();		

				$html = <<<cabecera
<html>
<head>
<title>Impresión página - $titulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="igep/css/screen.css" type="text/css" media="screen" />
<link rel="stylesheet" href="igep/css/print.css" type="text/css" media="print" />
</head>
cabecera;


				if($actuaSobre=='ficha') {

					$html.= "<body>\n";
					$html.="<div class=\"boton\"><img src=\"igep/images/print.gif\">&nbsp;<a href=\"javascript:print();\">Imprimir</a></div>\n";
					$html.= "<table class=\"left\">\n";
					$html.= "<tr>\n"; 
					
					$html.="<td class=\"titulo\" colspan=\"2\">$titulo</td></tr>\n";
					
					foreach($data as $index => $row) {
						$html.="<!-- Tantas filas como número de tuplas -->\n";
						$html.="<!-- INI -->\n";
						foreach($row as $name => $value) {
						$html.="<tr class=\"linea\">\n";
						$html.="<td class=\"col_titulo_right\">";
							if(empty($name))
								$name = "&nbsp;";
							else
								$name.=': ';
						$html.= $name;
						$html.="</td>";
						$html.="<td class=\"col_left\">";
						if(empty($value))
							$value = "&nbsp;";
						elseif(is_array($value))
							$value = $value['seleccionado'];
						$html.=$value."<br/>";
						$html.="</td>\n";
						$html.="</tr>\n";
						}
						$html.="<tr>";
						$html.="<td class=\"pie\" colspan=\"2\">";
						$html.= "<i>Registro ".(++$index)."</i>";
						$html.="</td>\n";
						$html.="</tr>\n";
						$html.="<!-- FIN -->\n";
					}
					
					//Contador
					$html.="<tr>\n";
					$html.="<td class=\"pie\" colspan=\"$num_columnas\"><i>Total registros: $total_registros</i></td>\n";
					$html.="</tr>\n";
					
					$html.= "</table>\n";
					$html.= "</body>\n";
					$html.= "</html>\n";
				}
				else {

				
					$html.= "<body>\n"; 
					$html.="<div class=\"boton\"><img src=\"igep/images/print.gif\">&nbsp;<a href=\"javascript:print();\">Imprimir</a></div>\n";
					$html.= "<table class=\"center\">\n";
					$html.= "<tr>\n"; 
					
					$html.="<th colspan=\"$num_columnas\" class=\"titulo\">$titulo</th></tr>\n";
					
					$html.="<tr>\n";
					$html.="<!-- Tantas celdas como número de campos -->\n";
					$html.="<!-- INI -->\n";
					foreach($nom_columnas as $col) {
					
						if(empty($col))
							$col = "&nbsp;";
						$html.="<td class=\"col_titulo\">$col</td>\n";
					}
					$html.="<!-- FIN -->\n";
					$html.="</tr>\n";
					
					
					foreach($data as $row) {
						$html.="<!-- Tantas filas como número de tuplas -->\n";
						$html.="<!-- INI -->\n";
						$html.="<tr class=\"linea\">\n";
						foreach($row as $field) {
							if(empty($field))
								$field = "&nbsp;";
							elseif(is_array($field))
								$field = $field['seleccionado'];
							$html.="<td class=\"col\">$field</td>\n";
						}
						$html.="</tr>\n";
						$html.="<!-- FIN -->\n";
					}
					
					//Contador
					$html.="<tr>\n";
					$html.="<td class=\"pie\" colspan=\"$num_columnas\"><i>Total registros: $total_registros</i></td>\n";
					$html.="</tr>\n";
					
					$html.= "</table>\n";
					$html.= "</body>\n";
					$html.= "</html>\n";
				}
				
				print $html;
				die;

				break;

			case 'exportCSV':
				$nombreClase = $_REQUEST['claseManejadora'];
				$actuaSobre = $_REQUEST['actuaSobre'];
				$objClase = & IgepSession::damePanel($nombreClase);
				$objClase->regenerarInstancia();
				
				if(!isset($actuaSobre))
					$actuaSobre = 'tabla';
				
				if($actuaSobre=='ficha')
					$data = $objClase->getResultForEdit();
				else
					$data = $objClase->getResultForSearch();
				
				//Vaciamos la cache del navegador	
				ob_end_clean();
				ob_start();		
				$nombre_fich='listado-'.date('d-m-H-i-s');
				$fecha_hoy=date('d/m/Y');
				//Indicamos en el header que se trata de un csv

				header("Content-Type: application/soffice");
				header('Content-Disposition: inline; filename='.$nombre_fich.'.csv');
				
				//Cabeceras
				$cabeceras = array_keys($data[0]);

				foreach($cabeceras as $col) {
					//Comprobamos si tiene etiqueta para la columna.
					$type = $objClase->getFieldType($col);
					if($type!=null AND $type->getLabel()!='')
						echo utf8_encode($type->getLabel())."\t";
					else
						echo utf8_encode($col)."\t";
				}
				echo PHP_EOL;
				
				foreach($data as $row) {
					foreach($row as $field){						
						//Comprobación de fechas
						if(is_object($field) and method_exists($field,'formatUser')) 
							echo utf8_encode($field->formatUser());
						//Comprobación de listas
						elseif(is_array($field)) {
							$seleccionado = $field['seleccionado'];
							foreach($field['lista'] as $value) {
								if($seleccionado==$value['valor'])
									echo utf8_encode($field['seleccionado'].' '.$value['descripcion']);
							}
						}
						else
							//Quitamos retorno de carro
							echo utf8_encode(str_replace("\r\n",'',$field));
						echo "\t";						
					}	
					echo PHP_EOL;
				}
				//Vaciamos la cache
				ob_end_flush ();
				//Ponemos este die para que corte la ejecución y no redireccione la pagina
				die;
				break;
				
			case 'focusChanged':
				$this->comunica->construirArrayFocusChanged($actionForm);
				$this->focusChanged();
				//Aqui tendremos dos opciones de return... de momento solo hay una
				//una para el error. No se recarga la página
				//otra para cuando todo ha ido bien.		
				$actionForward = $actionMapping->get('IgepOperacionOculto');
				break;
			default:				
				die('Error: La acción '.$str_accion.' no se reconoce.');
				break;
		}//Fin switch
		return $actionForward;
	}// Fin de perform
	
	/*------------------------ METODOS DE LAS ACCIONES ------------------------*/


	/**
	* Método encargado de realizar la recarga dinamica de las listas Dependientes.
	* @access	private
	*/
	public function calcularCamposDependientes() {

		//Creamos un objeto para la gestión de javascript 
		$this->obj_IgSmarty = new IgepSmarty();
		//Recogemos los datos del comunicacion
		$datosCampoDependiente = $this->comunica->dameDatos('camposDependientes');
		//Descomponemos el nombre del campo origen que nos viene con prefijos
		$descCampoOrigen = explode('___', $datosCampoDependiente['origen']);
		//El campo puede tener prefijo (cam__ , ins__) o no (si está en un panel de busqueda). Si tiene prefijo count(descCampoOrigen)>1 sino no	
		if(count($descCampoOrigen)>1)
			$campoOrigen = $descCampoOrigen[1];
		else
			$campoOrigen = $descCampoOrigen[0];
		//Obtenemos los campos destino de la validación
		$destinos = explode(",",$datosCampoDependiente['destino']);
			
		foreach($destinos as $indice => $dest)
			$destinosAdaptados[$indice] = str_replace($campoOrigen,trim($dest),$datosCampoDependiente['origen']);

		if (IgepSession::existePanel($datosCampoDependiente['claseManejadora'])){

			//Hacemos la nueva conexión.
			$objPanel = IgepSession::damePanel($datosCampoDependiente['claseManejadora']);		

			//si necesitamos dsn para regenerar lo obtenemos
			if(method_exists($objPanel,'getDSN'))
				$dsn = $objPanel->getDSN();
	
			$objPanel->regenerarInstancia($dsn);
			$objPanel->comunica = new IgepComunicacion($objPanel->v_descCamposPanel);		
			$objPanel->comunica->data2Arrays();
			$objPanel->comunica->setOperation('visibles');		
			$objPanel->comunica->posicionarEnTupla($datosCampoDependiente['registroActivo']);

			//Validamos los campos antes de saltar
			// TODO: 02-09-2009. Toni: Validacion de datos en acciones de interfaz
			// Este codigo permite validar los datos al entrar en una accion de interfaz.
			// Esta comentado porque provoca efectos no deseados. Bloque la interfaz y no 
			// deja que se ejectuen el resto de acciondes de interfaz.
/*
			$validacionIgep = $objPanel->comunica->checkDataTypes();
			if($validacionIgep!= '0') {				
				$jsMensajeError =IgepSmarty::getJsMensaje(new IgepMensaje('IGEP-17',array($validacionIgep)));
				$this->obj_IgSmarty->addPostScript($jsMensajeError);
			}
			else {
*/
				//Si actualizamos listas, tenemos que recorrer cada uno de ellas para obtener la forma en que se cargan.		
				$this->obj_IgSmarty->addPreScript($objPanel->_recalcularListasDependientes($destinos,$destinosAdaptados));
				//Ahora comprobamos si el campo origen tiene una validación. En este caso ejecutaremos la función de validación correspondiente
				$this->obj_IgSmarty->addPostScript($objPanel->_accionesInterfaz($campoOrigen,$datosCampoDependiente['origen']));
				//Por el paso a PHP5 tenemos que eliminar la referencia del mensaje.
				$objPanel->obj_mensaje=null;
//			}
			$objPanel->limpiarInstancia();
			unset($objPanel);
		}
		//Arreglamos los destinos
		IgepSession::guardaVariable('camposDependientes','formulario',$datosCampoDependiente['formulario']);
		IgepSession::guardaVariable('camposDependientes','origen',$datosCampoDependiente['origen']);
		IgepSession::guardaVariable('camposDependientes','obj_jsOculto',$this->obj_IgSmarty);
		return 0;
	} //Fin de calcularCamposDependientes
  

	/**
	* Método  encargado de realizar las operaciones necesarias para la fase de apertura de una ventana de Selección
	* @access	private
	*/
	public function abrirVentanaSeleccion() {		

		$datosVentana = $this->comunica->dameDatos('abrirVentanaSeleccion');
		$coleccionVentanas = & IgepSession::dameVariable($datosVentana['claseManejadora'],'v_ventanasSeleccion');	
		if(isset($coleccionVentanas[$datosVentana['nomCampo']])){			
			
			$coleccionVentanas[$datosVentana['nomCampo']]->abrirVentanaSeleccion($datosVentana);
			$coleccionVentanas[$datosVentana['nomCampo']]->buscarVentanaSeleccion($datosVentana,$this->obj_errorNegocio);

			return 0;
		}
		else{

			$panelVentanaSeleccion['mensaje'] =  new IgepMensaje('IGEP-19',array($datosVentana['nomCampo'],$datosVentana['claseManejadora']));
			IgepSession::_guardaPanelIgep('ventanaSeleccion',$panelVentanaSeleccion);
			return -1;
		}	
	}

	/**
	* Método encargado de realizar las operaciones necesarias para la fase de busqueda de una ventana de Selección
	* @access	private
	*/
	public function buscarVentanaSeleccion() {	

		$datosVentana = $this->comunica->dameDatos('abrirVentanaSeleccion');	
		$coleccionVentanas = IgepSession::dameVariable($datosVentana['claseManejadora'],'v_ventanasSeleccion');	
		if(isset($coleccionVentanas[$datosVentana['nomCampo']]))
			$coleccionVentanas[$datosVentana['nomCampo']]->buscarVentanaSeleccion($datosVentana);
		return 0;
	}
 
	public function focusChanged() {

		$m_datosFocusChanged  = $this->comunica->dameDatos('focusChanged');
		//Provisional
		$composicionCampos=$m_datosFocusChanged['tipoCampo'].'___'.'campo'.'___'.$m_datosFocusChanged['idPanel'].'_0';		
		$this->obj_IgSmarty = new IgepSmarty();				
		if (IgepSession::existePanel($m_datosFocusChanged['claseManejadora'])){
			//Hacemos la nueva conexión.
			$objPanel = IgepSession::damePanel($m_datosFocusChanged['claseManejadora']);	
			$dsn = $objPanel->getDSN();
			$objPanel->regenerarInstancia($dsn);
			$objPanel->comunica = new IgepComunicacion($objPanel->v_descCamposPanel);			  
			$objPanel->comunica->setOperation('postConsultar');
			//Si es un tres modos o un dos modos
			if(isset($objPanel->obj_ultimaEdicion))			
				$objPanel->comunica->setArrayOperacion($objPanel->obj_ultimaEdicion);
			else
				$objPanel->comunica->setArrayOperacion($objPanel->obj_ultimaConsulta);
			$objPanel->comunica->posicionarEnTupla($m_datosFocusChanged['filaActual']);
			$jsGenerado = $objPanel->_focusChanged($composicionCampos,$m_datosFocusChanged['filaActual'],$m_datosFocusChanged['filaProxima']);		  
			$this->obj_IgSmarty->addPostScript($jsGenerado);
			unset($objPanel);
		}
		//Arreglamos los destinos
		IgepSession::guardaVariable('camposDependientes','formulario',$m_datosFocusChanged['nomForm']);
		IgepSession::guardaVariable('camposDependientes','obj_jsOculto',$this->obj_IgSmarty);
	}
	
	/**
	* Funcion que ordena cursores (vectores de registros)
	* por la clave que se indique por argumento y de forma
	* ascedente o descendente.
	* TODO:
	* Si no se define el tipo en v_descCampos, se puede intentar obtener el tipo
	* a partir del valor de la primera fila
	* Esta funcion solo se llama desde IgepAccionesGenericas donde no se 
	* inicializa el dsn, luego de momento no se pueden obtener fechas ni numeros
	* si no se han declarado
	*/
	private function ordenarCursor(& $cursor, $clave, $tipo, $orden = 'asc') {

		if (count($cursor) < 2)
	  		return;
		//Inicializacion de variables
		$vectorClave = array ();
		$cursorOrdenado = array ();
		
		$es_numero = ($tipo==TIPO_DECIMAL OR $tipo==TIPO_ENTERO);
		$es_fecha = ($tipo==TIPO_FECHA OR $tipo==TIPO_FECHAHORA);

		if ($es_fecha)
			$tipoOrdenacion = SORT_REGULAR;
		elseif ($es_numero)		
			$tipoOrdenacion = SORT_NUMERIC;
		else
			$tipoOrdenacion = SORT_STRING;
		foreach ($cursor as $fila => $valor) {
			$vectorClave[$fila] = $valor[$clave];
		}
		
		if ($tipoOrdenacion == SORT_STRING)
		{
			// Para ordenar sin distinguir entre mayúsculas y minúsculas
			uasort($vectorClave, strcasecmp);
			if (strtolower($orden) != 'asc')
				// Reverse para ordenación descendente
				$vectorClave = array_reverse($vectorClave, true);
		}
		else
		{
			if (strtolower($orden) == 'asc')
				asort($vectorClave, $tipoOrdenacion);
			else
				arsort($vectorClave, $tipoOrdenacion);
		}
		
		foreach ($vectorClave as $fila => $valor) {
			$cursorOrdenado[] = $cursor[$fila];
		}
		$cursor = $cursorOrdenado;
	}	
	
}//Fin clase IgepAccionesGenericas
?>