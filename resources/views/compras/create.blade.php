@extends('adminlte::page')

@section('title', 'Registrar Compra')

@section('content_header')
    <h1>Registrar Compra</h1>
@stop

@section('content')
<form method="POST" action="{{ route('compras.store') }}">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <label>Cliente</label>
                <select name="cliente_id" class="form-control" required>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->nombre }} {{ $cliente->apellidos }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Fecha</label>
                <input type="date" name="fecha" class="form-control" required value="{{ date('Y-m-d') }}">
            </div>

            <div class="form-group">
                <label>Medio de Pago</label>
                <select name="medio_pago" class="form-control" required>
                    <option value="E">Efectivo</option>
                    <option value="T">Tarjeta</option>
                    <option value="C">Cuenta Bancaria</option> {{-- Agregando una opción más por si acaso --}}
                </select>
            </div>

            <div class="form-group">
                <label>Comentario</label>
                <textarea name="comentario" class="form-control"></textarea>
            </div>

            <hr>
            <h5>Detalle de Productos</h5>
            <table class="table" id="tablaProductos">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio Unitario (Bs.)</th> {{-- Nuevo campo para el precio unitario --}}
                        <th>Cantidad</th>
                        <th>Total Bs.</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="detalleProductos">
                    {{-- Filas de productos se agregarán aquí dinámicamente --}}
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Total General:</strong></td>
                        <td><input type="text" id="totalGeneralCompra" class="form-control" readonly value="0.00"></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <button type="button" class="btn btn-secondary" onclick="agregarFila()">Agregar Producto</button>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Guardar Compra</button>
                <a href="{{ route('compras.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </div>
</form>
@stop

@section('js')
<script>
    // Asegúrate de que $productos contenga también el precio_venta
    // El controlador debe pasar $productos como: Producto::all()->toJson();
    let productos = @json($productos);

    // Función para encontrar el precio de un producto por su ID
    function getPrecioProducto(idProducto) {
        const producto = productos.find(p => p.id == idProducto);
        return producto ? parseFloat(producto.precio_venta) : 0;
    }

    function calcularTotalFila(index) {
        const selectProducto = document.querySelector(`select[name="productos[${index}][id_producto]"]`);
        const inputCantidad = document.querySelector(`input[name="productos[${index}][cantidad]"]`);
        const inputTotal = document.querySelector(`input[name="productos[${index}][total]"]`);
        const inputPrecioUnitario = document.getElementById(`precioUnitario_${index}`); // Nuevo input de precio unitario

        const idProducto = selectProducto ? selectProducto.value : null;
        let cantidad = parseFloat(inputCantidad.value) || 0;
        let precioUnitario = 0;

        if (idProducto) {
            precioUnitario = getPrecioProducto(idProducto);
            if(inputPrecioUnitario) {
                inputPrecioUnitario.value = precioUnitario.toFixed(2); // Muestra el precio unitario
            }
        }

        const totalFila = precioUnitario * cantidad;
        inputTotal.value = totalFila.toFixed(2); // Asegura dos decimales

        calcularTotalGeneral(); // Recalcula el total general cada vez que una fila cambia
    }

    function calcularTotalGeneral() {
        let totalGeneral = 0;
        document.querySelectorAll('input[name^="productos"][name$="[total]"]').forEach(input => {
            totalGeneral += parseFloat(input.value) || 0;
        });
        document.getElementById('totalGeneralCompra').value = totalGeneral.toFixed(2);
    }

    function agregarFila() {
        const index = document.querySelectorAll('#detalleProductos tr').length;
        let fila = `
            <tr id="filaProducto_${index}">
                <td>
                    <select name="productos[${index}][id_producto]" class="form-control producto-select" required onchange="calcularTotalFila(${index})">
                        <option value="">Seleccione un producto</option>
                        ${productos.map(p => `<option value="${p.id}">${p.nombre}</option>`).join('')}
                    </select>
                </td>
                <td>
                    <input type="text" id="precioUnitario_${index}" class="form-control" readonly value="0.00">
                </td>
                <td>
                    <input type="number" name="productos[${index}][cantidad]" class="form-control cantidad-input" min="1" required oninput="calcularTotalFila(${index})">
                </td>
                <td>
                    <input type="number" name="productos[${index}][total]" class="form-control total-input" step="0.01" readonly value="0.00">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove(); calcularTotalGeneral();">X</button>
                </td>
            </tr>`;
        document.getElementById('detalleProductos').insertAdjacentHTML('beforeend', fila);

        // Disparar el cálculo inicial si hay un producto pre-seleccionado o al cargar
        // Aquí no hay preselección, pero si el primer producto en la lista se selecciona por defecto,
        // podrías llamar a calcularTotalFila(index) aquí después de agregar la fila.
    }

    // Asegurarse de que el cálculo general se haga cuando la página carga si hay filas preexistentes (aunque aquí no las hay)
    document.addEventListener('DOMContentLoaded', () => {
        // Añadir una fila inicial automáticamente al cargar la página si lo deseas:
        agregarFila();
    });
</script>
@stop
