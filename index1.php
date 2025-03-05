<?php
// Incluir el archivo de fragmentos
require_once 'fragmentos.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribuidora El Legado - Inicio</title>
    <?php incluir_css(); ?>
    <!-- Estilos específicos de esta página -->
    <link rel="stylesheet" href="css/index1.css">
</head>
<body>
    <!-- Incluir el navbar -->
    <?php incluir_navbar(); ?>
    
    <!-- Contenido de la página de inicio -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">Bienvenidos a El Legado</h1>
            <p class="hero-subtitle">Los mejores productos alimenticios para su negocio</p>
            <a href="productos.php" class="btn">Ver Productos</a>
        </div>
    </section>
    
    <section class="products-section">
        <div class="container">
            <h2 class="section-title">Nuestros Productos Destacados</h2>
            
            <div class="products-grid">
                <div class="product-card">
                    <div class="product-image">🍔</div>
                    <h3 class="product-title">Hamburguesa Premium</h3>
                    <p class="product-description">La mejor calidad para su restaurante</p>
                    <p class="product-price">$15.99</p>
                    <button class="btn">Añadir al carrito</button>
                </div>
                
                <div class="product-card">
                    <div class="product-image">🍰</div>
                    <h3 class="product-title">Postre de Chocolate</h3>
                    <p class="product-description">Delicioso postre para complementar su menú</p>
                    <p class="product-price">$8.50</p>
                    <button class="btn">Añadir al carrito</button>
                </div>
                
                <div class="product-card">
                    <div class="product-image">🥪</div>
                    <h3 class="product-title">Sandwich de Pollo</h3>
                    <p class="product-description">Ideal para comidas rápidas</p>
                    <p class="product-price">$12.75</p>
                    <button class="btn">Añadir al carrito</button>
                </div>
                
                <div class="product-card">
                    <div class="product-image">🥗</div>
                    <h3 class="product-title">Hamburguesa Vegetariana</h3>
                    <p class="product-description">Opción saludable para su menú</p>
                    <p class="product-price">$14.25</p>
                    <button class="btn">Añadir al carrito</button>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Incluir el footer -->
    <?php incluir_footer(); ?>
</body>
</html>