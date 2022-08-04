<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('foo', function () {
    return 'Hello World';
});

$router->get('ping', 'ExampleController@ping');
$router->post('/auth/login', 'AuthController@login');
$router->get('/api/statistik', 'ApiController@statistik');
$router->get('/api/suratmasuk/suratmasuk-dan-disposisi', 'ApiController@smdisposisi');