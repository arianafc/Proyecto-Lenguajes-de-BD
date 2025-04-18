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

 * @return array Lista de productos
 */
function listarProductos($conn)
{
    $productos = [];

    // Preparar la llamada al SP GET_PRODUCTOS
    $sql = "BEGIN PKG_LEGADO.SP_GET_PRODUCTOS(:cursor); END;";
    $stmt = oci_parse($conn, $sql);

    // Definir el cursor
    $cursor = oci_new_cursor($conn);
    oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);

    // Ejecutar el SP
    oci_execute($stmt);
    oci_execute($cursor);

    // Obtener los resultados
    while ($row = oci_fetch_assoc($cursor)) {
        $productos[] = $row;
    }

    // Liberar recursos
    oci_free_statement($cursor);
    oci_free_statement($stmt);

    return $productos;
}

/**
 * Obtiene los datos de un producto específico
 * @param int $id_producto ID del producto
 * @return array|bool Datos del producto o false si no existe
 */
function obtenerProductoPorID($conn, $id_producto)
{
    // Preparar la llamada al SP OBTENER_producto_POR_ID
    $sql = "BEGIN PKG_LEGADO.SP_GET_PRODUCTO_ID(:id_producto, :cursor); END;";
    $stmt = oci_parse($conn, $sql);

    // Parámetros
    oci_bind_by_name($stmt, ':id_producto', $id_producto);
    $cursor = oci_new_cursor($conn);
    oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);

    // Ejecutar
    oci_execute($stmt);
    oci_execute($cursor);

    // Obtener el resultado
    $producto = oci_fetch_assoc($cursor);

    // Liberar recursos
    oci_free_statement($cursor);
    oci_free_statement($stmt);

    return $producto ?: false;
}

// Obtener la lista de productos y categorias
$productos = listarProductos($conn);


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de productos - Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="./js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="js/java.js"></script>

    <?php incluir_css() ?>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php sidebar() ?>

            <main id="content" class="col-md-10 ms-sm-auto px-md-4 content">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2" id="tituloAdmin">GESTIÓN DE PRODUCTOS</h1>
                    <div class="profile" onclick="toggleDropdown()">
                        <span><?php echo $_SESSION['nombre'] ?? 'producto'; ?> ▼</span>
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

                <div class="mb-3">
                    <button id="btnAgregarProducto" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Agregar Producto
                    </button>
                </div>

                <div class="row card p-5">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Precio</th>
                                    <th>Categoría</th>
                                    <th>Imagen</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($productos as $index => $producto): ?>
                                    <tr>

                                        <td><?php echo htmlspecialchars($producto['NOMBRE']); ?></td>
                                        <td><?php echo htmlspecialchars($producto['DESCRIPCION']); ?></td>
                                        <td><?php echo htmlspecialchars($producto['PRECIO']); ?></td>
                                        <td><?php echo htmlspecialchars($producto['CATEGORIA']); ?></td>
                                        <td>
                                            <img src="<?php echo htmlspecialchars($producto['IMAGEN']); ?>"
                                                alt="Imagen del producto" style="max-width: 100px; height: auto;">
                                        </td>

                                        <td>
                                            <span
                                                class="badge <?php echo ($producto['ESTADO'] === 'ACTIVO' || $producto['ID_ESTADO'] == 1) ? 'bg-success' : 'bg-danger'; ?>">
                                                <?php echo ($producto['ESTADO'] === 'ACTIVO' || $producto['ID_ESTADO'] == 1) ? 'Activo' : 'Inactivo'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($producto['ESTADO'] !== 'INACTIVO' && $producto['ID_ESTADO'] == 1): ?>
                                                <button class="btn-editar btn btn-warning btn-sm" id="btnEditarProducto"
                                                    data-id="<?= $producto['ID_PRODUCTO'] ?>"
                                                    data-nombre="<?= $producto['NOMBRE'] ?>"
                                                    data-descripcion="<?= $producto['DESCRIPCION'] ?>"
                                                    data-precio="<?= $producto['PRECIO'] ?>"
                                                    data-categoria="<?= $producto['CATEGORIA'] ?>"
                                                    data-imagen="<?= $producto['IMAGEN'] ?>"
                                                    data-id_estado="<?= $producto['ID_ESTADO'] ?>">
                                                    <i class="fas fa-edit"></i> Editar
                                                </button>

                                                <button class="btn btn-danger btn-sm btn-toggle-estado" id="btnEliminarProducto"
                                                    data-id="<?= $producto['ID_PRODUCTO'] ?>" data-estado="2">
                                                    <i class="fas fa-trash-alt"></i> Desactivar
                                                </button>
                                            <?php else: // Inactivo ?>
                                                <button class="btn btn-success btn-sm btn-toggle-estado" id="btnActivarProducto"
                                                    data-id="<?= $producto['ID_PRODUCTO'] ?>" data-estado="1">
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


        document.getElementById("btnAgregarProducto").addEventListener("click", async function () {
            const { value: formValues } = await Swal.fire({
                title: 'Agregar producto',
                html: `
            <input id="swal-nombre" class="swal2-input" placeholder="Nombre">
            <input id="swal-descripcion" class="swal2-input" placeholder="Descripción">
            <input id="swal-precio" type="number" class="swal2-input" placeholder="Precio">
            <select id="swal-categoria" class="swal2-input">
                <option value="">-- Categoría --</option>
            </select>
            <label for="swal-imagen" class="adjuntarComprobante">Insertar Imagen</label>
            <input type="file" id="swal-imagen" class="swal2-input">

        `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                didOpen: () => {
                    $.post('data/accionesProducto.php', { action: 'obtenerCategorias' }, function (categorias) {
                        const select = document.getElementById('swal-categoria');
                        categorias.forEach(cat => {
                            const option = document.createElement('option');
                            option.value = cat.ID_CATEGORIA;
                            option.textContent = cat.DESCRIPCION;
                            select.appendChild(option);
                        });
                    }, 'json');
                },
                preConfirm: () => {
                    const nombre = document.getElementById('swal-nombre').value;
                    const descripcion = document.getElementById('swal-descripcion').value;
                    const precio = document.getElementById('swal-precio').value;
                    const categoria = document.getElementById('swal-categoria').value;
                    const imagen = document.getElementById('swal-imagen').files[0];

                    if (!nombre || !descripcion || !precio || !categoria || !imagen) {
                        Swal.showValidationMessage('Por favor, completa todos los campos.');
                        return false;
                    }

                    return { nombre, descripcion, precio, categoria, imagen };
                }
            });

            if (formValues) {
                const formData = new FormData();
                formData.append('action', 'agregar');
                formData.append('nombre', formValues.nombre);
                formData.append('descripcion', formValues.descripcion);
                formData.append('precio', formValues.precio);
                formData.append('id_categoria', formValues.categoria);
                formData.append('imagen', document.getElementById('swal-imagen').files[0]);

                $.ajax({
                    url: 'data/accionesProducto.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function (resp) {
                        if (resp.success) {
                            Swal.fire('¡Éxito!', resp.message || 'Producto agregado correctamente.', 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Error', resp.message || 'Algo salió mal.', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'No se pudo enviar el formulario.', 'error');
                    }
                });
            }
        });

        document.querySelectorAll(".btn-toggle-estado").forEach(btn => {
            btn.addEventListener("click", async function () {
                const id = this.dataset.id;
                const nuevoEstado = this.dataset.estado;

                const accion = nuevoEstado == 1 ? 'activar' : 'desactivar';

                const confirmacion = await Swal.fire({
                    title: `¿Deseas ${accion} este producto?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, continuar',
                    cancelButtonText: 'Cancelar'
                });

                if (confirmacion.isConfirmed) {
                    $.post('data/accionesProducto.php', {
                        action: 'eliminarActivar',
                        id: id,
                        id_estado: nuevoEstado
                    }, function (resp) {
                        if (resp.success) {
                            Swal.fire('¡Hecho!', resp.message, 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Error', resp.message, 'error');
                        }
                    }, 'json');
                }
            });
        });



        document.getElementById("btnActivarProducto").addEventListener("click", async function () {
            console.log('hola');
            const id = this.dataset.id;
            const estado = this.dataset.estado;

            $.post('data/accionesProducto.php', {
                action: 'eliminarActivar',
                id: id,
                id_estado: estado
            }, function (data) {
                if (data.success) {
                    Swal.fire('Éxito', data.message, 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            }, 'json');
        });

        document.querySelectorAll(".btn-editar").forEach(btn => {
            btn.addEventListener("click", async function () {
                const id = this.dataset.id;
                const nombre = this.dataset.nombre;
                const descripcion = this.dataset.descripcion;
                const precio = this.dataset.precio;
                const categoria = this.dataset.categoria;
                const imagenActual = this.dataset.imagen;
                const estado = this.dataset.id_estado;

                const { value: formValues } = await Swal.fire({
                    title: 'Editar producto',
                    html: `
                <input id="swal-nombre" class="swal2-input" placeholder="Nombre" value="${nombre}">
                <input id="swal-descripcion" class="swal2-input" placeholder="Descripción" value="${descripcion}">
                <input id="swal-precio" type="number" class="swal2-input" placeholder="Precio" value="${precio}">
                <select id="swal-categoria" class="swal2-input">
                    <option value="">-- Categoría --</option>
                </select>
                <select id="swal-estado" class="swal2-input">
                    <option value="1" ${estado == 1 ? 'selected' : ''}>Activo</option>
                    <option value="2" ${estado == 2 ? 'selected' : ''}>Inactivo</option>
                </select>
                <label for="swal-imagen" class="adjuntarComprobante">Actualizar Imagen</label>
                <input type="file" id="swal-imagen" class="swal2-input">
                <small>Imagen actual: ${imagenActual}</small>
            `,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: 'Guardar cambios',
                    didOpen: () => {
                        // Cargar categorías
                        $.post('data/accionesProducto.php', { action: 'obtenerCategorias' }, function (categorias) {
                            const select = document.getElementById('swal-categoria');
                            categorias.forEach(cat => {
                                const option = document.createElement('option');
                                option.value = cat.ID_CATEGORIA;
                                option.textContent = cat.DESCRIPCION;
                                if (cat.ID_CATEGORIA == categoria) {
                                    option.selected = true;
                                }
                                select.appendChild(option);
                            });
                        }, 'json');
                    },
                    preConfirm: () => {
                        const nombre = document.getElementById('swal-nombre').value;
                        const descripcion = document.getElementById('swal-descripcion').value;
                        const precio = document.getElementById('swal-precio').value;
                        const categoria = document.getElementById('swal-categoria').value;
                        const estado = document.getElementById('swal-estado').value;
                        const imagen = document.getElementById('swal-imagen').files[0]; // puede ser null

                        if (!nombre || !descripcion || !precio || !categoria || estado === "") {
                            Swal.showValidationMessage('Por favor, completa todos los campos obligatorios.');
                            return false;
                        }

                        return { id, nombre, descripcion, precio, categoria, estado, imagen };
                    }
                });

                if (formValues) {
                    const formData = new FormData();
                    formData.append('action', 'editar');
                    formData.append('id', formValues.id);
                    formData.append('nombre', formValues.nombre);
                    formData.append('descripcion', formValues.descripcion);
                    formData.append('precio', formValues.precio);
                    formData.append('id_categoria', formValues.categoria);
                    formData.append('id_estado', formValues.estado);
                    formData.append('imagenActual', imagenActual); // ✅ siempre enviar imagen actual

                    if (formValues.imagen) {
                        formData.append('imagen', formValues.imagen); // ✅ solo si hay imagen nueva
                    }

                    Swal.fire({
                        title: 'Actualizando...',
                        text: 'Por favor espera un momento.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    $.ajax({
                        url: 'data/accionesProducto.php',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.success) {
                                Swal.fire('¡Éxito!', resp.message || 'Producto editado correctamente.', 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Error', resp.message || 'Algo salió mal.', 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'No se pudo enviar el formulario.', 'error');
                        }
                    });
                }
            });
        });





        // Función para manejar el dropdown del perfil
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        }

        // Cerrar el dropdown al hacer clic fuera de él
        document.addEventListener('click', function (event) {
            const dropdown = document.getElementById('dropdownMenu');
            const profile = document.querySelector('.profile');
            if (dropdown.style.display === 'block' && !profile.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });
    </script>
</body>

</html>