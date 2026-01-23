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
$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Versioned API routes (only register)
$router->group(['prefix' => 'api/v1'], function () use ($router) {

    // Auth registration route
    $router->post('/register', 'AuthController@register');

});
