<table class="table">
    <thead>
    <tr class="d-flex">
        <th class="col-2">CI</th>
        <th class="col-3">Empleado</th>
        <th class="col-3">Cargo</th>
        <th class="col-2">Salario Base 50%</th>
        <th class="col-2">Monto Bono cargo</th>
        <th class="col-2">Monto Asistencia</th>
        <th class="col-2">Horas extra</th>
        <th class="col-2">N°:Cajas/Paletas</th>
        <th class="col-2">Monto Producción</th>
        <th class="col-1">TOTAL</th>
    </tr>
    </thead>
    <tbody>
    @foreach($results as $res)
        <tr class="d-flex">
            <th class="col-sm-2">{{$res->cedula}}</th>
            <th class="col-sm-3">{{$res->nombre}}</th>
            <th class="col-sm-3">{{$res->cargo}}</th>
            <td class="col-sm-2">{{$res->base}}</td>
            <td class="col-sm-2">{{$res->bono_extra}}</td>
            <td class="col-sm-2">{{$res->asistencia}}</td>
            <td class="col-sm-2">{{$res->extra}}</td>
            <td class="col-sm-2">{{$res->ncajas}}</td>
            <td class="col-sm-2">{{$prod = $res->ncajas*$res->produccion}}</td>
            <th class="col-sm-1" style="text-align: right">
                {{\App\Http\Controllers\Transcar\ReportController
            ::totalSalary($res->base,$res->bono_extra,$res->asistencia,$res->extra,$prod)}}
            </th>
        </tr>
    @endforeach
    </tbody>
</table>