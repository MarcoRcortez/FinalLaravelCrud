@extends('adminlte::page')

@section('title', 'Lista de Productos')

@section('content_header')
    <h1>Lista de productos</h1>
    <br>
    <div class="card-header">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCrearProducto">
            Crear Producto
        </button>
    </div>
@stop

@section('content')
    <table id="productos-table" class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>id</th>
                <th>nombre</th>
                <th>categoria_id</th>
                <th>codigo_barras</th>
                <th>precio_venta</th>
                <th>cantidad_stock</th>
                <th>estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $producto)
                <tr>
                    <td>{{ $producto->id }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->categoria_id }}</td>
                    <td>{{ $producto->codigo_barras }}</td>
                    <td>{{ $producto->precio_venta }}</td>
                    <td>{{ $producto->cantidad_stock }}</td>
                    <td>{{ $producto->estado ? 'Activo' : 'Inactivo' }}</td>

                    <td class="text-center">
                        <button type="button" class="btn btn-warning btn-sm"
                            onclick="cargarDatosEditar({{ $producto->id }})">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm"
                            onclick="eliminarProducto({{ $producto->id }})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="modal fade" id="modalCrearProducto" tabindex="-1" role="dialog" aria-labelledby="modalCrearProductoLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('productos.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCrearProductoLabel">Crear Nuevo Producto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="categoria_id">Categoría</label>
                            <select name="categoria_id" class="form-control" required>
                                <option value="">-- Selecciona una categoría --</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="codigo_barras">Código de Barras</label>
                            <input type="text" name="codigo_barras" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="precio_venta">Precio de Venta</label>
                            <input type="number" name="precio_venta" step="0.01" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="cantidad_stock">Cantidad en Stock</label>
                            <input type="number" name="cantidad_stock" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Producto</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('productos.modal_create')
    @include('productos.modal_edit')
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#productos-table').DataTable();
        });

        function cargarDatosEditar(id) {
            $('#formEditarProducto').attr('action', `/productos/${id}`);
            $.ajax({
                url: `/productos/${id}`,
                type: 'GET',
                success: function(data) {
                    $('#edit_id').val(data.id);
                    $('#edit_nombre').val(data.nombre);
                    $('#edit_categoria_id').val(data.categoria_id);
                    $('#edit_codigo_barras').val(data.codigo_barras);
                    $('#edit_precio_venta').val(data.precio_venta);
                    $('#edit_cantidad_stock').val(data.cantidad_stock);
                    $('#modalEditarProducto').modal('show');
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire('Error', 'No se pudo cargar el producto. Verifica si tienes permisos.', 'error');
                }
            });
        }
    </script>


    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded',
                function() {
                    Swal.fire({
                        title: "{{ session('success') }}",
                        icon: "success"
                    });
                });
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $('#formEditarProducto').on('submit', function(e) {
                e.preventDefault();
                const id = $('#edit_id').val();
                const url = `/productos/${id}`;
                const datos = {
                    _token: $('input[name="_token"]').val(),
                    _method: 'PUT',
                    nombre: $('#edit_nombre').val(),
                    categoria_id: $('#edit_categoria_id').val(),
                    codigo_barras: $('#edit_codigo_barras').val(),
                    precio_venta: $('#edit_precio_venta').val(),
                    cantidad_stock: $('#edit_cantidad_stock').val()
                };
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: datos,
                    success: function(response) {
                        const modal = bootstrap.Modal.getInstance(document.getElementById(
                            'modalEditarProducto'));
                        modal.hide();
                        Swal.fire('Éxito', response.message, 'success');
                        location.reload();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire('Error', 'No se pudo actualizar el producto.', 'error');
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('[data-dismiss="modal"]').click(function() {
                $('#modalEditarProducto').modal('hide');
            });
        });
    </script>
    <script>
        function eliminarProducto(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esta acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/productos/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.text().then(text => {
                                    throw new Error(text || 'Error del servidor');
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            Swal.fire(
                                '¡Eliminado!',
                                'El producto ha sido eliminado.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        })
                        .catch(error => {
                            console.error('Error al eliminar producto:', error);
                            Swal.fire(
                                'Error',
                                'No se pudo eliminar el producto. Detalle: ' + error.message,
                                'error'
                            );
                        });
                }
            });
        }
    </script>
@stop
