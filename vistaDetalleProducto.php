<!DOCTYPE html>
<html lang="en">

<?php
// Incluir el archivo de fragmentos
require_once 'fragmentos.php';

require 'conexion.php';

$idProducto = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($idProducto > 0) {
    $url = "http://localhost/Proyectos/Proyecto-Lenguajes-de-BD/data/obtenerProductoPorID.php?id=" . $idProducto;
    $productoJson = file_get_contents($url);
    $producto = json_decode($productoJson, true);

    if (!$producto || count($producto) === 0) {
        echo "<p>No se encontró el producto.</p>";
        exit;
    }
} else {
    echo "<p>ID de producto no válido.</p>";
    exit;
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Legado</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="../css/index.css">
    <script src="js/carrito.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <?php incluir_css(); ?>
    <script src="js/java.js"></script>
    <script src="js/jquery-3.7.1.min.js"></script>

</head>

<body>
    <?php incluir_navbar(); ?>
    <main>
        <section>
            <br>
            <div class="mainContainer container-fluid row">
                <div class="col">
                    <img class="object-fit-lg-contain border rounded imagenHP p-5" style="height: 700px;" src='<?= $producto[0]['IMAGEN'] ?>'>
                </div>
                <div class="col align-self-center p-5">
                    <div id="container-producto">
                        <h2 class="text-center textos"><?= $producto[0]['NOMBRE'] ?></h2>
                        <p class="text-center"><b>₡<?= $producto[0]['PRECIO'] ?></b></p>
                        <br>
                        <h5><?= $producto[0]['DESCRIPCION'] ?></h5>
                        <hr>
                        <br>
                        <div class="mb-4">
                            <label for="quantity" class="form-label">Cantidad</label>
                            <input type="number" class="form-control" id="cantidadCarrito" value="1" min="1"
                                style="width: 80px;">
                        </div>
                        <br>
                        <button class="btn bg-light" id="btnAgregarCarrito" data-id="<?= $producto[0]['ID_PRODUCTO'] ?>">
                            <a href="#" class="fw-bold text-dark">🛒 Agregar al Carrito</a>
                        </button>
                    </div>
                </div>
            </div>





            <hr>
        </section>

    </main>

    <?php incluir_footer(); ?>

    <script>
    $(document).on("click", "#btnAgregarCarrito", function () {
        console.log("hola");
        let idProducto = $(this).data("id"); 
        console.log(idProducto);
        let cantidad = $("#cantidadCarrito").val();
    
        $.post("./data/addArticuloCarrito.php", {
    action: "add",
    idProducto: idProducto,
    cantidad: cantidad
}, function (data, status) {
    let response;
    console.log(response);
    try {
        response = JSON.parse(data); 
    } catch (e) {
        Swal.fire({
            title: "Error",
            text: "Error en la respuesta del servidor",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return;
    }

    if (response.success) {
        Swal.fire({
            title: "Éxito",
            text: response.success,
            icon: "success",
            confirmButtonText: "Aceptar"
        }).then(() => {
            location.reload(); // Recarga la página después de mostrar el mensaje
        });
    } else {
        console.log(response.error);
        Swal.fire({
            title: "Error",
            text: response.error,
            icon: "error",
            confirmButtonText: "Aceptar"
        });
    }
});

    });

   

    </script>
    
</body>

</html>