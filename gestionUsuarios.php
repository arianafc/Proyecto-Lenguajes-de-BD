<?php
//session_start();
require_once 'fragmentos.php';
require_once 'conexion.php';

// Inicializar variables
$mensaje = '';
$error = '';
global $conn;

/**
 * Obtiene todos los usuarios del sistema mediante SP
 * @return array Lista de usuarios
 */
function listarUsuarios($conn) {
    $usuarios = [];
    
    // Preparar la llamada al SP LISTAR_USUARIOS
    $sql = "BEGIN SP_LISTAR_USUARIOS(:cursor); END;";
    $stmt = oci_parse($conn, $sql);
    
    // Definir el cursor
    $cursor = oci_new_cursor($conn);
    oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);
    
    // Ejecutar el SP
    oci_execute($stmt);
    oci_execute($cursor);
    
    // Obtener los resultados
    while ($row = oci_fetch_assoc($cursor)) {
        $usuarios[] = $row;
    }
    
    // Liberar recursos
    oci_free_statement($cursor);
    oci_free_statement($stmt);
    
    return $usuarios;
}

/**
 * Obtiene los roles disponibles en el sistema
 * @return array Lista de roles
 */
function obtenerRoles($conn) {
    $roles = [];
    
    // Preparar la llamada al SP OBTENER_ROLES
    $sql = "BEGIN SP_OBTENER_ROLES(:cursor); END;";
    $stmt = oci_parse($conn, $sql);
    
    $cursor = oci_new_cursor($conn);
    oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);
    
    oci_execute($stmt);
    oci_execute($cursor);
    
    while ($row = oci_fetch_assoc($cursor)) {
        $roles[] = $row;
    }
    
    oci_free_statement($cursor);
    oci_free_statement($stmt);
    
    return $roles;
}

/**
 * Obtiene los datos de un usuario específico
 * @param int $id_usuario ID del usuario
 * @return array|bool Datos del usuario o false si no existe
 */
function obtenerUsuarioPorId($conn, $id_usuario) {
    // Preparar la llamada al SP OBTENER_USUARIO_POR_ID
    $sql = "BEGIN SP_OBTENER_USUARIO_POR_ID(:id_usuario, :cursor); END;";
    $stmt = oci_parse($conn, $sql);
    
    // Parámetros
    oci_bind_by_name($stmt, ':id_usuario', $id_usuario);
    $cursor = oci_new_cursor($conn);
    oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);
    
    // Ejecutar
    oci_execute($stmt);
    oci_execute($cursor);
    
    // Obtener el resultado
    $usuario = oci_fetch_assoc($cursor);
    
    // Liberar recursos
    oci_free_statement($cursor);
    oci_free_statement($stmt);
    
    return $usuario ?: false;
}

// Obtener la lista de usuarios y roles para la página
$usuarios = listarUsuarios($conn);
$roles = obtenerRoles($conn);

// Manejo de AJAX para SweetAlert
// Manejo de solicitudes AJAX
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    try {
        $nombre = $_POST['nombre'] ?? '';
        $apellidos = $_POST['apellidos'] ?? '';
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $rol = $_POST['rol'] ?? '';

        // Validación de campos
        if (empty($nombre) || empty($apellidos) || empty($username) || empty($email) || empty($password) || empty($rol)) {
            throw new Exception("Todos los campos son requeridos");
        }

        // Insertar usuario con SP
        $sql = "BEGIN SP_CREAR_USUARIO(:nombre, :apellidos, :username, :email, :password, :rol, :id_usuario); END;";
        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ':nombre', $nombre);
        oci_bind_by_name($stmt, ':apellidos', $apellidos);
        oci_bind_by_name($stmt, ':username', $username);
        oci_bind_by_name($stmt, ':email', $email);
        oci_bind_by_name($stmt, ':password', $password);
        oci_bind_by_name($stmt, ':rol', $rol);
        oci_bind_by_name($stmt, ':id_usuario', $id_usuario, 10);

        if (!oci_execute($stmt)) {
            throw new Exception("No se pudo crear el usuario.");
        }

        oci_free_statement($stmt);

        // Llamar a CREAR_CARRITO
        $sql = "BEGIN CREAR_CARRITO(:id_usuario); END;";
        $stmt2 = oci_parse($conn, $sql);
        oci_bind_by_name($stmt2, ':id_usuario', $id_usuario);
        if (!oci_execute($stmt2)) {
            throw new Exception("Error al asignar carrito.");
        }

        oci_free_statement($stmt2);

        // Respuesta para AJAX
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Usuario agregado correctamente']);
            exit;
        }

        $mensaje = "Usuario agregado correctamente.";
        header("Location: gestionUsuarios.php");
        exit;

    } catch (Exception $e) {
        // Respuesta para AJAX
        if ($isAjax) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
        
        $error = "Error: " . $e->getMessage();
    }
}

// Manejo de desactivación de usuario
if (isset($_GET['desactivar'])) {
    try {
        $id_usuario = $_GET['desactivar'];
        // Aquí iría tu código para desactivar el usuario
        // ...
        
        $mensaje = "Usuario desactivado correctamente.";
        header("Location: gestionUsuarios.php");
        exit;
    } catch (Exception $e) {
        $error = "Error al desactivar usuario: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="./js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="js/java.js"></script>
    
    <?php incluir_css() ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php sidebar() ?>
            
            <!-- Contenido principal -->
            <main id="content" class="col-md-10 ms-sm-auto px-md-4 content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2" id="tituloAdmin">GESTIÓN DE USUARIOS</h1>
                    <div class="profile" onclick="toggleDropdown()">
                        <span><?php echo $_SESSION['nombre'] ?? 'Usuario'; ?> ▼</span>
                        <div class="dropdown" id="dropdownMenu" style="display: none;">
                            <a href="ajustes.php"><i class="fas fa-cog"></i> Ajustes</a>
                            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </div>
                    </div>
                </div>
                
                <?php if (!empty($mensaje)): ?>
                <script>
                    Swal.fire({
                        title: 'Éxito',
                        text: '<?php echo addslashes($mensaje); ?>',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    });
                </script>
                <?php endif; ?>
                
                <?php if (!empty($error)): ?>
                <script>
                    Swal.fire({
                        title: 'Error',
                        text: '<?php echo addslashes($error); ?>',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                </script>
                <?php endif; ?>
                
                <!-- Botón para agregar usuario -->
                <div class="mb-3">
                    <button class="btn btn-primary" onclick="mostrarFormularioUsuario()">
                        <i class="fas fa-plus"></i> Agregar Usuario
                    </button>
                </div>
                
                <!-- Tabla de usuarios -->
                <div class="row card p-5">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Usuario</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios as $index => $usuario): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($usuario['NOMBRE']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['APELLIDOS']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['USERNAME']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['EMAIL']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['ROL_DESCRIPCION'] ?? $usuario['ROL']); ?></td>
                                    <td>
                                        <span class="badge <?php echo (isset($usuario['ESTADO']) && $usuario['ESTADO'] == 'ACTIVO') || (isset($usuario['ID_ESTADO']) && $usuario['ID_ESTADO'] == 1) ? 'bg-success' : 'bg-danger'; ?>">
                                            <?php echo (isset($usuario['ESTADO']) && $usuario['ESTADO'] == 'ACTIVO') || (isset($usuario['ID_ESTADO']) && $usuario['ID_ESTADO'] == 1) ? 'Activo' : 'Inactivo'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ((isset($usuario['ESTADO']) && $usuario['ESTADO'] != 'INACTIVO') || (isset($usuario['ID_ESTADO']) && $usuario['ID_ESTADO'] == 1)): ?>
                                            <button class="btn btn-warning btn-sm" 
                                                    onclick="editarUsuario(<?php echo $usuario['ID_USUARIO']; ?>)">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>
                                            <button class="btn btn-danger btn-sm" 
                                                    onclick="confirmarDesactivar(<?php echo $usuario['ID_USUARIO']; ?>, '<?php echo htmlspecialchars($usuario['NOMBRE']); ?>')">
                                                <i class="fas fa-trash-alt"></i> Desactivar
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
                // Función para mostrar el formulario con SweetAlert
                function mostrarFormularioUsuario() {
            Swal.fire({
                title: 'Agregar Usuario',
                html: `
                    <form id="usuarioForm" class="text-start">
                        <input type="hidden" name="accion" value="agregar">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                     <div class="mb-3">
                            <label class="form-label">Apellidos</label>
                            <input type="text" name="apellidos" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Usuario</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rol</label>
                            <select name="rol" class="form-select" required>
                                <?php foreach ($roles as $rol): ?>
                                <option value="<?php echo $rol['ID_ROL']; ?>">
                                    <?php echo $rol['DESCRIPCION']; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    const form = document.getElementById('usuarioForm');
                    const formData = new FormData(form);
                    
                    // Validación básica
                    if (!form.querySelector('[name="nombre"]').value.trim()) {
                        Swal.showValidationMessage('El nombre es requerido');
                        return false;
                    }
                    // ... (validar otros campos) ...

                    // Mostrar loader
                    Swal.showLoading();
                    
                    // Enviar datos por AJAX
                    return fetch(window.location.href, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest' // Identificar como AJAX
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { 
                                throw new Error(err.message || 'Error en la solicitud'); 
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Éxito',
                                text: data.message,
                                icon: 'success'
                            }).then(() => {
                                window.location.reload();
                            });
                            return false; // Evitar que SweetAlert cierre automáticamente
                        } else {
                            throw new Error(data.message);
                        }
                    })
                    .catch(error => {
                        Swal.hideLoading();
                        Swal.showValidationMessage(`Error: ${error.message}`);
                        return false;
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        }

        // Función para confirmar desactivación de usuario
        function confirmarDesactivar(id, nombre) {
            Swal.fire({
                title: '¿Desactivar usuario?',
                text: `¿Estás seguro de desactivar a ${nombre}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loader mientras se procesa
                    Swal.fire({
                        title: 'Procesando',
                        text: 'Por favor espere...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Redirigir para desactivar
                    window.location.href = `gestionUsuarios.php?desactivar=${id}`;
                }
            });
        }

        // Función para editar usuario
        function editarUsuario(id) {
            // Mostrar loader mientras se cargan los datos
            Swal.fire({
                title: 'Cargando datos',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Obtener datos del usuario via AJAX
            fetch(`obtener_usuario.php?id=${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al obtener datos del usuario');
                    }
                    return response.json();
                })
                .then(usuario => {
                    Swal.fire({
                        title: 'Editar Usuario',
                        html: `
                            <form id="editarUsuarioForm" class="text-start">
                                <input type="hidden" name="accion" value="editar">
                                <input type="hidden" name="id_usuario" value="${id}">
                                <div class="mb-3">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" name="nombre" class="form-control" value="${usuario.NOMBRE}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Apellidos</label>
                                    <input type="text" name="apellidos" class="form-control" value="${usuario.APELLIDOS}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Usuario</label>
                                    <input type="text" name="username" class="form-control" value="${usuario.USERNAME}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="${usuario.EMAIL}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nueva Contraseña (dejar vacío para no cambiar)</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Rol</label>
                                    <select name="rol" class="form-select" required>
                                        <?php foreach ($roles as $rol): ?>
                                        <option value="<?php echo $rol['ID_ROL']; ?>" ${usuario.ID_ROL == <?php echo $rol['ID_ROL']; ?> ? 'selected' : ''}>
                                            <?php echo $rol['DESCRIPCION']; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </form>
                        `,
                        focusConfirm: false,
                        showCancelButton: true,
                        confirmButtonText: 'Actualizar',
                        cancelButtonText: 'Cancelar',
                        preConfirm: () => {
                            // Similar a la función de agregar, pero para editar
                            // Implementar lógica de actualización aquí
                        }
                    });
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error',
                        text: error.message,
                        icon: 'error'
                    });
                });
        }

        // Función para manejar el dropdown del perfil
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        }
        
        // Cerrar el dropdown al hacer clic fuera de él
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('dropdownMenu');
            const profile = document.querySelector('.profile');
            if (dropdown.style.display === 'block' && !profile.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });
    </script>
</body>
</html>