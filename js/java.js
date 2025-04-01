



document.addEventListener("DOMContentLoaded", function () {
    console.log("✅ DOM completamente cargado.");
 


    obtenerProductos();
  

    let links = document.querySelectorAll(".opciones a");
    let currentUrl = window.location.pathname.split("/").pop(); // Obtiene el nombre del archivo actual

    links.forEach(link => {
        if (link.getAttribute("href") === currentUrl) {
            link.classList.add("active"); // Agrega la clase 'active' al enlace actual
        }
    });



    function toggleDropdown() {
        document.getElementById("dropdownMenu").classList.toggle("active");
    }
    

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
                    <div class="product-image">🥗</div>
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
    
        // Agregar evento a los botones después de crear los elementos
    
    }
    
    const modal = document.getElementById("checkoutModal");
    const modalContent = document.getElementById("modalContent");
    const openModalBtn = document.getElementById("checkoutBtn");
    const closeModalBtn = document.getElementById("closeModal");
    const closeSuccessBtn = document.getElementById("closeSuccess");
    const closeSpan = document.querySelector(".close");
    const paymentMethod = document.getElementById("paymentMethod");
    const sinpeInfo = document.getElementById("sinpeInfo");
    const pagarYaBtn = document.getElementById("pagarYa");
    const loading = document.getElementById("loading");
    const successMessage = document.getElementById("successMessage");

    // Abrir modal
    openModalBtn.addEventListener("click", () => {
        modal.style.display = "flex";
        modalContent.style.display = "block";
        loading.classList.add("hidden");
        successMessage.classList.add("hidden");
    });

    // Cerrar modal
    closeModalBtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    closeSpan.addEventListener("click", () => {
        modal.style.display = "none";
    });

    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });

    // Mostrar información de Sinpe si se selecciona
    paymentMethod.addEventListener("change", function () {
        if (this.value === "sinpe") {
            sinpeInfo.classList.remove("hidden");
        } else {
            sinpeInfo.classList.add("hidden");
        }
    });

    // Simulación de pago al hacer clic en "Pagar Ahora"
    pagarYaBtn.addEventListener("click", function () {
        modalContent.style.display = "none"; // Oculta el contenido normal del modal
        loading.classList.remove("hidden"); // Muestra animación de carga

        setTimeout(() => {
            loading.classList.add("hidden"); // Oculta animación de carga
            successMessage.classList.remove("hidden"); // Muestra mensaje de éxito
        }, 3000); // Simula un tiempo de procesamiento de 3 segundos
    });

    // Cerrar mensaje de éxito
    closeSuccessBtn.addEventListener("click", function () {
        modal.style.display = "none";
    });

    //=========================================================================================================
    //LOGICA PARA EL CARRITO

    function getCarrito() {
        $.ajax({
            url: './data/getItemsCarrito.php',  // Cambia esto por la ruta correcta al archivo PHP que ejecuta la lógica de la base de datos
            type: 'GET',  // Usamos GET ya que no estamos enviando datos, solo pidiendo los datos del carrito
            dataType: 'json',  // Esperamos una respuesta en formato JSON
            success: function(data) {
                if (data.error) {
                    alert(data.error);
                } else {
                    let total = 0;
                    $('#tablaCarrito tbody').empty(); 
                
                    data.forEach(function(producto) {
                        let fila = `
                            <tr>
                                <td>${producto.NOMBRE}</td>
                                <td>
                                    ${producto.CANTIDAD}
                                </td>
                                <td>${producto.PRECIO.toLocaleString('es-CR')}</td>
                                <td>${(producto.PRECIO * producto.CANTIDAD).toLocaleString('es-CR')}</td>
                                <td>
                                    <button class="btn btn-danger" id="btnEliminarCarrito" data-id=${producto.ID_ARTICULO}">Eliminar</button>
                                    <button class="btn btn-warning" id="btnEditarCarrito" data-id=${producto.ID_ARTICULO}">Editar</button>
                                </td>
                            </tr>
                        `;
                        $('#tablaCarrito').append(fila);
                        total += producto.PRECIO * producto.CANTIDAD;
                    });

                    $('.cart-total h3').text('Total: ' + total.toLocaleString('es-CR') + ' CRC');
                }
            },
            error: function(xhr, status, error) {
               
                console.log(error);
                alert('Error al cargar el carrito.');
            }
        });
    }

    getCarrito();

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
                alert("Error en la respuesta del servidor");
                return;
            }
    
            if (response.success) {
                alert(response.success); 
                location.reload();
            } else {
                console.log(response.error);
                alert(response.error); 
            }
        });
    });
        
    });

    



























































