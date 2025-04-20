<!DOCTYPE html>
<html lang="en">

<?php
// Incluir el archivo de fragmentos
require_once 'fragmentos.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quienes Somos - El Legado</title>
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

    <main class="container my-5">
        <section class="text-center mb-5">
            <h1 class="display-4 font-weight-bold">¿Quiénes Somos?</h1>
            <p class="lead mt-4">Distribuidora <strong>El Legado de Mis Padres</strong> es un emprendimiento familiar costarricense, fundado en Cartago con el propósito de compartir lo mejor de la tradición culinaria local. Nuestra empresa nace del amor por las raíces, el trabajo en familia y el deseo de llevar productos de calidad, frescos y artesanales hasta la puerta de cada cliente.</p>
            <p class="lead">Nos dedicamos a la distribución de alimentos y productos seleccionados cuidadosamente, con un compromiso firme por la excelencia, la responsabilidad y el respeto hacia nuestros consumidores. Cada producto que ofrecemos es un reflejo del esfuerzo, la dedicación y la pasión que definen a nuestra familia y a nuestra tierra.</p>
        </section>

        <section class="row text-center">
            <div class="col-md-6 mb-4">
                <h2 class="h3 font-weight-bold">Misión</h2>
                <p class="mt-3">Brindar productos artesanales y de alta calidad a negocios y hogares costarricenses, promoviendo el desarrollo local, el trabajo en familia y el compromiso con nuestros clientes, garantizando un servicio confiable, cálido y eficiente.</p>
            </div>
            <div class="col-md-6 mb-4">
                <h2 class="h3 font-weight-bold">Visión</h2>
                <p class="mt-3">Ser reconocidos a nivel nacional como una distribuidora líder en productos artesanales, destacándonos por nuestra autenticidad, atención personalizada y valores familiares, consolidando así una red de clientes satisfechos y fieles a nuestro legado.</p>
            </div>
        </section>

    </main>

    <?php incluir_footer(); ?>
</body>

</html>