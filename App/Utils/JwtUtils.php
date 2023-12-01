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
            "exp" => time() * 3600 * 6, //6 Hours
            "data" => $data
        ];

        $token = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
        return $token;
    }

    public static function is_valid_token($token)
    {
        try {
            JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
            return true;
        } catch (Exception $error) {
            return false;
        }
    }
}
