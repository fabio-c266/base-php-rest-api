<?php

namespace App\Controllers;

use App\Models\UserModel;
use Exception;
use Firebase\JWT\JWT;

class AuthController
{
    public function store($req)
    {
        $body = $req['body'];

        if (empty($body->email) || empty($body->password)) {
            throw new Exception('Invalid body format.');
        }

        $userModel = new UserModel();
        $user = $userModel->findByEmail($body->email);

        if (!$user) {
            throw new Exception("Invalid credentials");
        }

        $isValidPassword = password_verify($body->password, $user['password']);

        if (!$isValidPassword) {
            throw new Exception("Invalid credentials");
        }

        $payload = [
            "exp" => time() * 3600 * 6, //6 Hours
            "data" => [
                "id" => $user['id'],
                "email" => $user['email']
            ]
        ];

        $token = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

        $data = [
            "status" => "200",
            "data" => [
                "token" => $token,
            ],
        ];

        return json_encode($data);
    }
}
