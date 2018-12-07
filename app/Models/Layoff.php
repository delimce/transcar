<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layoff extends Model
{
    protected $table = 'tbl_egreso';

    public function person()
    {
        return $this->belongsTo('App\Models\Person', 'empleado_id');
    }

}
