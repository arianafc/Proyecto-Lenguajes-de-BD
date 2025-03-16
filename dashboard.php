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
                    <h1 class="h2 tituloAdmin text-dark">Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button class="btn btn-primary">Perfil</button>
                    </div>
                </div>

                <!-- Tarjetas informativas -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-body">
                                <h5 class="card-title">$30,200</h5>
                                <p class="card-text">Total Ganancias</p>
                            </div>
                        </div>
                    </div>
                
                    <div class="col-md-3">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-body">
                                <h5 class="card-title">290+</h5>
                                <p class="card-text">Productos Registrados</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-danger mb-3">
                            <div class="card-body">
                                <h5 class="card-title">145</h5>
                                <p class="card-text">Clientes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-info mb-3">
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

   
</body>

</html>