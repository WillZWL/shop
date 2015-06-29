<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Product_content_dao extends Base_dao
{
    private $table_name = "product_content";
    private $vo_class_name = "Product_content_vo";
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

    public function get_content_w_default($where = array(), $option = array())
    {
        $option["limit"] = 1;
        return $this->get_content_w_default_list($where, $option);
    }

    public function get_content_w_default_list($where = array(), $option = array(), $classname = "Product_content_w_ext_dto")
    {
        $select_str = "
                        p.sku AS prod_sku,
                        l.id,
                        IFNULL(NULLIF(pc.prod_name,''), IFNULL(NULLIF(pc_en.prod_name,''), p.name)) AS prod_name,
                        IFNULL(NULLIF(pc.short_desc,''), pc_en.short_desc) AS short_desc,
                        IFNULL(NULLIF(pc.contents,''), pc_en.contents) AS contents,
                        IFNULL(NULLIF(pc.keywords,''), pc_en.keywords) AS keywords,
                        IFNULL(NULLIF(pc.detail_desc,''), pc_en.detail_desc) AS detail_desc,
                        IFNULL(NULLIF(pc.extra_info,''), pc_en.detail_desc) AS extra_info,
                        IFNULL(NULLIF(pce.feature,''), pce_en.feature) AS feature,
                        IFNULL(NULLIF(pce.specification,''), pce_en.specification) AS specification,
                        IFNULL(NULLIF(pce.requirement,''), pce_en.requirement) AS requirement
                        ";
        $this->db->from('product AS p');
        $this->db->join('language AS l', '1', 'INNER');
        $this->db->join('product_content AS pc', 'p.sku = pc.prod_sku AND pc.lang_id = l.id', 'LEFT');
        $this->db->join('product_content AS pc_en', 'p.sku = pc_en.prod_sku AND pc_en.lang_id = "en"', 'LEFT');
        $this->db->join('product_content_extend AS pce', 'p.sku = pce.prod_sku AND pce.lang_id = l.id', 'LEFT');
        $this->db->join('product_content_extend AS pce_en', 'p.sku = pce_en.prod_sku AND pce_en.lang_id = "en"', 'LEFT');
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, $select_str);
    }
}

/* End of file product_content_dao.php */
/* Location: ./system/application/libraries/dao/Product_content_dao.php */