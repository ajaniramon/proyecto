<?php
/**
 * Validacion de usuarios: configuracion personalizable por el usuario
 * Hace uso del PEAR::Auth
 *
 * @package gvHIDRA
 */

include_once 'gvhBaseAuth.php';

class AuthBasic extends gvhBaseAuth
{
	var $response = null;
	
    function __construct()
    {
		parent::__construct();
		$this->datos=array(
			'bd'=>'bd-usuario',	// servidor bd
			'server'=>'http',		// servidor web
		);
    }


    function fetchData($username, $password, $isChallengeResponse=false)
    {
       /* 
        if($username=='invitado' and md5('salto-pre'.$password.'salto-post')=='19fda1814a6fc1c51c6ceb14ea92fba7') {
           
            return true;
        }
        return false;*/
    	
    	
 		$link = mysqli_connect("localhost","root","root","shop") or die("Error conectando a la BD: ".mysqli_errno());
    	$query = "SELECT * from cliente WHERE correo = '".$username."' AND contrasenya ='".md5($password)."';";
    	$resultado = mysqli_query($link,$query);
    	if (mysqli_num_rows($resultado) == 1) {
    		$fila = mysqli_fetch_array($resultado,MYSQLI_ASSOC);
    		if ($fila["empleado"] == "true") {
    	
    			
    			return true;
    		}else{
    			return false;
    		}
    		 
    		 
    	}else if(mysqli_num_rows($resultado) == 0){
    		return false;
    	}else{
    		throw new Exception("Se ha liado parda");
    	}
    }
    
    function getDatos()
    {
    	return $this->datos;
    }

	function getResponse() 
	{
		return $this->response;
	}
    
    /**
     * Inicializa variables en la sesion que recibe.
     * Se ejecuta la primera vez que accede a la aplicacion
     */
    function postLogin($sess, $aut=null)
    {
    	
    	$conf = ConfigFramework::getConfig();
    	$descAplicacion = $conf->getCustomTitle();
    	if (empty($descAplicacion))
    	{
    		$descAplicacion = 'Aplicacion gvHIDRA (AuthBasic)';
    	}
    	
    	$sess['usuario']['nombre']="Bienvenido, ".$sess['usuario']['usuario']. "!";
		$sess['rolusuar'] = 'perfil_por_definir';
		$sess['modulos'] = array();
		$sess['daplicacion'] = $descAplicacion;
    	return $sess;
    }

	/**
	 * Metodo para llamar desde aplicaciones, en la autenticacion inicial
	 * 
	 * Devuelve cadena vacia si todo va bien, o texto si error
	 */
	static function autenticate($p_apli)
	{
		$auth_container = new self($p_apli);

		IgepSession::session_start($p_apli, false);
		$aut = new Auth($auth_container,'','loginFunction');

		$aut->setSessionName($p_apli);
		if (isset($_GET['logout']))
			$aut->logout();
		$aut->start();

		$resp = $auth_container->getResponse();

		if ($aut->checkAuth()) {
			$aut->setAuthData('response',$resp);
			$auth_container->open('igep/include/valida/validacion.php');
			return '';
		} else {
			if (isset($resp))
				return $resp->getMessage();
			else
				return ''; // ocurre cuando no hay usuario/password
		}
	}

}
function loginFunction($username = null, $status = null, &$auth = null) 
{
		$status_desc = '';
	    if (!empty($status) && $status == AUTH_EXPIRED) {
            $status_desc = '<i>Tu sesi�n ha expirado. Por favor conectate de nuevo!</i>'."\n";
        } else if (!empty($status) && $status == AUTH_IDLED) {
            $status_desc = '<i>Has estado inactivo mucho tiempo. Por favor conectate de nuevo!</i>'."\n";
        } else if (!empty ($status) && $status == AUTH_WRONG_LOGIN) {
            $status_desc = '<i>Credenciales incorrectas!</i>'."\n";
        } else if (!empty ($status) && $status == AUTH_SECURITY_BREACH) {
            $status_desc = '<i>Problema de seguridad detectado. </i>'."\n";
        }
        $custom = ConfigFramework::getCustomDirName();
        $aplDesc = ConfigFramework::getCustomTitle();
        $aplName = ConfigFramework::getApplicationName();
        $aplVersion = ConfigFramework::getAppVersion();
        $msgStart = ConfigFramework::getStartMsg();
        $msgStartType = ConfigFramework::getStartMsgType();       
        $msgAviso = '';
        if (!empty($msgStart))
        {
        	// Aplicaci�n con mensaje de aviso
        	$msgAviso = "<div id='msgStart'>".htmlentities($msgStart, ENT_QUOTES | ENT_IGNORE, "ISO8859-1")."</div>";
        }
        if (($msgStartType=='lock') && (!empty($msgStart))) 
        {
        	// Aplicaci�n bloqueada
        	echo <<<EOF
				<html>
				<head>
				<title>Acceso personalizado a aplicaciones</title>
				<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
				<link rel='icon' type='image/ico' href='custom/$custom/images/favicon.ico'/>
				<link rel='stylesheet' href='custom/$custom/css/layersmenu-cit.css' type='text/css' />
				<link rel='stylesheet' href='custom/$custom/css/bootstrap-theme.css' type='text/css' />
				<link rel='stylesheet' href='custom/$custom/css/bootstrap.css' type='text/css' />
				<link rel='stylesheet' href='custom/$custom/css/aplication-login.css' type='text/css' />
				</head>
				<body>
						<div class='container'>
				<div align="center" class='panel-login'>
				<div id='aplLogin' class="row text-center title-login">		
							<h2 id='title'>$aplName</h2>
							<h4 id='descrTitle'>$aplDesc</h4>
							<h4 id='version'>Versi�n $aplVersion</h4>
						</div>
					
						<h5 class='row text-center message-login'>	
							$msgAviso
						</h5>	
					
				
						<div style='clear:both;'></div>
						<!--<div class="image-login text-center">
							<img src="custom/$custom/images/logos/logo.gif">
						</div>-->
					</div>				
				</div>
        	
				</body>
				</html>
EOF;
        }
		else 
		{
			echo <<<EOF
				<html>
				<head>
				<title>Acceso personalizado a aplicaciones</title>
				<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
				<link rel='icon' type='image/ico' href='custom/$custom/images/favicon.ico'/>
				<link rel='stylesheet' href='custom/$custom/css/layersmenu-cit.css' type='text/css' />
				<link rel='stylesheet' href='custom/$custom/css/bootstrap-theme.css' type='text/css' />
				<link rel='stylesheet' href='custom/$custom/css/bootstrap.css' type='text/css' />
				<link rel='stylesheet' href='custom/$custom/css/aplication-login.css' type='text/css' />
				
				<script>
				  document.onkeypress = processKey;
				
				  function processKey(e)
				  {
				    if (null == e)
				      e = window.event ;
				    if (e.keyCode == 13)  {
				      document.forms[0].submit();
				    }
				  }
				</script>
				</head>
				<body>
			
				<div class='container'>
				<div align="center" class='panel-login'>
				<div id='aplLogin' class="row text-center title-login">		
							<h2 id='title'>$aplName</h2>
							<h4 id='descrTitle'>$aplDesc</h4>
							<h4 id='version'>Versi�n $aplVersion</h4>
						</div>
					
						<h5 class='row text-center message-login'>	
							$msgAviso
						</h5>	
					
						<div class='row form-row-login'>
					
						<div class=' text-center login-validation'>
				 			<h4>ACCESO RESTRINGIDO</h4>		
						</div>	
						<div id='formLogin'>
							<br>
							<form class='form-inline' method="post" action="" AUTOCOMPLETE="OFF">
							
								
							 <div class='form-group text-left form-elements'>
								<label for="exampleInputName2">Usuario&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
								<input type='text' name="username" class='form-control' value="$username" placeholder="Introduce el usuario">
							 </div>
								<br/><br/>
							   <div class="form-group text-left form-elements">
									<label for="exampleInputEmail2">Contrase�a&nbsp;&nbsp;</label>
									<input type="password" name="password" class="form-control"  placeholder="Introduce la contrase�a" >
							   </div> 	 
								
								<br/><br/>
								<div class='text-center panel-button-login'>
								<button id='validar' name='validar' value="Validar" type='button' class='button-login' onClick="javascript:this.form.submit();">
								<span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> Validar
								</button>
								</div>	
								<br/>
								<div class='status-message text-center bg-danger'>$status_desc</div>
									
							</form>
							</div>
						</div>
						
						<div style='clear:both;'></div>
						<!--<div class="image-login text-center">
							<img src="custom/$custom/images/logos/logo.gif">
						</div>-->
					</div>				
				</div>
				
				</body>
				</html>
EOF;
		}
}
?>