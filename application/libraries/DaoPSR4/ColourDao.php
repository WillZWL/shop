<?php
namespace AtomV2\Dao;

class ColourDao extends BaseDao
{
    private $table_name = 'colour';
    private $vo_class_name = 'ColourVo';

    public function getVoClassname()
    {
        return $this->vo_class_name;
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    public function getListWithLang($where = [], $option = [], $className = 'ColourWithLangDto')
    {
        $this->db->from('colour c');
        $this->db->join('colour_extend ce', 'c.colour_id = ce.colour_id', 'inner');
        $this->db->join('language l', 'ce.lang_id = l.lang_id', 'inner');

        return $this->commonGetList($className, $where, $option, 'ce.id, c.colour_id, c.status, ce.colour_name, ce.lang_id, l.lang_name');
    }
}
