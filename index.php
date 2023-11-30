<?php

namespace Application;

use App\Config\Database;
use App\Core\Routes;
use App\Config\Env;
use Dotenv\Dotenv;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

Env::validate();
Database::connect();

Routes::post('/auth/login', 'AuthController::store');
Routes::get('/users', 'UserController::index');
Routes::post('/users', 'UserController::store');
Routes::get('/courses', 'CourseController::index', true);

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
            if ($route->isNeedAuth) {
                $header = getallheaders();

                if (!isset($header['Authorization'])) {
                    throw new Exception("Is need Bearer token.");
                }

                $token = explode(' ', $header['Authorization'])[1];

                try {
                    $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
                    $_SERVER['jwt_data'] = $decoded->data;
                } catch (Exception $error) {
                    throw new Exception('Invalid token.');
                }
            }

            $className = $route->controllerName;
            $class = "\App\Controllers\\{$className}";
            $classInstance = new $class();

            if ($route->httpMethod === "POST") {
                $_SERVER['body'] = json_decode(file_get_contents('php://input'));
            }

            $_SERVER['query'] = $parsed_url['query'] ?? '';

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
