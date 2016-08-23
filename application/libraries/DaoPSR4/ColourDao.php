<?php
namespace ESG\Panther\Dao;

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

    public function getRemainColourList($prod_grp_cd)
    {
        $sql = "
                SELECT c.*
                FROM colour AS c
                LEFT JOIN product AS p
                ON c.id = p.colour_id AND p.prod_grp_cd = ?
                WHERE p.sku IS NULL AND c.status = 1
                ORDER BY c.id = 'NA' DESC
                ";


        if ($query = $this->db->query($sql, $prod_grp_cd)) {
            $rs = [];
            if ($query->num_rows() > 0) {
                foreach ($query->result($this->getVoClassname()) as $obj) {
                    $rs[] = $obj;
                }
                return (object)$rs;
            } else {
                return $rs;
            }
        } else {
            return FALSE;
        }
    }
}
