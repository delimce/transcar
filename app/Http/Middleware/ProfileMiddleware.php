<?php
/**
 * Created by PhpStorm.
 * User: delimce
 * Date: 9/17/2018
 * Time: 9:09 PM
 */

namespace App\Http\Middleware;

use Closure;

class ProfileMiddleware
{

    protected $auth;


    public function __construct()
    {

    }

    public function handle($request, Closure $next, $allowed)
    {

       $privileges = explode('|', $allowed);
        $profile = $request->session()->get('myUser')->perfil_id; ///accesos del usuario

        if (!in_array($profile, $privileges)) { //if profile is allowed
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->route('app.login'); ///pagina de no permitido el acceso
            }
        }
        return $next($request);
    }

}