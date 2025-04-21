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
    <script src="js/carrito.js"></script>
    <script src="js/perfil.js"></script>
    <script src="./js/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="css/perfil.css">
</head>

<body>
    <?php incluir_navbar(); ?>
    <main>
        <section class="contact-section row">
            <div class="perfil text-center col-md-4">
                <img src="img/logo.png" alt="Logo El Legado" style="width: 200px;">
                <h3 class="contacto-title-form">MI PERFIL</h3>
                    <p>Hola,   <?php echo $_SESSION['nombre']; ?></p>
                    <p><strong>Correo Electrónico:</strong> <?php echo $_SESSION['correo']; ?></p>
                    <p><strong>Usuario:</strong> <?php echo $_SESSION['username']; ?></p>
                    <hr>
                    <div class="buttons">
                        <button class="btn btn-edit"><i class="fas fa-edit"></i><a class="links" href="perfil.php">Pedidos</a></button>
                        <button class="btn btn-edit"><i class="fas fa-sign-out-alt"></i> <a class="links" href="consultas.php">Consultas</a></button>
                        <button class="btn btn-edit"><i class="fas fa-sign-out-alt"></i> <a class="links" href="ajustes.php">Ajustes</a></button>
                    </div>
                </div>

       

            </div>
            <div class="perfil text-center col-md-8">
                <img src="img/logo.png" alt="Logo El Legado" style="width: 200px;">
                <h3 class="contacto-title-form">TUS PEDIDOS</h3>
                <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="tablaDetallesPedidos">
                            </tbody>
                        </table>
            </div>
        </section>


      





    </main>
    <hr>
    <?php incluir_footer(); ?>
</body>

<script>
$(document).ready(function () {
    
    const urlParams = new URLSearchParams(window.location.search);
    const idPedido = urlParams.get('idPedido');

    if (!idPedido) {
        Swal.fire("Error", "No se proporcionó un ID de pedido válido.", "error");
        return;
    }

    $.post('./data/accionesPerfil.php', { action: 'verDetallePedido', idPedido: idPedido }, function (respuesta) {
        let data = [];

        try {
            data = JSON.parse(respuesta);
        } catch (e) {
            Swal.fire("Error", "Respuesta del servidor no válida.", "error");
            return;
        }

        if (data.error) {
            Swal.fire("Error", data.error, "error");
            return;
        }

        if (data.length === 0) {
            Swal.fire("Sin datos", "No se encontraron detalles para este pedido.", "info");
            return;
        }

        $('#tablaDetallesPedidos').empty();

        data.forEach(detalle => {
            const fila = `
                <tr>
                    <td>${detalle.PRODUCTO}</td>
                    <td>${detalle.CANTIDAD}</td>
                    <td>${parseFloat(detalle.PRECIO).toLocaleString('es-CR')}</td>
                    <td>${(detalle.PRECIO * detalle.CANTIDAD).toLocaleString('es-CR')}</td>
                </tr>
            `;
            $('#tablaDetallesPedidos').append(fila);
        });
    });
});
</script>

</html>
