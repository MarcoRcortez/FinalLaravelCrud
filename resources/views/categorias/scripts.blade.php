<script>
    // Definición de la función para cargar datos de la categoría al modal de edición
    // Se asigna directamente al objeto global 'window' para que sea accesible desde onclick
    window.cargarDatosEditarCategoria = function(id) {
        // console.log("Llamada a cargarDatosEditarCategoria para ID:", id); // Para depuración

        // Establece la acción del formulario de edición dinámicamente
        // Esto es útil si el formulario se enviara de forma tradicional, pero con AJAX la URL se define en $.ajax
        // No obstante, mantenerlo no hace daño.
        $('#formEditarCategoria').attr('action', `/categorias/${id}`);

        // Realiza una petición GET para obtener los datos de la categoría desde el servidor
        $.get(`/categorias/${id}`)
            .done(function(data) {
                // console.log("Datos de categoría recibidos:", data); // Para depuración
                $('#edit_id_categoria').val(data.id); // Rellena el input oculto con el ID
                $('#edit_descripcion_categoria').val(data.descripcion); // Rellena la descripción
                $('#modalEditarCategoria').modal('show'); // Abre el modal de edición
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.error('Error al cargar datos de categoría para edición:', textStatus, errorThrown, jqXHR.responseJSON);
                Swal.fire('Error', 'No se pudieron cargar los datos de la categoría.', 'error');
            });
    };

    // Este bloque jQuery document.ready asegura que el manejador del submit se adjunte
    // solo cuando el DOM esté completamente cargado.
    $(document).ready(function() {
        // Manejador para el envío del formulario de edición mediante AJAX
        $('#formEditarCategoria').submit(function(e) {
            e.preventDefault(); // Evita el envío tradicional del formulario HTML

            let id = $('#edit_id_categoria').val(); // Obtiene el ID de la categoría a actualizar
            let data = {
                _token: $('input[name="_token"]').val(), // Obtiene el token CSRF para seguridad
                _method: 'PUT', // Simula un método PUT para Laravel (aunque la petición es POST)
                descripcion: $('#edit_descripcion_categoria').val() // Obtiene el valor de la descripción
            };

            $.ajax({
                url: `/categorias/${id}`, // URL a la que se enviará la petición
                type: 'POST', // El tipo de petición HTTP real (Laravel lo interpretará como PUT por _method)
                data: data, // Los datos a enviar
                success: function(response) {
                    Swal.fire('Éxito', response.message, 'success').then(() => location.reload()); // Muestra mensaje y recarga la página
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error al actualizar la categoría:', textStatus, errorThrown, jqXHR.responseJSON);
                    let errorMessage = 'No se pudo actualizar la categoría.';
                    if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                        // Si hay errores de validación de Laravel, los muestra
                        errorMessage = Object.values(jqXHR.responseJSON.errors).flat().join('\n');
                    } else if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                        // Si hay un mensaje de error general en la respuesta JSON
                        errorMessage = jqXHR.responseJSON.message;
                    }
                    Swal.fire('Error', errorMessage, 'error'); // Muestra mensaje de error
                }
            });
        });
    });


    // Definición de la función para eliminar (desactivar) una categoría
    // Se asigna directamente al objeto global 'window' para que sea accesible desde onclick
    window.eliminarCategoria = function(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción desactivará la categoría y no se podrá revertir fácilmente.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, desactivar',
            cancelButtonText: 'Cancelar'
        }).then(result => {
            if (result.isConfirmed) {
                // Usa Fetch API para la petición DELETE (más moderna, pero jQuery.ajax también es válido)
                fetch(`/categorias/${id}`, {
                    method: 'DELETE', // Método HTTP DELETE
                    headers: {
                        'Content-Type': 'application/json',
                        // Obtén el token CSRF desde la meta etiqueta en tu layout principal
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({}) // Cuerpo vacío, pero necesario si Content-Type es 'application/json'
                })
                .then(res => {
                    if (!res.ok) {
                        // Si la respuesta no es exitosa (ej. 4xx, 5xx), lee el JSON de error
                        return res.json().then(errorData => {
                            throw new Error(errorData.message || 'Error desconocido del servidor.');
                        });
                    }
                    return res.json(); // Parsea la respuesta JSON
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire('¡Desactivado!', 'La categoría ha sido desactivada correctamente.', 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error', data.message || 'No se pudo desactivar la categoría.', 'error');
                    }
                })
                .catch(err => {
                    console.error('Error al desactivar:', err);
                    Swal.fire('Error', 'Hubo un problema al desactivar la categoría: ' + err.message, 'error');
                });
            }
        });
    };

    // Función 'cargarDatosDeCategoria' (si es que se usa en algún botón de "Crear" con onclick)
    // Aunque tu botón de "Crear Categoría" usa data-toggle="modal", si en algún otro lugar
    // se llama a esta función con onclick, es bueno tenerla definida globalmente.
    window.cargarDatosDeCategoria = function() {
        // console.log("Función cargarDatosDeCategoria (crear) llamada si es necesaria.");
        // Puedes agregar aquí lógica para limpiar el formulario del modal de creación antes de abrirlo.
        // Por ejemplo: $('#formCrearCategoria')[0].reset();
    };

</script>
