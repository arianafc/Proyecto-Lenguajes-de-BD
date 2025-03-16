<?php
require_once 'conexion.php'; // Conexión a la BD

function obtenerUsuarios() {
    global $conn; // Usa la conexión global a Oracle

    $sql = "SELECT * FROM V_CLIENTES";
    $stmt = oci_parse($conn, $sql);
    oci_execute($stmt);

    $usuarios = [];
    while ($row = oci_fetch_assoc($stmt)) {
        $usuarios[] = $row;
    }

    oci_free_statement($stmt);
    return $usuarios;
}
?>