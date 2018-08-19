@extends('layouts.app')
@section('content')

    @component("components.pageTitle",['title' => 'Ajustes del sistema'])
    @endcomponent

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-users-tab" data-toggle="tab" href="#nav-users" role="tab"
               aria-controls="nav-users" aria-selected="true">Usuarios</a>
            <a class="nav-item nav-link" id="nav-pass-tab" data-toggle="tab" href="#nav-pass" role="tab"
               aria-controls="nav-pass" aria-selected="false">Cambiar mi clave</a>
        </div>
    </nav>

    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-users" role="tabpanel" aria-labelledby="nav-users-tab">

            <table id="users-list" class="table table-striped cn-grid">
                <thead>
                <tr>
                    <th data-field="id" data-visible="false"></th>
                    <th data-field="nombre" data-sortable="true" scope="col">Nombre</th>
                    <th data-field="apellido" data-sortable="true" scope="col">Apellido</th>
                    <th data-field="usuario" data-sortable="true" scope="col">Usuario</th>
                    <th data-field="perfil" data-sortable="true" scope="col">Fecha inicio</th>

                </tr>
                </thead>
                <tbody>
                @foreach($users as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{str_limit($item->nombre,50)}}</td>
                        <td>{{str_limit($item->apellido,50)}}</td>
                        <td>{{$item->usuario}}</td>
                        <td>{{$item->nombre}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="nav-pass" role="tabpanel" aria-labelledby="nav-pass-tab">

        </div>
    </div>

@endsection

@push('scripts-ready')
    $('#users-list').bootstrapTable();
@endpush