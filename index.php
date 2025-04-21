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
    <script src="js/carrito.js"></script>
    <script src="js/productos.js"></script>
</head>

<body>
    <?php incluir_navbar(); ?>
    <main>
        <section>

            <div class="mainContainer container-fluid row ">
                <div class="col p-0 ">
                    <img class="object-fit-lg-contain border rounded imagenHP" src="./img/familia.png">

                </div>
                <div class="col align-self-center p-5">
                    <h4>LLEVANDO DELICIAS A TU NEGOCIO DESDE 2023</h4>
                    <h1 class="tituloHP">HECHO POR TICOS, <br>PARA TICOS</h1>
                    <span>Producto 100% artesanal</span>
                    <br><br>
                    <span><a href="nosotros.php" class="fw-bold text-dark">¿Quienes Somos?</a></span>
                </div>
            </div>
        <br>
            <h1 class="productosHP text-center">NUESTROS PRODUCTOS</h3>
                <section class="products-section">
                <div id="contenedor-productosIndex" class="d-flex flex-wrap gap-3 justify-content-center"></div>

                       
                </section>
        </section>
        <br>
        <hr>
        <div class="bottom-section">
            <div class="left-image">
                <div class="text-overlay text-center">
                    <h5>"Nacimos con la intención de llevar el sabor a cada uno de sus hogares. <br>
                        Donde el amor es nuestro ingrediente secreto."<br>-Yessenia Calderón, dueña.</h5>
                    <br>
                
                </div>

                <img src="img/quienesSomos.jpg" alt="Descripción de la imagen" class="img-fluid">
            </div>
        </div>

    </main>
    <hr>
    <?php incluir_footer(); ?>
</body>

</html>