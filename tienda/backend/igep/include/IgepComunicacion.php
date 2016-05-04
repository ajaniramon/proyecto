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
 * IgepComunicacion es una clase que se encarga de recoger los datos que vienen de la presentación a través de
 * POST o GET del formulario que ha realizado el submit. Estos datos se almacenan en varios repositorios dependiendo
 * de la operación a la que se vayan a destinar (inserción, actualización o borrado). Posteriormente, las clases interesadas 
 * podrán recuperar estos datos por los métodos habilitados a tal efecto.
 * 
 * La clase proporciona dos tipos de acceso a la información: por cursor o por matriz. El primero de ellos devuelve una
 * tupla por cada una de las solicitudes del programador. El segundo devuelve la matriz completa con todas las tuplas que
 * intervienen en la
 *  operación.
 * 
 * Toda esta información vendrá en un formato adaptado para el programador, es decir los nombres de los campos de la matriz
 * serán los que corresponden a los componentes de la Tpl. De este modo el acceso a la información se realizará de una 
 * forma uniforme para todos los métodos del programador.
 * 
 * La información a la que puede tener acceso el programador corresponde a las siguientes operaciones básicas:
 * <ul>
 * <ui>insertar</ui>
 * <ui>borrar</ui>
 * <ui>actualizar</ui>
 * <ui>seleccionar</ui>
 * <ui>borrar</ui>
 * <ui>visibles</ui>
 * </ul> 
 *
 * @version $Id: IgepComunicacion.php,v 1.156 2011-02-21 09:45:03 afelixf Exp $
 * @author David: <pascual_dav@gva.es> 
 * @author Keka: <bermejo_mjo@gva.es>
 * @author Vero: <navarro_ver@gva.es>
 * @author Raquel: <borjabad_raq@gva.es> 
 * @author Toni: <felix_ant@gva.es>
 * @package gvHIDRA
 */

class IgepComunicacion {
    
    /**
    * matriz de datos relativos a los campos Dependientes o Calculados
    *
    * @var array m_datosCamposDependientes
    */
    var $m_datosCamposDependientes;
    
    
    /**
    * Entero referencia a la fila actual
    *
    * @var integer int_filaSeleccionada
    */
    var $int_filaSeleccionada;
    
    /**
    * matriz de datos relativos a la ventana de Seleccion
    *
    * @var array m_datosVentanaSeleccion
    */
    var $m_datosVentanaSeleccion;
    
    /**
    * matriz de datos visibles en el panel. Es decir que capturamos todo lo que se vea independientemente de si se les ha practicado algun cambio o no.
    *
    * @var array m_datosVisbles
    */
    var $m_datos_visiblesTpl;

    /**
    * Array de definición de los campos que aparecen en un panel
    *
    * @var array desCampoPanel
    */
    var $descCampoPanel;
    
    /**
    * Array que contiene un conjunto de punteros que recrean los datos insertados en la tpl
    *
    * @var array m_datos_insertarTpl
    */
    var $m_datos_insertarTpl;
    
    /**
    * Array que contiene un conjunto de punteros que recrean los datos modificados en la tpl
    *
    * @var array m_datos_actualizarTpl
    */
    var $m_datos_actualizarTpl;
    
    /**
    * Array que contiene un conjunto de punteros que recrean los datos borrados en la tpl
    *
    * @var array m_datos_borrarTpl
    */
    var $m_datos_borrarTpl;
    
    /**
    * Array que contiene un conjunto de punteros que recrean los datos a ser editados en la tpl
    *
    * @var array m_datos_seleccionarTpl
    */
    var $m_datos_seleccionarTpl;
    
    /**
    * Array que contiene un conjunto de punteros que recrean los datos a ser editados en la tpl
    *
    * @var array m_datos_postConsultarTpl
    */
    var $m_datos_postConsultarTpl;
    
    /**
    * Array que contiene un conjunto de punteros que recrean los datos seleccionados del padre
    * en el método recargar. Se gasta para almacenar la tupla seleccionada por el padre para que
    * el programador tenga acceso a esta información.
    *
    * @var array m_datos_seleccionarPadreTpl
    */
    var $m_datos_seleccionarPadreTpl;
    
    /**
    * Array que contiene los datos del panel de busqueda en el formato TPL
    *
    * @var array m_datos_buscarTpl
    */
    var $m_datos_buscarTpl;
      
    /**
    * Array que contiene los datos de los ficheros que se han subido por Upload
    *
    * @var array m_datosFicherosUpLoad
    */
    var $m_datosFicherosUpLoad;

	var $m_datos_iniciarVentanaTpl;
	      
    /**
    * Objeto que contiene los destinos de posibles de la acción.
    *
    * @var array $_actionMapping
    */
    private $_actionMapping;

	/**
	 * variable para acumular los errores de transformacion en data2Arrays y leerlos en checkDataTypes
	 */
	private $_transformErrors;

    /**
    * Constructor. Recibe como parámetro el matching con el que trabajará el objeto   
    *
    * @access  public
    * @param array $matching
    */
    function IgepComunicacion($descCampoPanel = "") {
        $this->descCampoPanel = $descCampoPanel;
        $this->m_auxIndices_insertar = array ();
        $this->m_auxIndices_borrar = array ();
        $this->m_auxIndices_actualizar = array ();
        $this->m_auxIndices_seleccionar = array ();
    }
    /**************************************FUNCIONES DE CONSTRUCCIÓN**************************************/

	/**
	* Recupera los datos de pantalla y los almacena en estructuras conocidas por el Framework.
	* Esto permite un acceso coherente a los datos por parte del programador y del propio sistema.
	*
	* @access	public
	* @return	none
	*/	
    function data2Arrays() {
        /*La estructura de los arrays es la siguiente: 
          [Tabla] =>
            [fila] =>
              [campo1]=> valor, [campo2]=> valor
        */
        $aux_iAnterior = -1;
        //Como la forma de seleccionar cambia de un Ficha a un Tabla, tenemos q comprobar de donde viene
        $filaActual = null;
        if(isset($_REQUEST['claseManejadora'])){
        	$claseManejadora = $_REQUEST['claseManejadora'];
        	//Si la variable pagActual tiene valor entonces se trata de un Ficha.
        	if (isset ($_REQUEST['pagActual___'.$claseManejadora]))
        		$filaActual = $_REQUEST['pagActual___'.$claseManejadora];
        }
        $this->_limpiarRequest();
        $this->_transformErrors = array();
        foreach ($_REQUEST as $prop => $val) {
        	$errs_tr = null;
            switch (substr($prop, 0, 3)) {
                //Para los campos insertados
                case 'lin':
                    //Comprobamos si existe el campo ins, si no existe este es el valor bueno.
                    $campoReal = substr($prop,1);
                    if(array_key_exists($campoReal,$_REQUEST))
                        break;
                    $_REQUEST[$campoReal]=$val;
                    $prop = $campoReal;
                case 'hin' : //Para los checks insertados
                case 'ins' :
                    //Para extraer el indice de la matriz
                    $i = $this->_getPosicionRegistroPantalla($prop);
                    $datos = explode('___', $prop);
                    $datos2 = explode('_', $datos[2]);
                    $estado = 'est_'.$datos2[0].'_'.$i;
					//Transformamos los datos
        			$this->transform_User2FW($val,@$this->descCampoPanel[$datos[1]]['tipo']);
                    if ($_REQUEST[$estado] == 'insertada') {
                        if ($val != '') {
                            //m_datos_insertar
							$this->m_datos_insertarTpl[$i][$datos[1]] = $val;
                            if ($aux_iAnterior != $i)
                                array_push($this->m_auxIndices_insertar, $i);
                            $aux_iAnterior = $i;
                            //Para almacenar la fila actual
                            $this->int_filaActual = $i;
                            //Vamos acumularlos como datos Visibles
                            $this->m_datos_visiblesTpl[$i][$datos[1]] = $val;
                        }
                        else {
                    		//Para controlar el tema de los obligatorios en estos casos lo introducimos para que salte el error
                    		if(@$this->descCampoPanel[$datos[1]]['required'])
                    			$this->m_datos_insertarTpl[$i][$datos[1]] = $val;	    	
                        }
                    }

                    break;
                //para el resto de campos
                case 'lca':
                    //Comprobamos si existe el campo ins, si no existe este es el valor bueno.
                    $campoReal = substr($prop,1);
                    if(array_key_exists($campoReal,$_REQUEST))
                        break;
                    $_REQUEST[$campoReal]=$val;
                    $prop = $campoReal;
                case 'cam' :
                    /*Montamos las operaciones de borrado y actualización*/
                    //Para extraer el indice de la matriz
                    $j = $this->_getPosicionRegistroPantalla($prop);
                    //cogemos el nombre del campo q contiene el valor antiguo                    
                    $campo_antiguo = 'ant'.substr($prop,3);                    
                    $datos = explode('___', $prop);
                    //Transformamos los datos
            		$this->transform_User2FW($val,@$this->descCampoPanel[$datos[1]]['tipo']);
            		// guardo errores para que no se machaquen con las llamadas posteriores
            		$errs_tr = ConfigFramework::getTransformErrors();
                    $datos2 = explode('_', $datos[2]);
                    $estado = 'est_'.$datos2[0].'_'.$j;
                    $check = 'check_'.$datos2[0].'_'.$j;
                    //Comprobación de vacios
                    switch ($_REQUEST[$estado]) {
                        case 'borrada' :
							//Valor anterior
							$valueAnterior = $_REQUEST[$campo_antiguo];
							$this->transform_User2FW($valueAnterior,@$this->descCampoPanel[$datos[1]]['tipo']);
                            //m_datos_borrar
                            //m_datos_antiguos
                            $this->m_datos_borrarTpl[$j][$datos[1]] = $val;
                            $this->m_datos_antiguosTpl[$j][$datos[1]] = $valueAnterior;
                            if ($aux_iAnterior != $j)
                                array_push($this->m_auxIndices_borrar, $j);
                            $aux_iAnterior = $j;
                            //Para almacenar la fila actual
                            if (isset($_REQUEST[$check]) or $filaActual == $j)
                                $this->int_filaActual = 0;
                            break;
                        case 'modificada' :
							//Valor anterior
							$valueAnterior = $_REQUEST[$campo_antiguo];
							$this->transform_User2FW($valueAnterior,@$this->descCampoPanel[$datos[1]]['tipo']);
                            //m_datos_actualizar
                            //m_datos_antiguos
                            $this->m_datos_actualizarTpl[$j][$datos[1]] = $val;
							$this->m_datos_antiguosTpl[$j][$datos[1]] = $valueAnterior;

                            if ($aux_iAnterior != $j)
                                array_push($this->m_auxIndices_actualizar, $j);
                            $aux_iAnterior = $j;
                            //Para almacenar la fila actual
                            if (isset($_REQUEST[$check]) or $filaActual == $j)
                                $this->int_filaActual = $j;
                            break;
                        case 'nada' :
                            if (isset($_REQUEST[$check]) or ($filaActual == $j)) {
								//m_datos_seleccionar
								$this->m_datos_seleccionarTpl[$j][$datos[1]] = $val;
                                if ($aux_iAnterior != $j)
                                    array_push($this->m_auxIndices_seleccionar, $j);
                            }
                            $aux_iAnterior = $j;
                            break;
                        default :
                            break;
                    } //Fin switch
                    //Vamos acumularlos como datos Visibles
                    $this->m_datos_visiblesTpl[$j][$datos[1]] = $val;
                    break;
                case 'ant' :
                case 'est' :
                    break;
                default :
                    //Estos son los campos que estan fuera del CWFicha
                    //Transformamos los datos
            		$this->transform_User2FW($val,@$this->descCampoPanel[$prop]['tipo']);
                    $this->m_datos_externalTpl[0][$prop] = $val;
                    break;
            } //Fin de switch
            
            // si no se ha llamado a getTransformErrors (en cam), lo hago ahora
            if (is_null($errs_tr)) 
	            $errs_tr = ConfigFramework::getTransformErrors();
            if (!empty($errs_tr))
				$this->_transformErrors = array_merge($this->_transformErrors, $errs_tr);
        } //fin del foreach
        //Para los ficheros UpLoad
        foreach($_FILES as $prop => $val){
            $i = $this->_getPosicionRegistroPantalla($prop);
            $datos = explode('___', $prop);
            $datos2 = explode('_', $datos[2]);
            $estado = 'est_'.$datos2[0].'_'.$i;
            if (($_REQUEST[$estado] == 'insertada') or ($_REQUEST[$estado] == 'modificada'))
                $this->m_datosFicherosUpLoad[$i][$datos[1]] = $val;
        }
        $this->reset();
    } //Fin de data2Arrays

    /**
    * Construye únicamente el array de Seleccionados. En este caso es importante tener en cuenta si se venimos de un panel
    * Ficha o Tabla. Esto se debe a que la forma de marcar la fila seleccionada es diferente de uno a otro (la tabla permite los checks).
    *
    * @access public
    * @return integer
    */
    function construirArraySeleccionar() {
        $aux_iAnterior = -1;
        //Como la forma de seleccionar cambia de un Ficha a un Tabla, tenemos q comprobar de donde viene
        $claseManejadora = $_REQUEST['claseManejadora'];
        //Si la variable pagActual tiene valor entonces se trata de un Ficha.
        if (isset ($_REQUEST['pagActual___'.$claseManejadora]))
            $filaActual = $_REQUEST['pagActual___'.$claseManejadora];
        else
        	$filaActual = -1;
        foreach ($_REQUEST as $prop => $val) {
            /*Montamos el seleccionar*/
            if (substr($prop, 0, 3) == 'cam' OR substr($prop, 0, 3) == 'lca') {
                //de momento no dejamos que sean vacios
                //Para extraer el indice de la matriz
                $j = $this->_getPosicionRegistroPantalla($prop);
                $datos = explode('___', $prop);
				//Transformamos los datos
				$this->transform_User2FW($val,@$this->descCampoPanel[$datos[1]]['tipo']);
				//Creamos el estado
                $datos2 = explode('_', $datos[2]);
                $estado = 'est_'.$datos2[0].'_'.$j;
                $check = 'check_'.$datos2[0].'_'.$j;                
                if ((($_REQUEST[$estado] == 'nada') and (isset($_REQUEST[$check]))) or ($filaActual == $j)) {
					$this->m_datos_seleccionarTpl[$j][$datos[1]] = $val;
                    if ($aux_iAnterior != $j)
                        array_push($this->m_auxIndices_seleccionar, $j);          
                    $aux_iAnterior = $j;
                    //Para almacenar la fila seleccionada
                    $this->int_filaActual = $j;
                }
            }
        } //fin del foreach
        //PARCHE: Tenemos que guardarnos el nombre del check porq luego no lo sabemos
        //Nos guardamos la estructura de los checks para poder activarlos desde negocio
        $this->nombreCheckTabla = 'check_'.$datos2[0].'_';    
        return 0;
    } //Fin de ConstruirArraysSeleccionar

    /**
    * Construye el array de datos a Borrar y el de datos Antiguos para realizar las operaciones de DELETE correspondientes.
    *
    * @access public
    * @return integer
    */
    function construirArrayBorrar() {
        $aux_iAnterior = -1;
        foreach ($_REQUEST as $prop => $val) {
            /*Montamos el borrar*/
            if (substr($prop, 0, 3) == 'cam') {
                //cogemos el nombre del campo q contiene el valor antiguo
                $campo_antiguo = 'ant'.substr($prop,3);
                //Para extraer el indice de la matriz
                $j = $this->_getPosicionRegistroPantalla($prop);
                $datos = explode("___", $prop);
				//Transformamos los datos
				$this->transform_User2FW($val,$this->descCampoPanel[$datos[1]]['tipo']);
				//Valor anterior
				$valueAnterior = $_REQUEST[$campo_antiguo];
				$this->transform_User2FW($valueAnterior,$this->descCampoPanel[$datos[1]]['tipo']);
                $datos2 = explode('_', $datos[2]);
                $estado = 'est_'.$datos2[0].'_'.$j;
                if (($_REQUEST[$estado] == 'borrada')) {
					$this->m_datos_borrarTpl[$j][$datos[1]] = $val;
                    $this->m_datos_antiguosTpl[$j][$datos[1]] = $valueAnterior;
                    if ($aux_iAnterior != $j)
                        array_push($this->m_auxIndices_borrar, $j);
                    $aux_iAnterior = $j;
                }
            }
        } //fin del foreach
        return 0;
    } //Fin de ConstruirArraysBorrar

    /**
    * método privado que utilizamos para quitar del REQUEST las variables internas de IGEP
    * @access  private
    */
    function _limpiarRequest() {
        unset ($_REQUEST['modActv']);
        unset ($_REQUEST['action']);
        unset ($_REQUEST[session_name()]);
        unset ($_REQUEST['claseManejadora']);
        unset ($_REQUEST['TreeMenuBranchStatus']);
    }

    function _getPosicionRegistroPantalla($nombreCampoEnTpl) {
        //Hay que utilizar los explode porque un campo del panel Fil puede tener un _ en el nombre.
        $partesCampo = explode("___", $nombreCampoEnTpl);
        if (count($partesCampo) == 3) {        
            $subCampo = explode("_", $partesCampo[2]);
            $registro = $subCampo[1];
            //En el caso de las listas multiples tenemos que eliminar el sufijo []
            if(substr($registro,-2)=='[]')
            	$registro = substr($registro,0,-2);            
        } 
        else
            //Es un campo de filtro
            $registro = 0;
        return ($registro);
    }

    public function construirArrayBuscar() {
        //Limpiamos el REQUEST.
        $this->_limpiarRequest();
        $this->_transformErrors = array();
        
		if(count($_REQUEST)>0) {
	        foreach ($_REQUEST as $prop => $val) {
	
				//Puede darse el caso que sea una lista no editable:
				//En ese caso el valor de la propiedad viene con un l antepuesto. Borramos el primer caracter y comprobamos con el descCamposPanel si existe
				//Como lista
				$lista = substr($prop,1);
				if(isset($this->descCampoPanel[$lista]['size']) and @$this->descCampoPanel[$lista]['radio']==false) {
					//Si no tien valor y el valor l si, copiamos el valor l.
					if($val!='' and $_REQUEST[$lista]=='')
						$prop = $lista;
				}
	
	            //Transformamos los datos
	            $this->transform_User2FW($val,@$this->descCampoPanel[$prop]['tipo']);
	            $errs_tr = ConfigFramework::getTransformErrors();
				
				if (!empty($errs_tr))
					$this->_transformErrors = array_merge($this->_transformErrors, $errs_tr);
	            
	            $this->m_datos_buscarTpl[0][$prop] = $val;
	        }
		}
        return 0;
    }




	/**
	 * Carga los datos al iniciar la ventana. No tiene m_datos normal porque ningún campo puede tener matching
	 */
    function construirArrayIniciarVentana() {
        //Limpiamos el REQUEST.
        $this->_limpiarRequest();
        $this->m_datos_iniciarVentanaTpl = null;
        foreach ($_REQUEST as $prop => $val) {
        	$this->m_datos_iniciarVentanaTpl[0][$prop] = $val;
        }
        return 0;
    }

    /**
    * Construye el array especial para los campos Dependientes. Esta función hace uso de los métodos proporcionados por phrame.
    *
    * @access private  
    */
    
    function buildDataRefreshUI($actionForm) {
        $this->m_datosCamposDependientes['formulario'] = $actionForm->get('gvHfname');
        $this->m_datosCamposDependientes['origen'] = $actionForm->get('gvHfrom');
        $this->m_datosCamposDependientes['destino'] = $actionForm->get('gvHtarget');
        $this->m_datosCamposDependientes['valor'] = $actionForm->get('gvHvalue');
        $this->m_datosCamposDependientes['claseManejadora'] = $actionForm->get('gvHclass');
        $this->m_datosCamposDependientes['registroActivo'] = $this->_getPosicionRegistroPantalla($this->m_datosCamposDependientes['origen']);
        unset ($_REQUEST['gvHfname']);
        unset ($_REQUEST['gvHfrom']);
        unset ($_REQUEST['gvHtarget']);
        unset ($_REQUEST['gvHvalue']);
        unset ($_REQUEST['gvHclass']);
    }

    function construirArrayOrdenarTabla($actionForm) {
        $this->m_datosOrdenarTabla['claseManejadora'] = $actionForm->get('IGEPclaseM');
        $this->m_datosOrdenarTabla['columna'] = $actionForm->get('IGEPcol');
        $this->m_datosOrdenarTabla['orden'] = $actionForm->get('IGEPord');
    }

    function construirArrayFocusChanged($actionForm) {
        $this->m_datosFocusChanged['claseManejadora'] = $actionForm->get('claseManejadora');
        $this->m_datosFocusChanged['nomForm'] = $actionForm->get('nomForm');
        $this->m_datosFocusChanged['tipoCampo'] = $actionForm->get('tipoCampo');
        $this->m_datosFocusChanged['idPanel'] = $actionForm->get('idPanel');
        $this->m_datosFocusChanged['filaActual'] = $actionForm->get('filaActual');
        $this->m_datosFocusChanged['filaProxima'] = $actionForm->get('filaProxima');
    }

    /**
    * Método privado de igep que gastamos para construir los datos necesarios para abrir una ventana de Selección 
    *
    * @access private  
    */
    function construirArrayAbrirVentanaSeleccion($actionForm) {
        $this->m_datosVentanaSeleccion['claseManejadora'] = $actionForm->get('claseManejadora');
        //Capturamos el nombre del campo
        $campo = $actionForm->get('nomCampo');
        $this->m_datosVentanaSeleccion['nombreCompleto'] = $campo;
        if (!(strpos($campo, '___') === false)) {
            $datos = explode('___', $campo);
            $datos2 = explode('_', $datos[2]);
            $this->m_datosVentanaSeleccion['nomCampo'] = $datos[1];
        }
        else
            $this->m_datosVentanaSeleccion['nomCampo'] = $campo;
        $this->m_datosVentanaSeleccion['nomForm'] = $actionForm->get('nomForm');
        //Calculamos el índice
        $posIndice = strrpos($campo, '_');
        $indice = substr($campo, $posIndice +1);
        $this->m_datosVentanaSeleccion['filaActual'] = $indice;
        $this->m_datosVentanaSeleccion['panelActua'] = $actionForm->get('panelActua');
        $this->m_datosVentanaSeleccion['actionOrigen'] = $actionForm->get('actionOrigen');
    }

    /**
    * Método privado de igep que gastamos para construir los datos necesarios para buscar en una ventana de Selección 
    *
    * @access private
    * @return integer
    */
    function construirArrayBuscarVentanaSeleccion($actionForm) {
        $this->m_datosVentanaSeleccion['nomForm'] = $actionForm->get('nomForm');
        $this->m_datosVentanaSeleccion['nomCampo'] = $actionForm->get('nomCampo');
        $this->m_datosVentanaSeleccion['camposBusqueda'] = $actionForm->get('camposBusqueda');
        $this->m_datosVentanaSeleccion['valor'] = $actionForm->get('campoBuscar');
        $this->m_datosVentanaSeleccion['filaActual'] = $actionForm->get('filaActual');
        $this->m_datosVentanaSeleccion['panelActua'] = $actionForm->get('panelActua');
        $this->m_datosVentanaSeleccion['claseManejadora'] = $actionForm->get('claseManejadora');
        $this->m_datosVentanaSeleccion['actionOrigen'] = $actionForm->get('actionOrigen');
    }

    /*----------------------------------------------FUNCIONES DE ENTREGA----------------------------------------------*/


    /**
    * Esta funcion se encargará de realizar la validación de los datos en el servidor
    * antes de realizar una operación de inserción o actualización en la BD
    * Si ha habido errores previamente en la transformacion, estan en ConfigFramework
    * 
    * @access private
    * @return any Devuelve 0 si no hay errores y un string con el error en caso de error.
    */
    public function checkDataTypes() {

        $mensajeErrorValidacion = $this->_transformErrors;

        //En el caso de no tener operacion, fijamos como operacion los visibles.
        $noOperationFixed = false;
        if($this->getOperation()=='') {
        	$noOperationFixed = true;
        	$this->setOperation('visibles');
        }

        $datos = $this->getAllTuplas();
        if(is_array($datos) and count($datos)>0) {        
	        foreach($datos as $tupla){
	            foreach($tupla as $campo => $valor){  
	                //Si el campo tiene descripción lo validamos:           
	                if(isset($this->descCampoPanel[$campo]['instance'])){
						$typeValidator = unserialize($this->descCampoPanel[$campo]['instance']);
						try{
							$typeValidator->validate($valor);
						}
						catch(Exception $e){
							//Si tiene etiqueta utilizamos esa
							$nombreEtiqueta = $typeValidator->getLabel();
							if(empty($nombreEtiqueta))
								$nombreEtiqueta = $campo;
							$mensajeErrorValidacion[]="Campo $nombreEtiqueta: ".$e->getMessage();
						}
	                }//fin del if si existe definición del campo
	            }//fin del foreach de los campos de la tupla
	        }//fin del foreach de las tuplas
        }
		//Si no había operacion fijada, lo dejamos asi.
		$noOperationFixed = false;
        if($noOperationFixed) 
        	$this->setOperation('');

        if(empty($mensajeErrorValidacion))
           return 0;
        else
           return '<br>- '.implode('<br>- ', $mensajeErrorValidacion);
    }

    /**
    * Devuelve los datos que con anterioridad se han recuperado y clasificado. Para ello solicita una operación y una tabla de la BD. 
    *
    * @access private
    * @param  string  $operacion
    * @param  string  $nombreTabla
    * @return array  
    */
    function dameDatos($operacion, $nombreTabla = '') {
        switch ($operacion) {
            case 'camposDependientes' :
                return $this->m_datosCamposDependientes;
                break;
            case 'abrirVentanaSeleccion' :
                return $this->m_datosVentanaSeleccion;
                break;
            case 'buscarVentanaSeleccion' :
                return $this->m_datosVentanaSeleccion;
                break;
            case 'ordenarTabla' :
                return $this->m_datosOrdenarTabla;
                break;
            case 'focusChanged':
                return $this->m_datosFocusChanged;
                break;
            default :
                die('Dame Datos.Operacion desconocida.');
                break;
        }
    }

    /**
    * Devuelve los datos Antiguos para una tabla dada. 
    *
    * @access private
    * @param  string  $nombreTabla
    * @return array
    */
    function dameDatosAntiguos($nombreTabla) {
        return $this->m_datos_antiguos[$nombreTabla];
    } //fin de dameDatosAntiguos

    /**
    * Retorna el valor de la fila actual 
    *
    * @access  private
    * @return  integer
    */
    function dameFilaActual() {
        return $this->int_filaActual;
    } //fin de dameFilaActual

    /*PROVISIONAL HASTA Q LO COJAMOS DE PLUGINS*/
    function damePanelActivo() {
        foreach($_REQUEST as $indice => $valor){
            if (strpos($indice,'accionActivaP_')!==false){
                $panelActivo = str_replace('accionActivaP_F_','',$indice);
                break;
            }
        }    
        return $panelActivo;
    } //fin de damePanelActivo


  /************************************* NUEVO *********************************************/

    /**
    * String que contiene la operación que se está realizando
    *
    * @var string str_operacionActual
    * @access private 
    */
    var $str_operacionActual;

    /**
    * Integer indice de 2o nivel para referenciar la matriz "ficticia" de inserciones
    *
    * @var int $int_insertarIndice
    * @access private 
    */
    var $int_insertarIndice;

    /**
    * Matriz de referencias a las tuplas insertadas
    *
    * @var array  $m_auxIndices_insertar
    * @access private 
    */
    var $m_auxIndices_insertar;

    /**
    * Integer indice de 2o nivel para referenciar la matriz "ficticia" de modificaciones
    *
    * @var int $int_actulizarIndice
    * @access private 
    */
    var $int_actualizarIndice;

    /**
    * Matriz de referencias a las tuplas modificadas
    *
    * @var array  $m_auxIndices_actualizar
    * @access private 
    */
    var $m_auxIndices_actualizar;

    /**
    * Integer indice de 2o nivel para referenciar la matriz "ficticia" de borrados
    *
    * @var int $int_borrarIndice
    * @access private 
    */
    var $int_borrarIndice;

    /**
    * Matriz de referencias a las tuplas borradas
    *
    * @var array  $m_auxIndices_borrar
    * @access private 
    */
    var $m_auxIndices_borrar;

    /**
    * Integer indice de 2o nivel para referenciar la matriz "ficticia" de seleccionados
    *
    * @var int $int_seleccionarIndice
    * @access private 
    */
    var $int_seleccionarIndice;

    /**
    * Matriz de referencias a las tuplas seleccionadas
    *
    * @var array  $m_auxIndices_seleccionar
    * @access private 
    */
    var $m_auxIndices_seleccionar;

    /**
    * Integer indice de 2o nivel para referenciar la matriz "ficticia" de postConsultar
    *
    * @var int $int_postConsultarIndice
    * @access private 
    */
    var $int_postConsultarIndice;

    /**
    * Integer indice de 2o nivel para referenciar la matriz "ficticia" de postConsultar
    *
    * @var int $int_postConsultarIndice
    * @access private 
    */
    var $int_seleccionarPadreIndice;

    /**
    * Integer indice de 2o nivel para referenciar la matriz "ficticia" de buscar
    *
    * @var int $int_buscarIndice
    * @access private 
    */
    var $int_buscarIndice;

    /**
    * Integer indice de 2o nivel para referenciar la matriz "ficticia" de iniciarVentana
    *
    * @var int $int_iniciarVentanaIndice
    * @access private 
    */
    var $int_iniciarVentanaIndice;

    /**
    * Integer indice de 2o nivel para referenciar la matriz "ficticia" de external
    *
    * @var int $int_buscarIndice
    * @access private 
    */
    var $int_externalIndice;

    /**
    * Integer indice de 2o nivel para referenciar la matriz "ficticia" de visibles
    *
    * @var int $int_visiblesIndice
    * @access private 
    */
    var $int_visiblesIndice;

    /**
    * Este método se encarga de inicializar el indice del cursor que pertenece a la operación.
    *
    * @access  public
    * @params  $parametroOperacion la operación sobre la que se quiere reinicializar el cursor 
    * @return  none
    */
    function reset($parametroOperacion = '') {
        //Seleccionamos la operacion
        if ($parametroOperacion != '') {
            $operacion = $parametroOperacion;
            //Inicializamos el indice de la operacion a 0
            $indice = 'int_'.$operacion.'Indice';
            $this-> $indice = 0;
        } 
        else {
            $operacionesBasicas = array ('insertar', 'borrar', 'actualizar', 'seleccionar', 'external', 'visibles');
            foreach ($operacionesBasicas as $operacion) {
                $indice = 'int_'.$operacion.'Indice';
                $this-> $indice = 0;
            }
        }
    }

    /**
    * Este método indica a la instancia cual es la operación en curso e inicializa el indice del cursor de dicha
    * operación. La operación en curso se utilizará siempre que no se especifique como parámetro para el resto de 
    * métodos. OJO: Falta dar una lista clara de las operaciones para el usuario (internas puede haber más)
    *
    * @access  public
    * @params  $parametroOperacion el tipo de operación: insertar, borrar, actualizar, ... hay que dar una lista fija y clara 
    * @return  none
    */
    public function setOperation($parametroOperacion) {
        if (($parametroOperacion == 'visibles') && ($this->isEmpty('visibles')))
            $parametroOperacion = 'external';
        $this->str_operacionActual = $parametroOperacion;
        $this->reset($parametroOperacion);
    } //Fin function setOperacion

    function setArrayOperacion($m_datos) {
        $str_operacion = $this->getOperation();
        $nombreMatriz = 'm_datos_'.$str_operacion.'Tpl';
        if (is_array($m_datos))
            $this-> $nombreMatriz = $m_datos;
        else
            $this-> $nombreMatriz = array ();
    }

    /**
    * Este método permite el acceso a la propiedad interna que indica la operación actual.
    *
    * @access  public
    * @return  string
    */
    public function getOperation() {
        
        return $this->str_operacionActual;
    } //Fin function getOperation

    /**
    * Este método devuelve el valor del indice del cursor sobre la operación. 
    *
    * @access  public
    * @params  $parametroOperacion el tipo de operación, si no se indica se coge el fijado para la instancia
    * @return  integer
    */
    function getIndex($parametroOperacion = '') {
        if ($parametroOperacion != '')
            $operacion = $parametroOperacion;
        else
            $operacion = $this->getOperation();
        $indice = 'int_'.$operacion.'Indice';
        return ($this-> $indice);
    }

    /**
    * posicionarEnTupla, coloca los índices en la fila/tupla que se le indique por parámetro 
    *
    * @access private
    * @params $parametroOperacion el tipo de operación, si no se indica se coge el fijado para la instancia
    * @return integer
    */
    function posicionarEnTupla($indiceFila, $parametroOperacion = '') {
        if ($parametroOperacion != '')
            $operacion = $parametroOperacion;
        else
            $operacion = $this->getOperation();
        $indice = 'int_'.$operacion.'Indice';
        if (!empty ($indiceFila))
            $this-> $indice = $indiceFila;
        else
            $this-> $indice = 0;
    }

    /**
    * Este método avanza el indice del cursor sobre la operación y lo devuelve. 
    *
    * @access  private
    * @params  $parametroOperacion el tipo de operación, si no se indica se coge el fijado para la instancia
    * @return  integer
    */
    function _next($parametroOperacion = '') {
        if ($parametroOperacion != '')
            $operacion = $parametroOperacion;
        else
            $operacion = $this->getOperation();
        $indice = 'int_'.$operacion.'Indice';
        $this-> $indice ++;
        return $this-> $indice;
    }

    /**
    * Este método, para un indice de cursor le devuelve el indice en la matriz de la operación. 
    *
    * @access  private
    * @params  $indiceGlobal indica el valor del indice del cursor de la operación
    * @return  integer
    */
    function _getIndiceInterno($operacion, $indiceGlobal = -1) {
        if ($indiceGlobal == -1)
            $indiceGlobal = $this->getIndex($operacion);
        if (($operacion == 'buscar') or ($operacion == 'postConsultar') or ($operacion == 'seleccionarPadre') or ($operacion == 'external') or ($operacion == 'visibles') or ($operacion == 'iniciarVentana'))
            return $indiceGlobal;
        //if( ($operacion=='buscar') ) return ($indiceGlobal+1);
        //if( ($operacion=='postConsultar') or ($operacion=='seleccionarPadre') or ($operacion=='external') or ($operacion=='visibles')) return $indiceGlobal;
        $matrizAuxIndice = 'm_auxIndices_'.$operacion;
        $matrizGlobal = & $this-> $matrizAuxIndice;
        if (isset ($matrizGlobal[$indiceGlobal]))
            return $matrizGlobal[$indiceGlobal];
        else
            return -1;
    }

    /**
    * Este método devuelve una matriz con los campos que aparecen en la tpl para la tupla actual  
    * dentro del cursor de la operacion. No avanza la posiccion en el cursor. 
    *
    * @access  public
    * @params  $parametroOperacion el tipo de operación, si no se indica se coge el fijado para la instancia
    * @return  matriz
    */
    function currentTupla($parametroOperacion = '') {
        if ($parametroOperacion != '')
            $operacion = $parametroOperacion;
        else
            $operacion = $this->getOperation();
        $nombreMatriz = 'm_datos_'.$operacion.'Tpl';
        $indiceInterno = $this->_getIndiceInterno($operacion);
        $matrizInterna = & $this-> $nombreMatriz;
        if (isset ($matrizInterna[$indiceInterno])) {
            //Para evitar el problema de referencias a objetos clonamos
            $row = $matrizInterna[$indiceInterno];
            foreach($row as $field => $value){
            	if(is_object($value))
            		$row[$field] = clone $value;
            }
            return $row;
        } 
        else
            return 0;
    } //Fin function currentTupla

    function getCampo($nombreCampo, $parametroOperacion = '') {
        if ($parametroOperacion != '')
            $operacion = $parametroOperacion;
        else
            $operacion = $this->getOperation();
        $resultado = $this->currentTupla($operacion);
        
        if (isset ($resultado[$nombreCampo]))               
            return $resultado[$nombreCampo];
        else
            return null;
    } //Fin function getCampo
    
    /**
    * Este método devuelve el valor antiguo de una campo para la operacion activa.  
    *
    * @access  public
    * @params  $nombreCampo el nombre del campo del cual se quiere conocer el valor antiguo
    * @return  string
    */

    public function getOldValue($nombreCampo){

    	$operacion = $this->getOperation();
    	$indice = $this->_getIndiceInterno($operacion);
    	return $this->m_datos_antiguosTpl[$indice][$nombreCampo];
    }

    function setCampo($nombreCampo, $valorCampo, $parametroOperacion = '') {
        if ($parametroOperacion != '')
            $operacion = $parametroOperacion;
        else
            $operacion = $this->getOperation();
        $indiceInterno = $this->_getIndiceInterno($operacion);
        $nombreMatrizTpl = 'm_datos_'.$operacion.'Tpl';
        $matrizInternaTpl = & $this-> $nombreMatrizTpl;
        //Tenemos que añadirlo en el array adecuado.
        $matrizInternaTpl[$indiceInterno][$nombreCampo] = $valorCampo;
            
        
    } //Fin function setCampo

    /**
    * Este método devuelve una matriz con los campos que aparecen en la tpl para la tupla actual  
    * dentro del cursor de la operacion y avanza a la siguiente posición del cursor. 
    *
    * @access  public
    * @params  $parametroOperacion el tipo de operación, si no se indica se coge el fijado para la instancia
    * @return  matriz
    */
    function fetchTupla($parametroOperacion = '') {
        if ($parametroOperacion != '')
            $operacion = $parametroOperacion;
        else
            $operacion = $this->getOperation();
        $resultado = $this->currentTupla($operacion);
        $this->_next($operacion);
        return $resultado;
    } //Fin function fetchTupla

    /**
    * Este método avanza a la siguiente posición del cursor y devuelve verdadero
    * si quedan tuplas/registros en el cursor, y falso cuando se llega al final
    *
    * @access  public
    * @params  $parametroOperacion el tipo de operación, si no se indica se coge el fijado para la instancia
    * @return  boolean
    */
    function nextTupla($parametroOperacion = '') {
        if ($parametroOperacion != '')
            $operacion = $parametroOperacion;
        else
            $operacion = $this->getOperation();
        $this->_next($operacion);
        $resultado = $this->currentTupla($operacion);
        return (!empty ($resultado));
    } //Fin function nextTupla

    function setTupla($tupla, $str_operacion = '') {
        if ($str_operacion != '')
            $operacion = $str_operacion;
        else
            $operacion = $this->getOperation();
        $indiceGlobal = ($ind = $this->getIndex()) > 0 ? ($ind -1) : 0;
        $indiceInterno = $this->_getIndiceInterno($operacion, $indiceGlobal);
        $nombreMatrizTpl = 'm_datos_'.$operacion.'Tpl';
        $matrizInternaTpl = & $this-> $nombreMatrizTpl;
        foreach ($tupla as $nombreCampo => $valorCampo) {
        	$matrizInternaTpl[$indiceInterno][$nombreCampo] = $valorCampo;
        }
        return 1;
    } //Fin function setTupla

	/**
	* getAllTuplas metodo que devuelve la matriz de datos de la operación activa.
	* @access public 
	*
	* Este metodo devuelve todo el conjunto de la operacion activa. Se le puede
	* pasar por parametro una operacion si no se quiere obtener los datos pertenecientes
	* a la operacion activa. 
	* 
	* @param string $parametroOperacion indica la operacion de la que se quiere obtener la matriz, no es obligatorio
	* @return mixed
	*  
	*/    
	public function getAllTuplas($parametroOperacion = '') {

	    if ($parametroOperacion != '')
			$operacion = $parametroOperacion;
	    else
			$operacion = $this->getOperation();
	    $this->reset($operacion);
	    $nombreMatriz = 'm_datos_'.$operacion.'Tpl';
	    if (isset ($this-> $nombreMatriz))
	    	return $this->array_values_with_clone($this-> $nombreMatriz);
	    else
			return 0;
	} //Fin function getAllTuplas

	/**
	* array_values_with_clone funcion creada para evitar pasar referencias a objetos con la matriz.
	* @access private
	* 
	* Esta funcion evita que pasemos referencias a objetos cuando obtenemos la matriz de datos. Esto
	* evita que si un programador modifica el objeto obtenido y luego no hace el setAllTuplas, no tenga
	* problemas con dicha modificacion.
	* 
	* @param mixed $matrix matriz de datos
	* @return mixed
	*  
	*/
	private function array_values_with_clone($matrix) {
		
		//inicializamos la variables
		$clone = false;
		$result = array();
		
		if(!is_array($matrix))
			return $result;

		if(count($matrix)==0)
			return array();
		
		//Comprobamos en la primera fila si tiene objetos
		$validation = $matrix[key($matrix)];
		if (is_array($validation))
			foreach($validation as $field) {
				if(is_object($field)) {
					$clone = true;
					break;
				}
			}
		reset($matrix);
		//Construimos la matriz resultado clonando si es necesario
		foreach($matrix as $row) {
			if($clone) {
				foreach($row as $field => $value) {
					if(is_object($value))
						$row[$field] = clone $value;
				}
			}
			$result[]=$row;
		}
		return $result;
	}

  /**
   * Este método devuelve todas las tuplas de antiguas para una operacion dada.
   */
  function getAllTuplasAntiguas($parametroOperacion = '') {
    if ($parametroOperacion != '')
      $operacion = $parametroOperacion;
    else
      $operacion = $this->getOperation();
    $this->reset($operacion);
    $indice = 0;
    $resultado = array ();
    while (($indiceInterno = $this->_getIndiceInterno($operacion)) != -1) {
      $resultado[$indice] = $this->m_datos_antiguosTpl[$indiceInterno];
      ++$indice;
      $this->_next();
    }
    return $resultado;
  } //Fin de getAllTuplasAntiguas

  function setAllTuplas($vTuplas, $parametroOperacion = '') {
    if ($parametroOperacion != '')
      $operacion = $parametroOperacion;
    else
      $operacion = $this->getOperation();
    $this->reset();
    //Si tiene más tuplas de las que teniamos borramos el contenido de la matriz TPL
    if (count($vTuplas) != count($this->getAllTuplas($operacion))) {
        $matrizInternaTpl = 'm_datos_'.$operacion.'Tpl';
        $matrizInterna = 'm_datos_'.$operacion;
        $this-> $matrizInternaTpl = array ();
        $this-> $matrizInterna = array ();
        //Regeneramos el indice de las tuplas
        $numTotalTuplas = count($vTuplas);
        $matrizAuxIndices = 'm_auxIndices_'.$operacion;
        $this-> $matrizAuxIndices = array();
        $i = 0;
        while($i<$numTotalTuplas){
            array_push($this-> $matrizAuxIndices,$i);
            ++$i;
        }
    }
    if (!is_array($vTuplas)) {
    	throw new Exception('IgepComunicacion::setAllTuplas ha de recibir un array, ahora recibe: '.var_export($vTuplas,true));
    }
    foreach ($vTuplas as $tupla) {
      $this->_next($operacion);
      $this->setTupla($tupla, $operacion);
    }
    return 1;
  } //Fin de setAllTuplas


  /**
   * Este método guarda todas las tuplas de antiguas.
   */
  function _setAllTuplasAntiguas($vTuplas, $parametroOperacion = '') {
    if ($parametroOperacion != '')
      $operacion = $parametroOperacion;
    else
      $operacion = $this->getOperation();
    $this->reset($operacion);
    $indice = 0;
    $resultado = array ();
    while (($indiceInterno = $this->_getIndiceInterno($operacion)) != -1) {
      $this->m_datos_antiguosTpl[$indiceInterno] = $vTuplas[$indice];
      ++$indice;
      $this->_next();
    }
    return $resultado;
  } //Fin de getAllTuplasAntiguas


	function setList($nombreCampo, $v_lista, $parametroOperacion = '') {
		//Este método sólo tiene sentido en un postConsultar (postBuscar, postEditar, postRecargar)
	    //Por estar razón sólo se escribirá en la matriz TPL
		if ($parametroOperacion != '')
	    	$operacion = $parametroOperacion;
		else
	    	$operacion = $this->getOperation();
		$indiceInterno = $this->_getIndiceInterno($operacion);
		$nombreMatrizTpl = 'm_datos_'.$operacion.'Tpl';
		$matrizInternaTpl = & $this-> $nombreMatrizTpl;
		$matrizInternaTpl[$indiceInterno][$nombreCampo] = $v_lista;
	}

	public function isEmpty($parametroOperacion = '') {
		if ($parametroOperacion != '')
			$operacion = $parametroOperacion;
		else
			$operacion = $this->getOperation();
		$nombreMatriz = 'm_datos_'.$operacion.'Tpl';
		if(!isset($this-> $nombreMatriz) OR count($this-> $nombreMatriz)==0)
			return TRUE;
		return FALSE;
	} //fin de isEmpty
  
	/** Método que devuelve para una tupla dada la información de un campo de tipo 'FILE'
	* que se ha subido al servidor.
	* @access private
	* @param string nombreCampo Nombre del campo FILE del que se quiere obtener la información
	* @param string parametroOperacion Indica la operación sobre la que se quiere la tupla
	* @return array
	*/
	function getFileInfo($nombreCampo, $parametroOperacion){
		if ($parametroOperacion != '')
			$operacion = $parametroOperacion;
		else
			$operacion = $this->getOperation();
		$indiceInterno = $this->_getIndiceInterno($operacion);
		return $this->m_datosFicherosUpLoad[$indiceInterno][$nombreCampo];
	}
	
	//REVIEW: Toni Para poder conseguir que las acciones de negocio tengan acceso a mapeos diferentes a los ya especificados
	function setMapping(ActionMapping $actionMapping){
		$this->_actionMapping = $actionMapping;
	}

	/** 
	* Método que devuelve el forward name de la acción en curso. Buscará la respuesta identificada por name en la accion actual definida en mappings.php
	* @access public
	* @param string name Nombre de la respuesta deseada
	* @return object
	*/
	public function getForward($name){

		//Si no existe, lanzamos excepcion que informara en el debugger del error.
		if(!$this->_actionMapping->containsKey($name))
			throw new Exception('Error: actionForward '.$name.' no existe. Consulte el fichero de mapeos.');

		if(isset($this->_actionMapping))
			return $this->_actionMapping->get($name);
	}

	
	
	/**
	 * Realizar las conversiones desde formato usuario a formato FW
	 * 
	 * @access public
	 * @param array a_parametros Vector de asociativo de elementos a transformar
	 * @param string a_tipo Nombre de la respuesta deseada
	 */
    public static function transform_User2FW(& $a_parametros, $a_tipo=TIPO_CARACTER)
	{
		ConfigFramework::setTransformErrors(array());
		if (empty($a_tipo) and !is_array($a_tipo))
		{
			$a_tipo = TIPO_CARACTER;
		}
		
		if (!is_array($a_parametros))
		{
			// le doy estructura de vector para no repetir el codigo
			$vector = false;
			$a_parametros = array(array('col'=>$a_parametros,),);
			$a_tipo = array('col'=>array('tipo'=>$a_tipo,),);
		}
		else
		{
			$vector = true;
		}
		
		if (is_array($a_tipo)) 
		{
			$transformer = new IgepTransformer(true);
			$car_i = ConfigFramework::getNumericSeparatorsUser();
			$car_n = ConfigFramework::getNumericSeparatorsFW();
			$transformer->setDecimal($car_i['DECIMAL'],$car_n['DECIMAL'],$car_i['GROUP'],$car_n['GROUP']);
			$fecha_i = ConfigFramework::getDateMaskUser();
			$fecha_n = ConfigFramework::getDateMaskFW();
			$transformer->setDate($fecha_i, $fecha_n);			
			foreach ($a_parametros as $fila => $tupla)
			{
				foreach ($a_tipo as $campo => $descTipo)
				{
					$tipo_efectivo = ($descTipo['tipo']==TIPO_ENTERO? TIPO_DECIMAL: $descTipo['tipo']);
					if (empty($a_parametros[$fila][$campo]))
					{
						if($tipo_efectivo==TIPO_FECHA or $tipo_efectivo==TIPO_FECHAHORA)
						{
							$a_parametros[$fila][$campo] = null;
						}
						continue;
					}
					$num_errores = count($transformer->getTransformErrors());			
					$a_parametros[$fila][$campo] = $transformer->process($tipo_efectivo, $tupla[$campo]);
					
					if ($tipo_efectivo == TIPO_FECHA or $tipo_efectivo == TIPO_FECHAHORA)
					{
						if (count($transformer->getTransformErrors())==$num_errores)
						{
							$a_parametros[$fila][$campo] = new gvHidraTimestamp($a_parametros[$fila][$campo]);
						}
						else
						{
							$a_parametros[$fila][$campo] = null;
						}
					}
				}
			}
        	ConfigFramework::setTransformErrors($transformer->getTransformErrors());
		}
        if (!$vector)
        {
	    	$a_parametros = $a_parametros[0]['col'];
        }
	}//Fin IgepComunicacion


} //Fin de IgepComunicacion
?>