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

    public function getAppearance($start, $end, $table)
    {
        $params = array(
            'startDate' => $start,
            'endDate' => $end,
        );

        if ($table) {
            $params['tableID'] = $table;
            $tableFilter = "and e.mesa_id = :tableID ";
        } else {
            $tableFilter = "";
        }


        $query = "SELECT
                    e.id,
                    e.cedula,
                    concat(e.nombre,' ',e.apellido) as nombre,
                    GROUP_CONCAT(a.fecha) as fechas,
                    GROUP_CONCAT(ABS(if(isnull(a.hora_salida),17,HOUR(a.hora_salida)) - hour(a.hora_entrada))) as horas,
                    GROUP_CONCAT(a.id) as asis
                    FROM
                    tbl_asistencia AS a
                    INNER JOIN tbl_empleado AS e ON a.empleado_id = e.id
                    where a.fecha BETWEEN :startDate and :endDate $tableFilter 
                    GROUP BY
                    e.id";

        return $results = DB::select(DB::raw($query), $params);

    }

}
