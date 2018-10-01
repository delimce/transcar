@extends('layouts.app')
@section('content')

    @component("components.pageTitle",['title' => 'Administración - Mesas y Líneas'])
    @endcomponent

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-table-tab" data-toggle="tab" href="#nav-table" role="tab"
               aria-controls="nav-table" aria-selected="true">Mesas</a>
            <a class="nav-item nav-link" id="nav-line-tab" data-toggle="tab" href="#nav-line" role="tab"
               aria-controls="nav-line" aria-selected="false">Líneas</a>
        </div>
    </nav>

    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-table" role="tabpanel" aria-labelledby="nav-table-tab">

            <div id="table-list-container">
                <a id="to-table-form" href="#">[+] Crear Mesa</a>
                <table id="table-list" data-search="true" class="table table-striped cn-grid">
                    <thead>
                    <tr>
                        <th data-field="id" data-visible="false"></th>
                        <th data-field="titulo" data-sortable="true" scope="col">Nombre</th>
                        <th data-field="ubicacion" data-sortable="true" scope="col">Ubicación</th>
                        <th data-field="activo" data-sortable="true" scope="col">Activa?</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tables as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{str_limit($item->titulo,70)}}</td>
                            <td>{{str_limit($item->ubicacion,100)}}</td>
                            <td>{{ $item->activo ? "SI" : "NO" }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @include('pages.parts.table_form')
        </div>
        <div class="tab-pane fade" id="nav-line" role="tabpanel" aria-labelledby="nav-line-tab">
            <div id="line-list-container">
                <a id="to-line-form" href="#">[+] Crear Linea</a>
                <table id="line-list" data-search="true" class="table table-striped cn-grid">
                    <thead>
                    <tr>
                        <th data-field="id" data-visible="false"></th>
                        <th data-field="titulo" data-sortable="true" scope="col">Nombre</th>
                        <th data-field="descripcion" data-sortable="true" scope="col">Descripción</th>
                        <th data-field="mesa" data-sortable="true" scope="col">Mesa</th>
                        <th data-field="activo" data-sortable="true" scope="col">Activa?</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($lines as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{str_limit($item->titulo,70)}}</td>
                            <td>{{str_limit($item->descripcion,60)}}</td>
                            <td>{{str_limit($item->table->titulo,100)}}</td>
                            <td>{{ $item->activo ? "SI" : "NO" }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @include('pages.parts.line_form')
        </div>

    </div>

@endsection

@push('scripts-ready')
    $('#table-list').bootstrapTable();
    $('#line-list').bootstrapTable();
    reloadTableSelectBox();
@endpush