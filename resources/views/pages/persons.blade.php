@extends('layouts.app')
@section('content')

    @component("components.pageTitle",['title' => 'Administración - Empleados'])
    @endcomponent

    <div id="person-list-container">
        <a id="to-person-form" href="#">[+] Crear Empleado</a>
        <table id="person-list" class="table table-striped cn-grid">
            <thead>
            <tr>
                <th data-field="id" data-visible="false"></th>
                <th data-field="nombre" data-sortable="true" scope="col">Nombre</th>
                <th data-field="cedula" data-sortable="true" scope="col">Cedula</th>
                <th data-field="ingreso" data-sortable="true" scope="col">Ingreso</th>
                <th data-field="cargo" data-sortable="true" scope="col">Cargo</th>
            </tr>
            </thead>
            <tbody>
            @foreach($persons as $item)
                <tr>
                    <td>{{$item->id}}</td>
                    <td>{{str_limit($item->nombre.' '.$item->apellido,100)}}</td>
                    <td>{{str_limit($item->cedula,20)}}</td>
                    <td>{{str_limit($item->fecha_ingreso,20)}}</td>
                    <td>{{str_limit($item->role->nombre,20)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @include('pages.parts.person_form')
@endsection

@push('scripts-ready')
    $('#person-list').bootstrapTable();
    reloadRoleSelectBox();
    reloadAreaSelectBox();
@endpush