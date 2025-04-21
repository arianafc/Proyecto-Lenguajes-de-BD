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
    <script src="js/carrito.js"></script>
    <script src="js/perfil.js"></script>
    <script src="./js/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="css/perfil.css">
</head>

<body>
    <?php incluir_navbar(); ?>
    <main>
        <section class="contact-section row">
            <div class="perfil text-center col-md-4">
                <img src="img/logo.png" alt="Logo El Legado" style="width: 200px;">
                <h3 class="contacto-title-form">MI PERFIL</h3>
                    <p>Hola,   <?php echo $_SESSION['nombre']; ?></p>
                    <p><strong>Correo Electrónico:</strong> <?php echo $_SESSION['correo']; ?></p>
                    <p><strong>Usuario:</strong> <?php echo $_SESSION['username']; ?></p>
                    <hr>
                    <div class="buttons">
                        <button class="btn btn-edit"><i class="fas fa-edit"></i><a class="links" href="perfil.php">Pedidos</a></button>
                        <button class="btn btn-edit"><i class="fas fa-sign-out-alt"></i> <a class="links" href="consultas.php">Consultas</a></button>
                        <button class="btn btn-edit"><i class="fas fa-sign-out-alt"></i> <a class="links" href="ajustes.php">Ajustes</a></button>
                    </div>
                </div>

       

            </div>
            <div class="perfil text-center col-md-8">
                <img src="img/logo.png" alt="Logo El Legado" style="width: 200px;">
                <h3 class="contacto-title-form">TUS CONSULTAS</h3>
                <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Mensaje</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody id="tablaConsultasUsuario">
                            </tbody>
                        </table>
            </div>
        </section>


    




    </main>
    <hr>
    <?php incluir_footer(); ?>
</body>

<script>
$(document).ready(function () {
    obtenerConsultasUsuario();

    function obtenerConsultasUsuario() {
        $.post('./data/accionesConsultas.php', {
            action: 'obtenerConsultasUsuario'
        }, function (data) {
            let respuesta;
            try {
                respuesta = data;
            } catch (e) {
                Swal.fire("Error", "Respuesta inválida del servidor.", "error");
                return;
            }

            const tabla = $('#tablaConsultasUsuario');
            tabla.empty();

            if (respuesta.error) {
                Swal.fire("Error", respuesta.error, "error");
                return;
            }

            if (respuesta.mensaje) {
                tabla.append(`
                    <tr>
                        <td colspan="3" class="text-center">${respuesta.mensaje}</td>
                    </tr>
                `);
                return;
            }

            respuesta.forEach(consulta => {
                tabla.append(`
                    <tr>
                        <td>${consulta.TIPO}</td>
                        <td>${consulta.MENSAJE}</td>
                        <td>${consulta.ESTADO}</td>
                    </tr>
                `);
            });
        }).fail(function () {
            Swal.fire("Error", "Error al conectar con el servidor.", "error");
        });
    }
});

</script>

</html>
