<?php
/**
 * Validacion de usuarios usando certificados
 * Hace uso del PEAR::Auth
 *
 * @package gvHIDRA
 */

include_once 'gvhBaseAuthCert.php';
include_once 'UserCert.php';

class AuthCD extends gvhBaseAuth
{
	static $cd;
	static $cd_user;	
	static $method;// indica si se permite 1) user/pwd, 2) cd o 3) ambos
	static $https_all;
	static $redirect;
	static $cb_checkUserCert;
	static $cb_checkUser;
	static $cb_loginFunction;
	
    function fetchData($username, $password, $isChallengeResponse=false)
    {
    	// validacion del certificado
        if ($username == 'cd' and $password == 'cd') //Si usuario y pass es cd (cert. Dig)
        {
        	self::$cd = new UserCert($_SERVER);
			if (!is_callable(self::$cb_checkUserCert))
				throw new gvHidraException('No se puede invocar a '.var_export(self::$cb_checkUserCert,true).' para comprobar el usuario del certificado');
        	self::$cd_user = call_user_func(self::$cb_checkUserCert,self::$cd);
        	return (!is_null(self::$cd_user) and self::$cd_user !== false);

        }
        else
        {
        	// validación de usuario/contraseña
			if (!is_callable(self::$cb_checkUser))
				throw new gvHidraException('No se puede invocar a '.var_export(self::$cb_checkUser,true).' para comprobar el usuario/contraseña');
			$llamada = call_user_func(self::$cb_checkUser, $username, $password);
			return $llamada;
        }
    }
    
	/**
	 * Metodo para llamar desde aplicaciones, en la autenticacion inicial
	 * 
	 * Devuelve cadena vacia si todo va bien, o texto si error
	 */
	static function autenticate($p_apli, $p_options=array())
	{
		
		foreach ($p_options as $i=>$value) {
			self::$$i = $value;
		}
		// comprobar parámetros
		if (isset(self::$cb_loginFunction)) {
			if (!is_callable(self::$cb_loginFunction))
				throw new gvHidraException('No se puede invocar a '.var_export(self::$cb_loginFunction,true).' para dibujar formulario de login');
			$form = self::$cb_loginFunction;
		} else
			$form = array(__CLASS__,'loginFunction');
		////
		
		$auth_container = new self($p_apli);

		IgepSession::session_start($p_apli, false);
		$aut = new Auth($auth_container,null,$form);
		$aut->setSessionName($p_apli);
		if (isset($_GET['logout']))
			$aut->logout();

		$aut->start();
		
		if ($aut->checkAuth()) 
		{
			if ($aut->getUsername()=='cd') {
				$aut->setAuth(self::$cd_user);
				$auth_container->open(self::$redirect,self::$https_all,$aut);
			} else
				$auth_container->open(self::$redirect);
		}
		return '';
	}

	
	static function loginFunction($username = null, $status = null, &$auth = null) 
	{
		// guarda url de login
		$url_login = $auth->getAuthData('login');
		if (!$url_login)
			$auth->setAuthData('login','http'.(isset($_SERVER['HTTPS'])?'s':'').'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['PHP_SELF']);
		$status_desc = '';
	    if (!empty($status) && $status == AUTH_EXPIRED) 
	    {
			$status_desc = '<i>Tu sesión ha expirado. Por favor conéctate de nuevo!</i>'."\n";
        }
        else if (!empty($status) && $status == AUTH_IDLED)
        {
			$status_desc = '<i>Has estado inactivo mucho tiempo. Por favor conéctate de nuevo!</i>'."\n";
        }
        else if (!empty ($status) && $status == AUTH_WRONG_LOGIN) 
        {
			$status_desc = '<i><b>Credenciales incorrectas!</b></i><br/><br/>';
			$status_desc.='Puede haber problemas con tu certificado.<br/>';
			$status_desc.='Obten soporte en: <a class="text" href="http://www.accv.es/ciudadanos/ayuda/">ACCV</a> o ';
			$status_desc.='<a class="text" href="http://www.dnielectronico.es/como_utilizar_el_dnie/index.html">DNIe</a> '."\n";
        }
        else if (!empty ($status) && $status == AUTH_SECURITY_BREACH)
        {
			$status_desc = '<i>Problema de seguridad detectado. </i>'."\n";
        }
        $html_aviso = '';
        if ($status_desc)
        	$html_aviso = <<<EOF
	  <tr class="text"><td colspan="2" align="center">
	  	$status_desc
	  </td></tr>
EOF;
        if ($username=='cd') $username='';
        $custom = ConfigFramework::getCustomDirName();
        $puerto = $_SERVER['SERVER_PORT']!='80'? ':'.$_SERVER['SERVER_PORT']: '';
		$login = 'https://'.$_SERVER['SERVER_NAME'].$puerto.$_SERVER['PHP_SELF'];
        
		echo <<<EOF
<html>
<head>
<title>Accés personalitzat a aplicacions</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='igep/custom/$custom/css/aplicacion.css' type='text/css' />
<link rel='stylesheet' href='igep/custom/$custom/css/layersmenu-cit.css' type='text/css' />
<style>
.style {
	-webkit-border-radius: 9px;
	-moz-border-radius: 9px;
	border-radius: 9px;
	color: #857256;
	border:1px solid #857256;
}
</style>
</head>
<body>
<br/><br/><br/>
<table width="40%" border="0" cellspacing="0" cellpadding="0" align="center">
<tbody>
  <tr align="center"> 
   <td width="20%">
      <img src="igep/custom/$custom/images/logos/logo.gif" width="35%" height="35%"><br/><br/> 
    </td>
  </tr>
EOF;
		if (self::$method == 1 or self::$method == 3)
			echo <<<EOF
  <tr>
    <td width="20%" class="tablaModificar">
	<form method="post" action="" AUTOCOMPLETE="OFF">
      <table border="0" cellpadding="3" width="100%" align=center>
	  <tr>
			<td class="cabecerainicial" colspan="2">
			  Introduce tus datos
			</td>
	  </tr>
	  <tr class="formularios">
	    <td align="right">Usuario:</td>
	    <td><input type="text" name="username" size=15 class="formularios editable" value="$username"></td>
	  </tr>
	  <tr class="formularios">
	    <td align="right">Password:</td>
	    <td><input type="password" name="password" size=15 class="formularios editable"></td>
	  </tr>
	  <tr class="formularios">
	    <td colspan="2" align="center">
		<button style='cursor:pointer; display:inline; ' id='aceptar' name='aceptar' value="aceptar" type='button' class=boton onmouseover="this.className='boton_on';" onmouseout="this.className='boton';" onClick="javascript:this.form.submit();">
		  <img src='igep/custom/$custom/images/acciones/08.gif' style='border-style:none;' alt='Aceptar' title='Aceptar' /> Aceptar 
		</button>
	    </td>
	  </tr>
	  $html_aviso
	    </table>
    </form>
   </td>
  </tr>
EOF;
		if (self::$method == 2 or self::$method == 3)
			echo <<<EOF
<tr><td width="20%" class="tableModify">
	<form method="post" action="$login">
		<table border="0" cellpadding="3" width="100%" align="center">
		<tr>
			<td class="header" colspan="2">
				Acceso con certificado digital de la <a class="titlePanel" style="padding-left:0px;" href="http://www.accv.es">ACCV</a> o <a class="titlePanel" style="padding-left:0px;" href="http://www.dnielectronico.es">DNIe</a>
			</td>
		</tr>
		<tr class="text">
			<td align="center">
				Asegúrese de tener el certificado instalado en su navegador, o 
				<br/>tener el lector configurado y con la tarjeta en su interior.
				<br/><br/> Pulse el botón aceptar cuando este listo.<br/>
			</td>
			<td><input type="hidden" name="username" value="cd">
			<input type="hidden" name="password" value="cd"></td>
		</tr>
		<tr class="text">
			<td colspan="2" align="center">
				<button style='cursor:pointer; display:inline; ' id='cd' name='cd' value="Acceso con Certificado" type='button' class="button" onmouseover="this.className='button_on';" onmouseout="this.className='button';" onClick="javascript:this.form.submit();" />
				<img src='igep/custom/$custom/images/acciones/08.gif' style='border-style:none;' alt='Acceso con Certificado' title='Acceso con Certificado' /> Aceptar
				</button>
			</td>
		</tr>
$html_aviso
	</table>
	</form>
</td></tr>
EOF;
		echo <<<EOF
  </tbody>
</table>
 
</body>
</html>
EOF;
}

}
?>
