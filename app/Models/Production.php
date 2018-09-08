<?php

namespace App\Models;
use Carbon\Carbon;

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


}
