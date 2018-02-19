<?php

namespace App\Helpers;

use DateInterval;
use DatePeriod;

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

    public static function timeInterval($start, $end)
    {
        $period = new DatePeriod($start, new DateInterval('PT0H30M'), $end);

        $result = [];

        foreach ($period as $dt) {
            $result[$dt->format('H:i:s')] = $dt->format("h:i A");
        }

        return $result;
    }

}
