@extends('layouts.app')
@section('content')

    @component("components.pageTitle",['title' => 'Editar cuenta'])
    @endcomponent

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-data-tab" data-toggle="tab" href="#nav-data" role="tab" aria-controls="nav-data" aria-selected="true">Editar Datos</a>
            <a class="nav-item nav-link" id="nav-pass-tab" data-toggle="tab" href="#nav-pass" role="tab" aria-controls="nav-pass" aria-selected="false">Cambiar mi clave</a>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-data" role="tabpanel" aria-labelledby="nav-data-tab">

            <form id="form_user">
                <div class="row">
                    <div class="col-md-11 mx-auto">
                        <div class="form-group required row">
                            <div class="col-sm-6">
                                <label for="nombre" class="control-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre"
                                       placeholder="Nombre"
                                       value="{{$data->nombre}}"
                                       autocomplete="my-name"
                                       required>
                            </div>
                            <div class="col-sm-6">
                                <label for="apellido" class="control-label">Apellido</label>
                                <input type="text" class="form-control" id="apellido" name="apellido"
                                       placeholder="Apellido"
                                       value="{{$data->apellido}}"
                                       autocomplete="my-lastname">
                            </div>
                        </div>

                        <div class="form-group required row">
                            <div class="col-sm-6">
                                <label for="usuario" class="control-label">Usuario</label>
                                <input type="text" class="form-control" id="usuario" name="usuario"
                                       placeholder="Usuario"
                                       value="{{$data->usuario}}"
                                       autocomplete="my-user"
                                       required>
                            </div>
                            <div class="col-sm-6">
                                <label for="email" class="control-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                       placeholder="Email"
                                       value="{{$data->email}}"
                                       autocomplete="my-email"
                                       required>
                            </div>
                        </div>

                        <div>
                            <button id="save" class="btn btn-primary" type="submit">
                                Guardar
                            </button>
                        </div>

                    </div>
                </div>
            </form>

        </div>
        <div class="tab-pane fade" id="nav-pass" role="tabpanel" aria-labelledby="nav-pass-tab">
            <form id="form_pass">
                <div class="col-md-11 mx-auto">
                    <div class="form-group">
                        <label for="pass" class="control-label row">Nueva Clave</label>
                        <input type="password" class="form-control" id="pass" name="pass"
                               autocomplete="new-pass"
                               placeholder="Clave"
                               required>
                    </div>
                    <div class="form-group">
                        <label for="password2" class="control-label row">Confirmación clave</label>
                        <input type="password" class="form-control" id="pass2" name="pass2"
                               autocomplete="new-pass2"
                               placeholder="confirme la clave"
                               required>
                    </div>
                    <div>
                        <button id="savepass" class="btn btn-primary" type="submit">
                            Guardar password
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection