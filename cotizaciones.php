<!DOCTYPE html>
<html lang="es">

<?php
require_once 'fragmentos.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Legado - Cotización</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <?php incluir_css(); ?>
</head>

<body>
    <?php incluir_navbar(); ?>
    <main>
        <section class="quotation-section">
            <div class="quotation-form text-center">
                <img src="img/logo.png" alt="Logo El Legado">
                <h3 class="quotation-title-form">¿QUIERES UNA COTIZACIÓN DE NUESTROS PRODUCTOS?</h3>
                <form id="cotizacionForm" class="form-cotizacion" action="#">
                    <input type="text" id="nombreCotizacion" name="nombre" placeholder="Nombre Completo" required>
                    <input type="email" id="emailCotizacion" name="email" placeholder="Email" required>
                    <input type="text" id="telefonoCotizacion" name="telefono" placeholder="Teléfono" required>
                    <textarea id="descripcionCotizacion" name="descripcion" placeholder="Descripción de los productos o servicio" required></textarea>
                    <input type="number" id="presupuestoCotizacion" name="presupuesto" placeholder="Presupuesto Estimado (CRC)" required>
                    <button type="submit" class="btn btn-primary mt-2" id="enviarCotizacion">ENVIAR COTIZACIÓN</button>
                </form>
            </div>
        </section>
    </main>
    <hr>
    <?php incluir_footer(); ?>
</body>

</html>
