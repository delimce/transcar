@extends('layouts.app')
@section('content')

    @component("components.pageTitle",['title' => 'Administración - Areas y cargos'])
    @endcomponent

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-users-tab" data-toggle="tab" href="#nav-users" role="tab"
               aria-controls="nav-users" aria-selected="true">Areas</a>
            <a class="nav-item nav-link" id="nav-config-tab" data-toggle="tab" href="#nav-config" role="tab"
               aria-controls="nav-config" aria-selected="false">Cargos</a>
        </div>
    </nav>

    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-users" role="tabpanel" aria-labelledby="nav-users-tab">

            <div id="area-list-container">
                <a id="to-area-form" href="#">[+] Crear Area</a>
                <table id="area-list" class="table table-striped cn-grid">
                    <thead>
                    <tr>
                        <th data-field="id" data-visible="false"></th>
                        <th data-field="titulo" data-sortable="true" scope="col">Nombre</th>
                        <th data-field="descripcion" data-sortable="true" scope="col">Descripción</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($areas as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{str_limit($item->titulo,60)}}</td>
                            <td>{{str_limit($item->descripcion,100)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @include('pages.parts.area_form')
        </div>
        <div class="tab-pane fade" id="nav-config" role="tabpanel" aria-labelledby="nav-config-tab">
            <div id="role-list-container">
                <a id="to-role-form" href="#">[+] Crear Cargo</a>
                <table id="role-list" class="table table-striped cn-grid">
                    <thead>
                    <tr>
                        <th data-field="id" data-visible="false"></th>
                        <th data-field="titulo" data-sortable="true" scope="col">Nombre</th>
                        <th data-field="descripcion" data-sortable="true" scope="col">Descripción</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($roles as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{str_limit($item->titulo,60)}}</td>
                            <td>{{str_limit($item->descripcion,100)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('scripts-ready')
    $('#area-list').bootstrapTable();
    $('#role-list').bootstrapTable();
@endpush