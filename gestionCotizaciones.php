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
                    <div class="profile">
                    <?php
            if (!isset($_SESSION['id'])) {
                // Si el usuario no está logueado
                echo '<div class="auth-links"><a href="login.php">Sign In / Sign Up</a></div>';
            } else {
                // Si el usuario está logueado
                echo '<div class="auth-links">';
                echo '<div onclick="toggleDropdown()" style="cursor: pointer;">';
                echo 'Bienvenido, ' . $_SESSION['nombre'] . ' ▼';
                echo '<div class="dropdown" id="dropdownMenu" style="display: none;">';
                echo '<a href="#"><i class="fas fa-cog"></i> Ajustes</a>';
                echo '<a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        ?>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.getElementById("menu-toggle").addEventListener("click", function () {
            document.getElementById("sidebar").classList.toggle("show");
            document.getElementById("content").classList.toggle("shift");
        });

        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril'],
                datasets: [{
                    label: 'Ventas',
                    data: [30, 50, 80, 60],
                    borderColor: 'rgb(75, 192, 192)',
                    fill: false
                }]
            }
        });

        function toggleDropdown() {
    var dropdown = document.getElementById("dropdownMenu");
    if (dropdown.style.display === "none" || dropdown.style.display === "") {
        dropdown.style.display = "block";
    } else {
        dropdown.style.display = "none";
    }
}

// Cierra el dropdown cuando se hace clic en cualquier otra parte de la página
document.addEventListener('click', function(event) {
    var profile = document.querySelector('.profile');
    var dropdown = document.getElementById('dropdownMenu');
    
    if (profile && !profile.contains(event.target) && dropdown) {
        dropdown.style.display = 'none';
    }
});
    </script>
</html>