<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña - El Legado</title>
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
        <img src="img\logo.png" style="width: 200px;" alt="Logo el Legado">
        <h2>Cambiar Contraseña<br>El legado</h2>
        <form method="POST" id="formCambiarContrasena">
            <div class="mb-3">
                <input type="text" name="username" class="form-control" id="usernameCambio" placeholder="Nombre de Usuario" required>
            </div>
            <div class="mb-3">
                <input type="password" name="nueva_contrasena" class="form-control" id="nuevaContrasena" placeholder="Nueva Contraseña" required>
            </div>
            <div class="mb-3">
                <input type="password" name="confirmar_contrasena" class="form-control" id="confirmarContrasena" placeholder="Confirmar Contraseña" required>
            </div>
            <div class="form-text text-center mb-3" style="font-size: 0.85rem;">
                La contraseña debe tener al menos <strong>10 caracteres</strong> e incluir <strong>letras y
                    números</strong>.
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Actualizar Contraseña</button>
            </div>
        </form>
       
    </div>

    <script>
        $(document).ready(function () {
            $('#formCambiarContrasena').on('submit', function (e) {
                e.preventDefault();

                const username = $('#usernameCambio').val();
                const nueva = $('#nuevaContrasena').val();
                const confirmar = $('#confirmarContrasena').val();

                if (nueva !== confirmar) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Las contraseñas no coinciden',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }

                $.post('./data/accionesLogin.php', {
    action: 'cambiarContrasena',
    username: username,
    contrasena: nueva
}, function (respuesta) {
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
            title: 'Contraseña actualizada',
            text: data.mensaje,
            confirmButtonText: 'Aceptar'
        }).then(() => {
            window.location.href = 'login.php';
        });
    }
}, 'json');

        });


    });
    </script>
</body>
</html>
