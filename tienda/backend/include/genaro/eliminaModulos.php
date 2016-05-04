<?php
	include ("include/tracear_php.php"); 

	//inserta_log('Inicio eliminaModulos.php');
	
	// Recogemos las variables pasadas por POST
	$nombreModulElim = $_POST['nombreModulElim'];
	$opcion = $_POST['opcion'];		
	
	//inserta_log($nombreModulElim);
	//inserta_log($opcion);	

	switch($opcion) {
		case 1:
			$salidaHijos = tieneHijos($nombreModulElim);
			$salidaPadres = tienePadres($nombreModulElim);
			
			//inserta_log('(1)-'.$salidaHijos.'<->'.$salidaPadres);
			
			// devolvemos los valores al contenedor por AJAX
			echo '<input id="valOcultoHijo" type = "hidden" value="'.$salidaHijos.'"/>';			
			echo '<input id="valOcultoPadre" type = "hidden" value="'.$salidaPadres.'"/>';	
			break;
		case 2:
			$salida = borraModulos($nombreModulElim);
				
			//inserta_log('(2)-'.$salida);
			
			echo '<input id="valOcultoEliminado" type = "hidden" value="'.$salida.'"/>';
			break;			
	}

	
	function tieneHijos($modulo)
	{			
		$contenido_fichero = file($modulo);
		$flag=0;
		
		foreach ($contenido_fichero as $linea){			
			if (strpos($linea, 'addSlave')){
				$flag=1;
			}
		}
		return $flag;		
	}

	function listaHijos($modulo)
	{
		$contenido_fichero = file($modulo);
	
		foreach ($contenido_fichero as $linea){
			if (strpos($linea, 'addSlave'))
			{				
				/*$subCadena = substr($linea,18);
				$metodoHijo = strstr($subCadena, "'", true);*/
				$metodoHijo = strObtenerCadena($linea,"'" );								
				$arrHijos[] = $metodoHijo;
			}			
		}
		return $arrHijos;
	}	

	function tienePadres($modulo)
	{
		$contenido_fichero = file($modulo);
		$flag=0;
	
		foreach ($contenido_fichero as $linea){
			if (strpos($linea, 'addMaster')){
				$flag=1;
			}
		}
		return $flag;
	}	
	
	//Devuelve la primera aparicion de una cadena dentro de dos separadores indicados.
	
	//Si no lo encuentra devuelve false;
	
	function strObtenerCadena($cadena,$separador){	
		$pos = strpos($cadena, $separador);	
	
		if($pos==false){	
			return false;	
		}	
		for($i=$pos+1,$cont=1;$i<strlen($cadena);$i++,$cont++){	
			$token = substr($cadena,$i,1);	
			if($token==$separador){	
				return substr($cadena,$pos+1,$cont-1);	
			}	
		}	
		return false;	
	}	
	
	
	function borraModulos($modulo)
	{	
		//inserta_log('Entrando a la función borraModulos con el modulo: '.$modulo);
		// recuperamos la ruta hasta el /actions
		//$rutaOLD=strstr($modulo, "actions", true);
		$ruta=strFiltrarRuta($modulo,'actions');
		
		//inserta_log('$rutaOLD: '.$rutaOLD);
		//inserta_log('$ruta: '.$ruta);
		
		// desmontamos la ruta
		$partes_ruta = pathinfo($modulo);		
		//inserta_log('$partes_ruta: '.implode(',', $partes_ruta));
		
		// recuperamos el nombre del fichero con la extensión
		$valor=$partes_ruta['dirname'];		
		//inserta_log('$valor: '.$valor);
		
		$moduloSinExt=basename($modulo, '.php');	
		//inserta_log('$moduloSinExt: '.$moduloSinExt);
		
		$rutaModulo=basename($valor);		
		//inserta_log('$rutaModulo: '.$rutaModulo);
		
		$hayHijos = tieneHijos($modulo);		
		//inserta_log('$hayHijos: '.$hayHijos);	
		
		// actions		
		// si tiene detalles(hijos), localizarlos y borrarlos
		if ($hayHijos == 0){			
			//no tiene detalle asociados, lo eliminamos solo al modulo
			if (unlink($modulo))
				$flag = 0; // OK
			else
				$flag = 1; // ERROR			
		}
		elseif ($hayHijos == 1){
			// aÃ±adimos a un array la lista de ficheros a eliminar.
			$filesToDelete= listaHijos($modulo);
			
			//inserta_log('$filesToDelete: '.implode(',', $filesToDelete));
			
			// Eliminamos al Maestro
			if (unlink($modulo))
				$flag = 0; // OK
			else
				$flag = 1; // ERROR
			
			// recorremos el array y eliminamos todos los ficheros
			foreach ($filesToDelete as $fichero){
				$cadenaFichero = $valor.'/'.$fichero.'.php';				
				//inserta_log('$cadenaFichero: '.$cadenaFichero);
				
				if (unlink($cadenaFichero))
					$flag = 0; // OK
				else
					$flag = 1; // ERROR		
			}		
		}			
		
		// Eliminamos el directorio si está vacio, si tiene dos elementos, estos son '.' y '..'
		$rutaDirActions = $ruta.'actions/'.$rutaModulo;
		$ficherosDirActions = scandir($rutaDirActions);
		
		//inserta_log('$rutaDirActions: '.$rutaDirActions);
		//inserta_log('$ficherosDirActions: '.implode(',',$ficherosDirActions));
		
		if (count($ficherosDirActions) == 2)
		{
			rmdir($rutaDirActions);
		}
				
		// carpeta plantillas, eliminar el modulo, no existe tpl para el detalle, solo para maestro
		$rutaPlantillas = $ruta.'plantillas/'.$rutaModulo.'/p_'.$moduloSinExt.'.tpl'; 
		
		//inserta_log('$rutaPlantillas: '.$rutaPlantillas);
		
		if (unlink($rutaPlantillas))
			$flag = 0; // OK
		else
			$flag = 1; // ERROR
		
		// Eliminamos el directorio si está vacio, si tiene dos elementos, estos son '.' y '..'
		$rutaDirPlantillas = $ruta.'plantillas/'.$rutaModulo;
		$ficherosDirPlantillas = scandir($rutaDirPlantillas);
		
		if (count($ficherosDirPlantillas) == 2)
		{
			rmdir($rutaDirPlantillas);
		}
		
		// carpeta views, eliminar el modulo, no existe tpl para el detalle, solo para maestro
		$rutaViews = $ruta.'views/'.$rutaModulo.'/p_'.$moduloSinExt.'.php';
		
		//inserta_log('$rutaViews: '.$rutaViews);
		
		if (unlink($rutaViews))
			$flag = 0; // OK
		else
			$flag = 1; // ERROR
		
		// Eliminamos el directorio si está vacio, si tiene dos elementos, estos son '.' y '..'
		$rutaDirViews = $ruta.'views/'.$rutaModulo;
		$ficherosDirViews = scandir($rutaDirViews);
		
		if (count($ficherosDirViews) == 2)
		{
			rmdir($rutaDirViews);
		}		
		
		//eliminamos del fichero de configuración "menuModulos.xml" la linea que apuntaba a la clase que hemos eliminado.
		
		//montamos las cadenas a eliminar
		$txtSalidaFichero = '';
		$ficheroXML = $ruta.'include/menuModulos.xml';
		/*
		$cadena = '<opcion titulo="'.$moduloSinExt.'" descripcion="Mantenimiento de '.$moduloSinExt.'" url="phrame.php?action='.$moduloSinExt.'__iniciarVentana"/>';
		$cadenaModulo = '<modulo titulo="'.$rutaModulo.'" imagen="menu/43.gif" descripcion="'.$rutaModulo.'"></modulo>';
		$salida = file($ficheroXML);
		
		//recorremos y eliminamos las cadenas		
		foreach ($salida as $num_linea => $linea) {		
			if (strpos($linea, $cadena)){		
				$cadResult = str_replace(htmlspecialchars($cadena), "", htmlspecialchars($linea));		
				$cadResultFinal = str_replace(htmlspecialchars($cadenaModulo), "", $cadResult);
				$txtSalidaFichero .= htmlspecialchars_decode($cadResultFinal);
			}
			else{						
				$txtSalidaFichero .= $linea;
			}
		}
				
		// generamos de nuevo el fichero sin la cadena que hemos eliminado
		$f = fopen($ficheroXML, 'w');
		fwrite($f, $txtSalidaFichero);
		fclose($f);
		*/

		//limpiamos el modulo de menuModulos.xml
		$xdoc = new DOMDocument('1.0', 'UTF-8');
		$xdoc->formatOutput = true;
		$xdoc->preserveWhiteSpace = false;
		$xdoc->load($ficheroXML);	
		$nodosOpcion = $xdoc->getElementsByTagName('opcion');		
		for ($i = 0; $i < $nodosOpcion->length; ++$i){
			$nodo = $nodosOpcion->item($i);
			if ($nodo->getAttribute('titulo') == $moduloSinExt){
				$parent = $nodosOpcion->item($i)->parentNode;
				if($parent!=null){
					$parent->removeChild($nodosOpcion->item($i));
					
					//si el padre esta sin hijos a de morir
					if($parent->hasChildNodes()==false){
						$parent->parentNode->removeChild($parent);
					}
				}
			}
		}
		$xdoc->save($ficheroXML);

		//include.php, eliminamos la entrada del fichero ***************************************************************		
		//$txtSalidaFichero = '';
		$ficheroSalida = $ruta.'include/include.php';
		$contenido_fichero = file($ficheroSalida);		
		$cadena = '(\''.$moduloSinExt.'\'';
		
		foreach ($contenido_fichero as $linea){
			if (strpos($linea, $cadena)){
				if (strpos($linea, '?>'))
					/*$txtSalidaFichero .= '?>';*/
					$salida_fichero[]= '?>';		
			}
			else
				//$txtSalidaFichero .= $linea;	
				$salida_fichero[]= $linea;
		}
		
		/* LIMPIAMOS EL ARRAY DE ESPACIOS EN BLANCO */
		
		foreach ($salida_fichero as $lineaAux){
			if (trim($lineaAux) != '')
				$salidaAux[]= $lineaAux;
		}		

		// generamos de nuevo el fichero sin la cadena que hemos eliminado
		/*$f = fopen($ficheroSalida, 'w');
		fwrite($f, $txtSalidaFichero);
		fclose($f);	*/
		file_put_contents($ficheroSalida, $salidaAux);	

		//limpiams los arrays empleados
		unset($salida_fichero);
		unset($salidaAux);
		
		//mappings.php eliminamos las entrada en el fichero ************************************************************	
		$txtSalidaFichero = '';
		$ficheroSalida = $ruta.'include/mappings.php';
		$contenido_fichero = file($ficheroSalida);
		$cadena = '(\''.$moduloSinExt.'__';
		
		//inserta_log('$contenido_fichero:'.$contenido_fichero);
		
		foreach ($contenido_fichero as $linea){			
			
			if (strpos($linea, $cadena))
			{
				if (strpos($linea, '}}?>'))
					/*$txtSalidaFichero .= '}}?>';*/
					$salida_fichero[] = '}}?>';
			}
			else
				//$txtSalidaFichero .= $linea;
				$salida_fichero[] = $linea;
		}
		
		/* LIMPIAMOS EL ARRAY DE ESPACIOS EN BLANCO */
		
		foreach ($salida_fichero as $lineaAux){
			if (trim($lineaAux) != '')
				$salidaAux[]= $lineaAux;
		}
		
		
		// generamos de nuevo el fichero sin la cadena que hemos eliminado
		/*$f = fopen($ficheroSalida, 'w');
		fwrite($f, $txtSalidaFichero);
		fclose($f);*/
		
		file_put_contents($ficheroSalida, $salidaAux);
		
		//limpiams los arrays empleados
		unset($salida_fichero);
		unset($salidaAux);		
		
		return $flag; 			
	}
	
	function strFiltrarRuta($ruta,$carpeta){
		$pos = strpos($ruta, $carpeta);
		if($pos==false){
			return false;
		}
		else{
			return substr($ruta, 0, $pos);
		}
	}	
?>
