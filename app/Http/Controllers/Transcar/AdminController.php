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

    /****************areas method***************** */

    public function areaRoleIndex()
    {
        $areas = Area::all();
        $roles = Role::all();
        return view('pages.areaRole', ["areas" => $areas, "roles" => $roles]);
    }


    public function getAreas(){

        $areas = Area::all();
        return response()->json(['status' => 'ok', 'list' => $areas->toArray()]);

    }

    public function getAreaById($area_id){

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
        $area = Area::findOrFail($area_id);
        $areaTitle = $area->titulo;
        $area->delete();
        return response()->json(['status' => 'ok', 'message' => "Area: $areaTitle borrada con éxito"]);

    }


}