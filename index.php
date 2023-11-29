<?php

namespace Application;

use App\Core\Routes;
use App\Config\Env;
use Dotenv\Dotenv;
use Exception;

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

Env::validate();

Routes::get('/auth/login', 'AuthController::index');
Routes::get('/users', 'UserController::index');

header('Content-Type: application/json');

if (isset($_REQUEST)) {
    $endpoint = $_SERVER['REQUEST_URI'];
    $httpMethod = $_SERVER['REQUEST_METHOD'];

    if ($endpoint[strlen($endpoint) - 1] === '/') {
        $endpoint = substr($endpoint, 0, strlen($endpoint) - 1);
    }

    $parsed_url = parse_url($endpoint);
    $endpoint = $parsed_url['path'];

    $route = Routes::get_route($httpMethod, $endpoint);

    if ($route) {
        try {
            $className = $route->controllerName;
            $class = "\App\Controllers\\{$className}";
            $classInstance = new $class();

            echo call_user_func([$classInstance, $route->controllerMethod], $_SERVER);
        } catch (Exception $except) {
            $data = [
                "status" => 400,
                "message" => $except->getMessage(),
            ];

            echo json_encode($data);
        }
    } else {
        http_response_code(404);
        $data = [
            "status" => 404,
            "messsage" => "Router not found"
        ];

        echo json_encode($data);
    }
}
