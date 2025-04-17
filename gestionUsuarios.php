<?php
//session_start();
require_once 'fragmentos.php';
require_once 'conexion.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CDN (versión 6 gratuita) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                    <button id="btnAgregarUsuario" class="btn btn-primary">
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
                                    <th>Primer Apellido</th>
                                    <th>Segundo Apellido</th>
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
                                    <td><?php echo htmlspecialchars($usuario['APELLIDO1']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['APELLIDO2']); ?></td>
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
                                            <button class="btn-editar btn btn-warning btn-sm"
                                                data-id="<?= $usuario['ID_USUARIO'] ?? '' ?>"
                                                data-nombre="<?= $usuario['NOMBRE'] ?? '' ?>"
                                                data-apellido1="<?= $usuario['APELLIDO1'] ?? '' ?>"
                                                data-apellido2="<?= $usuario['APELLIDO2'] ?? '' ?>"
                                                data-email="<?= $usuario['EMAIL'] ?? '' ?>"
                                                data-username="<?= $usuario['USERNAME'] ?? '' ?>"
                                                data-contrasena="<?= $usuario['CONTRASENA'] ?? '' ?>"
                                                data-id_estado="<?= $usuario['ID_ESTADO'] ?? '' ?>"
                                                data-id_rol="<?= $usuario['ID_ROL'] ?? '' ?>">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>

                                            <button class="btn btn-danger btn-sm btn-toggle-estado" 
                                                data-id="<?= $usuario['ID_USUARIO'] ?>" 
                                                data-nuevo-estado="2" 
                                                data-nombre="<?= htmlspecialchars($usuario['NOMBRE']) ?>">
                                                <i class="fas fa-trash-alt"></i> Desactivar
                                            </button>
                                        <?php else: // Inactivo ?>
                                            <button class="btn btn-success btn-sm btn-toggle-estado" 
                                                data-id="<?= $usuario['ID_USUARIO'] ?>" 
                                                data-nuevo-estado="1" 
                                                data-nombre="<?= htmlspecialchars($usuario['NOMBRE']) ?>">
                                                <i class="fa-regular fa-circle-check"></i> Activar
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
       document.getElementById("btnAgregarUsuario").addEventListener("click", function () {
        Swal.fire({
            title: 'Agregar Usuario',
            html: `
            <input id="swal-nombre" class="swal2-input" placeholder="Nombre">
            <input id="swal-apellido1" class="swal2-input" placeholder="Primer Apellido">
            <input id="swal-apellido2" class="swal2-input" placeholder="Segundo Apellido">
            <input id="swal-email" class="swal2-input" placeholder="Correo">
            <input id="swal-username" class="swal2-input" placeholder="Usuario">
            <input id="swal-contrasena" type="password" class="swal2-input" placeholder="Contraseña">
            <select id="swal-id_estado" class="swal2-input">
                <option value="">-- Estado --</option>
                <option value="1">Activo</option>
                <option value="2">Inactivo</option>
            </select>
            <select id="swal-id_rol" class="swal2-input">
                <option value="">-- Rol --</option>
                <option value="1">Administrador</option>
                <option value="2">Usuario</option>
            </select>
            `,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            preConfirm: () => {
            const data = {
                action: 'agregar',
                nombre: document.getElementById('swal-nombre').value,
                apellido1: document.getElementById('swal-apellido1').value,
                apellido2: document.getElementById('swal-apellido2').value,
                email: document.getElementById('swal-email').value,
                username: document.getElementById('swal-username').value,
                contrasena: document.getElementById('swal-contrasena').value,
                id_estado: document.getElementById('swal-id_estado').value,
                id_rol: document.getElementById('swal-id_rol').value
            };

            // Validación básica
            if (!data.nombre || !data.apellido1 || !data.email || !data.username || !data.contrasena || !data.id_estado || !data.id_rol) {
                Swal.showValidationMessage('Por favor, completa todos los campos obligatorios.');
                return false;
            }

            // Enviar al servidor
            return $.ajax({
                url: 'data/accionesUsuario.php',
                type: 'POST',
                data: data,
                dataType: 'json'
            }).then(response => {
                if (!response.success) {
                Swal.showValidationMessage(response.message);
                }
                return response;
            }).catch(() => {
                Swal.showValidationMessage('Error al conectar con el servidor.');
            });
            }
        }).then((result) => {
            if (result.isConfirmed && result.value.success) {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: result.value.message
            }).then(() => {
                // Podés actualizar una tabla, limpiar algo o recargar la vista
                location.reload();
            });
            }
        });
        });


        document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', () => {
            const usuario = {
            id_usuario: btn.dataset.id,
            nombre: btn.dataset.nombre,
            apellido1: btn.dataset.apellido1,
            apellido2: btn.dataset.apellido2,
            email: btn.dataset.email,
            username: btn.dataset.username,
            contrasena: btn.dataset.contrasena,
            id_estado: btn.dataset.id_estado,
            id_rol: btn.dataset.id_rol
            };

            Swal.fire({
                title: 'Editar Usuario',
                html: `
                    <input id="swal-nombre" class="swal2-input" placeholder="Nombre" value="${usuario.nombre}">
                    <input id="swal-apellido1" class="swal2-input" placeholder="Apellido 1" value="${usuario.apellido1}">
                    <input id="swal-apellido2" class="swal2-input" placeholder="Apellido 2" value="${usuario.apellido2}">
                    <input id="swal-email" class="swal2-input" placeholder="Email" value="${usuario.email}">
                    <input id="swal-username" class="swal2-input" placeholder="Username" value="${usuario.username}">
                    <input id="swal-contrasena" type="password" class="swal2-input" placeholder="Contraseña" value="${usuario.contrasena}">

                    <select id="swal-id_estado" class="swal2-input">
                    <option value="1" ${usuario.id_estado == 1 ? 'selected' : ''}>Activo</option>
                    <option value="2" ${usuario.id_estado == 2 ? 'selected' : ''}>Inactivo</option>
                    </select>

                    <select id="swal-id_rol" class="swal2-input">
                    <option value="1" ${usuario.id_rol == 1 ? 'selected' : ''}>Comprador</option>
                    <option value="2" ${usuario.id_rol == 2 ? 'selected' : ''}>Administrador</option>
                    </select>
                `,
            confirmButtonText: 'Guardar cambios',
            focusConfirm: false,
            preConfirm: () => {
                const formData = new FormData();
                formData.append('action', 'modificar');
                formData.append('id_usuario', usuario.id_usuario);
                formData.append('nombre', document.getElementById('swal-nombre').value);
                formData.append('apellido1', document.getElementById('swal-apellido1').value);
                formData.append('apellido2', document.getElementById('swal-apellido2').value);
                formData.append('email', document.getElementById('swal-email').value);
                formData.append('username', document.getElementById('swal-username').value);
                formData.append('contrasena', document.getElementById('swal-contrasena').value);
                formData.append('id_estado', document.getElementById('swal-id_estado').value);
                formData.append('id_rol', document.getElementById('swal-id_rol').value);

                return fetch('data/accionesUsuario.php', {
                method: 'POST',
                body: formData
                })
                .then(res => res.json())
                .then(data => {
                if (!data.success) {
                    throw new Error(data.message);
                }
                return data;
                })
                .catch(error => {
                Swal.showValidationMessage(`Error: ${error.message}`);
                });
            }
            }).then(result => {
            if (result.isConfirmed && result.value.success) {
                Swal.fire('¡Actualizado!', result.value.message, 'success').then(() => {
                location.reload(); // o refrescar solo la tabla si lo preferís
                });
            }
            });
        });
        });


        document.querySelectorAll('.btn-toggle-estado').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const nuevoEstado = btn.dataset.nuevoEstado;
                const nombre = btn.dataset.nombre;

                Swal.fire({
                    title: `${nuevoEstado == 1 ? 'Activar' : 'Desactivar'} Usuario`,
                    text: `¿Estás seguro de que querés ${nuevoEstado == 1 ? 'activar' : 'desactivar'} a ${nombre}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, continuar',
                    cancelButtonText: 'Cancelar'
                }).then(result => {
                    if (result.isConfirmed) {
                        const formData = new FormData();
                        formData.append('action', 'cambiar_estado');
                        formData.append('id_usuario', id);
                        formData.append('nuevo_estado', nuevoEstado);

                        fetch('data/accionesUsuario.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Hecho', data.message, 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        });
                    }
                });
            });
        });

     
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