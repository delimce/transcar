@extends('layouts.app')
@section('content')

    @component("components.pageTitle",['title' => 'Reportar - Asistencia y Producción'])
    @endcomponent

    <form id="filter" action="">
        <div class="row">
            <div class="col-sm-6">
                <label for="fecha" class="control-label">Fecha desde</label>
                <input type="date" class="form-control" id="desde" name="desde"
                       placeholder="fecha" value="{{$init}}"
                       autocomplete="my-date">
            </div>
            <div class="col-sm-6">
                <label for="fecha" class="control-label">Fecha hasta</label>
                <input type="date" class="form-control" id="hasta" name="hasta"
                       placeholder="fecha" value="{{$end}}"
                       autocomplete="my-date">
            </div>
        </div>

        <br>

        <div class="row">
            <div class="col-sm-6">
                <label for="mesa" class="control-label">Mesa</label><br>
                <select name="mesa" data-style="form-select" class="selectpickerTable" data-width="90%">
                </select>
            </div>
            <div class="col-sm-6">
                <button id="report" style="margin-top: 27px" class="btn btn-primary" type="submit">
                    Reportar
                </button>
            </div>
        </div>

    </form>

    <p>&nbsp&nbsp;</p>

    <div class="report">

        <div>
            <span style="font-weight: bolder">Lineas:</span>
            <span>
                @if(count($tableInfo)>0)
                    {{$tableInfo->showLineNames()}}
                @endif
            </span>
        </div>

        <table class="table">
            <thead>
            <tr class="d-flex">
                <th class="col-1">CI</th>
                <th class="col-2">Empleado</th>
                @foreach($days as $day)
                    <th class="col-2">{{$day["name"]}} {{$day["day"]}}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($results as $item)
                <tr class="d-flex">
                    <th class="col-sm-1">{{$item->cedula}}</th>
                    <th class="col-sm-2">{{$item->nombre}}</th>
                    <?php
                    $mdates = explode(",", $item->fechas);
                    $mhours = explode(",", $item->horas);
                    ?>
                    @foreach($days as $day)
                        <td class="col-sm-2">
                            <span class="det-appear" data-id="{{$item->id}}" data-date="{{$day["date"]}}">
                            {!!\App\Http\Controllers\Transcar\ReportController::
                            findDateinAppearance($mdates,$mhours,$day["date"])!!}
                            </span>
                        </td>
                    @endforeach
                </tr>
            @endforeach
            <tr class="d-flex">
                <th class="col-sm-2">Total paletas</th>
                <td class="col-sm-1">&nbsp;</td>
                @foreach($days as $day)
                    <th class="col-2">
                        {!!\App\Http\Controllers\Transcar\ReportController::
                               findProdByDate($day["date"],$production,'tpaletas')!!}
                    </th>
                @endforeach
            </tr>
            <tr class="d-flex">
                <th class="col-sm-2">Total Cajas</th>
                <td class="col-sm-1">&nbsp;</td>
                @foreach($days as $day)
                    <th class="col-2">
                        {!!\App\Http\Controllers\Transcar\ReportController::
                               findProdByDate($day["date"],$production,'tcajas')!!}
                    </th>
                @endforeach
            </tr>
            </tbody>
        </table>

        <!-- Modal -->
        <div id="appear-detail" class="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detalle de asistencia</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div style="padding: 10px" class="row" id="person-id" data-id="">
                            <div class="col-sm-6">
                                <span class="appear-subtitle">Nombre:</span>&nbsp;<span id="asis_nombre"></span><br>
                                <span class="appear-subtitle">Telefono:</span>&nbsp;<span  id="asis_tlf"></span><br>
                                <span class="appear-subtitle">Fecha:</span>&nbsp;<span  id="asis_fecha"></span><br>
                                <span class="appear-subtitle">Mesa:</span>&nbsp;<span  id="asis_mesa"></span><br>
                                <span class="appear-subtitle">hora llegada:</span>&nbsp;<span  id="asis_llegada"></span><br>
                                <span class="appear-subtitle">Notas:</span>&nbsp;<span  id="asis_nota"></span><br>
                            </div>
                            <div class="col-sm-6">
                                <span class="appear-subtitle">Cedula:</span>&nbsp;<span id="asis_cedula"></span><br>
                                <span class="appear-subtitle">Email:</span>&nbsp;<span id="asis_email"></span><br>
                                <span class="appear-subtitle">Turno:</span>&nbsp;<span  id="asis_turno"></span><br>
                                <span class="appear-subtitle">Linea:</span>&nbsp;<span  id="asis_linea"></span><br>
                                <span class="appear-subtitle">hora salida:</span>&nbsp;<span id="asis_salida"></span><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts-ready')
    reloadTableSelectBox({{$table}});
@endpush