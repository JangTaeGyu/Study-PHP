<?php

namespace App\Core;

class Config
{
    public static function get($path = null)
    {
        if (!is_null($path)) {
            $config = $GLOBALS['config'];

            $path = explode('/', $path);

            foreach ($path as $value) {
                if (isset($value)) {
                    $config = $config[$value];
                }
            }

            return $config;
        }

        return false;
    }
}