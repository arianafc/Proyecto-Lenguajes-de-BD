<!DOCTYPE html>
<html lang="en">

<?php
// Incluir el archivo de fragmentos
require_once 'fragmentos.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Legado</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <?php incluir_css(); ?>
    <script src="js/java.js"></script>
    <script src="js/productos.js"></script>
    <script src="js/jquery-3.7.1.min.js"></script>
</head>

<body>
    <?php incluir_navbar(); ?>
    <main>
    <section class="productos-wrapper">
        <hr>
        <h1 class="productosHP text-center mb-4">NUESTROS PRODUCTOS</h1>

        <!-- Filtros -->
        <div id="filtros-categorias" class="d-flex justify-content-center flex-wrap gap-2 mb-4">
            
        </div>

        <!-- Productos -->
        <section class="products-section">
            <div class="container d-flex flex-wrap justify-content-center gap-4" id="contenedor-productos">
             
            </div>
        </section>

        <br>
        <hr>
    </section>
</main>


    <?php incluir_footer(); ?>
</body>

</html>