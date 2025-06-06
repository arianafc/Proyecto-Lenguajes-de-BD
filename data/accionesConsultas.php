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

// Validar si la sesión está activa
if (!isset($_SESSION['id']) || !isset($_SESSION['id_carrito'])) {
    echo json_encode(["error" => "Necesitas iniciar sesión realizar acción."]);
    exit;
}
$id = $_SESSION['id'];
$action = $_POST['action'];

switch ($action) {



    case 'agregarConsulta':
        $id = $_SESSION['id'] ?? null;

        if (!$id) {
            echo json_encode(["error" => "Debes iniciar sesión para realizar una consulta."]);
            exit;
        }
        $tipo = "Consulta";
        $mensaje = $_POST['mensaje'];
        $sql = "BEGIN PKG_LEGADO.SP_AGREGAR_CONSULTA(:id, :tipo, :mensaje); END;";
        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ":id", $id, -1, SQLT_INT);
        oci_bind_by_name($stmt, ":tipo", $tipo);
        oci_bind_by_name($stmt, ":mensaje", $mensaje);



        if (!oci_execute($stmt)) {
            $error = oci_error($stmt);
            echo json_encode(["error" => "Error al ejecutar procedimiento", "detalle" => $error['message']]);
            exit;
        }

        oci_free_statement($stmt);
        oci_close($conn);

        echo json_encode(["success" => "Consulta realizada correctamente. Te contactaremos lo más pronto posible."]);
        break;

    case 'agregarCotizacion':
        $id = $_SESSION['id'] ?? null;

        if (!$id) {
            echo json_encode(["error" => "Debes iniciar sesión para enviar una cotización."]);
            exit;
        }
        $tipo = "Cotización";
        $mensaje = htmlspecialchars($_POST['mensaje'], ENT_QUOTES, 'UTF-8');
        $mensaje = mb_convert_encoding($mensaje, 'UTF-8', 'auto');
        $sql = "BEGIN PKG_LEGADO.SP_AGREGAR_CONSULTA(:id, :tipo, :mensaje); END;";
        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ":id", $id, -1, SQLT_INT);
        oci_bind_by_name($stmt, ":tipo", $tipo);
        oci_bind_by_name($stmt, ":mensaje", $mensaje);



        if (!oci_execute($stmt)) {
            $error = oci_error($stmt);
            echo json_encode(["error" => "Error al ejecutar procedimiento", "detalle" => $error['message']]);
            exit;
        }

        oci_free_statement($stmt);
        oci_close($conn);

        echo json_encode(["success" => "Cotización solicitada correctamente. Te contactaremos lo más pronto posible."]);
        break;

    case 'obtenerConsultasUsuario':
        $id = $_SESSION['id'] ?? null;

        if (!$id) {
            echo json_encode(["error" => "Debes iniciar sesión para ver tus consultas."]);
            exit;
        }

        $sql = "BEGIN PKG_LEGADO.SP_GET_CONSULTA_USUARIO(:cursor, :id); END;";
        $stmt = oci_parse($conn, $sql);
        $cursor = oci_new_cursor($conn);

        oci_bind_by_name($stmt, ":id", $id, -1, SQLT_INT);
        oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);

        if (!oci_execute($stmt)) {
            $error = oci_error($stmt);
            echo json_encode(["error" => "Error al ejecutar el procedimiento", "detalle" => $error['message']]);
            exit;
        }

        if (!oci_execute($cursor)) {
            $error = oci_error($cursor);
            echo json_encode(["error" => "Error al obtener resultados del cursor", "detalle" => $error['message']]);
            exit;
        }

        $consultas = [];
        while ($row = oci_fetch_assoc($cursor)) {
            $consultas[] = $row;
        }

        if (empty($consultas)) {
            echo json_encode(["mensaje" => "No tienes consultas registradas."]);
        } else {
            echo json_encode($consultas);
        }

        oci_free_statement($stmt);
        oci_free_statement($cursor);
        oci_close($conn);
        break;
        case 'obtenerConsultas':
            try {
                $stmt = oci_parse($conn, "BEGIN PKG_LEGADO.SP_GET_CONSULTAS(:cursor); END;");
                $cursor = oci_new_cursor($conn);
        
                oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);
        
                oci_execute($stmt);
                oci_execute($cursor);
        
                $consultas = [];
                while (($row = oci_fetch_assoc($cursor)) != false) {
                    $consultas[] = $row;
                }
        
                echo json_encode($consultas);
            } catch (Exception $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }
            break;
        
            case 'actualizarEstadoConsulta':
                try {
                    $idConsulta = $_POST['id'];
                    $accion = $_POST['accion']; // 1 = Contestada, cualquier otro = Pendiente
            
                    $stmt = oci_parse($conn, "BEGIN PKG_LEGADO.SP_CRUD_CONSULTAS(:accion, :id); END;");
                    oci_bind_by_name($stmt, ":accion", $accion);
                    oci_bind_by_name($stmt, ":id", $idConsulta);
            
                    oci_execute($stmt);
            
                    echo json_encode(['success' => true, 'mensaje' => 'Consulta actualizada correctamente.']);
                } catch (Exception $e) {
                    echo json_encode(['error' => $e->getMessage()]);
                }
                break;
            

    default:
        echo json_encode(["error" => "Acción no válida"]);
        break;
}


exit;
?>