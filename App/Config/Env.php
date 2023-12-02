<?php

namespace App\Config;

use App\Utils\CompareArray;

class Env
{
    public static function validate()
    {
        $envSchema = [
            "DB_HOST",
            "DB_USER",
            "DB_PASSWORD",
            "DB_NAME",
            "JWT_SECRET"
        ];

        $isValid = CompareArray::compareKeys(data: $_ENV, schema: $envSchema);

        if (!$isValid) {
            trigger_error("Invalid environment variables", E_USER_ERROR);
            exit();
        }
    }
}
