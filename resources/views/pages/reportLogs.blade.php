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

<!-- Modal -->
<div id="log-detail" class="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detalle Log de Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="restore-ly" class="modal-body" data-person="">
                    <div class="col-sm-12">
                        <label class="control-label">Nombre</label><br>
                        <div id="log-detail-name"></div>
                    </div>
                    <div class="col-sm-12">
                            <label class="control-label">IP</label><br>
                            <div id="log-detail-ip"></div>
                        </div>
                    <br>
                    <div class="col-sm-12">
                        <label class="control-label">tipo</label><br>
                        <div id="log-detail-type"></div>
                    </div>
                    <br>
                    <div class="col-sm-12">
                        <label class="control-label">Dispositivo</label><br>
                       <span id="log-detail-client"></span>
                    </div>
                    <br>
                    <div class="col-sm-12">
                        <label class="control-label">fecha</label><br>
                        <span id="log-detail-date"></span>
                    </div>
                    <div class="col-sm-12">
                            <label for="fecha" class="control-label">Actividad</label><br>
                            <span id="log-detail-detail"></span>
                        </div>
                </div>
        </div>
    </div>
</div>


@endsection
@push('scripts-ready')
    $('#log-list').bootstrapTable();
@endpush