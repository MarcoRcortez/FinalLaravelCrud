<div class="modal fade" id="modalEditarCategoria" tabindex="-1" role="dialog" aria-labelledby="modalEditarCategoriaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formEditarCategoria" method="POST">
            @csrf
            @method('PUT') {{-- Directiva de Laravel para simular el método PUT --}}
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="modalEditarCategoriaLabel">Editar Categoría</h5>
                    {{-- Añadido type="button" para evitar envíos de formulario --}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- CORRECCIÓN: Se añadió el atributo name="id" --}}
                    <input type="hidden" id="edit_id_categoria" name="id">
                    <div class="form-group">
                        <label for="edit_descripcion_categoria">Descripción</label>
                        <input type="text" name="descripcion" id="edit_descripcion_categoria" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- Añadido type="button" para evitar envíos de formulario --}}
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>
