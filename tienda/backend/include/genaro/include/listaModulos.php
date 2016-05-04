<?php

$rutaFichero=$_SERVER['SCRIPT_FILENAME'];
/*
// recuperamos la ruta hasta el /include
$rutaBase=strstr($rutaFichero, "include", true);*/

$path1 = pathinfo($rutaFichero);
$path2 = pathinfo($path1['dirname']);
$path3 = pathinfo($path2['dirname']);
$rutaBase = $path3['dirname'];//Recuperamos la ruta donde se encuentra la carpeta include, en la cual está Genaro

$dir = $rutaBase.'/actions';

echo $dir;

$arrResult = find_all_files($dir);

function find_all_files($dir){

	$root = scandir($dir);

	foreach($root as $value){
		if($value === '.' || $value === '..'|| $value == 'index.html'|| $value == 'index.xml'|| $value == 'AppMainWindow.php') {
			continue;
		}

		if(is_file("$dir/$value")) {
			$result[]="$dir/$value";
			continue;
		}

		foreach(find_all_files("$dir/$value") as $value){
			$result[]=$value;
		}
	}
	return $result;
}

foreach($arrResult as $file)
{
	echo "<option value='$file'>";
	$valor = substr(strrchr($file, "/"), 1);
	echo substr($valor, 0,-4);
	echo "</option>";
}
?>