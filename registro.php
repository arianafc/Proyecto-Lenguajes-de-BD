<?php

session_start();
include 'conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido1 = $_POST['apellido1'];
    $apellido2 = isset($_POST['apellido2']) ? $_POST['apellido2'] : null;
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $username = $_POST['username'];
    $contrasena = $_POST['contrasena'];
    $id_estado = 1;
    $id_rol = 1;   

    // Validar campos vacíos
    if (empty($nombre) || empty($apellido1) || empty($email) || empty($username) || empty($contrasena)) {
        $error = "Por favor, complete todos los campos obligatorios.";
    } 
    // Validar contraseña con expresión regular (mínimo 10 caracteres, números y letras)
    elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d).{10,}$/', $contrasena)) {
        $error = "La contraseña debe tener al menos 10 caracteres e incluir letras y números.";
    } 
    else {
        try {
            // Comprobar si el usuario o email ya existen
            $sql = "SELECT COUNT(*) AS total FROM USUARIOS WHERE USERNAME = :username OR EMAIL = :email";
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ':username', $username);
            oci_bind_by_name($stmt, ':email', $email);
            oci_execute($stmt);
            $row = oci_fetch_array($stmt, OCI_ASSOC);
            
            if ($row['TOTAL'] > 0) {
                $error = "El nombre de usuario o correo electrónico ya están en uso.";
            } else {

                $sql = "BEGIN AGREGAR_USUARIO(:p_nombre, :p_email, :p_estado, :p_apellido1, :p_apellido2, :p_username, :p_contrasena, :p_rol); END;";
                $stmt = oci_parse($conn, $sql);

                oci_bind_by_name($stmt, ':p_nombre', $nombre);
                oci_bind_by_name($stmt, ':p_email', $email);
                oci_bind_by_name($stmt, ':p_estado', $id_estado);
                oci_bind_by_name($stmt, ':p_apellido1', $apellido1);
                oci_bind_by_name($stmt, ':p_apellido2', $apellido2);
                oci_bind_by_name($stmt, ':p_username', $username);
                oci_bind_by_name($stmt, ':p_contrasena', $contrasena);
                oci_bind_by_name($stmt, ':p_rol', $id_rol);

                // Ejecutamos el procedimiento de registro de usuario
                $execute = oci_execute($stmt);

                if ($execute) {
                    // Obtenemos el ID del usuario recién creado
                    $sql_id = "SELECT ID_USUARIO FROM USUARIOS WHERE USERNAME = :username";
                    $stmt_id = oci_parse($conn, $sql_id);
                    oci_bind_by_name($stmt_id, ':username', $username);
                    oci_execute($stmt_id);
                    $row = oci_fetch_array($stmt_id, OCI_ASSOC);
                    $id_usuario = $row['ID_USUARIO'];
                    
                    // Crear carrito de compra para el nuevo usuario
                    $sql_carrito = "BEGIN CREAR_CARRITO(:p_id_usuario); END;";
                    $stmt_carrito = oci_parse($conn, $sql_carrito);
                    oci_bind_by_name($stmt_carrito, ':p_id_usuario', $id_usuario);
                    $execute_carrito = oci_execute($stmt_carrito);
                    
                    if ($execute_carrito) {
                        header('Location: login.php');
                        exit();
                    } else {
                        $error = "Se creó la cuenta pero hubo un problema al crear el carrito de compra.";
                    }
                    
                    oci_free_statement($stmt_carrito);
                    oci_free_statement($stmt_id);
                } else {
                    $error = "No se pudo crear la cuenta. Por favor, inténtelo de nuevo.";
                }

                oci_free_statement($stmt);
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

            <input type="text" name="apellido2" placeholder="Segundo Apellido" 
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