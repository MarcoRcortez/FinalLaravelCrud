@extends('adminlte::page')

@section('title', 'Clientes')

@section('content_header')
    <h1>Gestión de Clientes</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalCrearCliente">
                Crear Cliente
            </button>
        </div>
        <div class="card-body">
            <table id="clientes" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>DNI</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Celular</th>
                        <th>Correo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientes as $cliente)
                        <tr id="fila_cliente_{{ $cliente->id }}">
                            <td class="dni">{{ $cliente->dni }}</td>
                            <td class="nombre">{{ $cliente->nombre }}</td>
                            <td class="apellidos">{{ $cliente->apellidos }}</td>
                            <td class="celular">{{ $cliente->celular }}</td>
                            <td class="correo">{{ $cliente->correo_electronico }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editarCliente({{ $cliente->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarCliente({{ $cliente->id }})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @include('clientes.modal_create')
    @include('clientes.modal_edit')
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@stop

@section('js')
    {{-- No cargamos jQuery de nuevo, AdminLTE ya lo incluye --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(() => {
            $('#clientes').DataTable({
                language: {
                    // CAMBIO AQUÍ: Ahora apunta a tu archivo local de idioma
                    url: '/js/datatables/es-ES.json'
                }
            });
            // Opcional: Mostrar SweetAlert de éxito si la sesión lo tiene
            @if (session('success'))
                Swal.fire("{{ session('success') }}", '', 'success');
            @endif
        });
    </script>
    {{-- Aquí se incluirán tus scripts personalizados de clientes (editar, eliminar) --}}
    @include('clientes.scripts')
@stop
