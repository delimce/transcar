<?php

namespace App\Models;
use Carbon\Carbon;
use DB;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $table = 'tbl_produccion';

    public function table()
    {
        return $this->belongsTo('App\Models\Table','mesa_id');
    }

    public function line()
    {
        return $this->belongsTo('App\Models\Line','linea_id');
    }

    public function date(){
        return Carbon::parse($this->fecha)->format('d/m/Y');
    }

    public function time(){
        return Carbon::parse($this->fecha)->format('H:i');
    }

    public function getProduction($start,$end,$table)
    {
        $params = array(
            'tableID' => $table,
            'startDate' => $start,
            'endDate' => $end,
        );
        $query = "SELECT
                    date(p.fecha) as fecha,
                    sum(p.cajas) as tcajas,
                    round(sum(p.cajas)/(select caja_paleta from tbl_configuracion)) as tpaletas
                    FROM
                    tbl_produccion AS p
                    where p.mesa_id = :tableID and p.fecha BETWEEN :startDate and :endDate
                    GROUP BY
                    date(p.fecha)";

        return $results = DB::select(DB::raw($query), $params);

    }


}
