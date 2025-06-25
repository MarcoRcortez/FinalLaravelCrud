<div class="modal fade" id="modalCrearCategoria" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('categorias.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Nueva Categoría</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <label>Descripción</label>
                    <input type="text" name="descripcion" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
