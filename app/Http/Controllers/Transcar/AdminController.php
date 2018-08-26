<?php

/**
 * Created by PhpStorm.
 * User: delimce
 * Date: 8/13/2018
 * Time: 12:01 AM
 */

namespace App\Http\Controllers\Transcar;


use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Validator;
use App\Models\User;
use App\Models\Config;
use App\Models\Area;
use App\Models\Role;
use App\Models\Table;
use App\Models\Line;
use App\Models\Person;
use App\Models\Bonus;


class AdminController extends BaseController
{

    private $user;

    public function __construct(Request $req)
    {
        $myUser = $req->session()->get("myUser");
        if (!is_null($myUser))
            $this->user = User::findOrFail($myUser->id);
    }


    public function index()
    {
        $users = User::where("id", "!=", $this->user->id)->with('profile')->get();
        $config = Config::first();
        return view('pages.system', ["users" => $users, "config" => $config]);
    }

    public function saveConfig(Request $req)
    {

        $config = Config::find(1); ///only reg
        $config->iva = $req->input('iva');
        $config->caja_paleta = $req->input('cajas');
        $config->save();
        return response()->json(['status' => 'ok', 'message' => 'Configuracion guardada con éxito']);
    }

    public function areaRoleIndex()
    {
        $areas = Area::all();
        $roles = Role::all();
        return view('pages.areaRole', ["areas" => $areas, "roles" => $roles]);
    }

    public function tableLineIndex()
    {
        $tables = Table::all();
        $lines = Line::all();
        return view('pages.tableLine', ["tables" => $tables, "lines" => $lines]);
    }

    public function personIndex()
    {
        $persons = Person::all();
        return view('pages.persons', ["persons" => $persons]);
    }

    public function bonusIndex()
    {
        $bonus = Bonus::all();
        return view('pages.bonus', ["bonus" => $bonus]);
    }

    /****************areas method***************** */


    public function getAreas()
    {

        $areas = Area::all();
        return response()->json(['status' => 'ok', 'list' => $areas->toArray()]);

    }

    public function getAreaById($area_id)
    {

        $area = Area::find($area_id);
        return response()->json(['status' => 'ok', 'area' => $area]);
    }

    public function createOrUpdateArea(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'nombre' => 'required|min:3',
            'descripcion' => 'required|min:3',
        ], ['required' => 'El campo :attribute es requerido',
            'min' => 'El campo :attribute debe ser mayor a :min',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        $area = new Area();
        if ($req->has('area_id')) {
            $area = Area::findOrFail($req->input('area_id'));
        }

        $area->nombre = $req->input('nombre');
        $area->descripcion = $req->input('descripcion');
        $area->save();

        return response()->json(['status' => 'ok', 'message' => 'Area guardada con éxito']);

    }

    public function deleteAreaById($area_id)
    {

        try {
            $area = Area::findOrFail($area_id);
            $areaTitle = $area->titulo;
            $area->delete();
            return response()->json(['status' => 'ok', 'message' => "Area: $areaTitle borrada con éxito"]);
        } catch (\PDOException $ex) {
            return response()->json(['status' => 'error', 'message' => 'Imposible eliminar, posee cargos asociados'], 500);
        }
    }


    /*************roles methods ********************/

    public function getRoles()
    {
        $roles = Role::all();
        $rolesArray = array();
        $roles->each(function ($item) use (&$rolesArray) {
            $rolesArray[] = array("id" => $item->id, "nombre" => $item->nombre, "descripcion" => $item->descripcion, "area" => $item->area->nombre);
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


    public function getRoleById($role_id)
    {

        $role = Role::find($role_id);
        return response()->json(['status' => 'ok', 'role' => $role]);
    }

    public function createOrUpdateRole(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'nombre' => 'required|min:3',
            'descripcion' => 'required|min:3',
            'sueldo' => 'required|numeric',
            'area' => 'required|numeric',
        ], ['required' => 'El campo :attribute es requerido',
            'min' => 'El campo :attribute debe ser mayor a :min',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        $role = new Role();
        if ($req->has('role_id')) {
            $role = Role::findOrFail($req->input('role_id'));
        }

        if ($req->has('asistencia')) {
            $role->asistencia = $req->input('asistencia');
        }

        if ($req->has('produccion')) {
            $role->produccion = $req->input('produccion');
        }

        if ($req->has('hora_extra')) {
            $role->hora_extra = $req->input('hora_extra');
        }

        $role->nombre = $req->input('nombre');
        $role->descripcion = $req->input('descripcion');
        $role->sueldo = $req->input('sueldo');
        $role->area_id = $req->input('area');
        $role->save();

        return response()->json(['status' => 'ok', 'message' => 'Cargo guardado con éxito']);

    }

    public function deleteRoleById($role_id)
    {
        $item = Role::findOrFail($role_id);
        $title = $item->nombre;
        $item->delete();
        return response()->json(['status' => 'ok', 'message' => "Cargo: $title borrado con éxito"]);

    }


    /*************table methods ********************/


    public function getTables()
    {

        $tables = Table::all();
        return response()->json(['status' => 'ok', 'list' => $tables]);

    }

    public function getTableById($table_id)
    {

        $table = Table::find($table_id);
        return response()->json(['status' => 'ok', 'table' => $table]);
    }

    public function createOrUpdateTable(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'titulo' => 'required|min:3',
            'ubicacion' => 'required|min:3',
        ], ['required' => 'El campo :attribute es requerido',
            'min' => 'El campo :attribute debe ser mayor a :min',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        $table = new Table();
        if ($req->has('table_id')) {
            $table = Table::findOrFail($req->input('table_id'));
        }

        $table->titulo = $req->input('titulo');
        $table->ubicacion = $req->input('ubicacion');
        $table->save();

        return response()->json(['status' => 'ok', 'message' => 'Mesa guardada con éxito']);

    }

    public function deleteTableById($table_id)
    {
        try {
            $item = Table::findOrFail($table_id);
            $title = $item->titulo;
            $item->delete();
            return response()->json(['status' => 'ok', 'message' => "Mesa: $title borrado con éxito"]);
        } catch (\PDOException $ex) {
            return response()->json(['status' => 'error', 'message' => 'Imposible eliminar, posee líneas asociadas'], 500);
        }
    }

    /****************lines method***************** */

    public function getLines()
    {
        $items = Line::all();
        $linesArray = array();
        $items->each(function ($item) use (&$linesArray) {
            $linesArray[] = array("id" => $item->id, "titulo" => $item->titulo, "descripcion" => $item->descripcion, "mesa" => $item->table->titulo);
        });

        return response()->json(['status' => 'ok', 'list' => $linesArray]);
    }

    public function getLineById($line_id)
    {
        $item = Line::find($line_id);
        return response()->json(['status' => 'ok', 'line' => $item]);
    }

    public function createOrUpdateLine(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'titulo' => 'required|min:3',
            'descripcion' => 'required|min:3',
            'mesa' => 'required|numeric',
        ], ['required' => 'El campo :attribute es requerido',
            'min' => 'El campo :attribute debe ser mayor a :min',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        $line = new Line();
        if ($req->has('line_id')) {
            $line = Line::findOrFail($req->input('line_id'));
        }

        $line->titulo = $req->input('titulo');
        $line->descripcion = $req->input('descripcion');
        $line->mesa_id = $req->input('mesa');
        $line->save();

        return response()->json(['status' => 'ok', 'message' => 'Linea guardada con éxito']);

    }

    public function deleteLineById($table_id)
    {
        $item = Line::findOrFail($table_id);
        $title = $item->titulo;
        $item->delete();
        return response()->json(['status' => 'ok', 'message' => "Linea: $title borrada con éxito"]);

    }


    /****************employee method***************** */

    public function getPersons()
    {
        $persons = Person::all();
        $personArray = array();
        $persons->each(function ($item) use (&$personArray) {
            $personArray[] = array("id" => $item->id, "nombre" => $item->nombre . ' ' . $item->apellido, "cedula" => $item->cedula, "ingreso" => $item->fecha_ingreso, "cargo" => $item->role->nombre);
        });
        return response()->json(['status' => 'ok', 'list' => $personArray]);
    }

    public function getPersonById($person_id)
    {

        $item = Person::find($person_id);
        return response()->json(['status' => 'ok', 'person' => $item]);
    }

    public function createOrUpdatePerson(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'nombre' => 'required|min:3',
            'apellido' => 'required|min:3',
            'area' => 'required|numeric',
            'cargo' => 'required|numeric',
            'cedula' => 'required|min:3',
            'sexo' => 'required',
            'fecha_nac' => 'required|date',
            'fecha_ingreso' => 'required|date',
        ], ['required' => 'El campo :attribute es requerido',
            'min' => 'El campo :attribute debe ser mayor a :min',
            'date' => 'El campo :attribute no es una fecha correcta',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        $person = new Person();
        if ($req->has('person_id')) {
            $person = Person::findOrFail($req->input('person_id'));
        }

        $person->nombre = $req->input('nombre');
        $person->apellido = $req->input('apellido');
        $person->cedula = $req->input('cedula');
        $person->fecha_nac = $req->input('fecha_nac');
        $person->fecha_ingreso = $req->input('fecha_ingreso');
        $person->sexo = $req->input('sexo');
        $person->cargo_id = $req->input('cargo');
        $person->area_id = $req->input('area');

        if ($req->has('email')) {
            $person->email = $req->input('email');
        }

        if ($req->has('telefono')) {
            $person->telefono = $req->input('telefono');
        }

        if ($req->has('account')) {
            $person->cuenta_bancaria = $req->input('account');
        }

        if ($req->has('titular')) {
            $person->titular = $req->input('titular');
        }

        $person->save();

        return response()->json(['status' => 'ok', 'message' => 'Empleado guardado con éxito']);

    }

    public function deletePersonById($table_id)
    {
        $item = Person::findOrFail($table_id);
        $title = $item->nombre;
        $item->delete();
        return response()->json(['status' => 'ok', 'message' => "Empleado: $title borrado con éxito"]);

    }

    /****************bonus method***************** */

    public function getBonus()
    {
        $bonus = Bonus::all();
        return response()->json(['status' => 'ok', 'list' => $bonus]);
    }

    public function getBonusById($bonus_id)
    {
        $bonus = Bonus::find($bonus_id);
        return response()->json(['status' => 'ok', 'bonus' => $bonus]);
    }

    public function createOrUpdateBonus(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'titulo' => 'required|min:3',
            'tipo' => 'required',
            'beneficiario' => 'required|numeric',
            'monto' => 'required|numeric',
            'fecha' => 'required|date',
        ], ['required' => 'El campo :attribute es requerido',
            'min' => 'El campo :attribute debe ser mayor a :min',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        $bonus = new Bonus();
        if ($req->has('bonus_id')) {
            $bonus = Bonus::findOrFail($req->input('bonus_id'));
        }

        $bonus->titulo = $req->input('titulo');
        $bonus->tipo = $req->input('tipo');
        $bonus->beneficiario = $req->input('beneficiario');
        $bonus->monto = $req->input('monto');
        $bonus->fecha = $req->input('fecha');
        $bonus->save();

        return response()->json(['status' => 'ok', 'message' => 'Bono guardado con éxito']);

    }

    public function deleteBonusById($bonus_id)
    {
        $item = Bonus::findOrFail($bonus_id);
        $title = $item->titulo;
        $item->delete();
        return response()->json(['status' => 'ok', 'message' => "Bono: $title borrado con éxito"]);

    }

}