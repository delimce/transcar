<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/******VIEWS********/
$router->group(['namespace' => 'Transcar'], function () use ($router) {
    $router->get('/', ['as' => 'app.in', 'uses' => 'BasicController@index']);
    $router->get('/login', ['as' => 'app.login', 'uses' => 'BasicController@login']);

    ///only authenticated users
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('/home', ['as' => 'app.home', 'uses' => 'BasicController@home']);
        $router->get('/logout', 'HomeController@logout');
        $router->get('/account', 'UserController@index');

    });

});

/******SERVICES********/
$router->group(['prefix' => 'api', 'namespace' => 'Transcar'], function () use ($router) {
    $router->post('/doLogin', 'BasicController@doLogin');
    ///user
    $router->group(['prefix' => 'user', 'middleware' => 'auth'], function () use ($router) {
        $router->post('/', 'UserController@createUser');
        $router->put('/password', 'UserController@changePassword');
        $router->put('/edit', 'UserController@editUser');
    });

});
