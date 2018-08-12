<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    protected $table = 'tbl_usuario_log';

    public function user()
    {
        return $this->belongsTo('App\Models\User','usuario_id');
    }

}
