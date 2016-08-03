<?php

if (!function_exists('addGlobal')) {
    function addGlobal(array $data = [], $type = 'array')
    {
        if (count($data) === 0) {
            die("글로벌 선언한 데이터를 확인해 주세요.");
        } else {
            foreach ($data as $key => $value) {
                $GLOBALS[$key] = $type === 'object' ? (object)$value : (array)$value;
            }
        }
    }
}