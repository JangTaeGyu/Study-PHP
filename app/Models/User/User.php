<?php

namespace App\Models\User;

use App\Models\Model;

class User extends Model
{
    use \App\Core\Traits\Crud;

    protected $database = 'php';

    protected $table = 'user';

    protected $primary = 'id';
}