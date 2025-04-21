
document.addEventListener("DOMContentLoaded", function () {
cargarConsultas();

function cargarConsultas() {
    $.post('./data/accionesConsultas.php', { action: 'obtenerConsultas' }, function (data) {
        let consultas = data;
        let html = '';

        consultas.forEach(c => {
            // Estado visual
            let estadoBadge = '';
            switch (parseInt(c.ID_ESTADO)) {
                case 4: // Nueva
                    estadoBadge = '<span class="badge bg-success">Nueva</span>';
                    break;
                case 3: // En proceso
                    estadoBadge = '<span class="badge bg-warning text-dark">En proceso</span>';
                    break;
                case 6: // Contestada
                    estadoBadge = '<span class="badge bg-primary">Contestada</span>';
                    break;
                default:
                    estadoBadge = '<span class="badge bg-secondary">Desconocido</span>';
                    break;
            }

            // Tipo visual
            let tipoBadge = '';
            if (c.TIPO.toLowerCase() === 'Cotización') {
                tipoBadge = '<span class="badge bg-info">Cotización</span>';
            } else if (c.TIPO.toLowerCase() === 'Consulta') {
                tipoBadge = '<span class="badge bg-success">Consulta</span>';
            } else {
                tipoBadge = `<span class="badge bg-secondary">${c.TIPO}</span>`;
            }

            // Botones de acción
            let acciones = '';
            if (parseInt(c.ID_ESTADO) === 4) {
                acciones += `
                    <button class="btn btn-sm btn-warning btnCambiarEstado" data-id="${c.ID_CONSULTA}" data-accion="2">En revisión</button>
                    <button class="btn btn-sm btn-primary btnCambiarEstado" data-id="${c.ID_CONSULTA}" data-accion="1">Contestada</button>
                `;
            } else if (parseInt(c.ID_ESTADO) === 3) {
                acciones += `
                    <button class="btn btn-sm btn-primary btnCambiarEstado" data-id="${c.ID_CONSULTA}" data-accion="1">Contestada</button>
                `;
            }

            html += `
                <tr>
                    <td>${c.USUARIO}</td>
                    <td>${c.EMAIL}</td>
                    <td>${c.TELEFONO_ACTIVO ?? 'Sin teléfono'}</td>
                    <td>${tipoBadge}</td>
                    <td>${c.MENSAJE}</td>
                    <td>${c.FECHA ?? 'N/A'}</td>
                    <td>${estadoBadge}</td>
                    <td>${acciones}</td>
                </tr>
            `;
        });

        $('#tablaConsultasDash').html(html);
    });
}



$(document).on('click', '.btnCambiarEstado', function () {
    const id = $(this).data('id');
    const accion = $(this).data('accion');

    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción cambiará el estado de la consulta.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('./data/accionesConsultas.php', {
                action: 'actualizarEstadoConsulta',
                id: id,
                accion: accion
            }, function (response) {
                const res = response;
                if (res.success) {
                    Swal.fire('Actualizado', res.mensaje, 'success');
                    cargarConsultas();
                } else {
                    Swal.fire('Error', res.error || 'No se pudo actualizar.', 'error');
                }
            });
        }
    });
});
});




