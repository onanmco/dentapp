<?php

use app\constant\Messages;
use app\model\Hasta;
use core\Request;
use core\Response;
use core\Router;

date_default_timezone_set('Europe/Istanbul');
require('../core/AutoLoader.php');

error_reporting(E_ALL);
set_error_handler('\core\Error::errorHandler');
set_exception_handler('\core\Error::exceptionHandler');

session_start();

$router = new Router();
$router->add('api/<controller>/<action>/<id:number>', ['namespace' => 'api']);
$router->add('api/<controller>/<action>', ['namespace' => 'api']);
$router->add('', ['controller' => 'home', 'action' => 'index']);
$router->add('login', ['controller' => 'login', 'action' => 'user']);
$router->add('<controller>/<action>');

$router->dispatch(Request::uri());

