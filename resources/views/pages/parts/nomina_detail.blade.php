<table class="table">
    <thead>
    <tr class="d-flex">
        <th class="col-1">CI</th>
        <th class="col-2">Empleado</th>
        <th class="col-2">Cargo</th>
        <th class="col-2">Salario Base 50% (BS)</th>
        <th class="col-2">Bono cargo (BS)</th>
        <th class="col-2">Asistencia (BS)</th>
        <th class="col-2">Horas extra (dias)</th>
        <th class="col-2">Horas extra (BS)</th>
        <th class="col-2">N°: Cajas/Paletas</th>
        <th class="col-2">Producción (BS)</th>
        <th class="col-1">TOTAL</th>
    </tr>
    </thead>
    <tbody>
    @foreach($results as $res)
        <tr class="d-flex">
            <th class="col-sm-1">{{$res->cedula}}</th>
            <th class="col-sm-2">{{$res->nombre}}</th>
            <td class="col-sm-2">{{$res->cargo}}</td>
            <td class="col-sm-2">{{$res->base}}</td>
            <td class="col-sm-2">{{$res->bono_extra}}</td>
            <td class="col-sm-2">{{$res->asistencia}}</td>
            <td class="col-sm-2">{{$res->diashe}}</td>
            <td class="col-sm-2">{{$res->extra}}</td>
            <?php
            if ($res->unidad == 'paleta') {
                $total_unity = floor($res->ncajas / $factor);
                $unity = 'P';
            } else {
                $total_unity = $res->ncajas;
                $unity = 'C';
            }
            ?>
            <td class="col-sm-2">{{$total_unity}}{{ $total_unity >0 ? $unity:'' }}</td>
            <td class="col-sm-2">
                {{$prod = $total_unity*$res->produccion}}</td>
            <th class="col-sm-1" style="text-align: right">
                {{number_format(\App\Http\Controllers\Transcar\ReportController
            ::totalSalary($res->base,$res->bono_extra,$res->asistencia,$res->extra,$prod),2)}}
            </th>
        </tr>
    @endforeach
    </tbody>
</table>