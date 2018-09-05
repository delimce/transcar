<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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


}
