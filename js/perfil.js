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
                // Determinar color del estado
                let color = '';
                switch (pedido.ESTADO.toLowerCase()) {
                    case 'nuevo':
                        color = 'blue';
                        break;
                    case 'en proceso':
                        color = 'orange';
                        break;
                    case 'en camino':
                        color = 'gold';
                        break;
                    case 'cancelado':
                        color = 'red';
                        break;
                    case 'entregado':
                        color = 'green';
                        break;
                    default:
                        color = 'black';
                }
    
                html += `
                    <tr>
                        <td>${pedido.ID_PEDIDO}</td>
                        <td>${pedido.FECHA}</td>
                        <td>${pedido.METODO_PAGO}</td>
                        <td style="color:${color}; font-weight:bold;">${pedido.ESTADO}</td>
                        <td>₡${pedido.TOTAL}</td>
                        <td>
                            <button class="btnVerDetallePedido" data-id="${pedido.ID_PEDIDO}">
                                <a class="links" href="detallePedidoPerfil.php?idPedido=${pedido.ID_PEDIDO}">Ver detalle</a>
                            </button>
                            ${!['cancelado', 'entregado'].includes(pedido.ESTADO.toLowerCase()) ? `
                            <button class="btnCancelarPedido" data-id="${pedido.ID_PEDIDO}">
                                <a class="links">Cancelar Pedido</a>
                            </button>` : ''}
                        </td>
                    </tr>
                `;
            });
    
            $('#tablaPedidosUsuario').html(html);
        }, 'json');
    }
    
    getPedidosUsuario();
    
    


    $(document).on('click', '.btnCancelarPedido', function () {
        const idPedido = $(this).data('id');
    
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción cancelará tu pedido. No podrás revertirlo.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, cancelar',
            cancelButtonText: 'No, volver',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('./data/accionesPerfil.php', {
                    action: 'cancelarPedido',
                    idPedido: idPedido
                }, function (data) {
                    let respuesta = {};
                    try {
                        respuesta = JSON.parse(data);
                    } catch (e) {
                        Swal.fire("Error", "Respuesta inválida del servidor.", "error");
                        return;
                    }
    
                    if (respuesta.success) {
                        Swal.fire("¡Cancelado!", "Tu pedido ha sido cancelado.", "success").then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire("Error", respuesta.message || "No se pudo cancelar el pedido.", "error");
                    }
                }).fail(() => {
                    Swal.fire("Error", "No se pudo conectar con el servidor.", "error");
                });
            }
        });
    });
    
});
