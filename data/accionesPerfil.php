<?php

session_start();
require '../conexion.php';
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
if (!isset($_POST['action'])) {
    echo json_encode(["error" => "No se recibió ninguna acción"]);
    exit;
}

$id = $_SESSION['id'];
$action = $_POST['action'];

switch ($action) {

    
    case 'obtenerPedidos':

        if (!isset($_SESSION['id'], $_SESSION['id_carrito'])) {
            echo json_encode(["error" => "Usuario no autenticado"]);
            exit;
        }
    
        $idUsuario = $_SESSION['id'];
    
        // 1. Verificar si el usuario tiene pedidos con FN_CONTAR_PEDIDOS
        $sqlContar = "BEGIN :resultado := PKG_LEGADO.FN_CONTAR_PEDIDOS(:idUsuario); END;";
        $stmtContar = oci_parse($conn, $sqlContar);
        $cantidadPedidos = 0;
    
        oci_bind_by_name($stmtContar, ":idUsuario", $idUsuario, -1, SQLT_INT);
        oci_bind_by_name($stmtContar, ":resultado", $cantidadPedidos, -1, SQLT_INT);
    
        if (!oci_execute($stmtContar)) {
            $error = oci_error($stmtContar);
            die(json_encode(["error" => "Error al contar pedidos", "detalle" => $error['message']]));
        }
    
        oci_free_statement($stmtContar);
    
      
        if ($cantidadPedidos == 0) {
            echo json_encode(["tienePedidos" => false, "mensaje" => "El usuario no tiene pedidos existentes"]);
            oci_close($conn);
            break;
        }
    
    
        $sql = "BEGIN PKG_LEGADO.SP_GET_PEDIDOS_USUARIO(:cursor, :idUsuario); END;";
        $stmt = oci_parse($conn, $sql);
        $cursor = oci_new_cursor($conn);
    
        oci_bind_by_name($stmt, ":idUsuario", $idUsuario, -1, SQLT_INT);
        oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);
    
        if (!oci_execute($stmt)) {
            $error = oci_error($stmt);
            die(json_encode(["error" => "Error al ejecutar procedimiento", "detalle" => $error['message']]));
        }
    
        if (!oci_execute($cursor)) {
            $error = oci_error($cursor);
            die(json_encode(["error" => "Error al obtener datos del cursor", "detalle" => $error['message']]));
        }
    
        $pedidos = [];
        while ($row = oci_fetch_assoc($cursor)) {
            $pedidos[] = $row;
        }
    
        echo json_encode([
            "tienePedidos" => true,
            "cantidad" => $cantidadPedidos,
            "pedidos" => $pedidos
        ]);
    
        oci_free_statement($stmt);
        oci_free_statement($cursor);
        oci_close($conn);
        break;
    
        case 'verDetallePedido':
            if (!isset($_POST['idPedido'])) {
                echo json_encode(["error" => "ID de pedido no proporcionado"]);
                exit;
            }
        
            $idPedido = $_POST['idPedido'];
        
            $sql = "BEGIN PKG_LEGADO.SP_GET_PEDIDOS_DETALLES(:cursor, :idPedido); END;";
            $stmt = oci_parse($conn, $sql);
            $cursor = oci_new_cursor($conn);
        
            oci_bind_by_name($stmt, ":idPedido", $idPedido, -1, SQLT_INT);
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
        
            $detalles = [];
            while ($row = oci_fetch_assoc($cursor)) {
                $detalles[] = $row;
            }
        
            echo json_encode($detalles);
        
            oci_free_statement($stmt);
            oci_free_statement($cursor);
            oci_close($conn);
            break;
        
    case 'editar':
        if (!isset($_POST['idArticulo'])) {
            echo json_encode(["error" => "Datos incompletos"]);
            exit;
        }

        $idArticulo = intval($_POST['idArticulo']);

        if (!isset($_SESSION['id'], $_SESSION['id_carrito'])) {
            echo json_encode(["error" => "Usuario no autenticado"]);
            exit;
        }

        $idCarrito = $_SESSION['id_carrito'];
        $cantidad = $_POST['cantidad'];
        $sql = "BEGIN PKG_CARRITO.SP_EDITAR_ARTICULO_CARRITO (:idArticulo, :cantidad); END;";
        $stmt = oci_parse($conn, $sql);

       
        oci_bind_by_name($stmt, ":idArticulo", $idArticulo, -1, SQLT_INT);
        oci_bind_by_name($stmt, ":cantidad", $cantidad, -1, SQLT_INT);


        if (!oci_execute($stmt)) {
            $error = oci_error($stmt);
            echo json_encode(["error" => "Error al ejecutar procedimiento", "detalle" => $error['message']]);
            exit;
        }

        oci_free_statement($stmt);
        oci_close($conn);

        echo json_encode(["success" => "Artículo editado correctamente"]);
        break;

        case 'checkout':

            if (!isset($_SESSION['id'])) {
                echo json_encode(["error" => "Usuario no autenticado"]);
                exit;
            }

            $sql = "BEGIN PKG_CHECKOUT.SP_EJECUTAR_CHECKOUT(:carrito, :idUsuario); END;";
            $stmt = oci_parse($conn, $sql);

    
            oci_bind_by_name($stmt, ":carrito", $carrito, -1, SQLT_INT);
            oci_bind_by_name($stmt, ":idUsuario", $id, -1, SQLT_INT);

            if (!oci_execute($stmt)) {
                $error = oci_error($stmt);
                echo json_encode(["error" => "Error al ejecutar procedimiento", "detalle" => $error['message']]);
                exit;
            }
    
            oci_free_statement($stmt);
            oci_close($conn);
    
            echo json_encode(["success" => "Tu pago se ha realizado exitosamente. Nuestro equipo te dará updates del estado de tu pedido."]);

            break;

            case 'contarCarrito':
                if (!isset($_SESSION['id'])) {
                    echo json_encode(["success" => false, "error" => "Usuario no autenticado"]);
                    exit;
                }
            
                $carrito_id = $_SESSION['id_carrito'];
                $total = 0;
            
                $stid = oci_parse($conn, "BEGIN :resultado := PKG_CARRITO.FN_CONTAR_ARTICULOS_CARRITO(:id_carrito); END;");
                oci_bind_by_name($stid, ":resultado", $total, 10);
                oci_bind_by_name($stid, ":id_carrito", $carrito_id);
            
                if (oci_execute($stid)) {
                    header('Content-Type: application/json');
                    echo json_encode(["success" => true, "total" => $total]);
                } else {
                    $e = oci_error($stid);
                    echo json_encode(["success" => false, "error" => $e['message']]);
                }
                exit;
            
                case 'obtenerInformacionUsuario':
                    $id = $_SESSION['id'] ?? null;
                
                    if (!$id) {
                        echo json_encode(["error" => "Debes iniciar sesión para ver esta información."]);
                        exit;
                    }
                
                    $sql = "BEGIN PKG_LEGADO.SP_GET_INFORMACION_USUARIO(:cursor, :id); END;";
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
                
                    $info = oci_fetch_assoc($cursor);
                
                    if ($info) {
                        echo json_encode($info);
                    } else {
                        echo json_encode(["error" => "No se encontró información para este usuario."]);
                    }
                
                    oci_free_statement($stmt);
                    oci_free_statement($cursor);
                    oci_close($conn);
                    break;
                    case 'actualizarUsuario':
                        if (!isset($_SESSION['id'])) {
                            echo json_encode(["error" => "Debes iniciar sesión para actualizar tu información."]);
                            exit;
                        }
                    
                        $id = $_SESSION['id'];
                        $nombre = $_POST['nombre'] ?? '';
                        $apellido1 = $_POST['apellido1'] ?? '';
                        $apellido2 = $_POST['apellido2'] ?? '';
                        $email = $_POST['email'] ?? '';
                    
                        if (!$nombre || !$apellido1 || !$apellido2 || !$email) {
                            echo json_encode(["error" => "Todos los campos son obligatorios."]);
                            exit;
                        }
                    
                        $sql = "BEGIN PKG_LEGADO.SP_ACTUALIZAR_INFORMACION_PERFIL(:id, :nombre, :apellido1, :apellido2, :email); END;";
                        $stmt = oci_parse($conn, $sql);
                    
                        oci_bind_by_name($stmt, ":id", $id, -1, SQLT_INT);
                        oci_bind_by_name($stmt, ":nombre", $nombre);
                        oci_bind_by_name($stmt, ":apellido1", $apellido1);
                        oci_bind_by_name($stmt, ":apellido2", $apellido2);
                        oci_bind_by_name($stmt, ":email", $email);
                    
                        if (!oci_execute($stmt)) {
                            $error = oci_error($stmt);
                            $mensajeError = $error['message'];
                    
                            if (str_contains($mensajeError, 'ORA-20003')) {
                                echo json_encode(["error" => "El correo electrónico ya está registrado por otro usuario."]);
                            } else {
                                echo json_encode(["error" => "Error al actualizar información.", "detalle" => $mensajeError]);
                            }
                    
                            oci_free_statement($stmt);
                            oci_close($conn);
                            exit;
                        }
                    
                        oci_free_statement($stmt);
                        oci_close($conn);
                    
                        // Actualizar sesión solo si todo salió bien
                        $_SESSION['nombre'] = $nombre;
                        $_SESSION['correo'] = $email;
                    
                        echo json_encode(["success" => "Información actualizada correctamente."]);
                        break;
                    
                    
                        case 'obtenerDireccionesUsuario':
                            if (!isset($_SESSION['id'])) {
                                echo json_encode(["error" => "No has iniciado sesión."]);
                                exit;
                            }
                        
                            $idUsuario = $_SESSION['id'];
                        
                            $sql = "BEGIN PKG_LEGADO.SP_GET_DIRECCIONES_USUARIO(:cursor, :idUsuario); END;";
                            $stmt = oci_parse($conn, $sql);
                            $cursor = oci_new_cursor($conn);
                        
                            oci_bind_by_name($stmt, ":idUsuario", $idUsuario, -1, SQLT_INT);
                            oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);
                        
                            if (!oci_execute($stmt)) {
                                $error = oci_error($stmt);
                                echo json_encode(["error" => "Error al ejecutar procedimiento", "detalle" => $error['message']]);
                                exit;
                            }
                        
                            if (!oci_execute($cursor)) {
                                $error = oci_error($cursor);
                                echo json_encode(["error" => "Error al obtener datos del cursor", "detalle" => $error['message']]);
                                exit;
                            }
                        
                            $direcciones = [];
                            while ($row = oci_fetch_assoc($cursor)) {
                                $direcciones[] = $row;
                            }
                        
                            if (empty($direcciones)) {
                                echo json_encode(["error" => "No tienes direcciones registradas."]);
                            } else {
                                echo json_encode($direcciones);
                            }
                        
                            oci_free_statement($stmt);
                            oci_free_statement($cursor);
                            oci_close($conn);
                            break;

                            case 'cancelarPedido':
                                try {
                                    $idPedido = $_POST['idPedido'];
                            
                                    $stmt = oci_parse($conn, "BEGIN PKG_LEGADO.SP_ACTUALIZAR_ESTADO_PEDIDO(:p_id_pedido, :p_id_estado); END;");
                                    oci_bind_by_name($stmt, ':p_id_pedido', $idPedido);
                                    $estadoCancelado = 8;
                                    oci_bind_by_name($stmt, ':p_id_estado', $estadoCancelado);
                            
                                    if (oci_execute($stmt)) {
                                        echo json_encode(['success' => true]);
                                    } else {
                                        $e = oci_error($stmt);
                                        echo json_encode(['success' => false, 'message' => $e['message']]);
                                    }
                            
                                    oci_free_statement($stmt);
                                } catch (Exception $e) {
                                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                                }
                                break;
                            

    default:
        echo json_encode(["error" => "Acción no válida"]);
        break;
}


exit;
?>
