<?php
namespace AtomV2\Dao;

class CategoryExtendDao extends BaseDao
{
    private $tableName = "category_extend";
    private $voClassName = "CategoryExtendVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getCatExtDefaultWithKeyList($where = [], $option = [])
    {
        $this->db->from('category AS c');
        $this->db->join('language AS l', '1=1', 'INNER');
        $this->db->join('category_extend AS ce', 'c.id = ce.cat_id AND ce.lang_id = l.lang_id', 'LEFT');

        return $this->commonGetList($where, $option, $this->getVoClassname(), 'c.id AS cat_id, l.lang_id, COALESCE(ce.name, c.name) AS name');
    }
}
