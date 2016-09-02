<?php

namespace App\Controllers;

use App\Models\User\UserSessions;

class Controller
{
    protected $container;

    protected $session;

    public function __construct()
    {
        $this->container = (object)$GLOBALS['container'];

        // 세션
        $session = UserSessions::getInstance()->getSessionLimit();
        if ($session->count() > 0) {
            $this->session = $session->first();
        }
    }

    public function __get($property)
    {
        if ($this->container->{$property}) {
            return $this->container->{$property};
        }
    }
}