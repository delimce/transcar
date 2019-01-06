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
                    Generar Reporte
                </button>
                <span>&nbsp;&nbsp;</span>
                <button id="report-txt" style="margin-top: 27px" class="btn btn-dark" type="button">
                    Generar archivo .txt
                </button>
            </div>
        </div>

    </form>

    <p>&nbsp</p>

    <table id="nomina-table" 
    data-search="true" 
    data-striped="true"
    data-height="640">
    <thead>
    <tr>
        <th  data-width="235px"  data-field="nombre" data-sortable="true">Empleado</th>
        <th  data-width="110px" data-field="codigo" data-sortable="true">Código</th>
        <th  data-width="110px" data-field="cedula" data-sortable="true">Cédula</th>
        <th  data-width="235px" data-field="cargo" data-sortable="true">Cargo</th>
        <th  data-field="salario">Salario Base 50% (BS)</th>
        <th  data-field="bonificacion">Bono Especial (BS)</th>
        <th  data-field="bono_cargo">Bono cargo (BS)</th>
        <th  data-field="bono_asistencia">Asistencia (BS)</th>
        <th  data-field="horas_ex_dias">Horas extra (dias)</th>
        <th  data-field="horas_ex_costo">Horas extra (BS)</th>
        <th  data-field="n_cajas">N°: Cajas/Paletas</th>
        <th  data-field="produccion">Producción (BS)</th>
        <th  data-field="total" data-sortable="true">TOTAL</th>
    </tr>
    </thead>
        <tbody>
            <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
             </tr>
        </tbody>
    </table>


@endsection

@push('scripts-ready')
    $('#nomina-table').bootstrapTable();
@endpush