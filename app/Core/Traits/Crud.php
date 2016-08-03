<?php

namespace App\Core\Traits;

trait Crud
{
    private static $instance = null;

    protected $data;

    protected $count;

    public static function getInstance()
    {
        if (is_null(self::$instance)) self::$instance = new self;

        return self::$instance;
    }

    public function count()
    {
        return $this->count;
    }

    public function data()
    {
        return $this->data;
    }

    public function first()
    {
        return $this->data[0];
    }

    public function create(array $args = [])
    {
        $this->check();

        return (boolean)$this->db->insert($this->database, $this->table, $args);
    }

    public function read($value = '')
    {
        $this->check($value);

        $sql = sprintf("SELECT * FROM {$this->table} WHERE {$this->primary} = '%s' ", $value);

        $this->data = $this->db->getResults($sql);

        $this->count = $this->db->rowCount();

        return $this;
    }

    public function update($value = '', array $args = [])
    {
        $this->check($value);

        return (boolean)$this->db->update($this->database, $this->table, $args, sprintf(" {$this->primary} = '%s' ", $value));
    }

    public function delete($value = '')
    {
        $this->check($value);

        return (boolean)$this->db->delete($this->database, $this->table, sprintf(" {$this->primary} = '%s' ", $value));
    }

    private function check($value = null)
    {
        if (is_null($this->database) || $this->database === '') die("database 를 지정해 주세요.");
        if (is_null($this->table) || $this->table === '') die("table 를 지정해 주세요.");
        if (is_null($this->primary) || $this->primary === '') die("primary 를 지정해 주세요.");
        if (!is_null($value)) {
            if ($value === '') die("value 값이 없습니다.");
        }
    }
}