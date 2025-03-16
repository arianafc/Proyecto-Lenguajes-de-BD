<!DOCTYPE html>
<html lang="en">

<?php
require_once 'fragmentos.php';
require_once 'data/usuarios.php';
$usuarios = obtenerUsuarios();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/dashboard.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="./js/jquery-3.7.1.min.js"></script>
    <script src="js/java.js"></script>

    <?php incluir_css()?>
</head>

<body>
 
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php sidebar() ?>
            <!-- Contenido -->
            <main id="content" class="col-md-10 ms-sm-auto px-md-4 content">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2" id="tituloAdmin">GESTIÓN DE USUARIOS</h1>
                    <div class="profile" onclick="toggleDropdown()">
                        <span>Username ▼</span>
                        <div class="dropdown" id="dropdownMenu">
                            <a href="#"><i class="fas fa-cog"></i> Ajustes</a>
                            <a href="#"><i class="fas fa-sign-out"></i> Logout</a>
                        </div>
                    </div>

                </div>
                <!-- Tarjetas informativas -->
                <div class="row card p-5">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($usuario['NOMBRE']) ?></td>
                                    <td><?= htmlspecialchars($usuario['APELLIDO1']) ?></td>
                                    <td><?= htmlspecialchars($usuario['EMAIL']) ?></td>
                                    <td><?= htmlspecialchars($usuario['ROL']) ?></td>
                                    <td><?= htmlspecialchars($usuario['ESTADO']) ?></td>
                                    <td>
                                        <?php if ($usuario['ESTADO'] !== 'INACTIVO') { ?>
                                            <button class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>
                                            <button class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i> Desactivar
                                            </button>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <!-- Gráfico (placeholder) -->
                
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>

</html>