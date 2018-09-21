@extends('layouts.app')
@section('content')

    @component("components.pageTitle",['title' => 'Reportar - Asistencia y Producción'])
    @endcomponent


    <div class="row">
        <div class="col-sm-6">
            <label for="fecha" class="control-label">Fecha desde</label>
            <input type="date" class="form-control" id="fecha" name="fecha"
                   placeholder="fecha"
                   autocomplete="my-date">
        </div>
        <div class="col-sm-6">
            <label for="fecha" class="control-label">Fecha hasta</label>
            <input type="date" class="form-control" id="fecha" name="fecha"
                   placeholder="fecha"
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

        </div>
    </div>

    <p>&nbsp;</p>

@endsection

@push('scripts-ready')
    reloadTableSelectBox();
@endpush