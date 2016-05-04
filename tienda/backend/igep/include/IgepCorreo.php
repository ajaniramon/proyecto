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
 * correo es una clase que permite enviar un correo, con o sin anexos, a una lista de usuarios  
 * 
 * @version	$Id: IgepCorreo.php,v 1.30 2010-02-23 16:46:00 gaspar Exp $ 
 * @package	gvHIDRA
 */
include_once('Mail.php');
include_once('Mail/mime.php');


class IgepCorreo {
	
	protected static $mailQueue = array();

	/**
	 * Función para enviar correos sin ficheros anexados
	 * @param string 	$from			Dirección de correo del usuario que envia
	 * @param string	$to				Contiene un array con los destinatarios
	 * @param string 	$subject		Asunto del mensaje.
	 * @param string 	$body			Cuerpo (texto) del mensaje.
	 * @param string	$responder_a	Contiene la dirección de correo del usuario a responder
	*/
	static public function sinAnexo($from,$to,$subject,$body,$responder_a)
	{
		IgepDebug::setDebug(DEBUG_IGEP, 'Enviando correo a '.count($to).' usuario(s)');

		//Formamos las cabeceras:
		$headers['From']    = $from;
		$headers['Reply-to'] = $responder_a;
		$headers['Subject'] = $subject;
		
		// Registramos metodo para enviar cola, cuando finalice la peticion 
		if (empty(self::$mailQueue))
			register_shutdown_function(array(__CLASS__, 'sendMailsInQueue'), getcwd());
				
		foreach ($to as $email) {
		 	$headers['To']=$email;
			self::$mailQueue[] = array($email,$headers,$body);
		}
		return true;
	}

	/**
	 * Envia todos los correos en la cola, y vacia la cola
	 */
	static public function sendMailsInQueue($dir) {
		if (!chdir($dir))
			IgepDebug::setDebug(ERROR, 'No se puede cambiar directorio de trabajo a '.$dir);
		IgepDebug::setDebug(DEBUG_IGEP, 'Enviando cola con '.count(self::$mailQueue).' correo(s)');
		
		//Recogemos el servidor de SMTP
		$conf = ConfigFramework::getConfig();
		$server = $conf->getSMTPServer();
		
		//Por compatibilidad
		if(empty($server))
			$params= array('host'=>'smtp.gva.es');
		else
			$params= $server;
		
		$mail_object = Mail::factory('smtp', $params);
		foreach (self::$mailQueue as $mail) {
			$result = $mail_object->send($mail[0], $mail[1], $mail[2]);
			if (!$result || !($result === TRUE))
				IgepDebug::setDebug(WARNING, 'Ha Fallado el envío de correo a "'.$mail[1]['To'].'" con asunto '.$mail[1]['Subject'].'"');
		}
		self::$mailQueue = array();
	}
	
	/**Función para enviar correos con ficheros anexados
	* from		 	--> Contiene la dirección de correo del usuario que envia
	* to        	--> Contiene un array con los destinatarios
	* subject   	--> Asunto del mensaje.
	* msg       	--> Texto del mensaje.
	* responder_a 	--> Contiene la dirección de correo del usuario a responder
	* tmp_fich  	--> Nombre del fichero temporal anexo a enviar en el mensaje.
	* tipofich  	--> Tipo de fichero
	* nom_fich  	--> Nombre del fichero anexo a enviar en el mensaje
	*/

	/*Se usa el paquete mail_mime (http://pear.php.net/package-info.php?pacid=21):
	*  - mime.php: Create mime email, with html, attachments, embedded images etc.
	*  - mimePart.php: Advanced method of creating mime messages.
	*  - mimeDecode.php - Decodes mime messages to a usable structure.
	*  - xmail.dtd: An XML DTD to acompany the getXML() method of the decoding class.
	*  - xmail.xsl: An XSLT stylesheet to transform the output of the getXML() method back to an email
	*/
	static public function conAnexo($from,$to,$subject,$msg,$responder_a,$tmp_fich,$tipo_fich,$nom_fich)
	{
		////////////////////// NO ESTÁ PROBADA ////////////////////////////
		IgepDebug::setDebug(DEBUG_IGEP, 'Enviando correo con anexo a '.count($to).' usuario(s)');		
		
	    //Cabeceras:
	    $hdrs = array('From'=>$from,'Subject' => $subject,'Reply-to'=> $responder_a);
	
	    //Creamos el objeto de tipo Mail_mime:
	    $mime = new Mail_mime(SALTO_LINEA);
	
	    if (!$mime->addAttachment($tmp_fich,$tipo_fich,$nom_fich)) $msg.= SALTO_LINEA." No ha podido anexarse el fichero";
		$mime->setTXTBody($msg);
	    $body = $mime->get();
	    $hdrs = $mime->headers($hdrs);
		
		//Recogemos el servidor de SMTP
		$conf = ConfigFramework::getConfig();
		$server = $conf->getSMTPServer();
		
		//Por compatibilidad
		if(empty($server))
			$params= array('host'=>'smtp.gva.es');	
		else
			$params= $server;	
	    
	    // Creamos el objeto mail usando el método Mail::factory
	    $mail_object =& Mail::factory('smtp', $params);
	    
	    //Mandamos el mensaje a cada destinatario:
		$resultado=TRUE;
		while (list($clave, $valor)=each($to)) {
			$result = $mail_object->send($valor, $hdrs, $body);
			if (!$result || !($result === TRUE))
				$resultado=FALSE;
		}
		return $resultado;
	}

}	 //Fin de la Clase correo
?>