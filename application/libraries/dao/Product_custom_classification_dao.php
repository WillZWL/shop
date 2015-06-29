<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Product_custom_classification_dao extends Base_dao
{
    private $table_name = "product_custom_classification";
    private $vo_class_name = "Product_custom_classification_vo";
    private $seq_name = "";
    private $seq_mapping_field = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_vo_classname()
    {
        return $this->vo_class_name;
    }

    public function get_table_name()
    {
        return $this->table_name;
    }

    public function get_seq_name()
    {
        return $this->seq_name;
    }

    public function get_seq_mapping_field()
    {
        return $this->seq_mapping_field;
    }

    public function get_pcc_list($where = array(), $option = array(), $classname = "product_custom_class_dto")
    {
        $this->db->from("product_custom_classification pcc");
        $this->db->join("product AS p", "pcc.sku = p.sku", "INNER");
        $this->db->join("category AS sc", "sc.id = p.sub_cat_id", "INNER");
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'pcc.sku, p.name prod_name, sc.id sub_cat_id, sc.name sub_cat_name, pcc.country_id, pcc.code, pcc.description, pcc.duty_pcent, pcc.create_on, pcc.create_at, pcc.create_by, pcc.modify_on, pcc.modify_at, pcc.modify_by');

    }

    public function get_all_pcc_list($where = array(), $option = array(), $classname = "product_custom_class_dto")
    {
        $where_clause = "";
        foreach ($where as $key => $value) {
            if (strpos(strtolower($key), "like") === true) {
                # if key contains "LIKE", then escape differently
                $where_clause .= " AND $key like " . $this->db->escape_like_str($value);
                // $where_clause .= " AND $key '$value'";
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


