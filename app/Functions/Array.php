<?php

if (!function_exists('elm')) {
    function elm($array, $item, $default = null)
    {
        if (is_array($array)) {
            return array_key_exists($item, $array) ? $array[$item] : $default;
        }

        return '';
    }
}

if (!function_exists('elms')) {
    function elms($input, array $data = [])
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = array_key_exists($key, $input) && $input[$key] != '' ? $input[$key] : $value;
            }
        }

        return $data;
    }
}


