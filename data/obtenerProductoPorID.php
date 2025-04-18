<?php 

require '../conexion.php';

if(!$conn) {
    $error = oci_error();
    die(json_encode(["error" => $error['message']]));
}

$idProducto = isset($_GET['id']) ? intval($_GET['id']) : 0;

//LLAMAMOS AL PROCEDIMIENTO ALMACENADO
$sql = "BEGIN PKG_LEGADO.SP_GET_PRODUCTO_ID(:cursor, :idProducto); END;";
$stmt = oci_parse($conn, $sql);

//DECLARAMOS UN CURSOR NUEVO
$cursor = oci_new_cursor($conn);

//VINCULAMOS LOS PARAMETROS DEL CURSOR E ID
oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);
oci_bind_by_name($stmt, ":idProducto", $idProducto, -1, SQLT_INT);

//EJECUTAMOS LA CONSULTA
oci_execute($stmt);
oci_execute($cursor);

//OBTENEMOS EL PRODUCTO EN UN ARRAY
$producto = [];
while ($row = oci_fetch_assoc($cursor)) {
    $producto[] = $row;
}

echo json_encode($producto);

oci_free_statement($stmt);
oci_free_statement($cursor);
oci_close($conn);

?>