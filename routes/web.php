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
/** @var \Laravel\Lumen\Routing\Router $router */

$router->group(['prefix' => 'api', 'middleware'=> 'removeExpiredToken'], function () use ($router) {
    $router->post('login', 'AuthController@login');
    $router->post('checkToken', 'AuthController@checkToken');


    $router->post('/register', 'UserController@registerUser');

    $router->group(['middleware' => 'auth'], function () use ($router){

        $router->post('refreshToken', 'AuthController@refreshToken');
        $router->post('logout', 'AuthController@logout');
        $router->post('self', 'AuthController@self');
        $router->post('canAccessSystem', 'AuthController@canAccessSystem');

        $router->group(['prefix' => 'user'], function () use ($router) {
            $router->group(['middleware' => 'sudoOnly'], function ()  use ($router) {
                $router->get('/getAll', 'UserController@getAllUsers');
            });

            $router->group(['middleware' => 'sudoOrHirer'], function () use ($router){
                $router->post('/search', 'UserController@search');
                $router->post('/create', 'UserController@newUser');
                $router->delete('/{id}/delete', 'UserController@deleteUser');
            });
            $router->get('/{id}/get', 'UserController@getUser');
            $router->get('/{id}/systems', 'UserController@getSystems');

            $router->get('/{id}/hirers', 'UserController@getHirers');
            $router->put('/{id}/update', 'UserController@updateUser');
        });

        $router->group(['prefix' => 'system', 'middleware' => 'sudoOnly'], function ()  use ($router) {
            $router->get('/getAll', 'SystemController@getAllSystems');
            $router->get('/{id}/get', 'SystemController@getSystem');
            $router->get('/{id}/hirers', 'SystemController@getHirers');
            $router->get('/{id}/users', 'SystemController@getUsers');
            $router->post('/create', 'SystemController@newSystem');
            $router->put('/{id}/update', 'SystemController@updateSystem');
            $router->delete('/{id}/delete', 'SystemController@deleteSystem');
        });

        $router->group(['prefix' => 'hirer'], function ()  use ($router) {
            $router->group(['middleware' => 'sudoOnly'], function ()  use ($router) {
                $router->get('/getAll', 'HirerController@getAllHirers');
                $router->post('/create', 'HirerController@newHirer');
                $router->put('/{id}/update', 'HirerController@updateHirer');
                $router->delete('/{id}/delete', 'HirerController@deleteHirer');
            });

            $router->group(['middleware' => 'sudoOrHirer'], function ()  use ($router) {
                $router->get('/{id}/get', 'HirerController@getHirer');
                $router->get('/{id}/systems', 'HirerController@getSystems');
                $router->get('/{id}/users', 'HirerController@getUsers');
                $router->post('/createRegKey', 'RegKeyController@createRegKey');
                $router->get('/{id}/getRegKeys', 'RegKeyController@getRegKeys');
                $router->delete('/{id}/deleteRegKey', 'RegKeyController@deleteRegKey');
            });

            $router->group(['middleware' => 'hirerOnly'], function () use ($router) {
                $router->get('/{id}/self', 'HirerController@getSelf');
            });
            //$router->get('/{id}/checkExpire','HirerController@checkExpire');
        });

        $router->group(['middleware' => 'sudoOnly'], function () use ($router){
            $router->post('/associate/hirerSystem', 'AssociateController@createHirerSystem');
            $router->post('/unassociate/hirerSystem', 'AssociateController@removeHirerSystem');
        });

        $router->group(['middleware' => 'sudoOrHirer'], function () use ($router) {
            $router->post('/associate/userHirerSystem', 'AssociateController@createUserHirerSystem');
            $router->post('/unassociate/userHirerSystem', 'AssociateController@removeUserHirerSystem');
        });

    });

});

