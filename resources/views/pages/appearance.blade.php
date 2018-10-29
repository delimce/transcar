@extends('layouts.app')
@section('content')

    @component("components.pageTitle",['title' => 'Operaciones - Registrar Asistencia Diaria'])
    @endcomponent

    <div class="row" style="display: inline-block; padding-left: 20px; padding: auto; font-size: medium">
      <form id="appear_form">
        Asistencias del día: <input type="date" id="appear_date" name="appear_date" value="{{$date}}">
        <button id="appear-search" class="btn btn-primary" type="submit">
            Buscar
        </button>
      </form>
    </div>
    <br><br>

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

            <button id="appear-batch" class="btn btn-warning" type="button">
                Registrar asistencia a todo el personal
            </button>
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
                        <th data-field="extra" data-sortable="true" scope="col">Horas extra</th>
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
                            <td>{{$item['extra']}}</td>
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

    @include('pages.parts.appear_actions',["date"=>$date])
    @include('pages.parts.non_appear_actions',["date"=>$date])
@endsection

@push('scripts-ready')
    $('#appear-list').bootstrapTable();
    $('#non-appear-list').bootstrapTable();
    ///loading area list to select
    $('.selectpickerArea').selectpicker('refresh');
    $('.selectpickerRole').selectpicker('refresh');
@endpush