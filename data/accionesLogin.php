<?php

session_start();
require '../conexion.php';


error_reporting(E_ERROR); 
ini_set('display_errors', 0);
header('Content-Type: application/json');
ob_start();

if (!isset($_POST['action'])) {
    echo json_encode(["error" => "No se recibió ninguna acción"]);
    exit;
}

$action = $_POST['action'];
switch ($action) {
    case 'recuperarUsuario':
        if (!isset($_POST['email']) || !isset($_POST['nombre'])) {
            echo json_encode(["error" => "Faltan datos para recuperar el usuario"]);
            exit;
        }

        $email = $_POST['email'];
        $nombre = $_POST['nombre'];

        try {
            $sql = "BEGIN :resultado := FN_RECUPERAR_USUARIO(:email, :nombre); END;";
            $stmt = oci_parse($conn, $sql);

            // Variables de salida y entrada
            oci_bind_by_name($stmt, ":resultado", $resultado, 200);
            oci_bind_by_name($stmt, ":email", $email);
            oci_bind_by_name($stmt, ":nombre", $nombre);

            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                throw new Exception("Error al ejecutar la función: " . $e['message']);
            }

            echo json_encode(["mensaje" => $resultado]);

        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }

        break;


    default:
        echo json_encode(["error" => "Acción no válida"]);
        break;
}


exit;
?>