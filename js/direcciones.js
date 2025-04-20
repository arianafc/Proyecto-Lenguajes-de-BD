$(document).ready(function () {
    $(document).on('click', '#btnAgregarDireccion', function(){
        mostrarFormularioDireccion();
        console.log('hola');
 
    });

    function mostrarFormularioDireccion() {
        Swal.fire({
            title: 'Agregar Dirección',
            html: `
                <select id="swal-provincia" class="swal2-input">
                    <option value="">Seleccione provincia</option>
                </select>
                <select id="swal-canton" class="swal2-input">
                    <option value="">Seleccione cantón</option>
                </select>
                <select id="swal-distrito" class="swal2-input">
                    <option value="">Seleccione distrito</option>
                </select>
                <input id="swal-direccion" class="swal2-input" placeholder="Dirección exacta">
            `,
            focusConfirm: false,
            preConfirm: () => {
                const provincia = $('#swal-provincia').val();
                const canton = $('#swal-canton').val();
                const distrito = $('#swal-distrito').val();
                const direccion = $('#swal-direccion').val().trim();

                if (!provincia || !canton || !distrito || !direccion) {
                    Swal.showValidationMessage('Todos los campos son obligatorios');
                    return false;
                }

                return { provincia, canton, distrito, direccion };
            },
            didOpen: () => {
                cargarProvincias();

                $('#swal-provincia').change(function () {
                    const idProvincia = $(this).val();
                    $('#swal-canton').html('<option value="">Seleccione cantón</option>');
                    $('#swal-distrito').html('<option value="">Seleccione distrito</option>');
                    if (idProvincia) cargarCantones(idProvincia);
                });

                $('#swal-canton').change(function () {
                    const idCanton = $(this).val();
                    $('#swal-distrito').html('<option value="">Seleccione distrito</option>');
                    if (idCanton) cargarDistritos(idCanton);
                });
            },
            confirmButtonText: 'Guardar',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
        }).then(result => {
            if (result.isConfirmed && result.value) {
                guardarDireccion(result.value);
            }
        });
    }

    function cargarProvincias(callback) {
        $.ajax({
            url: 'data/accionesDirecciones.php',
            type: 'POST',
            data: { action: 'getProvincias' },
            dataType: 'json',
            success: function (provincias) {
                $('#swal-provincia').empty().append('<option value="">Seleccione provincia</option>');
                provincias.forEach(p => {
                    $('#swal-provincia').append(`<option value="${p.ID_PROVINCIA}">${p.NOMBRE}</option>`);
                });
                if (callback) callback(); // Ejecutar callback si se proporciona
            },
            error: function () {
                Swal.fire("Error", "No se pudieron cargar las provincias.", "error");
            }
        });
    }
    
    
    function cargarCantones(idProvincia, callback) {
        $.ajax({
            url: 'data/accionesDirecciones.php',
            type: 'POST',
            data: {
                action: 'getCantones',
                idProvincia: idProvincia
            },
            dataType: 'json',
            success: function (cantones) {
                $('#swal-canton').empty().append('<option value="">Seleccione un cantón</option>');
                cantones.forEach(c => {
                    $('#swal-canton').append(`<option value="${c.ID_CANTON}">${c.NOMBRE}</option>`);
                });
                if (callback) callback();
            },
            error: function () {
                Swal.fire("Error", "No se pudieron cargar los cantones.", "error");
            }
        });
    }
    
    
    function cargarDistritos(idCanton, callback) {
        $.ajax({
            url: 'data/accionesDirecciones.php',
            type: 'POST',
            data: {
                action: 'getDistritos',
                idCanton: idCanton
            },
            dataType: 'json',
            success: function (distritos) {
                $('#swal-distrito').empty().append('<option value="">Seleccione un distrito</option>');
                distritos.forEach(d => {
                    $('#swal-distrito').append(`<option value="${d.ID_DISTRITO}">${d.NOMBRE}</option>`);
                });
                if (callback) callback();
            },
            error: function () {
                Swal.fire("Error", "No se pudieron cargar los distritos.", "error");
            }
        });
    }
    
    
    function guardarDireccion({ direccion, distrito }) {
        $.post('data/accionesDirecciones.php', {
            action: 'agregarDireccion',
            direccion,
            distrito
        }, function (data) {
            res = data;
            if (res.success) {
                Swal.fire('Éxito', res.success, 'success');
                location.reload();
            } else {
                Swal.fire('Error', res.error || 'No se pudo guardar', 'error');
            }
        });
    }

/////////////////////////// EDITAR

$(document).on('click', '#editarDireccion', function () {
    const idDireccion = $(this).data('id');
    mostrarFormularioEditarDireccion(idDireccion);
});

function mostrarFormularioEditarDireccion(idDireccion) {
    $.post('data/accionesDirecciones.php', { action: 'getDireccion', idDireccion }, function (data) {
        direccion = data;

        Swal.fire({
            title: 'Editar Dirección',
            html: `
                <select id="swal-provincia" class="swal2-input">
                    <option value="">Seleccione provincia</option>
                </select>
                <select id="swal-canton" class="swal2-input">
                    <option value="">Seleccione cantón</option>
                </select>
                <select id="swal-distrito" class="swal2-input">
                    <option value="">Seleccione distrito</option>
                </select>
                <input id="swal-direccion" class="swal2-input" placeholder="Dirección exacta" value="${direccion.DIRECCION_EXACTA}">
            `,
            focusConfirm: false,
            preConfirm: () => {
                const distrito = $('#swal-distrito').val();
                const direccionExacta = $('#swal-direccion').val().trim();

                if (!distrito || !direccionExacta) {
                    Swal.showValidationMessage('Todos los campos son obligatorios');
                    return false;
                }

                return {
                    idDireccion: direccion.ID_DIRECCION,
                    idDistrito: distrito,
                    direccion: direccionExacta
                };
            },
            didOpen: () => {
                cargarProvincias(() => {
                    $('#swal-provincia').val(direccion.ID_PROVINCIA);
                    cargarCantones(direccion.ID_PROVINCIA, () => {
                        $('#swal-canton').val(direccion.ID_CANTON);
                        cargarDistritos(direccion.ID_CANTON, () => {
                            $('#swal-distrito').val(direccion.ID_DISTRITO);
                        });
                    });
                });

                $('#swal-provincia').change(function () {
                    const idProvincia = $(this).val();
                    $('#swal-canton').html('<option value="">Seleccione cantón</option>');
                    $('#swal-distrito').html('<option value="">Seleccione distrito</option>');
                    if (idProvincia) cargarCantones(idProvincia);
                });

                $('#swal-canton').change(function () {
                    const idCanton = $(this).val();
                    $('#swal-distrito').html('<option value="">Seleccione distrito</option>');
                    if (idCanton) cargarDistritos(idCanton);
                });
            },
            confirmButtonText: 'Guardar',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
        }).then(result => {
            if (result.isConfirmed && result.value) {
                actualizarDireccion(idDireccion, result.value.direccion, result.value.idDistrito);
            }
        });
    });
}

function actualizarDireccion(idDireccion, direccion, idDistrito) {
    $.post('data/accionesDirecciones.php', {
        action: 'editarDireccion',
        idDireccion: idDireccion,
        direccion: direccion,
        idDistrito: idDistrito
    }, function(response) {
        const res = response;
        if (res.success) {
            Swal.fire('Éxito', res.message, 'success').then(() => {
                location.reload();
                cargarDirecciones(); 
            });
        } else {
            Swal.fire('Error', res.message, 'error');
        }
    });
}

$(document).on('click', '.btnEliminarDireccion', function(){
    const id = $(this).data('id');
    $.post('./data/accionesDirecciones.php', {action: 'eliminarDireccion', idDireccion: id}, function(response) {
        if (response.success){
            Swal.fire('Éxito', response.message, 'success').then(() => {
                location.reload();
                cargarDirecciones(); 
            });
        } else {
            Swal.fire('Error', response.message, 'error');
        }
    })
});

});