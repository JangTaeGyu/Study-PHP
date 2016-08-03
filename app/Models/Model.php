<?php

namespace App\Models;

use App\Core\Config;

class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = connection(Config::get('db/localhost'));
    }
}