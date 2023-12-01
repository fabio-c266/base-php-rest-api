<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Utils\JwtUtils;
use Exception;

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

        $data =  [
            "id" => $user['id'],
            "email" => $user['email']
        ];

        $token = JwtUtils::generate($data);
        $data = [
            "status" => "200",
            "data" => [
                "token" => $token,
            ],
        ];

        return json_encode($data);
    }
}
