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
    <link rel="stylesheet" href="../css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <?php incluir_css(); ?>
</head>

<body>
    <?php incluir_navbar(); ?>
    <main>
        <section>
        <br>
        <div class="mainContainer container-fluid row ">
                <div class="col ">
              
                    <img class="object-fit-lg-contain border rounded imagenHP p-5" src="./img/familia.png">

                </div>
                <div class="col align-self-center p-5">
                <h2 class="text-center textos">Title Producto</h2>
                <p class="text-center"><b>$$</b></p>
                <br>
                    <h5>DESCRIPCION DEL PROUCTO</h5>
                
                   
                    <hr>
                    <br>
                    <div class="mb-4">
                <label for="quantity" class="form-label">Cantidad</label>
                <input type="number" class="form-control" id="quantity" value="1" min="1" style="width: 80px;">
            </div>
                    <br>
                    <button class="btn bg-light" id="btnAgregarCarrito"><a href="" class="fw-bold text-dark">🛒 Agregar al Carrito</a></button>
                </div>
            </div>





            <hr>
        </section>

    </main>

    <?php incluir_footer(); ?>
</body>

</html>