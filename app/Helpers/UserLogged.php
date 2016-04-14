<?php

namespace App\Helpers;

use Auth;

class UserLogged
{
    /**
     * @return mixed
     */
    public static function get()
    {
        return Auth::user();
    }

    /**
     * @return bool
     */
    public static function getId()
    {
        $user = Auth::user();
        if ($user) {
            return $user->id;
        }

        return false;
    }
}