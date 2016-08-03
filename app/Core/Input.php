<?php

namespace App\Core;

class Input
{
    public static function exists($type = 'POST')
    {
        switch ($type) {
            case 'POST':
                return (!empty($_POST)) ? true : false;
                break;

            case 'GET':
                return (!empty($_GET)) ? true : false;
                break;

            default:
                return false;
                break;
        }
    }

    public static function get($args = null)
    {
        if (is_null($args)) {
            return $_GET;
        } else {
            return self::getFetchFromValue(__FUNCTION__, $_GET, $args);
        }
    }

    public static function post($args = null)
    {
        if (is_null($args)) {
            return $_POST;
        } else {
            return self::getFetchFromValue(__FUNCTION__, $_POST, $args);
        }
    }

    public static function cookie($key = null)
    {
        if (is_null($key) === true) return $_COOKIE;

        return self::getFetchFromValue(__FUNCTION__, $_COOKIE, $key);
    }

    public static function server($key = null)
    {
        if (is_null($key) === true) return $_SERVER;

        return self::getFetchFromValue(__FUNCTION__, $_SERVER, $key);
    }

    private function getFetchFromValue($type = '', array $input = [], $dummy)
    {
        if (is_array($dummy)) {
            foreach ($dummy as $key => $value) {
                $dummy[$key] = array_key_exists($key, $input) && $input[$key] != '' ? $input[$key] : $value;
            }
        } else {
            switch ($type) {
                case 'get':
                    $dummy = filter_input(INPUT_GET, $dummy);
                    break;

                case 'post':
                    $dummy = filter_input(INPUT_POST, $dummy);
                    break;

                case 'cookie':
                    $dummy = filter_input(INPUT_COOKIE, $dummy);
                    break;

                case 'server':
                    $dummy = filter_input(INPUT_SERVER, $dummy);
                    break;

                default:
                    $dummy = false;
                    break;
            }
        }

        return $dummy;
    }
}