<?php

namespace App\Utils;

class StringHelper
{
    public static function paramsStringQuery(array $params): string
    {
        $formattedParams = array_map(function ($value) {
            return "'" . addslashes($value) . "'";
        }, $params);

        return implode(', ', $formattedParams);
    }

    public static function getQueryParamsFromUrl(string $url)
    {
        parse_str($url, $arr);
        return $arr;
    }
}
