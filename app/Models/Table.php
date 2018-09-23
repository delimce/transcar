<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $table = 'tbl_mesa';

    public function lines()
    {
        return $this->hasMany('App\Models\Line', 'mesa_id');
    }

    /**chief of table
     * @return mixed
     */
    public function person()
    {
        return $this->hasMany('App\Models\Person', 'empleado_id');
    }

    public function showLineNames()
    {
        $lines = $this->lines()->get();
        $names = array();
        $lines->each(function ($item) use(&$names) {
             $names[] = $item->titulo;
        });
        return implode(" / ", $names);
    }


}
