<?php

namespace App\Controllers;

use App\Core\Response;
use App\Models\UserModel;
use App\Utils\JwtUtils;
use Exception;

class AuthController
{
    public function authenticate($req)
    {
        $body = $req['body'];

        if (empty($body->email) || empty($body->password)) {
            throw new Exception('Invalid Body.', Response::HTTP_BAD_REQUEST);
        }

        $userModel = new UserModel();
        $user = $userModel->findByEmail($body->email);

        if (!$user) {
            throw new Exception("Invalid Credentials.", Response::HTTP_UNAUTHORIZED);
        }

        $isValidPassword = password_verify($body->password, $user['password']);

        if (!$isValidPassword) {
            throw new Exception("Invalid Credentials.", Response::HTTP_UNAUTHORIZED);
        }

        $data =  [
            "id" => $user['id'],
            "email" => $user['email']
        ];

        $token = JwtUtils::generate($data);
        $data = [
            "token" => $token,
        ];

        return Response::reponseJson($data, Response::HTTP_CREATED);
    }
}
