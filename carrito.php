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
    <?php incluir_css(); ?>
    <script src="js/java.js"></script>
    <script src="js/jquery-3.7.1.min.js"></script>
</head>

<body>
    <?php incluir_navbar(); ?>
    <main>
        <section class="cart-section">
            <div class="cart-header text-center">
                <h1 class="productosHP text-center">TU CARRITO</h3>
            </div>

            <div class="cart-items">
                <?php
                // Aquí deberías incluir tu lógica PHP para manejar los productos en el carrito.
                // Este es un ejemplo estático para simular los productos en el carrito.
                $productos = [
                    ['nombre' => 'Pan de Manteca', 'precio' => 1500, 'cantidad' => 2],
                    ['nombre' => 'Torta de Zanahoria', 'precio' => 2500, 'cantidad' => 1],
                    ['nombre' => 'Pan con Queso', 'precio' => 1200, 'cantidad' => 3]
                ];
                $total = 0;
                ?>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario (CRC)</th>
                            <th>Total (CRC)</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $producto): ?>
                            <?php $total += $producto['precio'] * $producto['cantidad']; ?>
                            <tr>
                                <td><?php echo $producto['nombre']; ?></td>
                                <td>
                                    <input type="number" value="<?php echo $producto['cantidad']; ?>" min="1"
                                        class="form-control">
                                </td>
                                <td><?php echo number_format($producto['precio'], 2, ',', '.'); ?></td>
                                <td><?php echo number_format($producto['precio'] * $producto['cantidad'], 2, ',', '.'); ?>
                                </td>
                                <td>
                                    <button class="btn btn-danger">Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="cart-total">
                    <h3>Total: <?php echo number_format($total, 2, ',', '.'); ?> CRC</h3>
                </div>

                <div class="cart-actions">
                    <button class="btn btn-secondary"><a style="text-decoration: none; color: #fff"
                            href="productos.php">Seguir Comprando</a></button>
                    <button class="btn btn-primary" id="checkoutBtn">Proceder al Pago</button>
                </div>
            </div>

            <div id="checkoutModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Resumen de Compra</h2>
                    <p>Revisa tu pedido antes de finalizar.</p>

                    <!-- Selección de método de pago -->
                    <label for="paymentMethod">Método de Pago:</label>
                    <select id="paymentMethod" class="selectPago">
                        <option value="">Seleccione un método</option>
                        <option value="sinpe">Sinpe</option>
                        <option value="efectivo">Efectivo</option>
                    </select>

                    <!-- Información adicional para Sinpe -->
                    <div id="sinpeInfo" class="hidden">
                        <p><strong>Por favor realice el pago al siguiente número: <span
                                    class="phone-number">8888-8888</span></strong></p>
                        <label for="paymentAttachment" class="adjuntarComprobante">Adjuntar Comprobante</label>
                        <input type="file" id="paymentAttachment">
                        <p id="file-name"></p>
                    </div>

                    <div class="cart-summary">
                        <p class="total">Total: <strong>$XX.XX</strong></p>
                    </div>

                    <div class="modal-actions">
                        <button class="btn btn-secondary cancelar" id="closeModal">Cancelar</button>
                        <button class="btn btn-primary pagar" id="pagarYa">Pagar Ahora</button>
                    </div>
                </div>
            </div>

            <div id="loading" class="hidden">
                <div class="spinner"></div>
                <p>Procesando pago...</p>
            </div>

            <!-- Mensaje de éxito -->
            <div id="successMessage" class="hidden">
                <h2>✅ Pedido realizado con éxito</h2>
                <p>Gracias por tu compra.</p>
                <button class="btn-primary" id="closeSuccess">Aceptar</button>
            </div>


        </section>
    </main>
    <hr>
    <?php incluir_footer(); ?>
</body>

</html>