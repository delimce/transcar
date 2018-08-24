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
            'titulo' => 'required|min:3',
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

        $area->titulo = $req->input('titulo');
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
        $item = Table::findOrFail($table_id);
        $title = $item->titulo;
        $item->delete();
        return response()->json(['status' => 'ok', 'message' => "Mesa: $title borrado con éxito"]);

    }


}