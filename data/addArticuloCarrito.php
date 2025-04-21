<?php

session_start();
require '../conexion.php';

if (!isset($_POST['action'])) {
    echo json_encode(["error" => "No se recibió ninguna acción"]);
    exit;
}

// Validar si la sesión está activa
if (!isset($_SESSION['id']) || !isset($_SESSION['id_carrito'])) {
    echo json_encode(["error" => "Necesitas iniciar sesión para ver/agregar cosas a tu carrito."]);
    exit;
}

$id = $_SESSION['id'];
$carrito = $_SESSION['id_carrito'];
$action = $_POST['action'];

switch ($action) {
    case 'getCarrito':
        if (!isset($_SESSION['id'])) {
            echo json_encode(["error" => "Necesitas iniciar sesión para ver tu carrito."]);
            exit;
        } else {
            $idCarrito = $_SESSION['id_carrito'];
        
            $sql = "BEGIN PKG_LEGADO.SP_GET_CARRITO_USUARIO(:cursor, :id); END;";
            $stmt = oci_parse($conn, $sql);
        
            $cursor = oci_new_cursor($conn);
        
            oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);
            oci_bind_by_name($stmt, ":id", $idCarrito, -1, SQLT_INT);
        
            if (!oci_execute($stmt)) {
                $error = oci_error($stmt);
                echo json_encode(["error" => "Error al ejecutar procedimiento", "detalle" => $error['message']]);
                exit;
            }
        
            if (!oci_execute($cursor)) {
                $error = oci_error($cursor);
                echo json_encode(["error" => "Error al ejecutar cursor", "detalle" => $error['message']]);
                exit;
            }
        
            $carrito = [];
            while ($row = oci_fetch_assoc($cursor)) {
                $carrito[] = $row;
            }
        
            echo json_encode($carrito);
        
            oci_free_statement($stmt);
            oci_free_statement($cursor);
            oci_close($conn);
        }
        
        
        break;

    case 'add':
        if (!isset($_POST['idProducto'], $_POST['cantidad'])) {
            echo json_encode(["error" => "Datos incompletos"]);
            exit;
        }

        $cantidad = intval($_POST['cantidad']);
        $idProducto = intval($_POST['idProducto']);

        if (!isset($_SESSION['id'], $_SESSION['id_carrito'])) {
            echo json_encode(["error" => "Usuario no autenticado"]);
            exit;
        }

        $idCarrito = $_SESSION['id_carrito'];

        $sql = "BEGIN PKG_LEGADO.SP_AGREGAR_ARTICULO_CARRITO (:idCarrito, :idProducto, :cantidad); END;";
        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ":idCarrito", $idCarrito, -1, SQLT_INT);
        oci_bind_by_name($stmt, ":idProducto", $idProducto, -1, SQLT_INT);
        oci_bind_by_name($stmt, ":cantidad", $cantidad, -1, SQLT_INT);

        if (!oci_execute($stmt)) {
            $error = oci_error($stmt);
            echo json_encode(["error" => "Error al ejecutar procedimiento", "detalle" => $error['message']]);
            exit;
        }

        oci_free_statement($stmt);
        oci_close($conn);

        echo json_encode(["success" => "Artículo agregado correctamente"]);
        break;
    case 'delete':
        if (!isset($_POST['idProducto'])) {
            echo json_encode(["error" => "Datos incompletos"]);
            exit;
        }

        $idProducto = intval($_POST['idProducto']);

        if (!isset($_SESSION['id'], $_SESSION['id_carrito'])) {
            echo json_encode(["error" => "Usuario no autenticado"]);
            exit;
        }

        $idCarrito = $_SESSION['id_carrito'];

        $sql = "BEGIN PKG_LEGADO.SP_ELIMINAR_ARTICULO_CARRITO (:idProducto); END;";
        $stmt = oci_parse($conn, $sql);


        oci_bind_by_name($stmt, ":idProducto", $idProducto, -1, SQLT_INT);

        if (!oci_execute($stmt)) {
            $error = oci_error($stmt);
            echo json_encode(["error" => "Error al ejecutar procedimiento", "detalle" => $error['message']]);
            exit;
        }

        oci_free_statement($stmt);
        oci_close($conn);

        echo json_encode(["success" => "Artículo eliminado correctamente"]);
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
        $sql = "BEGIN PKG_LEGADO.SP_EDITAR_ARTICULO_CARRITO (:idArticulo, :cantidad); END;";
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

        $metodo = $_POST['metodo'];
        $sql = "BEGIN PKG_LEGADO.SP_EJECUTAR_CHECKOUT(:carrito, :idUsuario, :metodoPago); END;";
        $stmt = oci_parse($conn, $sql);


        oci_bind_by_name($stmt, ":carrito", $carrito, -1, SQLT_INT);
        oci_bind_by_name($stmt, ":idUsuario", $id, -1, SQLT_INT);
        oci_bind_by_name($stmt, ":metodoPago", $metodo);
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

        $stid = oci_parse($conn, "BEGIN :resultado := PKG_LEGADO.FN_CONTAR_ARTICULOS_CARRITO(:id_carrito); END;");
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


    case 'totalCarrito':
        if (!isset($_SESSION['id_carrito'])) {
            echo json_encode(["error" => "Carrito no identificado"]);
            exit;
        }

        $idCarrito = $_SESSION['id_carrito'];

        $sql = "BEGIN :subtotal := PKG_LEGADO.FN_OBTENER_SUBTOTAL_CARRITO(:idCarrito); END;";
        $stmt = oci_parse($conn, $sql);

        $subtotal = 0;

        oci_bind_by_name($stmt, ":idCarrito", $idCarrito, -1, SQLT_INT);
        oci_bind_by_name($stmt, ":subtotal", $subtotal, -1, OCI_B_INT);



        if (!oci_execute($stmt)) {
            $error = oci_error($stmt);
            echo json_encode(["error" => "Error al obtener subtotal", "detalle" => $error['message']]);
            exit;
        }

        echo json_encode(["subtotal" => number_format($subtotal, 2)]);
        oci_free_statement($stmt);
        oci_close($conn);
        break;

    default:
        echo json_encode(["error" => "Acción no válida"]);
        break;
}


exit;
?>