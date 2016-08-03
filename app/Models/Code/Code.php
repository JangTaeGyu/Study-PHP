<?php

namespace App\Models\Code;

use App\Models\Model;

class Code extends Model
{
    use \App\Core\Traits\Crud;

    protected $database = 'php';

    protected $table = 'code';

    protected $primary = 'idx';

    /**
     * [getMainSearch 메인코드 찾기]
     * @param  string $main 코드
     * @return object
     */
    public function getMainSearch(string $main = '')
    {
        $sql = sprintf(" SELECT * FROM {$this->table} WHERE main = '%s' ORDER BY idx ASC ", $main);

        $this->data = $this->db->getResults($sql);

        $this->count = $this->db->rowCount();

        return $this;
    }

    /**
     * [getSubSearch 서브코드 찾기]
     * @param  string $sub 코드
     * @return object
     */
    public function getSubSearch(string $main = '', string $sub = '')
    {
        $sql = sprintf(" SELECT * FROM {$this->table} WHERE sub = '%s' ", $sub);
        if ($main != '') $sql .= sprintf(" AND main = '%s' ", $main);
        $sql .= " ORDER BY idx ASC ";

        $this->data = $this->db->getResults($sql);

        $this->count = $this->db->rowCount();

        return $this;
    }

    public function getMultiMainSearch(array $main = [])
    {
        $sql = sprintf(" SELECT * FROM {$this->table} WHERE main IN ('%s') ORDER BY main ASC ", implode("','", $main));
        $data = $this->db->getResults($sql);

        $temp = [];
        foreach ($main as $code) {
            if (array_key_exists($code, $temp) === false) $temp[$code] = [];

            foreach ($data as $value) {
                if ($code === $value['main']) {
                    $temp[$code] = array_merge($temp[$code], [$value['sub'] => $value['name']]);
                }
            }
        }

        return $temp;
    }
}