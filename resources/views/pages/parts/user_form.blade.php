<!-- The Modal -->

<div id="user-form">
    <a id="to-user-list" href="#">Volver a la lista</a>
    <div class="sub-title">&nbsp;</div>
    <form id="user_form" data-record="">
        <div class="row">
            <div class="col-md-11 mx-auto">
                <div class="form-group required row">
                    <div class="col-sm-6">
                        <label for="nombre" class="control-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                               placeholder="Nombre"
                               autocomplete="my-name"
                               required>
                    </div>
                    <div class="col-sm-6">
                        <label for="apellido" class="control-label">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido"
                               placeholder="Apellido"
                               autocomplete="my-lastname">
                    </div>
                </div>

                <div class="form-group required row">
                    <div class="col-sm-6">
                        <label for="usuario" class="control-label">Usuario</label>
                        <input type="text" class="form-control" id="usuario" name="usuario"
                               placeholder="Usuario"
                               autocomplete="my-user"
                               required>
                    </div>
                    <div class="col-sm-6">
                        <label for="email" class="control-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                               placeholder="Email"
                               autocomplete="my-email"
                               required>
                    </div>
                </div>

                <div class="form-group required row">
                    <div class="col-sm-6">
                        <label for="password" class="control-label">Clave</label>
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="clave"
                               autocomplete="my-clave"
                               required>
                    </div>
                    <div class="col-sm-6">
                        <label for="password2" class="control-label">Repetir clave</label>
                        <input type="password" class="form-control" id="password2" name="password2"
                               placeholder="repetir clave"
                               autocomplete="my-clave2"
                               required>
                    </div>
                </div>

                <div class="form-group required row">
                    <div class="col-sm-6">
                        <label for="profile" class="control-label">Perfil de usuario</label>
                        <select name="profile" data-style="form-select" class="selectpicker" data-width="90%">
                            <option value="2" title="Operador">Operador, Carga Asistencia y producción</option>
                            <option value="3" title="Supervisor">Supervisor, Operaciones y reportes
                            </option>
                            <option value="1" title="Administrador">Administrador, Acceso a todo el
                                sistema
                            </option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Activo?</label>
                        <div class="custom-switch">
                            <input class="custom-switch-input btn-primary" name="activo" id="activo" value="1" type="checkbox" checked>
                            <label class="custom-switch-btn" for="activo"></label>
                        </div>
                    </div>
                </div>

                <div class="form-group required row">
                    <div class="col-sm-6">
                        <button id="save" class="btn btn-primary" type="submit">
                            Guardar Usuario
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