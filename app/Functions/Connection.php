<?php

if (!function_exists('connection')) {
    function connection(array $config = [])
    {
        if (is_array($config)) {
            return new \App\Core\DB($config['host'], $config['name'], $config['user'], $config['password']);
        } else {
            die('데이터베이스 연결 오류');
        }
    }
}

if (!function_exists('connectionPDO')) {
    function connectionPDO(array $config = [])
    {
        if (is_array($config)) {
            try {
                return new \PDO(
                    sprintf("mysql:host=%s;dbname=%s;port=%d;charset=%s", $config['host'], $config['name'], $config['port'], $config['charset']),
                    $config['user'],
                    $config['password']
                );
            } catch (\PDOException $e) {
                die($e->getMessage());
            }
        } else {
            die('데이터베이스 연결 오류');
        }
    }
}