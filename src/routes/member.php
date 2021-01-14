<?php

use Laravel\Lumen\Routing\Router;

/**@var Router $router*/
$router->get('/', function () use ($router) {
    dd(['member' => env('APP_ENV')]);
});

// Member-login
$router->post("/auth", ['uses' => 'AuthMemberController@login']);
$router->post("/logout", ['uses' => 'AuthMemberController@logout']);
$router->post("/refresh", ['uses' => 'AuthMemberController@refresh']);
$router->post("/me", ['uses' => 'AuthMemberController@me']);
