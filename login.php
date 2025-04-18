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
            $sql = "BEGIN SP_VERIFICAR_USUARIO(:username, :id_usuario, :id_rol, :rol_descripcion, :email, :contrasena_bd, :nombre, :id_carrito, :estado_usuario); END;";
            
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

            <div class="createAccount">
                <label for="createAccount">¿No tienes cuenta?</label>
                <a href="registro.php">Registrarme</a>
            </div>

            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>