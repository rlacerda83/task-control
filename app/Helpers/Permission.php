<?php

namespace App\Helpers;

use Auth;

class Permission
{
    public static function check($route)
    {
        return true;
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();
        $found = false;
        foreach ($user->profile->actions as $action) {
            if ($action->name == $route) {
                $found = true;
                break;
            }
        }
        return $found;
    }
}