<?php
namespace ESG\Panther\Dao;

class CategoryMappingDao extends BaseDao
{
    private $tableName = "category_mapping";
    private $voClassname = "CategoryMappingVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getVoClassname()
    {
        return $this->voClassname;
    }

    public function getGooglebaseCatListWithCountry($where, $option, $classname = "CategoryMappingVo")
    {
        $this->db->from("category_mapping AS cm");
        $this->db->join("product AS p", "p.sku = cm.category_mapping_id", "LEFT");
        $this->db->join("ext_category_mapping AS ecm", "ecm.country_id = cm.country_id AND ecm.category_id = if(p.sub_sub_cat_id = 0, if(p.sub_cat_id = 0, p.cat_id, p.sub_cat_id), p.sub_sub_cat_id)", "LEFT");
        $this->db->join("external_category AS ec", "ec.id = ecm.ext_id", "LEFT");

        return $this->commonGetList($classname, $where, $option, 'cm.category_mapping_id, cm.country_id, cm.ext_id, ec.ext_name, cm.product_name');
    }

    public function getYandexCatBySubcatid($subcat_id, $lang_id = "ru", $country_id = "RU", $classname = "CategoryMappingVo")
    {
        $this->db->from("category_mapping AS cm");
        $this->db->join("external_category AS ec", "cm.status = 1 AND ec.status = 1 AND ec.id = cm.ext_id", "LEFT");
        $this->db->select("cm.category_mapping_id, cm.ext_id, cm.lang_id, cm.country_id, ec.ext_name");
        $this->db->where(["cm.lang_id" => $lang_id, "cm.country_id" => $country_id, "cm.ext_party" => "YANDEX"]);
        if ($subcat_id) {
            $this->db->where(["cm.category_mapping_id" => $subcat_id]);
        }
        $this->db->limit(1);

        $rs = [];
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $obj) {
                $rs = $obj;
            }
            return (object)$rs;
        }

        return false;
    }

    public function getExternalCatmapping($ext_party, $level, $subcat_id, $lang_id = "en", $country_id = "GB", $classname = "CategoryMappingVo")
    {
        $this->db->from("category_mapping AS cm");
        $this->db->join("external_category AS ec", " ec.id = cm.ext_id AND cm.level = $level AND ec.level = cm.level", "LEFT");
        $this->db->select("cm.category_mapping_id, ec.ext_id, cm.lang_id, cm.country_id, ec.ext_name");
        $this->db->where(["cm.lang_id" => $lang_id, "cm.country_id" => $country_id, "cm.ext_party" => $ext_party]);
        if ($subcat_id) {
            $this->db->where(["cm.category_mapping_id" => $subcat_id, "ec.status" => 1, "cm.status" => 1]);
        }
        $this->db->limit(1);

        $rs = [];
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


