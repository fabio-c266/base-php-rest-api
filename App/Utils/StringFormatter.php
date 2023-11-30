<?php

namespace App\Utils;

class StringFormatter
{
    public static function ArrayToQueryValues(array $params): string
    {
        $formattedParams = array_map(function ($value) {
            return "'" . addslashes($value) . "'";
        }, $params);

        return implode(', ', $formattedParams);
    }

    public static function getQueryParams(string $url)
    {
        parse_str($url, $arr);
        return $arr;
    }
}
