@extends('layouts.app')
@section('content')

    @component("components.pageTitle",['title' => 'Administración - Sistema'])
    @endcomponent

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-users-tab" data-toggle="tab" href="#nav-users" role="tab"
               aria-controls="nav-users" aria-selected="true">Usuarios</a>
            <a class="nav-item nav-link" id="nav-config-tab" data-toggle="tab" href="#nav-config" role="tab"
               aria-controls="nav-config" aria-selected="false">Configuración</a>
        </div>
    </nav>

    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-users" role="tabpanel" aria-labelledby="nav-users-tab">

            <div id="users-list-container">
                <a id="to-user-form" href="#">[+] Crear usuario</a>
                <table id="users-list" class="table table-striped cn-grid">
                    <thead>
                    <tr>
                        <th data-field="id" data-visible="false"></th>
                        <th data-field="nombre" data-sortable="true" scope="col">Nombre</th>
                        <th data-field="apellido" data-sortable="true" scope="col">Apellido</th>
                        <th data-field="usuario" data-sortable="true" scope="col">Usuario</th>
                        <th data-field="perfil" data-sortable="true" scope="col">Perfil</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{str_limit($item->nombre,50)}}</td>
                            <td>{{str_limit($item->apellido,50)}}</td>
                            <td>{{$item->usuario}}</td>
                            <td>{{$item->profile->titulo}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @include('pages.parts.user_form')
        </div>
        <div class="tab-pane fade" id="nav-config" role="tabpanel" aria-labelledby="nav-config-tab">
            <div id="config-container">
                <form id="config_form">
                        <div class="row">
                                <div class="col-md-11 mx-auto">
                                        
                                    <div class="form-group required row">
                                        <div class="col-sm-6">
                                            <label for="empresa_nombre" class="control-label">nombre Empresa</label>
                                            <input type="text" class="form-control" id="empresa_nombre" name="empresa_nombre"
                                                   value="{{$config->empresa_nombre}}"
                                                   placeholder="Nombre de la empresa"
                                                   autocomplete="my-name-company"
                                                   required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="empresa_rif" class="control-label">Rif Empresa</label>
                                            <input type="text" class="form-control" id="empresa_rif" name="empresa_rif"
                                                   value="{{$config->empresa_rif}}"
                                                   placeholder="Rif de la empresa"
                                                   autocomplete="my-rif-company"
                                                   required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group required row">
                                                <div class="col-sm-6">
                                                    <label for="iva" class="control-label">impuesto</label>
                                                <input type="number" value="{{$config->iva}}" class="form-control" id="iva" name="iva"
                                                           placeholder="impuesto iva"
                                                           autocomplete="impuesto"
                                                           required>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="cajas" class="control-label">Cajas por paleta</label>
                                                    <input type="number" value="{{$config->caja_paleta}}" class="form-control" id="cajas" name="cajas"
                                                           placeholder="cajas por paleta"
                                                           autocomplete="cajas">
                                                </div>
                                            </div>

                                            <div class="form-group required row">
                                                    <div class="col-sm-6">
                                                        <button id="save_config" class="btn btn-primary" type="submit">
                                                            Guardar
                                                        </button>
                                                    </div>
                                                </div>
                                </div>
                        </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts-ready')
    $('#users-list').bootstrapTable();
@endpush