<?php

namespace App\Controllers;

use App\Core\Response;
use App\Models\UserModel;
use App\Utils\StringFormatter;
use Exception;

class UserController
{
    public function get($req)
    {
        $jwtData = $req['jwt_data'];
        $user_id = $jwtData->data->id;

        $userModel = new UserModel();
        $user = $userModel->findOne($user_id);

        if (!$user) {
            throw new Exception('User Not Found.', Response::HTTP_BAD_REQUEST);
        }

        return Response::reponseJson($user);
    }

    public function create($req)
    {
        $body = $req['body'];

        if (empty($body->email) || empty($body->password)) {
            throw new Exception('Invalid Body.', Response::HTTP_BAD_REQUEST);
        }

        $emailFormatted = strtolower(trim($body->email));

        $userModel = new UserModel();
        $isAlreadyRegisteredEmail = $userModel->findByEmail($emailFormatted);

        if ($isAlreadyRegisteredEmail) {
            throw new Exception('There is already a user with this email', Response::HTTP_BAD_REQUEST);
        }

        $passwordHashed = password_hash($body->password, PASSWORD_BCRYPT, ["cost" => 6]);

        $data = [
            "email" => $emailFormatted,
            "password" => $passwordHashed
        ];

        $userModel->create($data);

        unset($data['password']);

        return Response::reponseJson($data, RESPONSE::HTTP_CREATED);
    }
}
