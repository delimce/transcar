<div id="table-form">
    <a id="to-table-list" href="#">Volver a la lista</a>
    <div class="sub-title">&nbsp;</div>
    <form id="table_form" data-record="">
        <div class="row">
            <div class="col-md-11 mx-auto">
                <div class="form-group required row">
                    <div class="col-sm-6">
                        <label for="titulo" class="control-label">titulo</label>
                        <input type="text" class="form-control" id="titulo" name="titulo"
                               placeholder="titulo"
                               autocomplete="my-title"
                               required>
                    </div>
                    <div class="col-sm-6">
                        <label for="ubicacion" class="control-label">Ubicacion</label>
                        <input type="text" class="form-control" id="ubicacion" name="ubicacion"
                               placeholder="descripcion"
                               autocomplete="my-desc">
                    </div>
                </div>

                <div class="form-group required row">
                    <div class="col-sm-6">
                        <button id="save" class="btn btn-primary" type="submit">
                            Guardar Mesa
                        </button>
                        &nbsp;
                        <button id="delete-table" class="btn btn-danger" type="button">
                            Borrar
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>