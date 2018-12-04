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
            <th data-field="empleado" data-sortable="true" scope="col">Empleado</th>
            <th data-field="cargo" data-sortable="true" scope="col">Cargo</th>
            <th data-field="motivo" data-sortable="true" scope="col">Motivo de egreso</th>
            <th data-field="fecha" data-sortable="true" scope="col">Fecha</th>
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


@endsection
@push('scripts-ready')
    $('#layoff-list').bootstrapTable();
@endpush