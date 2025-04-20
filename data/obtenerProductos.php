<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require '../conexion.php';


header('Content-Type: application/json'); 

try {
    // Preparar la llamada al procedimiento almacenado
    $sql = "BEGIN PKG_LEGADO.SP_GET_PRODUCTOS_ACTIVOS(:cursor); END;";
    $stmt = oci_parse($conn, $sql);
    if (!$stmt) {
        $error = oci_error($conn);
        die(json_encode(["error" => "Error en oci_parse", "detalle" => $error['message']]));
    }

    // Crear cursor y enlazarlo
    $cursor = oci_new_cursor($conn);
    oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);

    // Ejecutar el procedimiento
    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        die(json_encode(["error" => "Error al ejecutar procedimiento", "detalle" => $error['message']]));
    }

    // Ejecutar el cursor
    if (!oci_execute($cursor)) {
        $error = oci_error($cursor);
        die(json_encode(["error" => "Error al ejecutar cursor", "detalle" => $error['message']]));
    }

    // Obtener resultados en un array
    $resultados = [];
    while ($fila = oci_fetch_array($cursor, OCI_ASSOC + OCI_RETURN_NULLS)) {
        foreach ($fila as $key => $value) {
            if (is_string($value)) {
                $fila[$key] = utf8_encode($value); // Convertir a UTF-8
            }
        }
        $resultados[] = $fila;
    }

    // Cerrar conexiones antes de devolver JSON
    oci_free_statement($stmt);
    oci_free_statement($cursor);
    oci_close($conn);

    // Si no hay resultados, devolver un mensaje de error
    if (empty($resultados)) {
        die(json_encode(["error" => "No hay productos disponibles"]));
    }

    // Intentar convertir a JSON
    $json = json_encode($resultados, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if ($json === false) {
        die(json_encode(["error" => "Error al convertir a JSON", "detalle" => json_last_error_msg()]));
    }

    echo $json;
    exit;

} catch (Exception $e) {
    echo json_encode(["error" => "Excepción en PHP", "detalle" => $e->getMessage()]);
}
