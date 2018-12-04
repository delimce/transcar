<!-- Modal -->
<div id="layoff-actions" class="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="layoff_form" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Egresar Empleado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <label for="nombre" class="control-label">empleado</label><br>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                               value="nombre" readonly>
                    </div>
                    <br>
                    <div class="col-sm-12">
                        <label for="motivo" class="control-label">Motivo de egreso</label><br>
                        <input type="text" class="form-control" id="motivo" name="motivo"
                               placeholder="motivo de egreso" required>
                    </div>
                    <br>
                    <div class="col-sm-12">
                        <label for="fecha" class="control-label">fecha de egreso</label><br>
                        <input type="date" class="form-control" id="fecha" name="fecha" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <input id="save-layoff" value="Crear Egreso" type="submit" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
</div>