<?php
// Configuración de la conexión
$db_host = 'localhost'; // Host de la base de datos
$db_port = '1521';      // Puerto de Oracle (por defecto es 1521)
$db_service_name = 'ORCL'; // Nombre del servicio de Oracle
$db_user = 'admin';   // Usuario de la base de datos
$db_password = 'admin'; // Contraseña del usuario

// Cadena de conexión
$connection_string = "//$db_host:$db_port/$db_service_name";

// Intenta conectar
$conn = oci_connect($db_user, $db_password, $connection_string);

if (!$conn) {
    $error = oci_error();
    die("Error al conectar a Oracle: " . $error['message']);
} 
?>