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
        $router->get('/system', 'AdminController@index');
        $router->get('/areaRole', 'AdminController@areaRoleIndex');
        $router->get('/tableLine', 'AdminController@tableLineIndex');
        $router->get('/people', 'AdminController@personIndex');

    });

});

/******SERVICES********/
$router->group(['prefix' => 'api', 'namespace' => 'Transcar'], function () use ($router) {
    $router->post('/doLogin', 'BasicController@doLogin');

    ////authenticated users
    $router->group(['middleware' => 'auth'], function () use ($router) {

        ///users
        $router->group(['prefix' => 'user'], function () use ($router) {
            $router->get('all/', 'UserController@getUserList');
            $router->get('/{user_id}', 'UserController@getUserById');
            $router->post('/', 'UserController@createOrUpdateUser');
            $router->put('/password', 'UserController@changePassword');
            $router->put('/edit', 'UserController@editUser');
            $router->delete('/{user_id}', 'UserController@deleteUserById');
        });

        ////config
        $router->group(['prefix' => 'config'], function () use ($router) {
            $router->put('/', 'AdminController@saveConfig');
        });

        ////areas
        $router->group(['prefix' => 'area'], function () use ($router) {
            $router->get('/all', 'AdminController@getAreas');
            $router->post('/', 'AdminController@createOrUpdateArea');
            $router->get('/{area_id}', 'AdminController@getAreaById');
            $router->delete('/{area_id}', 'AdminController@deleteAreaById');
        });

        ////roles
        $router->group(['prefix' => 'role'], function () use ($router) {
            $router->get('/all', 'AdminController@getRoles');
            $router->get('/all/{area_id}', 'AdminController@getRolesByArea');
            $router->post('/', 'AdminController@createOrUpdateRole');
            $router->get('/{role_id}', 'AdminController@getRoleById');
            $router->delete('/{role_id}', 'AdminController@deleteRoleById');
        });

        ////tables
        $router->group(['prefix' => 'table'], function () use ($router) {
            $router->get('/all', 'AdminController@getTables');
            $router->post('/', 'AdminController@createOrUpdateTable');
            $router->get('/{table_id}', 'AdminController@getTableById');
            $router->delete('/{table_id}', 'AdminController@deleteTableById');
        });

        ////lines
        $router->group(['prefix' => 'line'], function () use ($router) {
            $router->get('/all', 'AdminController@getLines');
            $router->post('/', 'AdminController@createOrUpdateLine');
            $router->get('/{line_id}', 'AdminController@getLineById');
            $router->delete('/{line_id}', 'AdminController@deleteLineById');
        });

        ///employees
        $router->group(['prefix' => 'person'], function () use ($router) {
            $router->get('/all', 'AdminController@getPersons');
            $router->post('/', 'AdminController@createOrUpdatePerson');
            $router->get('/{person_id}', 'AdminController@getPersonById');
            $router->delete('/{person_id}', 'AdminController@deletePersonById');
        });

    });

});
