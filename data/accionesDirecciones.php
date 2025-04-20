<?php
session_start();
require '../conexion.php';
header('Content-Type: application/json; charset=utf-8');

$action = $_POST['action'] ?? '';
$id = $_SESSION['id'] ?? null;

switch ($action) {
    case 'getProvincias':
        $stmt = oci_parse($conn, "BEGIN PKG_LEGADO.SP_GET_PROVINCIAS(:cursor); END;");
        $cursor = oci_new_cursor($conn);
        oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);
        oci_execute($stmt);
        oci_execute($cursor);
        $data = [];
        while (($row = oci_fetch_assoc($cursor)) != false) {
            $data[] = $row;
        }
        echo json_encode($data);
        break;

    case 'getCantones':
        $provincia = $_POST['idProvincia'];
        $stmt = oci_parse($conn, "BEGIN PKG_LEGADO.SP_GET_CANTONES(:cursor, :provincia); END;");
        $cursor = oci_new_cursor($conn);
        oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);
        oci_bind_by_name($stmt, ":provincia", $provincia);
        oci_execute($stmt);
        oci_execute($cursor);
        $data = [];
        while (($row = oci_fetch_assoc($cursor)) != false) {
            $data[] = $row;
        }
        echo json_encode($data);
        break;

    case 'getDistritos':
        $canton = $_POST['idCanton'];
        $stmt = oci_parse($conn, "BEGIN PKG_LEGADO.SP_GET_DISTRITOS(:cursor, :canton); END;");
        $cursor = oci_new_cursor($conn);
        oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);
        oci_bind_by_name($stmt, ":canton", $canton);
        oci_execute($stmt);
        oci_execute($cursor);
        $data = [];
        while (($row = oci_fetch_assoc($cursor)) != false) {
            $data[] = $row;
        }
        echo json_encode($data);
        break;

    case 'agregarDireccion':
        if (!$id) {
            echo json_encode(["error" => "Sesión no iniciada"]);
            exit;
        }

        $direccion = $_POST['direccion'];
        $distrito = $_POST['distrito'];

        $stmt = oci_parse($conn, "BEGIN PKG_LEGADO.SP_AGREGAR_DIRECCION(:id, :direccion, :distrito); END;");
        oci_bind_by_name($stmt, ":id", $id);
        oci_bind_by_name($stmt, ":direccion", $direccion);
        oci_bind_by_name($stmt, ":distrito", $distrito);

        if (oci_execute($stmt)) {
            echo json_encode(["success" => "Dirección agregada correctamente."]);
        } else {
            $error = oci_error($stmt);
            echo json_encode(["error" => $error['message']]);
        }
        break;

        case 'editarDireccion':
            $idDireccion = $_POST['idDireccion'];
            $idDistrito = $_POST['idDistrito'];
            $direccionExacta = $_POST['direccion'];
        
            $sql = "BEGIN PKG_LEGADO.SP_EDITAR_DIRECCION(:idDireccion, :direccion, :idDistrito); END;";
            $stmt = oci_parse($conn, $sql);
        
            oci_bind_by_name($stmt, ":idDireccion", $idDireccion);
            oci_bind_by_name($stmt, ":direccion", $direccionExacta);
            oci_bind_by_name($stmt, ":idDistrito", $idDistrito);
        
            if (oci_execute($stmt)) {
                echo json_encode(["success" => true, "message" => "Dirección actualizada correctamente"]);
            } else {
                $e = oci_error($stmt);
                echo json_encode(["success" => false, "message" => $e['message']]);
            }
        
            oci_free_statement($stmt);
            break;
            case 'getDireccion':
                $idDireccion = $_POST['idDireccion'];
            
                $sql = "BEGIN PKG_LEGADO.SP_GET_DIRECCION(:cursor, :idDireccion); END;";
                $stmt = oci_parse($conn, $sql);
            
                $cursor = oci_new_cursor($conn);
                oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);
                oci_bind_by_name($stmt, ":idDireccion", $idDireccion);
            
                if (oci_execute($stmt)) {
                    oci_execute($cursor);
                    $datos = [];
                    while ($row = oci_fetch_assoc($cursor)) {
                        $datos[] = $row;
                    }
                    echo json_encode($datos[0]); // Se espera un solo resultado
                } else {
                    $e = oci_error($stmt);
                    echo json_encode(["success" => false, "message" => $e['message']]);
                }
            
                oci_free_statement($stmt);
                oci_free_statement($cursor);
                break;
                case 'eliminarDireccion':
                    $idDireccion = $_POST['idDireccion'];
                
                    if (!$idDireccion) {
                        echo json_encode(["success" => false, "message" => "ID de dirección no proporcionado"]);
                        exit;
                    }
                
                    $sql = "BEGIN PKG_LEGADO.SP_ELIMINAR_DIRECCION(:idDireccion); END;";
                    $stmt = oci_parse($conn, $sql);
                
                    oci_bind_by_name($stmt, ":idDireccion", $idDireccion);
                
                    if (oci_execute($stmt)) {
                        echo json_encode(["success" => true, "message" => "Dirección eliminada correctamente"]);
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
