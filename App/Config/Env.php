<?php

namespace App\Config;

class Env
{
    public static function validate()
    {
        $envFormat = [
            "DB_HOST",
            "DB_USER",
            "DB_PASSWORD",
            "DB_NAME",
            "JWT_SECRET"
        ];

        if (!empty(array_diff_key(array_flip($envFormat), $_ENV))) {
            trigger_error("Invalid environment variables", E_USER_ERROR);
            exit();
        }
    }
}
