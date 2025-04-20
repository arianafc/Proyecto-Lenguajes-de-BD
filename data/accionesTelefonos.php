<?php
session_start();
require '../conexion.php';
header('Content-Type: application/json; charset=utf-8');

$action = $_POST['action'] ?? '';
$id = $_SESSION['id'] ?? null;

switch ($action) {
    case 'getTelefonos':
        $sql = "BEGIN PKG_LEGADO.SP_GET_TELEFONOS_USUARIO(:cursor, :id); END;";
        $stmt = oci_parse($conn, $sql);
    
        $cursor = oci_new_cursor($conn);
        oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);
        oci_bind_by_name($stmt, ":id", $id);
    
        if (oci_execute($stmt)) {
            oci_execute($cursor);
            $telefonos = [];
            while ($row = oci_fetch_assoc($cursor)) {
                $telefonos[] = $row;
            }
            echo json_encode($telefonos);
        } else {
            $e = oci_error($stmt);
            echo json_encode(["success" => false, "message" => $e['message']]);
        }
    
        oci_free_statement($stmt);
        oci_free_statement($cursor);
        break;
    
    
    case 'crudTelefono':
        $accion = $_POST['accion']; // 1 = insertar, 2 = actualizar, 3 = eliminar
        $telefono = $_POST['telefono'] ?? null;
        $idUsuario = $_SESSION['id'];
        $idTelefono = $_POST['idTelefono'] ?? null;
    
        $sql = "BEGIN PKG_LEGADO.SP_CRUD_TELEFONO(:telefono, :idUsuario, :accion, :idTelefono); END;";
        $stmt = oci_parse($conn, $sql);
    
        oci_bind_by_name($stmt, ":telefono", $telefono);
        oci_bind_by_name($stmt, ":idUsuario", $idUsuario);
        oci_bind_by_name($stmt, ":accion", $accion);
        oci_bind_by_name($stmt, ":idTelefono", $idTelefono);
    
        if (oci_execute($stmt)) {
            if ($accion == 1) {
                $mensaje = "Teléfono agregado correctamente";
            } elseif ($accion == 2) {
                $mensaje = "Teléfono actualizado correctamente";
            } elseif ($accion == 3) {
                $mensaje = "Teléfono eliminado correctamente";
            } else {
                $mensaje = "Acción realizada";
            }
            
            echo json_encode(["success" => true, "message" => $mensaje]);
            
        } else {
            $e = oci_error($stmt);
            echo json_encode(["success" => false, "message" => $e['message']]);
        }
    
        oci_free_statement($stmt);
        break;
          
    default:
        echo json_encode(["error" => "Acción no válida"]);
        break;
}
