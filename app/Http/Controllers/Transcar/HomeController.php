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
use Laravel\Lumen\Routing\Controller as BaseController;

class HomeController extends BaseController
{

    private $user;

    public function __construct(Request $req)
    {
        $myUser = $req->session()->get("myUser");
        if (!is_null($myUser))
            $this->user = User::findOrFail($myUser->id);
    }

    public function logout(Request $req)
    {
        $req->session()->flush();
        return redirect()->route('app.login');
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

}