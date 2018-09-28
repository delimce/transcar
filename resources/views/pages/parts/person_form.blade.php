<div id="person-form">
    <a id="to-person-list" href="#">Volver a la lista</a>
    <div class="sub-title">&nbsp;</div>
    <form id="person_form" data-record="">
        <div class="row">
            <div class="col-md-11 mx-auto">
                <h3>Información Personal</h3>
                <div class="form-group required row">
                    <div class="col-sm-6">
                        <label for="nombre" class="control-label">nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                               placeholder="Nombre"
                               autocomplete="my-person-name"
                               required>
                    </div>
                    <div class="col-sm-6">
                        <label for="apellido" class="control-label">apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido"
                               placeholder="apellido"
                               autocomplete="my-person-lastname"
                               required>
                    </div>
                </div>

                <div class="form-group required row">
                    <div class="col-sm-6">
                        <label for="cedula" class="control-label">cédula</label>
                        <input type="text" class="form-control" id="cedula" name="cedula"
                               placeholder="cédula"
                               autocomplete="my-person-ced"
                               required>
                    </div>
                    <div class="col-sm-6">
                        <label for="fecha_nac" class="control-label">fecha nacimiento</label>
                        <input type="date" class="form-control" id="fecha_nac" name="fecha_nac"
                               placeholder="fecha nacimiento"
                               autocomplete="my-nac-person"
                               required>
                    </div>
                </div>

                <div class="form-group required row">
                    <div class="col-sm-6">
                        <label for="sexo" class="control-label">Sexo</label><br>
                        <select name="sexo" data-style="form-select" class="selectpicker" data-width="90%">
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label for="email" class="control-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                               placeholder="email"
                               autocomplete="my-person-email">
                    </div>
                </div>

                <div class="form-group required row">
                    <div class="col-sm-6">
                        <label for="fecha_ingreso" class="control-label">fecha ingreso</label>
                        <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso"
                               placeholder="fecha de ingreso a la empresa"
                               autocomplete="my-ing-person"
                               required>
                    </div>
                    <div class="col-sm-6">
                        <label for="telefono" class="control-label">telefono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono"
                               placeholder="telefono"
                               autocomplete="my-person-tlf">
                    </div>
                </div>

                <h3>Información Laboral</h3>

                <div class="form-group required row">
                    <div class="col-sm-6">
                        <label for="area" class="control-label">Area</label><br>
                        <select name="area" id="area" title="Seleccione..." class="selectpickerArea" data-style="form-select" data-width="90%">
                        </select>
                    </div>

                    <div class="col-sm-6">
                        <label for="cargo" class="control-label">Cargo</label><br>
                        <select name="cargo" id="cargo" title="Seleccione el cargo"  class="selectpickerRole" data-live-search="true" data-style="form-select" data-width="90%">
                        </select>
                    </div> 

                </div>

                <div class="form-group required row">
                        <div class="col-sm-6">
                                <label class="control-label">Activo?</label>
                                <div class="custom-switch">
                                    <input class="custom-switch-input btn-primary" name="activo" id="activo" value="1" type="checkbox" checked>
                                    <label class="custom-switch-btn" for="activo"></label>
                                </div>
                        </div>
                </div>    

                <div id="role-location">
                    <h3>Ubicación</h3>
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
                </div>

                <h3>Información de la cuenta</h3>

                <div class="form-group required row">
                    <div class="col-sm-6">
                        <label for="titular" class="control-label">Titular</label>
                        <input type="text" class="form-control" id="titular" name="titular"
                               placeholder="titular de la cuenta"
                               autocomplete="my-person-titular">
                    </div>
                    <div class="col-sm-6">
                        <label for="account" class="control-label">Nº cuenta</label>
                        <input type="text" class="form-control" id="account" name="account"
                               placeholder="numero de cuenta"
                               autocomplete="my-person-account">
                    </div>
                </div>

                <div class="form-group required row">
                    <div class="col-sm-6">
                        <button id="save" class="btn btn-primary" type="submit">
                            Guardar Empleado
                        </button>
                        &nbsp;
                        <button id="delete-person" class="btn btn-danger" type="button">
                            Borrar
                        </button>
                    </div>
                </div>


            </div>
        </div>
    </form>
</div>