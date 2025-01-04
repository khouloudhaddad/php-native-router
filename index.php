<?php
require_once 'Router.php';
require_once 'controllers/HomeController.php';
require_once 'controllers/UserController.php';

// Initialize the router
$router = new Router();

// Define routes
$router->add('GET', '/', [HomeController::class, 'index']);
$router->add('GET', '/user/{id}', [UserController::class, 'show']);
$router->add('POST', '/user', [UserController::class, 'create']);

// Dispatch the request
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

$router->dispatch($requestUri, $requestMethod);