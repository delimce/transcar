<div id="line-form">
    <a id="to-line-list" href="#">Volver a la lista</a>
    <div class="sub-title">&nbsp;</div>
    <form id="line_form" data-record="">
        <div class="row">
            <div class="col-md-11 mx-auto">
                <div class="form-group required row">
                    <div class="col-sm-6">
                        <label for="titulo" class="control-label">titulo</label>
                        <input type="text" class="form-control" id="titulo" name="titulo"
                               placeholder="titulo"
                               autocomplete="my-line-name"
                               required>
                    </div>
                    <div class="col-sm-6">
                        <label for="descripcion" class="control-label">descripción</label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion"
                               placeholder="descripcion"
                               autocomplete="my-line-desc">
                    </div>
                </div>
                <div class="form-group required row">
                    <div class="col-sm-6">
                        <label for="mesa"  class="control-label">Mesa</label><br>
                        <select name="mesa" data-style="form-select" class="selectpickerTable" data-width="90%">
                        </select>

                    </div>
                    <div class="col-sm-6">
                        <label for="jefe" class="control-label">jefe mesa</label>
                        <select name="jefe" data-style="form-select" class="selectpicker" data-width="90%">
                        </select>
                    </div>
                </div>

                <div class="form-group required row">
                    <div class="col-sm-6">
                        <button id="save" class="btn btn-primary" type="submit">
                            Guardar
                        </button>
                        &nbsp;
                        <button id="delete-line" class="btn btn-danger" type="button">
                            Borrar
                        </button>
                    </div>
                </div>


            </div>
        </div>
    </form>
</div>