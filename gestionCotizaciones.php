<!DOCTYPE html>
<html lang="en">

<?php
// Incluir el archivo de fragmentos
require_once 'fragmentos.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
                    <h1 class="h2" id="tituloAdmin">GESTIÓN DE CONSULTAS</h1>
                    <div class="profile" onclick="toggleDropdown()">
                        <span>Username ▼</span>
                        <div class="dropdown" id="dropdownMenu">
                            <a href="#"><i class="fas fa-cog"></i> Ajustes</a>
                            <a href="#"><i class="fas fa-right-from-bracket"></i> Logout</a>
                        </div>
                    </div>

                </div>

                <!-- Tarjetas informativas -->
                <div class="row">
                    
                </div>

                <!-- Gráfico (placeholder) -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Sales Analytics</h5>

                    </div>
                </div>
            </main>
        </div>
    </div>

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
            document.getElementById("dropdownMenu").classList.toggle("active");
        }
        document.addEventListener("click", function(event) {
            var dropdown = document.getElementById("dropdownMenu");
            if (!event.target.closest(".profile")) {
                dropdown.classList.remove("active");
            }
        });
    </script>
</body>

</html>