<?php

namespace App\Helpers;

class Menu
{
    public static function isActive($route)
    {
        $currentRoute = \Request::route()->getName();

        if (stristr($currentRoute, $route)) {
            return true;
        }

        return false;
    }
}