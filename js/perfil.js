document.addEventListener("DOMContentLoaded", function () {

    function getPedidosUsuario() {
        $.post('./data/accionesPerfil.php', { action: 'obtenerPedidos' }, function (response) {
            if (response.error) {
                $('#tablaPedidosUsuario').html(`<tr><td colspan="4">${response.error}</td></tr>`);
                return;
            }

            if (!response.tienePedidos) {
                $('#tablaPedidosUsuario').html(`<tr><td colspan="4">${response.mensaje}</td></tr>`);
                return;
            }

            const pedidos = response.pedidos;
            let html = '';

            pedidos.forEach(pedido => {
                html += `
                <tr>
                    <td>${pedido.ID_PEDIDO}</td>
                    <td>${pedido.FECHA}</td>
                   <td>${pedido.METODO_PAGO}</td>
                    <td>${pedido.ESTADO}</td>
                    <td>₡${pedido.TOTAL}</td>
                     <td>
                       <button class="btnVerDetallePedido" data-id="${pedido.ID_PEDIDO}">
    <a class="links" href="detallePedidoPerfil.php?idPedido=${pedido.ID_PEDIDO}">Ver detalle</a>
</button>
                    </td>
                </tr>
            `;
            });

            $('#tablaPedidosUsuario').html(html);
        }, 'json');

    }
    getPedidosUsuario();



});
