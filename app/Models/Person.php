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
        return $this->belongsTo('App\Models\Role', 'cargo_id');
    }


    public function table()
    {
        return $this->belongsTo('App\Models\Table', 'mesa_id');
    }


    public function bank()
    {
        return $this->belongsTo('App\Models\Bank', 'banco_id');
    }


    public function line()
    {
        return $this->belongsTo('App\Models\Line', 'linea_id');
    }


    public function appear()
    {
        return $this->hasMany('App\Models\Appearance', 'empleado_id');
    }


    public function fullInfo()
    {
        return $this->nombre . ' ' . $this->apellido . ', cod:' . $this->codigo;
    }


    /**person location word
     * @return string
     */
    public function location()
    {
        $location = 'N/A';
        if (!empty($this->mesa_id)) {
            $table    = Table::find($this->mesa_id);
            $location = $table->titulo;
            if (!empty($this->linea_id)) {
                $line     = Line::find($this->linea_id);
                $location .= ', ' . $line->titulo;
            }
        }
        return $location;
    }


    protected $dates = ['deleted_at'];

}
