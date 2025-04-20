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
    <script src="js/carrito.js"></script>
    <script src="./js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php incluir_navbar(); ?>
    <main>
        <section class="quotation-section">
            <div class="quotation-form text-center">
                <img src="img/logo.png" alt="Logo El Legado">
                <h3 class="quotation-title-form">¿QUIERES UNA COTIZACIÓN DE NUESTROS PRODUCTOS?</h3>
                <form id="cotizacionForm" class="form-cotizacion" action="#">
                    <textarea id="descripcionCotizacion" name="descripcion" placeholder="Descripción de los productos o servicio" required></textarea>
                    <input type="number" id="presupuestoCotizacion" name="presupuesto" placeholder="Presupuesto Estimado (CRC)" required>
                    <button type="submit" class="btn btn-primary mt-2" id="enviarCotizacion">SOLICITAR COTIZACIÓN</button>
                </form>
            </div>
        </section>
    </main>
    <hr>
    <?php incluir_footer(); ?>
</body>

<script>
    $(document).ready(function () {
    $('#cotizacionForm').on('submit', function (e) {
        e.preventDefault();

        const descripcion = $('#descripcionCotizacion').val();
        const presupuesto = $('#presupuestoCotizacion').val();

        if (!descripcion || !presupuesto) {
            Swal.fire("Error", "Por favor completá todos los campos.", "warning");
            return;
        }

        const mensaje = ` ${descripcion}\n-- Presupuesto: ${presupuesto} CRC`;
        console.log(mensaje);
        $.post('./data/accionesConsultas.php', {
            action: 'agregarCotizacion',
            mensaje: mensaje
        }, function (data) {
            let respuesta = {};
            try {
                respuesta = JSON.parse(data);
            } catch (e) {
                Swal.fire("Error", "Respuesta no válida del servidor.", "error");
                return;
            }

            if (respuesta.success) {
                Swal.fire("¡Cotización enviada!", respuesta.success, "success");
                $('#cotizacionForm')[0].reset();
            } else if (respuesta.error) {
                Swal.fire("Error", respuesta.error, "error");
            }
        }).fail(function () {
            Swal.fire("Error", "Error de conexión con el servidor.", "error");
        });
    });
});
</script>

</html>
