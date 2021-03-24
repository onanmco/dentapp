<?php

use core\Request;
use core\Router;
use core\Routes;

require __DIR__ . DIRECTORY_SEPARATOR . 'AutoLoader.php';

date_default_timezone_set('Europe/Istanbul');

error_reporting(E_ALL);
set_error_handler('\core\Error::errorHandler');
set_exception_handler('\core\Error::exceptionHandler');

session_start();

$routes = Routes::getRoutes();
$router = new Router();

foreach ($routes as $route) {
    isset($route['params']) ? $router->add($route['path'], $route['params']) 
        : $router->add($route['path']);
}

$router->dispatch(Request::uri());
