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
use Laravel\Lumen\Routing\Controller as BaseController;

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
        $users = User::where("id","!=",$this->user->id)->with('profile')->get();
        return view('pages.system', ['users' => $users]);
    }

}