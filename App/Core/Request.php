<?php

namespace App\Core;

use App\Utils\JwtUtils;
use Exception;
use Throwable;
use App\Core\Routes as Routes;

require './App/routes.php';

class Request
{
    public static function handler($server)
    {
        $endpoint = $server['REQUEST_URI'];
        $httpMethod = $server['REQUEST_METHOD'];

        if ($endpoint[strlen($endpoint) - 1] === '/') {
            $endpoint = substr($endpoint, 0, strlen($endpoint) - 1);
        }

        $parsed_url = parse_url($endpoint);
        $endpoint = $parsed_url['path'];

        $route = Routes::get_route($httpMethod, $endpoint);

        if (!$route) {
            $data = [
                "messsage" => "Router not found"
            ];

            echo Response::reponseJson($data, Response::HTTP_NOT_FOUND);
            return;
        }

        try {
            if ($route->isNeedAuth) {
                $header = getallheaders();

                if (!isset($header['Authorization'])) {
                    throw new Exception("Is need Bearer token.", Response::HTTP_UNAUTHORIZED);
                }

                $token = explode(' ', $header['Authorization'])[1];

                if (!JwtUtils::is_valid_token($token)) {
                    return throw new Exception('Invalid token.', Response::HTTP_UNAUTHORIZED);
                }
            }

            $className = $route->controllerName;
            $class = "App\Controllers\\{$className}";
            $classInstance = new $class();

            if ($route->httpMethod === "POST") {
                $server['body'] = json_decode(file_get_contents('php://input'));
            }

            $server['query'] = $parsed_url['query'] ?? '';

            echo call_user_func([$classInstance, $route->controllerMethod], $server);
        } catch (Throwable $throwable) {
            $data = [
                "message" => $throwable->getMessage(),
            ];

            echo Response::reponseJson($data, $throwable->getCode());
        }
    }
}
