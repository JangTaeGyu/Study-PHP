<?php

namespace App\Core;

class Redirect
{
    public static function to($location = null)
    {
        if (!is_null($location)) {
            if (is_numeric($location)) {
                switch ($location) {
                    case 404:
                        header('HTTP/1.0 404 Not Found');
                        include 'errors/404.php';
                        exit;
                    break;
                }
            } else {
                header('Location: '. $location);
                exit;
            }
        }
    }
}