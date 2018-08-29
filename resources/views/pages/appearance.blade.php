@extends('layouts.app')
@section('content')

    @component("components.pageTitle",['title' => 'Operaciones - Control de asistencia'])
    @endcomponent


    <div class="row">
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

    <p>&nbsp;</p>

    <div id="appear-list-container">
        <table id="appear-list" class="table table-striped cn-grid">
            <thead>
            <tr>
                <th data-field="id" data-visible="false"></th>
                <th data-field="nombre" data-sortable="true" scope="col">Nombre</th>
                <th data-field="cedula" data-sortable="true" scope="col">Cédula</th>
                <th data-field="cargo" data-sortable="true" scope="col">Cargo</th>
            </tr>
            </thead>
            <tbody>
            @foreach($persons as $item)
                <tr>
                    <td>{{$item->id}}</td>
                    <td>{{str_limit($item->nombre.' '.$item->apellido,100)}}</td>
                    <td>{{str_limit($item->cedula,20)}}</td>
                    <td>{{str_limit($item->role->nombre,20)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


@endsection

@push('scripts-ready')
    $('#appear-list').bootstrapTable();
    ///loading area list to select
    $('.selectpickerArea').selectpicker('refresh');
    $('.selectpickerRole').selectpicker('refresh');
@endpush