<?php

namespace App\Utils;

use Ramsey\Uuid\Uuid as UuidUuid;

class UUID
{
    public static function generate()
    {
        $uuid = UuidUuid::uuid4();
        return $uuid->toString();
    }
}
