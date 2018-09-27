@extends('layouts.app')
@section('content')

    @component("components.pageTitle",['title' => 'Operaciones - Registrar Asistencia Diaria'])
    @endcomponent

    <div>
        <h4> Asistencias del día: {{$date}}</h4>
    </div>

{{--    <div class="row">
        <div class="col-sm-6">
            <label for="area" class="control-label">Area</label><br>
            <select name="area" id="area" title="Seleccione el area." data-style="form-select" class="selectpickerArea"
                    data-width="90%">
                <option value="All" selected>Todas</option>
                @foreach($areas as $area)
                    <option value="{{$area->id}}">{{$area->nombre}}</option>
                @endforeach
            </select>

        </div>
        <div class="col-sm-6">
            <label for="role" class="control-label">Cargo</label><br>
            <select name="role" id="role" title="Seleccione el cargo." class="selectpickerRole"
                    data-style="form-select" data-width="90%">
            </select>
        </div>
    </div>

    <p>&nbsp;</p>--}}

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-asis-tab" data-toggle="tab" href="#nav-asis" role="tab"
               aria-controls="nav-asis" aria-selected="true">Asistencias</a>
            <a class="nav-item nav-link" id="nav-ina-tab" data-toggle="tab" href="#nav-ina" role="tab"
               aria-controls="nav-ina" aria-selected="false">Inasistencias</a>
        </div>
    </nav>

    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-asis" role="tabpanel" aria-labelledby="nav-asis-tab">
            <div id="appear-list-container">
                <table id="appear-list" data-search="true" data-unique-id="id" class="table table-striped cn-grid">
                    <thead>
                    <tr>
                        <th data-field="id" data-visible="false"></th>
                        <th data-field="nombre" data-sortable="true" scope="col">Nombre</th>
                        <th data-field="cedula" data-sortable="true" scope="col">Cédula</th>
                        <th data-field="cargo" data-sortable="true" scope="col">Cargo</th>
                        <th data-field="ubicacion" data-sortable="true" scope="col">Ubicación</th>
                        <th data-field="entrada" data-sortable="true" scope="col">Entrada</th>
                        <th data-field="salida" data-sortable="true" scope="col">Salida</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($persons as $item)
                        <tr>
                            <td>{{$item['id']}}</td>
                            <td>{{str_limit($item['nombre'],100)}}</td>
                            <td>{{str_limit($item['cedula'],20)}}</td>
                            <td>{{str_limit($item['cargo'],20)}}</td>
                            <td>{{str_limit($item['ubicacion'],40)}}</td>
                            <td>{{$item['entrada']}}</td>
                            <td>{{$item['salida']}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane fade" id="nav-ina" role="tabpanel" aria-labelledby="nav-ina-tab">
            <div id="non-appear-list-container">
                <table id="non-appear-list" data-search="true" class="table table-striped cn-grid">
                    <thead>
                    <tr>
                        <th data-field="id" data-visible="false"></th>
                        <th data-field="nombre" data-sortable="true" scope="col">Nombre</th>
                        <th data-field="cedula" data-sortable="true" scope="col">Cédula</th>
                        <th data-field="cargo" data-sortable="true" scope="col">Cargo</th>
                        <th data-field="ubicacion" data-sortable="true" scope="col">Ubicación</th>
                        <th data-field="fecha" data-sortable="true" scope="col">Fecha</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($nonAppear as $item)
                        <tr>
                            <td>{{$item['id']}}</td>
                            <td>{{str_limit($item['nombre'],100)}}</td>
                            <td>{{str_limit($item['cedula'],20)}}</td>
                            <td>{{str_limit($item['cargo'],25)}}</td>
                            <td>{{str_limit($item['ubicacion'],50)}}</td>
                            <td>{{$item['fecha']}} </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>


    </div>

    @include('pages.parts.appear_actions')
    @include('pages.parts.non_appear_actions')
@endsection

@push('scripts-ready')
    $('#appear-list').bootstrapTable();
    $('#non-appear-list').bootstrapTable();
    ///loading area list to select
    $('.selectpickerArea').selectpicker('refresh');
    $('.selectpickerRole').selectpicker('refresh');
@endpush