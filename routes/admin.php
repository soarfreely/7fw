<?php

use Laravel\Lumen\Routing\Router;

/**@var Router $router*/
$router->get('/', function () use ($router) {
    dd(['admin' => env('APP_ENV')]);
});

// Admin-login

$router->post("/auth", ['uses' => 'AuthAdminController@login']);
$router->post("/logout", ['uses' => 'AuthAdminController@logout']);
$router->post("/refresh", ['uses' => 'AuthAdminController@refresh']);
$router->post("/me", ['uses' => 'AuthAdminController@me']);




$router->get("/synonym", ['uses' => 'SynonymController@index']);

