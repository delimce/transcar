<table class="table">
    <thead>
    <tr class="d-flex">
        <th class="col-2">CI</th>
        <th class="col-3">Empleado</th>
        <th class="col-2">Salario Base</th>
        <th class="col-2">Bono cargo</th>
        <th class="col-2">Asistencia</th>
        <th class="col-2">Horas extra</th>
    </tr>
    </thead>
    <tbody>
    @foreach($results as $res)
        <tr class="d-flex">
            <th class="col-sm-2">{{$res->cedula}}</th>
            <th class="col-sm-3">{{$res->nombre}}</th>
            <td class="col-sm-2">{{$res->base}}</td>
            <td class="col-sm-2">{{$res->bono_extra}}</td>
            <td class="col-sm-2">{{$res->asistencia}}</td>
            <td class="col-sm-2">{{$res->extra}}</td>
        </tr>
    @endforeach
    </tbody>
</table>