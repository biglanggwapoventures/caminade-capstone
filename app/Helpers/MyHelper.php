<?php

namespace App\Helpers;

class MyHelper
{

    public static function resource($action, $params = null, $returnAsRoute = true)
    {
        $fullRoute = explode('.', request()->route()->getName());
        array_pop($fullRoute);
        $resource = implode('.', $fullRoute);
        return $returnAsRoute ? route("{$resource}.{$action}", $params) : "{$resource}.{$action}";
    }

    public static function route($action, $params = [])
    {
        $fullRoute = explode('.', request()->route()->getName());
        array_pop($fullRoute);
        $resource = implode('.', $fullRoute);
        return route("{$resource}.{$action}", $params);
    }

    public static function replaceBrackets($str, $with = '')
    {
        return rtrim(str_replace("[", ".", str_replace("][", ".", $str)), "]");
    }

}
