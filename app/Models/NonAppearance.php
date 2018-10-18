<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class NonAppearance extends Model
{
    protected $table = 'tbl_inasistencia';

    public function person()
    {
        return $this->belongsTo('App\Models\Person', 'empleado_id');
    }

    public function date()
    {
        return Carbon::parse($this->fecha)->format("d/m/Y");
    }


}
