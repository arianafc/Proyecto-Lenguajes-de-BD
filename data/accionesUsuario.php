<?php
require_once '../conexion.php';

error_reporting(E_ERROR); 
ini_set('display_errors', 0);
header('Content-Type: application/json');
ob_start();

$action = $_POST['action'] ?? null;

$response = ['success' => false, 'message' => 'Acción no válida'];

switch ($action) {
    case 'agregar':
        $nombre = $_POST['nombre'] ?? '';
        $apellido1 = $_POST['apellido1'] ?? '';
        $apellido2 = $_POST['apellido2'] ?? '';
        $email = $_POST['email'] ?? '';
        $id_estado = (int)($_POST['id_estado'] ?? 1); // Valor por defecto si no viene
        $username = $_POST['username'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';
        $id_rol = (int)($_POST['id_rol'] ?? 1); // Valor por defecto si no viene
    
        try {
            // Llamar SP_AGREGAR_USUARIO
            $stmt = oci_parse($conn, "BEGIN SP_AGREGAR_USUARIO(
                :p_nombre, :p_email, :p_estado, :p_apellido1, :p_apellido2, 
                :p_username, :p_contrasena, :p_rol, :p_id_usuario); END;");
    
            oci_bind_by_name($stmt, ':p_nombre', $nombre);
            oci_bind_by_name($stmt, ':p_email', $email);
            oci_bind_by_name($stmt, ':p_estado', $id_estado);
            oci_bind_by_name($stmt, ':p_apellido1', $apellido1);
            oci_bind_by_name($stmt, ':p_apellido2', $apellido2);
            oci_bind_by_name($stmt, ':p_username', $username);
            oci_bind_by_name($stmt, ':p_contrasena', $contrasena);
            oci_bind_by_name($stmt, ':p_rol', $id_rol);
            $id_usuario = null;
            oci_bind_by_name($stmt, ':p_id_usuario', $id_usuario, 10); // OUT param
    
            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                throw new Exception($e['message']);
            }
    
            // Llamar SP CREAR_CARRITO para ese nuevo usuario
            $stmtCarrito = oci_parse($conn, "BEGIN CREAR_CARRITO(:p_id_usuario); END;");
            oci_bind_by_name($stmtCarrito, ':p_id_usuario', $id_usuario);
            if (!oci_execute($stmtCarrito)) {
                $e = oci_error($stmtCarrito);
                throw new Exception($e['message']);
            }
    
            oci_commit($conn);
            $response = ['success' => true, 'message' => 'Usuario y carrito creados correctamente.'];
    
        } catch (Exception $e) {
            oci_rollback($conn);
            if (strpos($e->getMessage(), '-20001') !== false) {
                $response = ['success' => false, 'message' => 'El correo electrónico ya está registrado con otro usuario.'];
            } elseif (strpos($e->getMessage(), '-20002') !== false) {
                $response = ['success' => false, 'message' => 'El nombre de usuario ya está en uso.'];
            } else {
                $response = ['success' => false, 'message' => 'Error al agregar usuario: ' . $e->getMessage()];
            }
        }
        break;
    
        case 'modificar':
            $id_usuario = (int)$_POST['id_usuario'];
            $nombre = $_POST['nombre'];
            $apellido1 = $_POST['apellido1'];
            $apellido2 = $_POST['apellido2'];
            $email = $_POST['email'];
            $id_estado = (int)$_POST['id_estado'];
            $username = $_POST['username'];
            $contrasena = $_POST['contrasena'];
            $id_rol = (int)$_POST['id_rol'];
    
            try {
                $stmt = oci_parse($conn, "BEGIN SP_EDITAR_USUARIO(
                    :p_id_usuario, :p_nombre, :p_apellido1, :p_apellido2,
                    :p_email, :p_estado, :p_username, :p_contrasena, :p_rol
                ); END;");
    
                oci_bind_by_name($stmt, ':p_id_usuario', $id_usuario);
                oci_bind_by_name($stmt, ':p_nombre', $nombre);
                oci_bind_by_name($stmt, ':p_apellido1', $apellido1);
                oci_bind_by_name($stmt, ':p_apellido2', $apellido2);
                oci_bind_by_name($stmt, ':p_email', $email);
                oci_bind_by_name($stmt, ':p_estado', $id_estado);
                oci_bind_by_name($stmt, ':p_username', $username);
                oci_bind_by_name($stmt, ':p_contrasena', $contrasena);
                oci_bind_by_name($stmt, ':p_rol', $id_rol);
    
                if (!oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    throw new Exception($e['message']);
                }
    
                oci_commit($conn);
                $response = ["success" => true, "message" => "Usuario modificado correctamente."];
    
            } catch (Exception $e) {
                oci_rollback($conn);
                $response = ["success" => false, "message" => "Error al modificar: " . $e->getMessage()];
            }
            break;

        case 'cambiar_estado':
            $id_usuario = (int)$_POST['id_usuario'];
            $nuevo_estado = (int)$_POST['nuevo_estado'];
        
            try {
                $stmt = oci_parse($conn, "BEGIN SP_CAMBIAR_ESTADO_USUARIO(:p_id_usuario, :p_nuevo_estado); END;");
                oci_bind_by_name($stmt, ':p_id_usuario', $id_usuario);
                oci_bind_by_name($stmt, ':p_nuevo_estado', $nuevo_estado);
        
                if (!oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    throw new Exception($e['message']);
                }
        
                oci_commit($conn);
                $response = ["success" => true, "message" => "Estado del usuario actualizado."];
        
            } catch (Exception $e) {
                oci_rollback($conn);
                $response = ["success" => false, "message" => "Error al cambiar estado: " . $e->getMessage()];
            }
            break;
        

    default:
        $response = ['success' => false, 'message' => 'Acción no reconocida'];
}

echo json_encode($response);
?>