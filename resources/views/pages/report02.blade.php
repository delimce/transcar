@extends('layouts.app')
@section('content')

    @component("components.pageTitle",['title' => 'Reportes - Cálculo de Nómina'])
    @endcomponent


    <form id="filter" action="">
        <div class="row">
            <div class="col-sm-6">
                <label for="month" class="control-label">Mes a pagar:</label>
                <select id="month"  name="month" data-style="form-select" class="selectpicker" data-width="90%">
                    @foreach($months as $month)
                        <option value="{{$month['number']}}">{{$month['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6">
                <label for="fecha" class="control-label">Quincena:</label>
                <div class="custom-control custom-radio">
                    <input type="radio" id="customRadio1" value="1" name="quincena" class="custom-control-input" checked>
                    <label class="custom-control-label" for="customRadio1">Primera: 01-15</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" id="customRadio2" value="2" name="quincena" class="custom-control-input">
                    <label class="custom-control-label" for="customRadio2">Segunda: 15-<span id="last-day">{{$days}}</span></label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <button id="report-det" style="margin-top: 27px" class="btn btn-primary" type="button">
                    Ver detalle
                </button>
                <span>&nbsp;&nbsp;</span>
                <button id="report-txt" style="margin-top: 27px" class="btn btn-dark" type="button">
                    Generar archivo .txt
                </button>
            </div>
        </div>

    </form>

    <p>&nbsp&nbsp;</p>

    <div id="nomina-det" class="report">
    </div>

@endsection