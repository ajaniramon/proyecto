<?php
include ("autorizacion.php");
session_unset();
$s1 = session_destroy();
if ($s1 == true) {
    http_response_code(200);
} else {
    http_response_code(400);
}
?>