@extends('layouts.app')

@section('content')

    @component("components.pageTitle",['title' => 'Inicio'])
    @endcomponent


    <div id="home-panel" class="row">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Control de asistencia</h3>
                <h1><i class="fa fa-calendar-check-o" aria-hidden="true"></i></h1>
                <p class="card-text">Carga de Asistencias o Inasistencias de empleados al día de hoy.</p>
                <a href="{!! url('appear') !!}" class="btn btn-primary">Entrar al módulo</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Registro de Producción</h3>
                <h1><i class="fa fa-cubes" aria-hidden="true"></i></h1>
                <p class="card-text">Carga de producción por mesa y linea del almacen.</p>
                <a href="{!! url('prod') !!}" class="btn btn-primary">Entrar al módulo</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Reportes</h3>
                <h1><i class="fa fa-line-chart" aria-hidden="true"></i></h1>
                <p class="card-text">Reportes para el pago de la nómina.</p>
                <a href="#" class="btn btn-primary">Entrar al módulo</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Administración</h3>
                <h1><i class="fa fa-tachometer" aria-hidden="true"></i></h1>
                <p class="card-text">Maestros y administración de los datos principales.</p>
                <a href="{!! url('system') !!}" class="btn btn-primary">Entrar al módulo</a>
            </div>
        </div>

    </div>

@endsection
