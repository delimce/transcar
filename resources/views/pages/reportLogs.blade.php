@extends('layouts.app')
@section('content')

@component("components.pageTitle",['title' => 'Reportes - Actividad de usuarios'])
@endcomponent

<p>&nbsp&nbsp;</p>


<div id="logs-list-container">
    <table id="log-list" data-search="true" data-pagination="true" class="table table-striped cn-grid">
        <thead>
        <tr>
            <th data-field="id" data-visible="false"></th>
            <th data-field="usuario" data-sortable="true" scope="col">Usuario</th>
            <th data-field="tipo" data-sortable="true" scope="col">Tipo</th>
            <th data-field="actividad" data-sortable="true" scope="col">Actividad</th>
            <th data-field="fecha" data-sortable="true" scope="col">Fecha</th>
        </tr>
        </thead>
        <tbody>
        @foreach($list as $item)
            <tr>
                <td>{{$item->id}}</td>
                <td>{{str_limit($item->user->usuario,120)}}</td>
                <td>{{str_limit($item->tipo,20)}}</td>
                <td>{{str_limit($item->actividad,120)}}</td>
                <td>{{str_limit($item->created_at,20)}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<p>&nbsp;</p>


@endsection
@push('scripts-ready')
    $('#log-list').bootstrapTable();
@endpush