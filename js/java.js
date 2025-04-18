



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
                    // Si hay un error en la respuesta, muestra una alerta de error
                    Swal.fire({
                        title: "Error",
                        text: data.error,
                        icon: "error",
                        confirmButtonText: "Aceptar"
                    });
                } else {
                    let total = 0;
                    $('#tablaCarrito tbody').empty();  // Limpiar la tabla del carrito antes de agregar los nuevos productos
        
                    // Recorre los productos recibidos y agrega filas a la tabla
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
            },
            error: function(xhr, status, error) {
              
                console.log(error);
                Swal.fire({
                    title: "Error",
                    text: 'Error al cargar el carrito.',
                    icon: "error",
                    confirmButtonText: "Aceptar"
                });
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
        let cantidad = $("#cantidadArticulo").val();  // Cambié .value() por .val()
        console.log(idArticulo);
        console.log(cantidad);
       
        $.post("./data/addArticuloCarrito.php", {
            action: "editar",
            idArticulo: idArticulo,
            cantidad: cantidad,
        }, function (data, status) {
            let response;
            console.log(data);  // Asegúrate de que esta línea imprima la respuesta
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

    $(document).on('click', '#checkoutBTN', function () {
        Swal.fire({
            title: "TU COMPRA",
            html: `
                <p>Revisa tu pedido antes de finalizar.</p>
                <label for="paymentMethod">Método de Pago:</label>
                <div id="paymentOptions">
                    <label style="cursor: pointer; display: flex; align-items: center; gap: 10px;">
                        <input type="radio" name="paymentMethod" value="SINPE" data-descripcion="sinpe">
                        SINPE
                    </label>
                    <label style="cursor: pointer; display: flex; align-items: center; gap: 10px;">
                        <input type="radio" name="paymentMethod" value="EFECTIVO" data-descripcion="efectivo">
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
                $.post('./data/carritoActions.php', { action: 'totalCarrito' }, function (data) {
                    $('#totalAmount').text(`${parseFloat(data).toFixed(2)} CRC`);
                });
    
                const container = document.getElementById("paymentDetails");
                const paymentButton = document.querySelector('.bntPagar');
                paymentButton.disabled = true;
    
                $('input[name="paymentMethod"]').on('change', function () {
                    const metodo = $(this).data('descripcion');
                    container.innerHTML = "";
                    paymentButton.disabled = true;
    
                    if (metodo === "sinpe") {
                        container.innerHTML = `
                            <p><strong>Realice el pago por SINPE Móvil al número <span class="text">8888-8888</span></strong></p>
                            <p><strong>A nombre de: El Legado de Mis Padres</strong></p>
                            <label for="transferCode">Código de Transferencia:</label>
                            <input type="text" id="transferCode" placeholder="Ej: TRX12345" class="swal2-input"><br>
                            <label for="paymentAttachment" class="adjuntarComprobante">Adjuntar Comprobante</label>
                            <input type="file" id="paymentAttachment" class="swal2-file">
                            <p id="file-name"></p>
                        `;
    
                        $('#transferCode, #paymentAttachment').on('change', function () {
                            if ($('#transferCode').val() !== "" && $('#paymentAttachment')[0].files.length > 0) {
                                paymentButton.disabled = false;
                            } else {
                                paymentButton.disabled = true;
                            }
                        });
    
                    } else if (metodo === "efectivo") {
                        container.innerHTML = `
                            <p><strong>El pago se realizará en efectivo al momento de recoger o disfrutar tu reserva.</strong></p>
                        `;
                        paymentButton.disabled = false;
                    }
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const metodoSeleccionado = document.querySelector('input[name="paymentMethod"]:checked');
    
                if (metodoSeleccionado) {
                    let metodoPago = metodoSeleccionado.value;
                    console.log("Método de pago seleccionado:", metodoPago);
    
                    $.post('./data/carritoActions.php', { action: 'checkout', metodoPago: metodoPago }, function (data) {
                        if (data.success) {
                            Swal.fire({
                                title: "Procesando Pago...",
                                text: "Contactando con la entidad financiera...",
                                showConfirmButton: false,
                                timer: 5000
                            }).then(() => {
                                Swal.fire({
                                    title: "¡Pago Exitoso!",
                                    text: "Tu pago ha sido completado correctamente. Encontrarás la información en Mi Perfil.",
                                    icon: "success",
                                    confirmButtonText: "Aceptar",
                                    timer: 3000
                                }).then(() => {
                                    location.reload();
                                });
                            });
                        } else {
                            Swal.fire({
                                title: "No pudimos completar tu transacción.",
                                text: "Tu pago no ha sido completado correctamente.",
                                icon: "error",
                                confirmButtonText: "Aceptar"
                            });
                        }
                    }, 'json');
                }
            }
        });
    });
    
    });
