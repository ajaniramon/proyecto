<?php
/**
 * Validacion de usuarios mediante certificados: comprueba en cada acceso si el usuario puede entrar
 * 
 * Es una clase estática. 
 *
 * @package	gvHIDRA
 */

require_once 'validacion_cd_base.php';

class validacion extends validacion_cd_base 
{	

	/**
	 * sustituye a metodo postLogin que normalmente está en contenedor Auth
	 */		
	static function postLogin($sess, $auth=null)
	{	
		if (!is_object($auth))
			return;
		
		$usuario = $auth->getUserName();
		$usuario = strtoupper(trim($usuario));
	
		// manejador de conexión
		$conf = ConfigFramework::getConfig();
		$g_dsn = $conf->getDSN('g_oracle');
		$options = array (
			'debug'       => 2,
			'portability' => MDB2_PORTABILITY_ALL,
		);
		
		$mdb2ConDB =& MDB2::connect($g_dsn, $options);
		if (PEAR::isError($mdb2ConDB))
		{
			return null;
			//throw new RuntimeException ('Error acceso BD: '.$mdb2ConDB->getMessage());
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

		$vUsuarios = array();		
		$res = &$mdb2ConDB->query($strSelect);

		if (PEAR::isError($res)) 
		{			
			throw new RuntimeException ('Error consulta BD: '.__FILE__.' '.__CLASS__.$res->getMessage());
		}		
		$mdb2ConDB->disconnect();
				
		$vUsuarios = $res->fetchAll();
		$VUsuario = $vUsuarios[0];
		
		/* Cargamos los datos en sesión */
		$sess['usuario']['nombre'] = $usuario;
		$sess['usuario']['user'] = $usuario;
		$sess['usuario']['nif']	= strtoupper($VUsuario['nif']);
		$sess['usuario']['nombreCompleto'] = $VUsuario['nombreCompleto'];
		$sess['usuario']['email'] = $VUsuario['email'];
		$sess['modulos'] = array();
		$sess['daplicacion'] = 'Nombre de mi aplicación';		
		return $sess;
	}//Fin postLogin

}

?>