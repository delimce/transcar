<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Appearance extends Model
{
    protected $table = 'tbl_asistencia';

    public function person()
    {
        return $this->belongsTo('App\Models\Person', 'empleado_id');
    }

    public function table()
    {
        return $this->belongsTo('App\Models\Table', 'mesa_id');
    }

    public function line()
    {
        return $this->belongsTo('App\Models\Line', 'linea_id');
    }

    public function getAppearance($start, $end, $table)
    {
        $date_start = $start.' 00:00:00';
        $date_end = $end.' 23:59:59';
        if ($table!=='') {
            $tableFilter = "and a.mesa_id = $table ";
        } else {
            $tableFilter = "";
        }

        $query = "SELECT
                    e.id,
                    e.codigo,
                    concat(e.nombre,' ',e.apellido) as nombre,
                    (select count(*) from tbl_inasistencia ina where ina.empleado_id = e.id and ina.justificada = 0 and ina.fecha BETWEEN '$date_start' and '$date_end')  as inasistencia,
                    GROUP_CONCAT(a.fecha) as fechas,
                    GROUP_CONCAT(ABS(if(isnull(a.hora_salida),17,HOUR(a.hora_salida)) - hour(a.hora_entrada))) as horas
                    FROM
                    tbl_asistencia AS a
                    INNER JOIN tbl_empleado AS e ON a.empleado_id = e.id
                    where a.fecha BETWEEN '$date_start' and '$date_end' $tableFilter 
                    GROUP BY
                    e.id";
        return $results = DB::select(DB::raw($query));

    }

}
