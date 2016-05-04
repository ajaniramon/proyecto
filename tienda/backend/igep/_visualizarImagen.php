<?php
//Este fichero visualiza una imagen situada en un PATH del servidor
//Se utiliza en el CWImagen para poder visualizar cualquier imagen

	$nombreFichero = $_REQUEST['fichero'];
	if(!file_exists($nombreFichero))
	die;

	//Vector de tipos MIME
	$mime_types = array
	(
		'txt' => 'text/plain',
		'htm' => 'text/html',
		'html' => 'text/html',
		'php' => 'text/html',
		'css' => 'text/css',
		'js' => 'application/javascript',
		'json' => 'application/json',
		'xml' => 'application/xml',
		'swf' => 'application/x-shockwave-flash',
		'flv' => 'video/x-flv',

		// images
		'png' => 'image/png',
		'jpe' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpg' => 'image/jpeg',
		'gif' => 'image/gif',
		'bmp' => 'image/bmp',
		'ico' => 'image/vnd.microsoft.icon',
		'tiff' => 'image/tiff',
		'tif' => 'image/tiff',
		'svg' => 'image/svg+xml',
		'svgz' => 'image/svg+xml',

		// archives
		'zip' => 'application/zip',
		'rar' => 'application/x-rar-compressed',
		'exe' => 'application/x-msdownload',
		'msi' => 'application/x-msdownload',
		'cab' => 'application/vnd.ms-cab-compressed',

		// audio/video
		'mp3' => 'audio/mpeg',
		'qt' => 'video/quicktime',
		'mov' => 'video/quicktime',

		// adobe
		'pdf' => 'application/pdf',
		'psd' => 'image/vnd.adobe.photoshop',
		'ai' => 'application/postscript',
		'eps' => 'application/postscript',
		'ps' => 'application/postscript',

		// ms office
		'doc' => 'application/msword',
		'docx' => 'application/msword',
		'rtf' => 'application/rtf',
		'xls' => 'application/vnd.ms-excel',
		'xlsx' => 'application/vnd.ms-excel',
		'ppt' => 'application/vnd.ms-powerpoint',
		'pptx' => 'application/vnd.ms-powerpoint',

		// open office
		'odt' => 'application/vnd.oasis.opendocument.text',
		'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
	);

	//Extensi�n del fichero
	$ext = trim(strtolower(array_pop(explode('.',$nombreFichero))));
	$tipo = null;

	//Si la extensi�n se contempla en nuestro vector de tipos...
	if (array_key_exists($ext, $mime_types))
	{
		$tipo = $mime_types[$ext];
	}
	elseif (function_exists('finfo_open')) //Si no est� y existe finfo_open
	{
		$finfo = finfo_open(FILEINFO_MIME);
		$mimetype = finfo_file($finfo, $nombreFichero);
		finfo_close($finfo);
		$tipo = $mimetype;
	}
	elseif(function_exists('mime_content_type'))//si no probamos con mime_content_type
	{
		$tipo = mime_content_type($nombreFichero);
	}
	else //En cualquier otor caso, lo servimos como binario...
	{
		$tipo = 'application/octet-stream';
	}


	print_r($tipo);
	ob_clean();
	if(strpos($tipo,'image')!==false)//S�lo servimos im�genes...
	{
		$gestor = fopen($nombreFichero, 'r');
		$contenido = fread($gestor, filesize($nombreFichero));
		echo $contenido;
	}
	else
		die;

?>