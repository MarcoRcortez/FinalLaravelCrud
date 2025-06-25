{{-- resources/views/clientes/scripts.blade.php --}}

<script>
    // Función para cargar datos de cliente en el modal de edición
    window.editarCliente = function(id) {
        // Obtenemos los datos de la fila de la tabla
        const fila = $(`#fila_cliente_${id}`);
        const dni = fila.find('.dni').text();
        const nombre = fila.find('.nombre').text();
        const apellidos = fila.find('.apellidos').text();
        const celular = fila.find('.celular').text();
        const correo = fila.find('.correo').text();
        // Asumiendo que la dirección no se muestra en la tabla principal
        // Si necesitas la dirección, tendrías que obtenerla por AJAX o tenerla en un atributo de datos

        // Rellenar el formulario del modal de edición
        $('#edit_cliente_id').val(id); // Asumiendo que tienes un input oculto con este ID
        $('#edit_dni').val(dni);
        $('#edit_nombre').val(nombre);
        $('#edit_apellidos').val(apellidos);
        $('#edit_celular').val(celular);
        $('#edit_correo_electronico').val(correo);
        // Si tienes campo para dirección en el modal de edición, necesitarías cargarlo
        // $('#edit_direccion').val(direccion);

        // Mostrar el modal de edición
        $('#modalEditarCliente').modal('show');
    };

    // Función para eliminar cliente (físicamente de la DB)
    window.eliminarCliente = function(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: '¡Esta acción eliminará el cliente permanentemente y no se podrá revertir!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/clientes/${id}`, { // Asegúrate de que tu ruta DELETE para clientes sea /clientes/{id}
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({})
                })
                .then(res => {
                    if (!res.ok) {
                        return res.json().then(errorData => {
                            throw new Error(errorData.message || 'Error desconocido del servidor.');
                        });
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire('¡Eliminado!', data.message || 'El cliente ha sido eliminado correctamente.', 'success').then(() => {
                            // Opción 1: Recargar la página para actualizar la tabla
                            location.reload();
                            // Opción 2: Eliminar la fila de la tabla directamente sin recargar (más suave)
                            // $(`#fila_cliente_${id}`).remove();
                            // $('#clientes').DataTable().row($(`#fila_cliente_${id}`)).remove().draw();
                        });
                    } else {
                        Swal.fire('Error', data.message || 'No se pudo eliminar el cliente.', 'error');
                    }
                })
                .catch(err => {
                    console.error('Error al eliminar:', err);
                    Swal.fire('Error', 'Hubo un problema al eliminar el cliente: ' + err.message, 'error');
                });
            }
        });
    };
</script>
