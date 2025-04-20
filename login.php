<?php

session_start();
include 'conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y limpiar los datos del formulario
    $username = trim($_POST['usuario']);
    $contrasena = $_POST['contraseña'];
    $estado_usuario = '';


    //Validar que los campos no estén vacíos
    if (empty($username) || empty($contrasena)) {
        $error = "Por favor, complete todos los campos.";
    } else {
        try {
            // Consulta a la vista por medio de un SP para obtener la información del usuario
            $sql = "BEGIN PKG_LEGADO.SP_VERIFICAR_USUARIO(:username, :id_usuario, :id_rol, :rol_descripcion, :email, :contrasena_bd, :nombre, :id_carrito, :estado_usuario); END;";
            
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ':username', $username);

                    // Parámetros de salida
                    $id_usuario = '';
                    $id_rol = '';
                    $rol_descripcion = '';
                    $email = '';
                    $contrasena_bd = '';
                    $nombre = '';
                    $id_carrito = '';
                        
                    oci_bind_by_name($stmt, ':id_usuario', $id_usuario, 32);
                    oci_bind_by_name($stmt, ':id_rol', $id_rol, 32);
                    oci_bind_by_name($stmt, ':rol_descripcion', $rol_descripcion, 100);
                    oci_bind_by_name($stmt, ':email', $email, 100);
                    oci_bind_by_name($stmt, ':contrasena_bd', $contrasena_bd, 100);
                    oci_bind_by_name($stmt, ':nombre', $nombre, 100);
                    oci_bind_by_name($stmt, ':id_carrito', $id_carrito, 32);
                    oci_bind_by_name($stmt, ':estado_usuario', $estado_usuario, 32);

                    oci_execute($stmt);
            
                    if (!empty($id_usuario)) {                        
                        if ($estado_usuario == 2) {
                            $error = "Tu cuenta está inactiva. Por favor contactá al administrador.";
                            // Usuario encontrado, verificar contraseña
                        } elseif ($contrasena === $contrasena_bd) {
                            // Regenerar ID de sesión para prevenir secuestro de sesión
                            session_regenerate_id(true);
                            
                            // Guardar datos en la sesión
                            $_SESSION['id_carrito'] = $id_carrito;
                            $_SESSION['id'] = $id_usuario;
                            $_SESSION['id_rol'] = $id_rol;
                            $_SESSION['rol'] = strtolower($rol_descripcion);
                            $_SESSION['correo'] = $email;
                            $_SESSION['username'] = $username;
                            $_SESSION['nombre'] = $nombre;
                            
                            // Redirigir según el rol
                            if (strtolower($rol_descripcion) == 'administrador') {
                                header('Location: dashboard.php');
                            } elseif (strtolower($rol_descripcion) == 'comprador') {
                                header('Location: index.php');
                            } 
                            exit();
                        } else {
                            $error = "Usuario o contraseña incorrectos.";
                        }
                    } else {
                        $error = "Usuario no encontrado.";
                    }
                    
                    oci_free_statement($stmt);
            
        } catch (Exception $e) {
            $error = "Error en el sistema: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Login</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <div class="login-container">

        <img src="\LenguajesBD-Proyecto\Proyecto-Lenguajes-de-BD\img\familia.png" alt="Logo el Legado">
        
        <h2>Inicio de sesión<br>El legado</h2>

        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de login -->
        <form method="POST" action="">

            <input type="text" name="usuario" placeholder="Usuario" required 
                   value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">

            <input type="password" name="contraseña" placeholder="Contraseña" required>
<hr>
            <div class="createAccount text-center">
                <label for="createAccount">¿No tienes cuenta?<a href="registro.php"> Registrarme</a></label>                
            </div>
            <div class="createAccount text-center">
            <label for="createAccount">¿Olvidaste tu usuario?<a href="recuperarUsuario.php"> Recuperar Usuario</a></label>
                <hr>
            </div>
            <div class="createAccount text-center">
            <label for="createAccount">¿Olvidaste tu contraseña?<a href="recuperarPassword.php"> Actualizar Contraseña</a></label>
                <hr>
            </div>
            <hr>

            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>