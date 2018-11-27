<table id="nomina-table" class="table table-striped">
    <thead>
    <tr>
        <th style="position: absolute; background: #F8FAFC">Empleado</th>
        <th></th>
        <th>CI</th>
        <th>Cargo</th>
        <th>Salario Base 50% (BS)</th>
        <th>Bono cargo (BS)</th>
        <th>Asistencia (BS)</th>
        <th>Horas extra (dias)</th>
        <th>Horas extra (BS)</th>
        <th>N°: Cajas/Paletas</th>
        <th>Producción (BS)</th>
        <th style="text-align: right">TOTAL</th>
    </tr>
    </thead>
    <tbody>
    @foreach($results as $i => $res)
        <tr>
            <td style="position: absolute; background: <?=($i % 2 == 0) ? '#EBEDEF' : '#F8FAFC' ?>">
                {{str_limit($res->nombre,25)}}
            </td>
            <td></td>
            <td>{{$res->cedula}}</td>
            <td>{{str_limit($res->cargo,30)}}</td>
            <?php $salary = \App\Http\Controllers\Transcar\ReportController::
            prorateSalary($res->id,$res->base,$res->fecha_ingreso) ?>
            <td><b>{{$salary}}</b></td>
            <td><b>{{$res->bono_extra}}</b></td>
            <td><b>{{$res->asistencia}}</b></td>
            <td>{{$res->diashe}}</td>
            <td><b>{{$res->extra}}</b></td>
            <?php
            if ($res->unidad == 'paleta') {
                $total_unity = floor($res->ncajas / $factor);
                $unity = 'P';
            } else {
                $total_unity = $res->ncajas;
                $unity = 'C';
            }
            ?>
            <td>{{$total_unity}}{{ $total_unity >0 ? $unity:'' }}</td>
            <td><b>{{$prod = $total_unity*$res->produccion}}</b></td>
            <td style="text-align: right">
                {{number_format(\App\Http\Controllers\Transcar\ReportController
            ::totalSalary($salary,$res->bono_extra,$res->asistencia,$res->extra,$prod),2)}}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>