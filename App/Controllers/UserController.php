<?php

namespace App\Controllers;

use App\Core\Response;
use App\Models\UserModel;
use App\Utils\CompareArray;
use App\Utils\UUID;
use Exception;

use function PHPSTORM_META\type;

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
        $bodySchema = [
            "name" => 'string',
            "email" => 'string',
            "phone" => 'string',
            "password" => 'string'
        ];

        if (!is_object($body) || !CompareArray::compareKeysAndValues(data: get_object_vars($body), schema: $bodySchema)) {
            throw new Exception('Invalid Body.', Response::HTTP_BAD_REQUEST);
        }

        $name = $body->name;
        $emailFormatted = strtolower(trim($body->email));

        $userModel = new UserModel();
        $isAlreadyRegisteredEmail = $userModel->findByEmail($emailFormatted);

        if ($isAlreadyRegisteredEmail) {
            throw new Exception('There is already a user with this email', Response::HTTP_BAD_REQUEST);
        }

        $phone = $body->phone;
        $passwordHashed = password_hash($body->password, PASSWORD_BCRYPT, ["cost" => 6]);

        $data = [
            "id" => UUID::generate(),
            "name" => $name,
            "email" => $emailFormatted,
            "password" => $passwordHashed,
            "phone" => $phone,
        ];

        $userModel->create($data);

        unset($data['password']);

        return Response::reponseJson($data, RESPONSE::HTTP_CREATED);
    }
}
