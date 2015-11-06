<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Product_image_dao extends Base_dao
{
    private $table_name = "product_image";
    private $vo_class_name = "Product_image_vo";
    private $seq_name = "product_image";
    private $seq_mapping_field = "id";

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

	public function get_pending_images()
    {
        $sql = "select pi.id, pi.sku, pi.priority, pi.image, pi.alt_text, pi.image_saved, pi.vb_alt_text,
					(select min(pi2.priority) from product_image pi2 where pi2.sku = pi.sku ) as min_priority
				from product_image pi
				where pi.image_saved = 0 and pi.vb_alt_text <> '' and pi.vb_alt_text is not null
				  ";


        if ($query = $this->db->query($sql)) {
            foreach ($query->result() as $row) {
                $res[] = $row;
            }
            return $res;
        }
        return FALSE;
    }
}


