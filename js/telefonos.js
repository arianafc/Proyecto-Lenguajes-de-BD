$(document).ready(function () {

    $('#btnAgregarTelefono').on('click', function () {
        Swal.fire({
            title: 'Agregar Teléfono',
            html: `
                <input id="swal-telefono" type="number" class="swal2-input" placeholder="Número de teléfono">
            `,
            focusConfirm: false,
            preConfirm: () => {
                const telefono = $('#swal-telefono').val().trim();
    
                if (!telefono) {
                    Swal.showValidationMessage('Debe ingresar un número de teléfono');
                    return false;
                }
    
                return { telefono };
            },
            confirmButtonText: 'Guardar',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
        }).then(result => {
            if (result.isConfirmed && result.value) {
                agregarTelefono(result.value.telefono);
            }
        });
    });



    
    function agregarTelefono(telefono) {
    
        $.post('data/accionesTelefonos.php', {
            action: 'crudTelefono',
            accion: 1,
            telefono: telefono
        }, function (response) {
            const res = response;
            if (res.success) {
                Swal.fire("Éxito", res.message, "success");
               location.reload();
            } else {
                Swal.fire("Error", res.message, "error");
            }
        });
    }
    

    $(document).on('click', '.btnEliminarTelefono', function(){
        const id = $(this).data('id');
            $.post('data/accionesTelefonos.php', {
                action: 'crudTelefono',
                accion: 3,
                idTelefono: id
            }, function (response) {
                const res = response;
                if (res.success) {
                    Swal.fire("Éxito", res.message, "success");
                   location.reload();
                } else {
                    Swal.fire("Error", res.message, "error");
                }
            });
        
    });

    $(document).on('click', '.btnEditarTelefono', function () {
        const telefono = $(this).data('telefono');
        const idTelefono = $(this).data('id');
    
        Swal.fire({
            title: 'Editar Teléfono',
            html: `
                <input id="swal-telefono" type="number" class="swal2-input" placeholder="Número de teléfono" value="${telefono}">
            `,
            focusConfirm: false,
            preConfirm: () => {
                const nuevoTelefono = $('#swal-telefono').val().trim();
                if (!nuevoTelefono) {
                    Swal.showValidationMessage('Debes ingresar un número de teléfono');
                    return false;
                }
                return { telefono: nuevoTelefono };
            },
            confirmButtonText: 'Actualizar',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
        }).then(result => {
            if (result.isConfirmed && result.value) {
                $.post('data/accionesTelefonos.php', {
                    action: 'crudTelefono',
                    accion: 2,
                    idTelefono: idTelefono,
                    telefono: result.value.telefono
                }, function (respuesta) {
                    const res = respuesta;
                    if (res.success) {
                        Swal.fire('Éxito', res.message, 'success');
                        location.reload();
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                });
            }
        });
    });
    
    function cargarTelefonos() {
        $.post('data/accionesTelefonos.php', { action: 'getTelefonos' }, function (data) {
            const telefonos = data;
            const $tabla = $('#tablaTelefonos');
            $tabla.empty(); 
    
            if (telefonos.length > 0) {
                telefonos.forEach(t => {
                    $tabla.append(`
                        <tr>
                            <td>${t.TELEFONO}</td>
                            <td>
                                <button class="btn btn-warning btn-sm btnEditarTelefono" data-id="${t.ID_TELEFONO}" data-telefono="${t.TELEFONO}">Editar</button>
                                <button class="btn btn-danger btn-sm btnEliminarTelefono" data-id="${t.ID_TELEFONO}">Eliminar</button>
                            </td>
                        </tr>
                    `);
                });
            } else {
                $tabla.append('<tr><td colspan="2" class="text-center">No hay teléfonos registrados.</td></tr>');
            }
        });
    }

    cargarTelefonos();
    













});