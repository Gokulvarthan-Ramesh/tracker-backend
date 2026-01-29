<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
|
*/

// Root route (app version)
$router->get('/', [
    'as' => 'app.version',
    function () use ($router) {
        return [
            'version' => $router->app->version(),
            'route_name' => app('request')->route()[1]['as'] ?? null,
        ];
    }
]);


// Versioned API routes (only register)
$router->group(['prefix' => 'api/v1'], function () use ($router) {

    // Auth registration route
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('/userInfo', 'UserController@info');
        $router->get('/study', 'StudyController@index');
        $router->post('/study', 'StudyController@store');       
        $router->put('/study/{id}', 'StudyController@update');   
        $router->delete('/study/{id}', 'StudyController@destroy'); 
        $router->post('/logout', 'AuthController@logout');
    });
});
