<?php

if (!function_exists('view')) {
    function view($path, array $data = [])
    {
        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $$key = $value;
            }
        }

        include_once PATH_ROOT_VIEWS . '/' . $path . '.php';
    }
}
