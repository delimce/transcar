@extends('layouts.app')
@section('content')

    @component("components.pageTitle",['title' => 'Administración - Usuarios'])
    @endcomponent

            <div id="users-list-container">
                <a id="to-user-form" href="#">[+] Crear usuario</a>
                <table id="users-list" class="table table-striped cn-grid">
                    <thead>
                    <tr>
                        <th data-field="id" data-visible="false"></th>
                        <th data-field="nombre" data-sortable="true" scope="col">Nombre</th>
                        <th data-field="apellido" data-sortable="true" scope="col">Apellido</th>
                        <th data-field="usuario" data-sortable="true" scope="col">Usuario</th>
                        <th data-field="perfil" data-sortable="true" scope="col">Perfil</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{str_limit($item->nombre,50)}}</td>
                            <td>{{str_limit($item->apellido,50)}}</td>
                            <td>{{$item->usuario}}</td>
                            <td>{{$item->profile->titulo}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @include('pages.parts.user_form')

@endsection

@push('scripts-ready')
    $('#users-list').bootstrapTable();
@endpush