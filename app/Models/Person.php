<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use SoftDeletes;
    protected $table = 'tbl_empleado';

    public function role()
    {
        return $this->belongsTo('App\Models\Role','cargo_id');
    }

    protected $dates = ['deleted_at'];

}
