<?php
/**
 * Created by PhpStorm.
 * User: delimce
 * Date: 9/17/2018
 * Time: 10:51 PM
 */

namespace App\Http\Controllers\Transcar;

use App\Models\Appearance;
use App\Models\User;
use App\Models\Area;
use App\Models\Role;
use App\Models\Table;
use App\Models\Line;
use App\Models\Person;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class QueryController extends BaseController
{

    private $user;
    private $currentdate;

    public function __construct(Request $req)
    {
        $myUser = $req->session()->get("myUser");
        if (!is_null($myUser))
            $this->user = User::findOrFail($myUser->id);
        $this->currentdate = Carbon::today();

    }


    public function getAreas()
    {

        $areas = Area::all();
        return response()->json(['status' => 'ok', 'list' => $areas->toArray()]);

    }


    public function getRoles()
    {
        $roles = Role::all();
        $rolesArray = array();
        $roles->each(function ($item) use (&$rolesArray) {
            $rolesArray[] = array("id" => $item->id, "nombre" => $item->nombre, "descripcion" => $item->descripcion, "area" => $item->area->nombre, "produccion" => $item->produccion_tipo);
        });
        return response()->json(['status' => 'ok', 'list' => $rolesArray]);
    }

    public function getRolesByArea($area_id)
    {
        $roles = Role::where("area_id", $area_id)->get();
        $rolesArray = array();
        $roles->each(function ($item) use (&$rolesArray) {
            $rolesArray[] = array("id" => $item->id, "nombre" => $item->nombre, "descripcion" => $item->descripcion, "area" => $item->area->titulo);
        });
        return response()->json(['status' => 'ok', 'list' => $rolesArray]);
    }


    public function getTables()
    {

        $tables = Table::whereActivo(1)->get();
        return response()->json(['status' => 'ok', 'list' => $tables]);

    }


    public function getLines()
    {
        $items = Line::all();
        $linesArray = array();
        $items->each(function ($item) use (&$linesArray) {
            $linesArray[] = array("id" => $item->id, "titulo" => $item->titulo, "descripcion" => $item->descripcion, "mesa" => $item->table->titulo);
        });

        return response()->json(['status' => 'ok', 'list' => $linesArray]);
    }


    public function getLinesByTable($table_id)
    {
        $lines = Line::where("mesa_id", $table_id)->get();
        $linesArray = array();
        $lines->each(function ($item) use (&$linesArray) {
            $linesArray[] = array("id" => $item->id, "titulo" => $item->titulo, "descripcion" => $item->descripcion, "mesa" => $item->table->titulo);
        });
        return response()->json(['status' => 'ok', 'list' => $linesArray]);
    }


    public function getPersons()
    {
        $persons = Person::all();
        $personArray = array();
        $persons->each(function ($item) use (&$personArray) {
            $personArray[] = array("id" => $item->id, "nombre" => $item->nombre . ' ' . $item->apellido, "cedula" => $item->cedula, "ingreso" => $item->fecha_ingreso, "cargo" => $item->role->nombre);
        });
        return response()->json(['status' => 'ok', 'list' => $personArray]);
    }


    public function getAppearDetail(Request $req)
    {
        $detail = Appearance::whereEmpleadoId($req->input('person'))
            ->whereFecha($req->input('date'))->with('person', 'table', 'line')->first();
        if ($detail->count() > 0) {
            return response()->json(['status' => 'ok', 'info' => $detail]);
        } else {
            return response()->json(['status' => 'error', 'message' => 404], 401);
        }

    }

    public function getDaysOfMonth($month)
    {
        $now = Carbon::now();
        $days = Carbon::parse($now->format("Y-$month"))->daysInMonth;
        return response()->json(['status' => 'ok', 'days' => $days]);
    }

}