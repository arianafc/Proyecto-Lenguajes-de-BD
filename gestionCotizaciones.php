<?php
//session_start();
require_once 'fragmentos.php';
require_once 'conexion.php';

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de productos - Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="./js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="js/java.js"></script>
    <script src="js/consultas.js"></script>

    <?php incluir_css() ?>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php sidebar() ?>

            <main id="content" class="col-md-10 ms-sm-auto px-md-4 content">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2" id="tituloAdmin">GESTIÓN DE SOLICITUDES</h1>
                    <div class="profile" onclick="toggleDropdown()">
                        <span><?php echo $_SESSION['nombre'] ?? 'producto'; ?> ▼</span>
                        <div class="dropdown" id="dropdownMenu" style="display: none;">
                            <a href="ajustes.php"><i class="fas fa-cog"></i> Ajustes</a>
                            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </div>
                    </div>
                </div>

 

                <div class="row card p-5">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Tipo</th>
                                    <th>Mensaje</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaConsultasDash">
                        
                            </tbody>
                        </table>
                    </div>
                </div>





            </main>
        </div>
    </div>

   
</body>

</html>