<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'tbl_cargo';

    public function area()
    {
        return $this->belongsTo('App\Models\Area','area_id');
    }


}
