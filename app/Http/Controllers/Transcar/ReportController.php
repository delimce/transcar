<?php
/**
 * Created by PhpStorm.
 * User: delimce
 * Date: 9/17/2018
 * Time: 9:41 PM
 */

namespace App\Http\Controllers\Transcar;

use App\Models\User;
use App\Models\UserLog;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;


class ReportController extends BaseController
{

    private $user;
    private $currentdate;

    public function __construct(Request $req)
    {
        $this->middleware('profiles:1,3'); ///perfiles requeridos
        $myUser = $req->session()->get("myUser");
        if (!is_null($myUser))
            $this->user = User::findOrFail($myUser->id);
        $this->currentdate = Carbon::today();

    }

}