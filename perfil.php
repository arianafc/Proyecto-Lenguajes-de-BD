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
                                    <th># Pedido</th>
                                    <th>Fecha</th>
                                    <th>Método de Pago</th>
                                    <th>Estado</th>
                                    <th>Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaPedidosUsuario">
                            </tbody>
                        </table>
            </div>
        </section>






    </main>
    <hr>
    <?php incluir_footer(); ?>
</body>
<script>
     $(document).on('click', '.btnVerDetallePedido', function () {
        console.log('hola');
        const idPedido = $(this).data('id');
        $.post('./data/accionesPerfil.php', { action: 'verDetallePedido', idPedido: idPedido }, function (response) {
            let detalles = [];
            
            try {
                detalles = JSON.parse(response);
            } catch (e) {
                alert("Error al procesar respuesta del servidor");
                return;
            }
  
            if (detalles.error) {
                alert(detalles.error);
                return;
            }
            console.log(detalles);
            $('#tablaDetallesPedidos').empty();
  
            detalles.forEach(det => {
                const fila = `
                    <tr>
                        <td>${det.PRODUCTO}</td>
                        <td>${det.CANTIDAD}</td>
                        <td>$${det.PRECIO}</td>
                        <td>$${parseFloat(det.SUBTOTAL).toFixed(2)}</td>
                    </tr>
                `;
                $('#tablaDetallesPedidos').append(fila);
            });
    
        
            $('#tablaDetallesPedidos').closest('table').show();
        });
    });

</script>
</html>
