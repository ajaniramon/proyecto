<?php
/**
 * Hace uso de la validacion por certificado. Reemplaza a login.php que queda como login.old.php
 *
 * @package gvHIDRA
 */

include_once('igep/include_class.php');
include_once 'include/valida/validacion_cd.php';

/**
 * Función de autorización con Cert. Dig. Recibe identificador de usuario (Certificado) y devuelve estrutura de autorización o null
 * @param UserCert $cert	Certificado de usuario
 * @return mixed	Devuelve un array asociativo con los datos del ususario o null si Error
 */
function usuarioValidoCert(UserCert $cert)
{
	//Leemo el Id obtenido del certificado, es el DNI del usuario
	$id = $cert->getIdent();
	$id = strtoupper(trim($id));

	/*
		Preparamos un consulta que comprueba si el usuario (DNI del Cert. Dig)
		tiene acceso a la aplicación y cargue los datos del mismo en sesión
		Esta consulta es dependiente de la fuente de información (podría no ser
		una query SQL sino una invocación a un WS, de un fichero plano, etc...)
	*/
	
	/* Manejador de conexión */
	$conf = ConfigFramework::getConfig();
	$g_dsn = $conf->getDSN('g_oracle');
	$options = array (
		'debug'       => 2,
		'portability' => MDB2_PORTABILITY_ALL,
	);
	
	$mdb2ConDB =& MDB2::connect($g_dsn, $options);
	if (PEAR::isError($mdb2ConDB))
	{	
		throw new RuntimeException ('Error acceso BD: '.$mdb2ConDB->getMessage());
		return null;
	}
	$mdb2ConDB->setFetchMode(MDB2_FETCHMODE_ASSOC);
	
$strSelect = <<<query
SELECT
	upper(usu.nif) as "nif",	
	usu.fecha_alta as "fechaAlta",
	usu.fecha_baja as "fechaBaja",
	usu.nombre as "nombre",
	usu.apellido1 as "apellido1",
	usu.apellido2 as "apellido2",
	usu.nombre || ''
		||usu.apellido1 || ''
		|| usu.apellido2 as "nombreCompleto",
	usu.email as "email",
	usu.telefono as "telefono"	
FROM 
	tabla_usuario usu
WHERE 
	usu.fecha_baja is null 	
	AND usu.nif = '$id'
query;

		$vUsuario = array();		
		$res = &$mdb2ConDB->query($strSelect);
		if (PEAR::isError($res)) 
		{
			return null;
			//throw new RuntimeException ('Error consulta BD: '.$res->getMessage());
		}		
		$mdb2ConDB->disconnect();
				
		$vUsuario = $res->fetchAll();
		$numUsu = count($vUsuario);
		if ($numUsu == 1) return ($vUsuario[0]['usuario']);
    	
		return null;
}

/**
 * Función de autorización con usuario y pass. Recibe el identificador de usuario (DNI) y su password y devuelve estrutura de autorización o null
 * @param	string	$idUser		id del ususario, DNI en este ejemplo
 * @param	string	$password	Palabra de paso del usuario
 * @return	mixed	Devuelve un array asociativo con los datos del ususario o null si error
 */
function usuarioValidoUserPass($idUser, $password)
{	
	$id = strtoupper(trim($idUser));
	$password = md5($password);
	
	/*
		Preparamos un consulta que comprueba si el usuario (DNI del Cert. Dig)
		tiene acceso a la aplicación y cargue los datos del mismo en sesión
		Esta consulta es dependiente de la fuente de información (podría no ser
		una query SQL sino una invocación a un WS, de un fichero plano, etc...)
	*/
	$conf = ConfigFramework::getConfig();
	$g_dsn = $conf->getDSN('g_oracle');
	$options = array (
		'debug'       => 2,
		'portability' => MDB2_PORTABILITY_ALL,
	);
	
	$mdb2ConDB =& MDB2::connect($g_dsn, $options);
	if (PEAR::isError($mdb2ConDB))
	{	
		throw new RuntimeException ('Error acceso BD: '.$mdb2ConDB->getMessage());
		return null;
	}
	$mdb2ConDB->setFetchMode(MDB2_FETCHMODE_ASSOC);
	
	
$strSelect = <<<query
SELECT
	upper(usu.nif) as "nif",	
	usu.fecha_alta as "fechaAlta",
	usu.fecha_baja as "fechaBaja",
	usu.nombre as "nombre",
	usu.apellido1 as "apellido1",
	usu.apellido2 as "apellido2",
	usu.nombre || ''
		||usu.apellido1 || ''
		|| usu.apellido2 as "nombreCompleto",
	usu.email as "email",
	usu.telefono as "telefono"	
FROM 
	tabla_usuario usu
WHERE 
	usu.fecha_baja is null 	
	AND usu.nif = '$idUser'
	AND usu.password = '$password'
query;

		$vUsuario = array();
		$res = &$mdb2ConDB->query($strSelect);
		if (PEAR::isError($res)) 
		{
			return null;
			//throw new RuntimeException ('Error consulta BD: '.$res->getMessage());
		}		
		$mdb2ConDB->disconnect();

		$vUsuario = $res->fetchAll();

		$numUsu = count($vUsuario);
		if ($numUsu == 1) return ($vUsuario[0]['usuario']);
		return null;
}//Fin validaUserPass


//Array asociativo de opciones de valicación para la clase AUTHCD
$options = array (
	'redirect'=>	'include/valida/validacion_cd.php', // Redicrección al lugar de inicio de sesión y gestor de la validación
	'method'=>		2,	// indica si se permite 1) user/pwd, 2) Cert Dig o 3) ambos
	'https_all'=>	true, //False para seguir por HTTP y TRUE para forzar HTTPS 
	'cb_checkUserCert'=>'usuarioValidoCert', // callback para comprobar usuario con certificado
	'cb_checkUser'=>'usuarioValidoUserPass',	// callback para comprobar usuario con validación de usuario/contraseña
	//'cb_loginFunction'=>'loginFunction',	// callback para pintar formulario login	
	);
	
$msg = AuthCD::autenticate(ConfigFramework::getApplicationName(), $options);
if ($msg) 
{
	echo $msg;
}

?>
