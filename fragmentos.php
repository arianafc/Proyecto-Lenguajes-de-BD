<?php
/**
 * Función para incluir los archivos CSS principales
 */
function incluir_css()
{
    echo '<link rel="stylesheet" href="css/index.css">';
    echo '<link rel="stylesheet" href="css/navbar.css">';
    echo '<link rel="stylesheet" href="css/footer.css">';
    echo '<link rel="stylesheet" href="css/index1.css">';
    echo '<link rel="stylesheet" href="css/dashboard.css">';
    echo '<link rel="stylesheet" href="css/detalleProducto.css">';
    echo '<link rel="stylesheet" href="css/contacto.css">';
    echo '<link rel="stylesheet" href="css/cotizacion.css">';
    echo '<link rel="stylesheet" href="css/carrito.css">';
    echo '<link href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre&display=swap" rel="stylesheet">';
    echo '<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">';
}

/**
 * Función para incluir el navbar
 */
function incluir_navbar()
{
    ?>
    <!-- Topbar -->
    <div class="topbar">
        <div class="location">
            <span class="location-icon">📍</span>
            <span>Cartago, Costa Rica</span>
        </div>

        <div class="auth-links"><a href="login.php"> Sign In / Sign Up</a>

        </div>
    </div>
    </div>

    <!-- Main Navbar -->
    <div class="navbar">
        <div class="logo-container">
            <span class="logo-icon"> </span>
            <span class="logo-text">DISTRIBUIDORA EL LEGADO</span>
        </div>

        <div class="navbar-right">
            <a href="carrito.php" class="cart-container">
                <div class="cart-icon">🛒</div>
            </a>

        </div>
    </div>

    <!-- Main Menu -->
    <div class="main-menu">
        <ul class="menu-items">
            <li><a href="index.php">Home <span class="dropdown-indicator">▾</span></a></li>
            <li><a href="productos.php">Productos <span class="dropdown-indicator">▾</span></a></li>
            <li><a href="contactanos.php">Contáctanos</a></li>
            <li><a href="nosotros.php">Nosotros</a></li>
            <li><a href="cotizaciones.php">Cotizaciones</a></li>
        </ul>

        <div class="phone-number">
            <span class="phone-icon">📞</span>
            <span>(+506) 87858978</span>
        </div>

        <div class="mobile-menu-toggle" id="mobile-menu-toggle">☰</div>
    </div>
    <?php
}



/**
 * Función para incluir el footer
 */
function incluir_footer()
{
    ?>
    <footer class="footer">
        <div class="footer-divider"></div>

        <div class="footer-content">
            <div class="footer-section">
                <h3>Productos</h3>
                <ul>
                    <li><a href="productos.php?categoria=hamburguesas">Hamburguesas</a></li>
                    <li><a href="productos.php?categoria=postres">Postres</a></li>
                    <li><a href="productos.php?categoria=sandwich">Sandwich</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Nosotros</h3>
                <ul>
                    <li><a href="nosotros.php?seccion=mision">Misión</a></li>
                    <li><a href="nosotros.php?seccion=vision">Visión</a></li>
                    <li><a href="nosotros.php?seccion=quienes-somos">¿Quiénes somos?</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Ayuda</h3>
                <ul>
                    <li><a href="contactanos.php">Contáctanos</a></li>
                </ul>
            </div>

            <div class="company-name">
                <h2>DISTRIBUIDORA</h2>
                <h1>EL LEGADO</h1>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="copyright">
                Derechos Reservados El Legado @ 2025
            </div>

            <div class="footer-language-selector">
                Español
            </div>
        </div>
    </footer>
    <?php
}




function sidebar()
{
    ?>
    
    <!-- Sidebar -->
    <nav id="sidebar" class="sidebar py-3 d-md-block">
        <div class="sidebar-header text-center">
            <h4 class="tituloAdmin">Distribuidora El Legado</h4>
        </div>


        <hr>
        <div class="opciones">   
        <a href="dashboard.php" id="dashboard">Dashboard</a>
        <a href="gestionUsuarios.php" id="gestionUsuarios">Gestión de Usuarios</a>
        <a href="gestionPedidos.php" id="gestionPedidos">Gestión de Pedidos</a>
        <a href="gestionProductos.php" id="gestionProductos">Gestión de Productos</a>
        <a href="gestionInventario.php" id="gestionInventario">Gestión de Inventario</a>
        <a href="gestionCotizaciones.php" id="gestionCotizaciones">Gestión de Consultas</a></div>
    </nav>

    <!-- Botón de menú -->
    <button class="btn btn-toggle d-md-none m-3" id="menu-toggle">☰</button>

   
    <?php
}


function footerAdmin()
{
    ?>
    <footer class="footerAdmin">
        <div class="footerAdmin-title">
            <h2 class="footerAdmin-title">
                <div class="logo">
                    <img class="img" src="./imagenes/logo.png">
                    <div>
                        <span style="color: #fff;">CASA</span>
                        <br>
                        <span class="highlight">NATURA</span>
                    </div>

                    <img class="img" src="./imagenes/footer.png">
                </div>
            </h2>
        </div>

        <?php

}
?>