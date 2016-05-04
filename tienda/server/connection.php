<?php

$archivo = fopen("../config/tienda.cfg","r") or die("Imposible abrir el archivo.");

$host = explode(":",fgets($archivo))[1] or die("El archvivo tienda.cfg no tiene el formato correcto.");
$username = explode(":",fgets($archivo))[1] or die("El archvivo tienda.cfg no tiene el formato correcto.");
$password = explode(":",fgets($archivo))[1] or die("El archvivo tienda.cfg no tiene el formato correcto.");
$database = explode(":",fgets($archivo))[1] or die("El archvivo tienda.cfg no tiene el formato correcto.");


$config = array("host"=>$host,"username"=>$username,"password"=>$password,"database"=>$database);

$connection = array("user" => trim($config["username"]) , "password" => trim($config["password"]));



?>