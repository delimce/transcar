<div id="bonus-form">
    <a id="to-bonus-list" href="#">Volver a la lista</a>
    <div class="sub-title">&nbsp;</div>
    <form id="bonus_form" data-record="">
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
                </div>

                <div class="form-group required row">
                    <div class="col-sm-6">
                        <label for="tipo" class="control-label">Tipo</label><br>
                        <select name="tipo" id="tipo" title="Seleccione el tipo"  data-style="form-select" class="selectpickerType" data-width="90%">
                            <option value="area" title="Departamento">Departamento</option>
                            <option value="cargo" title="Cargo">Cargos por departamento</option>
                            <option value="empleado" title="Empleado">Empleado directo</option>
                            <option value="linea" title="Línea">Línea de producción</option>
                        </select>

                    </div>
                    <div class="col-sm-6">
                        <label for="beneficiario" class="control-label">beneficiario</label><br>
                        <select name="beneficiario" id="beneficiario" title="Seleccione el beneficiario"  class="selectpickerBene" data-live-search="true" data-style="form-select" data-width="90%">
                        </select>
                    </div>
                </div>

                <div class="form-group required row">
                    <div class="col-sm-6">
                        <label for="fecha" class="control-label">Fecha pago</label>
                        <input type="date" class="form-control" id="fecha" name="fecha"
                               placeholder="fecha"
                               autocomplete="my-bonus-date"
                               required>
                    </div>
                    <div class="col-sm-6">
                        <label for="monto" class="control-label">Monto bono</label>
                        <input type="text" class="form-control currency" id="monto" name="monto"
                               placeholder="" required>
                    </div>
                </div>

                <div class="form-group required row">
                    <div class="col-sm-6">
                        <button id="save" class="btn btn-primary" type="submit">
                            Guardar Bono
                        </button>
                        &nbsp;
                        <button id="delete-bonus" class="btn btn-danger" type="button">
                            Borrar
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>