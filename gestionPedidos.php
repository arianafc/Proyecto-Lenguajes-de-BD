<?php
require_once 'conexion.php';

// Cancelar pedido
if (isset($_POST['btn_cancelar'])) {
    $idCancelar = $_POST['id_cancelar'];
    $nuevo_estado = 8; // CANCELADO

    $stmtCancel = oci_parse($conn, "BEGIN PKG_LEGADO.ACTUALIZAR_ESTADO_PEDIDO(:id_pedido, :nuevo_estado); END;");
    oci_bind_by_name($stmtCancel, ":id_pedido", $idCancelar);
    oci_bind_by_name($stmtCancel, ":nuevo_estado", $nuevo_estado);

    if (oci_execute($stmtCancel)) {
        oci_free_statement($stmtCancel);
        header("Location: gestionPedidos.php");
        exit();
    } else {
        $e = oci_error($stmtCancel);
        $error_cancelar = "Error al cancelar pedido: " . htmlentities($e['message'], ENT_QUOTES);
        oci_free_statement($stmtCancel);
    }
}

// Actualizar estado
if (isset($_POST['btn_estado'])) {
    $id = $_POST['id_pedido'];
    $nuevo_estado = $_POST['nuevo_estado'];

    $stmtUpdate = oci_parse($conn, "BEGIN PKG_LEGADO.ACTUALIZAR_ESTADO_PEDIDO(:id, :nuevo_estado); END;");
    oci_bind_by_name($stmtUpdate, ":id", $id);
    oci_bind_by_name($stmtUpdate, ":nuevo_estado", $nuevo_estado);

    if (oci_execute($stmtUpdate)) {
        oci_free_statement($stmtUpdate);
        header("Location: gestionPedidos.php");
        exit();
    } else {
        $e = oci_error($stmtUpdate);
        $error_estado = "Error al actualizar estado: " . htmlentities($e['message'], ENT_QUOTES);
        oci_free_statement($stmtUpdate);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
require_once 'fragmentos.php';
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="js/java.js"></script>
    <?php incluir_css() ?>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php sidebar() ?>
        <main id="content" class="col-md-10 ms-sm-auto px-md-4 content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2" id="tituloAdmin">GESTIÓN DE PEDIDOS</h1>
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
                    <h5 class="card-title">Lista de Pedidos</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID Pedido</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Estado</th>
                                    <th>Subtotal</th>
                                    <th>Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
$estado_siguiente = [
    'NUEVO' => 3, 
    'EN PROCESO' => 7, 
    'EN CAMINO' => 5
];

$colores_estado = [
    'NUEVO' => 'secondary', 
    'EN PROCESO' => 'warning', 
    'EN CAMINO' => 'primary',
    'ENTREGADO' => 'success',
    'CANCELADO' => 'danger'
];

$query = "BEGIN PKG_LEGADO.OBTENER_PEDIDOS(:cursor_pedidos); END;";
$stmt = oci_parse($conn, $query);
$cursor = oci_new_cursor($conn);
oci_bind_by_name($stmt, ':cursor_pedidos', $cursor, -1, OCI_B_CURSOR);

if (oci_execute($stmt)) {
    oci_execute($cursor);
    while ($row = oci_fetch_assoc($cursor)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['ID_PEDIDO']) . "</td>";
        echo "<td>" . htmlspecialchars($row['FECHA']) . "</td>";
        echo "<td>" . htmlspecialchars($row['NOMBRE_CLIENTE']) . "</td>";

        $estado = $row['ESTADO'];
        $color = $colores_estado[$estado] ?? "secondary";
        echo "<td><span class='badge bg-$color'>" . htmlspecialchars($estado) . "</span></td>";

        echo "<td>₡" . number_format($row['SUBTOTAL'], 2) . "</td>";
        echo "<td>₡" . number_format($row['TOTAL'], 2) . "</td>";

        echo "<td>";
        if ($estado == 'ENTREGADO') {
            echo "<span class='text-success fs-4' title='Entregado'><i class='fas fa-check-circle'></i></span>";
        } elseif ($estado == 'CANCELADO') {
            echo "<span class='text-danger fs-4' title='Cancelado'><i class='fas fa-times-circle'></i></span>";
        } else {
            echo "<form method='POST' style='display:inline-block;'>
                    <input type='hidden' name='id_cancelar' value='" . $row['ID_PEDIDO'] . "'>
                    <button type='submit' class='btn btn-danger btn-sm' name='btn_cancelar'>Cancelar</button>
                </form>";

            if (isset($estado_siguiente[$estado])) {
                $nuevo_estado = $estado_siguiente[$estado];
                echo "<form method='POST' style='display:inline-block; margin-left:5px;'>
                        <input type='hidden' name='id_pedido' value='" . $row['ID_PEDIDO'] . "'>
                        <input type='hidden' name='nuevo_estado' value='" . $nuevo_estado . "'>
                        <button type='submit' class='btn btn-primary btn-sm' name='btn_estado'>Actualizar Estado</button>
                    </form>";
            }
        }
        echo "</td>";
        echo "</tr>";
    }
} else {
    $e = oci_error($stmt);
    echo "<tr><td colspan='7'>Error: " . htmlentities($e['message'], ENT_QUOTES) . "</td></tr>";
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

