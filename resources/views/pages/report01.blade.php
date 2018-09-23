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
            <span>{{$tableInfo->showLineNames()}}</span>
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
                            {!!\App\Http\Controllers\Transcar\ReportController::
                            findDateinAppearance($mdates,$mhours,$day["date"])!!}
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

    </div>

@endsection

@push('scripts-ready')
    reloadTableSelectBox({{$table}});
@endpush