<?php
session_start();
if (!isset($_SESSION['logged']) || $_SESSION['logged'] == false) {
    http_response_code(403);
    exit();
}else{
	if ($_SERVER['REQUEST_URI'] == "/backend.php") {
		if (!isset($_SESSION['empleado']) || $_SESSION['empleado'] == "false") {
			http_response_code(403);
			exit();
		}
	}else{
		http_response_code(200);

	}
}
?>
