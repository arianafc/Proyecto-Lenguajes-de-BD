<?php
session_start();
require '../conexion.php';


if (!isset($_SESSION['id_carrito'])) {
    
    die(json_encode(["error" => "El usuario no tiene un carrito activo."]));
} else {
    $idCarrito = $_SESSION['id_carrito'];

    $sql = "BEGIN PKG_CARRITO.SP_GET_CARRITO_USUARIO(:cursor, :id); END;";
    $stmt = oci_parse($conn, $sql);

    $cursor = oci_new_cursor($conn);


    oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);
    oci_bind_by_name($stmt, ":id", $idCarrito, -1, SQLT_INT);

    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        die(json_encode(["error" => "Error al ejecutar procedimiento", "detalle" => $error['message']]));
    }


    if (!oci_execute($cursor)) {
        $error = oci_error($cursor);
        die(json_encode(["error" => "Error al ejecutar cursor", "detalle" => $error['message']]));
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



?>