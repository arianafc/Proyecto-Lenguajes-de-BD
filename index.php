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
</head>

<body>
<?php incluir_navbar(); ?>
    <main>
 <section>

    <div class="mainContainer container-fluid row ">
        <div class="col p-0 ">
        <img class="object-fit-lg-contain border rounded imagenHP" src="./img/familia.png" >

        </div>
        <div class="col align-self-center p-5">
             <h4>LLEVANDO DELICIAS A TU NEGOCIO DESDE 2023</h4>
             <h1 class="tituloHP">HECHO POR TICOS, <br>PARA TICOS</h1>
             <span>Producto 100% artesanal</span>
             <br><br>
             <span><a href="" class="fw-bold text-dark">¿Quienes Somos?</a></span>
</div>
    </div>
    <hr>
    <h1 class="productosHP text-center">NUESTROS PRODUCTOS</h3>
    <section class="products-section">
        <div class="container">
            <div class="products-grid">
                <div class="product-card">
                
                <div class="product-card">
                    <div class="product-image">🥗</div>
                    <h3 class="product-title">Hamburguesa Vegetariana</h3>
                    <p class="product-description">Opción saludable para su menú</p>
                    <p class="product-price">$14.25</p>
                    <button class="btn bg-light">Añadir al carrito</button>
                </div>
            </div>
        </div>
    </section>
 </section>
 <br>
<hr>
 <div class="bottom-section">
    <div class="left-image">
        <div class="text-overlay text-center"><h6>"Nacimos con la intención de llevar el sabor a cada uno de sus hogares. 
            Donde el amor es nuestro ingrediente secreto."</h6>
            <br><br><h6> -Yessenia Calderón, dueña. </h6>
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