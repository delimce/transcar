<?php
/**
 * Created by PhpStorm.
 * User: delimce
 * Date: 8/13/2018
 * Time: 12:01 AM
 */

namespace App\Http\Controllers\Transcar;

use App\Models\User;
use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Validator;
use Laravel\Lumen\Routing\Controller as BaseController;

class UserController extends BaseController
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
        return view('pages.account', ['data' => $this->user]);
    }


    /**create user
     * @param Request $req
     * @return mixed
     */
    public function createOrUpdateUser(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'nombre' => 'required|min:3',
            'apellido' => 'required|min:3',
            'usuario' => 'required|min:5',
            'password' => 'required|min:5|same:password2',
            'password2' => 'required|min:5|same:password',
            'email' => 'required|email',
        ], ['required' => 'El campo :attribute es requerido',
            'email' => 'El campo :attribute debe ser un Email bien formado',
            'min' => 'El campo :attribute debe ser mayor a :min',
            'same' => 'los campos de clave tienen valores  diferentes',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        $user = new User();
        if ($req->has('user_id')) {
            $user = User::findOrFail($req->input('user_id'));
        }

        ///change password
        if ($req->input('password') != 'nop4sswordchang3d') {
            $user->password = Hash::make($req->input('password'));
        }

        $user->activo = ($req->has('activo')) ? 1 : 0;
        $user->perfil_id = $req->input('profile');
        $user->nombre = $req->input('nombre');
        $user->usuario = $req->input('usuario');
        $user->apellido = $req->input('apellido');
        $user->email = $req->input('email');
        $user->save();

        return response()->json(['status' => 'ok', 'message' => 'Usuario guardado con éxito']);

    }

    public function editUser(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'nombre' => 'required|min:3',
            'apellido' => 'min:3',
            'email' => 'required|email|unique:tbl_usuario,email,' . $this->user->id,
            'usuario' => 'required|min:4|unique:tbl_usuario,usuario,' . $this->user->id,
        ], ['required' => 'El campo :attribute es requerido',
            'email' => 'El campo :attribute debe ser un Email bien formado',
            'min' => 'El campo :attribute debe ser mayor a :min',
            'unique' => 'El valor del campo :attribute ya esta registrado',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        $this->user->nombre = $req->input('nombre');
        $this->user->usuario = $req->input('usuario');
        $this->user->email = $req->input('email');
        if ($req->has('apellido'))
            $this->user->apellido = $req->input('apellido');
        $this->user->save();

        $req->session()->get("myUser")->nombre = $req->input('nombre');

        return response()->json(['status' => 'ok', 'message' => 'datos guardados con éxito']);

    }

    public function getUserList()
    {
        $users = User::where("id", "!=", $this->user->id)->with('profile')->get();
        $userArray = array();
        $users->each(function ($item) use (&$userArray) {
            $userArray[] = array("id" => $item->id, "nombre" => $item->nombre, "apellido" => $item->apellido, "usuario" => $item->usuario, "perfil" => $item->profile->titulo);
        });

        return response()->json(['status' => 'ok', 'list' => $userArray]);
    }

    public function getUserById($user_id)
    {
        $user = User::findOrFail($user_id);
        return response()->json(['status' => 'ok', 'user' => $user]);
    }

    public function deleteUserById($user_id)
    {
        $user = User::findOrFail($user_id);
        $userName = $user->usuario;
        $user->delete();
        $this->saveActivity("Ha borrado la cuenta del usuario: $user->nombre $user->apellido ");
        return response()->json(['status' => 'ok', 'message' => "Usuario: $userName borrado con éxito"]);

    }

    public function changePassword(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'pass' => 'required|min:5|same:pass2',
            'pass2' => 'required|min:5|same:pass',
        ], ['required' => 'El campo :attribute es requerido',
            'min' => 'El campo :attribute debe ser mayor a :min',
            'same' => 'los campos de clave tienen valores  diferentes',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        $this->user->password = Hash::make($req->input('pass'));
        $this->user->save();
        return response()->json(['status' => 'ok', 'message' => 'clave cambiada con éxito']);

    }

    /**
     * saving user activity
     */
    public function saveActivity($activity)
    {
        # code...
        $log = array(
            "ip_acc"=>$_SERVER['REMOTE_ADDR'],
            "info_cliente"=>$_SERVER['HTTP_USER_AGENT'],
            "actividad"=>$activity,
        );
        $this->user->logs()->create($log);
    }


}