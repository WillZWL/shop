<?php

include_once 'Base_dao.php';

class Product_warranty_dao extends Base_dao
{
    private $table_name="product_warranty";
    private $vo_classname="Product_warranty_vo";
    private $seq_name="";
    private $seq_mapping_field="";

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

    public function get_sku_warranty($sku, $platform_id)
    {
        $this->db->from("product p");
        $this->db->join("product_warranty pw", "p.sku = pw.sku and pw.platform_id='{$platform_id}'", "LEFT");
        $this->db->select("COALESCE(pw.warranty_in_month, p.warranty_in_month)as warranty_in_month");
        $this->db->where(array("p.sku"=>$sku));
        $this->db->limit(1);

        $classname = "Product_warranty_vo";
        $this->include_vo($classname);
        if ($query = $this->db->get())
        {
            foreach ($query->result($classname) as $obj)
            {
                $rs[] = $obj;
            }
            return $rs[0];
        }
    }
}

/* End of file product_warranty_dao.php */
/* Location: ./app/libraries/dao/Product_warranty_dao.php */