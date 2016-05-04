<?php
session_start();

$session = new stdClass();

$session->dni = $_SESSION['dni'];
$session->email = $_SESSION['correo'];
$session->nombre = $_SESSION['nombre'];
$session->apellido = $_SESSION['apellido'];
$session->empleado = $_SESSION['empleado'];

echo json_encode($session);
?>
