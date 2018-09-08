<div id="prod-form">
    <a id="to-prod-list" href="#">Volver a la lista</a>
    <div class="sub-title">&nbsp;</div>
    <form id="prod_form" data-record="">
        <div class="row">
            <div class="col-md-11 mx-auto">
                <div class="form-group required row">
                    <div class="col-sm-6">
                        <label for="fecha" class="control-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha"
                               placeholder="fecha"
                               autocomplete="my-date"
                               readonly>
                    </div>
                    <div class="col-sm-6">
                        <label for="apellido" class="control-label">Hora</label>
                        <input type="time" class="form-control" id="hora" name="hora"
                               placeholder="hora"
                               autocomplete="my-hour">
                    </div>
                </div>

                <div class="form-group required row">
                    <div class="col-sm-6">
                        <label for="mesa" class="control-label">Mesa</label><br>
                        <select name="mesa" id="mesa" data-style="form-select" class="selectpickerTable" data-width="90%">
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label for="linea" class="control-label">Linea</label><br>
                        <select name="linea" id="linea" data-style="form-select" class="selectpickerLine" data-width="90%">
                        </select>
                    </div>
                </div>

                <div class="form-group required row">
                    <div class="col-sm-6 short-text">
                        <label for="password" class="control-label">N° de Cajas</label>
                        <input type="text" class="form-control" id="cajas" name="cajas"
                               placeholder="cajas producidas"
                               autocomplete="my-boxes"
                               required>
                    </div>

                </div>

                <div class="form-group required row">
                    <div class="col-sm-6">
                        <button id="save" class="btn btn-primary" type="submit">
                            Guardar Producción
                        </button>
                        &nbsp;
                        <button id="delete" class="btn btn-danger" type="button">
                            Borrar
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>

</div>