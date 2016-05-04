<?php
/**
 * 
 * Clave envoltorio para un certificado digital de un usuario
 * @author gaspar
 *
 */
class UserCert
{
	/**
	 * Se inicializa con las variables SSL_* de la varible $_SERVER
	 */
	function __construct($serv) 
	{
		$this->vars = array();
		foreach ($serv as $i => $v) 
		{
			if (substr($i,0,4) == 'SSL_')
				$this->vars[$i] = $v;
		}
		
		$this->ca = null;
		$this->ident = null;
		
		//Comprobamos si se ha verificado al cliente con éxito
		if ($this->vars['SSL_CLIENT_VERIFY'] !='SUCCESS') return;

		//Accedemos a los datos del certificado cliente
		if (array_key_exists('SSL_CLIENT_I_DN_OU', $this->vars))//Vemos quien es el emisor
		{
			$emisor_OU = strtoupper($this->vars['SSL_CLIENT_I_DN_OU']);
			switch ($emisor_OU)
			{
				case 'PKIGVA':
				case 'PKIACCV':
					$this->ca = 'gva';
					//[SSL_CLIENT_S_DN] => /CN=PEPITO PEREZ GARCIA - NIF:12345678A/serialNumber=12345678A/GN=PEPITO/SN=PEREZ GARCIA/OU=Ciudadanos/O=Generalitat Valenciana/C=ES
					$items = explode('/', $this->vars['SSL_CLIENT_S_DN']);
					$this->ident = substr(strrchr($items[2], "="), 1);
				break;
				case 'DNIE':
					$this->ca = 'dnie';
					//[SSL_CLIENT_S_DN] => /C=ES/serialNumber=12345678A/SN=PEREZ/GN=PEPITO/CN=PEREZ GARCIA, PEPITO (AUTENTICACI\xC3\x93N)
					$items = explode('/', $this->vars['SSL_CLIENT_S_DN']);
					$this->ident = substr(strrchr($items[2], "="), 1);
					break;
				default:
					$this->ca = null;
					$this->ident = null;
				;
			}
		}
		
		
		
	}//Fin __construct
	
	/**
	 * 
	 * Devuelve el identificador del titular del certificado, normalmente un DNI
	 * Se pasa a mayúsculas para evitar problemas con las letras
	 */
	function getIdent()
	{
		return strtoupper($this->ident);
	}
}

?>