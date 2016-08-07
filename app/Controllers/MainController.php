<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\User\User;
use App\Core\{ Hash, Input, Session, Redirect, Validator };

class MainController extends Controller
{
    public function getMain(array $request, array $response, bool $exists)
    {
        view('auth/main');
    }
}