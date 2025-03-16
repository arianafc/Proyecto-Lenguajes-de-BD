<?php

//The connection.php file is called to make the connection to the database
session_start();
include 'conexion.php';

//Variable to handle error messages
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //The mail input is cleaned using a PHP function that filters out invalid characters in an email.
    $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
    $contraseña = $_POST['contraseña'];

    //Validate that the fields are not empty
    if (empty($correo) || empty($contraseña)) {
        $error = "Por favor, complete todos los campos.";
    } else {
        //Perform the query to search in the empleado table
        $stmt = $conexion->prepare("
            SELECT idEmpleado AS id, contraseña, rol 
            FROM empleado 
            WHERE correo = ?
        ");
        $stmt->bind_param('s', $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        //If not found in the empleado table, search in the cliente table
        if ($resultado->num_rows === 0) {
            $stmt = $conexion->prepare("
                SELECT idCliente AS id, contraseña, rol 
                FROM cliente 
                WHERE correo = ?
            ");
            $stmt->bind_param('s', $correo);
            $stmt->execute();
            $resultado = $stmt->get_result();
        }

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();

            //Check if the password matches the user's saved password in the DB 
            if ($contraseña === $usuario['contraseña']) {
                //Regenerate session ID to prevent session hijacking using a PHP function
                session_regenerate_id(true);
                
                //Save session data 
                $_SESSION['id'] = $usuario['id'];
                $_SESSION['rol'] = $usuario['rol'];
                $_SESSION['correo'] = $correo;
                
                //Redirect based on role
                if ($usuario['rol'] == 'administrador') {
                    header('Location: common.php');
                } elseif ($usuario['rol'] == 'cliente'){
                    header('Location: common.php');
                }
                exit();
            } else {
                $error = "Correo o contraseña incorrectos.";
            }
        } else {
            $error = "Usuario no encontrado.";
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
    <link rel="stylesheet"  href="css/login.css">>
    
    
</head>

<body>

    <div class="login-container">

        <!-- Logo -->
        <img src="/LenguajesBD-Proyecto/Proyecto-Lenguajes-de-BD/img/familia.png" alt="Logo el Legado">
        

        <!-- Título del login-->
        <h2>Inicio de sesión<br>El legado</h2>

        <!-- Mostrar mensaje de error -->
        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de login -->
        <form method="POST" action="">

            <!-- Campo de usuario -->
            <input type="text" name="usuario" placeholder="Usuario" required 
                   value="<?php echo isset($correo) ? htmlspecialchars($correo) : ''; ?>">

            <!-- Campo de contraseña -->
            <input type="password" name="contraseña" placeholder="Contraseña" required>

            <!-- Opción para crear una nueva cuenta -->
            <div class="createAccount">
                <label for="createAccount">¿No tienes cuenta?</label>
                <a href="/LenguajesBD-Proyecto/Proyecto-Lenguajes-de-BD/registro.php">Registrarme</a>
            </div>

            <!-- Botón de ingreso -->
            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>