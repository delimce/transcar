<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Area;
use App\Models\Role;
use App\Models\Line;
use App\Models\Person;

class Bonus extends Model
{
    protected $table = 'tbl_bonificacion';

    public function getDetail()
    {
        $detail = '';
        $item = '';

        switch ($this->tipo) {
            case "area":
                $detail = Area::find($this->beneficiario);
                $item = $detail->nombre;
                break;
            case "cargo":
                $detail = Role::find($this->beneficiario);
                $item = $detail->nombre;
                break;
            case "empleado":
                $detail = Person::find($this->beneficiario);
                $item = $detail->nombre . ' ' . $detail->apellido;
                break;
        }

        return $item;

    }



}
