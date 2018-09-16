<div id="role-form">
    <a id="to-role-list" href="#">Volver a la lista</a>
    <div class="sub-title">&nbsp;</div>
    <form id="role_form" data-record="">
        <div class="row">
            <div class="col-md-11 mx-auto">
                <div class="form-group required row">
                    <div class="col-sm-6">
                        <label for="nombre" class="control-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                               placeholder="nombre"
                               autocomplete="my-role-name"
                               required>
                    </div>
                    <div class="col-sm-6">
                        <label for="descripcion" class="control-label">Descripcion</label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion"
                               placeholder="descripcion"
                               autocomplete="my-role-desc">
                    </div>
                </div>

                <div class="form-group required row">
                    <div class="col-sm-6">
                        <label for="sueldo" class="control-label">Sueldo</label>

                        <input type="number" class="form-control currency" id="sueldo" name="sueldo"
                               placeholder=""
                               min="0" step="0.01" data-number-to-fixed="2"
                               data-number-stepfactor="100"
                               required>

                    </div>
                    <div class="col-sm-6">
                        <label for="asistencia" class="control-label">Bono Asistencia</label>
                        <input type="number" class="form-control currency" id="asistencia" name="asistencia"
                               placeholder=""
                               min="0" step="0.01" data-number-to-fixed="2"
                               data-number-stepfactor="100"
                               required>
                    </div>
                </div>

                <div class="form-group required row">

                    <div class="col-sm-6">
                        <label for="profile" class="control-label">Ganancia producción</label>
                        <select name="produccion_tipo" data-style="form-select" class="selectpicker" data-width="90%">
                            <option value="">Seleccione</option>
                            <option value="total" title="Total">Toda la producción</option>
                            <option value="mesa" title="Mesa">Produccion por mesa</option>
                            <option value="linea" title="Línea">Producción por línea</option>
                        </select>
                    </div>

                    <div class="col-sm-6">
                        <label for="produccion" class="control-label">Bono Producción (%)</label>
                        <input type="number" class="form-control currency" id="produccion" name="produccion"
                               placeholder=""
                               min="0" step="0.01" data-number-to-fixed="2"
                               data-number-stepfactor="100"
                               autocomplete="my-prod"
                               required>
                    </div>

                </div>

                <div class="form-group required row">
                    <div class="col-sm-6">
                        <label for="bono_extra" class="control-label">Bono Extra cargo</label>
                        <input type="number" class="form-control currency" id="bono_extra" name="bono_extra"
                               placeholder=""
                               min="0" step="0.01" data-number-to-fixed="2"
                               data-number-stepfactor="100"
                               autocomplete="my-bonus">
                    </div>

                    <div class="col-sm-6">
                        <label for="hora_extra" class="control-label">Bono Hora Extra</label>
                        <input type="number" class="form-control currency" id="hora_extra" name="hora_extra"
                               placeholder=""
                               min="0" step="0.01" data-number-to-fixed="2"
                               data-number-stepfactor="100"
                               autocomplete="my-extra">
                    </div>
                </div>

                <div class="form-group required row">
                    <div class="col-sm-6">
                        <label for="area" class="control-label">Area</label><br>
                        <select name="area" class="selectpickerArea" data-style="form-select" data-width="90%">
                        </select>
                    </div>
                </div>


                <div class="form-group required row">
                    <div class="col-sm-6">
                        <button id="save" class="btn btn-primary" type="submit">
                            Guardar Cargo
                        </button>
                        &nbsp;
                        <button id="delete-role" class="btn btn-danger" type="button">
                            Borrar
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>