<?php

namespace App\Models\Member;

use App\Models\Model;

class Member extends Model
{
    use \App\Core\Traits\Crud;

    protected $database = 'php';

    protected $table = 'member';

    protected $primary = 'idx';

    public function getMemberSearch()
    {
        $sql = " SELECT * FROM {$this->table} ORDER BY {$this->primary} ASC ";

        $this->data = $this->db->getResults($sql);

        $this->count = $this->db->rowCount();

        return $this;
    }
}