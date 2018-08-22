<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'tbl_area';

    public function roles()
    {
        return $this->hasMany('App\Models\Role','area_id');
    }


}
