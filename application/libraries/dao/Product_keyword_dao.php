<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Product_keyword_dao extends Base_dao
{
    private $table_name="product_keyword";
    private $vo_class_name="Product_keyword_vo";
    private $seq_name="";
    private $seq_mapping_field="";

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

    public function get_product_keyword_arraylist($sku = "", $platform_id = "")
    {
        if(empty($sku) || empty($platform_id))
        {
            return false;
        }

        $this->db->select('pk.keyword');
        $this->db->from('product_keyword AS pk');
        $this->db->join('platform_biz_var AS pbv', 'pk.type = 1 AND pk.lang_id = pbv.language_id');
        $this->db->where(array('pk.sku'=>$sku, 'pbv.selling_platform_id'=>$platform_id));

        if($query = $this->db->get())
        {
            $ret = array();
            $result = $query->result_array();
            foreach($result as $row)
            {
                $ret[] = $row["keyword"];
            }
            return $ret;
        }
    }
}

/* End of file Product_keyword_dao.php */
/* Location: ./system/application/libraries/dao/Product_keyword_dao.php */