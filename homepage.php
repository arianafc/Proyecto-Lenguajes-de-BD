<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Legado</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css" >
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <?php 
        include("fragmentos.php");
        echo $navbar;
    ?>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-container">
            <h1 class="title">SE PARTE DE <br> NUESTRA FAMILIA</h1>
            <p class="welcome-text">
            ¡Bienvenido a Casa Natura! Aquí puedes marcar la diferencia en la vida de los animales que más lo necesitan. Nuestra misión es conectar corazones con causas nobles, facilitando el patrocinio de animales rescatados. 
            Te invitamos a conocer más de nosotros, y ¿por qué no formar parte de nuestra comunidad?
            </p>
            <button class="about-btn"><a href="quienessomos.php">ACERCA DE NOSOTROS</a></button>
        </div>

        <div class="image-container">
            <img src="imagenes/img1.png" alt="Descripción de la imagen" class="img-fluid">
        </div>
    </div>

    <h2 class="family-title">CONOCÉ A NUESTRA FAMILIA</h2>
    
<div class="d-flex justify-content-center mb-5">
    <div id="animalCarouselHP" class="carousel slide" data-bs-ride="carousel" data-bs-interval="7000">
        <div class="carousel-inner">
            <?php
            include('./actions/conexion.php'); // Incluir la conexión aquí
            
            $query = "SELECT Imagen, Nombre, Raza, Estado_Salud FROM animal";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                $index = 0;
                while ($animal = $result->fetch_assoc()): ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <div class="carousel-contentHP d-flex">
                            <div class="image-containerHP w-50">
                                <img src="./actions/<?php echo $animal['Imagen']; ?>" alt="<?php echo htmlspecialchars($animal['Nombre']); ?>" class="img-fluid">
                            </div>
                            <div class="text-container-carouselHP w-50">
                                <h3><?php echo htmlspecialchars($animal['Nombre']); ?> - <?php echo htmlspecialchars($animal['Raza']); ?></h3>
                                <p>Estado de Salud: <?php echo htmlspecialchars($animal['Estado_Salud']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php 
                $index++;
                endwhile;
            } else {
                echo "<p>No se encontraron animales.</p>";
            }
            ?>
        </div>

        <a class="carousel-control-prev" data-bs-target="#animalCarouselHP" data-bs-slide="prev">
            <i class="fas fa-arrow-left" aria-hidden="true"></i> 
        </a>
        <a class="carousel-control-next" data-bs-target="#animalCarouselHP" data-bs-slide="next">
            <i class="fas fa-arrow-right" aria-hidden="true"></i> 
        </a>
    </div>
</div>

    <!-- Sección inferior -->
    <div class="bottom-section">
        <div class="left-image">
            <img src="imagenes/img3.png" alt="Descripción de la imagen" class="img-fluid-info">
        </div>
        <div class="right-box">
            <a href="contactanos.php">
                ¿Deseas conocer más <br> acerca de <br> nuestro refugio? Contáctanos!
                <span class="arrow">→</span>
            </a>
        </div>
    </div>

    <?php 
    include("fragmentos.php");
    echo $footer;
    ?>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>