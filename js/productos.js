document.addEventListener("DOMContentLoaded", function () {

 
    

    console.log("✅ DOM completamente cargado.");


    obtenerProductos();
  

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
        let contenedor = document.getElementById("contenedor-productosIndex");
    
        productos.slice(0, 4).forEach(producto => {
            let card = document.createElement("div");
            card.classList.add("products-grid", "p-2");
            card.innerHTML = `
                <div class="product-card ">
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

    $.post("./data/accionesCategorias.php", { action: "obtenerCategorias" }, function (data) {
        const categorias = data;
        const contenedor = $("#filtros-categorias");

        contenedor.append('<button class="btn btn-outline-dark m-1" data-id="0" id="btnTodosProductos">Todos</button>');

      
        categorias.forEach(function (cat) {
            contenedor.append(
                `<button class="btn btn-outline-primary m-1 btn-categoria" data-id="${cat.ID_CATEGORIA}">${cat.DESCRIPCION}</button>`
            );
        });

     
    });

    function cargarProductosPorCategoria(categoriaId) {
        $.post("./data/accionesProducto.php", {
            action: "obtenerProductosPorCategoria",
            categoria: categoriaId
        }, function (respuesta) {
            let productos = respuesta 
            let contenedor = $("#contenedor-productos");
            contenedor.empty();
    
            if (productos.length === 0 || productos[0].ID_PRODUCTO === null) {
                contenedor.append("<p class='text-center'>No hay productos disponibles en esta categoría.</p>");
                return;
            }
    
            productos.forEach(producto => {
                let card = document.createElement("div");
            card.classList.add("products-grid", "p-2");
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
                contenedor.append(card);
            });
        });
    }
    
    $(document).on("click", ".btn-categoria", function () {
        let categoriaId = $(this).data("id");
        cargarProductosPorCategoria(categoriaId);
    });
    

});