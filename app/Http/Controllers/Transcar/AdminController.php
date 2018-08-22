<?php

/**
 * Created by PhpStorm.
 * User: delimce
 * Date: 8/13/2018
 * Time: 12:01 AM
 */

namespace App\Http\Controllers\Transcar;

use App\Models\User;
use App\Models\Config;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
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

    public function areaRoleIndex()
    {
        $areas = Area::all();
        $roles = Role::all();
        return view('pages.areaRole', ["areas" => $areas, "roles" => $roles]);
    }


}