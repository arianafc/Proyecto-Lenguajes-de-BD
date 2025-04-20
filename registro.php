<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);     
error_reporting(E_ALL);       

session_start();
include 'conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $apellido1 = $_POST['apellido1'] ?? '';
    $apellido2 = $_POST['apellido2'] ?? '';
    $email = $_POST['email'] ?? '';
    $id_estado = (int)($_POST['id_estado'] ?? 1);
    $username = $_POST['username'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';
    $id_rol = (int)($_POST['id_rol'] ?? 1); 

    // Validaciones
    if (empty($nombre) || empty($apellido1) || empty($apellido2) || empty($email) || empty($username) || empty($contrasena)) {
        $error = "Por favor, complete todos los campos obligatorios.";
    } else {
        try {
            // Crear usuario
            $sql = "BEGIN SP_AGREGAR_USUARIO(:p_nombre, :p_email, :p_estado, :p_apellido1, :p_apellido2, :p_username, :p_contrasena, :p_rol, :p_id_usuario); END;";
            $stmt = oci_parse($conn, $sql);

            oci_bind_by_name($stmt, ':p_nombre', $nombre);
            oci_bind_by_name($stmt, ':p_email', $email);
            oci_bind_by_name($stmt, ':p_estado', $id_estado);
            oci_bind_by_name($stmt, ':p_apellido1', $apellido1);
            oci_bind_by_name($stmt, ':p_apellido2', $apellido2);
            oci_bind_by_name($stmt, ':p_username', $username);
            oci_bind_by_name($stmt, ':p_contrasena', $contrasena);
            oci_bind_by_name($stmt, ':p_rol', $id_rol);
            $id_usuario = null;
            oci_bind_by_name($stmt, ':p_id_usuario', $id_usuario, 10);

            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                throw new Exception("Error al crear usuario: " . $e['message']);
            }

            // Crear carrito para el nuevo usuario
            $sql_carrito = "BEGIN CREAR_CARRITO(:p_id_usuario); END;";
            $stmt_carrito = oci_parse($conn, $sql_carrito);
            oci_bind_by_name($stmt_carrito, ':p_id_usuario', $id_usuario);

            if (!oci_execute($stmt_carrito)) {
                $e = oci_error($stmt_carrito);
                throw new Exception("Usuario creado, pero error al crear carrito: " . $e['message']);
            }

            // Todo ok → redirigir al login
            oci_free_statement($stmt);
            oci_free_statement($stmt_carrito);
            header('Location: login.php');
            exit();

        } catch (Exception $e) {
            $mensajeError = $e->getMessage();

            if (strpos($mensajeError, 'ORA-20001') !== false) {
                $error = "El correo electrónico ya está registrado.";
            } elseif (strpos($mensajeError, 'ORA-20002') !== false) {
                $error = "El nombre de usuario ya está registrado.";
            } elseif (strpos($mensajeError, 'ORA-20003') !== false) {
                $error = "La contraseña debe tener al menos 10 caracteres e incluir letras y números.";
            } else {
                $error = "Ocurrió un error inesperado. Intente nuevamente más tarde.";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - El Legado</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="login-container">

        <img src="/LenguajesBD-Proyecto/Proyecto-Lenguajes-de-BD/img/familia.png" alt="Logo el Legado">

        <h2>Crear cuenta<br>El legado</h2>

        <!-- Mostrar mensaje de error -->
        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de registro -->
        <form method="POST" action="">
          
            <input type="text" name="nombre" placeholder="Nombre" required 
                   value="<?php echo isset($nombre) ? htmlspecialchars($nombre) : ''; ?>">

            <input type="text" name="apellido1" placeholder="Primer Apellido" required 
                   value="<?php echo isset($apellido1) ? htmlspecialchars($apellido1) : ''; ?>">

            <input type="text" name="apellido2" placeholder="Segundo Apellido" required
                   value="<?php echo isset($apellido2) ? htmlspecialchars($apellido2) : ''; ?>">

            <input type="email" name="email" placeholder="Correo electrónico" required 
                   value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">

            <input type="text" name="username" placeholder="Nombre de usuario" required 
                   value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">

            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <div class="password-requirements">
                La contraseña debe tener al menos 10 caracteres e incluir letras y números.
            </div>

            <button type="submit">Crear cuenta</button>

            <button type="button" class="back-btn" onclick="window.location.href='/LenguajesBD-Proyecto/Proyecto-Lenguajes-de-BD/login.php'">
                Volver al inicio de sesión
            </button>
        </form>
    </div>
</body>
</html>