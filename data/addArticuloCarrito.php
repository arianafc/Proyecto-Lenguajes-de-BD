<?php

session_start();
require '../conexion.php';

if (!isset($_POST['action'])) {
    echo json_encode(["error" => "No se recibió ninguna acción"]);
    exit;
}

$action = $_POST['action'];

switch ($action) {
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

        $sql = "BEGIN PKG_CARRITO.SP_AGREGAR_ARTICULO_CARRITO (:idCarrito, :idProducto, :cantidad); END;";
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

        $sql = "BEGIN PKG_CARRITO.SP_ELIMINAR_ARTICULO_CARRITO (:idProducto); END;";
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
        $cantidad = $_POST['cantidad'];
        $sql = "BEGIN PKG_CARRITO.SP_EDITAR_ARTICULO_CARRITO (:idProducto, :cantidad); END;";
        $stmt = oci_parse($conn, $sql);

       
        oci_bind_by_name($stmt, ":idProducto", $idProducto, -1, SQLT_INT);
        oci_bind_by_name($stmt, ":cantidad", $cantidad, -1, SQLT_INT);


        if (!oci_execute($stmt)) {
            $error = oci_error($stmt);
            echo json_encode(["error" => "Error al ejecutar procedimiento", "detalle" => $error['message']]);
            exit;
        }

        oci_free_statement($stmt);
        oci_close($conn);

        echo json_encode(["success" => "Artículo editado correctamente"]);
    default:
        echo json_encode(["error" => "Acción no válida"]);
        break;
}


exit;
?>
