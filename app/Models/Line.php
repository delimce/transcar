<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    protected $table = 'tbl_linea';

    public function table()
    {
        return $this->belongsTo('App\Models\Table','mesa_id');
    }

}
