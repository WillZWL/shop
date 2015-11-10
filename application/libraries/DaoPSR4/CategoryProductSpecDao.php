<?php
namespace ESG\Panther\Dao;

class CategoryProductSpecDao extends BaseDao
{
    private $tableName = "category_product_spec";
    private $voClassName = "CategoryProductSpecVo";

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

    public function getFullCpsList($cat_id, $classname = "FullCpsWithCatIdDto")
    {
        $sql =
            '
                SELECT psg.id AS psg_id, psg.name AS psg_name, ps.product_spec_id AS ps_func_id, ps.name AS ps_name, ps.unit_type_id, ut.name AS unit_type_name, cps.cat_id, cps.unit_id, u.unit_name, cps.priority, cps.status
                FROM product_spec ps
                LEFT JOIN category_product_spec cps
                    ON ps.product_spec_id = cps.ps_id AND cps.cat_id = ?
                LEFT JOIN product_spec_group psg
                    ON ps.psg_id = psg.id
                LEFT JOIN unit_type ut
                    ON ps.unit_type_id = ut.unit_type_id
                LEFT JOIN unit u
                    ON cps.unit_id = u.unit_id
                WHERE ps.status = 1
                ORDER BY psg.priority DESC, cps.status DESC, cps.priority DESC
            ';

        if ($query = $this->db->query($sql, $cat_id)) {
            $rs = $query->result($classname);
            return $rs;
        }
    }
}


