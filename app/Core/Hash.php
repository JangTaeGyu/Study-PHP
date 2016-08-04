<?php

namespace App\Core;

class Hash
{
    const COST = 12;

    public static function make($string, $salt = '')
    {
        return hash('sha256', $string . $salt);
    }

    public static function passwordHash($string, $salt = '')
    {
        return password_hash($string, PASSWORD_DEFAULT, [
            'cost' => self::COST,
            'salt' => $salt,
        ]);
    }

    public static function salt($length)
    {
        return utf8_encode(mcrypt_create_iv($length));
    }

    public static function unique($value='')
    {
        return self::make(uniqid());
    }
}