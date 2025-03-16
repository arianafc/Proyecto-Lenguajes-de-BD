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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="js/java.js"></script>
    <?php incluir_css()?>
</STYLE>
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
                    <h1 class="h2" id="tituloAdmin">DASHBOARD</h1>
                    <div class="profile" onclick="toggleDropdown()">
                        <span>Username ▼</span>
                        <div class="dropdown" id="dropdownMenu">
                            <a href="#"><i class="fas fa-cog"></i> Ajustes</a>
                            <a href="#"><i class="fas fa-sign-out"></i> Logout</a>
                        </div>
                    </div>

                </div>
                <!-- Tarjetas informativas -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="card text-white cardVerde mb-3">
                            <div class="card-body">
                                <h5 class="card-title">290+</h5>
                                <p class="card-text">Productos Registrados</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white cardCafe mb-3">
                            <div class="card-body">
                                <h5 class="card-title">145</h5>
                                <p class="card-text">Clientes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white  cardVerde mb-3">
                            <div class="card-body">
                                <h5 class="card-title">500</h5>
                                <p class="card-text">Consultas</p>
                            </div>
                        </div>
                    </div>
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