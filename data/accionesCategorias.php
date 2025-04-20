<?php
require_once '../conexion.php';

error_reporting(E_ERROR); 
ini_set('display_errors', 0);
header('Content-Type: application/json');
ob_start();

$action = $_POST['action'] ?? null;

$response = ['success' => false, 'message' => 'Acción no válida'];

switch ($action) {
    case 'obtenerCategorias':
        try {
            $stmt = oci_parse($conn, "BEGIN PKG_LEGADO.SP_GET_CATEGORIAS(:datos); END;");
            
            $cursor = oci_new_cursor($conn);
            oci_bind_by_name($stmt, ":datos", $cursor, -1, OCI_B_CURSOR);
            
            oci_execute($stmt);
            oci_execute($cursor);
            
            $categorias = [];
            while (($row = oci_fetch_assoc($cursor)) != false) {
                $categorias[] = $row;
            }
        
            echo json_encode($categorias);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

          // Crear nueva categoría
    case 'crearCategoria':
        try {
            $descripcion = $_POST['descripcion'];
            $accion = 1; // Crear

            $stmt = oci_parse($conn, "BEGIN PKG_LEGADO.SP_CRUD_CATEGORIAS(:desc, NULL, :accion); END;");
            oci_bind_by_name($stmt, ":desc", $descripcion);
            oci_bind_by_name($stmt, ":accion", $accion);

            oci_execute($stmt);
            echo json_encode(['success' => true, 'mensaje' => 'Categoría creada']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;
        case 'actualizarCategoria':
        try {
            $idCategoria = $_POST['id'];
            $descripcion = $_POST['descripcion'];
            $accion = 2; // Actualizar

            $stmt = oci_parse($conn, "BEGIN PKG_LEGADO.SP_CRUD_CATEGORIAS(:desc, :id, :accion); END;");
            oci_bind_by_name($stmt, ":desc", $descripcion);
            oci_bind_by_name($stmt, ":id", $idCategoria);
            oci_bind_by_name($stmt, ":accion", $accion);

            oci_execute($stmt);
            echo json_encode(['success' => true, 'mensaje' => 'Categoría actualizada']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

        case 'desactivarCategoria':
            try {
                $idCategoria = $_POST['id'];
                $accion = 3; // Desactivar
    
                $stmt = oci_parse($conn, "BEGIN PKG_LEGADO.SP_CRUD_CATEGORIAS(NULL, :id, :accion); END;");
                oci_bind_by_name($stmt, ":id", $idCategoria);
                oci_bind_by_name($stmt, ":accion", $accion);
    
                oci_execute($stmt);
                echo json_encode(['success' => true, 'mensaje' => 'Categoría desactivada']);
            } catch (Exception $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }
            break;

            case 'activarCategoria':
                try {
                    $idCategoria = $_POST['id'];
                    $accion = 4; // Activar 
        
                    $stmt = oci_parse($conn, "BEGIN PKG_LEGADO.SP_CRUD_CATEGORIAS(NULL, :id, :accion); END;");
                    oci_bind_by_name($stmt, ":id", $idCategoria);
                    oci_bind_by_name($stmt, ":accion", $accion);
        
                    oci_execute($stmt);
                    echo json_encode(['success' => true, 'mensaje' => 'Categoría activada']);
                } catch (Exception $e) {
                    echo json_encode(['error' => $e->getMessage()]);
                }
                break;

    default:
        $response = ['success' => false, 'message' => 'Acción no reconocida'];
}

?>