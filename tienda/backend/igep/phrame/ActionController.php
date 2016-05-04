<?php
/**
 * The ActionController class represents the controller in the
 * Model-View-Controller (MVC) design pattern. The ActionController receives
 * and processes all requests that change the state of the application.
 *
 * Generally, a "Model2" application is architected as follows:
 * <ul>
 * <li>The user interface will generally be created with either PHP pages,
 * which will not themselves contain any business logic. These pages represent
 * the "view" component of an MVC architecture.</li>
 * <li>Forms and hyperlinks in the user interface that require business logic
 * to be executed will be submitted to a request URI that is mapped to the
 * ActionController. The ActionController receives and processes all requests
 * that change the state of a user's interaction with the application. This
 * component represents the "controller" component of an MVC architecture.</li>
 * <li>The ActionController will select and invoke an Action class to perform
 * the requested business logic.</li>
 * <li>The Action classes will manipulate the state of the application's
 * interaction with the user, typically by creating or modifying classes that
 * are stored as session attributes. Such classes represent the "model"
 * component of an MVC architecture.</li>
 * <li>Instead of producing the next page of the user interface directly,
 * Action classes will forward control to an appropriate PHP page to produce
 * the next page of the user interface.</li>
 * </ul>
 * The standard version of ActionController implements the following logic for
 * each incoming HTTP request. You can override some or all of this
 * functionality by subclassing this class and implementing your own version of
 * the processing.
 * <ul>
 * <li>Identify, from the incoming request URI, the substring that will be used
 * to select an Action procedure.</li>
 * <li>Use this substring to map to the class name of the corresponding Action
 * class (a subclass of the Action class).</li>
 * <li>If this is the first request for a particular Action class, instantiate
 * an instance of that class and cache it for future use.</li>
 * <li>Optionally populate the properties of an ActionForm class associated
 * with this ActionMapping and cache it for future use.</li>
 * <li>Call the perform() method of this Action class. Passing in the mapping
 * and the request that were passed to the ActionController by the bootstrap.
 * </li>
 * </ul>
 *
 * The standard version of ActionController is configured based on the
 * following initialization parameters, which you will specify in the options
 * for your application. Subclasses that specialize this ActionController are
 * free to define additional initialization parameters.
 * <ul>
 * <li><b>options</b> - This sets the ActionController options.</li>
 * </ul>
 *
 * @author	Arnold Cano
 * @version	$Id: ActionController.php,v 1.28 2010-02-23 15:51:59 gaspar Exp $
 */
class ActionController extends Object
{
	/**
	 * @var	array
	 */
	var $_options;
	/**
	 * @var	HashMap
	 */
	var $_actionMappings;
	/**
	 * @var	HashMap
	 */
	var $_actions;

	/**
	 * Create a ActionController specifying the options.
	 *
	 * @access	public
	 * @param	array	$options
	 */
	public function ActionController($options)
	{
		if (!is_array($options)) {
			trigger_error('Invalid options file');
			return;
		}
		$this->_options = $options;
		//initialize cache
		$this->_actionMappings = new HashMap();
		$this->_actions = new HashMap();
	}
	/**
	 * Process the request.
	 *
	 * @access	public
	 * @param	array	$mappings
	 * @param	array	$request
	 */
	public function process($mappings, $request)
	{
		if (!is_array($mappings)) {
			trigger_error('Invalid mappings file');
			return;
		}
		if (!is_array($request)) {
			trigger_error('Invalid request');
			return;
		}
		//error_reporting($this->_options[_ERROR_REPORTING]);
		$actionMapping = $this->_processMapping($mappings, $request);
		//Creamos el ActionForm (un Hasmap sobre el request)
		$actionForm = new HashMap($request);
		$actionForward = $this->_processAction($actionMapping, $actionForm);
		if (is_object($actionForward)) {
			$this->_processForward($actionForward);
		}
	}
	/**
	 * Identify and return an appropriate ActionMapping.
	 *
	 * @access	private
	 * @param	array			$mappings
	 * @param	array			$request
	 * @return	ActionMapping
	 */
	public function _processMapping($mappings, $request)
	{
		$name = $request[_ACTION];
		$mapping = $mappings[_ACTION_MAPPINGS][$name];
		$actionMapping = $this->_actionMappings->get($name);
		if (!is_object($actionMapping)) {
			$actionMapping = new ActionMapping($name, $mapping);
			if ($this->_options[_CACHE]) {
				$this->_actionMappings->put($name, $actionMapping);
			}
		}
		return $actionMapping;
	}

	/**
	 * Ask the specified Action instance to handle this request.
	 *
	 * @access	private
	 * @param	ActionMapping	$actionMapping
	 * @param	ActionForm		$actionForm
	 * @return	ActionForward
	 */
	public function _processAction($actionMapping, $actionForm)
	{
		$name = $actionMapping->getName();
		$type = $actionMapping->getType();
		$action = $this->_actions->get($name);
		if (!is_object($action)) {
			//Si la clase no existe marcamos el error.
			if (!class_exists($type)) {

				$name = htmlentities($name, ENT_COMPAT | ENT_HTML401, 'ISO-8859-1');
				$type = htmlentities($type, ENT_COMPAT | ENT_HTML401, 'ISO-8859-1');
				if($type=='' AND $name=='') {
					IgepDebug::setDebug(ERROR,'Se ha producido un error al intentar ejecutar una clase vacía y una acción vacía.');
				}
				elseif($type=='') {
					IgepDebug::setDebug(ERROR,'Se ha intentado ejecutar la acción \''.$name.'\' y no está programada para la clase actual. Compruebe el fichero de la clase y el mappings.php de su aplicación.');
				}
				else {
					IgepDebug::setDebug(ERROR,'Se ha producido un error intentado ejecutar la acción \''.$name.'\' de la clase \''.$type.'\'. Compruebe el nombre de la clase y su accesibilidad (fichero include.php).');
				}
				die('ACCESO NO PERMITIDO: Se ha intentado ejecutar una acción no programada.');
				return;
			}
            //Guardamos la referencia del modulo si es la primera pantalla del modulo a la que accedemos.
            if(isset($_REQUEST['modActv'])){
                IgepSession::guardaVariable('global','modActv',$_REQUEST['modActv']);
                //Borramos el contenido de los paneles anteriores
                IgepSession::_borrarPanelesVisitados();
                //Borramos el contenido del salto
                IgepSession::borraSalto();
            }
            //Si el panel ya existe lo recuperamos de la Session
            if(IgepSession::existePanel($type)&&(strpos($type,'gvHidraForm')===false)){
                $action = IgepSession::damePanel($type);
                if(method_exists($action,'regenerarInstancia'))
                	$action->regenerarInstancia('');
                else{
                	IgepSession::borraPanel($type);
                	IgepDebug::setDebug(PANIC,'Error al recuperar la instancia de '.$type.'. Puede deberse a un error en el constructor. Se crea una nueva instancia.');
                	$action = new $type();
                }
            }
            else{
                IgepDebug::setDebug(DEBUG_IGEP,'Creamos una instancia de la clase '.$type);
                $action = new $type();
            }
            if ($this->_options[_CACHE]) {
        	   $this->_actions->put($name, $action);
            }
       }
       if(is_callable($this->_options[_ERROR_HANDLER]))
           set_error_handler($this->_options[_ERROR_HANDLER]);
       if(!$action->obj_errorNegocio->hayError()) {
           $actionForward = $action->perform($actionMapping, $actionForm);
           //Si es un gvHidraNoAction, cargamos la clase manejadora
			if($actionForward->getName()=='gvHidraNoAction') {
				$aux = $actionForward->get('IGEPclaseManejadora');
				if(empty($aux))
					$actionForward->put('IGEPclaseManejadora',$type);
			}
			if($actionForward->getName()=='gvHidraReload') {
				$aux = $actionForward->get('IGEPclaseManejadora');
				if(empty($aux))
					$actionForward->put('IGEPclaseManejadora',$type);
				//Cargamos la ruta actual desde el views.
				$posicionIndice = strpos($_SERVER['HTTP_REFERER'],"index.php?view=");
				if($posicionIndice!==false AND $posicionIndice>0)
					$actionForward->setPath(substr($_SERVER['HTTP_REFERER'],$posicionIndice));
			}
       }
       else{
           $action->obj_errorNegocio->limpiarError();
           unset($action);
           $actionForward = new ActionForward('IgepInicioAplicacion');
           $actionForward->_path = 'index.php?view=igep/views/aplicacion.php';
           //Borramos cualquier referencia a la clase en la SESSION
           IgepSession::borraPanel($type);
       }
	   if(is_callable($this->_options[_ERROR_HANDLER]))
	       restore_error_handler();
	   return $actionForward;
	}
	/**
	 * Forward to the specified destination.
	 *
	 * @access	private
	 * @param	ActionForward	$actionForward
	 */
	public function _processForward($actionForward)
	{
		$salto ='';
		switch($actionForward->getName()){
			case 'gvHidraNoAction':
				$path='';
				$claseManejadora = $actionForward->get('IGEPclaseManejadora');
				$salto = "Location: index.php?view=igep/views/igep_regenerarVentana.php&IGEPpath=$path&IGEPclaseManejadora=$claseManejadora";
				break;
			case 'IgepOperacionOculto':
				$path = $actionForward->getPath();
				$salto = "Location: $path";
				break;
			case 'IgepSaltoVentana':
				$path = $actionForward->get('IGEPaccionDestinoSalto');
				$salto = "Location: $path";
      			break;
			default:
				$path = $actionForward->getPath();
				//REVIEW: Toni PHRAME
				/* La variable clase Manejadora no está definida, por tanto se podrá eliminar.
				*/
				$claseManejadora ='';
				$salto ="Location: index.php?view=igep/views/igep_regenerarVentana.php&IGEPpath=$path&IGEPclaseManejadora=$claseManejadora";
		}
		header($salto);
	}
}
?>
