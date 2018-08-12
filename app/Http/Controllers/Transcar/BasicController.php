<?php

namespace App\Http\Controllers\Transcar;

use App\Models\User;
use App\Models\UserLog;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class BasicController extends BaseController
{
    //
    public function index(Request $req)
    {

        if ($req->session()->has("myUser")) {
            return redirect()->route('app.home');
        } else {
            return redirect()->route('app.login');
        }

    }

    public function login()
    {
        return view('pages.login');
    }

    public function doLogin(Request $req)
    {
        $user = User::where('usuario', $req->input('user'))->first();
        if (is_null($user))
            return response()->json(['status' => 'error', 'message' => 'El usuario no existe'], 401);

        if (!$user->activo) {
            return response()->json(['status' => 'fail', 'message' => 'El usuario no esta activo'], 401);
        } else if (Hash::check($req->input('password'), $user->password)) {
            $log = new UserLog();
            $log->usuario_id = $user->id;
            $log->ip_acc = $_SERVER['REMOTE_ADDR'];
            $log->info_cliente = $_SERVER['HTTP_USER_AGENT'];
            $log->save();
            ///new login
            $req->session()->put('myUser', $user);
            return response()->json(['status' => 'ok', 'user' => $user]);
        } else {
            return response()->json(['status' => 'fail', 'message' => 'El password no es correcto'], 401);
        }

    }

}