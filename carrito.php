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
</head>

<body>
    <?php incluir_navbar(); ?>
    <main>
        <section class="cart-section">
            <div class="cart-header text-center">
                <h2>Tu Carrito de Compras</h2>
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
                                    <input type="number" value="<?php echo $producto['cantidad']; ?>" min="1" class="form-control">
                                </td>
                                <td><?php echo number_format($producto['precio'], 2, ',', '.'); ?></td>
                                <td><?php echo number_format($producto['precio'] * $producto['cantidad'], 2, ',', '.'); ?></td>
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
                    <button class="btn btn-secondary">Seguir Comprando</button>
                    <button class="btn btn-primary">Proceder al Pago</button>
                </div>
            </div>
        </section>
    </main>
    <hr>
    <?php incluir_footer(); ?>
</body>

</html>
