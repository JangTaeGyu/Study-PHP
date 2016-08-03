<?php

namespace App\Models\Task;

use App\Models\Model;

class Task extends Model
{
    use \App\Core\Traits\Crud;

    protected $database = 'php';

    protected $table = 'task';

    protected $primary = 'idx';

    public function getTaskSearch(array $args = [], bool $complete = false)
    {
        $sql = " SELECT *, (SELECT name FROM member where idx = member_idx) AS name FROM {$this->table} WHERE 1 = 1 ";
        if ($args['target'] != '') $sql .= sprintf(" AND target = '%s' ", $args['target']);
        if ($args['kind'] != '') $sql .= sprintf(" AND kind = '%s' ", $args['kind']);
        if ($args['title'] != '') $sql .= sprintf(" AND title LIKE '%%%s%%' ", $args['title']);
        if ($args['member_idx'] != '') $sql .= sprintf(" AND member_idx = '%s' ", $args['member_idx']);
        if (in_array($args['state'], ['W', 'S', 'N'])) $sql .= sprintf(" AND state = '%s' ", $args['state']);
        if ($complete) {
            if ($args['sdate'] != '' && $args['edate'] != '') $sql .= sprintf(" AND complete_date BETWEEN '%s' AND '%s' ", $args['sdate'], $args['edate']);
        } else {
            if ($args['sdate'] != '' && $args['edate'] != '') $sql .= sprintf(" AND date BETWEEN '%s 00:00:00' AND '%s 23:59:59' ", $args['sdate'], $args['edate']);
        }
        $sql .= " ORDER BY date DESC ";

        $this->data = $this->db->getResults($sql);

        $this->count = $this->db->rowCount();

        return $this;
    }

    public function getTaskIssue()
    {
        $sql = " SELECT *, (SELECT name FROM member where idx = member_idx) AS name FROM {$this->table} WHERE issue = 'Y' ORDER BY date DESC ";

        $this->data = $this->db->getResults($sql);

        $this->count = $this->db->rowCount();

        return $this;
    }
}