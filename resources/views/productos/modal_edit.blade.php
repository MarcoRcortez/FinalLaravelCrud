<div class="modal fade" id="modalEditarProducto" tabindex="-1" role="dialog" aria-labelledby="modalEditarProductoLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formEditarProducto" method="POST">
            @csrf
            @method('PUT') <!-- Importante para que Laravel lo
reconozca como PUT -->
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="modalEditarProductoLabel">Editar Producto</h5>
                    <button type="button" class="close" data dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Campos del formulario -->
                    <input type="hidden" name="id" id="edit_id">
                    <div class="form-group">
                        <label for="edit_nombre">Nombre</label>
                        <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_categoria_id">Categoría</label>
                        <select name="categoria_id" id="edit_categoria_id" class="form-control" required>
                            <option value="">-- Selecciona una categoría --</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_codigo_barras">Código de
                            Barras</label>
                        <input type="text" name="codigo_barras" id="edit_codigo_barras" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edit_precio_venta">Precio de
                            Venta</label>
                        <input type="number" name="precio_venta" id="edit_precio_venta" step="0.01"
                            class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_cantidad_stock">Cantidad en Stock</label>
                        <input type="number" name="cantidad_stock" id="edit_cantidad_stock"
                            class="form-control"required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn primary">Actualizar Producto</button>
                </div>
            </div>
        </form>
    </div>
</div>
