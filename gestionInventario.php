<?php
session_start();
require_once 'fragmentos.php';
require_once 'conexion.php';

if (isset($_POST['btn_estado'])) {
    $id = $_POST['id_inventario'];
    $nuevo_estado = $_POST['nuevo_estado'];

    $stmtUpdate = oci_parse($conn, "BEGIN PKG_LEGADO.SP_ACTUALIZAR_ESTADO_INVENTARIO(:id, :estado); END;");
    oci_bind_by_name($stmtUpdate, ":id", $id);
    oci_bind_by_name($stmtUpdate, ":estado", $nuevo_estado);

    if (oci_execute($stmtUpdate)) {
        $_SESSION['mensaje_estado'] = 'exito';
    } else {
        $_SESSION['mensaje_estado'] = 'error';
        $_SESSION['detalle_error_estado'] = htmlentities(oci_error($stmtUpdate)['message'], ENT_QUOTES);
    }
    oci_free_statement($stmtUpdate);
    header("Location: gestionInventario.php");
    exit();
}

if (isset($_POST['btn_insertar'])) {
    $nombre_producto = $_POST['nombre_producto'];
    $cantidad_producto = $_POST['cantidad_producto'];
    $estado_producto = $_POST['estado_producto'];

    $stmtInsert = oci_parse($conn, "BEGIN PKG_LEGADO.SP_INSERTAR_PRODUCTO_INVENTARIO(:nombre, :cantidad, :estado); END;");
    oci_bind_by_name($stmtInsert, ":nombre", $nombre_producto);
    oci_bind_by_name($stmtInsert, ":cantidad", $cantidad_producto);
    oci_bind_by_name($stmtInsert, ":estado", $estado_producto);

    if (oci_execute($stmtInsert)) {
        $_SESSION['mensaje'] = 'exito';
    } else {
        $_SESSION['mensaje'] = 'error';
        $_SESSION['detalle_error'] = htmlentities(oci_error($stmtInsert)['message'], ENT_QUOTES);
    }

    oci_free_statement($stmtInsert);
    header("Location: gestionInventario.php");
    exit();
}

if (isset($_POST['btn_actualizar_cantidad'])) {
    $id_inventario = $_POST['id_inventario'];
    $nueva_cantidad = $_POST['nueva_cantidad'];

    $stmtUpdateCantidad = oci_parse($conn, "BEGIN PKG_LEGADO.SP_ACTUALIZAR_CANTIDAD_INVENTARIO(:id_inventario, :cantidad); END;");
    oci_bind_by_name($stmtUpdateCantidad, ":id_inventario", $id_inventario);
    oci_bind_by_name($stmtUpdateCantidad, ":cantidad", $nueva_cantidad);

    if (oci_execute($stmtUpdateCantidad)) {
        $_SESSION['mensaje_cantidad'] = 'exito';
    } else {
        $_SESSION['mensaje_cantidad'] = 'error';
        $_SESSION['detalle_error_cantidad'] = htmlentities(oci_error($stmtUpdateCantidad)['message'], ENT_QUOTES);
    }
    oci_free_statement($stmtUpdateCantidad);
    header("Location: gestionInventario.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="js/java.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php incluir_css(); ?>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php sidebar(); ?>
        <main id="content" class="col-md-10 ms-sm-auto px-md-4 content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">GESTIÓN DE INVENTARIO</h1>
                <div class="profile" onclick="toggleDropdown()">
                    <span>ADMIN ▼</span>
                    <div class="dropdown" id="dropdownMenu">
                        <a href="#"><i class="fas fa-cog"></i> Ajustes</a>
                        <a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Agregar Nuevo Producto</h5>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="nombre_producto" class="form-label">Nombre del Producto</label>
                            <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" required>
                        </div>
                        <div class="mb-3">
                            <label for="cantidad_producto" class="form-label">Cantidad</label>
                            <input type="number" class="form-control" id="cantidad_producto" name="cantidad_producto" required>
                        </div>
                        <div class="mb-3">
                            <label for="estado_producto" class="form-label">Estado</label>
                            <select class="form-select" id="estado_producto" name="estado_producto" required>
                                <option value="1">Activo</option>
                                <option value="2">Inactivo</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" name="btn_insertar">Agregar Producto</button>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Lista de Inventario</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Cantidad</th> <!-- Ahora editable directamente -->
                                <th>Estado</th>
                                <th>Advertencia</th>
                                <th>Acciones</th> <!-- Solo cambiar estado -->
                            </tr>
                        </thead>
                        <tbody>
<?php
$query = "BEGIN PKG_LEGADO.SP_OBTENER_INVENTARIO(:cursor_inv); END;";
$stmt = oci_parse($conn, $query);
$cursor = oci_new_cursor($conn);
oci_bind_by_name($stmt, ':cursor_inv', $cursor, -1, OCI_B_CURSOR);

if (oci_execute($stmt)) {
    oci_execute($cursor);
    while ($row = oci_fetch_assoc($cursor)) {
        $id = $row['ID_INVENTARIO'];
        $estado = $row['ESTADO'];
        $cantidad = (int)$row['CANTIDAD'];

        echo "<tr>";
        echo "<td>" . htmlspecialchars($id) . "</td>";
        echo "<td>" . htmlspecialchars($row['NOMBRE']) . "</td>";
        echo "<td>
                <form method='POST' class='d-flex gap-2 align-items-center'>
                    <input type='hidden' name='id_inventario' value='$id'>
                    <input type='number' name='nueva_cantidad' value='$cantidad' min='0' class='form-control form-control-sm' style='width: 80px;' required>
                    <button type='submit' name='btn_actualizar_cantidad' class='btn btn-warning btn-sm'>Actualizar</button>
                </form>
            </td>";
        echo "<td>" . htmlspecialchars($estado) . "</td>";

        if ($cantidad == 0) {
            $advertencia = "<span class='text-danger fw-bold'>URGENTE: Comprar</span>";
        } elseif ($cantidad <= 5) {
            $advertencia = "<span class='text-warning fw-semibold'>Bajo stock</span>";
        } elseif ($cantidad <= 10) {
            $advertencia = "<span class='text-secondary'>Revisar pronto</span>";
        } else {
            $advertencia = "<span class='text-success'>Stock suficiente</span>";
        }

        echo "<td>$advertencia</td>";

        echo "<td>
                <form method='POST'>
                    <input type='hidden' name='id_inventario' value='$id'>
                    <input type='hidden' name='nuevo_estado' value='" . ($estado == 'ACTIVO' ? "2" : "1") . "'>
                    <button type='submit' class='btn btn-sm " . ($estado == 'ACTIVO' ? "btn-danger" : "btn-success") . "' name='btn_estado'>" . ($estado == 'ACTIVO' ? "Desactivar" : "Activar") . "</button>
                </form>
            </td>";
        echo "</tr>";
    }
} else {
    $e = oci_error($stmt);
    echo "<tr><td colspan='6'>Error: " . htmlentities($e['message'], ENT_QUOTES) . "</td></tr>";
}
oci_free_statement($stmt);
oci_free_cursor($cursor);
?>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    function toggleDropdown() {
        document.getElementById("dropdownMenu").classList.toggle("active");
    }
    document.addEventListener("click", function (event) {
        if (!event.target.closest(".profile")) {
            document.getElementById("dropdownMenu").classList.remove("active");
        }
    });
</script>

<?php if (isset($_SESSION['mensaje'])): ?>
<script>
Swal.fire({
    icon: '<?php echo $_SESSION['mensaje'] === "exito" ? "success" : "error"; ?>',
    title: '<?php echo $_SESSION["mensaje"] === "exito" ? "Producto agregado" : "Error al insertar"; ?>',
    text: '<?php echo $_SESSION["mensaje"] === "exito" ? "El producto fue agregado exitosamente." : $_SESSION["detalle_error"]; ?>',
    timer: 3000,
    showConfirmButton: false
});
</script>
<?php unset($_SESSION['mensaje'], $_SESSION['detalle_error']); endif; ?>

<?php if (isset($_SESSION['mensaje_cantidad'])): ?>
<script>
Swal.fire({
    icon: '<?php echo $_SESSION['mensaje_cantidad'] === "exito" ? "success" : "error"; ?>',
    title: '<?php echo $_SESSION["mensaje_cantidad"] === "exito" ? "Cantidad actualizada" : "Error al actualizar"; ?>',
    text: '<?php echo $_SESSION["mensaje_cantidad"] === "exito" ? "Se actualizó correctamente la cantidad." : $_SESSION["detalle_error_cantidad"]; ?>',
    timer: 3000,
    showConfirmButton: false
});
</script>
<?php unset($_SESSION['mensaje_cantidad'], $_SESSION['detalle_error_cantidad']); endif; ?>

<?php if (isset($_SESSION['mensaje_estado'])): ?>
<script>
Swal.fire({
    icon: '<?php echo $_SESSION['mensaje_estado'] === "exito" ? "success" : "error"; ?>',
    title: '<?php echo $_SESSION["mensaje_estado"] === "exito" ? "Estado actualizado" : "Error al actualizar estado"; ?>',
    text: '<?php echo $_SESSION["mensaje_estado"] === "exito" ? "El estado del producto fue modificado." : $_SESSION["detalle_error_estado"]; ?>',
    timer: 3000,
    showConfirmButton: false
});
</script>
<?php unset($_SESSION['mensaje_estado'], $_SESSION['detalle_error_estado']); endif; ?>
</body>
</html>
