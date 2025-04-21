<?php
require_once '../conexion.php';

error_reporting(E_ERROR);
ini_set('display_errors', 0);
header('Content-Type: application/json');
ob_start();

$action = $_POST['action'] ?? null;

$response = ['success' => false, 'message' => 'Acción no válida'];

switch ($action) {

    case 'agregar':
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $precio = (float) ($_POST['precio'] ?? 0);
        $id_categoria = (int) ($_POST['id_categoria'] ?? 0);

        // Aqui se procesa la imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nombreArchivo = $nombre . '.' . uniqid('img_') . '.' . $extension;
            $rutaDestino = './imgProductos/' . $nombreArchivo;
            $rutaFisica = __DIR__ . '/../imgProductos/' . $nombreArchivo;

            // Aqui se crea la imagen
            if (!file_exists(dirname($rutaFisica))) {
                mkdir(dirname($rutaFisica), 0755, true);
            }

            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaFisica)) {
                $response = ['success' => false, 'message' => 'Error al mover la imagen al servidor.'];
                break;
            }
        } else {
            $response = ['success' => false, 'message' => 'No se recibió la imagen.'];
            break;
        }

        try {
            $stmt = oci_parse($conn, "BEGIN PKG_LEGADO.SP_AGREGAR_PRODUCTO(
                    :p_precio, :p_categoria, :p_descripcion, :p_nombre, :p_imagen
                ); END;");

            oci_bind_by_name($stmt, ':p_precio', $precio);
            oci_bind_by_name($stmt, ':p_categoria', $id_categoria);
            oci_bind_by_name($stmt, ':p_descripcion', $descripcion);
            oci_bind_by_name($stmt, ':p_nombre', $nombre);
            oci_bind_by_name($stmt, ':p_imagen', $rutaDestino); // Ruta relativa guardada en la BD

            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                throw new Exception($e['message']);
            }

            oci_commit($conn);
            $response = ['success' => true, 'message' => 'Producto agregado correctamente.'];
            echo json_encode($response);
        } catch (Exception $e) {
            oci_rollback($conn);
            $response = ['success' => false, 'message' => 'Error al agregar producto: ' . $e->getMessage()];
            echo json_encode($response);
        }
        break;
    case 'editar':
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $precio = (float) ($_POST['precio'] ?? 0);
        $id_categoria = (int) ($_POST['id_categoria'] ?? 0);
        $id_estado = (int) ($_POST['id_estado'] ?? 0);
        $id = (int) ($_POST['id'] ?? 0);
        $imagenRutaFinal = $_POST['imagenActual'] ?? '';

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nombreArchivo = $nombre . '.' . uniqid('img_') . '.' . $extension;
            $rutaDestino = './imgProductos/' . $nombreArchivo;
            $rutaFisica = __DIR__ . '/../imgProductos/' . $nombreArchivo;

            if (!file_exists(dirname($rutaFisica))) {
                mkdir(dirname($rutaFisica), 0755, true);
            }

            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaFisica)) {
                $response = ['success' => false, 'message' => 'Error al mover la imagen al servidor.'];
                break;
            }

            $imagenRutaFinal = $rutaDestino;
        }

        try {
            $stmt = oci_parse($conn, "BEGIN PKG_LEGADO.SP_EDITAR_PRODUCTO(
                        :p_precio, :p_categoria, :p_descripcion, :p_nombre, :p_imagen, :p_estado, :p_id
                    ); END;");

            oci_bind_by_name($stmt, ':p_precio', $precio);
            oci_bind_by_name($stmt, ':p_categoria', $id_categoria);
            oci_bind_by_name($stmt, ':p_estado', $id_estado);
            oci_bind_by_name($stmt, ':p_id', $id);
            oci_bind_by_name($stmt, ':p_descripcion', $descripcion);
            oci_bind_by_name($stmt, ':p_nombre', $nombre);
            oci_bind_by_name($stmt, ':p_imagen', $imagenRutaFinal);

            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                throw new Exception($e['message']);
            }

            oci_commit($conn);
            echo json_encode(['success' => true, 'message' => 'Producto actualizado correctamente.']);
        } catch (Exception $e) {
            oci_rollback($conn);
            echo json_encode(['success' => false, 'message' => 'Error al editar producto: ' . $e->getMessage()]);
        }
        break;

    case 'eliminarActivar':
        $id = (int) $_POST['id'];
        $idEstado = (int) $_POST['id_estado'];

        try {
            $stmt = oci_parse($conn, "BEGIN PKG_LEGADO.SP_ELIMINAR_ACTIVAR_PRODUCTO(
                    :p_id, :p_estado
                ); END;");

            oci_bind_by_name($stmt, ':p_id', $id);
            oci_bind_by_name($stmt, ':p_estado', $idEstado);

            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                throw new Exception($e['message']);
            }

            oci_commit($conn);
            $response = ["success" => true, "message" => "Acción realizada exitosamente."];
            echo json_encode($response);
        } catch (Exception $e) {
            oci_rollback($conn);
            $response = ["success" => false, "message" => "Error al modificar: " . $e->getMessage()];
            echo json_encode($response);
        }
        break;
    case 'productosPorCategoria':
        $categoria = $_POST['idCategoria'];

        $conn = oci_connect(DB_USER, DB_PASS, DB_CONNECTION);
        if (!$conn) {
            echo json_encode(['error' => 'No se pudo conectar a la base de datos']);
            exit;
        }

        $query = 'BEGIN PKG_LEGADO.SP_GET_PRODUCTOS_CATEGORIAS(:datos, :categoria); END;';
        $stmt = oci_parse($conn, $query);

        $cursor = oci_new_cursor($conn);

        oci_bind_by_name($stmt, ':datos', $cursor, -1, OCI_B_CURSOR);
        oci_bind_by_name($stmt, ':categoria', $categoria);

        $success = oci_execute($stmt);

        if ($success) {
            oci_execute($cursor);
            $productos = [];
            while (($row = oci_fetch_assoc($cursor)) != false) {
                $productos[] = $row;
            }
            echo json_encode($productos);
        } else {
            $error = oci_error($stmt);
            echo json_encode(['error' => 'Error al ejecutar el procedimiento: ' . $error['message']]);
        }

        oci_free_statement($stmt);
        oci_free_statement($cursor);
        oci_close($conn);
        break;


    case 'obtenerProductosPorCategoria':
        try {
            if (!isset($_POST['categoria'])) {
                throw new Exception('Categoría no especificada');
            }

            $categoria = $_POST['categoria'];

            $stmt = oci_parse($conn, "BEGIN PKG_LEGADO.SP_GET_PRODUCTOS_CATEGORIAS(:datos, :categoria); END;");
            $cursor = oci_new_cursor($conn);

            oci_bind_by_name($stmt, ":datos", $cursor, -1, OCI_B_CURSOR);
            oci_bind_by_name($stmt, ":categoria", $categoria);

            oci_execute($stmt);
            oci_execute($cursor);

            $productos = [];
            while ($row = oci_fetch_array($cursor, OCI_ASSOC + OCI_RETURN_NULLS)) {
                foreach ($row as $key => $val) {
                    if (is_string($val)) {
                        $row[$key] = utf8_encode($val);
                    }
                }
                $productos[] = $row;
            }

            echo json_encode($productos);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;


    default:
        $response = ['success' => false, 'message' => 'Acción no reconocida'];
}

?>