@extends('layouts.app')
@section('content')

    @component("components.pageTitle",['title' => 'Operaciones - Control de producción'])
    @endcomponent

    <div>
        <h4> Producción del día: {{$date}}</h4>
    </div>

    <div id="prod-list-container">
        <a id="to-prod-form" href="#">[+] Cargar producción</a>
        <table id="prod-list" data-search="true" data-unique-id="id" class="table table-striped cn-grid">
            <thead>
            <tr>
                <th data-field="id" data-visible="false"></th>
                <th data-field="fecha" data-sortable="true" scope="col">Fecha</th>
                <th data-field="hora" data-sortable="true" scope="col">Hora</th>
                <th data-field="cajas" data-sortable="true" scope="col">Cajas</th>
                <th data-field="mesa" data-sortable="true" scope="col">Mesa</th>
                <th data-field="linea" data-sortable="true" scope="col">Linea</th>
            </tr>
            </thead>
            <tbody>
            @foreach($prod as $item)
                <tr>
                    <td>{{$item['id']}}</td>
                    <td>{{str_limit($item['fecha'],100)}}</td>
                    <td>{{str_limit($item['hora'],20)}}</td>
                    <td>{{str_limit($item['cajas'],20)}}</td>
                    <td>{{str_limit($item['mesa'],45)}}</td>
                    <td>{{str_limit($item['linea'],45)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @include('pages.parts.prod_form')
@endsection


@push('scripts-ready')
    $('#prod-list').bootstrapTable();
    reloadTableSelectBox();

@endpush