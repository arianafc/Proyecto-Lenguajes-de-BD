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
    case 'cambiarContrasena':
        if (!isset($_POST['username']) || !isset($_POST['contrasena'])) {
            echo json_encode(["error" => "Faltan datos para cambiar la contraseña."]);
            exit;
        }
    
        $username = $_POST['username'];
        $nuevaContrasena = $_POST['contrasena'];
    
        try {
            $sql = "BEGIN PKG_LEGADO.SP_CAMBIAR_CONTRASENA(:username, :nuevaContrasena); END;";
            $stmt = oci_parse($conn, $sql);
    
            oci_bind_by_name($stmt, ":username", $username);
            oci_bind_by_name($stmt, ":nuevaContrasena", $nuevaContrasena);
    
            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                throw new Exception("Error al ejecutar procedimiento: " . $e['message']);
            }
    
            echo json_encode(["mensaje" => "Contraseña actualizada correctamente."]);
    
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    
        break;
    
    case 'recuperarUsuario':
        if (!isset($_POST['email']) || !isset($_POST['nombre'])) {
            echo json_encode(["error" => "Faltan datos para recuperar el usuario"]);
            exit;
        }

        $email = $_POST['email'];
        $nombre = $_POST['nombre'];

        try {
            $sql = "BEGIN :resultado := PKG_LEGADO.FN_RECUPERAR_USUARIO(:email, :nombre); END;";
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
        case 'cambiarContrasena':
            if (!isset($_POST['username']) || !isset($_POST['nuevaContrasena'])) {
                echo json_encode(["error" => "Faltan datos para cambiar la contraseña"]);
                exit;
            }
        
            $username = $_POST['username'];
            $nuevaContrasena = $_POST['nuevaContrasena'];
        
            try {
                $sql = "BEGIN SP_CAMBIAR_CONTRASENA(:username, :nuevaContrasena); END;";
                $stmt = oci_parse($conn, $sql);
        
                oci_bind_by_name($stmt, ":username", $username);
                oci_bind_by_name($stmt, ":nuevaContrasena", $nuevaContrasena);
        
                if (!oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    throw new Exception("Error al ejecutar el procedimiento: " . $e['message']);
                }
        
                echo json_encode(["mensaje" => "Contraseña actualizada correctamente"]);
        
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