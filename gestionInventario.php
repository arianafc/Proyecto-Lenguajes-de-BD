<?php
require_once 'fragmentos.php';
require_once 'conexion.php';

// Actualizar estado del inventario
if (isset($_POST['btn_estado'])) {
    $id = $_POST['id_inventario'];
    $nuevo_estado = $_POST['nuevo_estado'];

    $stmtUpdate = oci_parse($conn, "BEGIN PKG_LEGADO.ACTUALIZAR_ESTADO_INVENTARIO(:id, :estado); END;");
    oci_bind_by_name($stmtUpdate, ":id", $id);
    oci_bind_by_name($stmtUpdate, ":estado", $nuevo_estado);

    if (oci_execute($stmtUpdate)) {
        header("Location: gestionInventario.php");
        exit();
    } else {
        $e = oci_error($stmtUpdate);
        echo "<div class='alert alert-danger'>Error: " . htmlentities($e['message'], ENT_QUOTES) . "</div>";
    }
    oci_free_statement($stmtUpdate);
}
?>
<!DOCTYPE html>
<html lang="en">
<?php
require_once 'fragmentos.php';
require_once 'conexion.php';
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="js/java.js"></script>
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
                    <h5 class="card-title">Lista de Inventario</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Cantidad</th>
                                <th>Estado</th>
                                <th>Advertencia</th> <!-- NUEVA COLUMNA -->
                                <th>Acción</th>
                            </tr>
                        </thead>
                            <tbody>
<?php
$colores_estado = [
    1 => 'success',    // ACTIVO
    2 => 'danger'      // INACTIVO
];



$query = "BEGIN PKG_LEGADO.OBTENER_INVENTARIO(:cursor_inv); END;";
$stmt = oci_parse($conn, $query);
$cursor = oci_new_cursor($conn);
oci_bind_by_name($stmt, ':cursor_inv', $cursor, -1, OCI_B_CURSOR);

if (oci_execute($stmt)) {
    oci_execute($cursor);
    while ($row = oci_fetch_assoc($cursor)) {
        $id = $row['ID_INVENTARIO'];
        $estado = $row['ESTADO'];

        echo "<tr>";
        echo "<td>" . htmlspecialchars($id) . "</td>";
        echo "<td>" . htmlspecialchars($row['NOMBRE']) . "</td>";
        echo "<td>" . htmlspecialchars($row['CANTIDAD']) . "</td>";
        echo "<td>" . htmlspecialchars($row['ESTADO']) . "</td>";
        // Determinar advertencia por cantidad
        $cantidad = (int)$row['CANTIDAD'];
        $advertencia = "";
        $claseAdvertencia = "";

        if ($cantidad == 0) {
            $advertencia = "URGENTE: Comprar";
            $claseAdvertencia = "text-danger fw-bold";
        } elseif ($cantidad > 0 && $cantidad <= 5) {
            $advertencia = "Bajo stock";
            $claseAdvertencia = "text-warning fw-semibold";
        } elseif ($cantidad > 5 && $cantidad <= 10) {
            $advertencia = "Revisar pronto";
            $claseAdvertencia = "text-secondary";
        } else {
            $advertencia = "Stock suficiente";
            $claseAdvertencia = "text-success";
        }

echo "<td class='$claseAdvertencia'>" . htmlspecialchars($advertencia) . "</td>";
        echo "<td>";
        if ($estado == 'ACTIVO') {
            echo "<form method='POST' style='display:inline-block;'>
                    <input type='hidden' name='id_inventario' value='$id'>
                    <input type='hidden' name='nuevo_estado' value='2'>
                    <button type='submit' class='btn btn-danger btn-sm' name='btn_estado'>Desactivar</button>
                </form>";
        } else {
            echo "<form method='POST' style='display:inline-block;'>
                    <input type='hidden' name='id_inventario' value='$id'>
                    <input type='hidden' name='nuevo_estado' value='1'>
                    <button type='submit' class='btn btn-success btn-sm' name='btn_estado'>Activar</button>
                </form>";
        }
        echo "</td>";
        echo "</tr>";
    }
} else {
    $e = oci_error($stmt);
    echo "<tr><td colspan='5'>Error: " . htmlentities($e['message'], ENT_QUOTES) . "</td></tr>";
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
</body>
</html>
