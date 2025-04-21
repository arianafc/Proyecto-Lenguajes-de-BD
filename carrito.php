<!DOCTYPE html>
<html lang="es">

<?php
require_once 'fragmentos.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Legado - Carrito de Compras</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <script src="./js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php incluir_css(); ?>
    <script src="js/vistaCarrito.js"></script>
    <script src="js/carrito.js"></script>
 
    <script src="js/java.js"></script>
  
</head>

<body>
    <?php incluir_navbar(); ?>
    <main>
        <section class="cart-section">
            <div class="cart-header text-center">
                <h1 class="productosHP text-center">TU CARRITO</h3>
            </div>

            <div class="cart-items">
                <table class="table" id="tablaCarrito">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario (CRC)</th>
                            <th>Subtotal (CRC)</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                   <tbody>

                   </tbody>
                </table>

                <div class="cart-total">
                    <h3 id="totalCarrito"></h3>
                </div>

                <div class="cart-actions">
                    <button class="btn btn-secondary"><a style="text-decoration: none; color: #fff"
                            href="productos.php">Seguir Comprando</a></button>
                    <button class="btn btn-primary" id="checkoutBtn">Proceder al Pago</button>
                </div>
            </div>

          
        </section>
    </main>
    <hr>
    <?php incluir_footer(); ?>
</body>
<script>
   $(document).on('click', '#checkoutBtn', function () {
    Swal.fire({
        title: "TU COMPRA",
        html: `
            <p>Revisa tu pedido antes de finalizar.</p>
            <label for="paymentMethod">Método de Pago:</label>
            <div id="paymentOptions">
                <label style="cursor: pointer; display: flex; align-items: center; gap: 10px;">
                    <input type="radio" name="paymentMethod" value="1" data-descripcion="sinpe">
                    Sinpe
                </label>
                <label style="cursor: pointer; display: flex; align-items: center; gap: 10px;">
                    <input type="radio" name="paymentMethod" value="2" data-descripcion="efectivo">
                    Efectivo
                </label>
            </div>
            <div id="paymentDetails" style="margin-top: 10px;"></div>
            <div class="cart-summary" style="margin-top: 10px;">
                <p class="total">Total: <strong id="totalAmount"></strong></p>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: "Pagar Ahora",
        cancelButtonText: "Cancelar",
        customClass: {
            confirmButton: "bntPagar",
            cancelButton: "btnCancelar"
        },
        didOpen: () => {
            $.post('./data/addArticuloCarrito.php', { action: 'totalCarrito' }, function (data) {
                $('#totalAmount').text(`${parseFloat(data).toFixed(2)} CRC`);
            });

            const paymentButton = document.querySelector('.bntPagar');
            paymentButton.disabled = true;

            $('input[name="paymentMethod"]').on('change', function () {
                const metodo = $(this).data('descripcion');
                const container = $('#paymentDetails');
                container.empty();
                paymentButton.disabled = false;

                if (metodo === 'SINPE') {
                    container.html(`
                        <p><strong>Por favor envía el comprobante de pago al número <span style="color:#007bff;">+506 78686790</span>.</strong></p>
                        <p>De lo contrario, no se procesará el pedido.</p>
                    `);
                } else if (metodo === 'Efectivo') {
                    container.html(`
                        <p><strong>Por favor realiza el pago a nuestro mensajero el día de la entrega de tu pedido.</strong></p>
                    `);
                }
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const metodoSeleccionado = document.querySelector('input[name="paymentMethod"]:checked');

            if (metodoSeleccionado) {
                const idMetodoPago = metodoSeleccionado.value;
                const descripcion = metodoSeleccionado.dataset.descripcion;
                let mensaje = "";

                if (descripcion === "SINPE") {
                    mensaje = "Pago por SINPE. El cliente debe enviar el comprobante al número +506 78686790.";
                } else if (descripcion === "efectivo") {
                    mensaje = "Pago en efectivo. El cliente pagará al mensajero el día de la entrega.";
                }

                $.post('./data/addArticuloCarrito.php', {
                    action: 'checkout',
                    idMetodoPago: idMetodoPago,
                    metodo: descripcion
                }, function (data) {
                    let respuesta = {};
                    try {
                        respuesta = JSON.parse(data);
                    } catch (e) {
                        Swal.fire("Error", "No se pudo procesar la respuesta del servidor.", "error");
                        return;
                    }

                    if (respuesta.success) {
                        Swal.fire({
                            title: "Procesando Pago...",
                            text: "Gracias por tu compra.",
                            showConfirmButton: false,
                            timer: 3000
                        }).then(() => {
                            Swal.fire({
                                title: "¡Pago Exitoso!",
                                text: "Encontrarás la información de tu pedido en Mi Perfil.",
                                icon: "success",
                                confirmButtonText: "Aceptar",
                                timer: 3000
                            }).then(() => {
                                location.reload();
                            });
                        });
                    } else {
                        Swal.fire("Error", respuesta.message || "No se pudo completar el pago.", "error");
                    }
                }).fail(function () {
                    Swal.fire("Error", "Error de conexión con el servidor.", "error");
                });
            } else {
                Swal.fire("Advertencia", "Por favor selecciona un método de pago.", "warning");
            }
        }
    });
});


</script>
</html>