@extends('adminlte::page')

@section('title', 'Lista de usuarios')

@section('content_header')
    <h1>Lista de usuarios</h1>
@stop

@section('content')
    <table>
        <tr>
            <th>Nombre</th>
            <th>Correo</th>
        </tr>
        @foreach ($usuarios as $usuario)
            <tr>
                <td>{{ $usuario->name }}</td>
                <td>{{ $usuario->email }}</td>
            </tr>
        @endforeach
    </table>
@stop

@section('css')
@section('css')
    <style>
        /* Estilo para la tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Encabezado de la tabla */
        th {
            background-color: #343a40;
            /* Color oscuro de AdminLTE */
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        /* Celdas de la tabla */
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        /* Filas alternas */
        tr:nth-child(even) {
            background-color: #f8f9fa;
            /* Gris claro */
        }

        /* Efecto hover */
        tr:hover {
            background-color: #e9ecef;
            transition: background-color 0.3s;
        }

        /* Estilo para el título */
        h1 {
            color: #343a40;
            font-weight: 700;
            margin-bottom: 20px;
        }
    </style>

@stop

@section('js')

@stop
