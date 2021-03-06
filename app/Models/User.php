<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'tbl_usuario';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'usuario','password'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function info(){
        return $this->nombre.' '.$this->apellido;
    }

    public function logs()
    {
        return $this->hasMany('App\Models\UserLog','usuario_id');
    }

    public function profile()
    {
        return $this->belongsTo('App\Models\UserProfile','perfil_id');
    }
}
