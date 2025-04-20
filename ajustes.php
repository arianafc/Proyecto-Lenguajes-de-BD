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
                <img src="img/logo.png" alt="Logo El Legado">
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
    <img src="img/logo.png" alt="Logo El Legado">
    <h3 class="contacto-title-form">AJUSTES</h3>
    <form id="formEditarUsuario">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="apellido1">Primer Apellido:</label>
            <input type="text" id="apellido1" name="apellido1" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="apellido2">Segundo Apellido:</label>
            <input type="text" id="apellido2" name="apellido2" class="form-control">
        </div>
        <div class="form-group">
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Guardar Cambios</button>
    </form>
</div>

        </section>


    




    </main>
    <hr>
    <?php incluir_footer(); ?>
</body>

<script>
$(document).ready(function () {
    $.post('./data/accionesPerfil.php', {
        action: 'obtenerInformacionUsuario'
    }, function (data) {
        if (data.error) {
            Swal.fire("Error", data.error, "error");
            return;
        }

        $('#nombre').val(data.NOMBRE);
        $('#apellido1').val(data.APELLIDO1);
        $('#apellido2').val(data.APELLIDO2);
        $('#email').val(data.EMAIL);
        $('#username').val(data.USERNAME);
    }, 'json')
    .fail(function () {
        Swal.fire("Error", "No se pudo cargar la información del usuario.", "error");
    });
});

$('#formEditarUsuario').on('submit', function (e) {
        e.preventDefault();

        const datos = {
            action: 'actualizarUsuario',
            nombre: $('#nombre').val(),
            apellido1: $('#apellido1').val(),
            apellido2: $('#apellido2').val(),
            email: $('#email').val()
        };

        $.post('./data/accionesPerfil.php', datos, function (respuesta) {
            let r = {};
            try {
                r = JSON.parse(respuesta);
            } catch (e) {
                Swal.fire("Error", "Respuesta no válida del servidor.", "error");
                return;
            }

            if (r.success) {
                Swal.fire("¡Éxito!", r.success, "success");
                location.reload(); 
            } else if (r.error) {
                Swal.fire("Error", r.error, "error");
            }
        }).fail(function () {
            Swal.fire("Error", "No se pudo conectar al servidor.", "error");
        });
    });

</script>

</html>
