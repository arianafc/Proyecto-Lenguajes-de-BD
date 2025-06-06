<!DOCTYPE html>
<html lang="es">

<?php
require_once 'fragmentos.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Legado - Contactenos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <?php incluir_css(); ?>
    <script src="./js/jquery-3.7.1.min.js"></script>
    <script src="js/carrito.js"></script>
    <script src="js/perfil.js"></script>
    <script src="js/direcciones.js"></script>
    <script src="js/telefonos.js"></script>

    <link rel="stylesheet" href="css/perfil.css">
</head>

<body>
    <?php incluir_navbar(); ?>
    <main>
        <section class="contact-section row">
            <div class="perfil text-center col-md-4">
                <img src="img/logo.png" alt="Logo El Legado" style="width: 200px;">
                <h3 class="contacto-title-form">MI PERFIL</h3>
                <p>Hola, <?php echo $_SESSION['nombre']; ?></p>
                <p><strong>Correo Electrónico:</strong> <?php echo $_SESSION['correo']; ?></p>
                <p><strong>Usuario:</strong> <?php echo $_SESSION['username']; ?></p>
                <hr>
                <div class="buttons">
                    <button class="btn btn-edit"><i class="fas fa-edit"></i><a class="links"
                            href="perfil.php">Pedidos</a></button>
                    <button class="btn btn-edit"><i class="fas fa-sign-out-alt"></i> <a class="links"
                            href="consultas.php">Consultas</a></button>
                    <button class="btn btn-edit"><i class="fas fa-sign-out-alt"></i> <a class="links"
                            href="ajustes.php">Ajustes</a></button>
                </div>
            </div>



            </div>
            <div class="perfil text-center col-md-8">
                <img src="img/logo.png" alt="Logo El Legado" style="width: 200px;">
                <h3 class="contacto-title-form">AJUSTES</h3>
                <form id="formEditarUsuario">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido1">Primer Apellido:</label>
                        <input type="text" id="apellido1" name="apellido1" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido2">Segundo Apellido:</label>
                        <input type="text" id="apellido2" name="apellido2" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="email">Correo Electrónico:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Guardar Cambios</button>
                </form>
                <hr>
                <div class="row">
                    <div class="table-responsive col-md-6">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Dirección</th>
                                    <th>Provincia</th>
                                    <th>Cantón</th>
                                    <th>Distrito</th>
                                    <th>Acciones</th>
                                </tr>


                            </thead>
                            <tbody id="tablaDirecciones">
                            </tbody>


                        </table>
                        <button class="btn btn-primary" id="btnAgregarDireccion">Agregar dirección</button>
                    </div>
                    <div class="table-responsive col-md-6">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Teléfono</th>
                                    <th>Acciones</th>
                                </tr>


                            </thead>
                            <tbody id="tablaTelefonos">
                            </tbody>


                        </table>
                        <button class="btn btn-primary" id="btnAgregarTelefono">Agregar Teléfono</button>
                    </div>
                </div>

            </div>

        </section>







    </main>
    <hr>
    <?php incluir_footer(); ?>
</body>

<script>
    $(document).ready(function () {
        $.post('./data/accionesPerfil.php', {
            action: 'obtenerInformacionUsuario'
        }, function (data) {
            if (data.error) {
                Swal.fire("Error", data.error, "error");
                return;
            }

            $('#nombre').val(data.NOMBRE);
            $('#apellido1').val(data.APELLIDO1);
            $('#apellido2').val(data.APELLIDO2);
            $('#email').val(data.EMAIL);
            $('#username').val(data.USERNAME);
        }, 'json')
            .fail(function () {
                Swal.fire("Error", "No se pudo cargar la información del usuario.", "error");
            });
    });

    $('#formEditarUsuario').on('submit', function (e) {
        e.preventDefault();

        const datos = {
            action: 'actualizarUsuario',
            nombre: $('#nombre').val(),
            apellido1: $('#apellido1').val(),
            apellido2: $('#apellido2').val(),
            email: $('#email').val()
        };

        $.post('./data/accionesPerfil.php', datos, function (respuesta) {
            try {
                const r = JSON.parse(respuesta);

                if (r.success) {
                    Swal.fire("¡Éxito!", r.success, "success").then(() => location.reload());
                } else if (r.error) {
                    Swal.fire("Error", r.error, "error");
                } else {
                    Swal.fire("Error", "Ocurrió un error inesperado.", "error");
                }

            } catch (e) {
                console.error("Respuesta no válida del servidor:", respuesta);
                Swal.fire("Error", "Ocurrió un error inesperado. Por favor intenta más tarde.", "error");
            }
        }).fail(function () {
            Swal.fire("Error", "No se pudo conectar al servidor.", "error");
        });


    });

    /////////////////////////////////////#apellido1

    $(document).ready(function () {
        $.post('./data/accionesPerfil.php', {
            action: 'obtenerDireccionesUsuario'
        }, function (data) {
            let respuesta = {};
            try {
                respuesta = JSON.parse(data);
            } catch (e) {
                Swal.fire("Error", "Respuesta no válida del servidor.", "error");
                return;
            }

            const $tabla = $('#tablaDirecciones');
            $tabla.empty();

            if (respuesta.error || respuesta.length === 0) {
                $tabla.append(`
                <tr>
                    <td colspan="5" class="text-center">No tenés direcciones registradas.</td>
                </tr>
               
            `);
            } else {
                respuesta.forEach(direccion => {
                    $tabla.append(`
                    <tr>
                        <td>${direccion.DIRECCION_EXACTA}</td>
                        <td>${direccion.PROVINCIA}</td>
                        <td>${direccion.CANTON}</td>
                        <td>${direccion.DISTRITO}</td>
                        <td>
                            <button class="btn btn-warning btn-sm btnEditarDireccion" id="editarDireccion" data-id=${direccion.ID_DIRECCION}>Editar</button>
                            <button class="btn btn-danger btn-sm btnEliminarDireccion" data-id=${direccion.ID_DIRECCION}>Eliminar</button>
                        </td>
                       <tr>
                  
                </tr>
                    </tr>
                `);
                });
            }
        }).fail(function () {
            Swal.fire("Error", "No se pudo conectar con el servidor.", "error");
        });
    });




</script>

</html>