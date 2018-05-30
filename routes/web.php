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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('users/{id}', 'UserDetailsController@show');

$router->get('circuit-breakers', 'CircuitBreakerController@index');
$router->get('circuit-breakers/{name}', 'CircuitBreakerController@show');
$router->get('circuit-breakers/{name}/reset', 'CircuitBreakerController@reset');
