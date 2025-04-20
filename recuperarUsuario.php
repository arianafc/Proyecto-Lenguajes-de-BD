<?php
session_start();
require 'conexion.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Usuario - El Legado</title>
    <link rel="stylesheet" href="css/login.css">
  
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="./js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   
</head>
<body>
    <div class="login-container">

        <img src="\LenguajesBD-Proyecto\Proyecto-Lenguajes-de-BD\img\familia.png" alt="Logo el Legado">
        
        <h2>Recuperar Usuario<br>El legado</h2>

        <?php if (!empty($mensaje)): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>


        <form method="POST" action="" id="formRecuperarUsuario">

        <div class="mb-3">
                <input type="text" name="nombre" class="form-control" id="nombreRecuperacion" placeholder="Nombre" required
                    >
            </div>

            <div class="mb-3">
                <input type="email" name="email" class="form-control" id="emailRecuperacion" placeholder="Correo electrónico" required
                    >
            </div>
            <div class="createAccount text-center">
                <label for="login">¿Ya tienes cuenta? <a href="login.php">Iniciar sesión</a></label>
                <hr>
            </div>

            <button type="submit" name="recuperar">Recuperar Usuario</button>
        </form>
    </div>
</body>

<script>
    $(document).ready(function () {
        $('#formRecuperarUsuario').on('submit', function (e) {
            e.preventDefault();

            const email = $('#emailRecuperacion').val();
            const nombre = $('#nombreRecuperacion').val();

            $.post('./data/accionesLogin.php', {
                action: 'recuperarUsuario',
                email: email,
                nombre: nombre
            }, function (respuesta) {
                try {
                    const data = respuesta;

                    if (data.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.error,
                            confirmButtonText: 'Aceptar'
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Usuario encontrado',
                            text: data.mensaje,
                            confirmButtonText: 'Aceptar'
                        });
                    }
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error inesperado',
                        text: 'No se pudo procesar la respuesta del servidor.',
                        confirmButtonText: 'Aceptar'
                    });
                    console.error('Error al parsear JSON:', e);
                }
            });
        });
    });
</script>

</html>
