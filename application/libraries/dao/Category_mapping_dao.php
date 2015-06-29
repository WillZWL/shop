<?php

include_once 'Base_dao.php';

class Category_mapping_dao extends Base_dao
{
    private $table_name = "category_mapping";
    private $vo_classname = "Category_mapping_vo";
    private $seq_name = "";
    private $seq_mapping_field = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_table_name()
    {
        return $this->table_name;
    }

    public function get_vo_classname()
    {
        return $this->vo_classname;
    }

    public function get_seq_name()
    {
        return $this->seq_name;
    }

    public function get_seq_mapping_field()
    {
        return $this->seq_mapping_field;
    }

    public function get_googlebase_cat_list_w_country($where, $option, $classname = "category_mapping_vo")
    {
        $this->db->from("category_mapping AS cm");
        $this->db->join("product AS p", "p.sku = cm.id", "LEFT");
        $this->db->join("ext_category_mapping AS ecm", "ecm.country_id = cm.country_id AND ecm.category_id = if(p.sub_sub_cat_id = 0, if(p.sub_cat_id = 0, p.cat_id, p.sub_cat_id), p.sub_sub_cat_id)", "LEFT");
        $this->db->join("external_category AS ec", "ec.id = ecm.ext_id", "LEFT");
        $this->include_vo($classname);
        return $this->common_get_list($where, $option, $classname = "category_mapping_vo", 'cm.id, cm.country_id, cm.ext_id, ec.ext_name, cm.product_name');
    }

    public function get_yandex_cat_by_subcatid($subcat_id, $lang_id = "ru", $country_id = "RU", $classname = "category_mapping_vo")
    {
        $this->db->from("category_mapping AS cm");
        $this->db->join("external_category AS ec", "cm.status = 1 AND ec.status = 1 AND ec.id = cm.ext_id", "LEFT");
        $this->db->select("cm.id, cm.ext_id, cm.lang_id, cm.country_id, ec.ext_name");
        $this->db->where(array("cm.lang_id" => $lang_id, "cm.country_id" => $country_id, "cm.ext_party" => "YANDEX"));
        if ($subcat_id) {
            $this->db->where(array("cm.id" => $subcat_id));
        }
        $this->db->limit(1);

        $rs = array();
        $query = $this->db->get();
        // var_dump($this->db->last_query());die();

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $obj) {
                $rs = $obj;
            }
            return (object)$rs;
        }

        return false;
    }

    public function get_external_catmapping($ext_party, $level, $subcat_id, $lang_id = "en", $country_id = "GB", $classname = "category_mapping_vo")
    {
        // e.g. $ext_party = "CENEO"
        // $level: 0 = SKU / 1 = Category / Sub-Category

        // $cmstatus = 1;
        $this->db->from("category_mapping AS cm");
        $this->db->join("external_category AS ec", " ec.id = cm.ext_id AND cm.level = $level AND ec.level = cm.level", "LEFT");
        $this->db->select("cm.id, ec.ext_id, cm.lang_id, cm.country_id, ec.ext_name");
        $this->db->where(array("cm.lang_id" => $lang_id, "cm.country_id" => $country_id, "cm.ext_party" => $ext_party));
        if ($subcat_id) {
            $this->db->where(array("cm.id" => $subcat_id, "ec.status" => 1, "cm.status" => 1));
        }
        $this->db->limit(1);

        $rs = array();
        if ($query = $this->db->get()) {
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $obj) {
                    $rs = $obj;
                }
                return (object)$rs;
            }
        }

        return false;
    }

}


