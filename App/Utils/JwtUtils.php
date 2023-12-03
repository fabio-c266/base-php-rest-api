<?php

namespace App\Utils;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtUtils
{
    public static function generate($data)
    {
        $payload = [
            "exp" => time() + 20, //6 Hours 3600 * 6
            "iat" => time(),
            "data" => $data
        ];

        $token = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
        return $token;
    }

    public static function is_valid_token($token)
    {
        try {
            $data = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
            return $data;
        } catch (Exception $error) {
            return false;
        }
    }
}
