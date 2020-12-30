<?php

use Laravel\Lumen\Routing\Router;

/**
 * @var $router Router
 */
$router->get('/', function () use ($router) {
    return $router->app->version();
});
