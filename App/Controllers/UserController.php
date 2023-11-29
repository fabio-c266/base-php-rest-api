<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Utils\StringHelper;
use Exception;

class UserController
{
    public function index($req)
    {
        $query = $req['query'];

        if (strlen($query) == 0 || !str_contains($query, 'id=')) {
            throw new Exception('Invalid query paramets.');
        }

        $queryParams = StringHelper::getQueryParamsFromUrl($query);
        $id = reset($queryParams);

        if (strlen($id) == 0) {
            throw new Exception('Id value is need.');
        }

        $userModel = new UserModel();
        $user = $userModel->findOne($id);

        if (!$user) {
            throw new Exception('Invalid user.');
        }

        return json_encode(
            [
                "status" => 200,
                "data" => $user,
            ]
        );
    }

    public function store($req)
    {
        $body = $req['body'];

        if (empty($body->email) || empty($body->password)) {
            throw new Exception('Invalid body format.');
        }

        $emailFormatted = strtolower(trim($body->email));

        $userModel = new UserModel();
        $isAlreadyRegisteredEmail = $userModel->findByEmail($emailFormatted);

        if ($isAlreadyRegisteredEmail) {
            throw new Exception('Not is allowed use this email.');
        }

        $passwordHashed = password_hash($body->password, PASSWORD_BCRYPT, ["cost" => 6]);

        $data = [
            "email" => $emailFormatted,
            "password" => $passwordHashed
        ];

        $userModel->create($data);

        unset($data['password']);

        $response = [
            "status" => 201,
            "data" => $data,
        ];

        return json_encode($response);
    }
}
