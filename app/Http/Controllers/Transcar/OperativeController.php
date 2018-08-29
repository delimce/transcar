<?php
/**
 * Created by PhpStorm.
 * User: delimce
 * Date: 8/13/2018
 * Time: 12:01 AM
 */

namespace App\Http\Controllers\Transcar;

use App\Models\Area;
use App\Models\User;
use App\Models\Person;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class OperativeController extends BaseController
{

    private $user;

    public function __construct(Request $req)
    {
        $myUser = $req->session()->get("myUser");
        if (!is_null($myUser))
            $this->user = User::findOrFail($myUser->id);
    }

    public function appearanceIndex()
    {
        $persons = Person::all();
        $areas = Area::all();
        return view('pages.appearance', ["persons" => $persons, "areas" => $areas]);
    }


    //************************* services *********************//

    public function getPersonsByEntity($entity, $value)
    {
        $persons = null;
        if ($value == "All") {
            $persons = Person::all();
        } else {
            if ($entity == "role") {
                $persons = Person::whereCargoId($value)->get();
            } else if ($entity == "area") {
                $persons = Person::whereHas('role', function ($query) use ($value) {
                    $query->whereAreaId($value);
                })->get();
            }
        }

        $personArray = array();
        $persons->each(function ($item) use (&$personArray) {
            $personArray[] = array("id" => $item->id, "nombre" => $item->nombre . ' ' . $item->apellido, "cedula" => $item->cedula, "ingreso" => $item->fecha_ingreso, "cargo" => $item->role->nombre);
        });

        return response()->json(['status' => 'ok', 'list' => $personArray]);

    }


}