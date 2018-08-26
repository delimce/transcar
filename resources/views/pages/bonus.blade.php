@extends('layouts.app')
@section('content')

    @component("components.pageTitle",['title' => 'Administración - Bonificaciones'])
    @endcomponent

    <div id="bonus-list-container">
        <a id="to-bonus-form" href="#">[+] Crear Bonificación</a>
        <table id="bonus-list" data-search="true" class="table table-striped cn-grid">
            <thead>
            <tr>
                <th data-field="id" data-visible="false"></th>
                <th data-field="titulo" data-sortable="true" scope="col">Nombre</th>
                <th data-field="tipo" data-sortable="true" scope="col">Tipo</th>
                <th data-field="fecha" data-sortable="true" scope="col">Fecha</th>
                <th data-field="monto" data-sortable="true" scope="col">Monto</th>
            </tr>
            </thead>
            <tbody>
            @foreach($bonus as $item)
                <tr>
                    <td>{{$item->id}}</td>
                    <td>{{str_limit($item->titulo,30)}}</td>
                    <td>{{str_limit($item->tipo,20)}}</td>
                    <td>{{str_limit($item->fecha,20)}}</td>
                    <td>{{str_limit($item->monto,20)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @include('pages.parts.bonus_form')
@endsection

@push('scripts-ready')
    $('#bonus-list').bootstrapTable();

@endpush