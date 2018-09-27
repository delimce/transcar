@extends('layouts.app')
@section('content')

    @component("components.pageTitle",['title' => 'Administración - Departamentos y Cargos'])
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
                <a id="to-area-form" href="#">[+] Crear Departamento</a>
                <table id="area-list" data-search="true" class="table table-striped cn-grid">
                    <thead>
                    <tr>
                        <th data-field="id" data-visible="false"></th>
                        <th data-field="nombre" data-sortable="true" scope="col">Nombre</th>
                        <th data-field="descripcion" data-sortable="true" scope="col">Descripción</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($areas as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{str_limit($item->nombre,70)}}</td>
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
                <table id="role-list" data-search="true" class="table table-striped cn-grid">
                    <thead>
                    <tr>
                        <th data-field="id" data-visible="false"></th>
                        <th data-field="nombre" data-sortable="true" scope="col">Nombre</th>
                        <th data-field="descripcion" data-sortable="true" scope="col">Descripción</th>
                        <th data-field="area" data-sortable="true" scope="col">Area</th>
                        <th data-field="produccion" data-sortable="true" scope="col">% producción</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($roles as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{str_limit($item->nombre,70)}}</td>
                            <td>{{str_limit($item->descripcion,100)}}</td>
                            <td>{{str_limit($item->area->nombre,100)}}</td>
                            <td>{{str_limit($item->produccion_tipo,100)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @include('pages.parts.role_form')
        </div>
    
    </div>

@endsection

@push('scripts-ready')
    $('#area-list').bootstrapTable();
    $('#role-list').bootstrapTable();
    ///loading area list to select
    reloadAreaSelectBox();
@endpush