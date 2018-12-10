@extends('layouts.app')
@section('content')

@component("components.pageTitle",['title' => 'Administración - Egresos de Empleados'])
@endcomponent

<p>&nbsp&nbsp;</p>

<div id="logs-list-container">
    <table id="layoff-list" data-search="true" data-pagination="true" class="table table-striped cn-grid">
        <thead>
        <tr>
            <th data-field="id" data-visible="false"></th>
            <th data-field="nombre" data-sortable="true" scope="col">Empleado</th>
            <th data-field="cargo" data-sortable="true" scope="col">Cargo</th>
            <th data-field="motivo" data-sortable="true" scope="col">Motivo de egreso</th>
            <th data-field="fecha" data-sortable="true" scope="col">Fecha Egreso</th>
        </tr>
        </thead>
        <tbody>
        @foreach($list as $item)
            <tr>
                <td>{{$item->id}}</td>
                <td>{{str_limit($item->nombre,120)}}</td>
                <td>{{str_limit($item->cargo,80)}}</td>
                <td>{{str_limit($item->motivo,150)}}</td>
                <td>{{str_limit($item->fecha,12)}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<p>&nbsp;</p>

<!-- Modal -->
<div id="layoff-reverse" class="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Egreso de Empleado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="restore-ly" class="modal-body" data-person="">
                    <div class="col-sm-12">
                        <label class="control-label">Empleado</label><br>
                        <div id="layoff-name"></div>
                    </div>
                    <br>
                    <div class="col-sm-12">
                        <label class="control-label">Cargo</label><br>
                        <div id="layoff-role"></div>
                    </div>
                    <br>
                    <div class="col-sm-12">
                        <label for="motivo" class="control-label">Motivo de egreso</label><br>
                       <span id="layoff-subject"></span>
                    </div>
                    <br>
                    <div class="col-sm-12">
                        <label for="fecha" class="control-label">fecha de egreso</label><br>
                        <span id="layoff-date"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="restore-person" class="btn btn-primary" type="submit">
                        Reingresar Empleado
                    </button>
                </div>
        </div>
    </div>
</div>


@endsection
@push('scripts-ready')
    $('#layoff-list').bootstrapTable();
@endpush