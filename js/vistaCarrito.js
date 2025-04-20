



document.addEventListener("DOMContentLoaded", function () {

    function getCarrito() {
        $.post('./data/addArticuloCarrito.php', { action: 'getCarrito' }, function (data) {
            if (data.error) {
                Swal.fire({
                    title: "Atención",
                    text: data.error,
                    icon: "warning",
                    confirmButtonText: "Iniciar sesión"
                });
                return;
            }
    
            let total = 0;
            $('#tablaCarrito tbody').empty();
    
            data.forEach(function (producto) {
                let fila = `
                    <tr>
                        <td>${producto.NOMBRE}</td>
                        <td>
                            <input type="number" class="form-control cantidad-input" 
                                   value="${producto.CANTIDAD}" 
                                   data-id="${producto.ID_ARTICULO}" min="1">
                        </td>
                        <td>₡${parseFloat(producto.PRECIO).toLocaleString('es-CR')}</td>
                        <td>₡${(producto.PRECIO * producto.CANTIDAD).toLocaleString('es-CR')}</td>
                        <td>
                            <button class="btn btn-danger btn-sm" id="btnEliminarCarrito" data-id="${producto.ID_ARTICULO}">Eliminar</button>
                            <button class="btn btn-warning btn-sm" id="btnEditarCarrito" data-id="${producto.ID_ARTICULO}">Editar</button>
                        </td>
                    </tr>
                `;
                $('#tablaCarrito tbody').append(fila);
                total += producto.PRECIO * producto.CANTIDAD;
            });
    
            $('.cart-total h3').text('Total: ₡' + total.toLocaleString('es-CR'));
        }, 'json')
        .fail(function (xhr, status, error) {
            console.error(error);
            Swal.fire({
                title: "Error",
                text: "Ocurrió un error al intentar cargar el carrito.",
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
    
 
    getCarrito();
});