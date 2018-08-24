<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $table = 'tbl_mesa';

    public function lines()
    {
        return $this->hasMany('App\Models\Line','mesa_id');
    }


}
