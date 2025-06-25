@extends('adminlte::page')

@section('title', 'Categorías')

@section('content_header')
    <h1>Gestión de Categorías</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalCrearCategoria">
                Crear Categoría
            </button>
        </div>
        <div class="card-body">
            <table id="categorias" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categorias as $categoria)
                        <tr id="fila_categoria_{{ $categoria->id }}">
                            <td class="descripcion">{{ $categoria->descripcion }}</td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm"
                                    onclick="cargarDatosEditarCategoria({{ $categoria->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarCategoria({{ $categoria->id }})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Incluye los modales aquí --}}
    @include('categorias.modal_create')
    @include('categorias.modal_edit')
@stop

@section('css')
    {{-- Mantén este enlace CSS para DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@stop

@section('js')
    {{--
        IMPORTANTE: Se ha ELIMINADO la línea para cargar jQuery,
        ya que 'adminlte::page' ya lo incluye.
        Cargar jQuery dos veces provoca el error '$().modal is not a function'.
    --}}

    {{-- 1. Carga DataTables --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    {{-- 2. Carga SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- 3. Este script con $(document).ready para DataTables y SweetAlert --}}
    <script>
        $(document).ready(function() {
            $('#categorias').DataTable({
                language: {
                    // CAMBIO AQUÍ: Ahora apunta a la URL del CDN para el idioma español.
                    // Esto soluciona el error "404 Not Found" del archivo de idioma.
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });
        });

        @if (session('success'))
            Swal.fire("{{ session('success') }}", '', 'success');
        @endif
    </script>

    {{-- 4. Incluye tus scripts personalizados DESPUÉS de las librerías --}}
    {{-- Asegúrate de que este archivo exista en resources/views/categorias/scripts.blade.php --}}
    @include('categorias.scripts')

@stop
