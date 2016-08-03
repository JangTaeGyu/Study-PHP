<?php

namespace App\Models\User;

use App\Models\Model;

class UserSessions extends Model
{
    use \App\Core\Traits\Crud;

    protected $database = 'php';

    protected $table = 'user_sessions';

    protected $primary = 'session_id';

    public function getSessionLimit()
    {
    	$sql = sprintf(" SELECT * FROM {$this->table} WHERE {$this->primary} = '%s' AND session_expires >= %d ", session_id(), time());

        $this->data = $this->db->getResults($sql);

        $this->count = $this->db->rowCount();

        return $this;
    }
}