<?php

namespace App\Utils;

class CompareArray
{
    public static function compareKeys(array $schema, ?array $data = [])
    {
        return empty(array_diff_key(array_flip($schema), $data)) ? true : false;
    }

    public static function compareKeysAndValues(array $schema, ?array $data = [])
    {
        foreach ($schema as $key => $type) {
            if (!array_key_exists($key, $data)) {
                return false;
            }

            if (empty($data[$key]) || gettype($data[$key]) !== $type) {
                return false;
            }
        }

        return true;
    }
}
