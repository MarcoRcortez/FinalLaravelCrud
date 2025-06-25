@extends('adminlte::page')

@section('title', 'Detalle de Compra')

@section('content_header')
    <h1>Detalle de la Compra #{{ $compra->id }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <p><strong>Cliente:</strong> {{ $compra->cliente->nombre }} {{ $compra->cliente->apellidos }}</p>
            <p><strong>Fecha:</strong> {{ $compra->fecha }}</p>
            <p><strong>Medio de pago:</strong> {{ $compra->medio_pago == 'E' ? 'Efectivo' : 'Tarjeta' }}</p>
            <p><strong>Comentario:</strong> {{ $compra->comentario ?? 'Sin comentario' }}</p>
            <p><strong>Estado:</strong> {{ $compra->estado == 'A' ? 'Activa' : 'Inactiva' }}</p>

            <hr>

            <h5>Detalle de productos</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Total (Bs.)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($compra->productos as $detalle)
                        <tr>
                            <td>{{ $detalle->producto->nombre }}</td>
                            <td>{{ $detalle->cantidad }}</td>
                            <td>{{ number_format($detalle->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ route('compras.index') }}" class="btn btn-secondary">Volver a la lista</a>
        </div>
    </div>
@stop
