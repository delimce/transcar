<table class="table table-striped">
    <thead>
    <tr>
        <th class="col-1">CI</th>
        <th class="col-3">Empleado</th>
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
    @foreach($results as $i => $res)
        <tr>
            <th class="col-sm-1">{{$res->cedula}}</th>
            <th class="col-sm-3">{{str_limit($res->nombre,40)}}</th>
            <td class="col-sm-2">{{str_limit($res->cargo,30)}}</td>
            <td class="col-sm-2"><b>{{$res->base}}</b></td>
            <td class="col-sm-2"><b>{{$res->bono_extra}}</b></td>
            <td class="col-sm-2"><b>{{$res->asistencia}}</b></td>
            <td class="col-sm-2">{{$res->diashe}}</td>
            <td class="col-sm-2"><b>{{$res->extra}}</b></td>
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
            <td class="col-sm-2"><b>{{$prod = $total_unity*$res->produccion}}</b></td>
            <th class="col-sm-1" style="text-align: right">
                {{number_format(\App\Http\Controllers\Transcar\ReportController
            ::totalSalary($res->base,$res->bono_extra,$res->asistencia,$res->extra,$prod),2)}}
            </th>
        </tr>
    @endforeach
    </tbody>
</table>