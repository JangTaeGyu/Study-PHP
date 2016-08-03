<?php

namespace App\Core;

use App\Core\Config;
use App\Core\Session;

class Csrf
{
    public static function generate()
    {
        return Session::put(Config::get('csrf/token_name'), md5(uniqid()));
    }

    public static function check($token)
    {
        $tokenName = Config::get('csrf/token_name');

        if (Session::exists($tokenName) && $token === Session::get($tokenName)) {
            Session::delete($tokenName);

            return true;
        }

        return false;
    }
}