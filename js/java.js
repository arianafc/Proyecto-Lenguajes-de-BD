



document.addEventListener("DOMContentLoaded", function () {

 
    

    console.log("✅ DOM completamente cargado.");


    obtenerProductos();
  
    getCarrito();

    let links = document.querySelectorAll(".opciones a");
    let currentUrl = window.location.pathname.split("/").pop(); 

    links.forEach(link => {
        if (link.getAttribute("href") === currentUrl) {
            link.classList.add("active"); 
        }
    });


    function obtenerProductos() {
        fetch('./data/obtenerProductos.php')
            .then(response => response.json())  // 🔹
            .then(data => {
                console.log("Productos obtenidos:", data);
                if (data.length === 0) {
                    console.log("No hay productos disponibles.");
                } else {
                    mostrarProductos(data);
                }
            })
            .catch(error => console.error("Error al obtener productos:", error));
    }

    function mostrarProductos(productos) {
        let contenedor = document.getElementById("contenedor-productos");
    
        productos.forEach(producto => {
            let card = document.createElement("div");
            card.classList.add("products-grid");
            card.innerHTML = `
                <div class="product-card">
                    <h3 class="product-title">${producto.NOMBRE}</h3>
                    <p class="product-description">${producto.DESCRIPCION}</p>
                    <p class="product-price">₡${producto.PRECIO}</p>
                   <button class="btn bg-light">
    <a href="vistaDetalleProducto.php?id=${producto.ID_PRODUCTO}">Ver producto</a>
</button>
                </div>
            `;
    
            contenedor.appendChild(card);
        });
    
    
    }
    
    //=========================================================================================================
    //LOGICA PARA EL CARRITO

    function getCarrito() {
        $.post('./data/addArticuloCarrito.php', {action: 'getCarrito'}, function(data) {
            if (data.error) {
                Swal.fire({
                    title: "Error",
                    text: data.error,
                    icon: "error",
                    confirmButtonText: "Aceptar"
                });
            } else {
                let total = 0;
                $('#tablaCarrito tbody').empty(); 
        
                data.forEach(function(producto) {
                    let fila = `
                        <tr>
                            <td>${producto.NOMBRE}</td>
                            <td> <input type="number" class="form-control cantidad-input" id="cantidadArticulo" value="${producto.CANTIDAD}" data-id="${producto.ID_ARTICULO}" min="1"></td>
                            <td>${producto.PRECIO.toLocaleString('es-CR')}</td>
                            <td>${(producto.PRECIO * producto.CANTIDAD).toLocaleString('es-CR')}</td>
                            <td>
                                <button class="btn btn-danger" id="btnEliminarCarrito" data-id="${producto.ID_ARTICULO}">Eliminar</button>
                                <button class="btn btn-warning" id="btnEditarCarrito" data-id="${producto.ID_ARTICULO}">Editar</button>
                            </td>
                        </tr>
                    `;
                    $('#tablaCarrito').append(fila);
                    total += producto.PRECIO * producto.CANTIDAD;
                });
        
                $('.cart-total h3').text('Total: ' + total.toLocaleString('es-CR') + ' CRC');
            }
        }, 'json').fail(function(xhr, status, error) {
            console.log(error);
            Swal.fire({
                title: "Error",
                text: 'Error al cargar el carrito.',
                icon: "error",
                confirmButtonText: "Aceptar"
            });
        });
        
        
    }


    $(document).on("click", "#btnEliminarCarrito", function () {
        console.log("hola");
        let idProducto = $(this).data("id"); 
        console.log(idProducto);
       
        $.post("./data/addArticuloCarrito.php", {
            action: "delete",
            idProducto: idProducto,
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
                    text: "Producto eliminado correctamente.",
                    icon: "success",
                    confirmButtonText: "Aceptar"
                }).then(() => {
                    location.reload();
                });
            } else {
                console.log(response.error);
                Swal.fire({
                    title: "Error",
                    text: response.error || "Hubo un problema al eliminar el producto.",
                    icon: "error",
                    confirmButtonText: "Aceptar"
                });
            }
        });
    });
    
    
    $(document).on("click", "#btnEditarCarrito", function () {
        let idArticulo = $(this).data("id"); 
        let cantidad = $("#cantidadArticulo").val();  
        console.log(idArticulo);
        console.log(cantidad);
       
        $.post("./data/addArticuloCarrito.php", {
            action: "editar",
            idArticulo: idArticulo,
            cantidad: cantidad,
        }, function (data, status) {
            let response;
            console.log(data);  
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
                    text: "Producto actualizado correctamente.",
                    icon: "success",
                    confirmButtonText: "Aceptar"
                }).then(() => {
                    location.reload();
                });
            } else {
                console.log(response.error);
                Swal.fire({
                    title: "Error",
                    text: response.error || "Hubo un problema al actualizar el producto.",
                    icon: "error",
                    confirmButtonText: "Aceptar"
                });
            }
        });
    });
    
    //CHECKOUT
   
    
    
    
    
    
    
    });

