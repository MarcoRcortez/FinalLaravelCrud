@extends('adminlte::page')

@section('title', 'Lista de Compras')

@section('content_header')
    <h1>Compras Registradas</h1>
@stop

@section('content')
    <a href="{{ route('compras.create') }}" class="btn btn-primary mb-3">Registrar Compra</a>

    <table class="table table-bordered" id="tablaCompras">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Medio</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($compras as $compra)
                <tr>
                    <td>{{ $compra->id }}</td>
                    <td>{{ $compra->cliente->nombre }} {{ $compra->cliente->apellidos }}</td>
                    <td>{{ $compra->fecha }}</td>
                    <td>{{ $compra->medio_pago == 'E' ? 'Efectivo' : 'Tarjeta' }}</td>
                    <td>{{ $compra->estado == 'A' ? 'Activa' : 'Inactiva' }}</td>
                    <td>
                        <a href="{{ route('compras.show', $compra->id) }}" class="btn btn-info btn-sm">Ver</a>
                        <button onclick="eliminarCompra({{ $compra->id }})" class="btn btn-danger btn-sm">
                            Eliminar
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop

@section('css')
    {{-- Si usas DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@stop

@section('js')
    <!-- jQuery (si no está ya cargado) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables (opcional) -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#tablaCompras').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });
        });
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CSRF Token para AJAX -->
    <script>
        if (!document.querySelector('meta[name="csrf-token"]')) {
            const token = document.createElement('meta');
            token.name = "csrf-token";
            token.content = "{{ csrf_token() }}";
            document.head.appendChild(token);
        }
    </script>

    <!-- Eliminar Compra -->
    <script>
        function eliminarCompra(id) {
            Swal.fire({
                title: '¿Eliminar esta compra?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(`/compras/destroy/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({})
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Eliminado', 'Compra eliminada con éxito', 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Error', 'No se pudo eliminar la compra', 'error');
                        }
                    })
                    .catch(() => {
                        Swal.fire('Error', 'Fallo en la conexión', 'error');
                    });
                }
            });
        }
    </script>

    <!-- Mensaje flash de éxito (opcional) -->
    @if(session('success'))
        <script>
            Swal.fire("{{ session('success') }}", '', 'success');
        </script>
    @endif
@stop
