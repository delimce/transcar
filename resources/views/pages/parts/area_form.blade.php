<div id="area-form">
        <a id="to-area-list" href="#">Volver a la lista</a>
        <div class="sub-title">&nbsp;</div>
        <form id="area_form" data-record="">
                <div class="row">
                        <div class="col-md-11 mx-auto">
                                <div class="form-group required row">
                                        <div class="col-sm-6">
                                            <label for="nombre" class="control-label">Nombre</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre"
                                                   placeholder="nombre"
                                                   autocomplete="my-area-name"
                                                   required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="descripcion" class="control-label">descripcion</label>
                                            <input type="text" class="form-control" id="descripcion" name="descripcion"
                                                   placeholder="descripcion"
                                                   autocomplete="my-area-desc">
                                        </div>
                                </div>

                                <div class="form-group required row">
                                        <div class="col-sm-6">
                                            <button id="save" class="btn btn-primary" type="submit">
                                                Guardar
                                            </button>
                                            &nbsp;
                                            <button id="delete-area" class="btn btn-danger" type="button">
                                                Borrar
                                            </button>
                                        </div>
                                </div>
                        </div>

                       
                </div>
        </form>
</div>