<?php

session_start();
include 'conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y limpiar los datos del formulario
    $username = trim($_POST['usuario']);
    $contrasena = $_POST['contraseña'];

    //Validar que los campos no estén vacíos
    if (empty($username) || empty($contrasena)) {
        $error = "Por favor, complete todos los campos.";
    } else {
        try {
            // Consulta a la vista para obtener la información del usuario
            $sql = "SELECT ID_USUARIO, ID_ROL, ROL_DESCRIPCION, EMAIL, CONTRASENA, NOMBRE, ID_CARRITO
                    FROM V_USUARIOS_ROLES 
                    WHERE USERNAME = :username";
            
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ':username', $username);
            oci_execute($stmt);
            
            // Obtener los resultados
            if ($row = oci_fetch_assoc($stmt)) {
                // Usuario encontrado, verificar contraseña
                if ($contrasena === $row['CONTRASENA']) {
                    // Regenerar ID de sesión para prevenir secuestro de sesión
                    session_regenerate_id(true);
                    
                    // Guardar datos en la sesión
                    $_SESSION['id_carrito'] = $row['ID_CARRITO'];
                    $_SESSION['id'] = $row['ID_USUARIO'];
                    $_SESSION['id_rol'] = $row['ID_ROL'];
                    $_SESSION['rol'] = strtolower($row['ROL_DESCRIPCION']);
                    $_SESSION['correo'] = $row['EMAIL'];
                    $_SESSION['username'] = $username;
                    $_SESSION['nombre'] = $row['NOMBRE'];
                    
                    // Redirigir según el rol
                    if (strtolower($row['ROL_DESCRIPCION']) == 'administrador') {
                        header('Location: dashboard.php');
                    } elseif (strtolower($row['ROL_DESCRIPCION']) == 'comprador') {
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