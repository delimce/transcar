<?php
/**
 * Created by PhpStorm.
 * User: delimce
 * Date: 8/13/2018
 * Time: 12:01 AM
 */

namespace App\Http\Controllers\Transcar;

use App\Models\User;
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
    public function createUser(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'nombre' => 'required|min:8',
            'usuario' => 'required|min:5',
            'password' => 'required|min:5',
            'email' => 'required|email',
        ], ['required' => 'El campo :attribute es requerido',
            'email' => 'El campo :attribute debe ser un Email bien formado',
            'min' => 'El campo :attribute debe ser mayor a :min',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        $user = new User();
        $user->nombre = $req->input('nombre');
        $user->usuario = $req->input('usuario');
        $user->email = $req->input('email');
        $user->password = Hash::make($req->input('password'));
        $user->save();

        return response()->json(['status' => 'ok', 'message' => 'Usuario creado con éxito']);

    }


    public function changePassword(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'pass' => 'required|min:5',
            'pass2' => 'required|min:5'
        ], ['required' => 'El campo :attribute es requerido',
            'min' => 'El campo :attribute debe ser mayor a :min',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        if ($req->input('pass') === $req->input('pass2')) {
            $this->user->password = Hash::make($req->input('pass'));
            $this->user->save();
            return response()->json(['status' => 'ok', 'message' => 'clave cambiada con éxito']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'los campos de clave tienen valores  diferentes'], 400);
        }


    }


}