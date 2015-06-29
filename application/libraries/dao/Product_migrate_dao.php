<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Product_migrate_dao extends Base_dao
{
    private $table_name="product";
    private $vo_class_name="Product_vo";
    private $seq_name="product";
    private $seq_mapping_field="sku";

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . 'helpers/object_helper.php');
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

    public function get_migrate_images()
    {
        $sql = "select ap.sku id, replace(p.prodfile, 'images/user/products/', '') file
                from atomdemo.product AP
                JOIN atommigrate.pgproducts p ON (ap.prod_grp_cd = p.id)
                JOIN atommigrate.m_product mp ON (p.id = mp.prod_id)";

        $result = $this->db->query($sql);

        return $result->result_array();
    }

    public function update_image($sku, $ext)
    {
        $sql = "UPDATE atommigrate.product p set image = '$ext' WHERE sku = '$sku' LIMIT 1";

        $result = $this->db->query($sql);
    }
}



/* End of file product_dao.php */
/* Location: ./system/application/libraries/dao/Product_dao.php */
