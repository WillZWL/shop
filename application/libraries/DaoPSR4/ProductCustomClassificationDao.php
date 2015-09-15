<?php
namespace ESG\Panther\Dao;

class ProductCustomClassificationDao extends BaseDao
{
    private $tableName = "product_custom_classification";
    private $voClassName = "ProductCustomClassificationVo";

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

    public function getProductCustomClassList($where = [], $option = [], $classname = "ProductCustomClassDto")
    {
        $this->db->from("product_custom_classification pcc");
        $this->db->join("product AS p", "pcc.sku = p.sku", "INNER");
        $this->db->join("category AS sc", "sc.id = p.sub_cat_id", "INNER");

        return $this->commonGetList($classname, $where, $option, 'pcc.sku, p.name prod_name, sc.id sub_cat_id, sc.name sub_cat_name, pcc.country_id, pcc.code, pcc.description, pcc.duty_pcent, pcc.create_on, pcc.create_at, pcc.create_by, pcc.modify_on, pcc.modify_at, pcc.modify_by');

    }

    public function getAllProductCustomClassList($where = [], $option = [], $classname = "ProductCustomClassDto")
    {
        $where_clause = "";
        foreach ($where as $key => $value) {
            if (strpos(strtolower($key), "like") === true) {
                $where_clause .= " AND $key like " . $this->db->escape_like_str($value);
            } else {
                $where_clause .= " AND $key = " . $this->db->escape($value);
            }
        }

        $sql = <<<SQL
                    SELECT
                        pcc.sku, pcc.country_id, pcc.code, pcc.duty_pcent, pcc.description
                    FROM product_custom_classification pcc
                    WHERE
                        1=1
                         $where_clause
                    order by pcc.country_id asc
SQL;
        $query = $this->db->query($sql);
        if (!$query) {
            return FALSE;
        }
        $array = $query->result_array();
        return $array;
    }
}


