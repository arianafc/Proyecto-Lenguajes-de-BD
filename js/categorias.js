
document.addEventListener("DOMContentLoaded", function () {
cargarCategorias();

    function cargarCategorias() {
        $.post('./data/accionesCategorias.php', { action: 'obtenerCategorias' }, function (response) {
            const tabla = $('#tablaCategorias');
            tabla.empty(); 

            if (Array.isArray(response) && response.length > 0) {
                response.forEach(categoria => {
                    const estadoTexto = categoria.ID_ESTADO == 1 ? 'Activo' : 'Inactivo';
                    const estadoClase = categoria.ID_ESTADO == 1 ? 'bg-success' : 'bg-danger';

                    let acciones = '';

                    if (categoria.ID_ESTADO == 1) {
                        acciones = `
                            <button class="btn btn-warning btn-sm btn-editar-categoria"
                                data-id="${categoria.ID_CATEGORIA}"
                                data-descripcion="${categoria.DESCRIPCION}">
                                <i class="fas fa-edit"></i> Editar
                            </button>

                            <button class="btn btn-danger btn-sm btn-desactivar-categoria"
                                data-id="${categoria.ID_CATEGORIA}" data-estado="2">
                                <i class="fas fa-trash-alt"></i> Desactivar
                            </button>
                        `;
                    } else {
                        acciones = `
                            <button class="btn btn-success btn-sm btn-activar-categoria"
                                data-id="${categoria.ID_CATEGORIA}" data-estado="1">
                                <i class="fa-regular fa-circle-check"></i> Activar
                            </button>
                        `;
                    }

                    tabla.append(`
                        <tr>
                            <td>${categoria.DESCRIPCION}</td>
                            <td><span class="badge ${estadoClase}">${estadoTexto}</span></td>
                            <td>${acciones}</td>
                        </tr>
                    `);
                });
              
            } else {
                tabla.append('<tr><td colspan="3">No hay categorías registradas.</td></tr>');
            }
        }, 'json');
    }

    $('#btnAgregarCategoria').click(function () {
        Swal.fire({
            title: 'Agregar Categoría',
            input: 'text',
            inputLabel: 'Nombre de la categoría',
            showCancelButton: true,
            confirmButtonText: 'Agregar',
            cancelButtonText: 'Cancelar',
            inputValidator: (value) => {
                if (!value) {
                    return 'El nombre es requerido';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('./data/accionesCategorias.php', {
                    action: 'crearCategoria',
                    descripcion: result.value
                }, function (response) {
                    if (response.success) {
                        Swal.fire('¡Agregado!', response.mensaje, 'success');
                        location.reload();
                    } else {
                        Swal.fire('Error', response.error || 'No se pudo agregar la categoría', 'error');
                    }
                }, 'json');
            }
        });
    });

    cargarCategorias();



    $(document).on('click', '.btn-editar-categoria', function () {
        const id = $(this).data('id');
        const descripcion = $(this).data('descripcion');
    
        Swal.fire({
            title: 'Editar Categoría',
            input: 'text',
            inputLabel: 'Nuevo nombre de la categoría',
            inputValue: descripcion,
            showCancelButton: true,
            confirmButtonText: 'Actualizar',
            cancelButtonText: 'Cancelar',
            inputValidator: (value) => {
                if (!value) return 'Debe ingresar un nombre';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('./data/accionesCategorias.php', {
                    action: 'actualizarCategoria',
                    id: id,
                    descripcion: result.value
                }, function (response) {
                    if (response.success) {
                        Swal.fire('¡Actualizado!', response.mensaje, 'success');
                        cargarCategorias();
                    } else {
                        Swal.fire('Error', response.error || 'No se pudo actualizar', 'error');
                    }
                }, 'json');
            }
        });
    });

    $(document).on('click', '.btn-desactivar-categoria', function () {
        const id = $(this).data('id');
    
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esto desactivará la categoría y los productos asociados',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, desactivar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('./data/accionesCategorias.php', {
                    action: 'desactivarCategoria',
                    id: id
                }, function (response) {
                    if (response.success) {
                        Swal.fire('¡Desactivado!', response.mensaje, 'success');
                        cargarCategorias();
                    } else {
                        Swal.fire('Error', response.error || 'No se pudo desactivar', 'error');
                    }
                }, 'json');
            }
        });
    });



    $(document).on('click', '.btn-activar-categoria', function () {
        const id = $(this).data('id');
    
        Swal.fire({
            title: '¿Activar categoría?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, activar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('./data/accionesCategorias.php', {
                    action: 'activarCategoria',
                    id: id
                }, function (response) {
                    if (response.success) {
                        Swal.fire('¡Activado!', response.mensaje, 'success');
                        cargarCategorias();
                    } else {
                        Swal.fire('Error', response.error || 'No se pudo activar', 'error');
                    }
                }, 'json');
            }
        });
    });














});

