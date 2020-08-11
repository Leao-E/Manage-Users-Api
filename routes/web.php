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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('login', 'AuthController@login');
    $router->post('refreshToken', 'AuthController@refreshToken');
    $router->post('checkToken', 'AuthController@checkToken');

    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->get('/getAll', 'UserController@getAllUsers');
        $router->get('/{id}/get', 'UserController@getUser');
        $router->get('/{id}/systems', 'UserController@getSystems');
        $router->get('/{id}/hirers', 'UserController@getHirers');
        $router->post('/create', 'UserController@newUser');
        $router->post('{id}/associateSystem', 'UserController@associateSystem');
        $router->put('/{id}/update', 'UserController@updateUser');
        $router->delete('/{id}/delete', 'UserController@deleteUser');
    });

    $router->group(['prefix' => 'hirer'], function ()  use ($router) {
        $router->get('/getAll', 'HirerController@getAllHirers');
        $router->get('/{id}/get', 'HirerController@getHirer');
        $router->get('/{id}/systems', 'HirerController@getSystems');
        $router->get('/{id}/users', 'HirerController@getUsers');
        $router->get('/{id}/self', 'HirerController@getSelf');
        $router->post('/create', 'HirerController@newHirer');
        $router->post('/{id}/associateSystem', 'HirerController@associateSystem');
        $router->post('/{id}/associateUser', 'HirerController@associateUser');
        $router->put('/{id}/update', 'HirerController@updateHirer');
        $router->delete('/{id}/delete', 'HirerController@deleteHirer');
    });

    $router->group(['prefix' => 'system'], function ()  use ($router) {
        $router->get('/getAll', 'SystemController@getAllSystems');
        $router->get('/{id}/get', 'SystemController@getSystem');
        $router->get('/{id}/hirers', 'SystemController@getHirers');
        $router->get('/{id}/users', 'SystemController@getUsers');;
        $router->post('/create', 'SystemController@newSystem');
        $router->post('/{id}/associateHirer', 'SystemController@associateHirer');
        $router->post('/{id}/associateUser', 'SystemController@associateUser');
        $router->put('/{id}/update', 'SystemController@updateSystem');
        $router->delete('/{id}/delete', 'SystemController@deleteSystem');
    });

});

