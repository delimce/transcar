<?php

namespace App\Http\Controllers\Transcar;

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

}