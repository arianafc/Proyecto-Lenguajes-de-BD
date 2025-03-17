<!DOCTYPE html>
<html lang="es">

<?php
require_once 'fragmentos.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Legado - Contactenos</title>
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
        <section class="contact-section">
            <div class="contact-form text-center">
                <img src="img/logo.png" alt="Logo El Legado">
                <h3 class="contacto-title-form">¿TIENES ALGUNA DUDA?</h3>
                <form id="contactoForm" class="form-contacto" action="#">
                    <input type="text" id="nombreConsulta" name="nombre" placeholder="Nombre" required>
                    <input type="text" id="apellidoConsulta" name="apellidos" placeholder="Apellido" required>
                    <input type="email" id="emailConsulta" name="email" placeholder="Email" required>
                    <textarea id="mensajeConsulta" name="mensaje" placeholder="Mensaje"></textarea>
                    <button type="submit" class="btn btn-primary mt-2" id="enviarConsulta">ENVIAR</button>
                </form>
            </div>
        </section>

        <section class="info-location text-center">
            <div class="contact-info">
                <h2>INFORMACIÓN DE CONTACTO</h2>
                <p>Teléfono: +506 2222 3333</p>
                <p>Email: contacto@legado.cr</p>
                <p>Dirección: Cartago, Costa Rica</p>
                <p>Horario: Lunes a Viernes, 8:00 am - 5:00 pm</p>
            </div>
            <div class="location-map">
                <h2>UBICACIÓN</h2>
                <iframe src="https://maps.google.com/maps?q=San%20Jose%20Costa%20Rica&t=&z=13&ie=UTF8&iwloc=&output=embed"></iframe>
            </div>
        </section>
    </main>
    <hr>
    <?php incluir_footer(); ?>
</body>

</html>
